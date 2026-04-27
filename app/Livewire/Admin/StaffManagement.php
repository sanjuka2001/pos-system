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

    public $editingPasswordId = null;
    public $newPassword = '';

    public function mount()
    {
        $this->isManager = auth()->user()->role === 'manager';
        $this->generateEmployeeId();
        $this->loadData();
    }

    public function generateEmployeeId()
    {
        $lastUser = User::orderBy('id', 'desc')->first();
        $nextId = $lastUser ? $lastUser->id + 1 : 1;
        
        do {
            $empId = 'EMP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $exists = User::where('employee_id', $empId)->exists();
            if ($exists) {
                $nextId++;
            }
        } while ($exists);
        
        $this->employee_id = $empId;
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

        $this->reset(['name', 'role', 'plain_password', 'branch_id']);
        $this->generateEmployeeId();
        $this->loadData();
        session()->flash('message', 'Staff member created successfully.');
    }

    public function openEditPassword($id)
    {
        $this->editingPasswordId = $id;
        $this->newPassword = '';
    }

    public function closeEditPassword()
    {
        $this->editingPasswordId = null;
        $this->newPassword = '';
    }

    public function updatePassword()
    {
        $this->validate(['newPassword' => 'required|string|min:6']);
        $user = User::find($this->editingPasswordId);
        if ($user) {
            $user->password = Hash::make($this->newPassword);
            $user->plain_password = $this->newPassword;
            $user->save();
            session()->flash('message', 'Password updated successfully.');
        }
        $this->closeEditPassword();
        $this->loadData();
    }

    public function deleteStaff($id)
    {
        $user = User::find($id);
        if ($user && $user->id !== auth()->id() && $user->role !== 'admin') {
            $user->delete();
            session()->flash('message', 'Employee removed successfully.');
        } else {
            session()->flash('error', 'Cannot remove this user.');
        }
        $this->loadData();
    }

    public function render()
    {
        if ($this->isManager) {
            return view('livewire.admin.staff-management')->layout('layouts.manager');
        }
        return view('livewire.admin.staff-management')->layout('layouts.admin');
    }
}
