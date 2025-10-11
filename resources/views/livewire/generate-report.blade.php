  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Supplier Comparison</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body class="bg-white">

  <!-- resources/views/livewire/generate-report.blade.php -->
  <div class="bg-white min-h-screen pb-10">

    <div class="max-w-7xl mx-auto mt-10 bg-[#B3EAF1] border border-gray-300 rounded-md p-6">
      <!-- Header -->
    <div class="max-w-7xl mx-auto flex justify-between items-center mb-8 px-6">
        <h1 class="text-2xl font-bold text-gray-800">Supplier Submission Report</h1>
        <a href="{{ route('bac-procurement-workflow') }}" 
           class="bg-[#062B4A] hover:bg-gray-600 hover:text-black' text-white font-semibold px-4 py-2 rounded-md shadow">
            ← Back
        </a>
    </div>
  
      {{-- Bidding Chart --}}
      @if($ppmp->mode_of_procurement === 'bidding')
      <div class="w-full overflow-x-auto">
        <div class="min-w-[800px] h-[520px]">
          <canvas id="supplierChartBidding" class="w-full h-full"></canvas>
        </div>
      </div>
      @endif

      {{-- Quotation Chart --}}
      @if($ppmp->mode_of_procurement === 'quotation')
      <div class="w-full overflow-x-auto mt-10">
        <div class="min-w-[800px] h-[520px]">
          <canvas id="supplierChartQuotation" class="w-full h-full"></canvas>
        </div>
      </div>
      @endif

      {{-- Summary Section --}}
      <div class="w-full mt-8 space-y-4 text-base text-gray-800">
        @forelse($submissions as $submission)
            @if($ppmp->mode_of_procurement === 'bidding')
                <p class="leading-relaxed">
                    <strong class="text-lg">{{ $submission->supplier->first_name }}</strong>
                    scored <strong>{{ $submission->total_score ?? '-' }}</strong>/100 overall
                    (Technical: {{ $submission->technical_score ?? '-' }}, Financial: {{ $submission->financial_score ?? '-' }}),
                    with a bid amount of <strong>₱{{ number_format($submission->bid_amount ?? 0, 2) }}</strong>
                    and delivery in <strong>{{ $submission->delivery_days ?? 'N/A' }} days</strong>.
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

        {{-- Lowest Price Summary (Quotation Mode) --}}
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
                with delivery in <strong>{{ $lowest->delivery_days ?? 'N/A' }} days</strong>.
            </p>
        @endif

        {{-- Award Info --}}
        @if($awardedSubmission)
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-md mt-4">
                <p>
                    <strong>{{ $awardedSubmission->supplier->first_name ?? 'Unknown Supplier' }}</strong>
                    has already been awarded on 
                    <strong>{{ \Carbon\Carbon::parse($awardedSubmission->award_date)->format('F d, Y') }}</strong>
                    <span class="text-sm text-gray-600">
                        ({{ \Carbon\Carbon::parse($awardedSubmission->award_date)->diffForHumans() }})
                    </span>.
                </p>
                @if($awardedSubmission->remarks)
                    <p class="text-sm mt-1 text-gray-700 italic">
                        Remarks: "{{ $awardedSubmission->remarks }}"
                    </p>
                @endif
            </div>
        @else
            <livewire:award-modal 
                :ppmp="$ppmp" 
                :submissions="$submissions" 
                :winner="$winner" 
                wire:key="award-modal-{{ $ppmp->id }}" 
            />
        @endif
      </div>
    </div>
  </div>

    <!-- Chart JS -->
  <script>
  document.addEventListener("DOMContentLoaded", function() {
      // ---------------- BIDDING CHART ----------------
      const ctxBidding = document.getElementById('supplierChartBidding')?.getContext('2d');
      if (ctxBidding) {
          const biddingData = {
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
                  },
                  {
                      label: 'Delivery Days',
                      data: @json($submissions->pluck('delivery_days')),
                      type: 'line', // 👈 makes it a line
                      borderColor: 'rgba(0, 128, 0, 1)',
                      backgroundColor: 'rgba(0, 128, 0, 0.2)',
                      borderWidth: 2,
                      fill: false,
                      yAxisID: 'yDays'
                  }
              ]
          };

          new Chart(ctxBidding, {
              type: 'bar',
              data: biddingData,
              options: {
                  responsive: true,
                  maintainAspectRatio: false,
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
                      },
                      yDays: {
                          type: 'linear',
                          position: 'right',
                          title: { display: true, text: 'Delivery Days' },
                          grid: { drawOnChartArea: false },
                          ticks: { beginAtZero: true }
                      }
                  }
              }
          });
      }

      // ---------------- QUOTATION CHART ----------------
      const ctxQuotation = document.getElementById('supplierChartQuotation')?.getContext('2d');
      if (ctxQuotation) {
          const suppliers = @json($submissions->pluck('supplier.first_name'));
          const totalPrices = @json(
              $submissions->map(fn($s) => $s->items->sum(fn($i) => ($i->unit_price ?? 0) * ($i->procurementItem->qty ?? 1)))
          );
          const deliveryDays = @json($submissions->pluck('delivery_days'));

          const quotationData = {
              labels: suppliers,
              datasets: [
                  {
                      label: 'Total Quotation (₱)',
                      data: totalPrices,
                      backgroundColor: 'rgba(54, 162, 235, 0.6)',
                      borderColor: 'rgba(54, 162, 235, 1)',
                      borderWidth: 1,
                      yAxisID: 'yPrice'
                  },
                  {
                      label: 'Delivery Days',
                      data: deliveryDays,
                      backgroundColor: 'rgba(255, 99, 132, 0.6)',
                      borderColor: 'rgba(255, 99, 132, 1)',
                      borderWidth: 1,
                      yAxisID: 'yDays'
                  }
              ]
          };

          new Chart(ctxQuotation, {
              type: 'bar',
              data: quotationData,
              options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  interaction: { mode: 'index', intersect: false },
                  plugins: { legend: { position: 'top' } },
                  scales: {
                      yPrice: {
                          type: 'linear',
                          position: 'left',
                          title: { display: true, text: 'Total Price (₱)' },
                          ticks: { beginAtZero: true }
                      },
                      yDays: {
                          type: 'linear',
                          position: 'right',
                          title: { display: true, text: 'Delivery Days' },
                          grid: { drawOnChartArea: false },
                          ticks: { beginAtZero: true }
                      }
                  }
              }
          });
      }

      });
  </script>

  </body>
  </html>
