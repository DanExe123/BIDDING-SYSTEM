<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperadminEditAccount extends Component
{
    public User $user;

    public $first_name, $last_name, $middle_initial, $email, $username;
    public $account_type, $password, $confirm_password;

    public function mount(User $user)
    {
        $this->user = $user;

        // Load user data into form fields
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->middle_initial = $user->middle_initial;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->account_type = $user->roles->pluck('name')->first(); // Load current role
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'account_type' => 'required|exists:roles,name',
        ]);

        // Update user details
        $this->user->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_initial' => $this->middle_initial,
            'email' => $this->email,
            'username' => $this->username,
        ]);

        // Update password if filled
        if ($this->password) {
            $this->validate([
                'password' => 'required|min:6|same:confirm_password',
            ]);

            $this->user->update([
                'password' => Hash::make($this->password),
            ]);
        }

        // Sync role using Spatie
        $this->user->syncRoles([$this->account_type]);

        session()->flash('success', 'User updated successfully.');

        return redirect()->route('superadmin-user-management'); // Replace with your actual route
    }

    public function render()
    {
        return view('livewire.superadmin-edit-account', [
            'roles' => Role::pluck('name')->toArray(), 
        ]);
    }
}
