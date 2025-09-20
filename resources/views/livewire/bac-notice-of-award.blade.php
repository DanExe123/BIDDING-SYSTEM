<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Notice Of Award</h1>
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
                <div class="bg-[#062B4A] text-center py-2 rounded-t-md">
                    <h2 class="text-lg font-semibold text-white">Notice Of Award
                </div>
                <div x-data="{ showModal: false }">
                    <!-- Alpine component wrapper -->
                    <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                        <table wire:poll class="min-w-full text-sm">
                            <thead class="bg-blue-200 font-semibold border-b border-gray-300">
                                <tr>
                                    <th class="w-1/6 text-left px-4 py-2">Reference No</th>
                                    <th class="w-1/6 text-center px-4 py-2">Purpose</th>
                                    <th class="w-1/6 text-center px-4 py-2">Procurement Type</th>
                                    <th class="w-1/6 text-center px-4 py-2">Status</th>
                                    <th class="w-1/6 text-center px-4 py-2">Awarded To</th>
                                    <th class="w-1/6 text-center px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ppmps as $ppmp)
                                    @php
                                        $invitation = $ppmp->invitations->last();
                                        $awardedSubmissions = $invitation?->submissions->where('status', 'awarded');
                                    @endphp

                                    @if($awardedSubmissions && $awardedSubmissions->isNotEmpty())
                                        <tr class="border-b border-gray-200">
                                            <td class="px-4 py-2">{{ $invitation->reference_no ?? 'No Ref' }}</td>
                                            <td class="px-4 py-2 text-center">{{ $ppmp->project_title }}</td>
                                            <td class="px-4 py-2 text-center">{{ ucfirst($ppmp->mode_of_procurement) }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <span class="px-2 py-1 rounded-full text-sm bg-blue-100 text-blue-700">
                                                    Awarded
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                {{ $awardedSubmissions->pluck('supplier.first_name')->join(', ') }}
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <button wire:click="showAwardedSuppliers({{ $ppmp->id }})"
                                                        @click="showModal = true"
                                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-gray-500">
                                            No awarded submissions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="px-4 py-2">
                            {{ $ppmps->links() }}
                        </div>
                    </div>

                    <!-- Modal -->
                     <div x-show="showModal" x-transition
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                        <div class="bg-white w-[90%] md:w-[800px] rounded-md  ml-24 shadow-lg max-h-[90vh] overflow-y-auto"
                            @click.away="showModal = false">

                            <!-- Loading State -->
                            @if(!$selectedPpmp)
                                <div class="p-6 text-center">
                                    <div class="flex justify-center mb-4">
                                        <svg class="animate-spin h-8 w-8 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600">Loading award details...</p>
                                </div>
                            @else
                                @php
                                    $invitation = $selectedPpmp->invitations->first();
                                    $awardedSubmission = $invitation?->submissions->first() ?? null;
                                @endphp

                                <!-- Header -->
                                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                                    <div>
                                        <p class="text-base font-semibold text-green-700">Notice of Award</p>
                                        <p class="text-sm text-gray-600">
                                            Reference: <span class="font-medium">{{ $invitation?->reference_no ?? 'N/A' }} ({{ $invitation?->title }})</span>
                                        </p>
                                    </div>
                                    <button @click="showModal = false" class="text-gray-600 text-xl font-bold hover:text-black">&times;</button>
                                </div>

                                <!-- Award Details -->
                                <div class="px-6 py-4 border-b border-gray-300 bg-[#F9FAFB] space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-sm text-gray-600">Supplier</label>
                                            <input type="text" readonly class="w-full border-none bg-transparent px-3 py-2 font-semibold"
                                                  value="{{ $awardedSubmission?->supplier?->first_name ?? 'N/A' }}">
                                        </div>
                                        <div>
                                            <label class="text-sm text-gray-600">Bid Amount</label>
                                            <input type="text" readonly class="w-full border-none bg-transparent px-3 py-2 font-semibold text-green-700"
                                                  value="₱{{ number_format($awardedSubmission?->bid_amount ?? 0, 2) }}">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-sm text-gray-600">Technical Score</label>
                                            <input type="text" readonly class="w-full border-none bg-transparent px-3 py-2"
                                                  value="{{ $awardedSubmission?->technical_score ?? 'N/A' }}">
                                        </div>
                                        <div>
                                            <label class="text-sm text-gray-600">Financial Score</label>
                                            <input type="text" readonly class="w-full border-none bg-transparent px-3 py-2"
                                                  value="{{ $awardedSubmission?->financial_score ?? 'N/A' }}">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-sm text-gray-600">Total Score</label>
                                            <input type="text" readonly class="w-full border-none bg-transparent px-3 py-2 font-bold"
                                                  value="{{ $awardedSubmission?->total_score ?? 'N/A' }}">
                                        </div>
                                        <div>
                                            <label class="text-sm text-gray-600">Award Date</label>
                                            <input type="text" readonly class="w-full border-none bg-transparent px-3 py-2"
                                                  value="{{ $awardedSubmission?->awarded_at ? \Carbon\Carbon::parse($awardedSubmission->awarded_at)->format('F d, Y') : '—' }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- File Download -->
                                <div class="p-6">
                                    <p class="text-sm font-semibold text-gray-700 mb-2">Award Document:</p>
                                    <div class="flex items-center justify-between border border-gray-200 rounded-md px-4 py-2">
                                        <span class="text-sm text-gray-600">
                                            Notice_Of_Award_{{ $invitation?->reference_no ?? 'N/A' }}.pdf
                                        </span>
                                        <a href="{{ route('award.pdf', $selectedPpmp->id) }}"
                                          target="_blank"
                                          class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
                                            📥 Download
                                        </a>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="px-6 py-4 flex justify-end border-t border-gray-200">
                                    <button @click="showModal = false"
                                            class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">
                                        Close
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>


            </div>

      </main>
    </div>
</div>
     