<div>
    {{-- Section Header --}}
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Discount Configuration</h2>
        <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Set discount limits and rules for cashier terminals</p>
        <div class="h-px bg-gray-200/60 dark:bg-slate-700/50 mt-4"></div>
    </div>

    <form wire:submit.prevent="saveDiscountSettings">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Form Section --}}
            <div class="lg:col-span-2">
                <div class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-slate-800/60 p-6 shadow-sm">

                    {{-- Enable Toggle --}}
                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200/60 dark:border-slate-700/50">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Enable Discounts</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Allow cashiers to apply discounts on orders</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="discount_enabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-violet-500/40 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-500"></div>
                        </label>
                    </div>

                    <div class="space-y-5 {{ !$discount_enabled ? 'opacity-40 pointer-events-none' : '' }} transition-opacity duration-300">

                        {{-- Discount Type --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3">Discount Type</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative cursor-pointer">
                                    <input type="radio" wire:model.live="discount_type" value="percentage" class="sr-only peer">
                                    <div class="flex items-center gap-3 p-4 bg-gray-50/50 dark:bg-slate-800/50 border-2 rounded-xl transition-all duration-200 peer-checked:border-violet-500 peer-checked:bg-violet-50/50 dark:peer-checked:bg-violet-500/10 border-gray-200/60 dark:border-slate-700/50">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $discount_type === 'percentage' ? 'bg-violet-100 dark:bg-violet-500/20' : 'bg-gray-100 dark:bg-slate-700/50' }} transition-colors">
                                            <span class="text-lg font-bold {{ $discount_type === 'percentage' ? 'text-violet-600 dark:text-violet-400' : 'text-gray-400 dark:text-slate-500' }}">%</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Percentage</p>
                                            <p class="text-[11px] text-gray-500 dark:text-slate-400">Discount as % of subtotal</p>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" wire:model.live="discount_type" value="fixed" class="sr-only peer">
                                    <div class="flex items-center gap-3 p-4 bg-gray-50/50 dark:bg-slate-800/50 border-2 rounded-xl transition-all duration-200 peer-checked:border-violet-500 peer-checked:bg-violet-50/50 dark:peer-checked:bg-violet-500/10 border-gray-200/60 dark:border-slate-700/50">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $discount_type === 'fixed' ? 'bg-violet-100 dark:bg-violet-500/20' : 'bg-gray-100 dark:bg-slate-700/50' }} transition-colors">
                                            <span class="text-sm font-bold {{ $discount_type === 'fixed' ? 'text-violet-600 dark:text-violet-400' : 'text-gray-400 dark:text-slate-500' }}">LKR</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Fixed Amount</p>
                                            <p class="text-[11px] text-gray-500 dark:text-slate-400">Fixed LKR discount</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Max Percentage --}}
                        <div x-data x-show="$wire.discount_type === 'percentage'" x-transition>
                            <label for="discount_max_percentage" class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                                Maximum Discount Percentage <span class="text-red-400">*</span>
                            </label>
                            <div class="relative max-w-xs">
                                <input type="number" id="discount_max_percentage" wire:model="discount_max_percentage" step="0.1" min="0" max="100"
                                    class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/50 rounded-xl text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/40 transition-all duration-200 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                    placeholder="10">
                                <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 dark:text-slate-500 text-sm font-medium">%</span>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-400 dark:text-slate-500">Cashiers cannot exceed this percentage</p>
                            @error('discount_max_percentage') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Max Fixed --}}
                        <div x-data x-show="$wire.discount_type === 'fixed'" x-transition>
                            <label for="discount_max_fixed" class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1.5">
                                Maximum Discount Amount <span class="text-red-400">*</span>
                            </label>
                            <div class="relative max-w-xs">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 dark:text-slate-500 text-sm font-medium">LKR</span>
                                <input type="number" id="discount_max_fixed" wire:model="discount_max_fixed" step="1" min="0"
                                    class="w-full pl-12 pr-4 py-2.5 bg-gray-50/50 dark:bg-slate-800/50 border border-gray-200/60 dark:border-slate-700/50 rounded-xl text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-violet-500/40 focus:border-violet-500/40 transition-all duration-200 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                    placeholder="500">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-400 dark:text-slate-500">Cashiers cannot exceed this amount</p>
                            @error('discount_max_fixed') <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Require Reason --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200/40 dark:border-slate-700/30">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Require Discount Reason</h3>
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Cashier must enter a reason when applying a discount</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="discount_require_reason" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-violet-500/40 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-500"></div>
                            </label>
                        </div>
                    </div>

                    {{-- Save Button --}}
                    <div class="flex justify-end mt-6 pt-6 border-t border-gray-200/60 dark:border-slate-700/50">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-violet-500/25 hover:shadow-violet-500/40 hover:from-violet-400 hover:to-purple-500 transition-all duration-200 active:scale-[0.97] disabled:opacity-50"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveDiscountSettings">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <svg wire:loading wire:target="saveDiscountSettings" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Save Discount Settings
                        </button>
                    </div>
                </div>
            </div>

            {{-- Info Panel --}}
            <div class="lg:col-span-1">
                <div class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-sm rounded-2xl border border-gray-200/60 dark:border-slate-800/60 p-6 shadow-sm sticky top-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 bg-violet-100 dark:bg-violet-500/15 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">How It Works</h3>
                    </div>

                    <div class="space-y-3 text-xs text-gray-600 dark:text-slate-400">
                        <div class="flex items-start gap-2">
                            <span class="w-5 h-5 bg-emerald-100 dark:bg-emerald-500/15 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">1</span>
                            </span>
                            <p>Set the maximum discount limit cashiers can apply per order.</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="w-5 h-5 bg-emerald-100 dark:bg-emerald-500/15 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">2</span>
                            </span>
                            <p>Choose between percentage-based or fixed amount discounts.</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="w-5 h-5 bg-emerald-100 dark:bg-emerald-500/15 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">3</span>
                            </span>
                            <p>Optionally require a reason for every discount applied.</p>
                        </div>
                    </div>

                    @if(!$discount_enabled)
                        <div class="mt-4 flex items-center gap-2 text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-3 py-2 rounded-lg border border-amber-200/50 dark:border-amber-500/20">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            <span class="font-medium">Discounts are disabled. Cashiers will not see the discount option.</span>
                        </div>
                    @endif

                    <div class="mt-4 p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-200/40 dark:border-slate-700/30">
                        <p class="text-[11px] font-semibold text-gray-700 dark:text-slate-300 mb-1.5">Current Limit</p>
                        @if($discount_enabled)
                            @if($discount_type === 'percentage')
                                <p class="text-lg font-bold text-violet-600 dark:text-violet-400">{{ $discount_max_percentage }}%</p>
                                <p class="text-[10px] text-gray-400 dark:text-slate-500">Maximum percentage discount</p>
                            @else
                                <p class="text-lg font-bold text-violet-600 dark:text-violet-400">LKR {{ number_format((float)$discount_max_fixed, 2) }}</p>
                                <p class="text-[10px] text-gray-400 dark:text-slate-500">Maximum fixed discount</p>
                            @endif
                        @else
                            <p class="text-lg font-bold text-gray-400 dark:text-slate-600">Disabled</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
