<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')

    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen" x-cloak>
        <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Procurement Planning</h1>
           
         @livewire('purchaser-notification-bell')   
        </header>

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="absolute top-4 right-4 z-[9999] flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800"
                role="alert">
                <div
                    class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.147 15.085a7.159 7.159 0 0 1-6.189 3.307A6.713 6.713 0 0 1 3.1 15.444c-2.679-4.513.287-8.737.888-9.548A4.373 4.373 0 0 0 5 1.608c1.287.953 6.445 3.218 5.537 10.5 1.5-1.122 2.706-3.01 2.853-6.14 1.433 1.049 3.993 5.395 1.757 9.117Z" />
                    </svg>
                    <span class="sr-only">{{ session('message') }}</span>
                </div>
                <div class="ms-3 text-sm font-normal">{{ session('message') }}</div>
                <button type="button" @click="show = false"
                    class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    aria-label="Close">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Page content -->
        <main class="p-6 space-y-6 flex-1">
            <div x-data="{ view: 'create' }" class="p-4">
                <!-- Toggle Buttons -->
                <div class="flex justify-center gap-10 py-2">
                    <button @click="view = 'create'"
                        :class="view === 'create' ? 'bg-[#062B4A] text-white' : 'text-cyan-900'"
                        class="border border-cyan-700 hover:bg-[#062B4A] px-10 py-1 rounded-full text-sm font-semibold transition-colors">
                        Create PR
                    </button>

                    <button @click="view = 'list'"
                        :class="view === 'list' ? 'bg-[#062B4A] text-white' : 'text-cyan-900'"
                        class="border border-cyan-700 hover:bg-[#062B4A] px-12 py-1 rounded-full text-sm font-semibold transition-colors">
                        List
                    </button>
                </div>

                <div x-show="view === 'create'" x-data>
                    <form wire:submit.prevent="save" enctype="multipart/form-data" class="mt-6">
                        <div class="flex justify-center items-center w-full">
                            <div class="bg-white rounded-md shadow-md border border-gray-300 w-full">
                                <div class="rounded-md shadow-lg p-4">
                                    <h2 class="text-gray-800 py-2 font-bold">PR</h2>

                                    <div class="px-6 py-4 space-y-4 text-sm text-gray-700">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block font-medium mb-1">Project Title*</label>
                                                <input type="text" wire:model.defer="project_title"
                                                    placeholder="Project Title"
                                                    class="w-full border rounded px-3 py-2" />
                                                @error('project_title')
                                                    <span class="text-red-500">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block font-medium mb-1">Project Type*</label>
                                                <input type="text" wire:model.defer="project_type"
                                                    placeholder="Education, Infrastructure..."
                                                    class="w-full border rounded px-3 py-2" />
                                                @error('project_type')
                                                    <span class="text-red-500">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div wire:poll>
                                                <label class="block font-medium mb-1">ABC (Approved Budget)*</label>
                                                <input type="number" wire:model="abc" readonly
                                                    class="w-full border rounded px-3 py-2 " />
                                                @error('abc')
                                                    <span class="text-red-500">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label class="block font-medium mb-1">Implementing Unit*</label>
                                                <input type="text" wire:model.defer="implementing_unit"
                                                    placeholder="Office/Department"
                                                    class="w-full border rounded px-3 py-2" />
                                                @error('implementing_unit')
                                                    <span class="text-red-500">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Procurement Items --}}
                                        <div wire:poll class="mt-6">
                                            <label class="block font-medium mb-2">Procurement Items</label>
                                            <table class="w-full table-auto border border-gray-300 text-sm">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="border p-2">Description</th>
                                                        <th class="border p-2">Qty</th>
                                                        <th class="border p-2">Unit</th>
                                                        <th class="border p-2">Estimated Unit Cost</th>
                                                        <th class="border p-2">Total</th>
                                                        <th class="border p-2">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items as $index => $item)
                                                        <tr>
                                                            <td class="border p-2">
                                                                <input type="text"
                                                                    wire:model.defer="items.{{ $index }}.description"
                                                                    class="w-full border rounded px-2 py-1" />
                                                                @error("items.$index.description")
                                                                    <span class="text-red-500">{{ $message }}</span>
                                                                @enderror
                                                            </td>
                                                            <td class="border p-2">
                                                                <input type="number" min="1"
                                                                    wire:model.defer="items.{{ $index }}.qty"
                                                                    class="w-full border rounded px-2 py-1" />
                                                            </td>
                                                            <td class="border p-2">
                                                                <input type="text"
                                                                    wire:model.defer="items.{{ $index }}.unit"
                                                                    class="w-full border rounded px-2 py-1" />
                                                            </td>
                                                            <td class="border p-2">
                                                                <input type="number" step="0.01"
                                                                    wire:model.defer="items.{{ $index }}.unitCost"
                                                                    class="w-full border rounded px-2 py-1" />
                                                            </td>
                                                            <td class="border p-2">
                                                                <input type="number" readonly
                                                                    value="{{ (float) ($item['qty'] ?? 0) * (float) ($item['unitCost'] ?? 0) }}"
                                                                    class="w-full border rounded px-2 py-1 bg-gray-100" />
                                                            </td>
                                                            <td class="border p-2 text-center">
                                                                <button type="button"
                                                                    wire:click="removeItem({{ $index }})"
                                                                    class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="mt-3">
                                                <button type="button" wire:click="addItem"
                                                    class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">+
                                                    Add Item</button>
                                            </div>
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block font-medium mb-1">Project Description*</label>
                                            <textarea wire:model.defer="description" class="w-full border rounded px-3 py-2" rows="4"></textarea>
                                            @error('description')
                                                <span class="text-red-500">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Attachments --}}
                                        <div class="border-dashed border-gray-300 border-2 p-2 rounded-md mt-6">
                                            <label class="block font-medium mb-1">Attachments</label>
                                            <input type="file" wire:model="attachment"
                                                class="w-full border rounded px-3 py-2" />
                                            @error('attachment')
                                                <span class="text-red-500">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div
                                        class="px-6 py-4 flex justify-end items-center border-t border-gray-300 bg-[#F9FAFB]">
                                        <button type="submit"
                                            class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                            Submit PPMP
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <!-- List View -->
                <!-- LIST VIEW -->
                <div x-show="view === 'list'" class="mt-6" x-data="{ openModal: false, selectedPr: null }">
                    <div class="bg-white border border-gray-300 rounded-md shadow p-6">
                        <h2 class="text-lg font-bold mb-4">List of PR Entries</h2>
                        <table class="w-full table-auto border-collapse text-sm">
                            <thead>
                                <tr class="bg-[#062B4A] text-white text-left">
                                    <th class="p-2 border">PR #</th>
                                    <th class="p-2 border">Purpose</th>
                                    <th class="p-2 border">Status</th>
                                    <th class="p-2 border">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ppmps as $ppmp)
                                    <tr>
                                        <td class="p-2 border">{{ $ppmp->id }}</td>
                                        <td class="p-2 border">{{ $ppmp->project_type }}</td>
                                        <td class="p-2 border text-center
                                            {{ $ppmp->status === 'approved' ? 'text-green-400' : '' }}
                                            {{ $ppmp->status === 'rejected' ? 'text-pink-400' : '' }}
                                            {{ $ppmp->status === 'returned' ? 'text-gray-400' : '' }}
                                            {{ $ppmp->status === 'pending' ? 'text-blue-400' : '' }}">
                                            {{ ucfirst($ppmp->status) }}
                                        </td>
                                        <td class="p-2 border text-center">
                                            <button 
                                                @click="selectedPr = {{ $ppmp->toJson() }}; openModal = true" 
                                                class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-2 border text-center text-gray-400">
                                            No PR entries found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- MODAL -->
                    <!-- MODAL -->
                    <div x-show="openModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-transition>
                        <!-- Whole modal scrollable -->
                        <div class="bg-gray-100 w-full ml-30 max-w-4xl rounded-lg shadow-lg relative max-h-[90vh] overflow-y-auto" @click.away="openModal = false">
                            
                            <!-- Close -->
                            <button @click="openModal = false" 
                                    class="absolute top-2 right-2 text-gray-600 hover:text-black text-xl">&times;</button>
                            
                            <!-- Header -->
                            <div class="flex items-center px-6 py-4 border-b border-gray-300 space-x-4">
                                <h3 class="text-lg font-bold">PR Details</h3>
                                <span class="text-sm text-gray-500">
                                    Status: 
                                    <span x-text="selectedPr.status" 
                                        :class="{
                                            'text-green-600': selectedPr.status === 'approved',
                                            'text-red-500': selectedPr.status === 'rejected',
                                            'text-gray-400': selectedPr.status === 'returned',
                                            'text-blue-500': selectedPr.status === 'pending'
                                        }">
                                    </span>
                                </span>
                            </div>


                            <!-- Body -->
                            <div class="p-6 space-y-6">
                                <!-- Info Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">PR #</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.id"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Project Title</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.project_title"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Project Type</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.project_type"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Project Description</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.description"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Implemeting Unit</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.implementing_unit"></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">ABC (Approved Budget)*</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800" x-text="selectedPr.abc"></p>
                                    </div>
                                </div>

                                <!-- Items Table -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white text-sm text-left border border-gray-300 rounded-md">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">ITEM #</th>
                                                <th class="border px-2 py-1">ITEM</th>
                                                <th class="border px-2 py-1">QTY</th>
                                                <th class="border px-2 py-1">UNIT</th>
                                                <th class="border px-2 py-1">UNIT COST</th>
                                                <th class="border px-2 py-1">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="item in selectedPr.items" :key="item.id">
                                                <tr>
                                                    <td class="border px-2 py-1" x-text="item.id"></td>
                                                    <td class="border px-2 py-1" x-text="item.description"></td>
                                                    <td class="border px-2 py-1" x-text="item.qty"></td>
                                                    <td class="border px-2 py-1" x-text="item.unit"></td>
                                                    <td class="border px-2 py-1" x-text="item.unit_cost"></td>
                                                    <td class="border px-2 py-1" x-text="item.total_cost"></td>
                                                </tr>
                                            </template>
                                            <template x-if="!selectedPr.items || selectedPr.items.length === 0">
                                                <tr>
                                                    <td colspan="6" class="border px-2 py-2 text-center text-gray-400 italic">
                                                        No items listed
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Attachment -->
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-2">Attachment</p>
                                    <template x-if="selectedPr.attachment">
                                        <div class="flex items-center justify-between bg-white border rounded-md px-4 py-2 hover:shadow-sm">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828V15a3 3 0 01-3 3H6a3 
                                                        3 0 01-3-3V6a3 3 0 013-3h9a3 3 0 013 3v.172z"/>
                                                </svg>
                                                <span class="text-blue-600 font-medium" x-text="selectedPr.attachment_name"></span>
                                            </div>
                                            <a :href="'/ppmp/download/' + selectedPr.id"
                                            class="text-sm text-blue-500 hover:underline">
                                            Download
                                            </a>
                                        </div>
                                    </template>
                                    <template x-if="!selectedPr.attachment">
                                        <p class="text-gray-400 italic">No attachment uploaded</p>
                                    </template>
                                </div>

                                <!-- Remarks -->
                                <div class="p-4 border-2 border-dashed border-gray-300 rounded-lg">
                                    <p class="text-sm font-medium text-gray-600">Remarks</p>
                                    <p class="mt-1 text-gray-700"
                                        x-text="selectedPr.remarks || 'No remarks provided'" 
                                        :class="!selectedPr.remarks ? 'italic text-gray-400' : ''">
                                    </p>
                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-4 flex justify-end items-center border-t border-gray-300 bg-white">
                                <button @click="openModal = false"
                                    class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>




        </main>
    </div>
</div>
