<!-- Background Slideshow Wrapper -->
<div x-data="{
    current: 0,
    images: [
        '/icon/bago123.jpg',
        '/icon/Public_Plaza_in_Bago_City.jpg'
    ]
}" x-init="setInterval(() => { current = (current + 1) % images.length }, 3000)"
    class="relative flex items-center justify-center h-screen w-screen overflow-hidden">

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

    <!-- Login Card -->
    <div class="relative z-10 bg-white shadow-lg p-8 w-full max-w-md rounded-2xl border border-gray-300 mx-4">
        <div class="flex flex-col gap-6">

            <!-- Session Status -->
            <x-auth-session-status class="text-center" :status="session('status')" />

            <!-- Title -->
            <div class="flex justify-center bg-[#062B4A] py-3 rounded-md">
                <h1 class="font-bold text-2xl text-white text-center">
                    Login
                </h1>
            </div>

            <form wire:submit="login" class="flex flex-col gap-6 items-center">
                <flux:input wire:model="email" :label="__('Email')" type="email" required autofocus
                    autocomplete="email" placeholder="email@example.com" class="!w-70" />

                <flux:input wire:model="password" :label="__('Password')" type="password" required
                    autocomplete="current-password" :placeholder="__('Password')" class="!w-70" />

                <div class="flex justify-center w-full">
                    <flux:button type="submit" class="w-40 !bg-[#FAEA55] text-black">
                        {{ __('Log in') }}
                    </flux:button>
                </div>
            </form>

            @if (Route::has('password.request'))
                <flux:link class="text-center text-sm text-[#062B4A] dark:text-zinc-400 underline"
                    :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>
    </div>
</div>
