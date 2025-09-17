<div x-data="{ open: false }" x-cloak>
    @if($ppmp->invitations->isNotEmpty())
        <button
            type="button"
            @click.prevent="$wire.view({{ $ppmp->invitations->last()->id }}); open = true"
            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
            View
        </button>
    @endif

    <!-- Teleport to body so you don't get multiple stacked modals per row -->
    <template x-teleport="body">
        <div
            x-show="open"
            x-transition.opacity
            class="fixed inset-0 z-[100] flex items-center justify-center"
            wire:ignore.self
            @keydown.escape.window="open = false">

            <!-- backdrop -->
            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

            <!-- dialog -->
            <div class="relative bg-white w-[90%] md:w-[800px] rounded-lg shadow p-6">
                <div class="flex justify-between items-center border-b pb-2 mb-4">
                    <h2 class="text-lg font-semibold">Invitation Details</h2>
                    <button class="text-gray-500 hover:text-black text-2xl" @click="open = false">&times;</button>
                </div>

                <!-- optional tiny inline loader, no extra modal -->
                <div wire:loading wire:target="view" class="text-sm text-gray-500">Loading…</div>

                <div wire:loading.remove wire:target="view">
                    @if($invitation)
                        <div class="mb-4 space-y-1">

                            <h2 class="text-lg font-semibold text-gray-800">
                                Reference: {{ $invitation->reference_no }}
                            </h2>
                            <p class="text-sm text-gray-600">
                                Project: {{ $invitation->ppmp->project_title }}
                            </p>

                            {{-- Show Bid Amount only if bidding --}}
                            @if($invitation->ppmp->mode_of_procurement === 'bidding')
                                <div class="inline-block text-sm bg-blue-100 text-gray-800 px-6 py-2 rounded-md mb-2">
                                    <strong>Budget:</strong> ₱{{ number_format($invitation->ppmp->abc, 2) }}
                                </div>
                            @endif

                            <div class="mt-2 mb-2 text-sm text-gray-700 flex space-x-8">
                                <p>
                                    <strong>Pre Date:</strong> 
                                    {{ \Carbon\Carbon::parse($invitation->pre_date)->format('F d, Y') }}
                                </p>
                                <p>-</p>
                                <p>
                                    <strong>Submission Deadline:</strong> 
                                    {{ \Carbon\Carbon::parse($invitation->submission_deadline)->format('F d, Y') }}
                                </p>
                            </div>
                        </div>


                        <h3 class="font-semibold mb-2">Invited Suppliers</h3>
                        <table class="w-full border text-sm">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="px-2 py-1 text-left">Supplier</th>
                                    <th class="px-2 py-1 text-left">Response</th>
                                    <th class="px-2 py-1 text-left">Responded At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                    @php
                                        $submission = $invitation->submissions->firstWhere('supplier_id', $supplier->id);
                                    @endphp

                                    <tr class="border-t">
                                        <td class="px-2 py-1">{{ $supplier->first_name }} {{ $supplier->last_name }}</td>

                                        {{-- Response / Submission Status --}}
                                        <td class="px-2 py-1">
                                            <span class="px-2 py-1 rounded-full text-sm
                                                @if($submission && $submission->status !== 'draft')
                                                    @switch($submission->status)
                                                        @case('submitted') bg-blue-100 text-blue-700 @break
                                                        @case('under_review') bg-yellow-100 text-yellow-700 @break
                                                        @case('awarded') bg-green-100 text-green-700 @break
                                                        @case('rejected') bg-red-100 text-red-700 @break
                                                        @default bg-gray-100 text-gray-700
                                                    @endswitch
                                                @else
                                                    @switch($supplier->pivot->response ?? 'pending')
                                                        @case('pending') bg-yellow-100 text-yellow-700 @break
                                                        @case('accepted') bg-green-100 text-green-700 @break
                                                        @case('declined') bg-red-100 text-red-700 @break
                                                        @default bg-gray-100 text-gray-700
                                                    @endswitch
                                                @endif">
                                                @if($submission && $submission->status !== 'draft')
                                                    {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                                @else
                                                    {{ ucfirst($supplier->pivot->response ?? 'Pending') }}
                                                @endif
                                            </span>
                                        </td>

                                        <td class="px-2 py-1">
                                            @if($submission && $submission->status !== 'draft')
                                                {{ $submission->submitted_at }}
                                            @else
                                                {{ $supplier->pivot->responded_at ?? '—' }}
                                            @endif
                                        </td>
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
    </template>
</div>
