<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Bid Monitoring</h1>
        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1" x-cloak>
       
                <div class="bg-white border border-gray-300 rounded-md shadow p-6">
                    <h2 class="text-lg font-bold mb-4">List of PPMP Entries</h2>
                    <table class="w-full table-auto border-collapse text-sm">
                        <thead>
                            <tr class="bg-[#062B4A] text-white text-left">
                                <th class="p-2 border">PPMP #</th>
                                <th class="p-2 border">Purpose</th>
                                <th class="p-2 border">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-2 border">1</td>
                                <td class="p-2 border">Education</td>
                                <td class="p-2 border text-center text-green-400">Approved</td>
                            </tr>
                            <tr>
                                <td class="p-2 border">2</td>
                                <td class="p-2 border">Education</td>
                                <td class="p-2 border text-center text-pink-400">Rejected</td>
                            </tr>
                            <tr>
                                <td class="p-2 border">2</td>
                                <td class="p-2 border">Education</td>
                                <td class="p-2 border text-center text-gray-400">Returned</td>
                            </tr>
                            <tr>
                                <td class="p-2 border">2</td>
                                <td class="p-2 border">Education</td>
                                <td class="p-2 border text-center text-blue-400">Pending</td>
                            </tr>
                           
                        </tbody>
                    </table>
                </div>
        


          
</main>
</div>
</div>
 
