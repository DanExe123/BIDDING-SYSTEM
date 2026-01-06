<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Purchase Request</h1>
        <div>
            @livewire('bac-notification-bell')
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
            <h2 class="text-lg font-semibold text-white">Request for Approval</h2>
          </div>

          <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0 md:space-x-2 m-4">
                    <!-- Search -->
                    <input 
                        type="text" 
                        wire:model.debounce.500ms="search" 
                        placeholder="Search by requester or project..." 
                        class="border rounded px-3 py-2 w-full md:w-1/2"
                    />

                    <!-- Status Filter -->
                    <select 
                        wire:model="filterStatus" 
                        class="border rounded px-3 py-2 w-full md:w-1/4"
                    >
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
          <!-- Alpine component wrapper -->
          <div x-data="{ showModal: false }" x-on:close-modal.window="showModal = false" class="relative">
            <!-- Request Table -->
            <div wire:poll class="border border-gray-300 m-4 rounded-md overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-blue-200 font-semibold border-b border-gray-300">
                        <tr>
                            <th class="w-1/3 text-left px-4 py-2">Request by:</th>
                            <th class="w-1/5 text-center px-4 py-2">Purpose:</th>
                            <th class="w-1/3 text-center px-4 py-2">Requested At:</th>
                            <th class="w-1/4 text-center px-4 py-2">Responded At:</th>
                            <th class="w-1/3 text-right px-4 py-2">Status:</th>
                            <th class="w-1/3 text-right px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ppmps as $ppmp)
                            <tr class="border-b border-gray-200">
                                <td class="w-1/3 px-4 py-2">
                                    {{ $ppmp->requester->first_name }} {{ $ppmp->requester->last_name }}
                                </td>
                                <td class="w-1/3 px-4 py-2 text-center">
                                    {{ $ppmp->project_title }}
                                </td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">
                                    @if($ppmp->created_at->diffInHours() < 24)
                                        {{ $ppmp->created_at->diffForHumans() }}
                                    @else
                                        {{ $ppmp->created_at->format('n/j/Y, g:i A') }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">
                                    @if(in_array($ppmp->status, ['approved', 'rejected']))
                                        @if($ppmp->updated_at->diffInHours() < 24)
                                            {{ $ppmp->updated_at->diffForHumans() }}
                                        @else
                                            {{ $ppmp->updated_at->format('n/j/Y, g:i A') }}
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">—</span>
                                    @endif
                                </td>

                                <td class="w-1/3 px-4 py-2 text-right">
                                    <span class="
                                        px-2 py-1 rounded-full text-sm
                                        @if($ppmp->status === 'pending') bg-yellow-100 text-yellow-800 
                                        @elseif($ppmp->status === 'approved') bg-green-100 text-green-800 
                                        @elseif($ppmp->status === 'rejected') bg-red-100 text-red-800 
                                        @endif
                                    ">
                                        {{ ucfirst($ppmp->status) }}
                                    </span>
                                </td>
                                <td class="w-1/3 px-4 py-2 text-right">
                                    <button wire:click="showPpmp({{ $ppmp->id }})" 
                                        x-on:click="showModal = true"
                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        open
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- ✅ Pagination Links -->
                <div class="px-4 py-2">
                    {{ $ppmps->links() }}
                </div>

            </div>

            <!-- Modal -->
            <div x-show="showModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-gray-100 ml-30 w-[90%] md:w-[800px] max-h-[90vh] rounded-md shadow-lg overflow-hidden" @click.away="showModal = false">
                    
                    <!-- Added wrapper for scroll -->
                    <div class="overflow-y-auto max-h-[90vh]">
                        
                        <!-- Loading Spinner -->
                        <div wire:loading.flex wire:target="showPpmp" class="p-10 flex justify-center items-center">
                            <svg class="animate-spin h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span class="ml-2 text-gray-600">Loading...</span>
                        </div>

                        <!-- Modal Content -->
                        <div wire:loading.remove wire:target="showPpmp">
                            <!-- Header -->
                            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300 sticky top-0 bg-gray-100 z-10">
                                <div>
                                    @if($selectedPpmp)
                                        <p class="text-sm"><strong>Requested by:</strong> 
                                            {{ $selectedPpmp->requester->first_name }} {{ $selectedPpmp->requester->last_name }}
                                        </p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm"><strong>Role/Position:</strong> Purchaser</p>
                                </div>
                                <button @click="showModal = false; @this.call('closeModal')" class="text-black text-xl font-bold">&times;</button>
                            </div>

                            <!-- Purpose -->
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Project Title -->
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Project Title</p>
                                        @if($selectedPpmp)
                                            <p class="mt-1 text-lg font-semibold text-gray-800">{{ $selectedPpmp->project_title }}</p>
                                        @endif
                                    </div>

                                    <!-- Budget -->
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Budget</p>
                                        @if($selectedPpmp)
                                            <p class="mt-1 text-lg font-semibold">₱{{ number_format($selectedPpmp->abc, 2) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Project Type -->
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Project Type</p>
                                        @if($selectedPpmp)
                                            <p class="mt-1 text-lg font-semibold text-gray-800">{{ $selectedPpmp->project_type }}</p>
                                        @endif
                                    </div>

                                    <!-- Implementing Unit -->
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Implementing Unit</p>
                                        @if($selectedPpmp)
                                            <p class="mt-1 text-lg font-semibold text-gray-800">{{ $selectedPpmp->implementing_unit }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-1 gap-8">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Project Description</p>
                                        @if($selectedPpmp)
                                            <p class="mt-1 text-lg font-semibold text-gray-800">{{ $selectedPpmp->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto px-6 py-4">
                                <table class="min-w-full bg-white text-sm text-left border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="border px-2 py-1">ITEM NUMBER</th>
                                            <th class="border px-2 py-1">ITEMS</th>
                                            <th class="border px-2 py-1">QTY</th>
                                            <th class="border px-2 py-1">UNIT</th>
                                            <th class="border px-2 py-1">ESTIMATED UNIT COST</th>
                                            <th class="border px-2 py-1">ESTIMATED AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($selectedPpmp)
                                            @foreach($selectedPpmp->items as $item)
                                                <tr>
                                                    <td class="border px-2 py-1">{{ $item->id }}</td>
                                                    <td class="border px-2 py-1">{{ $item->description }}</td>
                                                    <td class="border px-2 py-1">{{ $item->qty }}</td>
                                                    <td class="border px-2 py-1">{{ $item->unit }}</td>
                                                    <td class="border px-2 py-1">{{ $item->unit_cost }}</td>
                                                    <td class="border px-2 py-1">{{ $item->total_cost }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-600 mb-2">Attachments</p>

                                @if($selectedPpmp && $selectedPpmp->attachments && count($selectedPpmp->attachments) > 0)
                                    @foreach($selectedPpmp->attachments as $index => $file)
                                        <div class="flex items-center justify-between bg-white border rounded-md px-4 py-2 hover:shadow-sm mb-2">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828V15a3 3 0 01-3 3H6a3 
                                                        3 0 01-3-3V6a3 3 0 013-3h9a3 3 0 013 3v.172z"/>
                                                </svg>
                                                <a href="{{ Storage::url($file) }}" target="_blank" 
                                                    class="text-blue-600 font-medium truncate max-w-[200px] hover:underline">
                                                        {{ $selectedPpmp->attachment_names[$index] ?? 'Attachment' }}
                                                </a>
                                            </div>
                                            <a href="{{ Storage::url($file) }}" target="_blank"
                                            download="{{ $selectedPpmp->attachment_names[$index] ?? 'file' }}"
                                            class="text-sm text-blue-500 hover:underline">
                                                Download
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-400 italic">No attachments uploaded</p>
                                @endif
                            </div>

                            @if($selectedPpmp && $selectedPpmp->status !== 'approved')
                            <!-- Footer -->
                            <div class="px-6 py-4 flex justify-end items-center border-t border-gray-300 sticky bottom-0 bg-gray-100">
                                <div class="space-x-2">
                                    @if($selectedPpmp)
                                        <button  x-on:click="
                                                $dispatch('open-approve-modal', { id: {{ $selectedPpmp->id }} });
                                                showModal = false
                                            " class="px-4 py-2 text-sm bg-green-200 text-green-800 rounded hover:bg-green-300">
                                            Approve</button>
                                        <button 
                                            x-on:click="
                                                $dispatch('open-reject-modal', { id: {{ $selectedPpmp->id }} });
                                                showModal = false
                                            "
                                            class="px-4 py-2 text-sm bg-red-300 text-red-800 rounded hover:bg-red-400">
                                            Reject
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div> <!-- scroll wrapper end -->
                </div>
            </div>

            <!-- Approve Confirmation Modal -->
            <div x-data="{ showApproveModal: false, ppmpId: null }"
                x-on:open-approve-modal.window="showApproveModal = true; ppmpId = $event.detail.id"
                x-on:close-approve-modal.window="showApproveModal = false"
                x-show="showApproveModal"
                x-transition
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

                <div class="bg-white w-[90%] md:w-[500px] rounded-md shadow-lg overflow-hidden" @click.away="showApproveModal = false">

                    <!-- Header -->
                    <div class="px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-green-600">Approve Request</h2>
                        <button @click="showApproveModal = false" class="text-gray-500 text-xl font-bold">&times;</button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4">
                        <p class="text-gray-700 text-sm">
                            Are you sure you want to approve this request? This action cannot be undone.
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 flex justify-end space-x-2 border-t">
                        <button @click="showApproveModal = false"
                                class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Back
                        </button>
                        <button wire:click="approvePpmp(ppmpId)"
                        @click="$dispatch('close-approve-modal')"
                                class="px-4 py-2 text-sm bg-green-500 text-white rounded hover:bg-green-600">
                            Confirm Approve
                        </button>
                    </div>

                </div>
            </div>

            <!-- Reject Confirmation Modal -->
            <div x-data="{ showRejectModal: false, ppmpId: null }"
                x-on:open-reject-modal.window="showRejectModal = true; ppmpId = $event.detail.id"
                x-on:close-reject-modal.window="showRejectModal = false"
                x-show="showRejectModal"
                x-transition
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

                <div class="bg-white w-[90%] md:w-[500px] rounded-md shadow-lg overflow-hidden" @click.away="showRejectModal = false">

                    <!-- Header -->
                    <div class="px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-red-600">Reject Request</h2>
                        <button @click="showRejectModal = false" class="text-gray-500 text-xl font-bold">&times;</button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Remarks (required for rejection):
                        </label>
                        <textarea wire:model="remarks" rows="3"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                        @error('remarks') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 flex justify-end space-x-2 border-t">
                        <button @click="showRejectModal = false"
                                class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                            Back
                        </button>
                        <button wire:click="rejectPpmp(ppmpId)"
                                class="px-4 py-2 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                            Confirm Reject
                        </button>
                    </div>

                </div>
            </div>

          </div>       
        </div>  

     </main>
  </div>
</div>
 
