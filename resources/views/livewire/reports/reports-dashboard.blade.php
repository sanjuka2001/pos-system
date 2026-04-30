<div x-data="{ chartInstances: {} }" x-on:chart-update.window="
    Object.keys($event.detail).forEach(id => {
        if (chartInstances[id]) { chartInstances[id].destroy(); }
        const ctx = document.getElementById(id);
        if (ctx) { chartInstances[id] = new Chart(ctx, $event.detail[id]); }
    });
">
    {{-- ═══════════════════════ PAGE HEADER ═══════════════════════ --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reports & Analytics</h1>
        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Comprehensive business insights and performance metrics</p>
    </div>

    {{-- ═══════════════════════ DATE RANGE FILTER ═══════════════════════ --}}
    <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Period:</span>
            
            @foreach(['today' => 'Today', 'this_week' => 'This Week', 'this_month' => 'This Month', 'this_year' => 'This Year'] as $key => $label)
                <button wire:click="setDateRange('{{ $key }}')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $dateRange === $key ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/25' : 'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                    {{ $label }}
                </button>
            @endforeach

            <div class="flex items-center gap-2 ml-auto">
                <input type="date" wire:model="startDate"
                    class="px-3 py-1.5 rounded-lg text-xs bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                <span class="text-xs text-gray-400">→</span>
                <input type="date" wire:model="endDate"
                    class="px-3 py-1.5 rounded-lg text-xs bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                <button wire:click="applyFilter"
                    class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-violet-600 text-white hover:bg-violet-700 transition-all shadow-lg shadow-violet-500/25">
                    <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Apply
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════ SUMMARY CARDS ═══════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        {{-- Total Sales --}}
        <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-all hover:shadow-lg hover:shadow-blue-500/5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2 py-0.5 rounded-full">Revenue</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono" wire:loading.class="animate-pulse">LKR {{ number_format($totalSales, 2) }}</p>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Total Sales</p>
        </div>

        {{-- Total Tax --}}
        <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-all hover:shadow-lg hover:shadow-red-500/5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-red-50 dark:bg-red-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                </div>
                <span class="text-[10px] font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 px-2 py-0.5 rounded-full">VAT</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono" wire:loading.class="animate-pulse">LKR {{ number_format($totalTax, 2) }}</p>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Total Tax / VAT</p>
        </div>

        {{-- Net Profit --}}
        <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-all hover:shadow-lg hover:shadow-emerald-500/5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-full">Profit</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono" wire:loading.class="animate-pulse">LKR {{ number_format($netProfit, 2) }}</p>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Net Profit</p>
        </div>

        {{-- Total Orders --}}
        <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-all hover:shadow-lg hover:shadow-amber-500/5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-[10px] font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 rounded-full">Count</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono" wire:loading.class="animate-pulse">{{ number_format($totalOrders) }}</p>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Total Orders</p>
        </div>
    </div>

    {{-- ═══════════════════════ TAB NAVIGATION ═══════════════════════ --}}
    <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl overflow-hidden">
        <div class="border-b border-gray-200/60 dark:border-slate-800/60 px-4 overflow-x-auto scrollbar-thin">
            <nav class="flex gap-1 -mb-px">
                @foreach([
                    'profit-loss' => ['Profit & Loss', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    'daily-sales' => ['Daily Sales', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    'top-products' => ['Top Products', 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                    'by-category' => ['By Category', 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                    'cashier-performance' => ['Cashier Performance', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                    'tax' => ['Tax Report', 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
                    'inventory-value' => ['Inventory Value', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ] as $tab => $info)
                    <button wire:click="setTab('{{ $tab }}')"
                        class="flex items-center gap-2 px-4 py-3 text-xs font-semibold border-b-2 transition-all whitespace-nowrap {{ $activeTab === $tab ? 'border-violet-500 text-violet-600 dark:text-violet-400' : 'border-transparent text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300 hover:border-gray-300 dark:hover:border-slate-600' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info[1] }}"/></svg>
                        {{ $info[0] }}
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- ═══════════════════════ TAB CONTENT ═══════════════════════ --}}
        <div class="p-6">
            {{-- Loading Overlay --}}
            <div wire:loading class="flex items-center justify-center py-12">
                <div class="flex items-center gap-3">
                    <svg class="animate-spin w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-sm text-gray-500 dark:text-slate-400">Loading report data...</span>
                </div>
            </div>

            <div wire:loading.remove>
                @if($activeTab === 'profit-loss')
                    @livewire('reports.profit-loss-report', ['startDate' => $startDate, 'endDate' => $endDate], key('pl-'.$startDate.'-'.$endDate))
                @elseif($activeTab === 'daily-sales')
                    @livewire('reports.daily-sales-report', ['startDate' => $startDate, 'endDate' => $endDate], key('ds-'.$startDate.'-'.$endDate))
                @elseif($activeTab === 'top-products')
                    @livewire('reports.top-products-report', ['startDate' => $startDate, 'endDate' => $endDate], key('tp-'.$startDate.'-'.$endDate))
                @elseif($activeTab === 'by-category')
                    @livewire('reports.sales-by-category-report', ['startDate' => $startDate, 'endDate' => $endDate], key('sc-'.$startDate.'-'.$endDate))
                @elseif($activeTab === 'cashier-performance')
                    @livewire('reports.cashier-performance-report', ['startDate' => $startDate, 'endDate' => $endDate], key('cp-'.$startDate.'-'.$endDate))
                @elseif($activeTab === 'tax')
                    @livewire('reports.tax-report', ['startDate' => $startDate, 'endDate' => $endDate], key('tx-'.$startDate.'-'.$endDate))
                @elseif($activeTab === 'inventory-value')
                    @livewire('reports.inventory-value-report', key('iv'))
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
@endpush
