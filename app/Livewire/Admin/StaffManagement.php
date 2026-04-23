<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class StaffManagement extends Component
{
    public $users, $branches;
    public $name, $employee_id, $role, $plain_password, $branch_id;
    public $isManager = false;

    public function mount()
    {
        $this->isManager = auth()->user()->role === 'manager';
        $this->loadData();
    }

    public function loadData()
    {
        if ($this->isManager) {
            // Manager can only see cashiers or their own branch users
            $this->users = User::where('role', 'cashier')->get();
        } else {
            $this->users = User::all();
        }
        $this->branches = Branch::all();
    }

    public function createStaff()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|unique:users,employee_id',
            'role' => 'required|in:manager,cashier',
            'plain_password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->employee_id . '@pos.local', // Dummy email
            'employee_id' => $this->employee_id,
            'role' => $this->role,
            'plain_password' => $this->plain_password,
            'password' => Hash::make($this->plain_password),
            'branch_id' => $this->branch_id ?: null,
        ]);

        $this->reset(['name', 'employee_id', 'role', 'plain_password', 'branch_id']);
        $this->loadData();
        session()->flash('message', 'Staff member created successfully.');
    }

    public function render()
    {
        if ($this->isManager) {
            return view('livewire.admin.staff-management')->layout('layouts.manager');
        }
        return view('livewire.admin.staff-management')->layout('layouts.admin');
    }
}
