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

    <div class="flex items-center gap-2">
      <label for="sort" class="text-sm font-medium">Sort by:</label>
      <select id="sort" name="sort" class="border border-gray-300 rounded px-2 py-1 text-sm">
        <option>First Name</option>
        <option>Last Name</option>
        <option>Status</option>
      </select>
      <button class="bg-[#d5e4e6] hover:bg-[#c5dadb] text-black rounded px-4 py-1 text-sm font-semibold">Filter</button>
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
@foreach ($groupedUsers as $role => $users)
<div class="mt-8">
    <!-- Role Header -->
    <div class="bg-[#002b4a] text-white px-4 py-2 rounded-full font-semibold">
        {{ $role }} Account
    </div>

    <!-- Table Layout -->
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full text-sm border border-gray-300">
            <thead class="bg-[#002b4a] text-cyan-900">
                <tr>
                    <th class="text-left px-4 py-2 border text-white">Name</th>
                    <th class="text-left px-4 py-2 border text-white">Username</th>
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
                            {{ $user->username }}
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
                            <button class="bg-blue-500 text-white px-2 py-1 rounded text-xs">Edit</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach


  
      </main>
    </div>
  </div>
  