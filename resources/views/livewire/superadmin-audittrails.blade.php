<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('partials.user-sidebar')
  
    <!-- Main content area (topbar + page content) -->
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
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Username</th>
                <th class="px-4 py-2">Account Type</th>
                <th class="px-4 py-2">Location</th>
              </tr>
            </thead>
            <tbody>
              <!-- Sample Row -->
              <tr class="border-b">
                <td class="px-4 py-2">6/21/2025, 8:54:29 AM</td>
                <td class="px-4 py-2 text-green-600 font-medium">Login</td>
                <td class="px-4 py-2">jay wil</td>
                <td class="px-4 py-2">jay</td>
                <td class="px-4 py-2">supplier</td>
                <td class="px-4 py-2">Mandaluyong, Metro Manila, Philippines</td>
              </tr>
              <tr class="border-b">
                <td class="px-4 py-2">6/21/2025, 8:53:39 AM</td>
                <td class="px-4 py-2 text-red-600 font-medium">Logout</td>
                <td class="px-4 py-2">jas per</td>
                <td class="px-4 py-2">jasper</td>
                <td class="px-4 py-2">treasurer</td>
                <td class="px-4 py-2">Mandaluyong, Metro Manila, Philippines</td>
              </tr>
              <tr class="border-b">
                <td class="px-4 py-2">6/21/2025, 8:53:31 AM</td>
                <td class="px-4 py-2 text-green-600 font-medium">Login</td>
                <td class="px-4 py-2">jas per</td>
                <td class="px-4 py-2">jasper</td>
                <td class="px-4 py-2">treasurer</td>
                <td class="px-4 py-2">Mandaluyong, Metro Manila, Philippines</td>
              </tr>
              <tr class="border-b">
                <td class="px-4 py-2">6/18/2025, 6:40:41 PM</td>
                <td class="px-4 py-2 text-green-600 font-medium">Login</td>
                <td class="px-4 py-2">jan nole</td>
                <td class="px-4 py-2">janelle</td>
                <td class="px-4 py-2">captain</td>
                <td class="px-4 py-2">Makati City, Metro Manila, Philippines</td>
              </tr>
              <tr class="border-b">
                <td class="px-4 py-2">6/18/2025, 6:40:19 PM</td>
                <td class="px-4 py-2 text-red-600 font-medium">Logout</td>
                <td class="px-4 py-2">Super Admin</td>
                <td class="px-4 py-2">admin</td>
                <td class="px-4 py-2">admin</td>
                <td class="px-4 py-2">Makati City, Metro Manila, Philippines</td>
              </tr>
              <tr class="border-b">
                <td class="px-4 py-2">6/18/2025, 6:29:50 PM</td>
                <td class="px-4 py-2 text-red-600 font-medium">Logout</td>
                <td class="px-4 py-2">jan nelle</td>
                <td class="px-4 py-2">janelle</td>
                <td class="px-4 py-2">captain</td>
                <td class="px-4 py-2">Unknown</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
</div>
</div>
