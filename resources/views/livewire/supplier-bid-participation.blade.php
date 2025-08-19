<div x-data="{ showModal: @entangle('showModal') }" class="relative">

    <!-- Table -->
    <div class="border border-gray-300 m-4 rounded-md overflow-hidden bg-white">
        <div class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
            <span class="w-1/3">Reference No</span>
            <span class="w-1/3 text-center">Purpose:</span>
            <span class="w-1/3 text-right">Status:</span>
        </div>

        @forelse($bids as $bid)
            <div class="flex justify-between px-4 py-2 text-sm border-b border-gray-200">
                <span class="w-1/3">{{ $bid->bid_reference ?? 'N/A' }}</span>

                <button wire:click="selectBid({{ $bid->id }})"
                        class="w-1/3 text-center text-blue-600 hover:underline">
                    {{ $bid->bid_title }}
                </button>

                <span class="w-1/3 text-right capitalize">{{ $bid->status }}</span>
            </div>
        @empty
            <div class="px-4 py-6 text-sm text-center text-gray-500">No open invitations.</div>
        @endforelse
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-transition
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white ml-30 w-[90%] md:w-[800px] rounded-md shadow-lg overflow-hidden"
             @click.away="showModal = false">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold">
                            Bid Submission:
                            <span class="font-bold">{{ $selectedBid?->bid_reference }}</span>
                        </p>
                        <p class="text-base font-semibold">
                            {{ $selectedBid?->bid_title }}
                        </p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs px-2 py-1 rounded bg-green-100 border border-green-300">
                                Budget: ₱{{ number_format($selectedBid?->approved_budget ?? 0, 2, '.', ',') }}
                            </span>
                            <span class="text-xs px-2 py-1 rounded bg-yellow-100 border border-yellow-300">
                                Deadline:
                                {{ optional($selectedBid?->submission_deadline)->format('M d, Y') ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <button @click="showModal = false"
                            class="text-gray-600 text-xl font-bold hover:text-black">&times;</button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 space-y-5 text-sm">

                <p class="font-semibold">Required Documents</p>

                <!-- Upload rows -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between border rounded-md px-4 py-3 bg-gray-50">
                        <div>
                            <p class="font-medium">Technical Proposal</p>
                            <p class="text-xs text-gray-500">PDF format (max 10MB)</p>
                        </div>
                        <label class="inline-flex items-center">
                            <input type="file" class="hidden" wire:model="technical_proposal" accept="application/pdf">
                            <span class="px-3 py-1 bg-blue-600 text-white rounded cursor-pointer">Upload</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between border rounded-md px-4 py-3 bg-gray-50">
                        <div>
                            <p class="font-medium">Financial Proposal</p>
                            <p class="text-xs text-gray-500">Excel format (max 5MB)</p>
                        </div>
                        <label class="inline-flex items-center">
                            <input type="file" class="hidden" wire:model="financial_proposal"
                                   accept=".xlsx,.xls,.csv">
                            <span class="px-3 py-1 bg-blue-600 text-white rounded cursor-pointer">Upload</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between border rounded-md px-4 py-3 bg-gray-50">
                        <div>
                            <p class="font-medium">Company Profile</p>
                            <p class="text-xs text-gray-500">PDF format (max 5MB)</p>
                        </div>
                        <label class="inline-flex items-center">
                            <input type="file" class="hidden" wire:model="company_profile" accept="application/pdf">
                            <span class="px-3 py-1 bg-blue-600 text-white rounded cursor-pointer">Upload</span>
                        </label>
                    </div>

                    @error('technical_proposal') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    @error('financial_proposal') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    @error('company_profile')  <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Bid Amount -->
                <div>
                    <label class="block mb-1 font-medium">Bid Amount*</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                        <input type="number" step="0.01" wire:model.defer="bid_amount"
                               placeholder="Enter your bid amount"
                               class="w-full border rounded px-8 py-2">
                    </div>
                    @error('bid_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block mb-1 font-medium">Additional Notes</label>
                    <textarea wire:model.defer="notes" rows="3"
                              placeholder="Any special conditions or clarifications..."
                              class="w-full border rounded px-3 py-2"></textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Certify -->
                <label class="flex items-start gap-2">
                    <input type="checkbox" wire:model="is_certified" class="mt-1">
                    <span class="text-xs text-gray-600">
                        I certify that all information provided is accurate and I agree to the terms of bidding.
                    </span>
                </label>
                @error('is_certified') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror

                <!-- Actions -->
                <div class="flex justify-end border-t pt-4">
                    <button wire:click="submitProposal"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Submit Bid
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
