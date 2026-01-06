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
            
            {{-- New Filter Section --}}
            @hasrole('Super_Admin')
            <div class="p-4 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" wire:model.live="viewAll" class="rounded">
                        <span class="text-sm font-medium">View all users' logs</span>
                    </label>
                    <input 
                        type="text" 
                        wire:model.live.debounce.500ms="search"
                        placeholder="Search actions or locations..."
                        class="flex-1 max-w-md px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>
            @endhasrole 
            
            <div class="p-4">
                <table class="min-w-full table-auto text-sm text-left text-gray-700">
                    <thead class="bg-gray-200 text-gray-700 font-semibold">
                        <tr>
                            <th class="px-4 py-2">Date/Time</th>
                            <th class="px-4 py-2">Action</th>
                            <th class="px-4 py-2">Location</th>
                            @if($viewAll)
                                <th class="px-4 py-2">User</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 w-[200px]">
                                    @if($log->created_at->diffInHours() < 24)
                                        {{ $log->created_at->diffForHumans() }}
                                    @else
                                        {{ $log->created_at->format('n/j/Y, g:i:s A') }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 font-medium">
                                    {{ $log->user ? $log->user->name . ' ' : 'You ' }}{{ $log->action }}
                                </td>
                                <td class="px-4 py-2">{{ $log->location ?? 'Unknown' }}</td>
                                @if($viewAll)
                                    <td class="px-4 py-2">{{ $log->user->first_name ?? 'Unknown' }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $viewAll ? 4 : 3 }}" class="text-center py-8 text-gray-500">
                                    No activity logs found{{ $search ? ' for "' . $search . '"' : '' }}.
                                </td>
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
