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
        <main class="p-6 space-y-6 flex-1" x-cloak>

            <!-- Request for Approval Box -->
            <div class="bg-white rounded-md shadow-md border border-gray-300" x-cloak>
                <div class="bg-[#062B4A] text-center py-2 rounded-t-md">
                    <h2 class="text-lg font-semibold text-white">Mode of procurement</h2>
                </div>
                <!-- Alpine component wrapper -->
                <div x-data="{ showModal: false }" x-on:close-modal.window="showModal = false" class="relative">
                    <!-- Request Table -->
                    <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                        <table wire:poll class="min-w-full text-sm">
                            <thead class="bg-blue-200 font-semibold border-b border-gray-300">
                                <tr>
                                    <th class="w-1/4 text-left px-4 py-2">Request by</th>
                                    <th class="w-1/4 text-center px-4 py-2">Purpose</th>
                                    <th class="w-1/4 text-center px-4 py-2">Mode of Procurement</th>
                                    <th class="w-1/8 text-center px-4 py-2">Status</th>
                                    <th class="w-1/8 text-center px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ppmps as $ppmp)
                                    <tr class="border-b border-gray-200">
                                        <td class="px-4 py-2">
                                            {{ $ppmp->requester->first_name }} {{ $ppmp->requester->last_name }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $ppmp->project_title }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $ppmp->mode_of_procurement ?? 'Not selected' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ ucfirst($ppmp->status) }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <button wire:click="showPpmp({{ $ppmp->id }})" 
                                                    x-on:click="showModal = true"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                                {{ $ppmp->mode_of_procurement ? 'View' : 'Select Mode' }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                        <div class="bg-gray-100 ml-30 w-[90%] md:w-[800px] rounded-md shadow-lg overflow-hidden" @click.away="showModal = false">
                            
                            <!-- Loading Spinner -->
                            <div wire:loading.flex wire:target="showPpmp" class="p-10 flex justify-center items-center">
                                <svg class="animate-spin h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span class="ml-2 text-gray-600">Loading...</span>
                            </div>

                            <!-- Modal Content -->
                            <div wire:loading.remove wire:target="showPpmp">
                                <!-- Header -->
                                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300">
                                    <div>
                                        @if($selectedPpmp)
                                            <p class="text-sm"><strong>Requested by:</strong> 
                                                {{ $selectedPpmp->requester->first_name }} {{ $selectedPpmp->requester->last_name }}
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm"><strong>Role/Position:</strong> Purchaser</p>
                                    </div>
                                    <button @click="showModal = false; @this.call('closeModal')" class="text-black text-xl font-bold">&times;</button>
                                </div>

                                <!-- Table -->
                                <div class="overflow-x-auto px-6 py-4">
                                    <table class="min-w-full bg-white text-sm text-left border border-gray-300">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">ITEM NUMBER</th>
                                                <th class="border px-2 py-1">ITEMS</th>
                                                <th class="border px-2 py-1">QTY</th>
                                                <th class="border px-2 py-1">UNIT</th>
                                                <th class="border px-2 py-1">ESTIMATED UNIT COST</th>
                                                <th class="border px-2 py-1">ESTIMATED AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($selectedPpmp)
                                                @foreach($selectedPpmp->items as $item)
                                                    <tr>
                                                        <td class="border px-2 py-1">{{ $item->id }}</td>
                                                        <td class="border px-2 py-1">{{ $item->description }}</td>
                                                        <td class="border px-2 py-1">{{ $item->qty }}</td>
                                                        <td class="border px-2 py-1">{{ $item->unit }}</td>
                                                        <td class="border px-2 py-1">{{ $item->unit_cost }}</td>
                                                        <td class="border px-2 py-1">{{ $item->total_cost }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Purpose -->
                                <div class="px-6 py-4">
                                    <p class="font-semibold">Purpose:</p>
                                    @if($selectedPpmp)
                                        <p class="text-gray-700">{{ $selectedPpmp->project_title }}</p>
                                    @endif
                                </div>

                                <!-- Footer -->
                                <div class="px-6 py-4 flex justify-between items-center border-t border-gray-300">
                                    <button class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">More details</button>

                                    @if($selectedPpmp)
                                        @if($selectedPpmp->mode_of_procurement)
                                            <!-- Show mode of procurement instead of buttons -->
                                            <span class="px-4 py-2 text-sm bg-blue-100 text-blue-800 rounded">
                                                {{ ucfirst($selectedPpmp->mode_of_procurement) }}
                                            </span>
                                        @else
                                            <!-- Show buttons only if mode not yet selected -->
                                            <div class="space-x-2">
                                                <button wire:click="movetoQoutation({{ $selectedPpmp->id }})" 
                                                        class="px-4 py-2 text-sm bg-green-200 text-green-800 rounded hover:bg-green-300">
                                                    Move for Quotation
                                                </button>
                                                <button wire:click="movetoBidding({{ $selectedPpmp->id }})" 
                                                        class="px-4 py-2 text-sm bg-blue-300 text-blue-800 rounded hover:bg-blue-400">
                                                    Move for Bidding
                                                </button>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>    

            </div>


        </main>
    </div>
</div>
