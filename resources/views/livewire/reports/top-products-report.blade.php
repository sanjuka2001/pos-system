<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Top Selling Products</h3>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Products ranked by quantity sold</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="toggleShowAll" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 hover:bg-violet-100 dark:hover:bg-violet-500/20 transition-all">
                {{ $showAll ? 'Show Top 10' : 'Show All' }}
            </button>
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.pdf' : 'manager.reports.export.pdf') }}" target="_blank">
                @csrf
                <input type="hidden" name="type" value="top-products">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                </button>
            </form>
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.excel' : 'manager.reports.export.excel') }}">
                @csrf
                <input type="hidden" name="type" value="top-products">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Excel
                </button>
            </form>
        </div>
    </div>
    @if(count($reportData) > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-slate-800/40">
                    <th class="text-left px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">#</th>
                    <th class="text-left px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Product</th>
                    <th class="text-left px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Category</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Qty Sold</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Revenue</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Profit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                @foreach($reportData as $i => $row)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-4 py-3 text-xs text-gray-400 dark:text-slate-500">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 text-xs font-semibold text-gray-900 dark:text-white">{{ $row->product_name }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500 dark:text-slate-400">{{ $row->category_name }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-semibold text-gray-900 dark:text-white">{{ number_format($row->total_qty) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono text-gray-700 dark:text-slate-300">LKR {{ number_format($row->total_revenue, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-semibold text-emerald-600 dark:text-emerald-400">LKR {{ number_format($row->total_profit, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        <p class="text-sm text-gray-500 dark:text-slate-400">No product sales data for the selected period</p>
    </div>
    @endif
</div>
