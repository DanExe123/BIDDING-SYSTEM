<?php

namespace App\Livewire;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class SuperadminCreateAccount extends Component
{
    public $first_name;
    public $last_name;
    public $middle_initial;
    public $username;
    public $email;
    public $password;
    public $confirm_password;
    public $account_type;

    public function createAccount()
    {
        $this->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'username'         => 'required|string|max:255|unique:users,username',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
            'account_type'     => 'required|string',
        ]);

        $user = User::create([
            'first_name'     => $this->first_name,
            'last_name'      => $this->last_name,
            'middle_initial' => $this->middle_initial,
            'username'       => $this->username,
            'email'          => $this->email,
            'password'       => Hash::make($this->password),
        ]);

        // Assign role (convert spaces back to underscores for DB storage)
        $roleName = str_replace(' ', '_', $this->account_type);
        $user->assignRole($roleName);


        // Reset form fields
        $this->reset([
            'first_name', 'last_name', 'middle_initial',
            'username', 'email', 'password', 'confirm_password', 'account_type'
        ]);

        // Redirect to user management page
        return redirect()->route('superadmin-user-management')->with('success', 'Account created successfully!');
    }

    public function render()
    {
        $roles = Role::where('name', '!=', 'Super_Admin')
            ->pluck('name')
            ->map(fn($role) => str_replace('_', ' ', $role));

        return view('livewire.superadmin-create-account', [
            'roles' => $roles
        ]);
    }
}
