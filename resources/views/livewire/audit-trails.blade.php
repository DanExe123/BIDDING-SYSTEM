<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
        <!-- Topbar -->
        <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-xl font-semibold">Audit Trails</h1>
            <div>
                <button class="text-gray-500 hover:text-black">
                    <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
                </button>
            </div>
        </header>

        <!-- Activity Log Table -->
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
                            <th class="px-4 py-2">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-b">
                                <td class="px-4 py-2 w-[200px]">
                                    @if($log->created_at->diffInHours() < 24)
                                        {{ $log->created_at->diffForHumans() }}
                                    @else
                                        {{ $log->created_at->format('n/j/Y, g:i:s A') }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 font-medium">
                                    You {{ $log->action }}
                                </td>
                                <td class="px-4 py-2">{{ $log->location ?? 'Unknown' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">No activity logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
