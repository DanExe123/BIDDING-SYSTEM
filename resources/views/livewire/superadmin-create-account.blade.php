<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.super-admin-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">User Management</h1>
        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1">
      <!-- Announcement Box -->
      <div class="bg-[#C5D7E6] p-4 rounded-md shadow-md border border-gray-300 relative h-auto">
        <div class="grid grid-flow-col grid-rows-4 gap-4">
          <x-input
        label="First Name"
        placeholder="First Name"
        class="!border !border-gray-400 rounded-lg"
        />

        <div x-data="{ selected: '', open: false, options: ['BAC Secretary', 'Bidder', 'Purchaser'] }" class="relative w-full">
            <!-- Label -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Select Account Type</label>
          
            <!-- Dropdown Button -->
            <button
              @click="open = !open"
              class="w-full bg-white border border-gray-400 rounded-md px-4 py-2 text-left shadow-sm focus:outline-none focus:ring focus:border-blue-300"
            >
              <span class="text-gray-400 text-xs w-full" x-text="selected || 'Select Account Type'"></span>
            </button>
          
            <!-- Options Dropdown -->
            <ul
              x-show="open"
              @click.away="open = false"
              x-transition
              class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg"
            >
              <template x-for="option in options" :key="option">
                <li
                  @click="selected = option; open = false"
                  class="px-4 py-2 hover:bg-blue-100 cursor-pointer"
                  x-text="option"
                ></li>
              </template>
            </ul>
          </div>
          
        <x-input
        label="User Name"
        placeholder="Juan"
        class="!border !border-gray-400 rounded-lg"
        />
        <x-input
        label="Password"
        placeholder="password"
        class="!border !border-gray-400 rounded-lg"
        />
       
        <x-input
        label="Last Name"
        placeholder="Last Name"
        class="!border !border-gray-400 rounded-lg"
        />
         <!-- role drop down -->
         <div x-data="{ selected: '', open: false, options: ['BAC Secretary', 'Bidder', 'Purchaser'] }" class="relative w-full">
            <!-- Label -->
            <label class="block mb-2 text-sm font-medium text-gray-700">Role/Position</label>
          
            <!-- Dropdown Button -->
            <button
              @click="open = !open"
              class="w-full bg-white border border-gray-400 rounded-md px-4 py-2 text-left shadow-sm focus:outline-none focus:ring focus:border-blue-300"
            >
              <span class="text-gray-400 text-xs w-full" x-text="selected || 'Select Account Type'"></span>
            </button>
          
            <!-- Options Dropdown -->
            <ul
              x-show="open"
              @click.away="open = false"
              x-transition
              class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg"
            >
              <template x-for="option in options" :key="option">
                <li
                  @click="selected = option; open = false"
                  class="px-4 py-2 hover:bg-blue-100 cursor-pointer"
                  x-text="option"
                ></li>
              </template>
            </ul>
          </div>

        <x-input
        label="Email"
        placeholder="Auto Email Generate"
        class="!border !border-gray-400 rounded-lg"
        />
        <x-input
        label="Confirm Password"
        placeholder="Conform Password"
        class="!border !border-gray-400 rounded-lg"
        />
        <x-input
        label="Middle Initial"
        placeholder="M"
        class="!border !border-gray-400 rounded-lg"
        />
          </div>

       <div class="flex justify-center mt-6">
            <x-button wire:click="sleeping" spinner="sleeping" primary class="!bg-[#FAEA55] text-black !px-10">
            Create Account
            </x-button>
        
        </div>

      </div>
      </main>
    </div>
  </div>
  