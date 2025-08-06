<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Dashboard</h1>
        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1">
      <!-- Announcement Box -->
      <div class="bg-white p-4 rounded-md shadow-md border border-gray-300 relative h-[400px]">
        <h2 class="text-center text-lg font-bold text-blue-900 mb-4">Announcement</h2>
        
       <!-- Centered Welcome Box -->
    <div class="flex items-center justify-center h-[300px] w-full"> 
      <div class="border border-gray-300 rounded-md p-6 text-center text-lg font-semibold w-full h-[250px] flex items-center justify-center">
        <div>Welcome User!!</div>
      </div>
    </div>

        <!-- Plus Button -->
        <button class="absolute bottom-4 right-4 bg-yellow-400 text-black text-2xl font-bold rounded-full w-10 h-10 flex items-center justify-center hover:scale-105 transition">
          <x-phosphor.icons::bold.plus class="w-6 h-6 text-black" />
        </button>
      </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-white border-l-4 border-yellow-300 p-6 rounded-md text-center shadow">
            <div class="text-3xl font-bold">1</div>
            <div class="text-sm mt-2">Admin Account</div>
          </div>
          <div class="bg-white border-l-4 border-blue-400 p-6 rounded-md text-center shadow">
            <div class="text-3xl font-bold">2</div>
            <div class="text-sm mt-2">Purchaser Account</div>
          </div>
          <div class="bg-white border-l-4 border-yellow-300 p-6 rounded-md text-center shadow">
            <div class="text-3xl font-bold">2</div>
            <div class="text-sm mt-2">Supplier Account</div>
          </div>
        </div>
      </main>
    </div>
  </div>
  