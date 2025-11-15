<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Loginform;
use App\Livewire\AnnouncementPage;

/// super admin //
use App\Livewire\SuperadminDashboard;
use App\Livewire\SuperadminUserManagement;
use App\Livewire\SuperadminCreateAccount;
use App\Livewire\SuperadminEditAccount;
use App\Livewire\SuperadminAudittrails;
// bac dashboard //
use App\Livewire\BacDashboard;
use App\Livewire\BacProcurementPlanning;
use App\Livewire\NoticeOfAward;
use App\Livewire\GenerateReport;
/// procurement module //
use App\Livewire\BacModeOfProcurement;
use App\Livewire\BacProcurementWorkflow;
use App\Livewire\BacNoticeOfAward;

// supplier side // 
use App\Livewire\SupplierDashboard;
use App\Livewire\SupplierInvitations;
use App\Livewire\SupplierParticipation;
use App\Livewire\SupplierProposalSubmission;

use App\Livewire\SupplierBidEvaluation;
use App\Livewire\SupplierBidInitiation;
use App\Livewire\SupplierBidParticipation;
use App\Livewire\SupplierBidding;
use App\Livewire\SupplierQuotation;
use App\Livewire\SupplierNoticeOfAward;
// purchaser side // 
use App\Livewire\PurchaserDashboard;
use App\Livewire\PurchaserProcurementPlanning;
use App\Livewire\PurchaserBidMonitoring;
use App\Livewire\InspectionReport;
use App\Livewire\PurchaserNotificationBell;
use App\Livewire\BacNotificationBell;
use App\Livewire\SupplierNotificationBell;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/user-help', function () {
    return view('user-help');
})->name('user-help');



Route::middleware(['auth', 'role:Super_Admin'])->group(function () {
    Route::get('/superadmin', SuperadminDashboard::class)->name('superadmin-dashboard'); 
    Route::get('/superadmin-audittrails', SuperadminAudittrails::class)->name('superadmin-audittrails'); 
    Route::get('/superadmin-usermanagement', SuperadminUserManagement::class)->name('superadmin-user-management'); 
    Route::get('/superadmin-CreateAccount', SuperadminCreateAccount::class)->name('superadmin-create-account'); 
    Route::get('/superadmin-EditAccount/{user}', SuperadminEditAccount::class)->name('superadmin-edit-account');
    Route::get('/announcements', AnnouncementPage::class)->name('announcement-page');
    
    
    
});
  
Route::middleware(['auth', 'role:Supplier'])->group(function () {
    Route::get('/supplier/dashboard', SupplierDashboard::class)->name('supplier-dashboard'); 
    Route::get('/supplier/invitations', SupplierInvitations::class)->name('supplier-invitations'); 
    Route::get('/supplier/participation', SupplierParticipation::class)->name('supplier-participation'); 
    Route::get('/supplier/proposal-submission', SupplierProposalSubmission::class)->name('supplier-proposal-submission'); 
    Route::get('/supplier/bidding', SupplierBidding::class)->name('supplier-bidding'); 
    Route::get('/supplier/quotation', SupplierQuotation::class)->name('supplier-quotation'); 
    Route::get('/supplier/bid-initiation', SupplierBidInitiation::class)->name('supplier-bid-initiation'); 
    Route::get('/supplier/bid-evaluation', SupplierBidEvaluation::class)->name('supplier-bid-evaluation'); 
    Route::get('/supplier/bid-participation', SupplierBidParticipation::class)->name('supplier-bid-participation'); 
    Route::get('/supplier-notice-of-award', SupplierNoticeOfAward::class)->name('supplier-notice-of-award'); 
    Route::get('/bell', SupplierNotificationBell::class)->name('supplier-notification-bell'); 
 
});

    Route::middleware(['auth', 'role:Supplier|BAC_Secretary'])->group(function () {
        Route::get('/notice-of-award', NoticeOfAward::class)->name('notice-of-award'); 
    });

    Route::middleware(['auth', 'role:Purchaser'])->group(function () {
        Route::get('/purchaser/dashboard', PurchaserDashboard::class)->name('purchaser-dashboard');
        Route::get('/purchaser/procurement-planning', PurchaserProcurementPlanning::class)->name('purchaser-procurement-planning');
        Route::get('/purchaser/bid-monitoring', PurchaserBidMonitoring::class)->name('purchaser-bid-monitoring');
        Route::get('/purchaser/inspection-report', InspectionReport::class)->name('inspection-report');
        Route::get('/bell', PurchaserNotificationBell::class)->name('purchaser-notification-bell');
    });

    Route::middleware(['auth', 'role:BAC_Secretary'])->group(function () {
        Route::get('/bacsec/dashboard', BacDashboard::class)->name('bac-dashboard');
        // temporary for purchase request route // 
        Route::get('/bacsec/purchase-request', BacProcurementPlanning::class)->name('bac-procurement-planning');
    //  Route::get('/notice-of-award', NoticeOfAward::class)->name('notice-of-award'); 
        Route::get('/generate-report/{ppmpId}', GenerateReport::class)->name('generate.report');
        //procurement planning module // 
        Route::get('/bac-mode-of-procurement',BacModeOfProcurement::class)->name('bac-mode-of-procurement');
        Route::get('/bac-procurement-workflow',BacProcurementWorkflow::class)->name('bac-procurement-workflow');
        Route::get('/bac-notice-of-award', BacNoticeOfAward::class)->name('bac-notice-of-award'); 
        Route::get('/bell', BacNotificationBell::class)->name('bac-notification-bell'); 
    
    });

    Route::get('/award/{id}/pdf', [\App\Http\Controllers\AwardController::class, 'generateAward'])
        ->name('award.pdf');
    Route::get('/ppmp/{id}/award-quotation', [\App\Http\Controllers\AwardController::class, 'generateQuotationAward'])
        ->name('award.quotation.pdf');


    //fileview&download
    Route::middleware(['auth'])->group(function () {
        Route::get('/download/{submission}/{type}', [\App\Http\Controllers\DownloadController::class, 'file'])
            ->name('submission.download');

        Route::get('/view/{submission}/{type}', [\App\Http\Controllers\DownloadController::class, 'view'])
            ->name('submission.view');
        Route::get('/ppmp/{id}/download', [\App\Http\Controllers\DownloadController::class, 'downloadPpmpAttachment'])
            ->name('ppmp.download');

    });


Route::middleware(['auth'])->group(function () {
    

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
