<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class SuperadminUserManagement extends Component
{
    public string $roleFilter = 'All';

    public $confirmingUserDeletion = false;
    public $userToDelete = null;

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
            session()->flash('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete user.');
        }

        $this->confirmingUserDeletion = false;
        $this->userToDelete = null;
    }


    public function render()
    {
        // Fetch users with their roles, excluding 'Super_Admin'
        $query = User::with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('name', '!=', 'Super_Admin');

                if ($this->roleFilter !== 'All') {
                    $q->where('name', str_replace(' ', '_', $this->roleFilter));
                }
            });

        $users = $query->get();

        return view('livewire.superadmin-user-management', [
            'users' => $users,
        ]);
    }

}
