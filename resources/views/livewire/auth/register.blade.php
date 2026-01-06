<?php

use App\Models\User;
use App\Models\SupplierCategory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('components.layouts.auth')] class extends Component {
    use WithFileUploads;

    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $supplier_category_id = null;
    public string $contact_no = '';

    public $categories = [];
    public $business_permit; // 👈 file upload property

    public function mount()
    {
        $this->categories = SupplierCategory::all();
    }

    public function register(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:' . User::class],
            'supplier_category_id' => ['required', 'exists:supplier_categories,id'],
            'business_permit' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'contact_no' => ['required', 'digits:11', 'unique:users,contact_no'],
             'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()], 
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        $validated['username'] = $validated['email'];

        // Save the uploaded business permit file
        $path = $this->business_permit->store('business_permits', 'public');
        $validated['business_permit'] = $path;
        $validated['bpl_file_name'] = $this->business_permit->getClientOriginalName(); // 👈 save original filename

        // Default account status
        $validated['account_status']    = 'pending';

        // Create the user
        $user = User::create($validated);

        // Assign supplier role using spatie
        $user->assignRole('supplier');

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        if ($user->hasRole('Supplier')) {
            $this->redirectIntended(route('supplier-dashboard', absolute: false), navigate: true);
        } 
    }
};


 ?>


<!-- Register Card -->
<div class="fixed inset-0 flex items-center justify-center w-screen h-screen overflow-hidden"
    x-data="{
        current: 0,
        images: [
            '/icon/bago123.jpg',
            '/icon/Public_Plaza_in_Bago_City.jpg'
        ]
    }"
    x-init="setInterval(() => { current = (current + 1) % images.length }, 3000)"
>

    <!-- Background Images -->
    <template x-for="(image, index) in images" :key="index">
        <div x-show="current === index" x-transition:enter="transition-opacity ease-in-out duration-1000"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in-out duration-1000" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="absolute inset-0 w-full h-full bg-cover bg-center"
            :style="`background-image: url('${image}'); background-size: cover; background-position: center;`">
        </div>
    </template>

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black/40"></div>
    
    <div class="relative z-10 bg-white shadow-lg p-8 w-full max-w-md rounded-2xl border border-gray-300 mx-4">
    <div class="flex flex-col space-y-6">

        <!-- Title -->
        <div class="flex justify-center bg-[#062B4A] py-3 rounded-md">
            <h1 class="font-bold text-2xl text-white text-center">
                Create Account
            </h1>
        </div>

        <!-- Register Form -->
        <form wire:submit="register" class="flex flex-col space-y-5">
            <flux:input
                wire:model="first_name"
                :label="__('Company Name')"
                type="text"
                required
                autofocus
                autocomplete="given-name"
                :placeholder="__('Company name')"
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

            <flux:input
                wire:model="contact_no"
                :label="__('Contact Number')"
                type="text"
                maxlength="11"
                inputmode="numeric"
                pattern="[0-9]*"
                required
                :placeholder="__('e.g. 09171234567')"
            />


            <!-- Supplier Category -->
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

            <!-- Business Permit -->
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Business Permit / License <br> (Mayor’s Permit or Local Business Permit)</label>
                <input 
                    type="file" 
                    wire:model="business_permit" 
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="w-full text-sm border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                    required
                >
                @error('business_permit') 
                    <span class="text-red-600 text-xs">{{ $message }}</span> 
                @enderror

                <div wire:loading wire:target="business_permit" class="text-xs text-gray-500">
                    Uploading file...
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <flux:input
                        wire:model="password"
                        :label="__('Password')"
                        type="password"
                        required
                        autocomplete="new-password"
                        :placeholder="__('Password')"
                    />
                </div>

                <div class="flex flex-col">
                    <flux:input
                        wire:model="password_confirmation"
                        :label="__('Confirm password')"
                        type="password"
                        required
                        autocomplete="new-password"
                        :placeholder="__('Confirm password')"
                    />
                </div>
            </div>


            <!-- Submit -->
            <div class="flex justify-center">
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
</div>