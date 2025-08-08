<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Supplier Comparison</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-white p-6">

<div class="max-w-5xl mx-auto mt-10 bg-[#B3EAF1] border border-gray-300 rounded-md p-4 flex flex-col md:flex-row gap-4 items-start">
  
  <!-- Chart -->
  <div class="w-full md:w-1/2">
    <canvas id="supplierChart" height="250"></canvas>
  </div>

  <!-- Description + Button -->
  <div class="w-full md:w-1/2 space-y-4 text-sm text-gray-800">
    <p>
      <strong>OfficeWorks</strong> has the lowest item price among the three suppliers, making it the most cost-effective option.
    </p>
    <p>
      <strong>Megs Schools Supplies</strong> offers the highest item price in the group, which means purchasing from Supplier 2 would cost more compared to the others.
    </p>
    <p>
      <strong>DPM School Supplies</strong> is in the middle in terms of pricing.
    </p>
    <div x-data="{
        showModal: false,
    }" class="relative">

    <button  @click="showModal = true" class="bg-yellow-200 hover:bg-yellow-300 text-gray-800 font-semibold py-2 px-4 rounded-md shadow inline-flex items-center">
      Select and send notice of award to supplier
      <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.293 15.707a1 1 0 010-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"/></svg>
    </button>

                <!-- Modal -->
            <div x-show="showModal"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white w-[90%] md:w-[700px] rounded-md shadow-lg overflow-hidden"
            @click.away="showModal = false">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h2 class="text-lg font-semibold">Award Contract</h2>
            <p class="text-sm text-gray-600">SS-2025-0001 School Supplies for Students</p>
            </div>

            <!-- Content -->
            <div class="px-6 py-6 space-y-6 text-sm text-gray-800">

            <!-- Recommended Awardee Box -->
            <div class="bg-green-100 border border-green-200 rounded-md px-4 py-3">
            <p class="text-sm font-semibold text-green-800">Recommended Awardee</p>
            <div class="flex justify-between items-center mt-1">
                <span class="text-green-700 font-medium">OfficeWorks</span>
                <div class="text-right text-xs text-green-800">
                <p>Total Score: <strong>93.5/100</strong></p>
                <p>Bid Amount: <strong>45,000</strong></p>
                </div>
            </div>
            </div>

            <!-- Award Date -->
            <div>
            <label class="block font-medium mb-1">Award Date*</label>
            <input type="date" value="2025-06-20" class="w-full border rounded px-3 py-2" />
            </div>

            <!-- Remarks -->
            <div>
            <label class="block font-medium mb-1">Remarks</label>
            <textarea rows="3" class="w-full border rounded px-3 py-2" placeholder="Any additional notes..."></textarea>
            </div>

            <!-- Generated Document -->
            <div>
            <label class="block font-medium mb-1">Generate Notice of Award</label>
            <div class="flex justify-between items-center border rounded px-3 py-2">
                <span class="truncate text-gray-600">Notice_Of_Award_SSS-2025-001.docx</span>
                <a href="#" class="text-blue-600 hover:underline text-sm font-medium">Download</a>
            </div>
            </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 flex justify-end items-center border-t border-gray-200 bg-gray-50">
            <button class="px-4 py-2 text-sm text-gray-700 bg-gray-200 hover:bg-gray-300 rounded mr-2">Back</button>
            <button class="px-4 py-2 text-sm text-white bg-green-600 hover:bg-green-700 rounded">Issue Award</button>
            </div>

            </div>
            </div>


    </div>
  </div>
</div>

<!-- Chart Setup -->
<script>
  const ctx = document.getElementById('supplierChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Supplier 1', 'Supplier 2', 'Supplier 3'],
      datasets: [{
        label: 'Item Price (₱)',
        data: [14, 24, 17],
        backgroundColor: '#0077b6',
        borderRadius: 4,
        barThickness: 40
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Item Price ₱'
          }
        }
      }
    }
  });
</script>

</body>
</html>
