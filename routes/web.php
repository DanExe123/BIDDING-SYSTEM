<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
/// super admin //
use App\Livewire\SuperadminDashboard;
use App\Livewire\SuperadminUserManagement;
use App\Livewire\SuperadminCreateAccount;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'role:Admin|Super_Admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Admin Dashboard';
    })->name('admin.dashboard');
});

  /* TEMPORARY LANG NI SINCE WAY PA USER ROLES . KAKSA LANG ANG MIDDLEWARE AUTH KG HAPLOK SA SUPER ADMIN ROLE */
    Route::get('/superadmin', SuperadminDashboard::class)->middleware(['auth'])->name('superadmin-dashboard');
    Route::get('/superadmin-usermanagement', SuperadminUserManagement::class)  ->middleware(['auth']) ->name('superadmin-user-management'); 
    Route::get('/superadmin-CreateAccount', SuperadminCreateAccount::class)  ->middleware(['auth']) ->name('superadmin-create-account'); 
    
Route::middleware(['auth', 'role:Supplier'])->group(function () {
    Route::get('/supplier/dashboard', function () {
        return 'Supplier Dashboard';
    })->name('supplier.dashboard');
});

Route::middleware(['auth', 'role:Purchaser'])->group(function () {
    Route::get('/purchaser/dashboard', function () {
        return 'Purchaser Dashboard';
    })->name('purchaser.dashboard');
});

Route::middleware(['auth', 'role:BAC_Sec'])->group(function () {
    Route::get('/bacsec/dashboard', function () {
        return 'BAC Sec Dashboard';
    })->name('bacsec.dashboard');
});








Route::middleware(['auth'])->group(function () {
    

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
