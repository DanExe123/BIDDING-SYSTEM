<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Participation</h1>
            <div>
            <button class="text-gray-500 hover:text-black">
                <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
            </button>
            </div>
        </header>
  
      <!-- Page content -->
        <main class="p-6 space-y-6 flex-1" x-cloak>
            <!-- Request for Approval Box -->
            <div class="bg-white rounded-md shadow-md border border-gray-300" x-cloak>
                <div class="bg-[#062B4A] text-center py-2 rounded-t-md">
                    <h2 class="text-lg font-semibold text-white">Participation
                </div>
                    <!-- Alpine component wrapper -->
                
        
            </div>

          
        </main>
</div>
</div>
 
