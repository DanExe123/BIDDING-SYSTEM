<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')


    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
        <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">
               Supplier Category
            </h1>

            <div>
                <button class="text-gray-500 hover:text-black">
                    <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
                </button>
            </div>
        </header>

        @if (session()->has('message'))
            <div 
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition
                class="absolute top-4 right-4 z-[9999] flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
                role="alert"
            >
                <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z" />
                    </svg>
                    <span class="sr-only">{{ session('message') }}</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ session('message') }}</div>
                <button type="button"
                    @click="show = false"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    aria-label="Close">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Page content -->
        <main class="p-6 space-y-6 flex-1">

            <!-- Add Button -->
            <div class="flex justify-end">
                <button
                    wire:click="openModal"
                    class="border border-cyan-700 text-cyan-900 hover:bg-cyan-100
                                px-4 py-1 rounded text-sm font-semibold">
                    + Add Category
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-300">
                    <thead class="bg-[#002b4a] text-cyan-900">
                        <tr>
                            <th class="text-left px-4 py-2 border text-white">
                                Category Name
                            </th>
                            <th class="text-left px-4 py-2 border text-white">
                                Project Type
                            </th>
                            <th class="text-center px-4 py-2 border text-white">
                                Suppliers (Number of supplier)
                            </th>
                            <th class="text-center px-4 py-2 border text-white">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($categories as $category)
                            <tr class="bg-gray-50 border-b hover:bg-gray-100 transition">
                                <td class="px-4 py-2 border font-medium text-gray-800">
                                    {{ $category->name }}
                                </td>
                                <td class="px-4 py-2 border text-gray-800">
                                    {{ $category->project_type ?? '—' }}
                                </td>
                                <td class="px-4 py-2 border text-center text-gray-800">
                                    {{ $category->users_count }}
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        
                                        <!-- 3-dot button -->
                                        <button @click="open = !open"
                                            class="p-2 rounded-full hover:bg-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-5 h-5 text-gray-600"
                                                fill="currentColor"
                                                viewBox="0 0 16 16">
                                                <path d="M9 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm0 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm0 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                            </svg>
                                        </button>

                                        <!-- Dropdown -->
                                        <div
                                            x-show="open"
                                            @click.away="open = false"
                                            x-transition
                                            class="absolute right-0 z-20 mt-2 w-36 bg-white border border-gray-200 rounded-md shadow-lg"
                                        >
                                            <div class="py-1 text-xs flex flex-col">
                                                <button
                                                    wire:click="edit({{ $category->id }})"
                                                    class="text-left px-3 py-2 hover:bg-blue-50 text-blue-700">
                                                    Edit
                                                </button>

                                                <button
                                                    wire:click="delete({{ $category->id }})"
                                                    onclick="confirm('Delete this supplier category?') || event.stopImmediatePropagation()"
                                                    class="text-left px-3 py-2 hover:bg-red-50 text-red-700">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-gray-500 border">
                                    No supplier categories found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
                    <!-- MODAL -->
            @if ($isOpen)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6">

                        <h2 class="text-lg font-semibold mb-4">
                            {{ $categoryId ? 'Edit Supplier Category' : 'Add Supplier Category' }}
                        </h2>

                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Category Name
                            </label>
                            <input
                                type="text"
                                wire:model.defer="name"
                                class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#062B4A] focus:outline-none"
                                placeholder="Enter category name">

                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-1">
                                Project Type
                            </label>
                            <input
                                type="text"
                                wire:model.defer="project_type"
                                class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#062B4A] focus:outline-none"
                                placeholder="e.g. Construction, Goods, Consulting"
                            >

                            @error('project_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-3 mt-6">
                            <button
                                wire:click="closeModal"
                                class="px-4 py-2 border rounded hover:bg-gray-100">
                                Cancel
                            </button>

                            @if ($categoryId)
                                <button
                                    wire:click="update"
                                    class="px-4 py-2 bg-[#062B4A] text-white rounded">
                                    Update
                                </button>
                            @else
                                <button
                                    wire:click="store"
                                    class="px-4 py-2 bg-[#062B4A] text-white rounded">
                                    Save
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </main>
    </div>
</div>
