<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\SupplierCategory;
use App\Models\ImplementingUnit;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class SuperadminCreateAccount extends Component
{
    public $first_name;
    public $username;
    public $email;
    public $password;
    public $confirm_password;
    public $account_type;
    public $supplier_category_id;
    public $categories;
    public $implementing_unit_id;
    public $units;

    public function createAccount()
    {
        $this->validate([
            'first_name'       => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
            'account_type'     => 'required|string',
             // Check supplier category only if account_type is Supplier
            'supplier_category_id' => $this->account_type === 'Supplier' ? 'required|exists:supplier_categories,id' : 'nullable',
            'implementing_unit_id' => $this->account_type === 'Purchaser' ? 'required|exists:implementing_units,id' : 'nullable',
        ]);

        $roleName = str_replace(' ', '_', $this->account_type);

        // Check if the role is Supplier (ignore underscores)
        $isSupplier = str_replace('_', ' ', $roleName) === 'Supplier';
        $isPurchaser = str_replace('_', ' ', $roleName) === 'Purchaser';
        

        $user = User::create([
            'first_name'     => $this->first_name,
            'email'          => $this->email,
            'password'       => Hash::make($this->password),
            'account_status' => 'verified',
            'supplier_category_id' => $isSupplier ? $this->supplier_category_id : null,
            'implementing_unit_id' => $isPurchaser ? $this->implementing_unit_id : null,
        ]);

        // Assign role (convert spaces back to underscores for DB storage)
        $user->assignRole($roleName);


        // Reset form fields
        $this->reset([
            'first_name',
            'email', 'password', 'confirm_password', 'account_type', 'supplier_category_id','implementing_unit_id'
        ]);

        // Redirect to user management page
        return redirect()->route('superadmin-user-management')->with('success', 'Account created successfully!');
    }

    public function mount()
    {
        // Fetch categories as [id => name] array
        $this->categories = SupplierCategory::all()->map(function($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
            ];
        })->toArray();

         $this->units = ImplementingUnit::all()->map(fn($u) => ['id' => $u->id, 'name' => $u->name])->toArray(); 

    }



    public function render()
    {
        $roles = Role::where('name', '!=', 'Super_Admin')
            ->pluck('name')
            ->map(fn($role) => str_replace('_', ' ', $role));

        return view('livewire.superadmin-create-account', [
            'roles' => $roles,
        ]);
    }

}
