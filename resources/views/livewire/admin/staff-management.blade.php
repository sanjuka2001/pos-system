<div>
    <div class="bg-white dark:bg-slate-900 shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Add New Employee</h2>
        
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="createStaff" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employee ID (Auto-Generated)</label>
                <input type="text" wire:model="employee_id" readonly class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 shadow-sm cursor-not-allowed dark:bg-slate-700 dark:border-slate-600 dark:text-gray-400">
                @error('employee_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                <select wire:model="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                    <option value="">Select Role</option>
                    @if(!$isManager)
                        <option value="manager">Manager</option>
                    @endif
                    <option value="cashier">Cashier</option>
                </select>
                @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input type="password" wire:model="plain_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                @error('plain_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Branch (Optional)</label>
                <select wire:model="branch_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                    <option value="">No Branch</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 flex justify-end mt-4">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Save Employee</button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-900 shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
            <thead class="bg-gray-50 dark:bg-slate-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                    @if(!$isManager)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                    @endif
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 dark:bg-slate-900 dark:divide-slate-800 text-gray-900 dark:text-white">
                @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->employee_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($user->role) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->branch ? $user->branch->name : 'N/A' }}</td>
                        @if(!$isManager)
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-red-500">{{ $user->plain_password ?? 'Hidden' }}</td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($user->id !== auth()->id() && $user->role !== 'admin')
                                <button wire:click="openEditPassword({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Change Password</button>
                                <button wire:click="deleteStaff({{ $user->id }})" onclick="confirm('Are you sure you want to remove this employee?') || event.stopImmediatePropagation()" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Remove</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($editingPasswordId)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-lg shadow-xl max-w-sm w-full">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Change Password</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                <input type="password" wire:model="newPassword" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                @error('newPassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end gap-3">
                <button wire:click="closeEditPassword" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 dark:bg-slate-700 dark:text-gray-200 dark:hover:bg-slate-600">Cancel</button>
                <button wire:click="updatePassword" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
            </div>
        </div>
    </div>
    @endif
</div>
