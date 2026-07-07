<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cement Capacity Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .fade-enter {
      opacity: 0;
      transform: translateY(10px);
    }
    .fade-enter-active {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.4s ease-in-out;
    }
    .fade-exit {
      opacity: 1;
      transform: translateY(0);
    }
    .fade-exit-active {
      opacity: 0;
      transform: translateY(10px);
      transition: all 0.3s ease-in-out;
    }
    .slide-in {
      transform: translateX(-20px);
      opacity: 0;
    }
    .slide-in-active {
      transform: translateX(0);
      opacity: 1;
      transition: all 0.4s ease-in-out;
    }
  </style>
</head>
<body class="bg-gray-50 font-sans">

  <!-- HEADER -->
  <header class="p-6 border-b bg-white shadow-sm flex flex-col gap-1">
    <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-500">
      Cement Capacity Dashboard
    </h1>
    <p class="text-gray-600">Real-time fleet management & capacity monitoring</p>
    <p class="text-xs text-gray-500">Last synced: <span id="lastSynced"></span></p>
  </header>

  <!-- MAIN LAYOUT -->
  <div id="layout" class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 transition-all">

    <!-- DASHBOARD GRID -->
    <section id="dashboard" class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 transition-all">
      <!-- Available Capacity Card -->
      <div onclick="openDetail('available')" 
        class="cursor-pointer bg-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 p-6">
        <h2 class="text-xl font-semibold mb-4 flex justify-between items-center">
         TRUCK UR SUMMARY <span class="text-purple-500 text-2xl">📦</span>
        </h2>

        <div class="grid grid-cols-5 gap-2 text-center text-sm font-medium text-gray-600 mb-2">
          <div>TOTAL</div><div>AVAIL</div><div>PENDING</div><div>ONGOING</div><div>UP%</div>
        </div>
        <div id="summaryValues" class="grid grid-cols-5 gap-2 text-center font-bold bg-purple-50 rounded-lg p-2 mb-4 text-gray-800"></div>

        <div class="grid grid-cols-11 gap-2 text-center text-xs text-gray-600 mb-2">
          <div>IDLE</div><div>AVAIL</div><div>PRELD</div><div>TRIP</div><div>YARD</div>
          <div>OUT</div><div>APP</div><div>RESC</div><div>ACPT</div><div>WFP</div><div>ON R</div>
        </div>
        <div id="breakdownValues" class="grid grid-cols-11 gap-2 text-center font-bold text-sm bg-purple-50 rounded-lg p-2"></div>
        <div class="mt-2 text-right text-xs text-gray-500" id="targetCapacity">Target Capacity: 216</div>
      </div>

      <!-- Live Truck Status -->
      <div onclick="openDetail('live')" 
        class="cursor-pointer bg-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 p-6">
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-semibold">TRAILER UR Status</h2>
          <span class="text-blue-500 text-2xl">📊</span>
        </div>
        <p class="text-4xl font-bold mt-2 text-gray-800">188</p>
        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">Units Tracked</span>
        <canvas id="truckChart" class="mt-4"></canvas>
      </div>

      <!-- Pending Operations -->
      <div onclick="openDetail('pending')" 
        class="cursor-pointer bg-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 p-6">
        <h2 class="text-xl font-semibold">SBUO TRUCK</h2>
        <p class="text-4xl font-bold mt-2 text-gray-800">29</p>
        <canvas id="pendingChart" class="mt-4"></canvas>
      </div>

      <!-- Active Jobs -->
      <div onclick="openDetail('active')" 
        class="cursor-pointer bg-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 p-6">
        <h2 class="text-xl font-semibold">SBUO TRAILER</h2>
        <p class="text-4xl font-bold mt-2 text-gray-800">55</p>
        <canvas id="jobsChart" class="mt-4"></canvas>
      </div>

      <!-- Utilization Rate -->
      <div onclick="openDetail('utilization')" 
        class="cursor-pointer bg-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 p-6">
        <h2 class="text-xl font-semibold">TRUCK STATUS TIMELINE</h2>
        <p class="text-4xl font-bold mt-2 text-purple-600">1.74%</p>
      </div>

      <!-- Fleet Overview -->
      <div onclick="openDetail('fleet')" 
        class="cursor-pointer bg-white rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 p-6">
        <h2 class="text-xl font-semibold">TRAILER STATUS</h2>
        <p class="text-4xl font-bold mt-2 text-gray-800">288</p>
      </div>
    </section>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="hidden lg:col-span-1 space-y-4">
      <h2 class="font-bold text-gray-700 text-sm">Quick View</h2>
      <div class="space-y-4">
        <div onclick="openDetail('available')" class="cursor-pointer bg-white rounded-lg shadow hover:bg-purple-50 p-4">
          <h3 class="font-semibold text-gray-700">TRUCK UR SUMMARY</h3>
          <p class="text-xl font-bold text-gray-900">203</p>
        </div>
        <div onclick="openDetail('live')" class="cursor-pointer bg-white rounded-lg shadow hover:bg-purple-50 p-4">
          <h3 class="font-semibold text-gray-700">Live Truck Status</h3>
          <p class="text-xl font-bold text-gray-900">188</p>
        </div>
        <div onclick="openDetail('pending')" class="cursor-pointer bg-white rounded-lg shadow hover:bg-purple-50 p-4">
          <h3 class="font-semibold text-gray-700">Pending Ops</h3>
          <p class="text-xl font-bold text-gray-900">29</p>
        </div>
        <div onclick="openDetail('active')" class="cursor-pointer bg-white rounded-lg shadow hover:bg-purple-50 p-4">
          <h3 class="font-semibold text-gray-700">Active Jobs</h3>
          <p class="text-xl font-bold text-gray-900">55</p>
        </div>
        <div onclick="openDetail('utilization')" class="cursor-pointer bg-white rounded-lg shadow hover:bg-purple-50 p-4">
          <h3 class="font-semibold text-gray-700">Utilization</h3>
          <p class="text-xl font-bold text-purple-600">1.74%</p>
        </div>
        <div onclick="openDetail('fleet')" class="cursor-pointer bg-white rounded-lg shadow hover:bg-purple-50 p-4">
          <h3 class="font-semibold text-gray-700">Fleet Overview</h3>
          <p class="text-xl font-bold text-gray-900">288</p>
        </div>
      </div>
    </aside>

    <!-- DETAIL VIEW -->
    <section id="detail" class="hidden lg:col-span-2">
      <button onclick="closeDetail()" 
        class="mb-4 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow">
        ← Back
      </button>
      <h2 id="detailTitle" class="text-2xl font-bold text-purple-700"></h2>
      <p class="text-gray-500 mb-6">Detailed analytics and insights</p>

      <div class="bg-white p-6 rounded-2xl shadow mb-6">
        <h3 class="font-bold text-gray-700 mb-4">Capacity Breakdown</h3>
        <div class="space-y-3" id="breakdownContainer"></div>
      </div>

      <div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="font-bold text-gray-700 mb-4">Live Truck Status</h3>
        <table class="w-full text-sm text-left border border-gray-200 rounded-lg overflow-hidden">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="p-2">Unit</th><th class="p-2">Type</th><th class="p-2">Brand</th><th class="p-2">Status</th><th class="p-2">Location</th>
            </tr>
          </thead>
          <tbody id="truckTableBody" class="divide-y divide-gray-200"></tbody>
        </table>
      </div>
    </section>
  </div>

  <script>
    document.getElementById("lastSynced").innerText = new Date().toLocaleString();

    const availableData = {
      summary: { total: 288, availableCapacity: 50, pendingJR: 3, ongoingJR: 0, upPercent: "1.04%" },
      breakdown: { idle: 288, available: 50, preloaded: 3, onTrip: 0, atYard: 156, outside: 18, approved: 2, forResc: 7, accepted: 0, wfp: 32, onResc: 19 },
      targetCapacity: 216
    };

    document.getElementById('summaryValues').innerHTML = `
      <div>${availableData.summary.total}</div>
      <div>${availableData.summary.availableCapacity}</div>
      <div>${availableData.summary.pendingJR}</div>
      <div>${availableData.summary.ongoingJR}</div>
      <div>${availableData.summary.upPercent}</div>
    `;
    document.getElementById('breakdownValues').innerHTML =
      Object.values(availableData.breakdown).map(v => `<div>${v}</div>`).join('');
    document.getElementById('targetCapacity').innerText = `Target Capacity: ${availableData.targetCapacity}`;

    function openDetail(section) {
      const dashboard = document.getElementById('dashboard');
      const sidebar = document.getElementById('sidebar');
      const detail = document.getElementById('detail');

      dashboard.classList.add('fade-exit');
      setTimeout(() => {
        dashboard.classList.add('hidden');
        dashboard.classList.remove('fade-exit');

        sidebar.classList.remove('hidden');
        sidebar.classList.add('slide-in');
        detail.classList.remove('hidden');
        detail.classList.add('fade-enter');

        requestAnimationFrame(() => {
          sidebar.classList.add('slide-in-active');
          detail.classList.add('fade-enter-active');
        });
      }, 200);

      const titleMap = {
        available: "Available Capacity",
        live: "Live Truck Status",
        pending: "Pending Operations",
        active: "Active Jobs",
        utilization: "Utilization Rate",
        fleet: "Fleet Overview"
      };
      document.getElementById("detailTitle").innerText = titleMap[section];

      if(section === 'available') {
        const detailBreakdown = document.getElementById('breakdownContainer');
        detailBreakdown.innerHTML = '';
        for(const [key, value] of Object.entries(availableData.breakdown)) {
          const div = document.createElement('div');
          div.innerHTML = `
            <div class="flex justify-between text-sm"><span>${key.toUpperCase()}</span><span>${value}</span></div>
            <div class="w-full bg-gray-200 h-2 rounded"><div class="bg-blue-500 h-2 rounded" style="width:${Math.min(100, value)}%"></div></div>
          `;
          detailBreakdown.appendChild(div);
        }

        document.getElementById('truckTableBody').innerHTML = `
          <tr><td class="p-2">HGF 667 NCG</td><td class="p-2">6x4</td><td class="p-2">SCANIA</td><td class="p-2">Enroute</td><td class="p-2">Batangas</td></tr>
          <tr><td class="p-2">HGF 445 NCG</td><td class="p-2">6x4</td><td class="p-2">SCANIA</td><td class="p-2">Idle</td><td class="p-2">Quezon City</td></tr>
        `;
      }
    }

    function closeDetail() {
      const dashboard = document.getElementById('dashboard');
      const sidebar = document.getElementById('sidebar');
      const detail = document.getElementById('detail');

      sidebar.classList.remove('slide-in-active');
      detail.classList.remove('fade-enter-active');

      sidebar.classList.add('slide-in');
      detail.classList.add('fade-exit');

      setTimeout(() => {
        sidebar.classList.add('hidden');
        detail.classList.add('hidden');
        dashboard.classList.remove('hidden');
        dashboard.classList.add('fade-enter');
        requestAnimationFrame(() => dashboard.classList.add('fade-enter-active'));
      }, 200);
    }
  </script>
</body>
</html>
