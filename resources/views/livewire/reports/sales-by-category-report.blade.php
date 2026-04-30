<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Sales by Category</h3>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Revenue breakdown per product category</p>
        </div>
        <div class="flex gap-2">
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.pdf' : 'manager.reports.export.pdf') }}" target="_blank">
                @csrf
                <input type="hidden" name="type" value="by-category">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all flex items-center gap-1.5">PDF</button>
            </form>
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.excel' : 'manager.reports.export.excel') }}">
                @csrf
                <input type="hidden" name="type" value="by-category">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all flex items-center gap-1.5">Excel</button>
            </form>
        </div>
    </div>

    @if(count($reportData) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Donut Chart --}}
        <div class="bg-gray-50 dark:bg-slate-800/40 rounded-xl p-4" wire:ignore>
            <canvas id="categoryChart" height="200"></canvas>
        </div>
        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-slate-800/40">
                        <th class="text-left px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Category</th>
                        <th class="text-center px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Orders</th>
                        <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Revenue</th>
                        <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">% Share</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                    @foreach($reportData as $row)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-4 py-3 text-xs font-semibold text-gray-900 dark:text-white">{{ $row->category_name }}</td>
                        <td class="px-4 py-3 text-xs text-center text-gray-700 dark:text-slate-300">{{ $row->total_orders }}</td>
                        <td class="px-4 py-3 text-xs text-right font-mono text-gray-700 dark:text-slate-300">LKR {{ number_format($row->total_revenue, 2) }}</td>
                        <td class="px-4 py-3 text-xs text-right">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="font-mono font-semibold text-gray-900 dark:text-white">{{ $row->percentage }}%</span>
                                <div class="w-16 h-1.5 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-violet-500 rounded-full" style="width: {{ $row->percentage }}%"></div>
                                </div>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>
        <p class="text-sm text-gray-500 dark:text-slate-400">No category data for the selected period</p>
    </div>
    @endif
</div>

@script
<script>
    function renderCategoryChart() {
        const chartData = $wire.chartData;
        if (!chartData || !chartData.labels || chartData.labels.length === 0) return;
        const ctx = document.getElementById('categoryChart');
        if (!ctx) return;
        if (window._catChart) window._catChart.destroy();
        const isDark = document.documentElement.classList.contains('dark');
        const colors = ['#8b5cf6','#3b82f6','#10b981','#f59e0b','#ef4444','#ec4899','#06b6d4','#84cc16'];
        window._catChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.values,
                    backgroundColor: colors.slice(0, chartData.labels.length),
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: isDark ? '#94a3b8' : '#64748b', font: { size: 11, family: 'Inter' }, padding: 16, usePointStyle: true, pointStyleWidth: 8 } },
                    tooltip: { backgroundColor: isDark ? '#1e293b' : '#fff', titleColor: isDark ? '#f8fafc' : '#1e293b', bodyColor: isDark ? '#94a3b8' : '#64748b', borderColor: isDark ? '#334155' : '#e2e8f0', borderWidth: 1, padding: 10, cornerRadius: 8 }
                }
            }
        });
    }
    setTimeout(renderCategoryChart, 500);
</script>
@endscript
