<?php

use App\Livewire\CashierTerminal;
use App\Livewire\Admin\InventoryDashboard;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportExportController;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // ═══════════════════════════════════════════════════════════
    // CASHIER ROUTES — accessible by cashier, manager, admin
    // ═══════════════════════════════════════════════════════════
    Route::middleware(['role:cashier,manager,admin'])->group(function () {
        Route::get('/cashier/dashboard', function () {
            return view('cashier.dashboard');
        })->name('cashier.dashboard');

        Route::get('/cashier', CashierTerminal::class)->name('cashier');

        // Product search API for cashier dropdown
        Route::get('/cashier/search-products', function (Request $request) {
            $term = trim($request->get('q', ''));
            if (strlen($term) === 0) {
                return response()->json([]);
            }

            $products = Product::where(function ($q) use ($term) {
                    $q->where('name', 'like', '%' . $term . '%')
                      ->orWhere('barcode', 'like', '%' . $term . '%');
                })
                ->orderBy('name')
                ->take(8)
                ->get()
                ->map(fn ($p) => [
                    'id'       => $p->id,
                    'barcode'  => $p->barcode,
                    'name'     => $p->name,
                    'price'    => (float) $p->price,
                    'stock'    => $p->stock,
                    'weight'   => '',
                    'category' => $p->category?->name ?? '',
                ]);

            return response()->json($products);
        })->name('cashier.search-products');

        // Place order — saves order + items to database and decrements stock
        Route::post('/cashier/place-order', function (Request $request) {
            $data = $request->validate([
                'receipt_no'      => 'required|string',
                'items'           => 'required|array|min:1',
                'items.*.id'      => 'required|integer|exists:products,id',
                'items.*.qty'     => 'required|integer|min:1',
                'items.*.price'   => 'required|numeric|min:0',
                'subtotal'        => 'required|numeric|min:0',
                'discount'        => 'nullable|numeric|min:0',
                'discount_type'   => 'nullable|string|in:percentage,fixed',
                'discount_value'  => 'nullable|numeric|min:0',
                'discount_reason' => 'nullable|string|max:500',
                'tax'             => 'required|numeric|min:0',
                'grand_total'     => 'required|numeric|min:0',
                'payment_method'  => 'required|string|in:cash,card,mobile',
                'note'            => 'nullable|string',
            ]);

            // Create the order
            $order = Order::create([
                'user_id'         => auth()->id(),
                'receipt_no'      => $data['receipt_no'],
                'subtotal'        => $data['subtotal'],
                'discount'        => $data['discount'] ?? 0,
                'discount_type'   => $data['discount_type'] ?? null,
                'discount_value'  => $data['discount_value'] ?? 0,
                'discount_reason' => $data['discount_reason'] ?? null,
                'tax'             => $data['tax'],
                'grand_total'     => $data['grand_total'],
                'payment_method'  => $data['payment_method'],
                'note'            => $data['note'] ?? null,
                'status'          => 'completed',
            ]);

            // Create order items and decrement stock
            foreach ($data['items'] as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['price'] * $item['qty'],
                ]);

                // Decrement product stock
                Product::where('id', $item['id'])
                    ->where('stock', '>=', $item['qty'])
                    ->decrement('stock', $item['qty']);
            }

            return response()->json([
                'success'  => true,
                'order_id' => $order->id,
                'message'  => 'Order placed successfully',
            ]);
        })->name('cashier.place-order');
    });

    // ═══════════════════════════════════════════════════════════
    // ADMIN ROUTES — admin only (role:admin)
    // ═══════════════════════════════════════════════════════════
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/inventory', InventoryDashboard::class)->name('admin.inventory');
        Route::get('/reports', \App\Livewire\Reports\ReportsDashboard::class)->name('admin.reports');
        Route::get('/staff', \App\Livewire\Admin\StaffManagement::class)->name('admin.staff');
        Route::get('/branches', \App\Livewire\Admin\BranchManagement::class)->name('admin.branches');
        Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('admin.settings');

        // Report exports
        Route::post('/reports/export/pdf', [ReportExportController::class, 'exportPdf'])->name('admin.reports.export.pdf');
        Route::post('/reports/export/excel', [ReportExportController::class, 'exportExcel'])->name('admin.reports.export.excel');

        // Admin stats API for real-time dashboard polling
        Route::get('/stats', function () {
            $monthSales = OrderItem::whereHas('order', function ($q) {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            })->sum('subtotal');

            if ($monthSales >= 1000000) {
                $salesFormatted = 'LKR ' . number_format($monthSales / 1000000, 1) . 'M';
            } elseif ($monthSales >= 1000) {
                $salesFormatted = 'LKR ' . number_format($monthSales / 1000, 1) . 'K';
            } else {
                $salesFormatted = 'LKR ' . number_format($monthSales, 2);
            }

            return response()->json([
                'totalUsers'      => User::count(),
                'totalProducts'   => Product::count(),
                'productsInStock' => Product::where('stock', '>', 0)->count(),
                'totalOrders'     => Order::count(),
                'todayOrders'     => Order::whereDate('created_at', today())->count(),
                'monthSales'      => $salesFormatted,
                'lowStockCount'   => Product::where('stock', '<', 10)->where('stock', '>', 0)->count(),
                'outOfStockCount' => Product::where('stock', '<=', 0)->count(),
            ]);
        })->name('admin.stats');
    });

    // ═══════════════════════════════════════════════════════════
    // MANAGER ROUTES — manager only (role:manager)
    // Admin can also access these via the admin middleware above.
    // ═══════════════════════════════════════════════════════════
    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', function () {
            return view('manager.dashboard');
        })->name('manager.dashboard');

        Route::get('/inventory', InventoryDashboard::class)->name('manager.inventory');
        Route::get('/reports', \App\Livewire\Reports\ReportsDashboard::class)->name('manager.reports');
        Route::get('/staff', \App\Livewire\Admin\StaffManagement::class)->name('manager.staff');

        // Report exports for manager
        Route::post('/reports/export/pdf', [ReportExportController::class, 'exportPdf'])->name('manager.reports.export.pdf');
        Route::post('/reports/export/excel', [ReportExportController::class, 'exportExcel'])->name('manager.reports.export.excel');
    });

});
