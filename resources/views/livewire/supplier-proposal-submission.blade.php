<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Proposal Submission</h1>
            @livewire('proposal-notification-bell')  
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

                <div x-data="{ 
                        showModal: @entangle('showModal'), 
                        confirmModal: false, 
                        actionType: '', 
                        selectedInvitation: @entangle('selectedInvitation') 
                    }" class="relative">

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
                                        <th class="px-4 py-2 w-1/6 text-center font-semibold">Status</th>
                                        <th class="px-4 py-2 w-1/5 text-right font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @foreach($invitations as $inv)
                                        <tr class="border-b border-gray-200">
                                            <td class="px-4 py-2">{{ $inv->reference_no }}</td>
                                            <td class="px-4 py-2 text-center">{{ $inv->title ?? $inv->ppmp->project_title }}</td>
                                            <td class="px-4 py-2 text-center">{{ ucfirst($inv->ppmp->mode_of_procurement) }}</td>
                                            <td class="px-4 py-2 text-center">{{ \Carbon\Carbon::parse($inv->submission_deadline)->format('F j, Y') }}</td>
                                            <td class="px-4 py-2 text-center">
                                                @php
                                                    $submission = $inv->submissions->where('supplier_id', Auth::id())->first();
                                                @endphp

                                                @if($submission)
                                                    <span class="
                                                        @if($submission->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($submission->status === 'draft') bg-gray-100 text-gray-800
                                                        @elseif($submission->status === 'submitted') bg-indigo-100 text-indigo-800
                                                        @elseif($submission->status === 'under_review') bg-blue-100 text-blue-800
                                                        @elseif($submission->status === 'awarded') bg-green-100 text-green-800
                                                        @elseif($submission->status === 'rejected') bg-red-100 text-red-800
                                                        @endif
                                                        px-2 py-1 rounded-full text-xs
                                                    ">
                                                        {{ ucfirst($submission->status) }}
                                                    </span>
                                                @else
                                                    {{-- No submission yet at all --}}
                                                    <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs">Pending</span>
                                                @endif

                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <button wire:click="openSubmission({{ $inv->id }})"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                    open
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

                                @if($selectedInvitation->ppmp->mode_of_procurement === 'bidding')
                                    <div class="space-y-4 p-2 mt-2" x-data="{ open: false }">
                                        <!-- Instruction Label -->
                                        <div class="flex items-start space-x-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                            <x-phosphor.icons::duotone.info class="w-6 h-6 text-blue-600 mt-0.5" />

                                            <p class="text-sm leading-relaxed text-gray-700">
                                                Suppliers are advised to ensure that all submitted Technical and Financial Documents are 
                                                complete, accurate, and compliant with the prescribed requirements.  
                                                Please download and use the standard forms provided below for proper submission.

                                                <span 
                                                    class="text-blue-600 font-medium cursor-pointer hover:underline"
                                                    @click="open = !open">
                                                    Download here
                                                </span>.
                                            </p>
                                        </div>

                                        <!-- Dropdown -->
                                        <div class="relative">
                                            <div x-show="open" @click.outside="open = false"
                                                class="absolute bg-white border border-gray-300 rounded-md shadow-lg w-64 z-50">

                                                <a href="{{ asset('samples/technical_proposal.pdf') }}" download
                                                    class="flex items-center space-x-2 px-4 py-2 text-sm hover:bg-gray-100 transition">
                                                    <x-phosphor.icons::regular.file-text class="w-4 h-4 text-gray-600" />
                                                    <span>Technical Proposal (Standard)</span>
                                                </a>

                                                <a href="{{ asset('samples/financial_proposal.pdf') }}" download
                                                    class="flex items-center space-x-2 px-4 py-2 text-sm hover:bg-gray-100 transition">
                                                    <x-phosphor.icons::regular.file-text class="w-4 h-4 text-gray-600" />
                                                    <span>Financial Proposal (Standard)</span>
                                                </a>

                                                <a href="{{ asset('samples/company_profile.pdf') }}" download
                                                    class="flex items-center space-x-2 px-4 py-2 text-sm hover:bg-gray-100 transition">
                                                    <x-phosphor.icons::regular.file-text class="w-4 h-4 text-gray-600" />
                                                    <span>Company Profile Template</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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
                                                    <th class="px-3 py-1 border text-right">Unit</th>
                                                    <th class="px-3 py-1 border text-right">Unit Price</th>
                                                    <th class="px-3 py-1 border text-right">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($submissionItems as $si)
                                                    <tr class="border-b border-gray-200">
                                                        <td class="px-3 py-1 border">{{ $si->procurementItem->description ?? 'Item #' . $si->procurement_item_id }}</td>
                                                        <td class="px-3 py-1 border text-right">{{ $si->procurementItem->qty ?? 1 }}</td>
                                                        <td class="px-3 py-1 border text-right">{{ $si->procurementItem->unit ?? '-' }}</td> 
                                                        <td class="px-3 py-1 border text-right">
                                                            <input type="number" step="0.01" wire:model="unitPrices.{{ $si->id }}"
                                                                class="w-32 text-right border rounded px-2 py-1" placeholder="Enter price" />
                                                                  @error('unitPrices.' . $si->id)
                                                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                                                @enderror
                                                        </td>
                                                        <td class="px-3 py-1 border text-right">
                                                            {{ number_format((float) ($unitPrices[$si->id] ?? 0) * ($si->procurementItem->qty ?? 1), 2) }}
                                            
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <!-- Delivery Days -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium mb-1">Delivery Days*</label>
                                            <input type="number" min="1" wire:model="delivery_days" 
                                                class="w-full border rounded px-3 py-2" placeholder="Enter delivery days" />
                                            @error('delivery_days') 
                                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p> 
                                            @enderror
                                        </div>


                                        <div class="mt-3 flex flex-col space-y-2">
                                            <!-- Remarks textarea -->
                                            <textarea wire:model="remarks" class="border rounded w-full p-2" placeholder="Remarks (optional)"></textarea>

                                            @php
                                                $hasSubmitted = $selectedInvitation->submissions
                                                                    ->where('supplier_id', Auth::id())
                                                                    ->where('status', 'submitted') // only count if status is submitted
                                                                    ->count() > 0;
                                            @endphp

                                            @if(! $hasSubmitted)
                                                    <!-- Buttons aligned to the right -->
                                                <div class="flex justify-end space-x-2">
                                                    <button wire:click="saveQuotationDraft" class="px-3 py-1 bg-gray-200 rounded">Save Draft</button>
                                                    <button wire:click="prepareConfirm('quotation')" class="px-3 py-1 bg-blue-600 text-white rounded">Submit Quotation</button>
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-600 mt-2">You have already submitted in this invitation.</p>
                                            @endif
                                        </div>
                                    </div>

                                @else
                                    {{-- BIDDING --}}
                                    <div>
                                        <h4 class="font-medium mb-4">Submission of Required Documents (PDF Format Only)</h4>

                                      <div class="space-y-3">

                                            <!-- Technical Proposal -->
                                            <div class="flex justify-between items-center border rounded px-4 py-3">
                                                <div class="w-full">
                                                    <p class="font-medium text-sm">Technical Proposal</p>

                                                    @if($technicalProposal)
                                                        <div class="flex items-center justify-between bg-gray-100 text-gray-700 text-xs rounded px-3 py-2 mt-2 w-2/3">
                                                            <p class="truncate">{{ $technicalProposal->getClientOriginalName() }}</p>
                                                            <button type="button" wire:click="$set('technicalProposal', null)" 
                                                                    class="text-red-500 hover:text-red-700 font-bold text-sm ml-2">✕</button>
                                                        </div>
                                                    @elseif($technicalProposalOriginalName)
                                                        <div class="flex items-center justify-between bg-gray-100 text-gray-700 text-xs rounded px-3 py-2 mt-2 w-2/3">
                                                            <p class="truncate">{{ $technicalProposalOriginalName }}</p>
                                                            <button type="button" wire:click="removeTechnicalProposal" 
                                                                    class="text-red-500 hover:text-red-700 font-bold text-sm ml-2">✕</button>
                                                        </div>
                                                    @else
                                                        <p class="text-xs text-gray-500">PDF format (max 10MB)</p>
                                                    @endif

                                                    @error('technicalProposal') 
                                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p> 
                                                    @enderror
                                                </div>
                                                <input type="file" wire:model="technicalProposal" class="hidden" id="tech-proposal">
                                                <label for="tech-proposal" class="ml-3 px-3 py-1 bg-blue-600 text-white text-sm rounded cursor-pointer">
                                                    Upload
                                                </label>
                                            </div>

                                            <!-- Financial Proposal -->
                                            <div class="flex justify-between items-center border rounded px-4 py-3">
                                                <div class="w-full">
                                                    <p class="font-medium text-sm">Financial Proposal</p>

                                                    @if($financialProposal)
                                                        <div class="flex items-center justify-between bg-gray-100 text-gray-700 text-xs rounded px-3 py-2 mt-2 w-2/3">
                                                            <p class="truncate">{{ $financialProposal->getClientOriginalName() }}</p>
                                                            <button type="button" wire:click="$set('financialProposal', null)" 
                                                                    class="text-red-500 hover:text-red-700 font-bold text-sm ml-2">✕</button>
                                                        </div>
                                                    @elseif($financialProposalOriginalName)
                                                        <div class="flex items-center justify-between bg-gray-100 text-gray-700 text-xs rounded px-3 py-2 mt-2 w-2/3">
                                                            <p class="truncate">{{ $financialProposalOriginalName }}</p>
                                                            <button type="button" wire:click="removeFinancialProposal" 
                                                                    class="text-red-500 hover:text-red-700 font-bold text-sm ml-2">✕</button>
                                                        </div>
                                                    @else
                                                        <p class="text-xs text-gray-500">Excel format (max 5MB)</p>
                                                    @endif

                                                    @error('financialProposal') 
                                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p> 
                                                    @enderror
                                                </div>
                                                <input type="file" wire:model="financialProposal" class="hidden" id="fin-proposal">
                                                <label for="fin-proposal" class="ml-3 px-3 py-1 bg-blue-600 text-white text-sm rounded cursor-pointer">
                                                    Upload
                                                </label>
                                            </div>

                                            <!-- Company Profile -->
                                            <div class="flex justify-between items-center border rounded px-4 py-3">
                                                <div class="w-full">
                                                    <p class="font-medium text-sm">Company Profile</p>

                                                    @if($companyProfile)
                                                        <div class="flex items-center justify-between bg-gray-100 text-gray-700 text-xs rounded px-3 py-2 mt-2 w-2/3">
                                                            <p class="truncate">{{ $companyProfile->getClientOriginalName() }}</p>
                                                            <button type="button" wire:click="$set('companyProfile', null)" 
                                                                    class="text-red-500 hover:text-red-700 font-bold text-sm ml-2">✕</button>
                                                        </div>
                                                    @elseif($companyProfileOriginalName)
                                                        <div class="flex items-center justify-between bg-gray-100 text-gray-700 text-xs rounded px-3 py-2 mt-2 w-2/3">
                                                            <p class="truncate">{{ $companyProfileOriginalName }}</p>
                                                            <button type="button" wire:click="removeCompanyProfile" 
                                                                    class="text-red-500 hover:text-red-700 font-bold text-sm ml-2">✕</button>
                                                        </div>
                                                    @else
                                                        <p class="text-xs text-gray-500">PDF format (max 5MB)</p>
                                                    @endif

                                                    @error('companyProfile') 
                                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p> 
                                                    @enderror
                                                </div>
                                                <input type="file" wire:model="companyProfile" class="hidden" id="comp-profile">
                                                <label for="comp-profile" class="ml-3 px-3 py-1 bg-blue-600 text-white text-sm rounded cursor-pointer">
                                                    Upload
                                                </label>
                                            </div>

                                        </div>

                                        <!-- Bid Amount -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium mb-1">Bid Amount*</label>
                                            <input type="number" step="0.01" wire:model="bid_amount" 
                                                class="w-full border rounded px-3 py-2" placeholder="Enter your bid amount" />
                                            <!--  Validation error -->
                                            @error('bid_amount') 
                                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p> 
                                            @enderror
                                        </div>

                                        <!-- Delivery Days -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium mb-1">Delivery Days*</label>
                                            <input type="number" min="1" wire:model="delivery_days" 
                                                class="w-full border rounded px-3 py-2" placeholder="Enter delivery days" />
                                            @error('delivery_days') 
                                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p> 
                                            @enderror
                                        </div>


                                        <!-- Additional Notes -->
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium mb-1">Additional Notes</label>
                                            <textarea wire:model="remarks" class="border rounded w-full p-2" placeholder="Any special conditions or clarifications..."></textarea>
                                        </div>

                                        @php
                                            $hasSubmitted = $selectedInvitation->submissions
                                                                ->where('supplier_id', Auth::id())
                                                                ->where('status', 'submitted') // only count if status is submitted
                                                                ->count() > 0;
                                        @endphp

                                        @if(! $hasSubmitted)
                                            <!-- Actions -->
                                            <div class="mt-4 flex justify-between items-center">
                                                <div>
                                                    <div class="flex items-center space-x-2">
                                                        <input type="checkbox" wire:model="isCertified" class="h-4 w-4">
                                                        <label class="text-xs text-gray-600">I certify that all information provided is accurate and I agree to the terms of bidding</label>
                                                    </div>
                                                    <!--  Validation error -->
                                                    @error('isCertified') 
                                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p> 
                                                    @enderror
                                                </div>

                                                <div class="space-x-2">
                                                    <button wire:click="saveBiddingDraft" class="px-3 py-1 bg-gray-200 rounded">Save Draft</button>
                                                    <button wire:click="prepareConfirm('bidding')" class="px-3 py-1 bg-green-600 text-white rounded">Submit Bid</button>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-600 mt-2">You have already submitted in this invitation.</p>
                                        @endif
                                    </div>

                                @endif
                            </div>
                        </div>

                        <div  x-data="{ confirmModal: false, actionType: '' }" 
                            x-on:show-confirm-modal.window="actionType = $event.detail.type; confirmModal = true" 
                            x-show="confirmModal" x-transition
                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                            <div class="bg-white rounded-md shadow-lg w-96 p-6" @click.away="confirmModal = false">
                                <h3 class="text-lg font-semibold mb-4">Confirm Submission</h3>
                                <p class="text-sm text-gray-700 mb-6">
                                    Are you sure you want to submit this <span x-text="actionType"></span>? 
                                    Once submitted, changes cannot be made.
                                </p>
                                <div class="flex justify-end space-x-2">
                                    <button @click="confirmModal = false" 
                                            class="px-3 py-1 bg-gray-200 rounded">Cancel</button>
                                    @if($selectedInvitation->ppmp->mode_of_procurement === 'quotation')
                                        <button @click="confirmModal = false" wire:click="submitQuotation" 
                                                class="px-3 py-1 bg-green-600 text-white rounded">
                                            Submit
                                        </button>
                                    @elseif($selectedInvitation->ppmp->mode_of_procurement === 'bidding')
                                        <button @click="confirmModal = false" wire:click="submitBidding" 
                                                class="px-3 py-1 bg-green-600 text-white rounded">
                                            Submit 
                                        </button>
                                    @endif

                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
          
        </main>
</div>
</div>
 
