<div class="flex min-h-screen" x-cloak>
    <!-- Sidebar -->
    @include('partials.user-sidebar')

    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-white min-h-screen">
        <!-- Topbar -->
        <header class="bg-white h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Settings</h1>
            <div class="flex items-center gap-4"></div>
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

                    @if($isSupplier)
                        <flux:input
                            wire:model="contact_no"
                            :label="__('Contact Number')"
                            type="text"
                            readonly
                            :placeholder="__('Your registered contact number')"
                        />

                        <!-- Business Permit -->
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Business Permit</label>

                            <div class="flex items-center justify-between border border-gray-300 rounded-md p-3 bg-gray-50">
                                <div class="flex-1">
                                    @if($currentBusinessPermit)
                                        <a href="{{ asset('storage/' . Auth::user()->business_permit) }}" target="_blank" class="text-blue-600 underline truncate block">
                                            {{ $currentBusinessPermit }}
                                        </a>
                                    @else
                                        <span class="text-gray-500 text-sm">No file uploaded</span>
                                    @endif

                                    @if ($business_permit)
                                        <p class="text-sm mt-1 text-gray-700">
                                            Selected: {{ $business_permit->getClientOriginalName() }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex-shrink-0 ml-4">
                                    <label class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                                        Upload
                                        <input type="file" wire:model="business_permit" class="hidden" />
                                    </label>
                                </div>
                            </div>

                            <div wire:loading wire:target="business_permit" class="text-sm text-gray-600 mt-1">
                                Uploading file...
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-end">
                            <flux:button class="bg-[#062B4A]" type="submit" class="w-full">
                                {{ __('Update') }}
                            </flux:button>
                        </div>
                        <x-action-message class="me-3" on="profile-updated">
                            {{ __('Updated.') }}
                        </x-action-message>
                    </div>
                </form>
            </x-settings.layout>
        </section>
    </div>
</div>
