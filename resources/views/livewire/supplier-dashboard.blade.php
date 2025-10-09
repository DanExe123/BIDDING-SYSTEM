<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Supplier Dashboard</h1>
        @livewire('supplier-notification-bell')  
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1">
      <!-- Announcement Box -->
        <div class="bg-white p-4 rounded-md shadow-md border border-gray-300 relative h-[400px]">
            <h2 class="text-center text-lg font-bold text-blue-900 mb-4">Announcements</h2>

            <div class="overflow-y-auto h-[320px] pr-2">
                @forelse($announcements as $announcement)
                    <div class="border border-gray-200 rounded-md p-3 mb-3 bg-gray-50">
                        <p class="text-sm text-gray-800">
                            {!! $announcement['message'] !!}
                        </p>

                        <div class="flex justify-between items-center mt-2 text-xs text-gray-500">
                            <span>{{ $announcement['time']->diffForHumans() }}</span>
                            <a href="{{ $announcement['route'] ?? '#' }}" 
                              class="text-blue-600 text-xs font-medium hover:underline">
                              View
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-[280px] text-gray-500 text-sm">
                        No announcements available.
                    </div>
                @endforelse
            </div>
        </div>


        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="bg-white p-6 rounded-md shadow">
            <div class="text-sm font-bold border-b border-gray-300 pb-2 mb-2">
                Recent Activity
            </div>

            <div class="overflow-y-auto h-54">
              @forelse($recentActivities as $activity)
                <div class="border border-gray-200 rounded-md p-3 mb-3 bg-gray-50">
                    <p class="text-gray-800">{!! $activity['message'] !!}</p>
                    <div class="text-xs text-gray-500">
                        {{ $activity['time']->diffForHumans() }}
                    </div>
                </div>
              @empty
                  <div class="text-sm text-gray-500 text-center">
                      No recent activities in the last 24hrs.
                  </div>
              @endforelse
            </div>
          </div>
            
          <div class="bg-white p-6 rounded-md shadow">
            <div class="text-sm font-bold border-b border-gray-300 pb-2 mb-2">
                Active Procurement
            </div>

            <div class="overflow-y-auto h-h-54">
              @forelse($activeProcurements as $proc) 
                <div class="border border-gray-200 rounded-md p-3 mb-3 bg-gray-50">
                  <div class="flex justify-between items-center">
                      <div>
                          {{ $proc['project'] }}
                          <strong>(₱{{ number_format($proc['abc'], 2) }})</strong>
                          - {{ ucfirst($proc['mode']) }}
                      </div>

                      <a href="{{ $proc['route'] }}" 
                        class="text-blue-600 text-xs font-medium hover:underline">
                        View
                      </a>
                  </div>
              </div>

              @empty
                  <p class="text-gray-500 text-sm">No active procurements.</p>
              @endforelse

            </div>
          </div>
        </div>

    </main>
  </div>
</div>
  