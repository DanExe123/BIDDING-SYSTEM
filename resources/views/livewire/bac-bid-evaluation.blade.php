<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
    <div class="flex-1 flex flex-col bg-gray-100 min-h-screen">
      <!-- Topbar -->
      <header class="g-[#EFE8A5] h-16 flex items-center justify-between px-6 shadow">
        <h1 class="text-xl font-semibold">Bid Evaluation</h1>
        <div>
          <button class="text-gray-500 hover:text-black">
            <x-phosphor.icons::regular.bell class="w-6 h-6 text-black" />
          </button>
        </div>
      </header>
  
      <!-- Page content -->
      <main class="p-6 space-y-6 flex-1" x-cloak>

            <!-- Request for Approval Box -->
            <div class="bg-white rounded-md shadow-md border border-gray-300" x-cloak>
              <div class="bg-[#062B4A] text-center py-2 rounded-t-md">
                <h2 class="text-lg font-semibold text-white">Bid Evaluation
              </div>
              <div x-data="{
                showEvaluation: false,
                selectedBid: null,
                openEvaluation(bid) {
                  this.selectedBid = bid;
                  this.showEvaluation = true;
                },
                goBack() {
                  this.showEvaluation = false;
                  this.selectedBid = null;
                }
              }" class="p-6 space-y-6">
              
                <!-- Request Table (Default View) -->
                <template x-if="!showEvaluation">
                  <div class="border border-gray-300 rounded-md overflow-hidden bg-white">
                    <!-- Header -->
                    <div class="bg-blue-200 flex justify-between text-sm px-4 py-2 font-semibold border-b border-gray-300">
                      <span class="w-1/3">Request by:</span>
                      <span class="w-1/3 text-center">Purpose:</span>
                      <span class="w-1/3 text-right">Status:</span>
                    </div>
              
                    <!-- Table Row Example -->
                    <div class="flex justify-between px-4 py-2 text-sm border-b border-gray-200">
                      <span class="w-1/3">Purchaser Name</span>
                      <button 
                        class="w-1/3 text-center text-blue-600 hover:underline"
                        @click="openEvaluation('SS-2025-0001')"
                      >School Supplies for Students</button>
                      <span class="w-1/3 text-right">Pending</span>
                    </div>
                  </div>
                </template>
              
                <!-- Evaluation Template (On Click) -->
                <template x-if="showEvaluation">
                  <div class="bg-white rounded-md border border-gray-300 shadow-sm p-6 space-y-4">
              
                    <!-- Header -->
                    <div class="flex justify-between items-center border-b pb-2">
                      <div>
                        <h2 class="text-lg font-semibold">Bid Evaluation: SS-2025-0001</h2>
                        <p class="text-sm text-gray-600">School Supplies for Student</p>
                      </div>
                      <button @click="goBack" class="text-sm text-blue-600 hover:underline">← Back</button>
                    </div>
              
                    <!-- Tags -->
                    <div class="flex space-x-2">
                      <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Budget: ₱50,000</span>
                      <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">3 Bids Received</span>
                    </div>
              
                    <!-- Evaluation Table -->
                    <div class="overflow-x-auto mt-4">
                      <table class="min-w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100 border-b text-gray-700">
                          <tr>
                            <th class="px-4 py-2 font-medium">Bidder</th>
                            <th class="px-4 py-2">Bid Amount</th>
                            <th class="px-4 py-2">Technical Score</th>
                            <th class="px-4 py-2">Financial Score</th>
                            <th class="px-4 py-2">Total Score</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Actions</th>
                          </tr>
                        </thead>
                        <tbody class="text-gray-700">
                          <tr class="border-b">
                            <td class="px-4 py-2 font-semibold">
                              OfficeWorks<br><span class="text-xs text-gray-500">Validated</span>
                            </td>
                            <td class="px-4 py-2">₱45,000</td>
                            <td class="px-4 py-2">92/100</td>
                            <td class="px-4 py-2">95/100</td>
                            <td class="px-4 py-2 font-bold">93.5</td>
                            <td class="px-4 py-2">
                              <span class="bg-green-100 text-green-800 px-2 py-1 text-xs rounded">Leading</span>
                            </td>
                            <td class="px-4 py-2 space-x-2">
                              <a href="#" class="text-blue-600 hover:underline">Evaluate</a>
                              <a href="#" class="text-gray-600 hover:underline">Docs</a>
                            </td>
                          </tr>
                          <tr class="border-b">
                            <td class="px-4 py-2 font-semibold">
                              Megs School Supplies<br><span class="text-xs text-gray-500">Validated</span>
                            </td>
                            <td class="px-4 py-2">₱48,500</td>
                            <td class="px-4 py-2">88/100</td>
                            <td class="px-4 py-2">90/100</td>
                            <td class="px-4 py-2 font-bold">89.0</td>
                            <td class="px-4 py-2">
                              <span class="bg-blue-100 text-blue-800 px-2 py-1 text-xs rounded">Evaluated</span>
                            </td>
                            <td class="px-4 py-2 space-x-2">
                              <a href="#" class="text-blue-600 hover:underline">Evaluate</a>
                              <a href="#" class="text-gray-600 hover:underline">Docs</a>
                            </td>
                          </tr>
                          <tr>
                            <td class="px-4 py-2 font-semibold">
                              DPM School Supplies<br><span class="text-xs text-gray-500">Pending</span>
                            </td>
                            <td class="px-4 py-2">₱48,000</td>
                            <td class="px-4 py-2">-</td>
                            <td class="px-4 py-2">-</td>
                            <td class="px-4 py-2">-</td>
                            <td class="px-4 py-2">
                              <span class="bg-yellow-100 text-yellow-800 px-2 py-1 text-xs rounded">Pending</span>
                            </td>
                            <td class="px-4 py-2 space-x-2">
                              <a href="#" class="text-blue-600 hover:underline">Evaluate</a>
                              <a href="#" class="text-gray-600 hover:underline">Docs</a>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
              
                    <!-- Report Button -->
                    <div class="flex justify-end">
                        <a href="{{ route('generate-report') }}" target="_blank" rel="noopener noreferrer">
                            <button class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                              Generate Report
                            </button>
                          </a> </div>
                    <hr class="mt-4">
                    <div class="flex flex-col items-center space-y-1">
                        <label for="supplier" class="text-xs font-medium text-gray-700">Select Supplier</label>
                        <select id="supplier" name="supplier" class="border border-gray-300 rounded py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Select supplier</option>
                            <option value="officeworks">Officeworks</option>
                            <option value="meg">MEG School Supplies</option>
                            <option value="dmp">DMP School Supplies</option>
                        </select>
                    </div>                    
                    
                  </div>
                </template>
              </div>
                   
            </div>

          
</main>
</div>
</div>
 
