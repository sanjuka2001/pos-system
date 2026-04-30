<div>
    <div class="bg-white dark:bg-slate-900 shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Tax Settings</h2>
        
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="saveSettings" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">VAT Rate (%)</label>
                <input type="number" step="0.01" wire:model="vat_rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                @error('vat_rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">SSCL Rate (%)</label>
                <input type="number" step="0.01" wire:model="sscl_rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                @error('sscl_rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 flex justify-end mt-4">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Save Settings</button>
            </div>
        </form>
    </div>
</div>
