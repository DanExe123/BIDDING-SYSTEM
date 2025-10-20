<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')


    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
        <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">User Management</h1>
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

            <!-- Filter and Create Account Buttons -->
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div class="flex items-center gap-2 mb-4">
                    <label for="roleFilter" class="text-sm font-medium">Filter by:</label>
                    <select wire:model="roleFilter" id="roleFilter"
                        class="border border-gray-300 rounded px-2 py-1 text-sm">
                        <option value="All">All</option>
                        <option value="BAC Secretary">BAC Secretary</option>
                        <option value="Supplier">Supplier</option>
                        <option value="Purchaser">Purchaser</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <a wire:navigate href="{{ route('superadmin-create-account') }}">
                        <button
                            class="border border-cyan-700 text-cyan-900 hover:bg-cyan-100 px-4 py-1 rounded text-sm font-semibold">
                            Create Account
                        </button>
                    </a>
                </div>

            </div>

            <div class="flex justify-start">
                <button
                    class="bg-yellow-300 hover:bg-yellow-400 text-black px-4 py-1 rounded-full text-sm font-semibold">Show
                    Archive Accounts</button>
            </div>

            <!-- Account Sections (temporary data for front use only) -->
            <div wire:poll class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-300">
                    <thead class="bg-[#002b4a] text-cyan-900">
                        <tr>
                            <th class="text-left px-4 py-2 border text-white">Name</th>
                            <th class="text-left px-4 py-2 border text-white">Email</th>
                            <th class="text-left px-4 py-2 border text-white">Account Type</th>
                            <th class="text-left px-4 py-2 border text-white">Status</th>
                            <th class="text-left px-4 py-2 border text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="bg-gray-50 border-b">
                                <td class="px-4 py-2 border font-medium">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </td>

                                <td class="px-4 py-2 border text-gray-700">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-2 border text-gray-700">
                                    {{ str_replace('_', ' ', $user->roles->first()->name ?? 'N/A') }}
                                </td>

                                <td class="px-4 py-2 border">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs
                                        @if($user->account_status === 'verified') bg-green-100 text-green-800
                                        @elseif($user->account_status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        <div class="w-2.5 h-2.5 rounded-full
                                            @if($user->account_status === 'verified') bg-green-700
                                            @elseif($user->account_status === 'rejected') bg-red-700
                                            @else bg-yellow-600 @endif"></div>
                                        {{ ucfirst($user->account_status ?? 'Pending') }}
                                    </span>
                                </td>

                                <td class="px-4 py-2 border space-x-1">
                                    <button class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">Activate</button>
                                    <button class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Deactivate</button>
                                    <button class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Archive</button>
                                    <a href="{{ route('superadmin-edit-account', ['user' => $user->id]) }}"
                                        class="inline-block bg-blue-500 text-white px-2 py-1 rounded text-xs text-center">
                                        Edit
                                    </a>

                                    {{-- ✅ Verify Supplier button (only for suppliers that are pending or rejected) --}}
                                    @if($user->roles->first()->name === 'Supplier' && in_array($user->account_status, ['pending', 'rejected']))
                                        <button 
                                            wire:click="openVerificationModal({{ $user->id }})"
                                            class="bg-green-700 text-white px-2 py-1 rounded text-xs hover:bg-green-800">
                                            Verify Supplier
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Verification Modal --}}
               @if($showVerificationModal && $selectedSupplier)
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white w-full max-w-lg p-6 rounded-2xl shadow-2xl border border-gray-200">
                            
                            {{-- Modal Header --}}
                            <h2 class="text-2xl font-bold text-[#002b4a] mb-5 text-center border-b pb-3">
                                Supplier Verification
                            </h2>

                            {{-- Supplier Info --}}
                            <div class="space-y-3 text-sm text-gray-700">
                                <div class="flex justify-between">
                                    <p><strong>Name:</strong></p>
                                    <p>{{ $selectedSupplier->first_name }} {{ $selectedSupplier->last_name }}</p>
                                </div>
                                <div class="flex justify-between">
                                    <p><strong>Email:</strong></p>
                                    <p>{{ $selectedSupplier->email }}</p>
                                </div>
                                <div class="flex justify-between">
                                    <p><strong>Category:</strong></p>
                                    <p>{{ $selectedSupplier->supplierCategory->name ?? 'N/A' }}</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p><strong>Account Status:</strong></p>
                                    <span class="font-semibold capitalize px-2 py-1 rounded-md
                                        @if($selectedSupplier->account_status === 'verified') bg-green-100 text-green-700
                                        @elseif($selectedSupplier->account_status === 'rejected') bg-red-100 text-red-700
                                        @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ $selectedSupplier->account_status ?? 'Pending' }}
                                    </span>
                                </div>

                                {{-- Business Permit --}}
                                @if($selectedSupplier->business_permit)
                                    <div class="mt-4">
                                        <p class="font-semibold text-sm mb-2">Business Permit File:</p>
                                        <div class="flex items-center justify-between bg-gray-50 border border-gray-300 rounded-md px-4 py-2 hover:bg-gray-100 transition">
                                            <div class="flex items-center gap-3">
                                                <span class="text-xl">📄</span>
                                                <span class="text-sm text-gray-800 font-medium truncate max-w-[200px]">
                                                    {{ $selectedSupplier->bpl_file_name }}
                                                </span>
                                            </div>

                                            <a href="{{ Storage::url($selectedSupplier->business_permit) }}"
                                                download="{{ $selectedSupplier->bpl_file_name }}"
                                                target="_blank"
                                                class="bg-yellow-300 hover:bg-yellow-400 text-black px-3 py-1 rounded text-xs font-semibold transition">
                                                 Download
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-500 mt-2">No business permit uploaded.</p>
                                @endif
                            </div>

                            {{-- Buttons --}}
                            <div class="flex justify-end gap-3 mt-8 border-t pt-4">
                                <button wire:click="$set('showVerificationModal', false)"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-1.5 rounded text-sm font-medium transition">
                                    Close
                                </button>

                                {{-- Trigger the new reject modal --}}
                                <button wire:click="openRejectModal"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded text-sm font-medium transition">
                                    Reject
                                </button>

                                <button wire:click="verifySupplier"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded text-sm font-medium transition">
                                    Verify
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                @if($showRejectModal)
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-2xl border border-gray-200">
                            <h2 class="text-xl font-bold text-red-700 mb-4 text-center border-b pb-3">
                                Confirm Rejection
                            </h2>

                            <p class="text-gray-700 text-sm mb-3 text-center">
                                Please provide a reason for rejecting this supplier registration.
                            </p>

                            <textarea 
                                wire:model="rejectionRemarks"
                                class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-2 focus:ring-red-400"
                                rows="4"
                                placeholder="Enter remarks here..."></textarea>

                            <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                                <button wire:click="$set('showRejectModal', false)"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-1.5 rounded text-sm font-medium transition">
                                    Cancel
                                </button>

                                <button wire:click="confirmRejectSupplier"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-sm font-medium transition">
                                    Confirm Reject
                                </button>
                            </div>
                        </div>
                    </div>
                @endif


            </div>

            @if ($confirmingUserDeletion)
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded shadow-md w-full max-w-sm">
                        <h2 class="text-lg font-bold mb-3">Confirm Deletion</h2>
                        <p class="text-gray-700 mb-4">Are you sure you want to delete this user? This action cannot be
                            undone.</p>

                        <div class="flex justify-end gap-2">
                            <button wire:click="$set('confirmingUserDeletion', false)"
                                class="px-4 py-1 bg-gray-300 rounded hover:bg-gray-400 text-sm">
                                Cancel
                            </button>
                            <button wire:click="deleteUser"
                                class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            @endif


        </main>
    </div>
</div>
