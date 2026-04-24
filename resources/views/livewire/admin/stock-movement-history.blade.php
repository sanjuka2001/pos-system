<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

    {{-- TOP NAV --}}
    <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-gray-200/60 dark:border-slate-800/60 px-6 py-4 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">Stock Movement History</h1>
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">All stock changes</p>
                </div>
            </div>
            <a href="{{ route('admin.inventory') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Inventory
            </a>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5">
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono">{{ $this->summary['total_movements'] }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">Total Movements</p>
            </div>
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5">
                <p class="text-2xl font-bold text-red-600 dark:text-red-400 font-mono">{{ $this->summary['sales'] }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">Sales</p>
            </div>
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5">
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 font-mono">{{ $this->summary['restocks'] }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">Restocks</p>
            </div>
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5">
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 font-mono">{{ $this->summary['adjustments'] }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">Adjustments</p>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-4 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                <div class="relative flex-1 w-full sm:max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by product name or barcode..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all" id="movement-search">
                </div>
                <select wire:model.live="typeFilter" class="px-3 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm text-gray-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all" id="type-filter">
                    <option value="">All Types</option>
                    <option value="sale">🔴 Sale</option>
                    <option value="restock">🟢 Restock</option>
                    <option value="adjustment">🔵 Adjustment</option>
                </select>
                <input wire:model.live="dateFrom" type="date" class="px-3 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm text-gray-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all" placeholder="From">
                <input wire:model.live="dateTo" type="date" class="px-3 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm text-gray-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all" placeholder="To">
            </div>
        </div>

        {{-- MOVEMENTS TABLE --}}
        <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full" id="movements-table">
                    <thead>
                        <tr class="border-b border-gray-200/60 dark:border-slate-800/60">
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Date</th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Product</th>
                            <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Type</th>
                            <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Quantity</th>
                            <th class="text-center px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Previous → New</th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Done By</th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                        @forelse($this->movements as $movement)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-4 text-xs text-gray-500 dark:text-slate-400 whitespace-nowrap">{{ $movement->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-5 py-4">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $movement->product->name ?? 'Deleted' }}</span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($movement->type === 'sale')
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400">Sale</span>
                                    @elseif($movement->type === 'restock')
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">Restock</span>
                                    @else
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">Adjustment</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="text-sm font-bold font-mono {{ $movement->quantity < 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                        {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="text-xs font-mono text-gray-500 dark:text-slate-400">{{ $movement->previous_stock }} → {{ $movement->new_stock }}</span>
                                </td>
                                <td class="px-5 py-4 text-xs font-medium text-gray-700 dark:text-slate-300">{{ $movement->user->name ?? 'System' }}</td>
                                <td class="px-5 py-4 text-xs text-gray-500 dark:text-slate-400 max-w-xs truncate">{{ $movement->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <p class="text-sm font-semibold text-gray-500 dark:text-slate-500">No stock movements found</p>
                                    <p class="text-xs text-gray-400 dark:text-slate-600 mt-1">Stock changes will appear here</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($this->movements->hasPages())
                <div class="px-5 py-4 border-t border-gray-200/60 dark:border-slate-800/60">
                    {{ $this->movements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
