@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
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
                    $activities = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();
                @endphp
                @foreach($activities as $activity)
                    @php
                        $color = match($activity->user?->role) {
                            'admin' => 'violet',
                            'manager' => 'amber',
                            'cashier' => 'emerald',
                            default => 'gray',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 bg-{{ $color }}-50 dark:bg-{{ $color }}-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-{{ $color }}-600 dark:text-{{ $color }}-400">{{ strtoupper(substr($activity->user->name ?? '?', 0, 1)) }}</span>
                                </div>
                                <span class="text-xs font-semibold text-gray-900 dark:text-white">{{ $activity->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-xs font-medium text-gray-700 dark:text-slate-300">{{ $activity->action }}</td>
                        <td class="px-6 py-3.5 text-xs text-gray-500 dark:text-slate-400 max-w-xs truncate">{{ $activity->description }}</td>
                        <td class="px-6 py-3.5 text-center">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-{{ $color }}-50 dark:bg-{{ $color }}-500/10 text-{{ $color }}-600 dark:text-{{ $color }}-400">
                                {{ ucfirst($activity->user->role ?? 'Unknown') }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-xs text-gray-400 dark:text-slate-500 text-right">{{ $activity->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
