<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Transaction History</h1>
        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1" x-cloak>
       
                <div class="mt-6" x-data="{ openModal: false, selectedPr: null }">
                    <div class="bg-white border border-gray-300 rounded-md shadow p-6">
                        <h2 class="text-lg font-bold mb-4">Purchase Request Entries</h2>
                        <table class="w-full table-auto border-collapse text-sm">
                            <thead>
                                <tr class="bg-[#062B4A] text-white text-left">
                                    <th class="p-2 border">PR #</th>
                                    <th class="p-2 border">Purpose</th>
                                    <th class="p-2 border">Created At</th>
                                    <th class="p-2 border">Status</th>
                                    <th class="p-2 border">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ppmps as $ppmp)
                                    <tr>
                                        <td class="p-2 border">{{ $ppmp->id }}</td>
                                        <td class="p-2 border">{{ $ppmp->project_title }}</td>
                                        <td class="p-2 border text-center">
                                            {{ $ppmp->created_at->format('M d, Y h:i A') }} <!-- ✅ Added -->
                                        </td>
                                        <td class="p-2 border text-center
                                            {{ $ppmp->status === 'approved' ? 'text-green-400' : '' }}
                                            {{ $ppmp->status === 'rejected' ? 'text-pink-400' : '' }}
                                            {{ $ppmp->status === 'returned' ? 'text-gray-400' : '' }}
                                            {{ $ppmp->status === 'pending' ? 'text-blue-400' : '' }}">
                                            {{ ucfirst($ppmp->status) }}
                                        </td>
                                        <td class="p-2 border text-center">
                                            <button 
                                                @click="selectedPr = {{ $ppmp->toJson() }}; openModal = true" 
                                                class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-2 border text-center text-gray-400">
                                            No PR entries found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- MODAL -->
                    <!-- MODAL -->
                    <div x-show="openModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-transition>
                        <!-- Whole modal scrollable -->
                        <div class="bg-gray-100 w-full ml-30 max-w-4xl rounded-lg shadow-lg relative max-h-[90vh] overflow-y-auto" @click.away="openModal = false">
                            
                            <!-- Close -->
                            <button @click="openModal = false" 
                                    class="absolute top-2 right-2 text-gray-600 hover:text-black text-xl">&times;</button>
                            
                            <!-- Header -->
                            <div class="flex items-center px-6 py-4 border-b border-gray-300 space-x-4">
                                <h3 class="text-lg font-bold">PR Details</h3>
                                <span class="text-sm text-gray-500">
                                    Status: 
                                    <span x-text="selectedPr.status" 
                                        :class="{
                                            'text-green-600': selectedPr.status === 'approved',
                                            'text-red-500': selectedPr.status === 'rejected',
                                            'text-gray-400': selectedPr.status === 'returned',
                                            'text-blue-500': selectedPr.status === 'pending'
                                        }">
                                    </span>
                                </span>
                            </div>


                            <!-- Body -->
                            <div class="p-6 space-y-6">
                                <!-- Info Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">PR #</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.id"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Project Title</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.project_title"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Project Type</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.project_type"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Project Description</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.description"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Implemeting Unit</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.implementing_unit"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">ABC (Approved Budget)*</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.abc"></p>
                                    </div>
                                </div>

                                <!-- Items Table -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white text-sm text-left border border-gray-300 rounded-md">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">ITEM #</th>
                                                <th class="border px-2 py-1">ITEM</th>
                                                <th class="border px-2 py-1">QTY</th>
                                                <th class="border px-2 py-1">UNIT</th>
                                                <th class="border px-2 py-1">UNIT COST</th>
                                                <th class="border px-2 py-1">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="item in selectedPr.items" :key="item.id">
                                                <tr>
                                                    <td class="border px-2 py-1" x-text="item.id"></td>
                                                    <td class="border px-2 py-1" x-text="item.description"></td>
                                                    <td class="border px-2 py-1" x-text="item.qty"></td>
                                                    <td class="border px-2 py-1" x-text="item.unit"></td>
                                                    <td class="border px-2 py-1" x-text="item.unit_cost"></td>
                                                    <td class="border px-2 py-1" x-text="item.total_cost"></td>
                                                </tr>
                                            </template>
                                            
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Attachment -->
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-2">Attachments</p>

                                    <template x-if="selectedPr.attachments && selectedPr.attachments.length > 0">
                                        <div class="space-y-2">
                                            <template x-for="(file, index) in selectedPr.attachments" :key="index">
                                                <div class="flex items-center justify-between bg-white border rounded-md px-4 py-2 hover:shadow-sm">
                                                    <div class="flex items-center space-x-2">
                                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828V15a3 3 0 01-3 3H6a3 
                                                                3 0 01-3-3V6a3 3 0 013-3h9a3 3 0 013 3v.172z"/>
                                                        </svg>
                                                        <a :href="`/storage/${file}`" target="_blank"
                                                            class="text-blue-600 font-medium truncate max-w-[200px] hover:underline"
                                                            x-text="selectedPr.attachment_names[index]"></a>
                                                    </div>
                                                    <a :href="`/storage/${file}`" target="_blank"
                                                        class="text-sm text-blue-500 hover:underline">
                                                        Download
                                                    </a>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="!selectedPr.attachments || selectedPr.attachments.length === 0">
                                        <p class="text-gray-400 italic">No attachments uploaded</p>
                                    </template>
                                </div>


                                <!-- Remarks -->
                                <div class="p-4 border-2 border-dashed border-gray-300 rounded-lg">
                                    <p class="text-sm font-medium text-gray-600">Remarks</p>
                                    <p class="mt-1 text-gray-700"
                                        x-text="selectedPr.remarks || 'No remarks provided'" 
                                        :class="!selectedPr.remarks ? 'italic text-gray-400' : ''">
                                    </p>
                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-4 flex justify-end items-center border-t border-gray-300 bg-white">
                                <button @click="openModal = false"
                                    class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
        


          
</main>
</div>
</div>
 
