<div x-data x-on:input="$dispatch('mark-dirty')" x-on:mark-dirty.window="$el.closest('[x-data]') && ($el.closest('[x-data]').__x_dirty = true, window.dispatchEvent(new CustomEvent('set-dirty')))">
    {{-- Section Header --}}
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Store Information</h2>
        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Basic information about your store that appears on receipts and reports</p>
        <div class="h-px bg-gray-200/60 dark:bg-slate-700/50 mt-4"></div>
    </div>

    <form wire:submit.prevent="saveStoreSettings">
        <div class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-slate-800/60 p-6 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Store Name --}}
                <div>
                    <label for="store_name" class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                        Store Name <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="store_name" wire:model="store_name"
                        class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/50 rounded-xl text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/40 transition-all duration-200 placeholder-gray-400 dark:placeholder-slate-500"
                        placeholder="Enter store name">
                    @error('store_name') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Store Email --}}
                <div>
                    <label for="store_email" class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                        Store Email
                    </label>
                    <input type="email" id="store_email" wire:model="store_email"
                        class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/50 rounded-xl text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/40 transition-all duration-200 placeholder-gray-400 dark:placeholder-slate-500"
                        placeholder="store@example.com">
                    @error('store_email') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Store Phone --}}
                <div>
                    <label for="store_phone" class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                        Store Phone
                    </label>
                    <input type="text" id="store_phone" wire:model="store_phone"
                        class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/50 rounded-xl text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/40 transition-all duration-200 placeholder-gray-400 dark:placeholder-slate-500"
                        placeholder="077 123 4567">
                    @error('store_phone') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Store Address --}}
                <div class="md:col-span-2">
                    <label for="store_address" class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                        Store Address
                    </label>
                    <textarea id="store_address" wire:model="store_address" rows="3"
                        class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/50 rounded-xl text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/40 transition-all duration-200 placeholder-gray-400 dark:placeholder-slate-500 resize-none"
                        placeholder="Enter full store address"></textarea>
                    @error('store_address') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- Logo Upload --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                        Store Logo
                    </label>
                    <div class="flex items-start gap-6">
                        {{-- Preview --}}
                        <div class="flex-shrink-0">
                            @if($new_logo)
                                <div class="w-24 h-24 rounded-xl border-2 border-violet-300 dark:border-violet-500/40 overflow-hidden bg-gray-100 dark:bg-slate-800">
                                    <img src="{{ $new_logo->temporaryUrl() }}" class="w-full h-full object-contain" alt="New logo preview">
                                </div>
                            @elseif($store_logo)
                                <div class="w-24 h-24 rounded-xl border-2 border-gray-200 dark:border-slate-700 overflow-hidden bg-gray-100 dark:bg-slate-800 relative group">
                                    <img src="{{ asset('storage/' . $store_logo) }}" class="w-full h-full object-contain" alt="Store logo">
                                    <button type="button" wire:click="removeLogo"
                                        class="absolute inset-0 bg-red-500/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            @else
                                <div class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-300 dark:border-slate-600 flex items-center justify-center bg-gray-50 dark:bg-slate-800/50">
                                    <svg class="w-8 h-8 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        {{-- Upload Input --}}
                        <div class="flex-1">
                            <label for="logo-upload" class="cursor-pointer">
                                <div class="flex items-center gap-3 px-4 py-3 bg-gray-50/50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/50 rounded-xl hover:bg-gray-100/50 dark:hover:bg-slate-800 transition-all duration-200">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-slate-300">Click to upload logo</p>
                                        <p class="text-xs text-gray-400 dark:text-slate-500">JPG, JPEG, PNG • Max 2MB</p>
                                    </div>
                                </div>
                            </label>
                            <input type="file" id="logo-upload" wire:model="new_logo" class="hidden" accept="image/jpeg,image/jpg,image/png">
                            @error('new_logo') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                            <div wire:loading wire:target="new_logo" class="mt-2 flex items-center gap-2 text-xs text-violet-500 dark:text-violet-400">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                Uploading...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end mt-6 pt-6 border-t border-gray-200/60 dark:border-slate-700/50">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 hover:from-violet-400 hover:to-purple-500 transition-all duration-200 active:scale-[0.97] disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveStoreSettings">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <svg wire:loading wire:target="saveStoreSettings" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Save Store Settings
                </button>
            </div>
        </div>
    </form>
</div>
