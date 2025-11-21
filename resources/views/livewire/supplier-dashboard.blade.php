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
    <main class="p-6 flex-1 bg-gray-100 space-y-8">

        <!-- TOP SECTION: KPI CARDS (Optional to match your screenshot style) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Announcements -->
            <div class="bg-white border rounded-lg shadow p-6 flex items-center gap-4">
                <div class="p-3 bg-blue-100 rounded-lg text-blue-700">
                    <x-phosphor.icons::regular.note class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Announcements</p>
                    <h2 class="font-bold text-xl">{{ count($announcements) }}</h2>
                </div>
            </div>
          
            <!-- Recent Activities -->
            <div class="bg-white border rounded-lg shadow p-6 flex items-center gap-4">
                <div class="p-3 bg-green-100 rounded-lg text-green-700">
                    <x-phosphor.icons::regular.clock class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Peding Invitation</p>
                    <h2 class="font-bold text-xl">{{ $invitationCount }}</h2>

                </div>
            </div>
          
            <!-- Active Procurements -->
            <div class="bg-white border rounded-lg shadow p-6 flex items-center gap-4">
                <div class="p-3 bg-yellow-100 rounded-lg text-yellow-700">
                    <x-phosphor.icons::regular.shopping-cart class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Active Procurements</p>
                    <h2 class="font-bold text-xl">{{ count($activeProcurements) }}</h2>
                </div>
            </div>
        </div>
        
    
                <!-- MIDDLE SECTION: ANNOUNCEMENT TABLE + IMAGE -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- LEFT: ANNOUNCEMENT TABLE -->
            <div class="bg-white rounded-lg shadow border border-gray-200 h-[420px] flex flex-col">

                <!-- Header -->
                <div class="flex items-center justify-between p-3 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">Announcements</h2>
                </div>

                <div class="overflow-y-auto flex-1">
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

            


            <!-- RIGHT: IMAGE PANEL WITH TEXT -->
            <div class="relative bg-white rounded-lg shadow border border-gray-200 h-[420px] overflow-hidden">

                <img src="{{ asset('icon/6210016-removebg-preview.png') }}"
                    class="absolute inset-0 w-full h-full object-cover opacity-90">

                <!-- Overlay Text -->
                <div class="absolute inset-0 bg-black/50 flex flex-col justify-center px-8 text-white">
                    <h2 class="text-3xl font-bold mb-2 drop-shadow-lg">Supplier Dashboard</h2>
                    <p class="text-sm leading-relaxed max-w-md drop-shadow">
                        Manage your Dashboard, view Invitations, submit Proposals, and track Notices of Award 
                        efficiently and confidently.
                    </p>
                </div>


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

                    </td>
                          <td class="px-4 py-2">
                              <a href="{{ $announcement['route'] ?? '#' }}"
                                 class="text-blue-700 text-xs font-medium hover:underline">
                                  View
                              </a>
                          </td>
                      </tr>
             
    
    
   <!-- Bottom Benefits Section -->
        <section class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
            
                <!-- Card 1 -->
            <div class="bg-white p-6 rounded-xl shadow-md border flex flex-col gap-3">
            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-600 rounded-full">
                <x-phosphor.icons::regular.house class="w-6 h-6" />
            </div>
            <h3 class="text-lg font-semibold">Dashboard</h3>
            <p class="text-sm text-gray-600">
                Get an overview of your submissions, invitations, and active notices at a glance.
            </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-xl shadow-md border flex flex-col gap-3">
            <div class="flex items-center justify-center w-12 h-12 bg-green-100 text-green-600 rounded-full">
                <x-phosphor.icons::regular.envelope-open class="w-6 h-6" />
            </div>
            <h3 class="text-lg font-semibold">Invitations</h3>
            <p class="text-sm text-gray-600">
                View and respond to invitations from clients and organizations in a timely manner.
            </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-xl shadow-md border flex flex-col gap-3">
            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 text-purple-600 rounded-full">
                <x-phosphor.icons::regular.file-text class="w-6 h-6" />
            </div>
            <h3 class="text-lg font-semibold">Proposal Submission</h3>
            <p class="text-sm text-gray-600">
                Submit your proposals securely, track their status, and manage deadlines efficiently.
            </p>
            </div>

            <!-- Card 4 -->
            <div class="bg-white p-6 rounded-xl shadow-md border flex flex-col gap-3">
            <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full">
                <x-phosphor.icons::regular.trophy class="w-6 h-6" />
            </div>
            <h3 class="text-lg font-semibold">Notice of Award</h3>
            <p class="text-sm text-gray-600">
                Stay updated on awarded contracts, view details, and take necessary actions promptly.
            </p>
            </div>

            
    </main>
    
  </div>
</div>
  