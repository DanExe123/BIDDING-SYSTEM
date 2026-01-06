<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Procurement Monitoring</h1>
        <div>
            @livewire('purchaser-notification-bell')
        </div>
      </header>
      @if (session()->has('message'))
          <div 
              x-data="{ show: true }"
              x-init="setTimeout(() => show = false, 3000)"
              x-show="show"
              x-transition
              class="absolute top-4 right-4 z-[9999] flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
              role="alert"
          >
              <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                  <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z" />
                  </svg>
                  <span class="sr-only">{{ session('message') }}</span>
              </div>
              <div class="ms-3 text-sm font-normal">{{ session('message') }}</div>
              <button type="button"
                  @click="show = false"
                  class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                  aria-label="Close">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                  </svg>
              </button>
          </div>
      @endif
      
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1" x-cloak>

        <!-- Request for Approval Box -->
        <div class="bg-white rounded-md shadow-md border border-gray-300" x-cloak>
          <div class="bg-[#062B4A] text-center py-2 rounded-t-md">
            <h2 class="text-lg font-semibold text-white">Procurement Monitoring</h2>
          </div>
          <div class="p-3">
            <livewire:bac-invitation />
          </div>
             
        </div>  

     </main>
  </div>
</div>
 
