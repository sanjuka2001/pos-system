<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Cashier Performance</h3>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Sales performance per cashier</p>
        </div>
        <div class="flex gap-2">
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.pdf' : 'manager.reports.export.pdf') }}" target="_blank">
                @csrf
                <input type="hidden" name="type" value="cashier-performance">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all flex items-center gap-1.5">PDF</button>
            </form>
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.excel' : 'manager.reports.export.excel') }}">
                @csrf
                <input type="hidden" name="type" value="cashier-performance">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all flex items-center gap-1.5">Excel</button>
            </form>
        </div>
    </div>
    @if(count($reportData) > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-slate-800/40">
                    <th class="text-left px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Cashier</th>
                    <th class="text-center px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Orders Handled</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Total Sales</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Avg Order Value</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                @foreach($reportData as $row)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 bg-violet-50 dark:bg-violet-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-violet-600 dark:text-violet-400">{{ strtoupper(substr($row->cashier_name, 0, 1)) }}</span>
                            </div>
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $row->cashier_name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs text-center font-mono text-gray-700 dark:text-slate-300">{{ $row->total_orders }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-semibold text-gray-900 dark:text-white">LKR {{ number_format($row->total_sales, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono text-gray-700 dark:text-slate-300">LKR {{ number_format($row->avg_order_value, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 dark:bg-slate-800/60 border-t-2 border-gray-200 dark:border-slate-700">
                    <td class="px-4 py-3 text-xs font-bold text-gray-900 dark:text-white">TOTALS</td>
                    <td class="px-4 py-3 text-xs text-center font-bold text-gray-900 dark:text-white">{{ $reportData->sum('total_orders') }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-gray-900 dark:text-white">LKR {{ number_format($reportData->sum('total_sales'), 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-gray-700 dark:text-slate-300">—</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <p class="text-sm text-gray-500 dark:text-slate-400">No cashier data for the selected period</p>
    </div>
    @endif
</div>
