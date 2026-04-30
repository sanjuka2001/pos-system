@extends('layouts.admin')
@section('title', 'Settings')

@section('content')
<div x-data="{
    activeTab: 'store',
    dirty: false,
    switchTab(tab) {
        if (this.dirty && !confirm('You have unsaved changes. Are you sure you want to switch tabs?')) return;
        this.activeTab = tab;
        this.dirty = false;
    }
}"
x-on:settings-saved.window="dirty = false"
x-init="
    window.addEventListener('beforeunload', (e) => {
        if (dirty) { e.preventDefault(); e.returnValue = ''; }
    });
"
>
    {{-- Toast Notification --}}
    <div x-data="{ show: false, message: '' }"
         x-on:settings-saved.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         x-cloak
         class="fixed top-20 right-6 z-50 flex items-center gap-3 px-5 py-3.5 bg-emerald-500 text-white rounded-xl shadow-2xl shadow-emerald-500/30"
    >
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm font-semibold" x-text="message"></span>
        <button @click="show = false" class="ml-2 text-white/70 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Manage your store configuration, tax settings, receipt customization, and discount rules</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex items-center gap-1 p-1 bg-gray-100/80 dark:bg-slate-800/60 rounded-xl w-fit mb-6 border border-gray-200/40 dark:border-slate-700/40">
        <button @click="switchTab('store')"
            :class="activeTab === 'store'
                ? 'bg-white dark:bg-slate-700 text-violet-600 dark:text-violet-400 shadow-sm shadow-gray-200/50 dark:shadow-slate-900/50'
                : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300'"
            class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Store Information
        </button>
        <button @click="switchTab('tax')"
            :class="activeTab === 'tax'
                ? 'bg-white dark:bg-slate-700 text-violet-600 dark:text-violet-400 shadow-sm shadow-gray-200/50 dark:shadow-slate-900/50'
                : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300'"
            class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            Tax & VAT
        </button>
        <button @click="switchTab('receipt')"
            :class="activeTab === 'receipt'
                ? 'bg-white dark:bg-slate-700 text-violet-600 dark:text-violet-400 shadow-sm shadow-gray-200/50 dark:shadow-slate-900/50'
                : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300'"
            class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Receipt
        </button>
        <button @click="switchTab('discount')"
            :class="activeTab === 'discount'
                ? 'bg-white dark:bg-slate-700 text-violet-600 dark:text-violet-400 shadow-sm shadow-gray-200/50 dark:shadow-slate-900/50'
                : 'text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300'"
            class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Discount
        </button>
    </div>

    {{-- Tab Content --}}
    <div x-show="activeTab === 'store'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        @livewire('settings.store-settings')
    </div>
    <div x-show="activeTab === 'tax'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        @livewire('settings.tax-settings')
    </div>
    <div x-show="activeTab === 'receipt'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        @livewire('settings.receipt-settings')
    </div>
    <div x-show="activeTab === 'discount'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        @livewire('settings.discount-settings')
    </div>
</div>
@endsection
