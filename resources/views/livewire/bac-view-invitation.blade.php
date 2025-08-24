<div x-data="{ open: false }" 
     x-on:open-view-invitation-modal.window="open = true" 
     x-on:close-view-invitation-modal.window="open = false">

    <!-- Trigger Button -->
    @if($ppmp->invitations->isNotEmpty())
        <button wire:click="view({{ $ppmp->invitations->last()->id }})"
                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
            View
        </button>
    @endif

    <!-- Modal -->
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-[90%] md:w-[800px] rounded-lg shadow p-6 relative" @click.away="open = false">
            
            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 class="text-lg font-semibold">Invitation Details</h2>
                <button class="text-gray-500 hover:text-black text-2xl" @click="open = false">&times;</button>
            </div>

            @if($invitation)
                <div class="mb-4">
                    <p><strong>Reference No:</strong> {{ $invitation->reference_no }}</p>
                    <p><strong>Title:</strong> {{ $invitation->title }}</p>
                    <p><strong>Project:</strong> {{ $invitation->ppmp->project_title }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($invitation->status) }}</p>
                </div>

                <!-- Suppliers Table -->
                <h3 class="font-semibold mb-2">Invited Suppliers</h3>
                <table class="w-full border text-sm">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-2 py-1 text-left">Supplier</th>
                            <th class="px-2 py-1 text-left">Category</th>
                            <th class="px-2 py-1 text-left">Response</th>
                            <th class="px-2 py-1 text-left">Responded At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                            <tr class="border-t">
                                <td class="px-2 py-1">{{ $supplier->first_name }} {{ $supplier->last_name }}</td>
                                <td class="px-2 py-1">{{ $supplier->supplierCategory->name ?? 'N/A' }}</td>
                                <td class="px-2 py-1">{{ $supplier->pivot->response ?? 'Pending' }}</td>
                                <td class="px-2 py-1">{{ $supplier->pivot->responded_at ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No invitation selected.</p>
            @endif
        </div>
    </div>
</div>
