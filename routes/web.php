<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
/// super admin //
use App\Livewire\SuperadminDashboard;
use App\Livewire\SuperadminUserManagement;
use App\Livewire\SuperadminCreateAccount;
use App\Livewire\SuperadminAudittrails;
// bac dashboard //
use App\Livewire\BacDashboard;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'role:Super_Admin'])->group(function () {
    Route::get('/superadmin', SuperadminDashboard::class)->name('superadmin-dashboard'); 
    Route::get('/superadmin-audittrails', SuperadminAudittrails::class)->name('superadmin-audittrails'); 
    Route::get('/superadmin-usermanagement', SuperadminUserManagement::class)->name('superadmin-user-management'); 
    Route::get('/superadmin-CreateAccount', SuperadminCreateAccount::class)->name('superadmin-create-account'); 
   
});
  
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
    Route::get('/bacsec/dashboard', BacDashboard::class)->name('bac-dashboard'); 
   
});








Route::middleware(['auth'])->group(function () {
    

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
