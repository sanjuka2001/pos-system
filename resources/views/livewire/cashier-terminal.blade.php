<div class="flex flex-col h-screen overflow-hidden">

    {{-- ═══════════════════════ TOP BAR ═══════════════════════ --}}
    <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-gray-200/60 dark:border-slate-800/60 px-6 py-4 flex-shrink-0 transition-colors duration-300">
        <div class="flex items-center justify-between gap-6">
            {{-- Logo / Title --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">POS Terminal</h1>
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">Cashier Mode</p>
                </div>
            </div>

            {{-- Search / Barcode Input --}}
            <div class="flex-1 max-w-2xl relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    wire:model.live.debounce.300ms="search"
                    wire:keydown.enter.prevent="scanBarcode"
                    type="text"
                    id="barcode-input"
                    placeholder="Scan barcode or type product name + Enter"
                    autofocus
                    x-init="$el.focus()"
                    x-on:barcode-scanned.window="$nextTick(() => { $el.value = ''; $el.focus() })"
                    @keydown.escape="$wire.set('search', ''); $el.focus()"
                    class="w-full pl-12 pr-4 py-3 bg-gray-100/70 dark:bg-slate-800/70 border border-gray-200/60 dark:border-slate-700/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-200"
                >
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 dark:text-slate-400 hover:text-gray-700 dark:hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>

            {{-- Right Controls --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                {{-- Cart Badge --}}
                <div class="flex items-center gap-2 bg-gray-100/60 dark:bg-slate-800/60 rounded-xl px-4 py-2.5 border border-gray-200/40 dark:border-slate-700/40">
                    <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $this->cartCount }}</span>
                    <span class="text-xs text-gray-500 dark:text-slate-400">items</span>
                </div>

                {{-- Admin Link --}}
                <a href="{{ route('admin.inventory') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all duration-200 flex items-center gap-2" id="admin-link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Inventory
                </a>

                {{-- Theme Toggle --}}
                <button
                    @click="darkMode = !darkMode"
                    class="w-10 h-10 rounded-xl flex items-center justify-center bg-gray-100/60 dark:bg-slate-800/60 border border-gray-200/40 dark:border-slate-700/40 hover:bg-gray-200/60 dark:hover:bg-slate-700/60 transition-all duration-200 group"
                    id="theme-toggle"
                    :title="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                >
                    {{-- Sun icon (shown in dark mode) --}}
                    <svg x-show="darkMode" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 rotate-[-90deg] scale-50" x-transition:enter-end="opacity-100 rotate-0 scale-100" class="w-5 h-5 text-amber-400 group-hover:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    {{-- Moon icon (shown in light mode) --}}
                    <svg x-show="!darkMode" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 rotate-90 scale-50" x-transition:enter-end="opacity-100 rotate-0 scale-100" class="w-5 h-5 text-slate-500 group-hover:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- Clock --}}
                <div class="hidden lg:flex items-center gap-2 text-gray-400 dark:text-slate-400 text-xs font-mono" x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { hour12: true }) }, 1000)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="time"></span>
                </div>
            </div>
        </div>

        {{-- Category Filter Tabs --}}
        <div class="flex items-center gap-2 mt-4 overflow-x-auto pb-1 scrollbar-thin">
            <button
                wire:click="filterByCategory(null)"
                class="px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-200 flex-shrink-0
                    {{ !$categoryFilter ? 'bg-emerald-500/15 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25 dark:border-emerald-500/30 shadow-sm shadow-emerald-500/10' : 'bg-gray-100/60 dark:bg-slate-800/40 text-gray-500 dark:text-slate-400 border border-gray-200/40 dark:border-slate-700/30 hover:bg-gray-200/60 dark:hover:bg-slate-800/60 hover:text-gray-700 dark:hover:text-slate-300' }}"
            >
                All Products
            </button>
            @foreach($this->categories as $category)
                <button
                    wire:click="filterByCategory({{ $category->id }})"
                    class="px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-200 flex-shrink-0
                        {{ $categoryFilter === $category->id ? 'bg-emerald-500/15 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25 dark:border-emerald-500/30 shadow-sm shadow-emerald-500/10' : 'bg-gray-100/60 dark:bg-slate-800/40 text-gray-500 dark:text-slate-400 border border-gray-200/40 dark:border-slate-700/30 hover:bg-gray-200/60 dark:hover:bg-slate-800/60 hover:text-gray-700 dark:hover:text-slate-300' }}"
                >
                    <span class="mr-1">{{ $category->icon }}</span>
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </header>

    {{-- ═══════════════════════ MAIN CONTENT ═══════════════════════ --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- ────────── LEFT: Product Grid ────────── --}}
        <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-slate-950 transition-colors duration-300" id="product-grid-area">
            @if($this->products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($this->products as $product)
                        <button
                            wire:click="addToCart({{ $product->id }})"
                            wire:key="product-{{ $product->id }}"
                            class="group relative bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-4 text-left transition-all duration-200 hover:bg-gray-50 dark:hover:bg-slate-800/60 hover:border-emerald-300/40 dark:hover:border-emerald-500/20 hover:shadow-lg hover:shadow-emerald-500/5 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 active:scale-[0.97]"
                            id="product-card-{{ $product->id }}"
                        >
                            {{-- Product Icon / Image Placeholder --}}
                            <div class="w-full aspect-square bg-gray-100 dark:bg-gradient-to-br dark:from-slate-800 dark:to-slate-800/40 rounded-xl mb-3 flex items-center justify-center text-3xl border border-gray-200/40 dark:border-slate-700/30 group-hover:border-emerald-200/30 dark:group-hover:border-emerald-500/10 transition-colors">
                                {{ $product->category->icon ?? '📦' }}
                            </div>

                            {{-- Product Info --}}
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate group-hover:text-emerald-700 dark:group-hover:text-emerald-50 transition-colors">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-gray-400 dark:text-slate-500 font-mono mt-0.5">{{ $product->barcode }}</p>

                            {{-- Price & Stock --}}
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-base font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                                    {{ number_format($product->price, 2) }}
                                </span>
                                @php $pStock = $product->inventory->quantity_in_stock ?? $product->stock; @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                    {{ $pStock > 20 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : ($pStock > 5 ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400') }}">
                                    {{ $pStock }} left
                                </span>
                            </div>

                            {{-- Add indicator --}}
                            <div class="absolute top-3 right-3 w-7 h-7 bg-emerald-500 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg shadow-emerald-500/30 scale-75 group-hover:scale-100">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        </button>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-slate-500">
                    <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-lg font-semibold">No products found</p>
                    <p class="text-sm mt-1">Try adjusting your search or category filter</p>
                </div>
            @endif
        </main>

        {{-- ────────── RIGHT: Cart / Invoice Panel ────────── --}}
        <aside class="w-[420px] flex-shrink-0 bg-white dark:bg-slate-900/80 border-l border-gray-200/60 dark:border-slate-800/60 flex flex-col overflow-hidden transition-colors duration-300" id="cart-panel">

            {{-- Cart Header --}}
            <div class="px-5 py-4 border-b border-gray-200/60 dark:border-slate-800/60 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Current Order</h2>
                    @if(count($cart) > 0)
                        <span class="bg-emerald-50 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $this->cartCount }}
                        </span>
                    @endif
                </div>
                @if(count($cart) > 0)
                    <button
                        wire:click="clearCart"
                        class="text-xs text-red-400/70 hover:text-red-500 dark:hover:text-red-400 font-medium transition-colors flex items-center gap-1"
                        id="clear-cart-btn"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Clear
                    </button>
                @endif
            </div>

            {{-- Cart Items --}}
            <div class="flex-1 overflow-y-auto px-5 py-3 space-y-2" id="cart-items-list">
                @forelse($cart as $productId => $item)
                    <div
                        wire:key="cart-{{ $productId }}"
                        class="bg-gray-50 dark:bg-slate-800/40 border border-gray-200/40 dark:border-slate-700/30 rounded-xl p-3 group hover:border-gray-300/60 dark:hover:border-slate-700/50 transition-all duration-150"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $item['name'] }}</h4>
                                <p class="text-xs text-gray-500 dark:text-slate-400 font-mono mt-0.5">
                                    LKR {{ number_format($item['price'], 2) }} × {{ $item['qty'] }}
                                </p>
                            </div>
                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 font-mono flex-shrink-0">
                                {{ number_format($item['line_total'], 2) }}
                            </span>
                        </div>

                        {{-- Quantity Controls --}}
                        <div class="flex items-center justify-between mt-2.5">
                            <div class="flex items-center gap-1">
                                <button
                                    wire:click="decrementQty({{ $productId }})"
                                    class="w-7 h-7 bg-gray-200/60 dark:bg-slate-700/50 hover:bg-gray-300/60 dark:hover:bg-slate-700 rounded-lg flex items-center justify-center text-gray-600 dark:text-slate-300 hover:text-gray-900 dark:hover:text-white transition-all duration-150 active:scale-90"
                                    id="decrement-{{ $productId }}"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <span class="w-10 text-center text-sm font-bold text-gray-900 dark:text-white font-mono">{{ $item['qty'] }}</span>
                                <button
                                    wire:click="incrementQty({{ $productId }})"
                                    class="w-7 h-7 bg-gray-200/60 dark:bg-slate-700/50 hover:bg-emerald-100 dark:hover:bg-emerald-600/30 rounded-lg flex items-center justify-center text-gray-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all duration-150 active:scale-90"
                                    id="increment-{{ $productId }}"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                            <button
                                wire:click="removeFromCart({{ $productId }})"
                                class="text-xs text-red-400/50 hover:text-red-500 dark:hover:text-red-400 font-medium transition-colors flex items-center gap-1 opacity-0 group-hover:opacity-100"
                                id="remove-{{ $productId }}"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Remove
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-slate-600 py-12">
                        <svg class="w-14 h-14 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-500 dark:text-slate-500">Cart is empty</p>
                        <p class="text-xs text-gray-400 dark:text-slate-600 mt-1">Scan an Item Code or click a product</p>
                    </div>
                @endforelse
            </div>

            {{-- ────────── Bottom: Totals & Checkout ────────── --}}
            @if(count($cart) > 0)
                <div class="border-t border-gray-200/60 dark:border-slate-800/60 flex-shrink-0">
                    {{-- Totals --}}
                    <div class="px-5 py-4 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-slate-400">Subtotal</span>
                            <span class="text-gray-900 dark:text-white font-mono font-semibold">LKR {{ number_format($this->subtotal, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-slate-400">VAT (15%)</span>
                            <span class="text-gray-900 dark:text-white font-mono font-semibold">LKR {{ number_format($this->tax, 2) }}</span>
                        </div>
                        <div class="h-px bg-gray-200/60 dark:bg-slate-700/50 my-2"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-base font-bold text-gray-900 dark:text-white">Grand Total</span>
                            <span class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono">LKR {{ number_format($this->grandTotal, 2) }}</span>
                        </div>
                    </div>

                    {{-- Payment Section --}}
                    <div class="px-5 pb-5 space-y-3">
                        {{-- Amount Received --}}
                        <div>
                            <label for="amount-received" class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 block">Amount Received</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-slate-500 text-sm font-semibold">LKR</span>
                                <input
                                    wire:model.live.debounce.200ms="amountReceived"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="amount-received"
                                    placeholder="0.00"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-100/50 dark:bg-slate-800/50 border border-gray-200/50 dark:border-slate-700/40 rounded-xl text-gray-900 dark:text-white font-mono text-lg font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all duration-200 placeholder-gray-400 dark:placeholder-slate-600 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                >
                            </div>
                        </div>

                        {{-- Balance --}}
                        @if($amountReceived !== '')
                            <div class="flex items-center justify-between p-3 rounded-xl {{ $this->balance >= 0 ? 'bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200/50 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-500/10 border border-red-200/50 dark:border-red-500/20' }}">
                                <span class="text-sm font-semibold {{ $this->balance >= 0 ? 'text-emerald-700 dark:text-emerald-300' : 'text-red-700 dark:text-red-300' }}">
                                    {{ $this->balance >= 0 ? 'Change Due' : 'Amount Short' }}
                                </span>
                                <span class="text-lg font-extrabold font-mono {{ $this->balance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    LKR {{ number_format(abs($this->balance), 2) }}
                                </span>
                            </div>
                        @endif

                        {{-- Action Buttons --}}
                        <div class="flex gap-2 pt-1">
                            <button
                                wire:click="clearCart"
                                class="flex-1 py-3 px-4 bg-gray-100/60 dark:bg-slate-800/60 border border-gray-200/40 dark:border-slate-700/40 rounded-xl text-sm font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-200/60 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-all duration-200 active:scale-[0.97]"
                                id="cancel-order-btn"
                            >
                                Cancel
                            </button>
                            <button
                                wire:click="placeOrder"
                                class="flex-[2] py-3 px-4 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl text-sm font-bold text-white shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:from-emerald-400 hover:to-emerald-500 transition-all duration-200 active:scale-[0.97] disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none"
                                {{ (float)($amountReceived ?: 0) < $this->grandTotal ? 'disabled' : '' }}
                                id="pay-btn"
                            >
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Complete Payment
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
