@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

    {{-- ═══════════════════════ TOP NAV ═══════════════════════ --}}
    <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-gray-200/60 dark:border-slate-800/60 sticky top-0 z-40 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-gray-900 dark:text-white tracking-tight">Manager Panel</h1>
                    <p class="text-[10px] text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Dashboard</p>
                </div>
            </div>

            {{-- Nav Links --}}
            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('cashier.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    POS
                </a>
                <a href="{{ route('admin.inventory') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Inventory
                </a>
                <a href="#" class="px-3 py-2 rounded-lg text-sm font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Reports
                </a>
            </nav>

            {{-- Right --}}
            <div class="flex items-center gap-2">
                <button @click="darkMode = !darkMode" class="w-9 h-9 rounded-xl flex items-center justify-center bg-gray-100/60 dark:bg-slate-800/60 border border-gray-200/40 dark:border-slate-700/40 hover:bg-gray-200/60 dark:hover:bg-slate-700/60 transition-all group">
                    <svg x-show="darkMode" x-transition class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg x-show="!darkMode" x-transition class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
                <a href="{{ route('login') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- ═══════════════════════ SUMMARY CARDS ═══════════════════════ --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            {{-- Today's Sales --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-full">+12.5%</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono">LKR 48,750.00</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Today's Sales</p>
            </div>

            {{-- Total Orders --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2 py-0.5 rounded-full">Today</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono">34</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Total Orders</p>
            </div>

            {{-- Low Stock --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 rounded-full">Alert</span>
                </div>
                <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 font-mono">5</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Low Stock Items</p>
            </div>
        </div>

        {{-- ═══════════════════════ RECENT ORDERS TABLE ═══════════════════════ --}}
        <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-gray-200/60 dark:border-slate-800/60 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Recent Orders</h2>
                <span class="text-xs text-gray-500 dark:text-slate-400 font-medium">Last 24 hours</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-slate-800/40">
                            <th class="text-left px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Order ID</th>
                            <th class="text-left px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Cashier</th>
                            <th class="text-left px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Items</th>
                            <th class="text-right px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Total</th>
                            <th class="text-center px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Status</th>
                            <th class="text-right px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                        @php
                            $orders = [
                                ['id' => '#ORD-1034', 'cashier' => 'John Doe', 'items' => 5, 'total' => 2450.00, 'status' => 'Completed', 'time' => '2 min ago'],
                                ['id' => '#ORD-1033', 'cashier' => 'Jane Smith', 'items' => 3, 'total' => 1180.00, 'status' => 'Completed', 'time' => '15 min ago'],
                                ['id' => '#ORD-1032', 'cashier' => 'John Doe', 'items' => 7, 'total' => 3920.00, 'status' => 'Completed', 'time' => '32 min ago'],
                                ['id' => '#ORD-1031', 'cashier' => 'Jane Smith', 'items' => 2, 'total' => 740.00, 'status' => 'Refunded', 'time' => '1 hr ago'],
                                ['id' => '#ORD-1030', 'cashier' => 'John Doe', 'items' => 4, 'total' => 1850.00, 'status' => 'Completed', 'time' => '1.5 hr ago'],
                                ['id' => '#ORD-1029', 'cashier' => 'Jane Smith', 'items' => 6, 'total' => 4210.00, 'status' => 'Completed', 'time' => '2 hr ago'],
                            ];
                        @endphp
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-3.5 text-xs font-bold font-mono text-gray-900 dark:text-white">{{ $order['id'] }}</td>
                                <td class="px-6 py-3.5 text-xs text-gray-600 dark:text-slate-300">{{ $order['cashier'] }}</td>
                                <td class="px-6 py-3.5 text-xs text-gray-500 dark:text-slate-400 font-mono">{{ $order['items'] }}</td>
                                <td class="px-6 py-3.5 text-xs font-bold font-mono text-gray-900 dark:text-white text-right">LKR {{ number_format($order['total'], 2) }}</td>
                                <td class="px-6 py-3.5 text-center">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $order['status'] === 'Completed' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400' }}">
                                        {{ $order['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-gray-400 dark:text-slate-500 text-right">{{ $order['time'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
