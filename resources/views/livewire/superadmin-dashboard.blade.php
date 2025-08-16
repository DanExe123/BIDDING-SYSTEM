<div class="flex min-h-screen">
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

                <div>
                    <button class="text-gray-500 hover:text-black">
                        <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
                    </button>
                </div>
        </header>

        <!-- Page content -->
        <main class="p-6 space-y-6 flex-1">
            <!-- Summary Cards -->
            <h2 class="text-start text-lg font-bold text-blue-900 mb-4">Welcome SuperAdmin!!</h2>
            <div class="flex gap-x-4">
                <!-- Admin -->
                <div class="bg-gray-100 p-3 rounded-md text-center shadow w-20 h-20">
                    <div class="text-xl font-bold">1</div>
                    <x-phosphor.icons::regular.user class="w-6 h-6 text-gray-700 mx-auto mt-2" />
                </div>

                <!-- Purchaser -->
                <div class="bg-gray-100 p-3 rounded-md text-center shadow w-20 h-20">
                    <div class="text-xl font-bold">2</div>
                    <x-phosphor.icons::regular.shopping-cart class="w-6 h-6 text-gray-700 mx-auto mt-2" />
                </div>

                <!-- Supplier -->
                <div class="bg-gray-100 p-3 rounded-md text-center shadow w-20 h-20">
                    <div class="text-xl font-bold">2</div>
                    <x-phosphor.icons::regular.package class="w-6 h-6 text-gray-700 mx-auto mt-2" />
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
    </div>
</div>
