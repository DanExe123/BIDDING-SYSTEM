<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')

    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
        <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <div class="flex items-center gap-2 px-4 py-2">
                <a href="{{ route('superadmin-user-management') }}" class="text-xl font-semibold hover:underline">
                    User Management
                 </a>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                <h1 class="text-xl font-semibold">Create Account</h1>
            </div>
            <div>
                <button class="text-gray-500 hover:text-black">
                    <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
                </button>
            </div>
        </header>

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="absolute top-4 right-4 z-[9999] flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
                role="alert">
                <div
                    class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z" />
                    </svg>
                    <span class="sr-only">{{ session('message') }}</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ session('message') }}</div>
                <button type="button" @click="show = false"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    aria-label="Close">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Page content -->
        <main class="p-6 space-y-6 flex-1">
            <!-- Announcement Box -->
            <div class="bg-[#C5D7E6] p-6 rounded-md shadow-md border border-gray-300 relative h-auto space-y-6">

                <!-- Row 1: Names (3 columns) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-input label="Name" placeholder="Name" wire:model="first_name"
                        class="!border !border-gray-400 rounded-lg" />
                    <x-input label="Email" placeholder="Enter Email" wire:model="email"
                            class="!border !border-gray-400 rounded-lg" />
                </div>

                <!-- Row 2: Account Type (2 columns) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Account Type Dropdown -->
                    <div x-data="{ selected: @entangle('account_type'), open: false, options: @js($roles) }" class="relative w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Account Type</label>
                        <button @click="open = !open"
                            class="w-full bg-white border border-gray-400 rounded-md px-4 py-2 text-left shadow-sm">
                            <span class="text-sm" :class="selected ? 'text-black' : 'text-gray-400'"
                                x-text="selected || 'Select Account Type'">
                            </span>
                        </button>
                        <ul x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                            <template x-for="option in options.filter(role => role !== 'Supplier')" :key="option">
                                <li @click="selected = option; open = false"
                                    class="px-4 py-2 hover:bg-blue-100 cursor-pointer"
                                    x-text="option">
                                </li>
                            </template>
                        </ul>
                        @error('account_type')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Supplier Category Dropdown (only show if Supplier) -->
                    <div x-data="{ selected: @entangle('supplier_category_id'), open: false, options: @js($categories) }" class="relative w-full" x-show="$wire.account_type === 'Supplier'">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Supplier Category</label>
                        <button @click="open = !open"
                            class="w-full bg-white border border-gray-400 rounded-md px-4 py-2 text-left shadow-sm">
                            <span class="text-sm" :class="selected ? 'text-black' : 'text-gray-400'"
                                x-text="options.find(c => c.id == selected)?.name || 'Select Category'">
                            </span>
                        </button>
                        <ul x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                            <template x-for="category in options" :key="category.id">
                                <li @click="selected = category.id; open = false"
                                    class="px-4 py-2 hover:bg-blue-100 cursor-pointer" x-text="category.name">
                                </li>
                            </template>
                        </ul>
                        @error('supplier_category_id')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Implementing Unit (only show if Purchaser) - NEW -->
                    <div x-data="{ selected: @entangle('implementing_unit_id'), open: false, options: @js($units) }" 
                        class="relative w-full" x-show="$wire.account_type === 'Purchaser'">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Implementing Unit</label>
                        <button @click="open = !open"
                                class="w-full bg-white border border-gray-400 rounded-md px-4 py-2 text-left shadow-sm">
                            <span class="text-sm" :class="selected ? 'text-black' : 'text-gray-400'"
                                x-text="options.find(u => u.id == selected)?.name || 'Select Unit'">
                            </span>
                        </button>
                        <ul x-show="open" @click.away="open = false" x-transition
                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                            <template x-for="unit in options" :key="unit.id">
                                <li @click="selected = unit.id; open = false"
                                    class="px-4 py-2 hover:bg-blue-100 cursor-pointer" x-text="unit.name">
                                </li>
                            </template>
                        </ul>
                        @error('implementing_unit_id')
                            <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-input label="Password" placeholder="Password" wire:model="password" type="password"
                        class="!border !border-gray-400 rounded-lg" />
                    <x-input label="Confirm Password" placeholder="Confirm Password" wire:model="confirm_password"
                        type="password" class="!border !border-gray-400 rounded-lg" />
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center pt-4">
                    <x-button 
                        wire:click="createAccount" 
                        wire:loading.attr="disabled" 
                        primary
                        class="!bg-[#FAEA55] text-black !px-10 flex items-center gap-2"
                    >
                        <!-- Normal State -->
                        <span wire:loading.remove wire:target="createAccount">Create Account</span>

                        <!-- Loading State -->
                        <span wire:loading wire:target="createAccount" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>
                            </svg>
                        </span>
                    </x-button>
                </div>


            </div>

        </main>
    </div>
</div>
