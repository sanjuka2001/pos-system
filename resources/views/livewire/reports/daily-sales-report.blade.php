<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Daily Sales Summary</h3>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Total sales grouped by day</p>
        </div>
        <div class="flex gap-2">
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.pdf' : 'manager.reports.export.pdf') }}" target="_blank">
                @csrf
                <input type="hidden" name="type" value="daily-sales">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                </button>
            </form>
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.excel' : 'manager.reports.export.excel') }}">
                @csrf
                <input type="hidden" name="type" value="daily-sales">
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
                    <th class="text-left px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">
                        <button wire:click="toggleSort" class="flex items-center gap-1 hover:text-gray-900 dark:hover:text-white transition-colors">
                            Date
                            <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }} transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        </button>
                    </th>
                    <th class="text-center px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Orders</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Total Amount</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">VAT</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Net</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                @foreach($reportData as $row)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-4 py-3 text-xs font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($row->date)->format('D, M d Y') }}</td>
                    <td class="px-4 py-3 text-xs text-center text-gray-700 dark:text-slate-300">{{ $row->num_orders }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono text-gray-700 dark:text-slate-300">LKR {{ number_format($row->total_amount, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono text-red-600 dark:text-red-400">LKR {{ number_format($row->total_vat, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-semibold text-emerald-600 dark:text-emerald-400">LKR {{ number_format($row->net_amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 dark:bg-slate-800/60 border-t-2 border-gray-200 dark:border-slate-700">
                    <td class="px-4 py-3 text-xs font-bold text-gray-900 dark:text-white">TOTALS</td>
                    <td class="px-4 py-3 text-xs text-center font-bold text-gray-900 dark:text-white">{{ $reportData->sum('num_orders') }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-gray-900 dark:text-white">LKR {{ number_format($reportData->sum('total_amount'), 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-red-600 dark:text-red-400">LKR {{ number_format($reportData->sum('total_vat'), 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">LKR {{ number_format($reportData->sum('net_amount'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="text-sm text-gray-500 dark:text-slate-400">No sales data for the selected period</p>
    </div>
    @endif
</div>
