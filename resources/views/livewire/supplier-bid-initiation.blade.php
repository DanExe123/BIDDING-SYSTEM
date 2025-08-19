            <div x-data="{ showModal: false }" class="relative">
                <!-- Request Table -->
                <div class="border border-gray-300 m-4 rounded-md overflow-hidden bg-white">
                    <table class="min-w-full text-sm border-collapse">
                        <thead class="bg-blue-200 border-b border-gray-300">
                            <tr>
                                <th class="px-4 py-2 w-1/3 text-left font-semibold">Reference No</th>
                                <th class="px-4 py-2 w-1/3 text-center font-semibold">Purpose:</th>
                                <th class="px-4 py-2 w-1/3 text-right font-semibold">Status:</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach($bids as $bid)
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-2">{{ $bid->bid_reference}}</td>
                                    <td class="px-4 py-2 text-center">
                                        <button wire:click="selectBid({{ $bid->id }})" @click="showModal = true"
                                                class="text-blue-600 hover:underline">
                                            {{ $bid->bid_title }}
                                        </button>
                                    </td>
                                    <td class="px-4 py-2 text-right">{{ ucfirst($bid->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div x-show="showModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-gray-100 ml-30 w-[90%] md:w-[800px] rounded-md shadow-lg overflow-hidden" @click.away="showModal = false">
                        
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                            <div>
                                <p class="text-base font-semibold">Bid Invitaion</p>
                                <p class="text-sm text-gray-600">
                                    Reference: <span class="font-medium">{{ $selectedBid?->bid_reference}} ({{ $selectedBid?->bid_title }})</span>
                    
                                </p>
                            </div>
                            <button @click="showModal = false" class="text-gray-600 text-xl font-bold hover:text-black">&times;</button>
                        </div>

                        <div class="px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                            <!-- Row 1 -->
                            <div class="grid grid-cols-2 gap-4 mb-2">
                                <div>
                                    <label class="text-sm text-gray-600">Bid Title</label>
                                    <input type="text" readonly 
                                        class="w-full border-none bg-transparent px-3 py-2"
                                        value="{{ $selectedBid?->bid_title ?? 'N/A' }}">
                                </div>

                                <div>
                                    <label class="text-sm text-gray-600">Bid Reference Number</label>
                                    <input type="text" readonly 
                                        class="w-full border-none bg-transparent px-3 py-2"
                                        value="{{ $selectedBid?->bid_reference ?? 'N/A' }}">
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="grid grid-cols-2 gap-4 mb-2">
                                <div>
                                    <label class="text-sm text-gray-600">Approved Budget</label>
                                    <input type="text" readonly 
                                        class="w-full border-none bg-transparent px-3 py-2"
                                        value="₱{{ number_format($selectedBid?->approved_budget ?? 0, 2) }}">
                                </div>

                                <div>
                                    <label class="text-sm text-gray-600">Source of Funds</label>
                                    <input type="text" readonly 
                                        class="w-full border-none bg-transparent px-3 py-2"
                                        value="{{ $selectedBid?->ppmp?->implementing_unit ?? 'N/A' }}">
                                </div>
                            </div>

                            <!-- Row 3 -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm text-gray-600">Bid Opening Date</label>
                                    <input type="text" readonly 
                                        class="w-full border-none bg-transparent px-3 py-2"
                                        value="{{ $selectedBid?->pre_bid_date ?? 'N/A' }}">
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600">Bid Submission Deadline</label>
                                    <input type="text" readonly 
                                        class="w-full border-none bg-transparent px-3 py-2"
                                        value="{{ $selectedBid?->submission_deadline ?? 'N/A' }}">
                                </div>
                            </div>
                        </div>


                        <!-- Modal Body: List PPMP Items -->
                        <div class="p-6 space-y-4">
                            <p class="text-sm font-semibold text-gray-700">Items Needed:</p>
                            <table class="min-w-full text-sm border-collapse border border-gray-300">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="px-3 py-1 border">Description</th>
                                        <th class="px-3 py-1 border">Qty</th>
                                        <th class="px-3 py-1 border">Unit</th>
                                        <th class="px-3 py-1 border">Unit Cost</th>
                                        <th class="px-3 py-1 border">Total Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedBid?->ppmp?->items ?? [] as $item)
                                        <tr class="border-b border-gray-200">
                                            <td class="px-3 py-1 border">{{ $item->description }}</td>
                                            <td class="px-3 py-1 border">{{ $item->qty }}</td>
                                            <td class="px-3 py-1 border">{{ $item->unit }}</td>
                                            <td class="px-3 py-1 border">₱{{ number_format($item->unit_cost, 2) }}</td>
                                            <td class="px-3 py-1 border">₱{{ number_format($item->total_cost, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="flex justify-end space-x-2">
                                <button @click="showModal = false" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


      