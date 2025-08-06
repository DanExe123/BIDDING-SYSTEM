<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Dashboard</h1>
        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1">

            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
              <!-- Active Procurements -->
              <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500 flex flex-col justify-between h-44">
                <div>
                  <div class="text-sm text-gray-500 flex justify-between">Active Procurements
                    <x-phosphor.icons::regular.file-text class="w-6 h-6 text-green-500" />
                  </div>
                  
                  <div class="text-4xl font-bold">24</div>
                </div>
                <div class="text-xs text-green-600 mb-2">+5 from last month</div>
              </div>
              
          
              <!-- Open Bids -->

            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500 flex flex-col justify-between h-44">
             <div>
                <div class="text-sm text-gray-500 flex justify-between">Open Bids
                    <x-phosphor.icons::regular.gavel class="w-6 h-6 text-blue-500" />
                </div>
                <div class="text-4xl font-bold">12</div>
             </div>
                <div class="text-xs text-blue-600 mb-2">3 ending today</div>
              </div>
          
              <!-- Pending Approvals -->
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500 flex flex-col justify-between h-44">
                    <div>
                    <div class="text-sm text-gray-500 flex justify-between">Pending Approvals
                        <x-phosphor.icons::regular.clock class="w-6 h-6 text-yellow-500" />
                    </div>
                <div class="text-4xl font-bold">8</div>
            </div>
                <div class="text-xs text-yellow-600 mb-2">2 require urgent attention</div>
              </div>
          
              <!-- Registered Suppliers -->
              <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-500 flex flex-col justify-between h-44">
                <div>
              <div class="text-sm text-gray-500 flex justify-between">Registered Suppliers
                <x-phosphor.icons::regular.browsers class="w-6 h-6 text-purple-500" />
              </div>
                <div class="text-4xl font-bold">156</div>
            </div>
                <div class="text-xs text-purple-600 mb-2">+14 this month</div>
              </div>
            </div>
          
            <!-- Ongoing Procurement Process -->
            <div class="bg-white rounded-lg shadow p-4 overflow-x-auto">
              <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold">Ongoing Procurement Process</h2>
                <a href="#" class="text-sm text-blue-500 hover:underline">View All</a>
              </div>
              <div class="flex gap-5 justify-between min-w-[700px] px-2 min-h-30">
                <div class="flex flex-col items-center">
                  <div class="bg-blue-100 p-2 rounded-full">
                    <x-phosphor.icons::regular.file-text class="w-6 h-6 text-blue-500" />
                  </div>
                  <div class="mt-2 text-sm font-medium">PPMP Submission</div>
                  <div class="text-xs text-gray-500">5 pending</div>
                </div>
          
                <div class="flex flex-col items-center ">
                  <div class="bg-yellow-100 p-2 rounded-full">
                    <x-phosphor.icons::regular.megaphone class="w-6 h-6 text-yellow-500" />
                  </div>
                  <div class="mt-2 text-sm font-medium">Bid Announcement</div>
                  <div class="text-xs text-gray-500">8 active</div>
                </div>
          
                <div class="flex flex-col items-center">
                  <div class="bg-green-100 p-2 rounded-full">
                    <x-phosphor.icons::regular.pencil-simple class="w-6 h-6 text-green-500" />
                  </div>
                  <div class="mt-2 text-sm font-medium">Bid Submission</div>
                  <div class="text-xs text-gray-500">12 received</div>
                </div>
          
                <div class="flex flex-col items-center">
                  <div class="bg-purple-100 p-2 rounded-full">
                    <x-phosphor.icons::regular.eye class="w-6 h-6 text-purple-500" />
                  </div>
                  <div class="mt-2 text-sm font-medium">Bid Evaluation</div>
                  <div class="text-xs text-gray-500">3 in progress</div>
                </div>
          
                <div class="flex flex-col items-center">
                  <div class="bg-red-100 p-2 rounded-full">
                    <x-phosphor.icons::regular.check-circle class="w-6 h-6 text-red-500" />
                  </div>
                  <div class="mt-2 text-sm font-medium">Contract Award</div>
                  <div class="text-xs text-gray-500">1 completed</div>
                </div>
              </div>
            </div>
          
            <!-- Recent Activities and Bid Notices -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Recent Activities -->
              <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center mb-3">
                  <h2 class="text-sm font-semibold">Recent Activities</h2>
                  <a href="#" class="text-sm text-blue-500 hover:underline">View All</a>
                </div>
                <div class="text-sm">
                  <span class="font-semibold text-blue-600">Barangay Health Center</span> submitted PPMP for medical supplies
                  <div class="text-xs text-gray-500">2 hours ago</div>
                </div>
              </div>
          
              <!-- Public Bid Notices -->
              <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center mb-3">
                  <h2 class="text-sm font-semibold">Public Bid Notices</h2>
                  <a href="#" class="text-sm text-blue-500 hover:underline">View All</a>
                </div>
                <div class="text-sm font-semibold text-gray-700">
                  ITB #2023-045: Construction of Barangay Health Center
                </div>
                <div class="text-sm text-gray-600">
                  Budget: ₱2,450,000.00 | Deadline: June 15, 2023
                </div>
              </div>
            </div>
          
      </main>
    </div>
  </div>