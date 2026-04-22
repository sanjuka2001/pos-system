@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

    {{-- ═══════════════════════ TOP NAV ═══════════════════════ --}}
    <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-gray-200/60 dark:border-slate-800/60 sticky top-0 z-40 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/20">
                    <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-gray-900 dark:text-white tracking-tight">Admin Panel</h1>
                    <p class="text-[10px] text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">System Administrator</p>
                </div>
            </div>

            {{-- Nav Links --}}
            <nav class="hidden lg:flex items-center gap-1">
                <a href="{{ route('cashier.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    POS
                </a>
                <a href="{{ route('admin.inventory') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Inventory
                </a>
                <a href="#" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Reports
                </a>
                <a href="#" class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>
                <a href="#" class="px-3 py-2 rounded-lg text-sm font-medium text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-500/10 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings
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
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            {{-- Total Users --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-violet-50 dark:bg-violet-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-semibold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-500/10 px-2 py-0.5 rounded-full">Active</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono">8</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Total Users</p>
            </div>

            {{-- Total Sales --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-full">This Month</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono">LKR 1.2M</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Total Sales</p>
            </div>

            {{-- Total Products --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 px-2 py-0.5 rounded-full">In Stock</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono">156</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Total Products</p>
            </div>

            {{-- Total Orders --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="text-[10px] font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 rounded-full">All Time</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono">2,847</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Total Orders</p>
            </div>
        </div>

        {{-- ═══════════════════════ RECENT ACTIVITY TABLE ═══════════════════════ --}}
        <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl overflow-hidden transition-colors">
            <div class="px-6 py-4 border-b border-gray-200/60 dark:border-slate-800/60 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Recent Activity</h2>
                <span class="text-xs text-gray-500 dark:text-slate-400 font-medium">System Log</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-slate-800/40">
                            <th class="text-left px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">User</th>
                            <th class="text-left px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Action</th>
                            <th class="text-left px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Details</th>
                            <th class="text-center px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Role</th>
                            <th class="text-right px-6 py-3 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                        @php
                            $activities = [
                                ['user' => 'John Doe', 'action' => 'Completed Sale', 'details' => 'Order #ORD-1034 — LKR 2,450.00', 'role' => 'Cashier', 'time' => '2 min ago', 'color' => 'emerald'],
                                ['user' => 'Admin', 'action' => 'Updated Product', 'details' => 'Coca-Cola 500ml — stock set to 120', 'role' => 'Admin', 'time' => '18 min ago', 'color' => 'violet'],
                                ['user' => 'Jane Smith', 'action' => 'Completed Sale', 'details' => 'Order #ORD-1033 — LKR 1,180.00', 'role' => 'Cashier', 'time' => '25 min ago', 'color' => 'emerald'],
                                ['user' => 'Admin', 'action' => 'Added Product', 'details' => 'New: Ceylon Tea 100g (Barcode: 8901234999)', 'role' => 'Admin', 'time' => '1 hr ago', 'color' => 'blue'],
                                ['user' => 'Manager', 'action' => 'Issued Refund', 'details' => 'Order #ORD-1031 — LKR 740.00', 'role' => 'Manager', 'time' => '1.5 hr ago', 'color' => 'amber'],
                                ['user' => 'Admin', 'action' => 'Created User', 'details' => 'New cashier account: Sarah K.', 'role' => 'Admin', 'time' => '3 hr ago', 'color' => 'violet'],
                                ['user' => 'John Doe', 'action' => 'Logged In', 'details' => 'Session started from 192.168.1.45', 'role' => 'Cashier', 'time' => '4 hr ago', 'color' => 'gray'],
                            ];
                        @endphp
                        @foreach($activities as $activity)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 bg-{{ $activity['color'] }}-50 dark:bg-{{ $activity['color'] }}-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <span class="text-xs font-bold text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-400">{{ strtoupper(substr($activity['user'], 0, 1)) }}</span>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $activity['user'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-xs font-medium text-gray-700 dark:text-slate-300">{{ $activity['action'] }}</td>
                                <td class="px-6 py-3.5 text-xs text-gray-500 dark:text-slate-400 max-w-xs truncate">{{ $activity['details'] }}</td>
                                <td class="px-6 py-3.5 text-center">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-{{ $activity['color'] }}-50 dark:bg-{{ $activity['color'] }}-500/10 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-400">
                                        {{ $activity['role'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-gray-400 dark:text-slate-500 text-right">{{ $activity['time'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
