<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.super-admin-sidebar')
  
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
  
  <!-- Account Sections  temporary data for front use only pre -->
  @foreach (['Admin' => ['ken paguddan'], 'Purchaser' => ['ken paguddan', 'kent alt'], 'Supplier' => ['ken alt', 'kent alt']] as $role => $users)
    <div class="mt-6">
      <div class="bg-[#002b4a] text-white px-4 py-2 rounded-full font-semibold">{{ $role }} Account</div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        @foreach ($users as $user)
          <div class="bg-cyan-100 border border-cyan-300 rounded-md p-4 shadow relative">
            <div class="flex items-center justify-between mb-2">
              <div>
                <div class="text-black font-bold">{{ $user }}</div>
                <div class="text-sm text-gray-600">Username: sample_user</div>
              </div>

              <span class="text-sm bg-blue-200 text-blue-800 px-2 py-1 rounded-full flex justify-start gap-1">
                <div class="bg-green-700 w-3 h-3 rounded-full mt-1"></div>
                Active</span>
            </div>
            <div class="flex gap-2 mt-2">
              <button class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm">Activate</button>
              <button class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm">Deactivate</button>
              <button class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm">Archive</button>
              <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm ml-auto">Edit</button>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endforeach
  
      </main>
    </div>
  </div>
  