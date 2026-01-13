<div class="flex min-h-screen" x-cloak>
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Invitations</h1>
            @livewire('supplier-notification-bell')  
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
                    <h2 class="text-lg font-semibold text-white">Invitations
                </div>
                    <!-- Alpine component wrapper -->
                <div x-data="{ showModal: false }" x-on:close-invitation-modal.window="showModal = false"  class="relative" >
                    <!-- Table -->
                    <div class="border border-gray-300 m-4 rounded-md overflow-hidden bg-white">
                        <table wire:poll class="min-w-full mb-18 text-sm border-collapse">
                            <thead class="bg-blue-200 border-b border-gray-300">
                                <tr>
                                    <th class="px-4 py-2 w-1/4 text-left font-semibold">Reference No</th>
                                    <th class="px-4 py-2 w-1/4 text-center font-semibold">Title</th>
                                    <th class="px-4 py-2 w-1/4 text-center font-semibold">Procurment Type</th>
                                    <th class="px-4 py-2 w-1/4 text-center font-semibold">Response</th>
                                    <th class="px-4 py-2 w-1/4 text-right font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach($invitations as $invitation)
                                    @php
                                        $isRead = $invitation->suppliers()
                                            ->where('supplier_id', auth()->id())
                                            ->first()?->pivot?->is_read ?? false;
                                    @endphp
                                    <tr class="border-b border-gray-200 {{ !$isRead ? 'bg-blue-50 font-semibold' : '' }}">
                                        <td class="px-4 py-2">
                                            @if(!$isRead)
                                               <span class="inline-flex items-center gap-1 mr-2">
                                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">NEW</span>
                                                </span>
                                            @endif
                                            {{ $invitation->reference_no }}
                                        </td>
                                        <td class="px-4 py-2 text-center">{{ $invitation->title }}</td>
                                        <td class="px-4 py-2 text-center">{{ $invitation->ppmp?->mode_of_procurement }}</td>
                                        <td class="px-4 py-2 text-center relative">
                                            @php
                                                $supplier = $invitation->suppliers()->where('supplier_id', auth()->id())->first();
                                                $response = $supplier?->pivot?->response;
                                                $remarks = $supplier?->pivot?->remarks ?? null;
                                            @endphp

                                            @if ($response === 'accepted')
                                                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                                    {{ ucfirst($response) }}
                                                </span>
                                            @elseif ($response === 'declined')
                                                <div x-data="{ open: false }" class="inline-block relative">
                                                    <!-- Dropdown Button -->
                                                    <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full cursor-pointer hover:bg-red-200 transition-colors flex items-center gap-1"
                                                        @click="open = !open">
                                                        Declined
                                                        <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </span>

                                                    <!-- Dropdown Remarks -->
                                                    <div x-show="open" 
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="transform opacity-0 scale-95"
                                                        x-transition:enter-end="transform opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="transform opacity-100 scale-100"
                                                        x-transition:leave-end="transform opacity-0 scale-95"
                                                        @click.outside="open = false"
                                                        class="absolute z-50 mt-2 left-0 w-[200px] bg-white border border-gray-200 rounded-lg shadow-lg p-3 text-left text-xs text-gray-700 max-h-64 overflow-y-auto"
                                                        x-cloak>
                                                        <div class="font-semibold text-red-800 mb-2 pb-1 border-b border-red-100 flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Decline Remarks
                                                        </div>
                                                        @if($remarks)
                                                            <div class="whitespace-pre-wrap break-words">{{ $remarks }}</div>
                                                        @else
                                                            <div class="text-gray-500 italic">No remarks provided</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>


                                        <td class="px-4 py-2 text-right">
                                            <button wire:click="selectInvitation({{ $invitation->id }})" @click="showModal = true"
                                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" x-transition 
                         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                        <div class="bg-white ml-30 w-[90%] md:w-[800px] rounded-md shadow-lg max-h-[90vh] overflow-y-auto"
                             @click.away="showModal = false">
                            
                            <!-- Header -->
                            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                                <div>
                                    <p class="text-base font-semibold">Invitation</p>
                                    <p class="text-sm text-gray-600">
                                        Reference: <span class="font-medium">{{ $selectedInvitation?->reference_no }} ({{ $selectedInvitation?->title }})</span>
                                    </p>
                                </div>
                                <button @click="showModal = false" class="text-gray-600 text-xl font-bold hover:text-black">&times;</button>
                            </div>

                            <!-- Invitation Details -->
                            <div class="px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                                <div class="grid grid-cols-2 gap-4 mb-2">
                                    <div>
                                        <label class="text-sm text-gray-600">Title</label>
                                        <input type="text" readonly class="w-full border-none bg-transparent px-3 py-2"
                                            value="{{ $selectedInvitation?->title ?? 'N/A' }}">
                                    </div>

                                    <div>
                                        <label class="text-sm text-gray-600">Reference Number</label>
                                        <input type="text" readonly class="w-full border-none bg-transparent px-3 py-2"
                                            value="{{ $selectedInvitation?->reference_no ?? 'N/A' }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-2">
                                    <div>
                                        <label class="text-sm text-gray-600">Approved Budget</label>
                                        <input type="text" readonly class="w-full border-none bg-transparent px-3 py-2"
                                            value="₱{{ number_format($selectedInvitation?->approved_budget ?? 0, 2) }}">
                                    </div>

                                    <div>
                                        <label class="text-sm text-gray-600">Source of Funds</label>
                                        <input type="text" readonly class="w-full border-none bg-transparent px-3 py-2"
                                            value="{{ $selectedInvitation?->ppmp?->implementing_unit ?? 'N/A' }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-2">
                                    <div>
                                        <label class="text-sm text-gray-600">Pre date</label>
                                        <input type="date" readonly class="w-full border-none bg-transparent px-3 py-2"
                                             value="{{ $selectedInvitation?->pre_date ? \Carbon\Carbon::parse($selectedInvitation->pre_date)->format('Y-m-d') : '' }}">
                                    </div>

                                    <div>
                                        <label class="text-sm text-gray-600">Deadline</label>
                                        <input type="date" readonly class="w-full border-none bg-transparent px-3 py-2"
                                             value="{{ $selectedInvitation?->submission_deadline ? \Carbon\Carbon::parse($selectedInvitation->submission_deadline)->format('Y-m-d') : '' }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Attachments Section -->
                            @if($selectedInvitation && $selectedInvitation->documents && count(json_decode($selectedInvitation->documents, true)) > 0)
                                @php
                                    $docs = json_decode($selectedInvitation->documents, true);
                                    $names = json_decode($selectedInvitation->document_names, true);
                                @endphp

                                <div class="px-6 py-4 border-t border-gray-300">
                                    <p class="text-sm font-medium text-gray-600 mb-2">Attachments</p>

                                    @foreach($docs as $index => $file)
                                        <div class="flex items-center justify-between bg-white border rounded-md px-4 py-2 hover:shadow-sm mb-2">
                                            <div class="flex items-center space-x-2">
                                                <!-- File Icon -->
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828V15a3 3 0 01-3 3H6a3 
                                                        3 0 01-3-3V6a3 3 0 013-3h9a3 3 0 013 3v.172z"/>
                                                </svg>

                                                <!-- Original File Name -->
                                                <a href="{{ Storage::url($file) }}" target="_blank" 
                                                    class="text-blue-600 font-medium truncate max-w-[200px] hover:underline">
                                                    {{ $names[$index] ?? 'Attachment ' . ($index + 1) }}
                                                </a>
                                            </div>

                                            <!-- Download Link -->
                                            <a href="{{ Storage::url($file) }}" target="_blank"
                                            download="{{ $names[$index] ?? 'file' }}"
                                            class="text-sm text-blue-500 hover:underline">
                                                Download
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="px-6 py-4 border-t border-gray-300">
                                    <p class="text-gray-400 italic">No attachments uploaded</p>
                                </div>
                            @endif

                            <!-- PPMP Items -->
                            <div class="p-6 space-y-4">
                                <p class="text-sm font-semibold text-gray-700">Items Needed:</p>
                                <table class="min-w-full text-sm border-collapse border border-gray-300">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="px-3 py-1 border">Description</th>
                                            <th class="px-3 py-1 border">Qty</th>
                                            <th class="px-3 py-1 border">Unit</th>
                                            <th class="px-3 py-1 border">Unit Cost</th>
                                            <th class="px-3 py-1 border">Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedInvitation?->ppmp?->items ?? [] as $item)
                                            <tr class="border-b border-gray-200">
                                                <td class="px-3 py-1 border">{{ $item->description }}</td>
                                                <td class="px-3 py-1 border">{{ $item->qty }}</td>
                                                <td class="px-3 py-1 border">{{ $item->unit }}</td>
                                                <td class="px-3 py-1 border">₱{{ number_format($item->unit_cost, 2) }}</td>
                                                <td class="px-3 py-1 border">₱{{ number_format($item->total_cost, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @php
                                    // Get the currently authenticated supplier ID
                                    $userId = auth()->id();

                                    // Get THIS supplier's response from the pivot table
                                    // Possible values: pending | accepted | declined
                                    $pivotResponse = $selectedInvitation?->suppliers
                                        ->firstWhere('id', $userId)?->pivot?->response;

                                    // Count how many suppliers have already ACCEPTED this invitation
                                    // This checks the pivot table response column
                                    $acceptedCount = $selectedInvitation?->suppliers
                                        ->where('pivot.response', 'accepted')
                                        ->count();

                                    // Check if the maximum allowed accepted suppliers (3) is already reached
                                    // If true → disable Accept button and show info message
                                    $limitReached = $acceptedCount >= 3;
                                @endphp


                                @if($pivotResponse === 'pending')
                                     @if($limitReached)
                                        <div class="mt-4 p-3 rounded-md bg-yellow-100 border border-yellow-300 text-yellow-800 text-sm">
                                            This bid has already been accepted by <strong>3 suppliers</strong>.
                                            You can no longer accept this invitation.
                                        </div>
                                    @else
                                        <div class="flex justify-end space-x-2 mt-4">
                                            <button wire:click="confirmResponse('declined')" @click="showModal = false"
                                                    class="px-4 py-2 bg-red-500 text-white rounded-md">
                                                Decline
                                            </button>

                                            <button wire:click="confirmResponse('accepted')" @click="showModal = false"
                                                    class="px-4 py-2 bg-green-500 text-white rounded-md">
                                                Accept
                                            </button>
                                        </div>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </div>

                    <div x-data="{ open: @entangle('confirmModal') }"
                        x-show="open"
                        x-transition
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

                        <div class="bg-white w-full max-w-md rounded-md shadow-lg p-6">
                            <h2 class="text-lg font-semibold mb-4">
                                Confirm {{ ucfirst($confirmAction) }}
                            </h2>

                            @if($confirmAction === 'declined')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Remarks / Feedback <span class="text-red-500">*</span>
                                    </label>
                                    <textarea wire:model.defer="remarks"
                                            class="w-full border rounded-md p-2 mt-1"
                                            rows="3"
                                            placeholder="Please state reason for rejection"></textarea>

                                    @error('remarks')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @else
                                <p class="text-gray-600 mb-4">
                                    Are you sure you want to accept this invitation?
                                </p>
                            @endif

                            <div class="flex justify-end space-x-2">
                                <button @click="open=false"
                                        class="px-4 py-2 border rounded-md">
                                    Cancel
                                </button>

                                <button wire:click="submitResponse"
                                        class="px-4 py-2 text-white rounded-md
                                            {{ $confirmAction === 'declined'
                                                ? 'bg-red-600'
                                                : 'bg-green-600' }}">
                                    Confirm
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
        
            </div>

          
        </main>
</div>
</div>
 
