<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
/// super admin //
use App\Livewire\SuperadminDashboard;
use App\Livewire\SuperadminUserManagement;
use App\Livewire\SuperadminCreateAccount;
use App\Livewire\SuperadminEditAccount;
use App\Livewire\SuperadminAudittrails;
// bac dashboard //
use App\Livewire\BacDashboard;
use App\Livewire\BacProcurementPlanning;
use App\Livewire\BacBidInvitation;
use App\Livewire\BacBidEvaluation;
use App\Livewire\NoticeOfAward;
use App\Livewire\GenerateReport;
// supplier side // 
use App\Livewire\SupplierDashboard;
use App\Livewire\SupplierBidEvaluation;
use App\Livewire\SupplierBidInitiation;
use App\Livewire\SupplierBidParticipation;
// purchaser side // 
use App\Livewire\PurchaserDashboard;
use App\Livewire\PurchaserProcurementPlanning;
use App\Livewire\PurchaserBidMonitoring;
use App\Livewire\InspectionReport;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'role:Super_Admin'])->group(function () {
    Route::get('/superadmin', SuperadminDashboard::class)->name('superadmin-dashboard'); 
    Route::get('/superadmin-audittrails', SuperadminAudittrails::class)->name('superadmin-audittrails'); 
    Route::get('/superadmin-usermanagement', SuperadminUserManagement::class)->name('superadmin-user-management'); 
    Route::get('/superadmin-CreateAccount', SuperadminCreateAccount::class)->name('superadmin-create-account'); 
    Route::get('/superadmin-EditAccount/{user}', SuperadminEditAccount::class)->name('superadmin-edit-account');
});
  
Route::middleware(['auth', 'role:Supplier'])->group(function () {
    Route::get('/supplier/dashboard', SupplierDashboard::class)->name('supplier-dashboard'); 
    Route::get('/supplier/bid-initiation', SupplierBidInitiation::class)->name('supplier-bid-initiation'); 
    Route::get('/supplier/bid-evaluation', SupplierBidEvaluation::class)->name('supplier-bid-evaluation'); 
    Route::get('/supplier/bid-participation', SupplierBidParticipation::class)->name('supplier-bid-participation'); 
 
});

    Route::middleware(['auth', 'role:Supplier|BAC_Secretary'])->group(function () {
        Route::get('/notice-of-award', NoticeOfAward::class)->name('notice-of-award'); 
    });

    Route::middleware(['auth', 'role:Purchaser'])->group(function () {
        Route::get('/purchaser/dashboard', PurchaserDashboard::class)->name('purchaser-dashboard');
        Route::get('/purchaser/procurement-planning', PurchaserProcurementPlanning::class)->name('purchaser-procurement-planning');
        Route::get('/purchaser/bid-monitoring', PurchaserBidMonitoring::class)->name('purchaser-bid-monitoring');
        Route::get('/purchaser/inspection-report', InspectionReport::class)->name('inspection-report');
    });

    Route::middleware(['auth', 'role:BAC_Secretary'])->group(function () {
        Route::get('/bacsec/dashboard', BacDashboard::class)->name('bac-dashboard'); 
        Route::get('/bacsec/procurement-planning', BacProcurementPlanning::class)->name('bac-procurement-planning'); 
        Route::get('/bacsec/bid-invitation', BacBidInvitation::class)->name('bac-bid-invitation'); 
        Route::get('/bacsec/bid-evaluation', BacBidEvaluation::class)->name('bac-bid-evaluation'); 
    //  Route::get('/notice-of-award', NoticeOfAward::class)->name('notice-of-award'); 
        Route::get('/generate-report',GenerateReport::class)->name('generate-report');
    
    });








Route::middleware(['auth'])->group(function () {
    

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
