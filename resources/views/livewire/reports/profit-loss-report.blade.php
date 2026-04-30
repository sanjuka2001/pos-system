<div>
    {{-- Export Buttons --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Profit & Loss Report</h3>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Daily breakdown of sales, discounts, taxes, and net profit</p>
        </div>
        <div class="flex gap-2">
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.pdf' : 'manager.reports.export.pdf') }}" target="_blank">
                @csrf
                <input type="hidden" name="type" value="profit-loss">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                </button>
            </form>
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.excel' : 'manager.reports.export.excel') }}">
                @csrf
                <input type="hidden" name="type" value="profit-loss">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel
                </button>
            </form>
        </div>
    </div>

    {{-- Chart --}}
    @if(count($chartData['labels'] ?? []) > 0)
    <div class="bg-gray-50 dark:bg-slate-800/40 rounded-xl p-4 mb-4" wire:ignore>
        <canvas id="profitLossChart" height="80"></canvas>
    </div>
    @endif

    {{-- Data Table --}}
    @if(count($reportData) > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-slate-800/40">
                    <th class="text-left px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Date</th>
                    <th class="text-center px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Total Orders</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Gross Sales</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Discount</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Net Sales</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">VAT</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Net Profit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                @foreach($reportData as $row)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-4 py-3 text-xs font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($row->date)->format('D, M d Y') }}</td>
                    <td class="px-4 py-3 text-xs text-center text-gray-700 dark:text-slate-300">{{ $row->total_orders }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono text-gray-700 dark:text-slate-300">LKR {{ number_format($row->gross_sales, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono text-orange-600 dark:text-orange-400">LKR {{ number_format($row->total_discount, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-semibold text-blue-600 dark:text-blue-400">LKR {{ number_format($row->net_sales, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono text-red-600 dark:text-red-400">LKR {{ number_format($row->vat_collected, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-semibold {{ $row->net_profit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">LKR {{ number_format($row->net_profit, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 dark:bg-slate-800/60 border-t-2 border-gray-200 dark:border-slate-700">
                    <td class="px-4 py-3 text-xs font-bold text-gray-900 dark:text-white">TOTALS</td>
                    <td class="px-4 py-3 text-xs text-center font-bold text-gray-900 dark:text-white">{{ $reportData->sum('total_orders') }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-gray-900 dark:text-white">LKR {{ number_format($reportData->sum('gross_sales'), 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-orange-600 dark:text-orange-400">LKR {{ number_format($reportData->sum('total_discount'), 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-blue-600 dark:text-blue-400">LKR {{ number_format($reportData->sum('net_sales'), 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-red-600 dark:text-red-400">LKR {{ number_format($reportData->sum('vat_collected'), 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">LKR {{ number_format($reportData->sum('net_profit'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p class="text-sm text-gray-500 dark:text-slate-400">No data available for the selected period</p>
    </div>
    @endif
</div>

@script
<script>
    $wire.on('dateRangeChanged', () => {
        setTimeout(() => renderPLChart(), 300);
    });

    function renderPLChart() {
        const chartData = $wire.chartData;
        if (!chartData || !chartData.labels || chartData.labels.length === 0) return;

        const ctx = document.getElementById('profitLossChart');
        if (!ctx) return;

        if (window._plChart) window._plChart.destroy();

        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(148, 163, 184, 0.1)' : 'rgba(0, 0, 0, 0.06)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        window._plChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Gross Sales',
                        data: chartData.sales,
                        backgroundColor: isDark ? 'rgba(99, 102, 241, 0.6)' : 'rgba(99, 102, 241, 0.7)',
                        borderRadius: 6,
                        borderSkipped: false,
                    },
                    {
                        label: 'Net Profit',
                        data: chartData.profit,
                        backgroundColor: isDark ? 'rgba(16, 185, 129, 0.6)' : 'rgba(16, 185, 129, 0.7)',
                        borderRadius: 6,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { labels: { color: textColor, font: { size: 11, family: 'Inter' } } },
                    tooltip: { backgroundColor: isDark ? '#1e293b' : '#fff', titleColor: isDark ? '#f8fafc' : '#1e293b', bodyColor: isDark ? '#94a3b8' : '#64748b', borderColor: isDark ? '#334155' : '#e2e8f0', borderWidth: 1, padding: 10, cornerRadius: 8 }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: textColor, font: { size: 10, family: 'Inter' } } },
                    y: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 10, family: 'Inter' }, callback: v => 'LKR ' + v.toLocaleString() } }
                }
            }
        });
    }

    document.addEventListener('livewire:navigated', () => setTimeout(renderPLChart, 200));
    setTimeout(renderPLChart, 500);
</script>
@endscript
