<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class SuperadminUserManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';


    public string $roleFilter = 'All';

    public $confirmingUserDeletion = false;
    public $userToDelete = null;

    public $showVerificationModal = false;
    public $showRejectModal = false; // 👈 new modal for rejection
    public $selectedSupplier;

    public $rejectionRemarks = ''; // 👈 remarks input

    public function confirmDelete($userId)
    {
        $this->userToDelete = $userId;
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser()
    {
        $user = User::findOrFail($this->userToDelete);

        try {
            $user->delete();
            session()->flash('message', 'User deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('message', 'Failed to delete user.');
        }

        $this->confirmingUserDeletion = false;
        $this->userToDelete = null;
    }

    public function updateAccountStatus($userId, $status)
    {
        $user = User::find($userId);

        if (!$user) {
            session()->flash('message', 'User not found.');
            return;
        }

        $user->account_status = $status;
        $user->save();

        session()->flash('message', "Account status updated to {$status}.");
    }

    public function openVerificationModal($userId)
    {
        $this->selectedSupplier = User::with('roles')->findOrFail($userId);
        $this->showVerificationModal = true;
    }

    public function openRejectModal()
    {
        $this->showRejectModal = true;
        $this->rejectionRemarks = '';
    }

    public function verifySupplier()
    {
        if ($this->selectedSupplier) {
            $this->selectedSupplier->account_status = 'verified';
            $this->selectedSupplier->save();

            session()->flash('message', 'Supplier verified successfully.');
            $this->showVerificationModal = false;
        }
    }

    public function confirmRejectSupplier()
    {
        if ($this->selectedSupplier) {
            $this->selectedSupplier->account_status = 'rejected';
            $this->selectedSupplier->remarks = $this->rejectionRemarks; // optional if you added 'remarks' in DB
            $this->selectedSupplier->save();

            session()->flash('message', 'Supplier rejected with remarks.');
            $this->showVerificationModal = false;
            $this->showRejectModal = false;
        }
    }

    public function render()
{
    $query = User::with('roles')
        ->whereHas('roles', function ($q) {
            $q->where('name', '!=', 'Super_Admin');

            if ($this->roleFilter !== 'All') {
                $q->where('name', str_replace(' ', '_', $this->roleFilter));
            }
        });

    // 🔥 If logged-in user is BAC_Secretary → ONLY show Supplier users
    if (Auth::user()->roles->first()->name === 'BAC_Secretary') {
        $query = User::with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Supplier');
            });
    }

    $users = $query->orderBy('created_at', 'desc')->paginate(11);

    return view('livewire.superadmin-user-management', [
        'users' => $users,
    ]);
}

}
