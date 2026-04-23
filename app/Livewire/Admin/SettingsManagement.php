<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class SettingsManagement extends Component
{
    public $vat_rate, $sscl_rate;

    public function mount()
    {
        $this->vat_rate = Setting::where('key', 'vat_rate')->value('value') ?? '0';
        $this->sscl_rate = Setting::where('key', 'sscl_rate')->value('value') ?? '0';
    }

    public function saveSettings()
    {
        $this->validate([
            'vat_rate' => 'required|numeric|min:0|max:100',
            'sscl_rate' => 'required|numeric|min:0|max:100',
        ]);

        Setting::updateOrCreate(['key' => 'vat_rate'], ['value' => $this->vat_rate]);
        Setting::updateOrCreate(['key' => 'sscl_rate'], ['value' => $this->sscl_rate]);

        session()->flash('message', 'Tax rates updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings-management')->layout('layouts.admin');
    }
}
