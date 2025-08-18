<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')

    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
        <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Competitive Bidding</h1>
            <div>
                <button class="text-gray-500 hover:text-black">
                    <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
                </button>
            </div>
        </header>

        <!-- Page content -->
        <main class="p-6 space-y-6 flex-1" x-cloak>

            <!-- Bidding Process Box -->
            <div class="bg-white rounded-md shadow-md border border-gray-300" x-data="{ tab: 'invitation' }" x-cloak>
                <!-- Dynamic Title -->
                <div class="bg-[#062B4A] text-center py-2 rounded-t-md">
                    <h2 class="text-lg font-semibold text-white">
                        <span x-show="tab === 'invitation'">Bid Invitation</span>
                        <span x-show="tab === 'opening'">Bid Opening & Evaluation</span>
                        <span x-show="tab === 'post'">Post-Qualification</span>
                        <span x-show="tab === 'recommendation'">Recommendation for Award</span>
                    </h2>
                </div>

                <div class="p-6">
                    <!-- Tabs -->
                    <div class="flex space-x-4 border-b border-gray-300 mb-4 justify-center">
                        <button @click="tab = 'invitation'"
                            :class="tab === 'invitation' ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black'"
                            class="px-4 py-2 rounded-t-md font-medium">
                            Bid Invitation
                        </button>
                        <button @click="tab = 'opening'"
                            :class="tab === 'opening' ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black'"
                            class="px-4 py-2 rounded-t-md font-medium">
                            Bid Opening & Evaluation
                        </button>
                        <button @click="tab = 'post'"
                            :class="tab === 'post' ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black'"
                            class="px-4 py-2 rounded-t-md font-medium">
                            Post-Qualification
                        </button>
                        <button @click="tab = 'recommendation'"
                            :class="tab === 'recommendation' ? 'bg-[#EFE8A5] text-black' : 'hover:bg-[#EFE8A5] hover:text-black'"
                            class="px-4 py-2 rounded-t-md font-medium">
                            Recommendation for Award
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="mt-4">
                        <!-- Bid Invitation -->
                        <div x-show="tab === 'invitation'">
                            <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                                <div
                                    class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
                                    <span class="w-1/2">Project:</span>
                                    <span class="w-1/2 text-right">Date Posted:</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm">
                                    <span class="w-1/2">Construction of School Building</span>
                                    <span class="w-1/2 text-right">Aug 15, 2025</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bid Opening & Evaluation -->
                        <div x-show="tab === 'opening'">
                            <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                                <div
                                    class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
                                    <span class="w-1/3">Supplier:</span>
                                    <span class="w-1/3 text-center">Bid Amount:</span>
                                    <span class="w-1/3 text-right">Status:</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm border-b border-gray-200">
                                    <span class="w-1/3">ABC Constructions</span>
                                    <span class="w-1/3 text-center">₱1,200,000</span>
                                    <span class="w-1/3 text-right">Evaluated</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm">
                                    <span class="w-1/3">XYZ Builders</span>
                                    <span class="w-1/3 text-center">₱1,150,000</span>
                                    <span class="w-1/3 text-right">Evaluated</span>
                                </div>
                            </div>
                        </div>

                        <!-- Post-Qualification -->
                        <div x-show="tab === 'post'">
                            <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                                <div
                                    class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
                                    <span class="w-1/2">Supplier:</span>
                                    <span class="w-1/2 text-right">Qualification Status:</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm border-b border-gray-200">
                                    <span class="w-1/2">XYZ Builders</span>
                                    <span class="w-1/2 text-right">Compliant</span>
                                </div>
                                <div class="flex justify-between px-4 py-2 text-sm">
                                    <span class="w-1/2">ABC Constructions</span>
                                    <span class="w-1/2 text-right">Non-Compliant</span>
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
                                    <span class="w-1/2">XYZ Builders</span>
                                    <span class="w-1/2 text-right">Lowest bid & Compliant</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>

    </div>
</div>
