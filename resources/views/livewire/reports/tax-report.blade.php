<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Tax Report</h3>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">VAT collected per period for accounting</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex bg-gray-100 dark:bg-slate-800 rounded-lg p-0.5">
                @foreach(['day' => 'Daily', 'week' => 'Weekly', 'month' => 'Monthly'] as $key => $label)
                    <button wire:click="setGroupBy('{{ $key }}')"
                        class="px-3 py-1 rounded-md text-xs font-semibold transition-all {{ $groupBy === $key ? 'bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.pdf' : 'manager.reports.export.pdf') }}" target="_blank">
                @csrf
                <input type="hidden" name="type" value="tax">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all">PDF</button>
            </form>
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.excel' : 'manager.reports.export.excel') }}">
                @csrf
                <input type="hidden" name="type" value="tax">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all">Excel</button>
            </form>
        </div>
    </div>
    @if(count($reportData) > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-slate-800/40">
                    <th class="text-left px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Period</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Total Sales</th>
                    <th class="text-center px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">VAT Rate</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">VAT Collected</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                @foreach($reportData as $row)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-4 py-3 text-xs font-semibold text-gray-900 dark:text-white">{{ $row->period }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono text-gray-700 dark:text-slate-300">LKR {{ number_format($row->total_sales, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-center"><span class="px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-bold">10%</span></td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-semibold text-red-600 dark:text-red-400">LKR {{ number_format($row->vat_collected, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 dark:bg-slate-800/60 border-t-2 border-gray-200 dark:border-slate-700">
                    <td class="px-4 py-3 text-xs font-bold text-gray-900 dark:text-white">TOTALS</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-gray-900 dark:text-white">LKR {{ number_format($reportData->sum('total_sales'), 2) }}</td>
                    <td class="px-4 py-3 text-xs text-center font-bold text-gray-400">—</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-red-600 dark:text-red-400">LKR {{ number_format($reportData->sum('vat_collected'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
        <p class="text-sm text-gray-500 dark:text-slate-400">No tax data for the selected period</p>
    </div>
    @endif
</div>
