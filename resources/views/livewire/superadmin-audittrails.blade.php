<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="bg-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Audit Trails</h1>
        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>



    <div class="w-full mx-auto bg-white rounded-lg border border-gray-300 shadow-lg overflow-x-auto h-screen">
        <div class="bg-white rounded-t-lg border-b border-gray-300 text-center py-4">
          <h2 class="text-lg font-semibold">Activity Log</h2>
        </div>
        
        <div class="p-4">
          <table class="min-w-full table-auto text-sm text-left text-gray-700">
            <thead class="bg-gray-200 text-gray-700 font-semibold">
              <tr>
                <th class="px-4 py-2">Date/Time</th>
                <th class="px-4 py-2">Action</th>
               
                <th class="px-4 py-2">Account Type</th>
                <th class="px-4 py-2">Location</th>
              </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $log->created_at->format('n/j/Y, g:i:s A') }}</td>
                    <td class="px-4 py-2 {{ $log->action === 'Login' ? 'text-green-600' : 'text-red-600' }} font-medium">
                        {{ $log->action }}
                    </td>
                   
                    <td class="px-4 py-2">
                        {{ Str::of($log->user?->getRoleNames()->first() ?? 'N/A')->replace('_', ' ')->title() }}
                    </td>
                    <td class="px-4 py-2">{{ $log->location ?? 'Unknown' }}</td>
                </tr>
                @endforeach
            </tbody>

          </table>
        </div>
      </div>
</div>
</div>
