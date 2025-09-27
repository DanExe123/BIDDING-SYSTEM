<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Supplier Comparison</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-white p-6">

<!-- resources/views/livewire/generate-report.blade.php -->
<div class="p-6 bg-white">

  <div class="max-w-7xl mx-auto mt-10 bg-[#B3EAF1] border border-gray-300 rounded-md p-6 flex flex-col md:flex-row gap-6 items-start">
    
    <!-- Chart (BIGGER) -->
    <div class="w-full md:w-3/5 h-[520px]">
      <canvas id="supplierChart" class="w-full h-full"></canvas>
    </div>

    <!-- Summary (BIGGER text) -->
    <div class="w-full md:w-2/5 space-y-4 text-base text-gray-800">
      @forelse($submissions as $submission)
        @if($ppmp->mode_of_procurement === 'bidding')
            <p class="leading-relaxed">
                <strong class="text-lg">{{ $submission->supplier->first_name }}</strong>
                scored <strong>{{ $submission->total_score ?? '-' }}</strong>/100 overall
                (Technical: {{ $submission->technical_score ?? '-' }}, Financial: {{ $submission->financial_score ?? '-' }}),
                with a bid amount of <strong>₱{{ number_format($submission->bid_amount ?? 0, 2) }}</strong>.
            </p>
        @elseif($ppmp->mode_of_procurement === 'quotation' && $submission->items->count() > 0)
            @php
                $totalPrice = $submission->items->sum(fn($i) => ($i->unit_price ?? 0) * ($i->procurementItem->qty ?? 1));
            @endphp

            <p class="leading-relaxed">
                <strong class="text-lg">{{ $submission->supplier->first_name }}</strong>
                quoted a total of <strong>₱{{ number_format($totalPrice, 2) }}</strong> 
                with delivery in <strong>{{ $submission->delivery_days ?? 'N/A' }} days</strong>.
            </p>

            <div class="ml-6 mt-2">
                <ul class="list-disc pl-5 text-gray-700">
                    @foreach($submission->items as $item)
                        <li>
                            {{ $item->procurementItem->description ?? 'N/A' }}
                            - ₱{{ number_format($item->unit_price, 2) }}
                            (x{{ $item->procurementItem->qty ?? 1 }})
                            = ₱{{ number_format($item->total_price, 2) }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
      @empty
          <p class="text-gray-600">No submissions found.</p>
      @endforelse

      @if($ppmp->mode_of_procurement === 'quotation' && $submissions->count() > 0)
        @php
            $sorted = $submissions->sort(function ($a, $b) {
                $aTotal = $a->items->sum(fn($i) => ($i->unit_price ?? 0) * ($i->procurementItem->qty ?? 1));
                $bTotal = $b->items->sum(fn($i) => ($i->unit_price ?? 0) * ($i->procurementItem->qty ?? 1));
                if ($aTotal == $bTotal) {
                    return ($a->delivery_days ?? PHP_INT_MAX) <=> ($b->delivery_days ?? PHP_INT_MAX);
                }
                return $aTotal <=> $bTotal;
            });
            $lowest = $sorted->first();
        @endphp
        <p class="mt-4 font-semibold text-green-700">
            Supplier <strong>{{ $lowest->supplier->first_name }}</strong> offered the lowest total price of 
            <strong>₱{{ number_format($lowest->items->sum(fn($i) => ($i->unit_price ?? 0) * ($i->procurementItem->qty ?? 1)), 2) }}</strong> 
            with delivery in <strong>{{ $lowest->delivery_days ?? 'N/A' }} days</strong>, which is lower than all other suppliers.
        </p>
    @endif


      <!-- Action Button (unchanged size) -->
      <div x-data="{ showModal: false }" class="relative">
        <button @click="showModal = true"
                class="bg-yellow-200 hover:bg-yellow-300 text-gray-800 font-semibold py-2 px-4 rounded-md shadow inline-flex items-center">
          Select and send notice of award to supplier
          <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.293 15.707a1 1 0 010-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"/></svg>
        </button>

        <!-- Award Modal (kept same size as you wanted) -->
        <div x-show="showModal" x-transition 
             class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div class="bg-white w-[90%] md:w-[700px] rounded-md shadow-lg overflow-hidden" @click.away="showModal = false">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-white text-center">
              <h2 class="text-lg font-semibold">Award Contract</h2>
              <p class="text-sm text-gray-600">
                {{ $ppmp->invitations->first()->reference_no }} {{ $ppmp->project_title }}
              </p>
            </div>

            <!-- Body: using wire:submit to validate award_date -->
            @php
              // $winner is passed from the component render()
            @endphp

            <form wire:submit.prevent="issueAward({{ $winner ? $winner->id : 'null' }})" class="px-6 py-6 space-y-6 text-sm text-gray-800">
              @if(!$winner)
                <p class="text-red-600">No recommended awardee available.</p>
              @else
                <!-- Recommended Awardee -->
                <div class="bg-green-100 border border-green-200 rounded-md px-4 py-3">
                  <p class="text-sm font-semibold text-green-800">Recommended Awardee</p>
                  <div class="flex justify-between items-center mt-1">
                    <span class="text-green-700 font-medium">{{ $winner->supplier->first_name }}</span>
                    <div class="text-right text-xs text-green-800">
                      <p>Total Score: <strong>{{ $winner->total_score }}/100</strong></p>
                      <p>Bid Amount: <strong>₱{{ number_format($winner->bid_amount, 2) }}</strong></p>
                    </div>
                  </div>
                </div>

                <!-- Award Date -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Award Date*</label>
                  <input type="date" wire:model="award_date" value="{{ $award_date }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" />
                  @error('award_date') 
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p> 
                  @enderror
                </div>


                <!-- Remarks -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Remarks</label>
                  <textarea wire:model="remarks" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                            placeholder="Any additional notes..."></textarea>
                  @error('remarks') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Generate Notice -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">Generate Notice of Award</label>
                  <div class="mt-1 flex items-center justify-between border rounded px-3 py-2 bg-gray-50">
                    <span class="text-sm text-gray-600">Notice_Of_Award_{{ $ppmp->invitations->first()->reference_no }}.docx</span>
                    <a href="{{ route('award.pdf', $ppmp->id) }}" 
                      target="_blank" 
                      class="text-sm text-green-600 hover:underline">
                      Download
                    </a>

                  </div>
                </div>
              @endif

              <!-- Footer -->
              <div class="px-0 py-4 flex justify-end items-center border-t border-gray-200 bg-gray-50">
                <button type="button" class="px-4 py-2 text-sm text-gray-700 bg-gray-200 hover:bg-gray-300 rounded mr-2" @click="showModal = false">Back</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 rounded"
                        >
                  Issue Award
                </button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Chart JS -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('supplierChart').getContext('2d');

    const data = {
        labels: @json($submissions->pluck('supplier.first_name')),
        datasets: [
            {
                label: 'Technical Score',
                data: @json($submissions->pluck('technical_score')),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                yAxisID: 'yScores'
            },
            {
                label: 'Financial Score',
                data: @json($submissions->pluck('financial_score')),
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                yAxisID: 'yScores'
            },
            {
                label: 'Total Score',
                data: @json($submissions->pluck('total_score')),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                yAxisID: 'yScores'
            },
            {
                label: 'Bid Amount (₱)',
                data: @json($submissions->pluck('bid_amount')),
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                yAxisID: 'yBid'
            }
        ]
    };

    new Chart(ctx, {
          type: 'bar',
          data: data,
          options: {
            responsive: true,
            maintainAspectRatio: false, // so h-[520px] is respected
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top' } },
            scales: {
                yScores: {
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: 'Scores (0–100)' },
                    min: 0,
                    max: 100
                },
                yBid: {
                    type: 'linear',
                    position: 'right',
                    title: { display: true, text: 'Bid Amount (₱)' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
});
</script>



</body>
</html>
