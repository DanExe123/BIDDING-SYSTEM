<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">SKU</h1>
        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1" x-cloak>
            <div class="bg-white rounded-md shadow-md border border-gray-300" x-cloak>
                <div class="bg-[#062B4A] text-center py-2 rounded-t-md">
                    <h2 class="text-lg font-semibold text-white">SKU</h2>
                </div>
                <div x-data="{ showModal: false }" x-on:close-modal.window="showModal = false" class="relative">

                    <!-- Request Table -->
                    <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                        <table wire:poll class="min-w-full text-sm">
                            <thead class="bg-blue-200 font-semibold border-b border-gray-300">
                                <tr>
                                    <th class="w-1/6 text-left px-4 py-2">Reference No</th>
                                    <th class="w-1/6 text-center px-4 py-2">Purpose</th>
                                    <th class="w-1/6 text-center px-4 py-2">Procurement Type</th>
                                    <th class="w-1/6 text-center px-4 py-2">Awarded at</th>
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
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-2 font-medium">
                                                {{ $invitation->reference_no ?? 'No Ref' }}
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                {{ Str::limit($ppmp->project_title, 40) }}
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                @php $mode = $ppmp->mode_of_procurement; @endphp
                                                <span class="
                                                    px-2 py-1 rounded-full text-xs font-semibold
                                                    @if($mode === 'bidding') bg-blue-100 text-blue-800
                                                    @elseif($mode === 'quotation') bg-green-100 text-green-800
                                                    @else bg-gray-200 text-gray-600
                                                    @endif
                                                ">
                                                    {{ ucfirst($mode ?? 'Not selected') }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                @php
                                                    $latestAward = $awardedSubmissions->sortByDesc('updated_at')->first();
                                                @endphp
                                                @if($latestAward)
                                                    {{ $latestAward->updated_at->diffInHours() < 24 
                                                        ? $latestAward->updated_at->diffForHumans() 
                                                        : $latestAward->updated_at->format('n/j/Y, g:i A') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    Awarded
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                {{ $awardedSubmissions->pluck('supplier.first_name')->unique()->implode(', ') }}
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <button wire:click="showAwardedSuppliers({{ $ppmp->id }})" 
                                                        x-on:click="showModal = true"
                                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm transition-colors">
                                                    View 
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center text-gray-500 bg-gray-50">
                                            No awarded items found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="px-4 py-2">
                            {{ $ppmps->links() }}
                        </div>
                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
                        <div class="bg-gray-100 w-full ml-24 max-w-4xl max-h-[90vh] rounded-md shadow-xl overflow-hidden" @click.away="showModal = false">
                            
                            <!-- Loading Spinner -->
                            <div wire:loading.flex wire:target="showAwardedSuppliers" class="p-10 flex justify-center items-center absolute inset-0 bg-gray-100/80 z-10">
                                <svg class="animate-spin h-8 w-8 text-gray-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span class="text-gray-600 font-medium">Loading awarded items...</span>
                            </div>

                            <!-- Modal Content -->
                            <div wire:loading.remove wire:target="showAwardedSuppliers">
                                <!-- Header -->
                                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300 bg-white">
                                    <div>
                                        @if($selectedPpmp)
                                            <p class="text-sm font-medium text-gray-700">
                                                <strong>Project:</strong> {{ $selectedPpmp->project_title }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <strong>Requested by:</strong> {{ $selectedPpmp->requester->first_name ?? '' }} {{ $selectedPpmp->requester->last_name ?? '' }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <button @click="showModal = false; $wire.closeModal()" 
                                                class="text-gray-500 hover:text-gray-700 text-xl font-bold p-1 hover:bg-gray-200 rounded-full transition-colors">
                                            &times;
                                        </button>
                                    </div>
                                </div>

                                <!-- Awarded Items Table -->
                                <div class="overflow-x-auto px-6 py-6 max-h-[60vh] overflow-y-auto">
                                    <table class="min-w-full bg-white text-sm border border-gray-300 rounded-lg overflow-hidden">
                                        <thead class="bg-gray-100 sticky top-0 z-10">
                                            <tr>
                                                <th class="border px-3 py-3 text-left font-semibold text-xs text-gray-700 uppercase tracking-wide w-24">SKU</th>
                                                <th class="border px-3 py-3 text-left font-semibold text-xs text-gray-700 uppercase tracking-wide w-[40%]">Item Description</th>
                                                <th class="border px-3 py-3 text-center font-semibold text-xs text-gray-700 uppercase tracking-wide w-16">QTY</th>
                                                <th class="border px-3 py-3 text-center font-semibold text-xs text-gray-700 uppercase tracking-wide w-16">UNIT</th>
                                                {{--<th class="border px-3 py-3 text-right font-semibold text-xs text-gray-700 uppercase tracking-wide w-32">UNIT COST</th>
                                                <th class="border px-3 py-3 text-right font-semibold text-xs text-gray-700 uppercase tracking-wide w-32">TOTAL COST</th>--}}
                                                <th class="border px-3 py-3 text-center font-semibold text-xs text-gray-700 uppercase tracking-wide w-[20%]">SUPPLIER</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @forelse(\App\Models\AwardedItem::with(['supplier', 'procurementItem'])->where('ppmp_id', $selectedPpmp?->id ?? 0)->get() as $item)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="border px-3 py-4 font-mono text-sm bg-gray-50 w-24">{{ $item->sku ?: 'N/A' }}</td>
                                                    <td class="border px-3 py-4 text-sm text-gray-900 w-[40%] max-w-[40%]">
                                                        {{ Str::limit($item->description, 60) }}
                                                    </td>
                                                    <td class="border px-3 py-4 text-center font-semibold text-lg text-gray-900 w-16">{{ $item->qty }}</td>
                                                    <td class="border px-3 py-4 text-center text-sm uppercase font-medium w-16">{{ $item->unit }}</td>
                                                    {{--<td class="border px-3 py-4 text-right font-mono text-sm text-gray-900">₱{{ number_format($item->unit_cost, 2) }}</td>
                                                    <td class="border px-3 py-4 text-right font-bold text-lg bg-green-50 text-green-900">₱{{ number_format($item->total_cost, 2) }}</td>--}}
                                                    <td class="border px-3 py-4 text-center w-[20%]">
                                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                            {{ $item->supplier->first_name ?? 'Unknown' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="border px-6 py-12 text-center text-gray-500">
                                                        No awarded items found for this procurement.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        {{--@if($selectedPpmp)
                                            @php $total = \App\Models\AwardedItem::where('ppmp_id', $selectedPpmp->id)->sum('total_cost'); @endphp
                                            @if($total > 0)
                                            <tfoot class="bg-gradient-to-r from-blue-50 to-indigo-50 border-t-2 border-blue-200">
                                                <tr>
                                                    <td colspan="5" class="border px-3 py-4 text-right font-bold text-lg text-gray-900">GRAND TOTAL:</td>
                                                    <td class="border px-3 py-4 text-right font-extrabold text-2xl text-green-700 bg-white rounded-tr-lg">₱{{ number_format($total, 2) }}</td>
                                                    <td class="border px-3 py-4"></td>
                                                </tr>
                                            </tfoot>
                                            @endif
                                        @endif--}}
                                    </table>
                                </div>


                                <!-- Footer -->
                                <div class="px-6 py-4 flex justify-between items-center border-t border-gray-300 bg-white">
                                    <div class="text-sm text-gray-600">
                                        <strong>Awarded Items Summary</strong> • {{ $selectedPpmp?->project_title }}
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button @click="showModal = false; $wire.closeModal()" 
                                                class="px-6 py-2 text-sm bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors font-medium">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
         

      </main>
    </div>
</div>
     