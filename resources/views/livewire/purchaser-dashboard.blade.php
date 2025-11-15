<div class="flex min-h-screen" x-cloak>
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Purchaser Dashboard</h1>
        <div>
          @livewire('purchaser-notification-bell')   
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1">
        <!-- Announcement Box -->
        <div class="bg-white p-4 rounded-md shadow-md border border-gray-300 relative h-[400px]">
          <h2 class="text-center text-lg font-bold text-blue-900 mb-4">Announcement</h2>

          <div class="overflow-y-auto h-[320px] space-y-4 px-2">
            
            {{-- Approved / Rejected PPMPs --}}
            <div>
              @forelse ($approvedOrRejectedPpmps as $ppmp)
                <div class="border border-gray-200 rounded-md p-3 mb-3 bg-gray-50">
                  <p><span class="font-semibold">{{ $ppmp->project_title }}</span> has been 
                  <span class="text-{{ $ppmp->status == 'approved' ? 'green-600' : 'red-600' }}">
                    {{ ucfirst($ppmp->status) }}
                  </span> by BAC Secretariat.</p>
                  <small class="text-gray-500">Updated: {{ $ppmp->updated_at->diffForHumans() }}</small>
                </div>
              @empty
                <p class="text-gray-500 text-sm">No approved or rejected PPMPs yet.</p>
              @endforelse
            </div>

          </div>
        </div>


        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          {{-- Procurement Planning Card --}}
          <div class="bg-white p-6 rounded-md shadow h-80 flex flex-col">
            <h3 class="text-sm font-bold border-b border-gray-300 pb-2 mb-3 text-blue-900">
              Recent Activity
            </h3>

            <div class="flex-1 overflow-y-auto pr-2">
              @forelse ($recentPurchaserActivities as $ppmp)
                <div class="border-b border-gray-200 pb-2 mb-2">
                  <p class="text-sm text-gray-800">
                    You created a new PPMP: 
                    <span class="font-semibold">{{ $ppmp->project_title }}</span>
                  </p>
                  <small class="text-gray-500">
                    Created {{ $ppmp->created_at->diffForHumans() }}
                  </small>
                </div>
              @empty
                <p class="text-gray-500 text-sm text-center mt-6">
                  No activities in the last 24 hours.
                </p>
              @endforelse
            </div>

            <div class="text-gray-400 text-xs border-t border-gray-200 pt-2 text-center">
              Last updated: {{ now()->format('M d, Y') }}
            </div>
          </div>


          {{-- Awarded Purchase Requests Card --}}
          <div class="bg-white p-6 rounded-md shadow h-80 flex flex-col">
            <h3 class="text-sm font-bold border-b border-gray-300 pb-2 mb-3 text-blue-900">
              Purchase Request Awarded
            </h3>

            {{-- Scrollable content area --}}
            <div class="flex-1 overflow-y-auto pr-2">
              @forelse ($recentlyAwardedPpmps as $submission)
                <div class="border-b border-gray-200 pb-2 mb-2 text-left">
                  <p class="text-sm">
                    <span class="font-semibold text-gray-800">
                      {{ $submission->invitation->ppmp->project_title ?? 'N/A' }}
                    </span>
                    <span class="text-green-700 font-semibold">awarded</span>
                  </p>
                  <small class="text-gray-500">
                    {{ $submission->award_date ? \Carbon\Carbon::parse($submission->award_date)->diffForHumans() : '' }}
                  </small>
                </div>
              @empty
                <p class="text-gray-500 text-sm text-center mt-6">
                  No purchase request awrded yet
                </p>
              @endforelse
            </div>
          </div>   
        </div>

      </main>
    </div>
  </div>
  