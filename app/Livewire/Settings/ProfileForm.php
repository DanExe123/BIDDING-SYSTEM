<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileForm extends Component
{
    use WithFileUploads;

    public string $first_name = '';
    public string $email = '';
    public $business_permit;
    public ?string $currentBusinessPermit = null;
    public string $contact_no = '';
    public ?string $accountMessage = null;
    public bool $isSupplier = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->isSupplier = $user->hasRole('Supplier');

        $this->first_name = $user->first_name ?? '';
        $this->email      = $user->email ?? '';
        $this->contact_no = $user->contact_no ?? '';
        $this->currentBusinessPermit = $user->bpl_file_name ?? null;

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
                Rule::unique(User::class)->ignore($user->id),
            ],
            // keep only the ignore rule (the previous code duplicated the key)
            'contact_no' => ['nullable', 'digits:11', Rule::unique('users', 'contact_no')->ignore($user->id)],
        ]);

        // Handle business permit upload
        if ($this->business_permit) {
            $path = $this->business_permit->store('business_permits', 'public');
            $user->business_permit = $path;
            $user->bpl_file_name   = $this->business_permit->getClientOriginalName();
            $this->currentBusinessPermit = $user->bpl_file_name;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->first_name . ' ');
    }

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

    public function render()
    {
        return view('livewire.settings.profile-form');
    }
}
