<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class StoreSettings extends Component
{
    use WithFileUploads;

    public $store_name;
    public $store_address;
    public $store_phone;
    public $store_email;
    public $store_logo;
    public $new_logo;
    public $saved = false;

    public function mount()
    {
        $this->store_name = Setting::get('store_name', '');
        $this->store_address = Setting::get('store_address', '');
        $this->store_phone = Setting::get('store_phone', '');
        $this->store_email = Setting::get('store_email', '');
        $this->store_logo = Setting::get('store_logo');
    }

    public function saveStoreSettings()
    {
        $this->validate([
            'store_name' => 'required|max:100',
            'store_address' => 'nullable|max:255',
            'store_phone' => 'nullable|max:20',
            'store_email' => 'nullable|email',
            'new_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        Setting::set('store_name', $this->store_name);
        Setting::set('store_address', $this->store_address);
        Setting::set('store_phone', $this->store_phone);
        Setting::set('store_email', $this->store_email);

        if ($this->new_logo) {
            $path = $this->new_logo->store('logo', 'public');
            Setting::set('store_logo', $path);
            $this->store_logo = $path;
            $this->new_logo = null;
        }

        Setting::clearCache();
        $this->saved = true;
        $this->dispatch('settings-saved', message: 'Store settings saved successfully!');
    }

    public function removeLogo()
    {
        if ($this->store_logo && \Storage::disk('public')->exists($this->store_logo)) {
            \Storage::disk('public')->delete($this->store_logo);
        }
        Setting::set('store_logo', null);
        $this->store_logo = null;
        $this->new_logo = null;
        Setting::clearCache();
    }

    public function render()
    {
        return view('livewire.settings.store-settings');
    }
}
