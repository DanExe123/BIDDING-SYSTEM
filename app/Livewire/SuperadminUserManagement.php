<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class SuperadminUserManagement extends Component
{
    public function render()
    {
        // Fetch users with their roles, excluding 'Super_Admin'
        $users = User::with('roles')
            ->whereHas('roles', fn ($query) => $query->where('name', '!=', 'Super_Admin'))
            ->get()
            ->groupBy(fn ($user) => str_replace('_', ' ', $user->roles->first()->name ?? 'Unknown'));

        return view('livewire.superadmin-user-management', [
            'groupedUsers' => $users,
        ]);
    }
}
