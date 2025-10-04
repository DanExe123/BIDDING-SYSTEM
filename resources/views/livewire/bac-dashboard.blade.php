<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Dashboard</h1>
        <div>
     @livewire('bac-notification-bell')
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1">

            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
              <!-- Active Procurements -->
              <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500 flex flex-col justify-between h-44">
                <div>
                  <div class="text-sm text-gray-500 flex justify-between">Active Procurements
                    <x-phosphor.icons::regular.file-text class="w-6 h-6 text-green-500" />
                  </div>
                  
                  <div class="text-4xl font-bold">{{ $activeProcurementsCount }}</div>
                </div>
                <div class="text-xs text-green-600 mb-2">+{{ $activeProcurementsLastMonth }} from last month</div>
              </div>
              
          
              <!-- Open Bids -->

              <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500 flex flex-col justify-between h-44">
                <div>
                    <div class="text-sm text-gray-500 flex justify-between">Open Bids
                        <x-phosphor.icons::regular.gavel class="w-6 h-6 text-blue-500" />
                    </div>
                    <div class="text-4xl font-bold">{{ $openBidsCount }}</div>
                </div>
                  <div class="text-xs text-blue-600 mb-2">{{ $openBidsEndingToday }} ending today</div>
              </div>

              <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-500 flex flex-col justify-between h-44">
                <div>
                    <div class="text-sm text-gray-500 flex justify-between">Active RFQ
                        <x-phosphor.icons::regular.gavel class="w-6 h-6 text-red-500" />
                    </div>
                    <div class="text-4xl font-bold">{{ $rfqCount }}</div>
                </div>
                  <div class="text-xs text-red-600 mb-2">{{ $rfqEndingToday }} ending today</div>
              </div>
          
              <!-- Pending Approvals -->
              <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500 flex flex-col justify-between h-44">
                <div>
                  <div class="text-sm text-gray-500 flex justify-between">Pending Approvals
                    <x-phosphor.icons::regular.clock class="w-6 h-6 text-yellow-500" />
                  </div>
                  <div class="text-4xl font-bold">{{ $pendingApprovalsCount }}</div>
                </div>
                <div class="text-xs text-yellow-600 mb-2">{{ $pendingApprovalsCount }} require urgent attention</div>
              </div>
          
              <!-- Registered Suppliers -->
              <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-500 flex flex-col justify-between h-44">
                <div>
                  <div class="text-sm text-gray-500 flex justify-between">Registered Suppliers
                    <x-phosphor.icons::regular.browsers class="w-6 h-6 text-purple-500" />
                  </div>
                  <div class="text-4xl font-bold">{{ $registeredSuppliersCount }}</div>
                </div>
                <div class="text-xs text-purple-600 mb-2">+{{ $registeredSuppliersThisMonth }} this month</div>
              </div>
            </div>
          
            <!-- Ongoing Procurement Process -->
            <div class="bg-white rounded-lg shadow p-4 overflow-x-auto">
              <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold">Ongoing Procurement Process</h2>
                <a href="{{ route('bac-procurement-workflow') }}" 
                  class="text-sm text-blue-500 hover:underline">
                  View All
                </a>
              </div>
              <div class="flex gap-5 justify-between min-w-[700px] px-2 min-h-30">
                <div class="flex flex-col items-center">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <x-phosphor.icons::regular.file-text class="w-6 h-6 text-blue-500" />
                    </div>
                    <div class="mt-2 text-sm font-medium">RFQ Submission</div>
                    <div class="text-xs text-gray-500">{{ $rfqSubmissionCount }} recieved</div>
                </div>

                <div class="flex flex-col items-center ">
                    <div class="bg-yellow-100 p-2 rounded-full">
                        <x-phosphor.icons::regular.eye class="w-6 h-6 text-yellow-500" />
                    </div>
                    <div class="mt-2 text-sm font-medium">RFQ Evaluation</div>
                    <div class="text-xs text-gray-500">{{ $rfqEvaluationCount }} in progress</div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="bg-green-100 p-2 rounded-full">
                        <x-phosphor.icons::regular.pencil-simple class="w-6 h-6 text-green-500" />
                    </div>
                    <div class="mt-2 text-sm font-medium">Bid Submission</div>
                    <div class="text-xs text-gray-500">{{ $bidSubmissionCount }} received</div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="bg-purple-100 p-2 rounded-full">
                        <x-phosphor.icons::regular.eye class="w-6 h-6 text-purple-500" />
                    </div>
                    <div class="mt-2 text-sm font-medium">Bid Evaluation</div>
                    <div class="text-xs text-gray-500">{{ $bidEvaluationCount }} in progress</div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="bg-red-100 p-2 rounded-full">
                        <x-phosphor.icons::regular.check-circle class="w-6 h-6 text-red-500" />
                    </div>
                    <div class="mt-2 text-sm font-medium">Contract Award</div>
                    <div class="text-xs text-gray-500">{{ $contractAwardCount }} completed</div>
                </div>
              </div>

            </div>
          
            <!-- Recent Activities and Bid Notices -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Recent Activities -->
              <div class="bg-white rounded-lg shadow p-4">
                  <div class="flex justify-between items-center mb-3">
                      <h2 class="text-sm font-semibold">Recent Activities & Updates</h2>
                      <a href="{{ route('bac-procurement-workflow') }}" 
                        class="text-sm text-blue-500 hover:underline">
                        View All
                      </a>

                  </div>

                  <div class="overflow-y-auto h-60">
                    @forelse($recentActivities as $activity)
                        <div class="border border-gray-200 rounded-md p-3 mb-3 text-sm bg-gray-50">
                            @if($activity['user'])
                                <span class="font-semibold text-blue-600">
                                    {{ $activity['user'] }}
                                </span>
                            @endif

                            {!! $activity['message'] !!} {{-- ✅ this allows <strong> --}}
                            
                            <div class="text-xs text-gray-500">
                                {{ $activity['time']->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">No recent activities.</div>
                    @endforelse
                  </div>

              </div>

              <!-- Public Bid Notices -->
              <div class="bg-white rounded-lg shadow p-4">
                  <div class="flex justify-between items-center mb-3">
                      <h2 class="text-sm font-semibold">Public Bid Notices</h2>
                      <a href="{{ route('bac-procurement-workflow') }}" 
                        class="text-sm text-blue-500 hover:underline">
                        View All
                      </a>
                  </div>

                  <div class="overflow-y-auto h-60">
                    @forelse($publicBidNotices as $notice)
                        <div class="border border-gray-200 rounded-md p-3 mb-3 text-sm bg-gray-50">
                            <div class="text-sm font-semibold text-gray-700">
                                {{ $notice->reference_no }}: {{ $notice->ppmp->project_title }}
                            </div>
                            <div class="text-sm text-gray-600">
                                Budget: ₱{{ number_format($notice->approved_budget, 2) }} | 
                                Deadline: {{ \Carbon\Carbon::parse($notice->submission_deadline)->format('F d, Y') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">No bid notices available.</div>
                    @endforelse
                  </div>
              </div>
            </div>

          
      </main>
    </div>
  </div>