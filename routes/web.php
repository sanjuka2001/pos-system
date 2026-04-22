<?php

use App\Livewire\CashierTerminal;
use App\Livewire\Admin\InventoryDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('cashier.dashboard');
})->name('login.submit');

// Cashier (Livewire - reactive)
Route::get('/cashier', CashierTerminal::class)->name('cashier');

// Cashier Dashboard (static UI)
Route::get('/cashier/dashboard', function () {
    return view('cashier.dashboard');
})->name('cashier.dashboard');

// Manager Dashboard
Route::get('/manager/dashboard', function () {
    return view('manager.dashboard');
})->name('manager.dashboard');

// Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// Admin Inventory (Livewire - reactive CRUD)
Route::get('/admin/inventory', InventoryDashboard::class)->name('admin.inventory');
