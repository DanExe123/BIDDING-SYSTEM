<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Procurement Planning</h1>
        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1" x-cloak>
        <div x-data="{ view: 'create' }" class="p-4">
            <!-- Toggle Buttons -->
            <div class="flex justify-center gap-10 py-2">
                <button
                    @click="view = 'create'"
                    :class="view === 'create' ? 'bg-[#062B4A] text-white' : 'text-cyan-900'"
                    class="border border-cyan-700 hover:bg-[#062B4A] px-10 py-1 rounded-full text-sm font-semibold transition-colors"
                >
                    Create PPMP
                </button>
        
                <button
                    @click="view = 'list'"
                    :class="view === 'list' ? 'bg-[#062B4A] text-white' : 'text-cyan-900'"
                    class="border border-cyan-700 hover:bg-[#062B4A] px-12 py-1 rounded-full text-sm font-semibold transition-colors"
                >
                    List
                </button>
            </div>
        
            <!-- Create PPMP Form -->
            <div x-show="view === 'create'" class="mt-6">
                <div class="flex justify-center items-center w-full">
                    <div class="bg-white rounded-md shadow-md border border-gray-300 w-full md:w-full">
                        <div class="rounded-md shadow-lg p-4">
                            <h2 class="text-gray-800 py-2 font-bold">Project Procurement Management Plan</h2>
        
                            <div class="px-6 py-4 space-y-4 text-sm text-gray-700">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-medium mb-1">Project Title*</label>
                                        <input type="text" value="School supplies for students" class="w-full border rounded px-3 py-2" />
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Project Type*</label>
                                        <input type="text" value="education" class="w-full border rounded px-3 py-2" />
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">ABC (Approved Budget)*</label>
                                        <input type="text" value="₱50,000" class="w-full border rounded px-3 py-2" />
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Implementing Unit*</label>
                                        <input type="text" value="Government officials" class="w-full border rounded px-3 py-2" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block font-medium mb-1">Project Description*</label>
                                        <textarea class="w-full border rounded px-3 py-2" rows="4">Project details here...</textarea>
                                    </div>
                                </div>
        
                                <div class="border-dashed border-gray-300 border-2 p-2 rounded-md">
                                    <label class="block font-medium mb-1">Attachments</label>
                                    <input type="file" class="w-full border rounded px-3 py-2" />
                                </div>
                            </div>
        
                            <div class="px-6 py-4 flex justify-end items-center border-t border-gray-300 bg-[#F9FAFB]">
                                <div class="space-x-2">
                                    <button class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                        Submit PPMP
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- List View -->
            <div x-show="view === 'list'" class="mt-6">
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
            </div>
        </div>
        


          
</main>
</div>
</div>
 
