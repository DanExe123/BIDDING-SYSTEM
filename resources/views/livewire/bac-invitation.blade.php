<div x-data="{ showModal: false, showSpecific: false }" 
     x-on:close-modal.window="showModal = false" 
     class="relative" x-cloak>

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
    <!-- Request Table -->

    <input type="text" 
       wire:model.live.debounce.300ms="searchInv"
       placeholder="Search by project title or reference no..."
       class="w-full max-w-md mx-4 mb-2 px-4 py-2 border rounded-lg">

    <div class="border border-gray-300 mx-4 rounded-md overflow-hidden">

        <table wire:poll class="min-w-full text-sm">
            <thead class="bg-blue-200 font-semibold border-b border-gray-300">
                <tr>
                    <th class="w-1/5 text-left px-4 py-2">Reference No</th>
                    <th class="w-1/5 text-center px-4 py-2">Purpose</th>
                    <th class="w-1/5 text-center px-4 py-2">Procurement Type</th>
                    <th class="w-1/6 text-center px-4 py-2">Created At</th>
                    <th class="w-1/5 text-center px-4 py-2">Status</th>
                    <th class="w-1/5 text-center px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ppmps as $ppmp)
                    <tr class="border-b border-gray-200">
                        <td class="px-4 py-2">
                            {{ $ppmp->invitations->last()?->reference_no ?? 'No Invitation' }}
                        </td>
                        <td class="px-4 py-2 text-center max-w-xs truncate">
                            {{ $ppmp->project_title }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            {{ ucfirst($ppmp->mode_of_procurement) }}
                        </td>

                        <td class="px-4 py-2 text-center">
                            @php
                                // get the latest invitation for this PPMP
                                $latestInvitation = $ppmp->invitations->sortByDesc('created_at')->first();
                            @endphp

                            @if($latestInvitation)
                                {{ $latestInvitation->created_at->diffInHours() < 24
                                    ? $latestInvitation->created_at->diffForHumans()
                                    : $latestInvitation->created_at->format('n/j/Y, g:i A') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-2 py-2 text-center">
                            @if($ppmp->invitations->isNotEmpty())
                                @php
                                    $status = $ppmp->invitations->last()->status;
                                @endphp

                                @if($status === 'published')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-sm">
                                        {{ ucfirst($status) }}
                                    </span>
                                @elseif($status === 'close')
                                    <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-sm">
                                        {{ ucfirst($status) }}
                                    </span>
                                @elseif($status === 'awarded')
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-sm">
                                        {{ ucfirst($status) }}
                                    </span>
                                @endif
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-sm">
                                    Pending
                                </span>
                            @endif
                        </td>
                        {{-- Actions column --}}
                        <td class="px-2 py-2 text-center max-w-xs">
                            <div class="flex flex-wrap justify-center items-center gap-2">
                                {{-- View Invitations --}}
                                <livewire:bac-view-invitation :ppmp="$ppmp" :key="$ppmp->id" />

                                @hasrole('BAC_Secretary')
                                {{-- Create Invitation (only show if no invitation exists) --}}
                                @if($ppmp->invitations->isEmpty())
                                    <button wire:click="showPpmp({{ $ppmp->id }})" 
                                        x-on:click="showModal = true"
                                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 whitespace-nowrap">
                                        {{ $ppmp->mode_of_procurement === 'quotation' ? 'Notify Supplier' : 'Invite Supplier' }}
                                    </button>
                                @endif
                                @endhasrole
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- ✅ Pagination Links -->
        <div class="px-4 py-2">
            {{ $ppmps->links() }}
        </div>


    </div>

    <!-- Modal -->
        <!-- Modal -->
    <div x-show="showModal" x-transition 
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        wire:ignore.self>
        <div class="bg-white ml-30 w-[90%] md:w-[800px] rounded-md shadow-lg max-h-[90vh] overflow-y-auto"
            @click.away="showModal = false">

            <!-- Loading -->
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
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-300 bg-[#F9FAFB]">
                    <div>
                        <p class="text-base font-semibold">
                            {{ ($selectedPpmp && $selectedPpmp->mode_of_procurement === 'quotation')
                            ? 'Notify Supplier'
                            : 'Create Invitation' }}
                        </p>
                        @if($selectedPpmp)
                        <p class="text-sm text-gray-600">
                            Reference: <span class="font-medium">{{ $referenceNo }} ({{ $selectedPpmp->project_title }})</span>
                        </p>
                        <p class="text-sm text-gray-600">
                            Mode of Procurment: <span class="font-medium">{{ ucfirst($selectedPpmp->mode_of_procurement) }}</span>
                        </p>
                        @endif
                    </div>
                    <button @click="showModal = false; @this.call('closeModal')" 
                            class="text-gray-600 text-xl font-bold hover:text-black">&times;</button>
                </div>

                <!-- Form Content -->
                <div class="px-6 py-4 space-y-4 text-sm text-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium mb-1">Title*</label>
                            <input type="text" wire:model="title" class="w-full border rounded px-3 py-2" readonly />
                            @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Reference Number*</label>
                            <input type="text" wire:model="referenceNo" class="w-full border rounded px-3 py-2" readonly />
                            @error('referenceNo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Approved Budget*</label>
                            <input type="text" wire:model="approvedBudget" class="w-full border rounded px-3 py-2" readonly />
                            @error('approvedBudget') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>{{--
                            <label class="block font-medium mb-1">Source of Funds*</label>
                            <input type="text" wire:model="sourceOfFunds" class="w-full border rounded px-3 py-2" readonly />
                            @error('sourceOfFunds') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror--}}
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Start Date*</label>
                            <input type="date" wire:model="preDate" min="{{ date('Y-m-d') }}" class="w-full border rounded px-3 py-2" />
                            @error('preDate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Submission Deadline*</label>
                            <input type="date" wire:model="submissionDeadline" min="{{ date('Y-m-d') }}" class="w-full border rounded px-3 py-2" />
                            @error('submissionDeadline') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <p class="font-medium mb-2">Notify Suppliers</p>

                        {{--
                        @if($selectedPpmp && $selectedPpmp->mode_of_procurement !== 'quotation')
                            <div class="mb-3">
                                <select 
                                    wire:model="supplierCategoryId" 
                                    wire:change="$set('inviteScope', 'category')" 
                                    class="w-full mt-2 border rounded px-3 py-2"
                                >
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplierCategoryId') 
                                    <span class="text-red-600 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                        @endif
                        File preview container --}}

                        <div>
                            <button 
                                type="button" 
                                @click="showSpecific = !showSpecific" 
                                wire:click="$set('inviteScope', 'specific')" 
                                class="mt-2 text-blue-600 text-sm hover:underline"
                            >
                                + Add Specific Suppliers
                            </button>
                            @error('selectedSuppliers') 
                                <span class="text-red-600 text-sm">{{ $message }}</span> 
                            @enderror

                            <div x-show="showSpecific" class="mt-2 space-y-2">
                                {{-- ✅ Search form with button --}}
                                <div class="w-1/2 flex space-x-2">
                                    <input 
                                        type="text" 
                                        wire:model="supplierSearch" 
                                        placeholder="Search suppliers..." 
                                        class="flex-1 border rounded px-3 py-2"
                                    />
                                    <button 
                                        type="button" 
                                        wire:click="searchSuppliers" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded"
                                    >
                                        Search
                                    </button>
                                </div>

                                <div class="max-h-48 overflow-y-auto border rounded p-2 mt-2">
                                    @forelse($suppliers as $supplier)
                                        <label class="flex items-center space-x-2">
                                            <input 
                                                type="checkbox" 
                                                wire:model="selectedSuppliers" 
                                                value="{{ $supplier->id }}" 
                                                wire:change="$set('inviteScope', 'specific')" 
                                            />
                                            <span>{{ $supplier->first_name }} 
                                                ({{ $supplier->supplierCategory->name ?? 'No Category' }})
                                            </span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500">No suppliers found.</p>
                                    @endforelse
                                </div>
                            </div>
                                             
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 flex justify-end items-center border-t border-gray-300 bg-[#F9FAFB]">
                    <button wire:click="saveInvitation" 
                            class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                        Publish
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
