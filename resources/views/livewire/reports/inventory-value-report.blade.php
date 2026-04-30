<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Inventory Value Report</h3>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Current stock value snapshot</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-3 py-1.5 rounded-lg bg-violet-50 dark:bg-violet-500/10 border border-violet-100 dark:border-violet-500/20">
                <span class="text-[10px] font-semibold text-violet-600 dark:text-violet-400 uppercase tracking-wider">Total Value:</span>
                <span class="text-sm font-bold text-violet-700 dark:text-violet-300 ml-1 font-mono">LKR {{ number_format($totalInventoryValue, 2) }}</span>
            </div>
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.pdf' : 'manager.reports.export.pdf') }}" target="_blank">
                @csrf
                <input type="hidden" name="type" value="inventory-value">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all">PDF</button>
            </form>
            <form method="POST" action="{{ route(auth()->user()->role === 'admin' ? 'admin.reports.export.excel' : 'manager.reports.export.excel') }}">
                @csrf
                <input type="hidden" name="type" value="inventory-value">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all">Excel</button>
            </form>
        </div>
    </div>
    @if(count($reportData) > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 dark:border-slate-800/40">
                    <th class="text-left px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Product</th>
                    <th class="text-left px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Category</th>
                    <th class="text-center px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Stock</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Unit Price</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Cost Price</th>
                    <th class="text-right px-4 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Stock Value</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                @foreach($reportData as $product)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors {{ $product->is_out_of_stock ? 'bg-red-50/50 dark:bg-red-500/5' : ($product->is_low_stock ? 'bg-amber-50/50 dark:bg-amber-500/5' : '') }}">
                    <td class="px-4 py-3 text-xs font-semibold text-gray-900 dark:text-white">{{ $product->name }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500 dark:text-slate-400">{{ $product->category->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-xs text-center">
                        @if($product->is_out_of_stock)
                            <span class="px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 text-[10px] font-bold">OUT</span>
                        @elseif($product->is_low_stock)
                            <span class="px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[10px] font-bold">{{ $product->stock }}</span>
                        @else
                            <span class="font-mono text-gray-700 dark:text-slate-300">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-right font-mono text-gray-700 dark:text-slate-300">LKR {{ number_format($product->price, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono text-gray-700 dark:text-slate-300">LKR {{ number_format($product->cost_price, 2) }}</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-semibold text-gray-900 dark:text-white">LKR {{ number_format($product->stock_value, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 dark:bg-slate-800/60 border-t-2 border-gray-200 dark:border-slate-700">
                    <td colspan="5" class="px-4 py-3 text-xs font-bold text-gray-900 dark:text-white">TOTAL INVENTORY VALUE</td>
                    <td class="px-4 py-3 text-xs text-right font-mono font-bold text-violet-600 dark:text-violet-400">LKR {{ number_format($totalInventoryValue, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    {{-- Legend --}}
    <div class="flex items-center gap-4 mt-3 px-4">
        <span class="flex items-center gap-1.5 text-[10px] text-gray-500 dark:text-slate-400"><span class="w-2.5 h-2.5 rounded-sm bg-red-100 dark:bg-red-500/20 border border-red-200 dark:border-red-500/30"></span> Out of Stock</span>
        <span class="flex items-center gap-1.5 text-[10px] text-gray-500 dark:text-slate-400"><span class="w-2.5 h-2.5 rounded-sm bg-amber-100 dark:bg-amber-500/20 border border-amber-200 dark:border-amber-500/30"></span> Low Stock (&lt;10)</span>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        <p class="text-sm text-gray-500 dark:text-slate-400">No inventory data available</p>
    </div>
    @endif
</div>
