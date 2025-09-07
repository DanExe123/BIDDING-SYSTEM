<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Proposal Submission</h1>
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
                <!-- Header -->
                <div class="bg-[#062B4A] text-center py-2 rounded-t-md">
                    <h2 class="text-lg font-semibold text-white">Proposal Submission</h2>
                </div>

                <div x-data="{ showModal: @entangle('showModal') }" class="relative">

                    <!-- Table -->
                    <div wire:poll class="border border-gray-300 m-4 rounded-md overflow-hidden bg-white">
                        @if($invitations->isEmpty())
                            <div class="text-sm text-gray-600 p-4">No active invitations.</div>
                        @else
                            <table class="min-w-full text-sm border-collapse">
                                <thead class="bg-blue-200 border-b border-gray-300">
                                    <tr>
                                        <th class="px-4 py-2 w-1/5 text-left font-semibold">Reference</th>
                                        <th class="px-4 py-2 w-1/5 text-center font-semibold">Purpose</th>
                                        <th class="px-4 py-2 w-1/5 text-center font-semibold">Procurement</th>
                                        <th class="px-4 py-2 w-1/5 text-center font-semibold">Deadline</th>
                                        <th class="px-4 py-2 w-1/5 text-right font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @foreach($invitations as $inv)
                                        <tr class="border-b border-gray-200">
                                            <td class="px-4 py-2">{{ $inv->reference_no }}</td>
                                            <td class="px-4 py-2 text-center">{{ $inv->title ?? $inv->ppmp->project_title }}</td>
                                            <td class="px-4 py-2 text-center">{{ ucfirst($inv->ppmp->mode_of_procurement) }}</td>
                                            <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($inv->submission_deadline)->toDateString() }}</td>
                                            <td class="px-4 py-2 text-right">
                                                <button wire:click="openSubmission({{ $inv->id }})" @click="showModal = true"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                    Open
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" x-transition 
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                        
                        @if($selectedInvitation)
                        <div class="bg-white ml-30 w-[90%] md:w-[800px] rounded-md shadow-lg max-h-[90vh] overflow-y-auto"
                             @click.away="showModal = false">

                            <!-- Header -->
                            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                                <div>
                                    <p class="text-base font-semibold">Submit Proposal</p>
                                    <p class="text-sm text-gray-600">
                                        Reference No.: <span class="font-medium">{{ $selectedInvitation->reference_no }}</span>
                                    </p>
                                </div>
                                <button @click="showModal = false" class="text-gray-600 text-xl font-bold hover:text-black">&times;</button>
                            </div>

                            <!-- Invitation Details -->
                            <div class="px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                                <p class="text-sm text-gray-600">Project: {{ $selectedInvitation->ppmp->project_title }}</p>
                                <p class="text-sm text-gray-600">Mode: {{ ucfirst($selectedInvitation->ppmp->mode_of_procurement) }}</p>
                                <div class="flex items-center space-x-2 mt-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded">
                                        Budget: ₱{{ number_format($selectedInvitation->ppmp->abc, 2) }}
                                    </span>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded">
                                        Deadline: {{ \Carbon\Carbon::parse($selectedInvitation->submission_deadline)->toFormattedDateString() }}
                                    </span>
                                </div>

                            </div>

                            <!-- Proposal Form -->
                            <div class="p-6 space-y-4">

                                {{-- QUOTATION (RFQ) --}}
                                @if($selectedInvitation->ppmp->mode_of_procurement === 'quotation')
                                    <div>
                                        <h4 class="font-medium mb-2">Quotation — Price per Item</h4>
                                        <table class="min-w-full text-sm border-collapse border border-gray-300">
                                            <thead class="bg-gray-200">
                                                <tr>
                                                    <th class="px-3 py-1 border text-left">Item</th>
                                                    <th class="px-3 py-1 border text-right">Qty</th>
                                                    <th class="px-3 py-1 border text-right">Unit Price</th>
                                                    <th class="px-3 py-1 border text-right">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($submissionItems as $si)
                                                    <tr class="border-b border-gray-200">
                                                        <td class="px-3 py-1 border">{{ $si->procurementItem->description ?? 'Item #' . $si->procurement_item_id }}</td>
                                                        <td class="px-3 py-1 border text-right">{{ $si->procurementItem->quantity ?? 1 }}</td>
                                                        <td class="px-3 py-1 border text-right">
                                                            <input type="number" step="0.01" wire:model="unitPrices.{{ $si->id }}"
                                                                class="w-32 text-right border rounded px-2 py-1" placeholder="Enter price" />
                                                        </td>
                                                        <td class="px-3 py-1 border text-right">
                                                            {{ number_format($si->total_price ?? 0, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="mt-3 flex justify-between items-center">
                                            <textarea wire:model="remarks" class="border rounded w-full p-2" placeholder="Remarks (optional)"></textarea>
                                            <div class="space-x-2 ml-3">
                                                <button wire:click="saveQuotationDraft" class="px-3 py-1 bg-gray-200 rounded">Save Draft</button>
                                                <button wire:click="submitQuotation" class="px-3 py-1 bg-blue-600 text-white rounded">Submit Quotation</button>
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    {{-- BIDDING --}}
                                    <div>
                                        <h4 class="font-medium mb-4">Required Documents</h4>

                                        <!-- Upload Sections Styled Like Screenshot -->
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center border rounded px-4 py-3">
                                                <div>
                                                    <p class="font-medium text-sm">Technical Proposal</p>
                                                    <p class="text-xs text-gray-500">PDF format (max 10MB)</p>
                                                </div>
                                                <input type="file" wire:model="technicalProposal" class="hidden" id="tech-proposal">
                                                <label for="tech-proposal" class="px-3 py-1 bg-blue-600 text-white text-sm rounded cursor-pointer">Upload</label>
                                            </div>

                                            <div class="flex justify-between items-center border rounded px-4 py-3">
                                                <div>
                                                    <p class="font-medium text-sm">Financial Proposal</p>
                                                    <p class="text-xs text-gray-500">Excel format (max 5MB)</p>
                                                </div>
                                                <input type="file" wire:model="financialProposal" class="hidden" id="fin-proposal">
                                                <label for="fin-proposal" class="px-3 py-1 bg-blue-600 text-white text-sm rounded cursor-pointer">Upload</label>
                                            </div>

                                            <div class="flex justify-between items-center border rounded px-4 py-3">
                                                <div>
                                                    <p class="font-medium text-sm">Company Profile</p>
                                                    <p class="text-xs text-gray-500">PDF format (max 5MB)</p>
                                                </div>
                                                <input type="file" wire:model="companyProfile" class="hidden" id="comp-profile">
                                                <label for="comp-profile" class="px-3 py-1 bg-blue-600 text-white text-sm rounded cursor-pointer">Upload</label>
                                            </div>
                                        </div>

                                        <!-- Bid Amount -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium mb-1">Bid Amount*</label>
                                            <input type="number" step="0.01" wire:model="bid_amount" 
                                                class="w-full border rounded px-3 py-2" placeholder="Enter your bid amount" />
                                        </div>

                                        <!-- Additional Notes -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium mb-1">Additional Notes</label>
                                            <textarea wire:model="remarks" class="border rounded w-full p-2" placeholder="Any special conditions or clarifications..."></textarea>
                                        </div>

                                        <!-- Actions -->
                                        <div class="mt-4 flex justify-between items-center">
                                            <div class="flex items-center space-x-2">
                                                <input type="checkbox" class="h-4 w-4">
                                                <label class="text-xs text-gray-600">I certify that all information provided is accurate and I agree to the terms of bidding</label>
                                            </div>
                                            <div class="space-x-2">
                                                <button wire:click="saveBiddingDraft" class="px-3 py-1 bg-gray-200 rounded">Save Draft</button>
                                                <button wire:click="submitBidding" class="px-3 py-1 bg-green-600 text-white rounded">Submit Bid</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

    

          
        </main>
</div>
</div>
 
