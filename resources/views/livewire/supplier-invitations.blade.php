<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Invitations</h1>
            <div>
            <button class="text-gray-500 hover:text-black">
                <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
            </button>
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
                    <h2 class="text-lg font-semibold text-white">Invitations
                </div>
                    <!-- Alpine component wrapper -->
                <div x-data="{ showModal: false }" x-on:close-invitation-modal.window="showModal = false"  class="relative" >
                    <!-- Table -->
                    <div class="border border-gray-300 m-4 rounded-md overflow-hidden bg-white">
                        <table wire:poll.500ms class="min-w-full text-sm border-collapse">
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
                                    <tr class="border-b border-gray-200">
                                        <td class="px-4 py-2">{{ $invitation->reference_no }}</td>
                                        <td class="px-4 py-2 text-center">{{ $invitation->title }}</td>
                                        <td class="px-4 py-2 text-center">{{ $invitation->ppmp?->mode_of_procurement }}</td>
                                        <td class="px-4 py-2 text-center">{{ $invitation->suppliers()->where('supplier_id', auth()->id())->first()?->pivot?->response }}</td>
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
                                        <label class="text-sm text-gray-600">Approved Budget</label>
                                        <input type="date" readonly class="w-full border-none bg-transparent px-3 py-2"
                                            value="{{ $selectedInvitation?->pre_date ?? 'N/A' }}">
                                    </div>

                                    <div>
                                        <label class="text-sm text-gray-600">Source of Funds</label>
                                        <input type="date" readonly class="w-full border-none bg-transparent px-3 py-2"
                                            value="{{ $selectedInvitation?->submission_deadline ?? 'N/A' }}">
                                    </div>
                                </div>
                            </div>

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
                                    $userId = auth()->id();
                                    $pivotResponse = $selectedInvitation?->suppliers
                                        ->firstWhere('id', $userId)?->pivot?->response;
                                @endphp

                                @if($pivotResponse === 'pending')
                                    <div class="flex justify-end space-x-2 mt-4">
                                        <button wire:click="respondToInvitation('declined')" 
                                                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                            Decline
                                        </button>

                                        <button wire:click="respondToInvitation('accepted')" 
                                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                            Accept
                                        </button>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
        
            </div>

          
        </main>
</div>
</div>
 
