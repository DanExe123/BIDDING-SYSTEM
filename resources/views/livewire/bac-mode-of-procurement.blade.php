<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')

    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
        <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Mode of Procurement</h1>
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
                    <h2 class="text-lg font-semibold text-white">Mode of procurement</h2>
                </div>
                <!-- Alpine component wrapper -->
                <div x-data="{
                    showModal: false,
                    items: [
                        { id: 1, qty: 10, unit: 'packs', description: 'Bond paper (A4, 70gsm, 500 sheets)', unitCost: 5, amount: 50 },
                        { id: 2, qty: 5, unit: 'boxes', description: 'Ballpoint pens (blue, 50 pcs/box)', unitCost: 5, amount: 25 },
                        { id: 3, qty: 5, unit: 'boxes', description: 'Staple wires (No. 35)', unitCost: 5, amount: 25 },
                        { id: 4, qty: 5, unit: 'units', description: 'Staplers (standard size)', unitCost: 3, amount: 15 },
                        { id: 5, qty: 10, unit: 'rolls', description: 'Masking tape (1 inch x 50 meters)', unitCost: 2, amount: 20 },
                        { id: 6, qty: 10, unit: 'pieces', description: 'Folders (long, with tab)', unitCost: 5, amount: 50 },
                        { id: 7, qty: 10, unit: 'pieces', description: 'Expanding envelopes (long, w/ garter)', unitCost: 5, amount: 50 }
                    ]
                }" class="relative">


                    <!-- Request Table -->
                    <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                        <!-- Table Header -->
                        <div
                            class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
                            <span class="w-1/3">Request by:</span>
                            <span class="w-1/3 text-center">Purpose:</span>
                            <span class="w-1/3 text-right">Status:</span>
                        </div>

                        <!-- Table Row 1 -->
                        <div class="flex justify-between px-4 py-2 text-sm border-b border-gray-200">
                            <span class="w-1/3">Purchaser Name</span>
                            <button @click="showModal = true"
                                class="w-1/3 text-center text-blue-600 hover:underline">School Supplies for
                                Students</button>
                            <span class="w-1/3 text-right">Pending</span>
                        </div>

                        <!-- Table Row 2 -->
                        <div class="flex justify-between px-4 py-2 text-sm">
                            <span class="w-1/3">Purchaser 2 Name</span>
                            <button @click="showModal = true"
                                class="w-1/3 text-center text-blue-600 hover:underline">For Security</button>
                            <span class="w-1/3 text-right">Approved</span>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" x-transition
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                        <div class="bg-gray-100 ml-30 w-[90%] md:w-[800px] rounded-md shadow-lg overflow-hidden"
                            @click.away="showModal = false">
                            <!-- Header -->
                            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300">
                                <div>
                                    <p class="text-sm"><strong>Requested by:</strong> Dela Cruz, Juan</p>
                                </div>
                                <div>
                                    <p class="text-sm"><strong>Role/Position:</strong> Purchaser</p>
                                </div>

                                <button @click="showModal = false" class="text-black text-xl font-bold">&times;</button>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto px-6 py-4">
                                <table class="min-w-full bg-white text-sm text-left border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="border px-2 py-1">ITEM NUMBER</th>
                                            <th class="border px-2 py-1">QTY</th>
                                            <th class="border px-2 py-1">UNIT</th>
                                            <th class="border px-2 py-1">ITEMS</th>
                                            <th class="border px-2 py-1">ESTIMATED UNIT COST</th>
                                            <th class="border px-2 py-1">ESTIMATED AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="item in items" :key="item.id">
                                            <tr>
                                                <td class="border px-2 py-1" x-text="item.id"></td>
                                                <td class="border px-2 py-1" x-text="item.qty"></td>
                                                <td class="border px-2 py-1" x-text="item.unit"></td>
                                                <td class="border px-2 py-1" x-text="item.description"></td>
                                                <td class="border px-2 py-1" x-text="item.unitCost"></td>
                                                <td class="border px-2 py-1" x-text="item.amount"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Purpose -->
                            <div class="px-6 py-4">
                                <p class="font-semibold">Purpose:</p>
                                <p class="text-gray-700">School Supplies for Students</p>
                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-4 flex justify-between items-center border-t border-gray-300">
                                <button
                                    class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">More
                                    details</button>
                                <div class="space-x-2">
                                    <button
                                        class="px-4 py-2 text-sm bg-green-200 text-green-800 rounded hover:bg-green-300">Approve</button>
                                    <button
                                        class="px-4 py-2 text-sm bg-red-300 text-red-800 rounded hover:bg-red-400">Reject</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>


        </main>
    </div>
</div>
