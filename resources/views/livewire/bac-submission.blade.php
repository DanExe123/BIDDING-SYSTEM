<div x-data="{ showSubmission: false }" x-on:ppmp-loaded.window="showSubmission = true"
    class="relative" x-cloak>

    <!-- Default Table View  -->
    <template x-if="!showSubmission">
        <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
            <table wire:poll class="min-w-full text-sm">
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
                                {{--
                                    @if($ppmp->invitations->isNotEmpty())
                                        @php 
                                            $status = $ppmp->invitations->last()->status;
                                            // remap 'published' to 'open' for display
                                            $displayStatus = $status === 'published' ? 'open' : $status;
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-sm
                                            @if($status === 'published') bg-green-100 text-green-700
                                            @elseif($status === 'close') bg-gray-200 text-gray-700
                                            @elseif($status === 'awarded') bg-blue-100 text-blue-700
                                            @endif">
                                            {{ ucfirst($displayStatus) }}
                                        </span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-sm">
                                            Pending
                                        </span>
                                    @endif
                                --}}
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

           
            <div class="px-4 py-2">
                {{ $ppmps->links() }}
            </div>
          
        </div>
    </template>

    <!-- Evaluation / Submissions View -->
    <template x-if="showSubmission" x-cloak>
        <div class="bg-white rounded-md border border-gray-300 shadow-sm m-4 p-6 space-y-4">
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-lg font-semibold">Evaluation</h2>
                <button
                    @click="showSubmission = false; $wire.closeModal()"
                    class="inline-flex items-center gap-2 text-sm text-blue-600 hover:underline" aria-label="Back" title="Back">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span>Back</span>
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
            <div wire:loading.remove wire:target="showPpmp" >
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

                        <div class="mt-2 mb-2 text-sm text-gray-700 flex space-x-8">
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
                                    <th class="px-4 py-2 text-left">Estimated Unit Cost</th>
                                    <th class="px-4 py-2 text-left">Estimated Total Cost</th>
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

                <div x-data="{ showDocsModal: false, showEvalModal: false }" class="relative" x-cloak>
                    <table class="min-w-full text-sm border">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left">Supplier</th>
                                <th class="px-4 py-2 text-left">
                                    {{ optional($selectedPpmp)->mode_of_procurement === 'quotation' ? 'Items' : 'Bid Amount' }}
                                </th>

                                {{-- Show only if bidding --}}
                                @if(optional($selectedPpmp)->mode_of_procurement === 'bidding')
                                    <th class="px-4 py-2 text-left">Technical Score</th>
                                    <th class="px-4 py-2 text-left">Financial Score</th>
                                    <th class="px-4 py-2 text-left">Total Score</th>
                                @endif

                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($submissions as $submission)
                                <tr>
                                    <td class="px-4 py-2">{{ $submission->supplier->first_name ?? 'N/A' }}</td>

                                    {{-- Inline condition --}}
                                    <td class="px-4 py-2">
    @if($selectedPpmp->mode_of_procurement === 'quotation')
        <table class="w-full border text-xs">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-2 py-1 text-left">Description</th>
                    <th class="px-2 py-1 text-right">Unit Price</th>
                    <th class="px-2 py-1 text-right">Qty</th>
                    <th class="px-2 py-1 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submission->items as $item)
                    <tr>
                        <td class="px-2 py-1">{{ $item->procurementItem->description }}</td>
                        <td class="px-2 py-1 text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-2 py-1 text-right">{{ $item->procurementItem->qty }}</td>
                        <td class="px-2 py-1 text-right">₱{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        ₱{{ number_format($submission->bid_amount, 2) }}
    @endif
</td>


                                    {{-- Show only if bidding --}}
                                    @if(optional($selectedPpmp)->mode_of_procurement === 'bidding')
                                        <td class="px-4 py-2 text-center">
                                            {{ $submission->technical_score !== null 
                                                ? (fmod($submission->technical_score, 1) == 0 
                                                    ? intval($submission->technical_score) 
                                                    : rtrim(rtrim(number_format($submission->technical_score, 2), '0'), '.')) . '/100' 
                                                : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $submission->financial_score !== null 
                                                ? (fmod($submission->financial_score, 1) == 0 
                                                    ? intval($submission->financial_score) 
                                                    : rtrim(rtrim(number_format($submission->financial_score, 2), '0'), '.')) . '/100' 
                                                : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $submission->total_score !== null 
                                                ? (fmod($submission->total_score, 1) == 0 
                                                    ? intval($submission->total_score) 
                                                    : rtrim(rtrim(number_format($submission->total_score, 2), '0'), '.')) 
                                                : '-' }}
                                        </td>
                                    @endif

                                    <td class="px-4 py-2">
                                        {{-- Status logic --}}
                                        @php
                                            $status = $submission->status === 'submitted' 
                                                ? 'pending' 
                                                : ($submission->status === 'under_review' ? 'evaluated' : $submission->status);

                                            $colors = [
                                                'pending'   => 'bg-yellow-100 text-yellow-800',
                                                'evaluated' => 'bg-blue-100 text-blue-800',
                                                'awarded'   => 'bg-green-100 text-green-800',
                                                'rejected'  => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp

                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <!-- Docs button -->
                                            <button 
                                                wire:click="viewSubmission({{ $submission->id }})"
                                                @click="showDocsModal = true"
                                                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                Docs
                                            </button>

                                            <button 
                                                wire:click="evaluateSubmission({{ $submission->id }})"
                                                @click="showEvalModal = true"
                                                class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                                Evaluate
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Adjust colspan depending on bidding or not --}}
                                    <td colspan="{{ optional($selectedPpmp)->mode_of_procurement === 'bidding' ? '7' : '4' }}" 
                                        class="px-4 py-2 text-center text-gray-500">
                                        No submissions yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="flex justify-end mt-4">
                        @if($selectedPpmp)
                            <a href="{{ route('generate.report', $selectedPpmp->id) }}" 
                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 inline-flex items-center justify-center">
                                Generate Report
                            </a>
                        @endif
                    </div>



                    <!-- Docs Modal -->
                    <div 
                        x-show="showDocsModal" 
                        x-transition 
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

                        @if($selectedSubmission)
                        <div class="bg-white w-[95%] md:w-[700px] rounded-md shadow-lg max-h-[90vh] overflow-y-auto"
                            @click.away="showDocsModal = false">

                            <!-- Header -->
                            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                                <div>
                                    <p class="text-base font-semibold">Submitted Documents</p>
                                    <p class="text-sm text-gray-600">
                                        Supplier: <span class="font-medium">{{ $selectedSubmission->supplier->first_name ?? 'N/A' }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Bid Amount: ₱{{ number_format($selectedSubmission->bid_amount, 2) }}
                                    </p>
                                </div>
                                <button @click="showDocsModal = false" class="text-gray-600 text-xl font-bold hover:text-black">&times;</button>
                            </div>

                            <!-- Submitted Docs -->
                            <div class="p-6 space-y-4">
                                <!-- Technical Proposal -->
                                <div class="flex justify-between items-center border rounded px-4 py-3">
                                    <p class="font-medium text-sm">Technical Proposal</p>
                                    @if($selectedSubmission->technical_proposal_path)
                                        <div class="space-x-2">
                                            <a href="{{ route('submission.view', [$selectedSubmission->id, 'technical']) }}" target="_blank"
                                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded">View</a>
                                            <a href="{{ route('submission.download', [$selectedSubmission->id, 'technical']) }}"
                                            class="px-3 py-1 bg-green-600 text-white text-sm rounded">Download</a>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">Not submitted</span>
                                    @endif
                                </div>

                                <!-- Financial Proposal -->
                                <div class="flex justify-between items-center border rounded px-4 py-3">
                                    <p class="font-medium text-sm">Financial Proposal</p>
                                    @if($selectedSubmission->financial_proposal_path)
                                        <div class="space-x-2">
                                            <a href="{{ route('submission.view', [$selectedSubmission->id, 'financial']) }}" target="_blank"
                                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded">View</a>
                                            <a href="{{ route('submission.download', [$selectedSubmission->id, 'financial']) }}"
                                            class="px-3 py-1 bg-green-600 text-white text-sm rounded">Download</a>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">Not submitted</span>
                                    @endif
                                </div>

                                <!-- Company Profile -->
                                <div class="flex justify-between items-center border rounded px-4 py-3">
                                    <p class="font-medium text-sm">Company Profile</p>
                                    @if($selectedSubmission->company_profile_path)
                                        <div class="space-x-2">
                                            <a href="{{ route('submission.view', [$selectedSubmission->id, 'company']) }}" target="_blank"
                                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded">View</a>
                                            <a href="{{ route('submission.download', [$selectedSubmission->id, 'company']) }}"
                                            class="px-3 py-1 bg-green-600 text-white text-sm rounded">Download</a>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">Not submitted</span>
                                    @endif
                                </div>

                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Evaluation Modal -->
                    <div x-show="showEvalModal" x-transition 
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-cloak x-data 
                        x-on:close-eval-modal.window="showEvalModal = false">

                        @if($evaluationSubmission)
                        <div class="bg-white w-[95%] md:w-[500px] rounded-lg shadow-lg p-6"
                            @click.away="showEvalModal = false">

                            <h2 class="text-lg font-semibold mb-4">Evaluate {{ $evaluationSubmission->supplier->first_name ?? 'Supplier' }}</h2>

                            <div class="mb-4">
                                <label class="block text-sm font-medium">Technical Score</label>
                                <input type="number" wire:model="technical_score" min="0" max="100" step="0.01" class="w-full border rounded px-3 py-2">
                                @error('technical_score') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium">Financial Score</label>
                                <input type="number" wire:model="financial_score" min="0" max="100" step="0.01" class="w-full border rounded px-3 py-2">
                                @error('financial_score') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div wire:poll.500ms class="mb-4">
                                <label class="block text-sm font-medium">Total Score</label>

                                <!-- Input (only when not loading) -->
                                <input type="text" 
                                    wire:model="total_score" 
                                    readonly
                                    wire:loading.remove
                                    wire:target="technical_score,financial_score"
                                    class="w-full border rounded px-3 py-2 bg-gray-100">

                                <!-- Spinner (only when loading) -->
                                <div wire:loading wire:target="technical_score,financial_score" 
                                    class="flex justify-center items-center w-full border rounded px-3 py-2 bg-gray-100">
                                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8v2a6 6 0 00-6 6H4z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button wire:click="saveEvaluation"wire:loading.attr="disabled" wire:target="saveEvaluation"
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center justify-center gap-2">
                                    <span wire:loading.remove wire:target="saveEvaluation">
                                        Save
                                    </span>
                                    <span wire:loading wire:target="saveEvaluation" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                        </svg>
                                    </span>
                                </button>
                                <button 
                                    @click="showEvalModal = false"
                                    class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                                    Cancel
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
                
        </div>
    </template>

</div>
