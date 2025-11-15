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
        </header>
        
          <!-- Page content -->
          <main class="p-6 space-y-6 flex-1">    
        <div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">Create Announcement</h1>

     

            <form wire:submit.prevent="save" class="space-y-4">
                <!-- Title -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Title</label>
                    <input type="text" wire:model="title" class="w-full border border-gray-300 rounded-md p-2 mt-1">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Description</label>
                    <textarea wire:model="description" class="w-full border border-gray-300 rounded-md p-2 mt-1 h-24"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Date -->
                <div>
                    <label class="text-sm font-medium text-gray-700">Date</label>
                    <input type="date" wire:model="date" class="w-full border border-gray-300 rounded-md p-2 mt-1">
                    @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('superadmin-dashboard') }}" class="px-4 py-2 rounded-md bg-gray-300 hover:bg-gray-400 text-black">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white">
                        Save
                    </button>
                </div>
            </form>
        </div>
        </main>
    </div>
</div>

