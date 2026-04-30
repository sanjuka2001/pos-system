<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    private function getDateRange(Request $request): array
    {
        $start = Carbon::parse($request->input('start_date', now()->format('Y-m-d')))->startOfDay();
        $end = Carbon::parse($request->input('end_date', now()->format('Y-m-d')))->endOfDay();
        return [$start, $end];
    }

    private function getReportData(string $type, $start, $end): array
    {
        return match ($type) {
            'profit-loss' => $this->profitLossData($start, $end),
            'daily-sales' => $this->dailySalesData($start, $end),
            'top-products' => $this->topProductsData($start, $end),
            'by-category' => $this->byCategoryData($start, $end),
            'cashier-performance' => $this->cashierData($start, $end),
            'tax' => $this->taxData($start, $end),
            'inventory-value' => $this->inventoryData(),
            default => ['title' => 'Report', 'headers' => [], 'rows' => [], 'totals' => []],
        };
    }

    public function exportPdf(Request $request)
    {
        $type = $request->input('type', 'profit-loss');
        [$start, $end] = $this->getDateRange($request);
        $data = $this->getReportData($type, $start, $end);
        $data['startDate'] = $start->format('M d, Y');
        $data['endDate'] = $end->format('M d, Y');
        $data['generatedAt'] = now()->format('M d, Y h:i A');

        $pdf = Pdf::loadView('exports.report-pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download($type . '-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $type = $request->input('type', 'profit-loss');
        [$start, $end] = $this->getDateRange($request);
        $data = $this->getReportData($type, $start, $end);

        $filename = $type . '-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [$data['title']]);
            fputcsv($file, ['Generated: ' . now()->format('M d, Y h:i A')]);
            fputcsv($file, []);
            fputcsv($file, $data['headers']);
            foreach ($data['rows'] as $row) {
                fputcsv($file, $row);
            }
            if (!empty($data['totals'])) {
                fputcsv($file, []);
                fputcsv($file, $data['totals']);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function profitLossData($start, $end): array
    {
        $rows = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(grand_total) as sales, SUM(discount) as discount, SUM(tax) as vat')
            ->groupBy('date')->orderBy('date')->get();

        $tableRows = [];
        $totals = [0, 0, 0, 0, 0];
        foreach ($rows as $row) {
            $cogs = OrderItem::whereHas('order', fn($q) => $q->where('status', 'completed')->whereDate('created_at', $row->date))
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->selectRaw('SUM(order_items.quantity * products.cost_price) as c')->value('c') ?? 0;
            $profit = (float)$row->sales - (float)$cogs;
            $tableRows[] = [$row->date, $row->orders, number_format($row->sales, 2), number_format($row->discount, 2), number_format($row->vat, 2), number_format($profit, 2)];
            $totals[0] += $row->orders; $totals[1] += $row->sales; $totals[2] += $row->discount; $totals[3] += $row->vat; $totals[4] += $profit;
        }

        return [
            'title' => 'Profit & Loss Report',
            'headers' => ['Date', 'Orders', 'Gross Sales', 'Discount', 'VAT', 'Net Profit'],
            'rows' => $tableRows,
            'totals' => ['TOTALS', $totals[0], number_format($totals[1], 2), number_format($totals[2], 2), number_format($totals[3], 2), number_format($totals[4], 2)],
        ];
    }

    private function dailySalesData($start, $end): array
    {
        $rows = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(grand_total) as total, SUM(tax) as vat, SUM(grand_total)-SUM(tax) as net')
            ->groupBy('date')->orderBy('date')->get();

        $tableRows = [];
        foreach ($rows as $r) {
            $tableRows[] = [$r->date, $r->orders, number_format($r->total, 2), number_format($r->vat, 2), number_format($r->net, 2)];
        }

        return [
            'title' => 'Daily Sales Summary',
            'headers' => ['Date', 'Orders', 'Total Amount', 'VAT', 'Net'],
            'rows' => $tableRows,
            'totals' => ['TOTALS', $rows->sum('orders'), number_format($rows->sum('total'), 2), number_format($rows->sum('vat'), 2), number_format($rows->sum('net'), 2)],
        ];
    }

    private function topProductsData($start, $end): array
    {
        $rows = OrderItem::whereHas('order', fn($q) => $q->where('status', 'completed')->whereBetween('created_at', [$start, $end]))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('products.name as pname, categories.name as cname, SUM(order_items.quantity) as qty, SUM(order_items.subtotal) as rev, SUM(order_items.quantity*(products.price-products.cost_price)) as profit')
            ->groupBy('products.id', 'products.name', 'categories.name')
            ->orderByDesc('qty')->limit(10)->get();

        $tableRows = [];
        foreach ($rows as $r) {
            $tableRows[] = [$r->pname, $r->cname, $r->qty, number_format($r->rev, 2), number_format($r->profit, 2)];
        }

        return [
            'title' => 'Top Selling Products',
            'headers' => ['Product', 'Category', 'Qty Sold', 'Revenue', 'Profit'],
            'rows' => $tableRows,
            'totals' => [],
        ];
    }

    private function byCategoryData($start, $end): array
    {
        $totalRev = OrderItem::whereHas('order', fn($q) => $q->where('status', 'completed')->whereBetween('created_at', [$start, $end]))->sum('subtotal');
        $rows = OrderItem::whereHas('order', fn($q) => $q->where('status', 'completed')->whereBetween('created_at', [$start, $end]))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as cname, COUNT(DISTINCT order_items.order_id) as orders, SUM(order_items.subtotal) as rev')
            ->groupBy('categories.id', 'categories.name')->orderByDesc('rev')->get();

        $tableRows = [];
        foreach ($rows as $r) {
            $pct = $totalRev > 0 ? round(($r->rev / $totalRev) * 100, 1) : 0;
            $tableRows[] = [$r->cname, $r->orders, number_format($r->rev, 2), $pct . '%'];
        }

        return [
            'title' => 'Sales by Category',
            'headers' => ['Category', 'Orders', 'Revenue', '% Share'],
            'rows' => $tableRows,
            'totals' => [],
        ];
    }

    private function cashierData($start, $end): array
    {
        $rows = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])->whereNotNull('user_id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->selectRaw('users.name as uname, COUNT(*) as orders, SUM(orders.grand_total) as sales, AVG(orders.grand_total) as avg_val')
            ->groupBy('users.id', 'users.name')->orderByDesc('sales')->get();

        $tableRows = [];
        foreach ($rows as $r) {
            $tableRows[] = [$r->uname, $r->orders, number_format($r->sales, 2), number_format($r->avg_val, 2)];
        }

        return [
            'title' => 'Cashier Performance Report',
            'headers' => ['Cashier', 'Orders', 'Total Sales', 'Avg Order Value'],
            'rows' => $tableRows,
            'totals' => ['TOTALS', $rows->sum('orders'), number_format($rows->sum('sales'), 2), '—'],
        ];
    }

    private function taxData($start, $end): array
    {
        $rows = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as period, SUM(grand_total) as sales, SUM(tax) as vat')
            ->groupByRaw('DATE(created_at)')->orderByRaw('MIN(created_at)')->get();

        $tableRows = [];
        foreach ($rows as $r) {
            $tableRows[] = [$r->period, number_format($r->sales, 2), '10%', number_format($r->vat, 2)];
        }

        return [
            'title' => 'Tax Report',
            'headers' => ['Period', 'Total Sales', 'VAT Rate', 'VAT Collected'],
            'rows' => $tableRows,
            'totals' => ['TOTALS', number_format($rows->sum('sales'), 2), '—', number_format($rows->sum('vat'), 2)],
        ];
    }

    private function inventoryData(): array
    {
        $products = Product::with('category')->orderBy('name')->get();
        $tableRows = [];
        $totalValue = 0;
        foreach ($products as $p) {
            $val = $p->stock * $p->cost_price;
            $totalValue += $val;
            $status = $p->stock <= 0 ? 'OUT OF STOCK' : ($p->stock < 10 ? 'LOW' : 'OK');
            $tableRows[] = [$p->name, $p->category->name ?? '—', $p->stock, number_format($p->price, 2), number_format($p->cost_price, 2), number_format($val, 2), $status];
        }

        return [
            'title' => 'Inventory Value Report',
            'headers' => ['Product', 'Category', 'Stock', 'Unit Price', 'Cost Price', 'Stock Value', 'Status'],
            'rows' => $tableRows,
            'totals' => ['TOTAL INVENTORY VALUE', '', '', '', '', number_format($totalValue, 2), ''],
        ];
    }
}
