@extends('layouts.app')

@section('title', 'Cashier')

@section('content')
<div class="flex flex-col h-screen overflow-hidden" x-data="cashierApp()">

    {{-- ═══════════════════════ TOP BAR ═══════════════════════ --}}
    <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-gray-200/60 dark:border-slate-800/60 px-6 py-3 flex-shrink-0 transition-colors duration-300">
        <div class="flex items-center justify-between gap-4">
            {{-- Logo --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-gray-900 dark:text-white tracking-tight">POS Terminal</h1>
                    <p class="text-[10px] text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Cashier: <span class="text-emerald-600 dark:text-emerald-400">John Doe</span></p>
                </div>
            </div>

            {{-- Search / Barcode Input --}}
            <div class="flex-1 max-w-xl relative" @keydown.f2.window.prevent="$refs.barcodeInput.focus(); $refs.barcodeInput.select()">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    x-ref="barcodeInput"
                    x-model="searchQuery"
                    @keydown.enter.prevent="handleBarcodeScan()"
                    type="text"
                    placeholder="Scan barcode or type product name + Enter (F2 to focus)"
                    autofocus
                    x-init="$el.focus()"
                    class="w-full pl-11 pr-24 py-2.5 bg-gray-100/70 dark:bg-slate-800/60 border border-gray-200/50 dark:border-slate-700/40 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all duration-200"
                    id="barcode-input"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center gap-1.5">
                    <kbd class="hidden sm:inline-flex px-1.5 py-0.5 text-[9px] font-mono font-bold bg-gray-200/80 dark:bg-slate-700/60 text-gray-500 dark:text-slate-400 rounded border border-gray-300/40 dark:border-slate-600/40">F2</kbd>
                    <kbd class="hidden sm:inline-flex px-1.5 py-0.5 text-[9px] font-mono font-bold bg-gray-200/80 dark:bg-slate-700/60 text-gray-500 dark:text-slate-400 rounded border border-gray-300/40 dark:border-slate-600/40">Enter</kbd>
                </div>
            </div>

            {{-- Right: Receipt + Clock + Actions --}}
            <div class="flex items-center gap-2.5 flex-shrink-0">
                {{-- Receipt Number --}}
                <div class="hidden md:flex items-center gap-2 bg-gray-100/60 dark:bg-slate-800/50 rounded-xl px-3 py-2 border border-gray-200/40 dark:border-slate-700/30">
                    <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-xs font-mono font-semibold text-gray-700 dark:text-slate-300" x-text="'#' + receiptNo"></span>
                </div>

                {{-- Live Clock --}}
                <div class="flex items-center gap-2 bg-gray-100/60 dark:bg-slate-800/50 rounded-xl px-3 py-2 border border-gray-200/40 dark:border-slate-700/30">
                    <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-mono font-semibold text-gray-700 dark:text-slate-300" x-text="currentTime"></span>
                </div>

                {{-- Hold Order --}}
                <button @click="holdOrder()" x-show="saleItems.length > 0"
                    class="w-9 h-9 rounded-xl flex items-center justify-center bg-amber-50 dark:bg-amber-500/10 border border-amber-200/40 dark:border-amber-500/20 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-all" title="Hold Order (F4)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>

                {{-- Recall Held --}}
                <button @click="recallOrder()" x-show="heldOrders.length > 0"
                    class="relative w-9 h-9 rounded-xl flex items-center justify-center bg-blue-50 dark:bg-blue-500/10 border border-blue-200/40 dark:border-blue-500/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-all" title="Recall Held Order (F5)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-blue-500 rounded-full text-[9px] font-bold text-white flex items-center justify-center" x-text="heldOrders.length"></span>
                </button>

                {{-- Theme Toggle --}}
                <button @click="darkMode = !darkMode" class="w-9 h-9 rounded-xl flex items-center justify-center bg-gray-100/60 dark:bg-slate-800/60 border border-gray-200/40 dark:border-slate-700/40 hover:bg-gray-200/60 dark:hover:bg-slate-700/60 transition-all group">
                    <svg x-show="darkMode" x-transition class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg x-show="!darkMode" x-transition class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
            </div>
        </div>
    </header>

    {{-- ═══════════════════════ MAIN ═══════════════════════ --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- ────── LEFT: Sale Items Table ────── --}}
        <main class="flex-1 flex flex-col overflow-hidden bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

            {{-- Notification Toast --}}
            <div x-show="toast.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-4"
                 class="mx-6 mt-4 px-4 py-2.5 rounded-xl text-sm font-medium flex items-center gap-2 shadow-lg"
                 :class="toast.type === 'success' ? 'bg-emerald-500 text-white shadow-emerald-500/20' : toast.type === 'warning' ? 'bg-amber-500 text-white shadow-amber-500/20' : 'bg-red-500 text-white shadow-red-500/20'">
                <svg x-show="toast.type === 'success'" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <svg x-show="toast.type === 'warning'" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <svg x-show="toast.type === 'error'" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span x-text="toast.message"></span>
            </div>

            {{-- Table Header --}}
            <div class="px-6 pt-4 pb-2 flex-shrink-0">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Sale Items</h2>
                        <span x-show="saleItems.length > 0" class="bg-emerald-50 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-full" x-text="totalQty + ' items'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="voidLastItem()" x-show="saleItems.length > 0" class="text-[10px] font-semibold text-red-400 hover:text-red-500 px-2.5 py-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 transition-all flex items-center gap-1" title="Void Last Item (F8)">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"/></svg>
                            Void Last
                        </button>
                        <button @click="clearSale()" x-show="saleItems.length > 0" class="text-[10px] font-semibold text-red-400 hover:text-red-500 px-2.5 py-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 transition-all flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Clear All
                        </button>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="flex-1 overflow-y-auto px-6 pb-4" id="sale-items-area">
                <div class="bg-white dark:bg-slate-900/60 border border-gray-200/50 dark:border-slate-800/50 rounded-2xl overflow-hidden">
                    <table class="w-full" id="sale-items-table">
                        <thead>
                            <tr class="border-b border-gray-200/60 dark:border-slate-800/60 bg-gray-50/50 dark:bg-slate-800/20">
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400 w-10">#</th>
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Item Code</th>
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Item Name</th>
                                <th class="text-center px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Weight</th>
                                <th class="text-right px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Unit Price</th>
                                <th class="text-center px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400 w-32">QTY</th>
                                <th class="text-right px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Total</th>
                                <th class="text-center px-4 py-2.5 text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400 w-14"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                            <template x-if="saleItems.length === 0">
                                <tr>
                                    <td colspan="8" class="px-4 py-20 text-center">
                                        <div class="flex flex-col items-center text-gray-400 dark:text-slate-600">
                                            <svg class="w-16 h-16 mb-3 text-gray-200 dark:text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <p class="text-sm font-semibold text-gray-500 dark:text-slate-500">No items in this sale</p>
                                            <p class="text-xs text-gray-400 dark:text-slate-600 mt-1">Scan an Item Code or search to add items</p>
                                            <div class="flex items-center gap-4 mt-4 text-[10px] text-gray-400 dark:text-slate-600">
                                                <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 rounded font-mono text-[9px] border border-gray-200/40 dark:border-slate-700/30">F2</kbd> Focus search</span>
                                                <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 rounded font-mono text-[9px] border border-gray-200/40 dark:border-slate-700/30">F4</kbd> Hold</span>
                                                <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 rounded font-mono text-[9px] border border-gray-200/40 dark:border-slate-700/30">F8</kbd> Void last</span>
                                                <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 rounded font-mono text-[9px] border border-gray-200/40 dark:border-slate-700/30">F12</kbd> Pay</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            <template x-for="(item, index) in saleItems" :key="item.id + '-' + index">
                                <tr class="group hover:bg-emerald-50/30 dark:hover:bg-emerald-500/5 transition-colors duration-100"
                                    :class="index === saleItems.length - 1 && justAdded ? 'bg-emerald-50/50 dark:bg-emerald-500/10' : ''">
                                    {{-- Row Number --}}
                                    <td class="px-4 py-3 text-xs font-mono text-gray-400 dark:text-slate-500" x-text="index + 1"></td>

                                    {{-- Barcode --}}
                                    <td class="px-4 py-3">
                                        <span class="text-[11px] font-mono text-gray-500 dark:text-slate-400 bg-gray-100 dark:bg-slate-800/40 px-2 py-0.5 rounded-md" x-text="item.barcode"></span>
                                    </td>

                                    {{-- Item Name --}}
                                    <td class="px-4 py-3">
                                        <div>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="item.name"></span>
                                            <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-0.5" x-text="item.category"></p>
                                        </div>
                                    </td>

                                    {{-- Weight --}}
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs text-gray-500 dark:text-slate-400 font-mono" x-text="item.weight"></span>
                                    </td>

                                    {{-- Unit Price --}}
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-xs font-bold font-mono text-gray-700 dark:text-slate-300" x-text="item.price.toFixed(2)"></span>
                                    </td>

                                    {{-- Quantity Controls --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1">
                                            <button @click="decrementQty(index)" class="w-7 h-7 bg-gray-200/60 dark:bg-slate-700/50 hover:bg-gray-300/60 dark:hover:bg-slate-700 rounded-lg flex items-center justify-center text-gray-600 dark:text-slate-300 transition-all active:scale-90">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                            </button>
                                            <input type="number" x-model.number="item.qty" @change="validateQty(index)" min="1"
                                                   class="w-12 text-center text-sm font-bold text-gray-900 dark:text-white font-mono bg-transparent border border-gray-200/40 dark:border-slate-700/30 rounded-lg py-1 focus:outline-none focus:ring-1 focus:ring-emerald-500/30 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            <button @click="incrementQty(index)" class="w-7 h-7 bg-gray-200/60 dark:bg-slate-700/50 hover:bg-emerald-100 dark:hover:bg-emerald-600/30 rounded-lg flex items-center justify-center text-gray-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all active:scale-90">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                            </button>
                                        </div>
                                    </td>

                                    {{-- Line Total --}}
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-sm font-bold font-mono text-emerald-600 dark:text-emerald-400" x-text="(item.price * item.qty).toFixed(2)"></span>
                                    </td>

                                    {{-- Remove --}}
                                    <td class="px-4 py-3 text-center">
                                        <button @click="removeItem(index)" class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-300 dark:text-slate-700 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all opacity-0 group-hover:opacity-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Bottom Status Bar --}}
            <div class="px-6 py-2 border-t border-gray-200/60 dark:border-slate-800/60 bg-white/50 dark:bg-slate-900/50 flex items-center justify-between text-[10px] text-gray-400 dark:text-slate-500 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <span>Date: <span class="font-mono text-gray-600 dark:text-slate-400" x-text="currentDate"></span></span>
                    <span x-show="heldOrders.length > 0" class="text-amber-500">⏸ <span x-text="heldOrders.length"></span> held order(s)</span>
                </div>
                <div class="flex items-center gap-4">
                    <span><kbd class="px-1 py-0.5 bg-gray-100 dark:bg-slate-800 rounded font-mono text-[8px] border border-gray-200/30 dark:border-slate-700/30">F2</kbd> Search</span>
                    <span><kbd class="px-1 py-0.5 bg-gray-100 dark:bg-slate-800 rounded font-mono text-[8px] border border-gray-200/30 dark:border-slate-700/30">F4</kbd> Hold</span>
                    <span><kbd class="px-1 py-0.5 bg-gray-100 dark:bg-slate-800 rounded font-mono text-[8px] border border-gray-200/30 dark:border-slate-700/30">F5</kbd> Recall</span>
                    <span><kbd class="px-1 py-0.5 bg-gray-100 dark:bg-slate-800 rounded font-mono text-[8px] border border-gray-200/30 dark:border-slate-700/30">F8</kbd> Void</span>
                    <span><kbd class="px-1 py-0.5 bg-gray-100 dark:bg-slate-800 rounded font-mono text-[8px] border border-gray-200/30 dark:border-slate-700/30">F12</kbd> Pay</span>
                </div>
            </div>
        </main>

        {{-- ────── RIGHT: Payment Panel ────── --}}
        <aside class="w-[380px] flex-shrink-0 bg-white dark:bg-slate-900/80 border-l border-gray-200/60 dark:border-slate-800/60 flex flex-col overflow-hidden transition-colors duration-300">

            {{-- Summary Header --}}
            <div class="px-5 py-3.5 border-b border-gray-200/60 dark:border-slate-800/60 flex-shrink-0">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Order Summary</h2>
            </div>

            {{-- Scrollable Summary Area --}}
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
                {{-- Quick Stats --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 dark:bg-slate-800/30 rounded-xl p-3 border border-gray-200/30 dark:border-slate-700/20">
                        <p class="text-[10px] text-gray-500 dark:text-slate-500 font-medium uppercase tracking-wider">Items</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white font-mono" x-text="saleItems.length"></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-800/30 rounded-xl p-3 border border-gray-200/30 dark:border-slate-700/20">
                        <p class="text-[10px] text-gray-500 dark:text-slate-500 font-medium uppercase tracking-wider">Total Qty</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white font-mono" x-text="totalQty"></p>
                    </div>
                </div>

                {{-- Totals --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500 dark:text-slate-400">Subtotal</span>
                        <span class="text-gray-900 dark:text-white font-mono font-semibold" x-text="'LKR ' + subtotal.toFixed(2)"></span>
                    </div>

                    {{-- Discount --}}
                    <div class="flex items-center justify-between text-xs gap-2">
                        <span class="text-gray-500 dark:text-slate-400 flex-shrink-0">Discount</span>
                        <div class="flex items-center gap-1.5">
                            <button @click="discountType = discountType === 'amount' ? 'percent' : 'amount'"
                                    class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 hover:text-gray-700 dark:hover:text-slate-300 border border-gray-200/40 dark:border-slate-700/30 transition-colors"
                                    x-text="discountType === 'amount' ? 'LKR' : '%'"></button>
                            <input type="number" x-model.number="discountValue" min="0" step="0.01" placeholder="0"
                                   class="w-16 px-2 py-1 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/50 dark:border-slate-700/30 rounded-lg text-xs font-mono text-right text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-emerald-500/30 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            <span class="text-red-500 dark:text-red-400 font-mono font-semibold flex-shrink-0 text-xs" x-text="'- ' + discountAmount.toFixed(2)"></span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500 dark:text-slate-400">VAT (10%)</span>
                        <span class="text-gray-900 dark:text-white font-mono font-semibold" x-text="'LKR ' + vat.toFixed(2)"></span>
                    </div>

                    <div class="h-px bg-gray-200/60 dark:bg-slate-700/50 my-1"></div>

                    <div class="flex items-center justify-between py-1">
                        <span class="text-base font-bold text-gray-900 dark:text-white">Grand Total</span>
                        <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono" x-text="'LKR ' + grandTotal.toFixed(2)"></span>
                    </div>
                </div>

                {{-- Customer Note --}}
                <div>
                    <label class="text-[10px] font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Note (optional)</label>
                    <textarea x-model="orderNote" rows="2" placeholder="Add note for this order..."
                        class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/50 dark:border-slate-700/30 rounded-xl text-xs text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-emerald-500/30 resize-none transition-all"></textarea>
                </div>
            </div>

            {{-- ────── Payment Section (sticky bottom) ────── --}}
            <div class="border-t border-gray-200/60 dark:border-slate-800/60 flex-shrink-0">
                <div class="px-5 py-4 space-y-3">
                    {{-- Payment Method --}}
                    <div>
                        <label class="text-[10px] font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 block">Payment Method</label>
                        <div class="grid grid-cols-3 gap-1.5">
                            <button @click="paymentMethod = 'cash'" :class="paymentMethod === 'cash' ? 'bg-emerald-50 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-300/50 dark:border-emerald-500/30' : 'bg-gray-50 dark:bg-slate-800/40 text-gray-500 dark:text-slate-400 border-gray-200/50 dark:border-slate-700/30 hover:bg-gray-100 dark:hover:bg-slate-800'"
                                    class="text-[11px] font-semibold py-2 rounded-lg border transition-all flex items-center justify-center gap-1">
                                💵 Cash
                            </button>
                            <button @click="paymentMethod = 'card'" :class="paymentMethod === 'card' ? 'bg-emerald-50 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-300/50 dark:border-emerald-500/30' : 'bg-gray-50 dark:bg-slate-800/40 text-gray-500 dark:text-slate-400 border-gray-200/50 dark:border-slate-700/30 hover:bg-gray-100 dark:hover:bg-slate-800'"
                                    class="text-[11px] font-semibold py-2 rounded-lg border transition-all flex items-center justify-center gap-1">
                                💳 Card
                            </button>
                            <button @click="paymentMethod = 'mobile'" :class="paymentMethod === 'mobile' ? 'bg-emerald-50 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-300/50 dark:border-emerald-500/30' : 'bg-gray-50 dark:bg-slate-800/40 text-gray-500 dark:text-slate-400 border-gray-200/50 dark:border-slate-700/30 hover:bg-gray-100 dark:hover:bg-slate-800'"
                                    class="text-[11px] font-semibold py-2 rounded-lg border transition-all flex items-center justify-center gap-1">
                                📱 Mobile
                            </button>
                        </div>
                    </div>

                    {{-- Amount Received (only for cash) --}}
                    <div x-show="paymentMethod === 'cash'">
                        <label class="text-[10px] font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Amount Received</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-slate-500 text-xs font-semibold">LKR</span>
                            <input type="number" x-model.number="amountReceived" step="0.01" min="0" placeholder="0.00"
                                   class="w-full pl-11 pr-4 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/50 dark:border-slate-700/40 rounded-xl font-mono text-lg font-bold text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/30 placeholder-gray-400 dark:placeholder-slate-600 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none transition-all"
                                   id="amount-received">
                        </div>
                        {{-- Quick amount buttons --}}
                        <div class="flex gap-1.5 mt-2">
                            <template x-for="amt in quickAmounts" :key="amt">
                                <button @click="amountReceived = amt" class="flex-1 text-[10px] font-mono font-semibold py-1.5 bg-gray-100 dark:bg-slate-800/40 text-gray-600 dark:text-slate-400 border border-gray-200/40 dark:border-slate-700/30 rounded-lg hover:bg-gray-200/50 dark:hover:bg-slate-700/50 transition-colors" x-text="amt.toLocaleString()"></button>
                            </template>
                            <button @click="amountReceived = grandTotal" class="flex-1 text-[10px] font-semibold py-1.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200/40 dark:border-emerald-500/20 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-colors">Exact</button>
                        </div>
                    </div>

                    {{-- Change Due --}}
                    <template x-if="paymentMethod === 'cash' && amountReceived > 0">
                        <div class="flex items-center justify-between p-2.5 rounded-xl transition-colors"
                             :class="changeDue >= 0 ? 'bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200/40 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-500/10 border border-red-200/40 dark:border-red-500/20'">
                            <span class="text-xs font-semibold" :class="changeDue >= 0 ? 'text-emerald-700 dark:text-emerald-300' : 'text-red-700 dark:text-red-300'"
                                  x-text="changeDue >= 0 ? 'Change Due' : 'Short'"></span>
                            <span class="text-base font-extrabold font-mono" :class="changeDue >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'"
                                  x-text="'LKR ' + Math.abs(changeDue).toFixed(2)"></span>
                        </div>
                    </template>

                    {{-- Place Order Button --}}
                    <button
                        @click="placeOrder()"
                        :disabled="saleItems.length === 0 || (paymentMethod === 'cash' && amountReceived < grandTotal)"
                        class="w-full py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl text-sm font-bold text-white shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:from-emerald-400 hover:to-emerald-500 transition-all active:scale-[0.97] disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none flex items-center justify-center gap-2"
                        id="place-order-btn"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Complete Sale
                        <kbd class="ml-1 px-1.5 py-0.5 text-[9px] font-mono bg-white/20 rounded">F12</kbd>
                    </button>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cashierApp() {
    return {
        currentTime: '',
        currentDate: '',
        searchQuery: '',
        receiptNo: 'INV-' + String(Math.floor(1000 + Math.random() * 9000)),
        saleItems: [],
        justAdded: false,
        discountType: 'amount',
        discountValue: 0,
        amountReceived: 0,
        paymentMethod: 'cash',
        orderNote: '',
        heldOrders: [],
        toast: { show: false, message: '', type: 'success' },

        // Dummy product database (simulates barcode lookup)
        productDB: [
            { id: 1, barcode: '5449000000996', name: 'Coca-Cola 500ml', price: 350, weight: '500ml', category: 'Beverages' },
            { id: 2, barcode: '5449000001498', name: 'Sprite 500ml', price: 350, weight: '500ml', category: 'Beverages' },
            { id: 3, barcode: '8901234567890', name: 'Elephant House Ginger Beer', price: 180, weight: '330ml', category: 'Beverages' },
            { id: 4, barcode: '4800016123456', name: 'Munchee Lemon Puff', price: 220, weight: '200g', category: 'Snacks' },
            { id: 5, barcode: '4800016234567', name: 'Maliban Gold Marie', price: 190, weight: '300g', category: 'Snacks' },
            { id: 6, barcode: '4800016345678', name: 'CBL Tikiri Mari', price: 150, weight: '250g', category: 'Snacks' },
            { id: 7, barcode: '9415007001234', name: 'Anchor Fresh Milk 1L', price: 620, weight: '1L', category: 'Dairy' },
            { id: 8, barcode: '9415007002345', name: 'Highland Curd 400g', price: 280, weight: '400g', category: 'Dairy' },
            { id: 9, barcode: '9415007003456', name: 'Newdale Yoghurt Strawberry', price: 150, weight: '80g', category: 'Dairy' },
            { id: 10, barcode: '7501234567001', name: 'Sliced Bread White', price: 420, weight: '450g', category: 'Bakery' },
            { id: 11, barcode: '7501234567002', name: 'Fish Bun', price: 120, weight: '100g', category: 'Bakery' },
            { id: 12, barcode: '7501234567003', name: 'Chocolate Croissant', price: 280, weight: '85g', category: 'Bakery' },
        ],

        get quickAmounts() {
            const gt = this.grandTotal;
            return [500, 1000, 2000, 5000].filter(a => a >= gt * 0.5);
        },

        get totalQty() { return this.saleItems.reduce((sum, i) => sum + i.qty, 0); },
        get subtotal() { return this.saleItems.reduce((sum, i) => sum + i.price * i.qty, 0); },
        get discountAmount() {
            if (this.discountType === 'percent') return this.subtotal * (this.discountValue || 0) / 100;
            return this.discountValue || 0;
        },
        get afterDiscount() { return Math.max(0, this.subtotal - this.discountAmount); },
        get vat() { return this.afterDiscount * 0.10; },
        get grandTotal() { return this.afterDiscount + this.vat; },
        get changeDue() { return (this.amountReceived || 0) - this.grandTotal; },

        handleBarcodeScan() {
            const q = this.searchQuery.trim();
            if (!q) return;

            // Search by barcode first, then by name
            let product = this.productDB.find(p => p.barcode === q);
            if (!product) {
                product = this.productDB.find(p => p.name.toLowerCase().includes(q.toLowerCase()));
            }

            if (product) {
                this.addItem(product);
                this.showToast(product.name + ' added', 'success');
            } else {
                this.showToast('Product not found: "' + q + '"', 'error');
            }

            this.searchQuery = '';
            this.$refs.barcodeInput.focus();
        },

        addItem(product) {
            const existing = this.saleItems.find(i => i.id === product.id);
            if (existing) {
                existing.qty++;
            } else {
                this.saleItems.push({ ...product, qty: 1 });
            }
            this.justAdded = true;
            setTimeout(() => { this.justAdded = false; }, 1500);
            // Scroll to bottom
            this.$nextTick(() => {
                const area = document.getElementById('sale-items-area');
                if (area) area.scrollTop = area.scrollHeight;
            });
        },

        incrementQty(idx) { this.saleItems[idx].qty++; },
        decrementQty(idx) {
            this.saleItems[idx].qty--;
            if (this.saleItems[idx].qty <= 0) this.saleItems.splice(idx, 1);
        },
        validateQty(idx) {
            if (this.saleItems[idx].qty < 1) this.saleItems[idx].qty = 1;
        },
        removeItem(idx) {
            const name = this.saleItems[idx].name;
            this.saleItems.splice(idx, 1);
            this.showToast(name + ' removed', 'warning');
        },
        voidLastItem() {
            if (this.saleItems.length === 0) return;
            const last = this.saleItems[this.saleItems.length - 1];
            this.saleItems.pop();
            this.showToast('Voided: ' + last.name, 'warning');
        },
        clearSale() {
            this.saleItems = [];
            this.discountValue = 0;
            this.amountReceived = 0;
            this.orderNote = '';
            this.showToast('Sale cleared', 'warning');
        },

        holdOrder() {
            if (this.saleItems.length === 0) return;
            this.heldOrders.push({
                items: [...this.saleItems],
                discount: { type: this.discountType, value: this.discountValue },
                note: this.orderNote,
                time: new Date().toLocaleTimeString('en-US', { hour12: true }),
                receiptNo: this.receiptNo,
            });
            this.saleItems = [];
            this.discountValue = 0;
            this.orderNote = '';
            this.receiptNo = 'INV-' + String(Math.floor(1000 + Math.random() * 9000));
            this.showToast('Order held (' + this.heldOrders.length + ' pending)', 'warning');
            this.$refs.barcodeInput.focus();
        },

        recallOrder() {
            if (this.heldOrders.length === 0) return;
            if (this.saleItems.length > 0) {
                this.holdOrder(); // auto-hold current
            }
            const recalled = this.heldOrders.pop();
            this.saleItems = recalled.items;
            this.discountType = recalled.discount.type;
            this.discountValue = recalled.discount.value;
            this.orderNote = recalled.note;
            this.receiptNo = recalled.receiptNo;
            this.showToast('Order recalled: ' + recalled.receiptNo, 'success');
            this.$refs.barcodeInput.focus();
        },

        placeOrder() {
            if (this.saleItems.length === 0) return;
            const total = this.grandTotal;
            this.showToast('Sale completed! Total: LKR ' + total.toFixed(2), 'success');
            this.saleItems = [];
            this.discountValue = 0;
            this.amountReceived = 0;
            this.orderNote = '';
            this.receiptNo = 'INV-' + String(Math.floor(1000 + Math.random() * 9000));
            this.$refs.barcodeInput.focus();
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => { this.toast.show = false; }, 3000);
        },

        init() {
            // Clock
            const tick = () => {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('en-US', { hour12: true });
                this.currentDate = now.toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            };
            tick();
            setInterval(tick, 1000);

            // Keyboard shortcuts
            window.addEventListener('keydown', (e) => {
                if (e.key === 'F4') { e.preventDefault(); this.holdOrder(); }
                if (e.key === 'F5') { e.preventDefault(); this.recallOrder(); }
                if (e.key === 'F8') { e.preventDefault(); this.voidLastItem(); }
                if (e.key === 'F12') { e.preventDefault(); this.placeOrder(); }
            });
        }
    };
}
</script>
@endpush
