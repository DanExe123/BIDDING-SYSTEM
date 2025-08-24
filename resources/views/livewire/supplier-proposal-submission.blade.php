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
        <div class="border border-gray-300 m-4 rounded-md overflow-hidden bg-white">
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
            <div class="bg-white w-[95%] md:w-[800px] rounded-md shadow-lg max-h-[90vh] overflow-y-auto"
                 @click.away="showModal = false">

                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                    <div>
                        <p class="text-base font-semibold">Submit Proposal</p>
                        <p class="text-sm text-gray-600">
                            Ref: <span class="font-medium">{{ $selectedInvitation->reference_no }}</span>
                        </p>
                    </div>
                    <button @click="showModal = false" class="text-gray-600 text-xl font-bold hover:text-black">&times;</button>
                </div>

                <!-- Invitation Details -->
                <div class="px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                    <p class="text-sm text-gray-600">Project: {{ $selectedInvitation->ppmp->project_title }}</p>
                    <p class="text-sm text-gray-600">Mode: {{ ucfirst($selectedInvitation->ppmp->mode_of_procurement) }}</p>
                    <p class="text-sm text-gray-600">Deadline: {{ \Carbon\Carbon::parse($selectedInvitation->submission_deadline)->toDateString() }}</p>
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
                                                    class="w-32 text-right border rounded px-2 py-1" />
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
                            <h4 class="font-medium mb-2">Bidding — Upload Proposals</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs">Bid Amount</label>
                                    <input type="number" step="0.01" wire:model="bid_amount" class="w-full border rounded px-2 py-1" />
                                </div>
                                <div>
                                    <label class="block text-xs">Technical Proposal</label>
                                    <input type="file" wire:model="technicalProposal" />
                                </div>
                                <div>
                                    <label class="block text-xs">Financial Proposal</label>
                                    <input type="file" wire:model="financialProposal" />
                                </div>
                                <div>
                                    <label class="block text-xs">Company Profile</label>
                                    <input type="file" wire:model="companyProfile" />
                                </div>
                            </div>

                            <div class="mt-3 flex justify-between items-center">
                                <textarea wire:model="remarks" class="border rounded w-full p-2" placeholder="Remarks (optional)"></textarea>
                                <div class="space-x-2 ml-3">
                                    <button wire:click="saveBiddingDraft" class="px-3 py-1 bg-gray-200 rounded">Save Draft</button>
                                    <button wire:click="submitBidding" class="px-3 py-1 bg-blue-600 text-white rounded">Submit Bid</button>
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
 
