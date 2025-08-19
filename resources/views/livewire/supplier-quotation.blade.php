<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')

    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
        <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Quotation</h1>
            <div>
                <button class="text-gray-500 hover:text-black">
                    <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
                </button>
            </div>
        </header>

        <!-- Page content -->
        <main class="p-6 space-y-6 flex-1" x-cloak>

            <!-- Request for Approval Box -->
            <div class="bg-white rounded-md shadow-md border border-gray-300" x-data="{ tab: 'rfq' }" x-cloak>
                <!-- Dynamic Title -->
                <div class="bg-[#062B4A] text-center py-2 rounded-t-md">
                    <h2 class="text-lg font-semibold text-white">
                        <span x-show="tab === 'rfq'">Request for Quotation</span>
                        <span x-show="tab === 'supplier'">Supplier Quotation Submissions</span>
                        <span x-show="tab === 'abstract'">Abstract of Quotation (Evaluation)</span>
                        <span x-show="tab === 'recommendation'">Recommendation for Award</span>
                    </h2>
                </div>

                <div class="p-6">
                    <!-- Tabs -->
                    <div class="flex space-x-4 border-b border-gray-300 mb-4">
                        <button @click="tab = 'rfq'"
                            :class="tab === 'rfq' ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black'"
                            class="px-4 py-2 rounded-t-md font-medium">
                            Request for Quotation
                        </button>
                        <button @click="tab = 'supplier'"
                            :class="tab === 'supplier' ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black'"
                            class="px-4 py-2 rounded-t-md font-medium">
                            Supplier Quotation Submissions
                        </button>
                        <button @click="tab = 'abstract'"
                            :class="tab === 'abstract' ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black'"
                            class="px-4 py-2 rounded-t-md font-medium">
                            Abstract of Quotation (Evaluation)
                        </button>
                        <button @click="tab = 'recommendation'"
                            :class="tab === 'recommendation' ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black'"
                            class="px-4 py-2 rounded-t-md font-medium">
                            Recommendation for Award
                        </button>
                    </div>


                    <!-- Tab Content -->
                    <div class="mt-4">
                        <!-- Request for Quotation -->
                        <div x-show="tab === 'rfq'">
                            <!-- Request Table -->
                            <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                                <div
                                    class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
                                    <span class="w-1/3">Request by:</span>
                                    <span class="w-1/3 text-center">Purpose:</span>
                                    <span class="w-1/3 text-right">Status:</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm border-b border-gray-200">
                                    <span class="w-1/3">Purchaser Name</span>
                                    <button @click="showModal = true"
                                        class="w-1/3 text-center text-blue-600 hover:underline">
                                        School Supplies for Students
                                    </button>
                                    <span class="w-1/3 text-right">Pending</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm">
                                    <span class="w-1/3">Purchaser 2 Name</span>
                                    <button @click="showModal = true"
                                        class="w-1/3 text-center text-blue-600 hover:underline">
                                        For Security
                                    </button>
                                    <span class="w-1/3 text-right">Approved</span>
                                </div>
                            </div>
                        </div>

                        <!-- Supplier Quotation Submissions -->
                        <div x-show="tab === 'supplier'">
                            <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                                <div
                                    class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
                                    <span class="w-1/3">Supplier:</span>
                                    <span class="w-1/3 text-center">Quotation:</span>
                                    <span class="w-1/3 text-right">Status:</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm border-b border-gray-200">
                                    <span class="w-1/3">ABC Supplies</span>
                                    <span class="w-1/3 text-center">₱10,000</span>
                                    <span class="w-1/3 text-right">Submitted</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm">
                                    <span class="w-1/3">XYZ Traders</span>
                                    <span class="w-1/3 text-center">₱9,500</span>
                                    <span class="w-1/3 text-right">Submitted</span>
                                </div>
                            </div>
                        </div>

                        <!-- Abstract of Quotation -->
                        <div x-show="tab === 'abstract'">
                            <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                                <div
                                    class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
                                    <span class="w-1/3">Supplier:</span>
                                    <span class="w-1/3 text-center">Total Price:</span>
                                    <span class="w-1/3 text-right">Remarks:</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm border-b border-gray-200">
                                    <span class="w-1/3">ABC Supplies</span>
                                    <span class="w-1/3 text-center">₱10,000</span>
                                    <span class="w-1/3 text-right">Higher</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm">
                                    <span class="w-1/3">XYZ Traders</span>
                                    <span class="w-1/3 text-center">₱9,500</span>
                                    <span class="w-1/3 text-right">Lowest Bid</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recommendation for Award -->
                        <div x-show="tab === 'recommendation'">
                            <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                                <div
                                    class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
                                    <span class="w-1/2">Recommended Supplier:</span>
                                    <span class="w-1/2 text-right">Reason:</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm">
                                    <span class="w-1/2">XYZ Traders</span>
                                    <span class="w-1/2 text-right">Lowest price and compliant</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


        </main>
    </div>
</div>
