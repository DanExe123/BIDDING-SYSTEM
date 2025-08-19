<div x-data="{ showModal: false, showSpecific: false }" 
                x-on:close-modal.window="showModal = false" 
                class="relative">

                <!-- Request Table -->
                <div class="border border-gray-300 m-4 rounded-md overflow-hidden">
                    <table wire:poll class="min-w-full text-sm">
                        <thead class="bg-blue-200 font-semibold border-b border-gray-300">
                            <tr>
                                <th class="w-1/3 text-left px-4 py-2">Request by:</th>
                                <th class="w-1/3 text-center px-4 py-2">Purpose:</th>
                                <th class="w-1/3 text-right px-4 py-2">Status:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ppmps as $ppmp)
                                <tr class="border-b border-gray-200">
                                    <td class="w-1/3 px-4 py-2">
                                        {{ $ppmp->requester->first_name }} {{ $ppmp->requester->last_name }}
                                    </td>
                                    <td class="w-1/3 px-4 py-2 text-center">
                                        <button wire:click="showPpmp({{ $ppmp->id }})" 
                                                x-on:click="showModal = true"
                                                class="text-blue-600 hover:underline">
                                            {{ $ppmp->project_title }}
                                        </button>
                                    </td>
                                    <td class="w-1/3 px-4 py-2 text-right">
                                        {{ ucfirst($ppmp->status) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div x-show="showModal" x-transition 
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white ml-30 w-[90%] md:w-[800px] rounded-md shadow-lg overflow-hidden" 
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
                                    <p class="text-base font-semibold">Create Invitation to Bid</p>
                                    @if($selectedPpmp)
                                    <p class="text-sm text-gray-600">
                                        Reference: <span class="font-medium">{{ $bidReference }} ({{ $selectedPpmp->project_title }})</span>
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
                                        <label class="block font-medium mb-1">Bid Title*</label>
                                        <input type="text" wire:model="bidTitle" class="w-full border rounded px-3 py-2" readonly />
                                        @error('bidTitle') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Bid Reference Number*</label>
                                        <input type="text" wire:model="bidReference" class="w-full border rounded px-3 py-2" readonly />
                                        @error('bidReference') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Approved Budget*</label>
                                        <input type="text" wire:model="approvedBudget" class="w-full border rounded px-3 py-2" readonly />
                                        @error('approvedBudget') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Source of Funds*</label>
                                        <input type="text" wire:model="sourceOfFunds" class="w-full border rounded px-3 py-2" readonly />
                                        @error('sourceOfFunds') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Pre-Bid Conference Date*</label>
                                        <input type="date" wire:model="preBidDate" class="w-full border rounded px-3 py-2" />
                                        @error('preBidDate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Bid Submission Deadline*</label>
                                        <input type="date" wire:model="submissionDeadline" class="w-full border rounded px-3 py-2" />
                                        @error('submissionDeadline') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">Bid Documents</label>
                                    <input type="text" wire:model="bidDocuments" class="w-full border rounded px-3 py-2" />
                                    @error('bidDocuments') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    <button type="button" class="mt-2 text-blue-600 text-sm hover:underline">+ Add Document</button>
                                </div>

                                <!-- Supplier Notification -->
                                <div wire:poll class="border-t pt-4">
                                    <p class="font-medium mb-2">Notify Suppliers</p>
                                    
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" wire:model="inviteScope" value="all" class="form-radio text-blue-600" />
                                        <span>All registered suppliers ({{ $suppliers->count() }})</span>
                                    </label>
                                    @error('inviteScope') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                                    <label class="flex items-center space-x-2">
                                        <input type="radio" wire:model="inviteScope" value="category" class="form-radio text-blue-600" />
                                        <span>By Category</span>
                                    </label>
                                    @if($inviteScope === 'category')
                                        <select wire:model="supplierCategoryId" class="w-full mt-2 border rounded px-3 py-2">
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('supplierCategoryId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    @endif

                                    <label class="flex items-center space-x-2">
                                        <input type="radio" wire:model="inviteScope" value="specific" class="form-radio text-blue-600" />
                                        <span>Specific Suppliers</span>
                                    </label>
                                    @if($inviteScope === 'specific')
                                        <button type="button" @click="showSpecific = !showSpecific" 
                                                class="mt-2 text-blue-600 text-sm hover:underline">+ Add Specific Suppliers</button>
                                        <div x-show="showSpecific" class="mt-2 space-y-2">
                                            @foreach($suppliers as $supplier)
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" wire:model="selectedSuppliers" value="{{ $supplier->id }}" />
                                                    <span>{{ $supplier->first_name }} {{ $supplier->last_name }} ({{ $supplier->supplierCategory->name ?? 'No Category' }})</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('selectedSuppliers') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    @endif
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-4 flex justify-end items-center border-t border-gray-300 bg-[#F9FAFB]">
                                <button wire:click="saveInvitation" 
                                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Publish Bid Notice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
    
 
