<?php

use App\Models\User;
use App\Models\SupplierCategory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $supplier_category_id = null;

    public $categories = [];

    public function mount()
    {
        $this->categories = SupplierCategory::all();
    }

    public function register(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'supplier_category_id' => ['required', 'exists:supplier_categories,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['username'] = $validated['email'];

        $user = User::create($validated);

        // assign supplier role using spatie
        $user->assignRole('supplier');

        event(new Registered($user));

        Auth::login($user);

         // redirect based on role
        if ($user->hasRole('Supplier')) {
            $this->redirectIntended(route('supplier-dashboard', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
        }
    }
};

 ?>

<!-- Register Card -->
    <div class="relative z-10 bg-white shadow-lg p-8 w-full max-w-md rounded-2xl border border-gray-300 mx-4">
        <div class="flex flex-col gap-6">

            <!-- Title -->
            <div class="flex justify-center bg-[#062B4A] py-3 rounded-md">
                <h1 class="font-bold text-2xl text-white text-center">
                    Create Account
                </h1>
            </div>

            <!-- Register Form -->
            <form wire:submit="register" class="flex flex-col gap-6">
                <!-- First Name -->
                <flux:input
                    wire:model="first_name"
                    :label="__('First Name')"
                    type="text"
                    required
                    autofocus
                    autocomplete="given-name"
                    :placeholder="__('First name')"
                />

                <!-- Last Name -->
                <flux:input
                    wire:model="last_name"
                    :label="__('Last Name')"
                    type="text"
                    required
                    autocomplete="family-name"
                    :placeholder="__('Last name')"
                />

                <!-- Email -->
                <flux:input
                    wire:model="email"
                    :label="__('Email address')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
                />

                <!-- Supplier Category Dropdown -->
                <div 
                    x-data="{ selected: @entangle('supplier_category_id'), open: false, options: @js($categories) }" 
                    class="relative w-full"
                >
                    <label class="block mb-2 text-sm font-medium text-gray-700">Supplier Category</label>
                    <button type="button"
                        @click="open = !open"
                        class="w-full bg-white border border-gray-400 rounded-md px-4 py-2 text-left shadow-sm flex justify-between items-center">
                        <span class="text-sm" 
                            :class="selected ? 'text-black' : 'text-gray-400'"
                            x-text="options.find(c => c.id == selected)?.name || 'Select Category'">
                        </span>
                        <svg class="w-4 h-4 ml-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul x-show="open" @click.away="open = false" x-transition
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <template x-for="category in options" :key="category.id">
                            <li @click="selected = category.id; open = false"
                                class="px-4 py-2 hover:bg-blue-100 cursor-pointer text-sm"
                                x-text="category.name">
                            </li>
                        </template>
                    </ul>

                    @error('supplier_category_id') 
                        <span class="text-red-600 text-xs">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Password -->
                <flux:input
                    wire:model="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Password')"
                />

                <!-- Confirm Password -->
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Confirm password')"
                />

                <!-- Submit -->
                <div class="flex justify-center w-full">
                    <flux:button type="submit" class="w-40 !bg-[#FAEA55] text-black">
                        {{ __('Register') }}
                    </flux:button>
                </div>
            </form>

            <!-- Already have an account -->
            <p class="text-center text-sm text-gray-700">
                Already registered? 
                <flux:link :href="route('login')" class="underline text-[#062B4A] font-medium" wire:navigate>
                    Log in
                </flux:link>
            </p>
        </div>
    </div>