<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Inspection Report</h1>
        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1" x-cloak>
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full border border-gray-600 text-sm text-center">
                <thead>
                    <tr>
                        <th rowspan="2" class="border border-gray-600 px-2 py-2 bg-[#17375E] text-white w-12">No</th>
                        <th rowspan="2" class="border border-gray-600 px-2 py-2 bg-[#17375E] text-white">Description</th>
                        <th rowspan="2" class="border border-gray-600 px-2 py-2 bg-[#17375E] text-white">Test Result</th>
                        <th colspan="2" class="border border-gray-600 px-2 py-2 bg-[#17375E] text-white">Status</th>
                    </tr>
                    <tr>
                        <th class="border border-gray-600 px-2 py-2 bg-[#17375E] text-white">Accepted</th>
                        <th class="border border-gray-600 px-2 py-2 bg-[#17375E] text-white">Rejected</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample Rows -->
                    <tr>
                        <td class="border border-gray-600 px-2 py-1">1</td>
                        <td class="border border-gray-600 px-2 py-1 text-left">Item Description</td>
                        <td class="border border-gray-600 px-2 py-1">Pass</td>
                        <td class="border border-gray-600 px-2 py-1"></td>
                        <td class="border border-gray-600 px-2 py-1"></td>
                    </tr>
                    <!-- Repeat as needed -->
                    <tr>
                        <td class="border border-gray-600 px-2 py-1 h-10"></td>
                        <td class="border border-gray-600 px-2 py-1"></td>
                        <td class="border border-gray-600 px-2 py-1"></td>
                        <td class="border border-gray-600 px-2 py-1"></td>
                        <td class="border border-gray-600 px-2 py-1"></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-[#D9E1F2] font-semibold">
                        <td colspan="1" class="border border-gray-600 px-2 py-2">Total Received Quantity</td>
                        <td class="border border-gray-600 px-2 py-2">Total Acceptable Quantity</td>
                        <td class="border border-gray-600 px-2 py-2">Total Rejected Quantity</td>
                        <td class="border border-gray-600 px-2 py-2">Total Returned Quantity</td>
                        <td class="border border-gray-600 px-2 py-2">Total In Process Quantity</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-600 px-2 py-2"></td>
                        <td class="border border-gray-600 px-2 py-2"></td>
                        <td class="border border-gray-600 px-2 py-2"></td>
                        <td class="border border-gray-600 px-2 py-2"></td>
                        <td class="border border-gray-600 px-2 py-2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        

          
</main>
</div>
</div>
 
