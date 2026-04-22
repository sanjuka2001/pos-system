<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-300">

    {{-- ═══════════════════════ TOP NAV ═══════════════════════ --}}
    <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-gray-200/60 dark:border-slate-800/60 px-6 py-4 sticky top-0 z-40 transition-colors duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-6">
            {{-- Logo / Title --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">Inventory</h1>
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">Admin Dashboard</p>
                </div>
            </div>

            {{-- Nav Links --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('cashier') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Cashier
                </a>

                {{-- Theme Toggle --}}
                <button
                    @click="darkMode = !darkMode"
                    class="w-10 h-10 rounded-xl flex items-center justify-center bg-gray-100/60 dark:bg-slate-800/60 border border-gray-200/40 dark:border-slate-700/40 hover:bg-gray-200/60 dark:hover:bg-slate-700/60 transition-all duration-200 group"
                    id="theme-toggle-admin"
                >
                    <svg x-show="darkMode" x-transition class="w-5 h-5 text-amber-400 group-hover:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!darkMode" x-transition class="w-5 h-5 text-slate-500 group-hover:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- ═══════════════════════ STAT CARDS ═══════════════════════ --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            {{-- Total Products --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-colors duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono">{{ $this->stats['total'] }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">Total Products</p>
            </div>

            {{-- Categories --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-colors duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-violet-50 dark:bg-violet-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono">{{ $this->stats['categories'] }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">Categories</p>
            </div>

            {{-- Low Stock --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-colors duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-amber-50 dark:bg-amber-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 font-mono">{{ $this->stats['low_stock'] }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">Low Stock (≤10)</p>
            </div>

            {{-- Out of Stock --}}
            <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-5 transition-colors duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-red-50 dark:bg-red-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400 font-mono">{{ $this->stats['out_of_stock'] }}</p>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium mt-1">Out of Stock</p>
            </div>
        </div>

        {{-- ═══════════════════════ TOOLBAR ═══════════════════════ --}}
        <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl p-4 mb-6 transition-colors duration-300">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 flex-1 w-full">
                    {{-- Search --}}
                    <div class="relative flex-1 w-full sm:max-w-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            wire:model.live.debounce.300ms="search"
                            type="text"
                            placeholder="Search products or barcodes..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-500/30 transition-all duration-200"
                            id="inventory-search"
                        >
                    </div>

                    {{-- Category Filter --}}
                    <select
                        wire:model.live="categoryFilter"
                        class="px-3 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm text-gray-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all duration-200"
                        id="category-filter"
                    >
                        <option value="">All Categories</option>
                        @foreach($this->categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>

                    {{-- Stock Filter --}}
                    <select
                        wire:model.live="stockFilter"
                        class="px-3 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm text-gray-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all duration-200"
                        id="stock-filter"
                    >
                        <option value="">All Stock Levels</option>
                        <option value="low">⚠️ Low Stock (≤10)</option>
                        <option value="out">🚫 Out of Stock</option>
                    </select>
                </div>

                {{-- Add Product Button --}}
                <button
                    wire:click="openCreateModal"
                    class="px-5 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 rounded-xl text-sm font-bold text-white shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 hover:from-violet-400 hover:to-purple-500 transition-all duration-200 active:scale-[0.97] flex items-center gap-2 flex-shrink-0"
                    id="add-product-btn"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Product
                </button>
            </div>
        </div>

        {{-- ═══════════════════════ PRODUCTS TABLE ═══════════════════════ --}}
        <div class="bg-white dark:bg-slate-900/60 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl overflow-hidden transition-colors duration-300">
            <div class="overflow-x-auto">
                <table class="w-full" id="products-table">
                    <thead>
                        <tr class="border-b border-gray-200/60 dark:border-slate-800/60">
                            <th class="text-left px-5 py-3.5">
                                <button wire:click="sortBy('name')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    Product
                                    @if($sortField === 'name')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.707a1 1 0 011.414 0L10 11l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                    @endif
                                </button>
                            </th>
                            <th class="text-left px-5 py-3.5">
                                <button wire:click="sortBy('barcode')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    Barcode
                                    @if($sortField === 'barcode')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.707a1 1 0 011.414 0L10 11l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                    @endif
                                </button>
                            </th>
                            <th class="text-left px-5 py-3.5">
                                <button wire:click="sortBy('category')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    Category
                                    @if($sortField === 'category')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.707a1 1 0 011.414 0L10 11l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                    @endif
                                </button>
                            </th>
                            <th class="text-right px-5 py-3.5">
                                <button wire:click="sortBy('price')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors ml-auto">
                                    Price
                                    @if($sortField === 'price')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.707a1 1 0 011.414 0L10 11l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                    @endif
                                </button>
                            </th>
                            <th class="text-center px-5 py-3.5">
                                <button wire:click="sortBy('stock')" class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors mx-auto">
                                    Stock
                                    @if($sortField === 'stock')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.707a1 1 0 011.414 0L10 11l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                    @endif
                                </button>
                            </th>
                            <th class="text-right px-5 py-3.5">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-slate-400">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800/40">
                        @forelse($this->products as $product)
                            <tr wire:key="row-{{ $product->id }}" class="group hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors duration-100">
                                {{-- Product Name --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gray-100 dark:bg-slate-800/60 rounded-lg flex items-center justify-center text-lg border border-gray-200/30 dark:border-slate-700/30 flex-shrink-0">
                                            {{ $product->category->icon ?? '📦' }}
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->name }}</span>
                                    </div>
                                </td>

                                {{-- Barcode --}}
                                <td class="px-5 py-4">
                                    <span class="text-xs font-mono text-gray-500 dark:text-slate-400 bg-gray-100 dark:bg-slate-800/40 px-2 py-1 rounded-md">{{ $product->barcode }}</span>
                                </td>

                                {{-- Category --}}
                                <td class="px-5 py-4">
                                    <span class="text-xs font-medium text-gray-600 dark:text-slate-300 bg-gray-100 dark:bg-slate-800/40 px-2.5 py-1 rounded-lg">
                                        {{ $product->category->icon }} {{ $product->category->name }}
                                    </span>
                                </td>

                                {{-- Price --}}
                                <td class="px-5 py-4 text-right">
                                    <span class="text-sm font-bold font-mono text-gray-900 dark:text-white">LKR {{ number_format($product->price, 2) }}</span>
                                </td>

                                {{-- Stock --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center text-xs font-bold font-mono px-2.5 py-1 rounded-full
                                        {{ $product->stock === 0 ? 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400' : ($product->stock <= 10 ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                        <button
                                            wire:click="openEditModal({{ $product->id }})"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 dark:text-slate-500 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-500/10 transition-all duration-150"
                                            title="Edit"
                                            id="edit-{{ $product->id }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button
                                            wire:click="confirmDelete({{ $product->id }})"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all duration-150"
                                            title="Delete"
                                            id="delete-{{ $product->id }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="text-sm font-semibold text-gray-500 dark:text-slate-500">No products found</p>
                                    <p class="text-xs text-gray-400 dark:text-slate-600 mt-1">Try adjusting your filters or add a new product</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($this->products->hasPages())
                <div class="px-5 py-4 border-t border-gray-200/60 dark:border-slate-800/60">
                    {{ $this->products->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════ CREATE / EDIT MODAL ═══════════════════════ --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data x-init="$el.querySelector('input[name=name]')?.focus()">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>

            {{-- Modal --}}
            <div class="relative bg-white dark:bg-slate-900 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200/60 dark:border-slate-800/60 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $isEditing ? 'Edit Product' : 'Add New Product' }}
                    </h3>
                    <button wire:click="closeModal" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form wire:submit="saveProduct" class="px-6 py-5 space-y-4">
                    {{-- Product Name --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-1.5 block">Product Name</label>
                        <input
                            wire:model="name"
                            name="name"
                            type="text"
                            placeholder="e.g. Coca-Cola 500ml"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all"
                            id="input-name"
                        >
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Barcode --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-1.5 block">Barcode</label>
                        <input
                            wire:model="barcode"
                            type="text"
                            placeholder="e.g. 5449000000996"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm font-mono text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all"
                            id="input-barcode"
                        >
                        @error('barcode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Price & Stock Row --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-1.5 block">Price (LKR)</label>
                            <input
                                wire:model="price"
                                type="number"
                                step="0.01"
                                min="0"
                                placeholder="0.00"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm font-mono text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                id="input-price"
                            >
                            @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-1.5 block">Stock Qty</label>
                            <input
                                wire:model="stock"
                                type="number"
                                min="0"
                                placeholder="0"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm font-mono text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                id="input-stock"
                            >
                            @error('stock') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-1.5 block">Category</label>
                        <select
                            wire:model="category_id"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/30 transition-all"
                            id="input-category"
                        >
                            <option value="">Select a category...</option>
                            @foreach($this->categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3 pt-2">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="flex-1 py-2.5 px-4 bg-gray-100 dark:bg-slate-800 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition-all active:scale-[0.97]"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="flex-[2] py-2.5 px-4 bg-gradient-to-r from-violet-500 to-purple-600 rounded-xl text-sm font-bold text-white shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 transition-all active:scale-[0.97] flex items-center justify-center gap-2"
                            id="save-product-btn"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $isEditing ? 'Update Product' : 'Create Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════ DELETE CONFIRMATION MODAL ═══════════════════════ --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm" wire:click="closeDeleteModal"></div>

            {{-- Modal --}}
            <div class="relative bg-white dark:bg-slate-900 border border-gray-200/60 dark:border-slate-800/60 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
                <div class="p-6 text-center">
                    <div class="w-14 h-14 bg-red-50 dark:bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Delete Product</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400">
                        Are you sure you want to delete <strong class="text-gray-900 dark:text-white">{{ $deletingProductName }}</strong>? This action cannot be undone.
                    </p>

                    <div class="flex gap-3 mt-6">
                        <button
                            wire:click="closeDeleteModal"
                            class="flex-1 py-2.5 px-4 bg-gray-100 dark:bg-slate-800 border border-gray-200/60 dark:border-slate-700/40 rounded-xl text-sm font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition-all active:scale-[0.97]"
                        >
                            Cancel
                        </button>
                        <button
                            wire:click="deleteProduct"
                            class="flex-1 py-2.5 px-4 bg-gradient-to-r from-red-500 to-red-600 rounded-xl text-sm font-bold text-white shadow-lg shadow-red-500/25 hover:shadow-red-500/40 transition-all active:scale-[0.97]"
                            id="confirm-delete-btn"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
