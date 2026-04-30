@extends('layouts.manager')

@section('title', 'Manager Dashboard')

@section('content')
{{-- ═══════════════════════ SUMMARY CARDS ═══════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    {{-- Today's Sales --}}
    <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-5.5 h-5.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 rounded-full">Today</span>
        </div>
        <p class="text-3xl font-bold text-gray-900 dark:text-white font-mono tracking-tight">LKR 45.2K</p>
        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-medium">Daily Revenue</p>
    </div>

    {{-- Low Stock Alerts --}}
    <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 transition-colors relative overflow-hidden group">
        <div class="absolute inset-0 bg-red-500/5 dark:bg-red-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="flex items-center justify-between mb-4 relative">
            <div class="w-11 h-11 bg-red-50 dark:bg-red-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-5.5 h-5.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <span class="text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 px-2.5 py-1 rounded-full animate-pulse">Needs Attention</span>
        </div>
        <p class="text-3xl font-bold text-gray-900 dark:text-white font-mono tracking-tight relative">12</p>
        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-medium relative">Items Low on Stock</p>
    </div>

    {{-- Active Staff --}}
    <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-6 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                <svg class="w-5.5 h-5.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2.5 py-1 rounded-full">On Shift</span>
        </div>
        <p class="text-3xl font-bold text-gray-900 dark:text-white font-mono tracking-tight">4</p>
        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 font-medium">Cashiers Active</p>
    </div>
</div>

{{-- ═══════════════════════ RECENT TRANSACTIONS ═══════════════════════ --}}
<div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl overflow-hidden transition-colors">
    <div class="px-6 py-5 border-b border-gray-200/60 dark:border-slate-800/60 flex items-center justify-between">
        <h2 class="text-base font-bold text-gray-900 dark:text-white">Recent Transactions</h2>
        <a href="#" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">View All</a>
    </div>
    <div class="p-6 text-center text-gray-500 dark:text-slate-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        <p class="text-sm font-medium">No recent transactions to display yet.</p>
    </div>
</div>
@endsection
