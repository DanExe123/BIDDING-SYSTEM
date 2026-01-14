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
                <div class="m-4 rounded-md overflow-hidden">
                   
                    <div class="flex flex-wrap items-end gap-4 mb-2">
                        <!-- Search - PERFECT 1/2 width -->
                        <div class="w-1/2">
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text" 
                                placeholder="Search Ref No, SKU, Description, Purchaser, Unit, Supplier..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm"
                            />
                        </div>

                        <!-- Status Filter -->
                        <div class="flex items-center gap-2 min-w-[180px] flex-shrink-0">
                            <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Status:</label>
                            <select wire:model.live="statusFilter" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm">
                                <option value="">All</option>
                                <option value="received">Received</option>
                                <option value="not_received">Not Received</option>
                            </select>
                        </div>
                    </div>

                    <table class="min-w-full bg-white border border-gray-300 rounded-lg text-sm">
                        <thead class="bg-blue-100 sticky top-0 z-10">
                            <tr class="text-gray-700 uppercase text-xs tracking-wide">
                                <th class="border px-3 py-3 text-left w-24">Reference No</th>
                                <th class="border px-3 py-3 text-left w-24">SKU</th>
                                <th class="border px-3 py-3 text-left w-[25%]">Description / Project</th>
                                <th class="border px-3 py-3 text-left w-32">Purchaser/Implementing Unit</th>
                                <th class="border px-3 py-3 text-left w-32">Supplier</th>
                                <th class="border px-3 py-3 text-center w-16">Qty</th>
                                <th class="border px-3 py-3 text-center w-16">Unit</th>
                                <th class="border px-3 py-3 text-center w-24">Status</th>
                                <th class="border px-3 py-3 text-center w-24">Created at</th>
                                <th class="border px-3 py-3 text-center w-16">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($awardedItems as $index => $item)
                                @php
                                    static $refColorMap = [];
                                    $referenceNo = $item->ppmp->invitations->first()?->reference_no ?? 'N/A';
                                    
                                    if (!isset($refColorMap[$referenceNo])) {
                                        $refColorMap[$referenceNo] = count($refColorMap) % 2 == 0 ? 'bg-white' : 'bg-blue-50';
                                    }
                                    
                                    $rowColor = $refColorMap[$referenceNo];
                                @endphp

                                <tr class="hover:bg-gray-50 {{ $rowColor }}">
                                    <td class="border px-2 py-2 text-xs font-mono truncate">
                                        <span class="bg-gray-100 px-2 py-1 rounded text-[10px]">
                                            {{ $referenceNo }}
                                        </span>
                                    </td>
                                    <!-- SKU -->
                                    <td class="border px-2 py-2 font-mono text-xs truncate">
                                        {{ $item->sku ?? 'N/A' }}
                                    </td>

                                    <!-- Description / Project -->
                                    <td class="border px-2 py-2">
                                        <p class="font-medium text-gray-900 text-sm leading-tight line-clamp-2">
                                            {{ $item->description }}
                                        </p>
                                        <p class="text-[11px] text-gray-500 truncate">
                                            {{ $item->ppmp->project_title ?? '' }}
                                        </p>
                                    </td>

                                    <!-- Purchaser -->
                                    <td class="border px-2 py-2 text-sm truncate">
                                        <p class="font-medium text-gray-900 text-sm leading-tight line-clamp-2">
                                        {{ $item->ppmp->requester->first_name ?? '—' }} {{ $item->ppmp->requester->last_name ?? '—' }}
                                        </p>
                                        <p class="text-[11px] text-gray-500 truncate">
                                            {{ $item->ppmp->requester->implementingUnit->name ?? '—' }}
                                        </p>
                                    </td>

                                    <!-- Supplier -->
                                    <td class="border px-2 py-2">
                                        <span class="px-2 py-0.5 text-[11px] rounded bg-blue-100 text-blue-800 truncate block">
                                            {{ $item->supplier->first_name ?? 'Unknown' }}
                                        </span>
                                    </td>

                                    <!-- Qty -->
                                    <td class="border px-2 py-2 text-center font-semibold text-sm">
                                        {{ $item->qty }}
                                    </td>

                                    <!-- Unit -->
                                    <td class="border px-2 py-2 text-center uppercase text-[11px]">
                                        {{ $item->unit }}
                                    </td>

                                    <!-- Status -->
                                    <td class="border px-2 py-2 text-center">
                                        @if($item->status === 'received')
                                            <span class="inline-block px-2 py-0.5 text-[10px] rounded-full bg-green-100 text-green-800">
                                                Received
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 text-[10px] rounded-full bg-red-100 text-red-800">
                                                Not Received
                                            </span>
                                        @endif
                                    </td>

                                    <!-- TIMESTAMP - NEW (like your example) -->
                                    <td class="border px-2 py-2 text-xs text-gray-500 text-center">
                                        {{ $item->created_at->diffInHours() < 24 
                                            ? $item->created_at->diffForHumans() 
                                            : $item->created_at->format('n/j/Y, g:i A') }}
                                    </td>


                                    <!-- Actions -->
                                    <td class="border px-2 py-2 text-center">
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open"
                                                    class="h-7 w-7 flex items-center justify-center rounded hover:bg-gray-200">
                                                ⋮
                                            </button>

                                            <div x-show="open"
                                                @click.away="open = false"
                                                x-transition
                                                class="absolute right-0 mt-1 w-36 bg-white border rounded shadow z-50 text-left">
                                                <button wire:click="markReceived({{ $item->id }})"
                                                        class="block w-full px-3 py-2 text-xs hover:bg-gray-100">
                                                    Mark Received
                                                </button>
                                                <button wire:click="viewItem({{ $item->id }})"
                                                        class="block w-full px-3 py-2 text-xs hover:bg-gray-100">
                                                    View
                                                </button>
                                                <button wire:click="removeItem({{ $item->id }})"
                                                    wire:confirm="Are you sure you want to delete this item? This cannot be undone."
                                                        class="block w-full px-3 py-2 text-xs text-red-600 hover:bg-red-50">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-10 text-center text-gray-500">
                                        No awarded items found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-4 py-2">
                        {{ $awardedItems->links() }}
                    </div>
                </div>
            </div>

         

      </main>
    </div>
</div>
     