<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SupplierCategory;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperadminEditAccount extends Component
{
    public User $user;

    public $first_name, $last_name, $middle_initial, $email, $username;
    public $account_type, $password, $confirm_password, $supplier_category_id;
    public $categories;

    public function mount(User $user)
    {
        $this->user = $user;

        // Load user data into form fields
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->middle_initial = $user->middle_initial;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->account_type = $user->roles->pluck('name')->first();
        $this->supplier_category_id = $user->supplier_category_id;

        // Fetch categories for dropdown
        $this->categories = SupplierCategory::all()->map(function($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
            ];
        })->toArray();
    }

    public function update()
    {
        // Validate fields
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->user->id,
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'account_type' => 'required|string',
            'supplier_category_id' => $this->account_type === 'Supplier' 
                ? 'required|exists:supplier_categories,id' 
                : 'nullable',
        ]);

        // Update user details
        $this->user->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_initial' => $this->middle_initial,
            'email' => $this->email,
            'username' => $this->username,
            'supplier_category_id' => $this->account_type === 'Supplier' ? $this->supplier_category_id : null,
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
        $this->user->syncRoles([str_replace(' ', '_', $this->account_type)]);

        session()->flash('success', 'User updated successfully.');

        return redirect()->route('superadmin-user-management');
    }

    public function render()
    {
        return view('livewire.superadmin-edit-account', [
            'roles' => Role::where('name', '!=', 'Super_Admin')->pluck('name')->map(fn($r) => str_replace('_',' ',$r))->toArray(),
        ]);
    }
}
