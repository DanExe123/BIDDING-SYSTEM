<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads; 
    
    public string $first_name = '';
    public string $email = '';
    public $business_permit; // temporary upload
    public ?string $currentBusinessPermit = null;
    public string $contact_no = '';
    public ?string $accountMessage = null; 
    public bool $isSupplier = false;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->isSupplier = $user->hasRole('Supplier');

        $this->first_name = $user->first_name ?? '';
        $this->email      = $user->email ?? '';
        $this->contact_no = $user->contact_no ?? '';
        $this->currentBusinessPermit = $user->bpl_file_name ?? null;

        // Account status message
        if ($user->account_status !== 'verified') {
            if ($user->account_status === 'rejected') {
                $remarks = $user->remarks ?? 'No remarks provided';
                $this->accountMessage = "Your account was rejected.<br><b>Reason: {$remarks}.</b><br>Please update your details and resubmit for verification.";
            }
            if ($user->account_status === 'pending') {
                $this->accountMessage = "Your account is currently pending verification. Please wait for the admin to review your account. Make sure your information and business permit are complete and up-to-date.";
            }
        }

    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'email'      => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'business_permit' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // max 10MB
        ]);

        unset($validated['business_permit']);

        // Handle business permit upload
        if ($this->business_permit) {
            // Store the file in storage/app/public/business_permits
            $path = $this->business_permit->store('business_permits', 'public');

            // Save the relative path in the DB, NOT the temp file path
            $user->business_permit = $path;  // THIS IS IMPORTANT
            $user->bpl_file_name = $this->business_permit->getClientOriginalName();
            $this->currentBusinessPermit = $user->bpl_file_name;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->first_name . ' ' );
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
};
?>


<div class="flex min-h-screen" x-cloak>
    <!-- Sidebar -->
    @include('partials.user-sidebar')

    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-white min-h-screen">
        <!-- Topbar -->
        <header class="bg-white h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Settings</h1>
            <!-- Right Section -->
            <div class="flex items-center gap-4"> 
        </header>

        <section class="w-full px-10 py-2">
            @include('partials.settings-heading')

            <x-settings.layout :heading="__('Profile')" :subheading="__('Update your account details')">
                @if($isSupplier)
                    @if($accountMessage)
                        <div class="mb-4 p-3 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                            {!! $accountMessage !!}
                        </div>
                    @endif
                @endif

                <form wire:submit="updateProfileInformation" enctype="multipart/form-data" class="my-6 w-full space-y-6">
                    <flux:input wire:model="first_name" :label="__('Name')" type="text" required autofocus autocomplete="given-name" />
                    
                    <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />
                    <flux:input wire:model="contact_no" :label="__('Contact Number')" type="text" readonly :placeholder="__('Your registered contact number')"/>

                    @if($isSupplier)
                        <!-- Business Permit -->
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Business Permit</label>

                            <div class="flex items-center justify-between border border-gray-300 rounded-md p-3 bg-gray-50">
                                <!-- File Name -->
                                <div class="flex-1">
                                    @if($currentBusinessPermit)
                                        <a href="{{ asset('storage/' . Auth::user()->business_permit) }}" target="_blank" class="text-blue-600 underline truncate block">
                                            {{ $currentBusinessPermit }}
                                        </a>
                                    @else
                                        <span class="text-gray-500 text-sm">No file uploaded</span>
                                    @endif

                                    <!-- Selected file preview -->
                                    @if ($business_permit)
                                        <p class="text-sm mt-1 text-gray-700">Selected: {{ $business_permit->getClientOriginalName() }}</p>
                                    @endif
                                </div>

                                <!-- Upload Button -->
                                <div class="flex-shrink-0 ml-4">
                                    <label class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                                        Upload
                                        <input type="file" wire:model="business_permit" class="hidden" />
                                    </label>
                                </div>
                            </div>

                            <!-- Uploading indicator -->
                            <div wire:loading wire:target="business_permit" class="text-sm text-gray-600 mt-1">
                                Uploading file...
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-end">
                            <flux:button class="bg-[#062B4A]" type="submit" class="w-full">{{ __('Update') }}</flux:button>
                        </div>
                        <x-action-message class="me-3" on="profile-updated">
                            {{ __('Updated.') }}
                        </x-action-message>
                    </div>
                </form>

                <!-- <livewire:settings.delete-user-form /> -->
            </x-settings.layout>
        </section>
    </div>
</div>