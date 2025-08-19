          <div x-data="{ showEvaluation: false }">

            <!-- Default Table View -->
            <template x-if="!showEvaluation">
                <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                    <table class="min-w-full text-sm text-left border-collapse">
                        <thead class="bg-blue-200 border-bW border-gray-300">
                            <tr>
                                <th class="px-4 py-2 w-1/3">Request by:</th>
                                <th class="px-4 py-2 w-1/3 text-center">Purpose:</th>
                                <th class="px-4 py-2 w-1/3 text-right">Response</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach($bids as $bid)
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-2">{{ $bid->bid_reference }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <button class="text-blue-600 hover:underline"
                                                wire:click="selectBid({{ $bid->id }})"
                                                @click="showEvaluation = true">
                                            {{ $bid->bid_title }}
                                        </button>
                                    </td>
                                    <td class="px-4 py-2 text-right">0</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </template>

            <!-- Evaluation View -->
            <template x-if="showEvaluation">
                <div class="bg-white rounded-md border border-gray-300 shadow-sm p-6 space-y-4">
                    @if($selectedBid)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <h2 class="text-lg font-semibold">{{ $selectedBid->bid_reference }}</h2>
                                <p class="text-sm text-gray-600">{{ $selectedBid->bid_title }}</p>
                            </div>
                            <button @click="showEvaluation = false; $wire.goBack()" 
                                    class="text-sm text-blue-600 hover:underline">
                                ← Back
                            </button>
                        </div>

                        <div class="flex space-x-2">
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                Budget: ₱{{ $selectedBid->approved_budget }}
                            </span>
                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                Suppliers: {{ $selectedBid->suppliers->count() ?? 0 }}
                            </span>
                        </div>

                        <ul class="mt-2">
                            @forelse($selectedBid->suppliers as $supplier)
                                <li class="text-sm">{{ $supplier->name }}</li>
                            @empty
                                <li class="text-sm text-gray-500">No suppliers assigned</li>
                            @endforelse
                        </ul>
                    @endif
                </div>
            </template>
          </div>
 
