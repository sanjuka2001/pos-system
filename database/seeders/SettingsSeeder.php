<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Store Information
            ['key' => 'store_name',    'value' => 'My POS Store',       'group' => 'store',   'label' => 'Store Name'],
            ['key' => 'store_address', 'value' => '123 Main Street',    'group' => 'store',   'label' => 'Store Address'],
            ['key' => 'store_phone',   'value' => '0771234567',         'group' => 'store',   'label' => 'Store Phone'],
            ['key' => 'store_email',   'value' => 'store@example.com',  'group' => 'store',   'label' => 'Store Email'],
            ['key' => 'store_logo',    'value' => null,                 'group' => 'store',   'label' => 'Store Logo'],

            // Tax Settings
            ['key' => 'tax_enabled',        'value' => 'true',  'group' => 'tax', 'label' => 'Enable Tax'],
            ['key' => 'tax_name',           'value' => 'VAT',   'group' => 'tax', 'label' => 'Tax Name'],
            ['key' => 'tax_rate',           'value' => '10',    'group' => 'tax', 'label' => 'Tax Rate (%)'],
            ['key' => 'tax_number',         'value' => '',      'group' => 'tax', 'label' => 'Tax Registration Number'],
            ['key' => 'prices_include_tax', 'value' => 'false', 'group' => 'tax', 'label' => 'Prices Include Tax'],

            // Receipt Settings
            ['key' => 'receipt_header',    'value' => 'Thank you for your purchase!', 'group' => 'receipt', 'label' => 'Receipt Header'],
            ['key' => 'receipt_footer',    'value' => 'Please come again!',           'group' => 'receipt', 'label' => 'Receipt Footer'],
            ['key' => 'receipt_show_logo', 'value' => 'true',                         'group' => 'receipt', 'label' => 'Show Logo on Receipt'],
            ['key' => 'receipt_show_tax',  'value' => 'true',                         'group' => 'receipt', 'label' => 'Show Tax on Receipt'],

            // Discount Settings
            ['key' => 'discount_enabled',        'value' => 'true',       'group' => 'discount', 'label' => 'Enable Discount'],
            ['key' => 'discount_type',           'value' => 'percentage', 'group' => 'discount', 'label' => 'Discount Type'],
            ['key' => 'discount_max_percentage', 'value' => '10',         'group' => 'discount', 'label' => 'Max Discount Percentage'],
            ['key' => 'discount_max_fixed',      'value' => '500',        'group' => 'discount', 'label' => 'Max Fixed Discount'],
            ['key' => 'discount_require_reason', 'value' => 'false',      'group' => 'discount', 'label' => 'Require Discount Reason'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
