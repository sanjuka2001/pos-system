<div>
    <div class="bg-white dark:bg-slate-900 shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Add New Branch</h2>
        
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="createBranch" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                <input type="text" wire:model="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                <input type="text" wire:model="contact_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
            </div>

            <div class="md:col-span-3 flex justify-end mt-4">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Save Branch</button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-900 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
            <thead class="bg-gray-50 dark:bg-slate-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-slate-900 dark:divide-slate-800 text-gray-900 dark:text-white">
                @foreach($branches as $branch)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $branch->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $branch->location }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $branch->contact_number }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
