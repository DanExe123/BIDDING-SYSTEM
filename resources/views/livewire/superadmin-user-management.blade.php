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

       @if (session()->has('success'))
          <div
              x-data="{ show: true }"
              x-init="setTimeout(() => show = false, 3000)"
              x-show="show"
              x-transition
              class="bg-green-300 text-green-900 px-4 py-2 rounded mt-2 text-sm font-medium"
          >
              {{ session('success') }}
          </div>
        @endif
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1">
        <!-- Filter and Create Account Buttons -->
        <div class="flex justify-between items-center flex-wrap gap-4"> 
          <div class="flex items-center gap-2 mb-4">
            <label for="roleFilter" class="text-sm font-medium">Filter by:</label>
            <select wire:model="roleFilter" id="roleFilter" class="border border-gray-300 rounded px-2 py-1 text-sm">
                <option value="All">All</option>
                <option value="BAC Secretary">BAC Secretary</option>
                <option value="Supplier">Supplier</option>
                <option value="Purchaser">Purchaser</option>
            </select>
          </div>

          <div class="flex items-center gap-2">
              <a wire:navigate href="{{ route('superadmin-create-account') }}">
                  <button class="border border-cyan-700 text-cyan-900 hover:bg-cyan-100 px-4 py-1 rounded text-sm font-semibold">
                    Create Account
                  </button>
                </a>          
          </div>
          
        </div>

        <div class="flex justify-start">
          <button class="bg-yellow-300 hover:bg-yellow-400 text-black px-4 py-1 rounded-full text-sm font-semibold">Show Archive Accounts</button>
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
                              <span class="inline-flex items-center gap-1 bg-blue-200 text-blue-800 px-2 py-1 rounded-full text-xs">
                                  <div class="bg-green-700 w-2.5 h-2.5 rounded-full"></div>
                                  Active
                              </span>
                          </td>
                          <td class="px-4 py-2 border space-x-1">
                              <button class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">Activate</button>
                              <button class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Deactivate</button>
                              <button class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Archive</button>
                              <a 
                                  href="{{ route('superadmin-edit-account', ['user' => $user->id]) }}"
                                  class="inline-block bg-blue-500 text-white px-2 py-1 rounded text-xs text-center"
                              >
                                  Edit
                              </a>
                              <button 
                                  wire:click="confirmDelete({{ $user->id }})"
                                  class="bg-red-500 text-white px-2 py-1 rounded text-xs"
                              >
                                  Delete
                              </button>
                          </td>
                      </tr>
                  @endforeach
              </tbody>
          </table>
        </div>

        @if ($confirmingUserDeletion)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded shadow-md w-full max-w-sm">
                    <h2 class="text-lg font-bold mb-3">Confirm Deletion</h2>
                    <p class="text-gray-700 mb-4">Are you sure you want to delete this user? This action cannot be undone.</p>

                    <div class="flex justify-end gap-2">
                        <button
                            wire:click="$set('confirmingUserDeletion', false)"
                            class="px-4 py-1 bg-gray-300 rounded hover:bg-gray-400 text-sm"
                        >
                            Cancel
                        </button>
                        <button
                            wire:click="deleteUser"
                            class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm"
                        >
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        @endif


      </main>
    </div>
  </div>
  