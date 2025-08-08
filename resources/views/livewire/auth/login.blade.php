<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
 use App\Models\ActivityLog;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $user = Auth::user();

        if ($user->hasRole('Super_Admin')) {
            $this->redirect(route('superadmin-dashboard'));
        } elseif ($user->hasRole('BAC_Secretary')) {
            $this->redirect(route('bac-dashboard'));
        } elseif ($user->hasRole('Supplier')) {
            $this->redirect(route('supplier-dashboard'));
        } elseif ($user->hasRole('Purchaser')) {
            $this->redirect(route('purchaser-dashboard'));
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Login',
            'location' => request()->ip(), // or use location service
        ]);

    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>
<!-- Outer wrapper to center content both vertically and horizontally -->
<div class="flex items-center justify-center bg-gray-100">
    <!-- Card -->
    <div class="bg-white shadow-sm p-4 w-[700px] rounded-md border border-gray-400">
        <div class="flex flex-col gap-6">
            <!-- Session Status -->
            <x-auth-session-status class="text-center" :status="session('status')" />

            <!-- Title -->
            <div class="flex justify-center bg-[#062B4A] py-2 rounded-md w-[600px]">
                <h1 class="font-bold text-xl text-white text-center">
                    Login 
                </h1>
            </div>

            <!-- Form -->
            <form wire:submit="login" class="flex flex-col gap-6 items-center">
                <!-- Email -->
                <flux:input
                    wire:model="email"
                    :label="__('Email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="!w-70"
                />

                <!-- Password -->
                <flux:input
                    wire:model="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    class="!w-70"
                />

                <!-- Submit -->
                <div class="flex justify-center w-full">
                    <flux:button type="submit" class="w-40 !bg-[#FAEA55] text-black">
                        {{ __('Log in') }}
                    </flux:button>
                </div>
            </form>

            <!-- Forgot Password -->
            @if (Route::has('password.request'))
                <flux:link
                    class="text-center text-sm text-[#062B4A] dark:text-zinc-400 underline"
                    :href="route('password.request')"
                    wire:navigate
                >
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>
    </div>
</div>
