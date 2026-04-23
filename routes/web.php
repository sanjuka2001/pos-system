<?php

use App\Livewire\CashierTerminal;
use App\Livewire\Admin\InventoryDashboard;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Cashier routes
    Route::middleware(['role:cashier,manager,admin'])->group(function () {
        Route::get('/cashier/dashboard', function () {
            return view('cashier.dashboard');
        })->name('cashier.dashboard');
        
        Route::get('/cashier', CashierTerminal::class)->name('cashier');
    });

    // Manager routes
    Route::middleware(['role:manager,admin'])->group(function () {
        Route::get('/manager/dashboard', function () {
            return view('manager.dashboard');
        })->name('manager.dashboard');
        Route::get('/manager/staff', \App\Livewire\Admin\StaffManagement::class)->name('manager.staff');
        Route::get('/admin/inventory', InventoryDashboard::class)->name('admin.inventory');
    });

    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        
        Route::get('/admin/staff', \App\Livewire\Admin\StaffManagement::class)->name('admin.staff');
        Route::get('/admin/branches', \App\Livewire\Admin\BranchManagement::class)->name('admin.branches');
        Route::get('/admin/settings', \App\Livewire\Admin\SettingsManagement::class)->name('admin.settings');
        Route::get('/admin/reports', \App\Livewire\Admin\ProfitLoss::class)->name('admin.reports');
    });

});
