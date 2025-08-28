<div x-data="{ showSubmission: false }" class="relative" x-cloak>

    <!-- Default Table View -->
    <template x-if="!showSubmission">
        <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-blue-200 font-semibold border-b border-gray-300">
                    <tr>
                        <th class="w-1/5 text-left px-4 py-2">Reference No</th>
                        <th class="w-1/5 text-center px-4 py-2">Purpose</th>
                        <th class="w-1/5 text-center px-4 py-2">Procurement Type</th>
                        <th class="w-1/5 text-center px-4 py-2">Response</th>
                        <th class="w-1/5 text-center px-4 py-2">Status</th>
                        <th class="w-1/5 text-center px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ppmps as $ppmp)
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-2">
                                {{ $ppmp->invitations->last()?->reference_no ?? 'No Invitation' }}
                            </td>
                            <td class="px-4 py-2 text-center max-w-xs truncate">
                                {{ $ppmp->project_title }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ ucfirst($ppmp->mode_of_procurement) }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if($ppmp->invitations->isNotEmpty())
                                    @php
                                        $invitation = $ppmp->invitations->last();
                                        $totalInvited = $invitation->suppliers->count();
                                        $totalSubmitted = $invitation->submissions->where('status', '!=', 'draft')->count();
                                    @endphp
                                    {{ $totalSubmitted }}/{{ $totalInvited }}
                                @else
                                    0/0
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if($ppmp->invitations->isNotEmpty())
                                    @php $status = $ppmp->invitations->last()->status; @endphp
                                    <span class="px-2 py-1 rounded-full text-sm
                                        @if($status === 'published') bg-green-100 text-green-700
                                        @elseif($status === 'close') bg-gray-200 text-gray-700
                                        @elseif($status === 'awarded') bg-blue-100 text-blue-700
                                        @endif">
                                        {{ ucfirst($status) }}
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-sm">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                <button wire:click="showPpmp({{ $ppmp->id }})" 
                                        @click="showSubmission = true"
                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                    View
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </template>

    <!-- Evaluation / Submissions View -->
    <template x-if="showSubmission">
        <div class="bg-white rounded-md border border-gray-300 shadow-sm m-4 p-6 space-y-4">

            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-lg font-semibold">Evaluation</h2>
                <button @click="showSubmission = false; $wire.closeModal()" 
                        class="text-sm text-blue-600 hover:underline">
                    ← Back
                </button>
            </div>

            <!-- 🔄 Loading -->
            <div wire:loading.flex wire:target="showPpmp" class="p-10 flex justify-center items-center">
                <svg class="animate-spin h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span class="ml-2 text-gray-600">Loading...</span>
            </div>

            <!-- ✅ Content -->
            <div wire:loading.remove wire:target="showPpmp">
                @if($selectedPpmp)
                    <div class="mb-4 border-b pb-2">
                        <h2 class="text-lg font-semibold text-gray-800">
                            Reference: {{ $selectedPpmp->invitations->last()?->reference_no }}
                        </h2>
                        <p class="text-sm text-gray-600">
                            Project: {{ $selectedPpmp->project_title }}
                        </p>

                        {{-- Show Bid Amount only if bidding --}}
                        @if($selectedPpmp?->mode_of_procurement === 'bidding')
                            <div class="inline-block text-sm bg-blue-100 text-gray-800 px-6 py-2 rounded-md mb-2">
                                <strong>Budget:</strong> ₱{{ number_format($selectedPpmp->abc, 2) }}
                            </div>
                        @endif

                        <div class="mt-2 text-sm text-gray-700 flex space-x-8">
                            <p>
                                <strong>Pre Date:</strong> 
                                {{ \Carbon\Carbon::parse($selectedPpmp->invitations->last()->pre_date)->format('F d, Y') }}
                            </p>
                            <p>-</p>
                            <p>
                                <strong>Submission Deadline:</strong> 
                                {{ \Carbon\Carbon::parse($selectedPpmp->invitations->last()->submission_deadline)->format('F d, Y') }}
                            </p>
                        </div>



                        {{-- Always show the table --}}
                        <h3 class="font-bold mb-2">Requested Items</h3>
                        <table class="min-w-full border border-gray-300 text-sm">
                            <thead class="bg-[#EFE8A5]">
                                <tr>
                                    <th class="px-4 py-2 text-left">Description</th>
                                    <th class="px-4 py-2 text-left">Quantity</th>
                                    <th class="px-4 py-2 text-left">Unit</th>
                                    <th class="px-4 py-2 text-left">Unit Cost</th>
                                    <th class="px-4 py-2 text-left">Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedPpmp->items as $item)
                                    <tr>
                                        <td class="px-4 py-2 ">{{ $item->description }}</td>
                                        <td class="px-4 py-2 ">{{ $item->qty }}</td>
                                        <td class="px-4 py-2 ">{{ $item->unit }}</td>
                                        <td class="px-4 py-2 ">₱{{ number_format($item->unit_cost, 2) }}</td>
                                        <td class="px-4 py-2 ">₱{{ number_format($item->total_cost, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                @endif

                <h2 class="text-lg font-semibold">Supplier Submissions</h2>

                {{-- your table remains untouched --}}
                <table class="min-w-full text-sm border">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">Supplier</th>
                            <th class="px-4 py-2 text-left">Bid Amount</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Submitted At</th>
                            <th class="px-4 py-2 text-left">Items</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($submissions as $submission)
                            <tr>
                                <td class="px-4 py-2">{{ $submission->supplier->first_name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">₱{{ number_format($submission->bid_amount, 2) }}</td>
                                <td class="px-4 py-2">{{ ucfirst($submission->status) }}</td>
                                <td class="px-4 py-2">{{ $submission->submitted_at }}</td>
                                <td class="px-4 py-2">
                                    <ul class="list-disc pl-4">
                                        @foreach($submission->items as $item)
                                            <li>
                                                {{ $item->procurementItem->description }}
                                                - ₱{{ number_format($item->unit_price, 2) }}
                                                (x{{ $item->procurementItem->qty }})
                                                = ₱{{ number_format($item->total_price, 2) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                                    No submissions yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </template>

</div>
