<div>
    {{-- Section Header --}}
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Tax & VAT Configuration</h2>
        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Configure tax rates and behavior for your sales transactions</p>
        <div class="h-px bg-gray-200/60 dark:bg-slate-700/50 mt-4"></div>
    </div>

    <form wire:submit.prevent="saveTaxSettings">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Form Section --}}
            <div class="lg:col-span-2">
                <div class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-slate-800/60 p-6 shadow-sm">

                    {{-- Tax Toggle --}}
                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200/60 dark:border-slate-700/50">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Enable Tax</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">When disabled, no tax will be applied to orders</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="tax_enabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-violet-500/40 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-500"></div>
                        </label>
                    </div>

                    <div class="space-y-5 {{ !$tax_enabled ? 'opacity-40 pointer-events-none' : '' }} transition-opacity duration-300">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Tax Name --}}
                            <div>
                                <label for="tax_name" class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                                    Tax Name <span class="text-red-400">*</span>
                                </label>
                                <input type="text" id="tax_name" wire:model.live="tax_name"
                                    class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/50 rounded-xl text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/40 transition-all duration-200 placeholder-gray-400 dark:placeholder-slate-500"
                                    placeholder="e.g. VAT, GST, Sales Tax">
                                @error('tax_name') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>

                            {{-- Tax Rate --}}
                            <div>
                                <label for="tax_rate" class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                                    Tax Rate (%) <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" id="tax_rate" wire:model.live="tax_rate" step="0.01" min="0" max="100"
                                        class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/50 rounded-xl text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/40 transition-all duration-200 placeholder-gray-400 dark:placeholder-slate-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                        placeholder="10">
                                    <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 dark:text-slate-500 text-sm font-medium">%</span>
                                </div>
                                @error('tax_rate') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Tax Registration Number --}}
                        <div>
                            <label for="tax_number" class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                                Tax Registration Number
                            </label>
                            <input type="text" id="tax_number" wire:model="tax_number"
                                class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/50 rounded-xl text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/40 transition-all duration-200 placeholder-gray-400 dark:placeholder-slate-500"
                                placeholder="Enter tax registration number">
                            @error('tax_number') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Prices Include Tax --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200/40 dark:border-slate-700/30">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Prices Include Tax</h3>
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Product prices already include tax amount</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="prices_include_tax" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-violet-500/40 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-500"></div>
                            </label>
                        </div>
                    </div>

                    {{-- Save Button --}}
                    <div class="flex justify-end mt-6 pt-6 border-t border-gray-200/60 dark:border-slate-700/50">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 hover:from-violet-400 hover:to-purple-500 transition-all duration-200 active:scale-[0.97] disabled:opacity-50"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveTaxSettings">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <svg wire:loading wire:target="saveTaxSettings" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Save Tax Settings
                        </button>
                    </div>
                </div>
            </div>

            {{-- Live Preview Panel --}}
            <div class="lg:col-span-1">
                <div class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-slate-800/60 p-6 shadow-sm sticky top-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 bg-violet-100 dark:bg-violet-500/15 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Live Preview</h3>
                    </div>

                    <div class="bg-gray-50 dark:bg-slate-800/50 rounded-xl p-4 border border-gray-200/40 dark:border-slate-700/30 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-slate-400">Example item price</span>
                            <span class="font-mono font-semibold text-gray-900 dark:text-white">LKR 100.00</span>
                        </div>
                        <div class="h-px bg-gray-200/60 dark:bg-slate-700/40"></div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-slate-400">{{ $tax_name ?: 'Tax' }} ({{ $tax_enabled ? ($tax_rate ?: '0') : '0' }}%)</span>
                            <span class="font-mono font-semibold {{ $tax_enabled ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400 dark:text-slate-500' }}">
                                LKR {{ number_format($this->preview_tax, 2) }}
                            </span>
                        </div>
                        <div class="h-px bg-gray-200/60 dark:bg-slate-700/40"></div>
                        <div class="flex justify-between text-sm">
                            <span class="font-semibold text-gray-900 dark:text-white">Total</span>
                            <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                LKR {{ number_format($this->preview_total, 2) }}
                            </span>
                        </div>
                    </div>

                    @if(!$tax_enabled)
                        <div class="mt-4 flex items-center gap-2 text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-3 py-2 rounded-lg border border-amber-200/50 dark:border-amber-500/20">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            <span class="font-medium">Tax is currently disabled. No tax will be charged on orders.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
