<?php

namespace App\Livewire\Admin;

use App\Models\Branch;
use Livewire\Component;

class BranchManagement extends Component
{
    public $branches;
    public $name, $location, $contact_number;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->branches = Branch::all();
    }

    public function createBranch()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
        ]);

        Branch::create([
            'name' => $this->name,
            'location' => $this->location,
            'contact_number' => $this->contact_number,
        ]);

        $this->reset(['name', 'location', 'contact_number']);
        $this->loadData();
        session()->flash('message', 'Branch created successfully.');
    }

    public function render()
    {
        return view('livewire.admin.branch-management')->layout('layouts.admin');
    }
}
