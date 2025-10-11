<div class="flex min-h-screen" x-cloak>
    <!-- Sidebar -->
    @include('partials.user-sidebar')

    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-white min-h-screen">
        <!-- Topbar -->
        <header class="bg-white h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Dashboard</h1>
            <!-- Right Section -->
            <div class="flex items-center gap-4">
                <!-- Search Bar -->
                <div class="relative">
                    <input type="text" placeholder="Search..."
                        class="pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#062B4A]" />
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 mt-2.5">
                        <x-phosphor.icons::regular.magnifying-glass class="w-5 h-5" />
                    </span>
                </div>

                <!-- notification bell -->
            @include('partials.notif')
             
        </header>

                <!-- Page content -->
                <main class="p-6 space-y-6 flex-1">
                   <!-- Summary Cards -->
                    <h2 class="text-start text-lg font-bold text-blue-900 mb-4">Welcome SuperAdmin!!</h2>
                   <div class="flex flex-wrap justify-center items-center gap-6 mt-10">
                <!-- Admin -->
                <div class="flex flex-col justify-center items-center bg-white border-l-4 shadow-lg border-l-blue-400 p-6 rounded-2xl w-60 h-60 hover:scale-105 transition-transform duration-300">
                    <x-phosphor.icons::regular.user class="w-10 h-10 text-blue-500 mb-3" />
                    <div class="text-3xl font-extrabold text-blue-700">{{ $adminCount }}</div>
                    <p class="text-sm font-semibold text-blue-500 mt-1">Bac Secretary</p>
                </div>

                <!-- Purchaser -->
                <div class="flex flex-col justify-center items-center bg-white border-l-4 border-green-400 p-6 rounded-2xl shadow-lg w-60 h-60 hover:scale-105 transition-transform duration-300">
                    <x-phosphor.icons::regular.shopping-cart class="w-10 h-10 text-green-500 mb-3" />
                    <div class="text-3xl font-extrabold text-green-700">{{ $purchaserCount }}</div>
                    <p class="text-sm font-semibold text-green-500 mt-1">Purchaser</p>
                </div>

                <!-- Supplier -->
                <div class="flex flex-col justify-center items-center bg-white border-l-4 border-yellow-400 p-6 rounded-2xl shadow-lg w-60 h-60 hover:scale-105 transition-transform duration-300">
                    <x-phosphor.icons::regular.package class="w-10 h-10 text-yellow-500 mb-3" />
                    <div class="text-3xl font-extrabold text-yellow-700">{{ $supplierCount }}</div>
                    <p class="text-sm font-semibold text-yellow-500 mt-1">Supplier</p>
                </div>
            </div>



                    <!-- Announcement Box -->
                    <div class="flex justify-between items-center">
                        <button
                            class="bg-gray-100 text-black text-sm font-medium rounded-full px-3 py-1 flex items-center space-x-1 hover:scale-105 transition">
                            <span>Sort</span>
                            <x-phosphor.icons::bold.caret-down class="w-4 h-4 text-black" />
                        </button>

                        <button
                            class="bg-gray-100 text-black text-sm font-medium rounded-full px-3 py-1 flex items-center space-x-1 hover:scale-105 transition p-5">
                            View
                        </button>
                    </div>

                    <div class="bg-gray-200 p-4 rounded-md shadow-md relative h-[400px] overflow-auto">
                        <h2 class="text-start text-lg font-bold text-black mb-4">Announcement</h2>

                        <table class="w-full text-sm text-left text-gray-700 border-collapse">
                            <thead class="bg-gray-300 text-gray-900 font-semibold">
                                <tr>
                                    <th class="px-4 py-2 border-b">#</th>
                                    <th class="px-4 py-2 border-b">Title</th>
                                    <th class="px-4 py-2 border-b">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300">
                                <tr>
                                    <td class="px-4 py-2">1</td>
                                    <td class="px-4 py-2">System Maintenance</td>
                                    <td class="px-4 py-2">2025-08-20</td>

                                </tr>
                                <tr>
                                    <td class="px-4 py-2">2</td>
                                    <td class="px-4 py-2">Holiday Notice</td>
                                    <td class="px-4 py-2">2025-08-25</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                </main>
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white border-l-4 border-yellow-300 p-6 rounded-md text-center shadow">
                    <div class="text-3xl font-bold">{{ $adminCount }}</div>
                    <div class="text-sm mt-2">Admin Account</div>
                </div>
                <div class="bg-white border-l-4 border-blue-400 p-6 rounded-md text-center shadow">
                    <div class="text-3xl font-bold">{{ $purchaserCount }}</div>
                    <div class="text-sm mt-2">Purchaser Account</div>
                </div>
                <div class="bg-white border-l-4 border-yellow-300 p-6 rounded-md text-center shadow">
                    <div class="text-3xl font-bold">{{ $supplierCount }}</div>
                    <div class="text-sm mt-2">Supplier Account</div>
                </div>
                </div>
            </main>
            </div>
</div>



