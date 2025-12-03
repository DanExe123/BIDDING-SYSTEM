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
