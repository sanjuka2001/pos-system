<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'plain_password' => 'password123'
        ]);

        User::create([
            'name' => 'Manager One',
            'email' => 'manager@example.com',
            'employee_id' => 'MGR001',
            'password' => bcrypt('manager123'),
            'role' => 'manager',
            'plain_password' => 'manager123'
        ]);

        User::create([
            'name' => 'Cashier One',
            'email' => 'cashier@example.com',
            'employee_id' => 'CSH001',
            'password' => bcrypt('cashier123'),
            'role' => 'cashier',
            'plain_password' => 'cashier123'
        ]);

        $this->call(ProductSeeder::class);
        $this->call(SettingsSeeder::class);
    }
}
