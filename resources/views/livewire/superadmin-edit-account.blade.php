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
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
            <h1 class="text-xl font-semibold">Edit Account</h1>
        </div>


        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1">
    
        <div class="bg-[#C5D7E6] p-4 rounded-md shadow-md border border-gray-300 relative h-auto">
            <div class="grid grid-flow-col grid-rows-4 gap-4">
                <x-input label="First Name" placeholder="First Name" wire:model="first_name" class="!border !border-gray-400 rounded-lg" />

                <div x-data="{ selected: @entangle('account_type'), open: false, options: @js($roles) }" class="relative w-full">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Select Account Type</label>
                    <button @click="open = !open" class="w-full bg-white border border-gray-400 rounded-md px-4 py-2 text-left shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <span class="text-sm w-full" :class="selected ? 'text-black' : 'text-gray-400'" x-text="selected || 'Select Account Type'"></span>
                    </button>
                    <ul x-show="open" @click.away="open = false" x-transition class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                        <template x-for="option in options" :key="option">
                            <li @click="selected = option; open = false" class="px-4 py-2 hover:bg-blue-100 cursor-pointer" x-text="option"></li>
                        </template>
                    </ul>
                </div>

                <x-input label="Username" wire:model="username" class="!border !border-gray-400 rounded-lg" />
                <x-input label="Password" wire:model="password" type="password" class="!border !border-gray-400 rounded-lg" />
                <x-input label="Last Name" wire:model="last_name" class="!border !border-gray-400 rounded-lg" />

                <div x-data="{ selected: '', open: false, options: ['BAC Secretary', 'Bidder', 'Purchaser'] }" class="relative w-full">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Role/Position</label>
                    <button @click="open = !open" class="w-full bg-white border border-gray-400 rounded-md px-4 py-2 text-left shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <span class="text-gray-400 text-xs w-full" x-text="selected || 'Select Role'"></span>
                    </button>
                    <ul x-show="open" @click.away="open = false" x-transition class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
                        <template x-for="option in options" :key="option">
                            <li @click="selected = option; open = false" class="px-4 py-2 hover:bg-blue-100 cursor-pointer" x-text="option"></li>
                        </template>
                    </ul>
                </div>

                <x-input label="Email" wire:model="email" class="!border !border-gray-400 rounded-lg" />
                <x-input label="Confirm Password" wire:model="confirm_password" type="password" class="!border !border-gray-400 rounded-lg" />
                <x-input label="Middle Initial" wire:model="middle_initial" class="!border !border-gray-400 rounded-lg" />
            </div>

            <div class="flex justify-center mt-6 space-x-4">
                <a href="{{ route('superadmin-user-management') }}" 
                  class="bg-gray-500 text-white px-6 py-2 rounded-md text-sm hover:bg-gray-600 transition">
                    Cancel
                </a>
                <x-button wire:click="update" spinner="update" primary class="!bg-[#FAEA55] text-black !px-10">
                    Update Account
                </x-button>
            </div>

        </div>

      
      </main>
    </div>
  </div>
  