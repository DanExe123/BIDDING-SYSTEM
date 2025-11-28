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

            <h2 class="text-start text-lg font-bold text-blue-900 mb-4">Welcome SuperAdmin!!</h2>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full mt-10">

                <!-- Admin -->
                <div class="flex flex-col justify-center items-center bg-white border-l-4 shadow-lg border-l-blue-400 p-4 rounded-2xl h-40 hover:scale-105 transition-transform duration-300">
                    <x-phosphor.icons::regular.user class="w-10 h-10 text-blue-500 mb-2" />
                    <div class="text-2xl font-extrabold text-blue-700">{{ $adminCount }}</div>
                    <p class="text-sm font-semibold text-blue-500 mt-1">Bac Secretary</p>
                </div>

                <!-- Purchaser -->
                <div class="flex flex-col justify-center items-center bg-white border-l-4 border-green-400 p-4 rounded-2xl shadow-lg h-40 hover:scale-105 transition-transform duration-300">
                    <x-phosphor.icons::regular.shopping-cart class="w-10 h-10 text-green-500 mb-2" />
                    <div class="text-2xl font-extrabold text-green-700">{{ $purchaserCount }}</div>
                    <p class="text-sm font-semibold text-green-500 mt-1">Purchaser</p>
                </div>

                <!-- Verified Supplier -->
                <div class="flex flex-col justify-center items-center bg-white border-l-4 border-yellow-400 p-4 rounded-2xl shadow-lg h-40 hover:scale-105 transition-transform duration-300">
                    <x-phosphor.icons::regular.package class="w-10 h-10 text-yellow-500 mb-2" />
                    <div class="text-2xl font-extrabold text-yellow-700">{{ $verifiedSupplierCount }}</div>
                    <p class="text-sm font-semibold text-yellow-500 mt-1">Verified Supplier</p>
                </div>

                <!-- Pending Supplier -->
                <div class="flex flex-col justify-center items-center bg-white border-l-4 border-orange-400 p-4 rounded-2xl shadow-lg h-40 hover:scale-105 transition-transform duration-300">
                    <x-phosphor.icons::regular.package class="w-10 h-10 text-orange-500 mb-2" />
                    <div class="text-2xl font-extrabold text-orange-700">{{ $pendingSupplierCount }}</div>
                    <p class="text-sm font-semibold text-orange-500 mt-1">Pending Supplier</p>
                </div>

            </div>

            <!-- Container -->
            <div x-data="{ openModal: false }">

                <!-- Buttons -->
                <div class="flex justify-between items-center py-4">
                    <button
                        class="bg-gray-100 text-black text-sm font-medium rounded-md px-3 py-3 flex items-center space-x-1 hover:scale-105 transition">
                        <span>Sort</span>
                        <x-phosphor.icons::bold.caret-down class="w-4 h-4 text-black" />
                    </button>

                    <a wire:navigate href="{{ route('announcement-page') }}"
                        class="bg-green-600 text-white text-sm font-medium rounded-md px-3 py-3 flex items-center space-x-1 hover:scale-105 transition p-5">
                        Create Announcement
                    </a>
                </div>

              <!-- Announcement Table -->
                <div class="bg-gray-200 p-4 rounded-md shadow-md relative h-[400px] overflow-auto">
                    <h2 class="text-start text-lg font-bold text-black mb-4">Announcement</h2>

                    <table class="w-full text-sm text-left text-gray-700 border-collapse">
                        <thead class="bg-gray-300 text-gray-900 font-semibold">
                            <tr>
                                <th class="px-4 py-2 border-b">#</th>
                                <th class="px-4 py-2 border-b">Title</th>
                                <th class="px-4 py-2 border-b">Description</th>
                                <th class="px-4 py-2 border-b">Date</th>
                                <th class="px-4 py-2 border-b text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300">
                            @forelse ($announcements as $index => $announcement)
                                <tr>
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $announcement->title }}</td>
                                    <td class="px-4 py-2">{{ $announcement->description }}</td>
                                    <td class="px-4 py-2">{{ $announcement->date }}</td>

                                    <td class="px-4 py-2 text-center flex gap-3 justify-center">

                                        <!-- Edit Icon -->
                                        <!-- Example: navigate to edit announcement with ID 5 -->
                                        <a wire:navigate href="{{ route('announcement-page', ['editId' => $announcement->id]) }}" 
                                            class="text-blue-600 hover:text-blue-800">
                                            <x-phosphor.icons::regular.pencil class="w-5 h-5" />
                                        </a>
                                        
                                    
                                  <!-- Delete Icon -->
                                  <a href="#" 
                                  x-on:click.prevent="
                                       Swal.fire({
                                           title: 'Are you sure?',
                                           text: 'Are you sure you want to delete this row?',
                                           icon: 'warning',
                                           showCancelButton: true,
                                           confirmButtonText: 'Yes, delete it!'
                                       }).then((result) => {
                                           if (result.isConfirmed) {
                                               $wire.deleteAnnouncement({{ $announcement->id }});
                                           }
                                       });
                                  "
                                  class="text-red-600 hover:text-red-500"
                                  title="Delete Announcement"
                               >
                                   <x-phosphor.icons::regular.trash class="w-5 h-5" />
                               </a>
                               
                              

                                    
                                    </td>
                                    
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">No announcements found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($toast)
                <x-toast :type="$toast['type']" :message="$toast['message']" />
            @endif

            </div>

        </main>
            
    </div>
</div>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
    
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure you want to delete this row?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Call Livewire method directly
                        Livewire.emit('deleteAnnouncement', id);
                    }
                });
            });
        });
    });
    </script>
    



