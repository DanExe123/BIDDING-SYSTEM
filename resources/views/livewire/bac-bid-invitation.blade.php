<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Bid Invitation</h1>
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
                <h2 class="text-lg font-semibold text-white">Bid Invitation
              </div>
        <!-- Alpine component wrapper -->
        <div x-data="{
            showModal: false,
        }" class="relative">


    <!-- Request Table -->
    <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
      <!-- Table Header -->
      <div class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
        <span class="w-1/3">Request by:</span>
        <span class="w-1/3 text-center">Purpose:</span>
        <span class="w-1/3 text-right">Status:</span>
      </div>
  
      <!-- Table Row 1 -->
      <div class="flex justify-between px-4 py-2 text-sm border-b border-gray-200">
        <span class="w-1/3">Purchaser Name</span>
        <button @click="showModal = true" class="w-1/3 text-center text-blue-600 hover:underline">School Supplies for Students</button>
        <span class="w-1/3 text-right">Pending</span>
      </div>
    </div>
  
        <!-- Modal -->
        <div x-show="showModal"
        x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        >
        <div class="bg-white ml-30 w-[90%] md:w-[800px] rounded-md shadow-lg overflow-hidden" @click.away="showModal = false">

        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
        <div>
        <p class="text-base font-semibold">Create Invitation to Bid</p>
        <p class="text-sm text-gray-600">Reference: <span class="font-medium">SS-2025-0001 (School supplies for students)</span></p>
        </div>
        <button @click="showModal = false" class="text-gray-600 text-xl font-bold hover:text-black">&times;</button>
        </div>

        <!-- Form Content -->
        <div class="px-6 py-4 space-y-4 text-sm text-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block font-medium mb-1">Bid Title*</label>
            <input type="text" value="School supplies for students" class="w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block font-medium mb-1">Bid Reference Number*</label>
            <input type="text" value="BC-2025-SS-0001" class="w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block font-medium mb-1">Approved Budget*</label>
            <input type="text" value="₱50,000" class="w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block font-medium mb-1">Source of Funds*</label>
            <input type="text" value="Special Education Fund" class="w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block font-medium mb-1">Pre-Bid Conference Date*</label>
            <input type="date" value="2025-06-21" class="w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block font-medium mb-1">Bid Submission Deadline*</label>
            <input type="date" value="2025-06-29" class="w-full border rounded px-3 py-2" />
        </div>
        </div>

        <div>
        <label class="block font-medium mb-1">Bid Documents</label>
        <input type="text" value="Bid_Documents_BC-2025-SS-0001" class="w-full border rounded px-3 py-2" />
        <button class="mt-2 text-blue-600 text-sm hover:underline">+ Add Document</button>
        </div>

        <div class="border-t pt-4">
        <p class="font-medium mb-2">Notify Suppliers</p>
        <label class="flex items-center space-x-2">
            <input type="checkbox" checked class="form-checkbox text-blue-600" />
            <span>. All registered school suppliers (24)</span>
        </label>
        <button class="mt-2 text-blue-600 text-sm hover:underline">Add Specific Suppliers</button>
        </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 flex justify-end items-center border-t border-gray-300 bg-[#F9FAFB]">
      <div class="space-x-2">
        <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Publish Bid Notice</button>
        </div>
        </div>
        </div>
        </div>

  
  </div>      
            </div>

          
</main>
</div>
</div>
 
