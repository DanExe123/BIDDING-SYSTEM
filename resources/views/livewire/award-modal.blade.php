<div x-data="{ showModal: false }" 
     x-on:close-award-modal.window="showModal = false"
     x-cloak>

  <!-- Button -->
  <button @click="showModal = true"
          class="bg-yellow-200 hover:bg-yellow-300 text-gray-800 font-semibold py-2 px-4 rounded-md shadow inline-flex items-center">
    Select and send notice of award to supplier
    <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
      <path d="M10.293 15.707a1 1 0 010-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"/>
    </svg>
  </button>

  <!-- Award Modal -->
  <div x-show="showModal" x-transition
       class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

    <div class="bg-white w-[90%] md:w-[700px] rounded-md shadow-lg overflow-hidden"
         @click.away="showModal = false">

      <!-- Header -->
      <div class="px-6 py-4 border-b border-gray-200 bg-white text-center">
        <h2 class="text-lg font-semibold">Award Contract</h2>
        <p class="text-sm text-gray-600">
          {{ $ppmp->invitations->first()->reference_no }} {{ $ppmp->project_title }}
        </p>
      </div>

      <div class="px-6 py-6 space-y-6 text-sm text-gray-800">
        @if(!$winner)
          <p class="text-red-600">No recommended awardee available.</p>
        @else
          <div class="bg-green-100 border border-green-200 rounded-md px-4 py-3">
            <p class="text-sm font-semibold text-green-800">Recommended Awardee</p>
            <div class="flex justify-between items-center mt-1">
              <span class="text-green-700 font-medium">{{ $winner->supplier->first_name }}</span>
              @if($ppmp->mode_of_procurement === 'bidding')
                <div class="text-right text-xs text-green-800">
                  <p>Total Score: <strong>{{ $winner->total_score }}/100</strong></p>
                  <p>Bid Amount: <strong>₱{{ number_format($winner->bid_amount, 2) }}</strong></p>
                </div>
              @endif
            </div>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-semibold text-gray-800">Select Supplier to Award*</label>
            <select wire:model="selectedSubmissionId"
              class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-4 pr-10 text-sm text-gray-700 shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-400 transition">
              <option value="">-- Choose Supplier --</option>
              @foreach($submissions as $submission)
                <option value="{{ $submission->id }}">
                  {{ $submission->supplier->first_name }}
                  @if($ppmp->mode_of_procurement === 'bidding')
                    — Score: {{ $submission->total_score }}/100 | Bid: ₱{{ number_format($submission->bid_amount, 2) }}
                  @else
                    — Quotation: ₱{{ number_format($submission->items->sum(fn($i) => ($i->unit_price ?? 0) * ($i->procurementItem->qty ?? 1)), 2) }}
                  @endif
                </option>
              @endforeach
            </select>
            @error('selectedSubmissionId') 
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p> 
            @enderror
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Award Date*</label>
            <input type="date" wire:model="award_date"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" />
            @error('award_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Remarks</label>
            <textarea wire:model="remarks" rows="3"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
              placeholder="Any additional notes..."></textarea>
            @error('remarks') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Generate Notice of Award</label>
            @if($ppmp->mode_of_procurement === 'bidding')
              <div class="mt-1 flex items-center justify-between border rounded px-3 py-2 bg-gray-50">
                <span class="text-sm text-gray-600">Notice_Of_Award_{{ $ppmp->invitations->first()->reference_no }}.pdf</span>
                <a href="{{ route('award.pdf', $ppmp->id) }}" target="_blank" class="text-sm text-green-600 hover:underline">Download</a>
              </div>
            @else
              <div class="mt-1 flex items-center justify-between border rounded px-3 py-2 bg-gray-50">
                <span class="text-sm text-gray-600">Notice_Of_Award_Quotation_{{ $ppmp->invitations->first()->reference_no }}.pdf</span>
                <a href="{{ route('award.quotation.pdf', $ppmp->id) }}" target="_blank" class="text-sm text-green-600 hover:underline">Download</a>
              </div>
            @endif
          </div>
        @endif

        <div class="px-0 py-4 flex justify-end items-center border-t border-gray-200 bg-gray-50">
          <button type="button" class="px-4 py-2 text-sm text-gray-700 bg-gray-200 hover:bg-gray-300 rounded mr-2" @click="showModal = false">
            Back
          </button>
          <button wire:click="issueAward" class="px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 rounded">
            Issue Award
          </button>
        </div>
      </div>
    </div>
  </div>

    <!-- ✅ Success Modal -->
    @if($showSuccess)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
        <div class="bg-white w-[90%] md:w-[400px] rounded-lg shadow-lg text-center p-6 space-y-4">
        <svg class="w-12 h-12 text-green-500 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <h3 class="text-lg font-semibold text-gray-800">Award Issued Successfully</h3>
        <p class="text-sm text-gray-600">
            The supplier <span class="font-semibold text-green-700">{{ $awardedSupplierName }}</span> has been awarded successfully.
        </p>
        <button wire:click="closeSuccessModal"
                class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            Close
        </button>
        </div>
    </div>
    @endif

</div>
