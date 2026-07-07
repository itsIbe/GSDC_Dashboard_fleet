<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cement Truck Capacity Summary</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
<style>
  body { overflow-x: hidden; font-family: Arial, sans-serif; transition: all 0.3s; }

  .sidebar-menu {
    position: fixed; top: 0; left: 0; height: 100%; width: 250px;
    color: white; background: linear-gradient(to right, #60A5FA, #2563EB);
    transition: all 0.3s; padding: 20px 0; overflow-y: auto; z-index: 1000;
  }
  .sidebar-menu a { color: #ffffff; padding: 12px 20px; text-decoration: none; font-size: 18px; display: flex; align-items: center; gap: 12px; transition: 0.3s;}
  .sidebar-menu a:hover { color: #2563eb; background-color: #ffffff;}
  .sidebar-menu a.active { background-color: #1e40af; font-weight: bold;}
  .sidebar-menu.open { left: 0; }
  .sidebar-menu.closed { left: -250px; }
  .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; z-index: 999; }
  .overlay.show { display: block; }
  .clickable { cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; }
  .clickable:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 8px 16px rgba(0,0,0,0.2); }
  .clickable:active { transform: scale(0.98); }
  /* 🔹 Breakdown Section Styling */
  #breakdownSection {
    transition: all 0.3s ease-in-out;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  #breakdownGrid > div {
    border-radius: 1rem;
    padding: 1rem;
    font-size: 0.9rem;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: transform 0.2s, box-shadow 0.2s;
  }
  #breakdownGrid > div:hover {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 8px 16px rgba(0,0,0,0.25);
  }/* Breakdown Modal Styling */
#breakdownModal1 {
  display: none; /* hidden by default */
  position: fixed;
  inset: 0; /* top:0; bottom:0; left:0; right:0 */
  background-color: rgba(0,0,0,0.5); /* semi-transparent overlay */
  z-index: 50;
  align-items: center; /* vertical centering */
  justify-content: center; /* horizontal centering */
  overflow-y: auto; /* allow scroll if content overflows */
  padding: 2rem; /* spacing around modal */
  transition: opacity 0.3s ease;
  opacity: 0;
}

/* Show modal with fade-in */
#breakdownModal1.flex {
  display: flex;
  opacity: 1;
}
/* Modal content */

/* Table wrapper */
#breakdownModal1 .table-container {
  max-height: 70vh;   /* keeps modal scrollable */
  overflow: auto;
  border-radius: 0.75rem; /* rounded edges */
  border: 1px solid #6e9dfcff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05); /* soft shadow */
}

/* Table */
#breakdownModal1 table {  
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}
/* Hidden state */
#breakdownModal1.hidden {
  display: none;
}

/* Visible modal */
#breakdownModal1.flex {
  display: flex;
  opacity: 1;
  transition: opacity 0.2s ease;
}

/* Fade out */
#breakdownModal1.fade-out {
  opacity: 0;
}

/* Header cells */
#breakdownModal1 th {
  background-color: #004183ff;
  color: #ffffffff;
  font-weight: 600;
  padding: 0.75rem 1rem;
  text-align: left;
  font-size: 0.875rem;
  border-bottom: 1px solid #e5e7eb;
  white-space: nowrap;
  position: sticky;
  top: 0;
  z-index: 5;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05); /* shadow under header */
}

/* Body cells */
#breakdownModal1 td {
  padding: 0.75rem 1rem;
  font-size: 0.85rem;
  border-bottom: 1px solid #e5e7eb;
  color: #111827;
  white-space: nowrap;
}

#breakdownModal1 th:first-child { background-color: #004183ff; }
#breakdownModal1 td:first-child { background-color: #ffffff; }
/* First column sticky */
#breakdownModal1 th:first-child,
#breakdownModal1 td:first-child {
  position: sticky;
  left: 0;
  z-index: 6;
  box-shadow: 2px 0 5px rgba(0,0,0,0.05); /* divider shadow for sticky col */
}
#breakdownModal1 tbody tr:nth-child(even) {
  background-color: #f9fafb;

}
/* Top-left corner */
#breakdownModal1 th:first-child {
  border-top-left-radius: 0.75rem;
  z-index: 7;
}

/* Top-right corner */
#breakdownModal1 th:last-child {
  border-top-right-radius: 0.75rem;
}

/* Bottom-left corner */
#breakdownModal1 tbody tr:last-child td:first-child {
  border-bottom-left-radius: 0.75rem;
}

/* Bottom-right corner */
#breakdownModal1 tbody tr:last-child td:last-child {
  border-bottom-right-radius: 0.75rem;
}

/* Row striping */
#breakdownModal1 tbody tr:nth-child(even) {
  background-color: #BDDDE4;
}
#breakdownModal1 tbody tr:nth-child(odd) {
  background-color: #ffffffff;
}

/* Row hover */
#breakdownModal1 tbody tr:hover {
  background-color: #9EC6F3;
  cursor: pointer;
}

</style>
</head>
<body class="bg-gray-50">

<!-- Sidebar Menu -->
<div id="mySidebar" class="sidebar-menu closed">
  <h2 class="text-center text-2xl font-bold mb-6">Cement Truck Capacity Summary</h2>
  <a href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a>
  <a href="{{ url('/fleetcapacitydashboard') }}"><i class="fas fa-chart-bar"></i>Fleet Capacity Dashboard</a>
  <a href="{{ url('/drivercapacitydashboard') }}"><i class="fas fa-truck"></i> Driver Capacity Dashboard</a>
  <a href="{{ url('/Truck-trailer-driver') }}" class="active"><i class="fas fa-cogs"></i> Truck, Trailer, And Driver Capacity</a>
  <a href="{{ url('/') }}"><i class="fas fa-database"></i> Module B</a>
</div>
<div id="overlay" class="overlay" onclick="closeNav()"></div>
<button class="fixed top-4 left-4 z-50 bg-blue-600 text-white px-3 py-2 rounded-full shadow-lg hover:bg-blue-700 transition text-2xl" onclick="openNav()">&#9776;</button>

<header class="p-6 border-b bg-white shadow-sm flex flex-col gap-1">
  <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-500 text-center">Truck, Trailer & Driver Capacity</h1>
  <div class="text-center mt-2"><p class="text-xs text-gray-500">Last synced: <span id="lastSynced"></span></p></div>
</header>

<div class="grid grid-cols-1 md:grid-cols-3 gap-1">

<!-- TRUCK CARD -->
<div class="bg-gradient-to-r from-blue-600 to-blue-300 p-6 rounded-xl shadow-md text-white">
  <h2 class="text-xl font-bold">TRUCK</h2>
  <p id="truckTotal" class="text-5xl font-extrabold mt-2">0</p>
  <div class="mt-6 grid grid-cols-2 gap-4">
    <div class="clickable bg-white rounded-2xl shadow-lg">
      <div class="bg-gradient-to-r from-green-400 to-green-600 text-white text-center py-4 rounded-t-2xl" onclick="showBreakdown('availabletruck')">
        <h2 class="text-lg font-bold">AVAILABLE</h2>
        <p id="truckAvailableTotal" class="text-3xl font-extrabold">0</p>
      </div>
      <div class="grid grid-cols-4 text-center py-2">
        <div><p class="text-orange-500 text-xs font-semibold">IDLE</p><p id="truckIdle" class="font-bold text-orange-500">0</p></div>
        <div><p class="text-green-500 text-xs font-semibold">AVAILABLE</p><p id="truckAvailable" class="font-bold text-green-500">0</p></div>
        <div><p class="text-yellow-600 text-xs font-semibold">PRELOADED</p><p id="truckPreloaded" class="font-bold text-yellow-600">0</p></div>
        <div><p class="text-emerald-600 text-xs font-semibold">ON TRIP</p><p id="truckOnTrip" class="font-bold text-emerald-600">0</p></div>
      </div>
    </div>
    <div class="clickable bg-white rounded-2xl shadow-lg">
      <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-center py-4 rounded-t-2xl" onclick="showBreakdown('pendingtruck')">
        <h2 class="text-lg font-bold">PENDING JR</h2>
        <p id="truckPendingTotal" class="text-3xl font-extrabold">0</p>
      </div> 
      <div class="grid grid-cols-4 text-center py-2">
        <div><p class="text-blue-600 text-xs font-semibold">AT YARD</p><p id="truckAtYard" class="font-bold text-blue-600">0</p></div>
        <div><p class="text-yellow-600 text-xs font-semibold">OUTSIDE</p><p id="truckOutside" class="font-bold text-yellow-600">0</p></div>
        <div><p class="text-red-600 text-xs font-semibold">APPROVED</p><p id="truckApproved" class="font-bold text-red-600">0</p></div>
        <div><p class="text-red-600 text-xs font-semibold">FOR RESC</p><p id="truckForRescue" class="font-bold text-red-600">0</p></div>
      </div>
    </div>
    <div class="clickable bg-white rounded-2xl shadow-lg col-span-2">
      <div class="bg-gradient-to-r from-red-400 to-red-600 text-white text-center py-4 rounded-t-2xl" onclick="showBreakdown('ongoingtruck')">
        <h2 class="text-lg font-bold">ON GOING JR</h2>
        <p id="truckOngoingTotal" class="text-3xl font-extrabold">0</p>
      </div>
      <div class="grid grid-cols-4 text-center py-3">
        <div><p class="text-orange-600 font-semibold">ACCEPTED</p><p id="truckAccepted" class="font-bold text-orange-600">0</p></div>
        <div><p class="text-yellow-600 font-semibold">WFP</p><p id="truckWFP" class="font-bold text-yellow-600">0</p></div>
        <div><p class="text-red-600 font-semibold">NOT RELEASED</p><p id="truckNotReleased" class="font-bold text-red-600">0</p></div>
        <div><p class="text-red-600 font-semibold">ON RESC</p><p id="truckOnResc" class="font-bold text-red-600">0</p></div>
      </div>
    </div>
  </div>
</div>

<!-- TRAILER CARD -->
<div class="bg-gradient-to-r from-green-600 to-green-300 p-6 rounded-xl shadow-md text-white">
  <h2 class="text-xl font-bold">TRAILER</h2>
  <p id="trailerTotal" class="text-5xl font-extrabold mt-2">0</p>
  <div class="mt-6 grid grid-cols-2 gap-4">
    <div class="clickable bg-white rounded-2xl shadow-lg">
      <div class="bg-gradient-to-r from-green-400 to-green-600 text-white text-center py-4 rounded-t-2xl" onclick="showBreakdown('availabletrailer')">
        <h2 class="text-lg font-bold">AVAILABLE</h2>
        <p id="trailerAvailableTotal" class="text-3xl font-extrabold">0</p>
      </div>
      <div class="grid grid-cols-4 text-center py-2">
        <div><p class="text-orange-500 text-xs font-semibold">IDLE</p><p id="trailerIdle" class="font-bold text-orange-500">0</p></div>
        <div><p class="text-green-500 text-xs font-semibold">AVAILABLE</p><p id="trailerAvailable" class="font-bold text-green-500">0</p></div>
        <div><p class="text-yellow-600 text-xs font-semibold">PRELOADED</p><p id="trailerPreloaded" class="font-bold text-yellow-600">0</p></div>
        <div><p class="text-emerald-600 text-xs font-semibold">ON TRIP</p><p id="trailerOnTrip" class="font-bold text-emerald-600">0</p></div>
      </div>
    </div>
    <div class="clickable bg-white rounded-2xl shadow-lg">
      <div class="bg-gradient-to-r from-yellow-300 to-yellow-600 text-white text-center py-4 rounded-t-2xl" onclick="showBreakdown('pendingtrailer')">
        <h2 class="text-lg font-bold">PENDING JR</h2>
        <p id="trailerPendingTotal" class="text-3xl font-extrabold">0</p>
      </div>
      <div class="grid grid-cols-4 text-center py-2">
        <div><p class="text-blue-600 text-xs font-semibold">AT YARD</p><p id="trailerAtYard" class="font-bold text-blue-600">0</p></div>
        <div><p class="text-yellow-600 text-xs font-semibold">OUTSIDE</p><p id="trailerOutside" class="font-bold text-yellow-600">0</p></div>
        <div><p class="text-red-600 text-xs font-semibold">APPROVED</p><p id="trailerApproved" class="font-bold text-red-600">0</p></div>
        <div><p class="text-red-600 text-xs font-semibold">FOR RESC</p><p id="trailerForRescue" class="font-bold text-red-600">0</p></div>
      </div>
    </div>
    <div class="clickable bg-white rounded-2xl shadow-lg col-span-2">
      <div class="bg-gradient-to-r from-red-400 to-red-600 text-white text-center py-4 rounded-t-2xl" onclick="showBreakdown('ongoingtrailer')">
        <h2 class="text-lg font-bold">ON GOING JR</h2>
        <p id="trailerOngoingTotal" class="text-3xl font-extrabold">0</p>
      </div>
      <div class="grid grid-cols-4 text-center py-3">
        <div><p class="text-orange-600 font-semibold">ACCEPTED</p><p id="trailerAccepted" class="font-bold text-orange-600">0</p></div>
        <div><p class="text-yellow-600 font-semibold">WFP</p><p id="trailerWFP" class="font-bold text-yellow-600">0</p></div>
        <div><p class="text-red-600 font-semibold">NOT RELEASED</p><p id="trailerNotReleased" class="font-bold text-red-600">0</p></div>
        <div><p class="text-red-600 font-semibold">ON RESC</p><p id="trailerOnResc" class="font-bold text-red-600">0</p></div>
      </div>
    </div>
  </div>
</div>

<!-- DRIVER CARD -->
<div class="bg-gradient-to-r from-red-500 to-red-300 p-6 rounded-2xl shadow-md text-white">
  <h2 class="text-xl font-bold">DRIVER</h2>
  <p id="driverTotal" class="text-5xl font-extrabold mt-4">0</p>
  <div class="mt-6 grid grid-cols-2 gap-4">
    <div class="clickable bg-white text-gray-700 p-3 rounded-xl shadow text-center" onclick="showBreakdown('driveractive')">
      <p class="font-semibold">ACTIVE</p>
      <p id="driverActive" class="text-lg text-red-500">0</p>
    </div>
    <div class="clickable bg-white text-gray-700 p-3 rounded-xl shadow text-center" onclick="showBreakdown('driverinactive')">
      <p class="font-semibold">INACTIVE</p>
      <p id="driverInactive" class="text-lg text-red-500">0</p>
    </div>
    <div class="clickable bg-white rounded-2xl shadow-lg">
      <div id="count-present" class="bg-gradient-to-r from-green-400 to-green-600 text-white text-center py-4 rounded-t-2xl" onclick="showBreakdown('driverpresent')">
        <h2 class="text-lg font-bold">PRESENT</h2>
        <p id="driverPresentTotal" class="text-3xl font-extrabold">0</p>
      </div>
      <div class="grid grid-cols-3 text-center py-2">
        <div><p class="text-orange-500 text-xs font-semibold">RUNNING</p><p id="driverRunning" class="font-bold text-orange-500">0</p></div>
        <div><p class="text-green-500 text-xs font-semibold">AVAILABLE</p><p id="driverAvailable" class="font-bold text-green-500">0</p></div>
        <div><p class="text-yellow-600 text-xs font-semibold">REST</p><p id="driverRest" class="font-bold text-yellow-600">0</p></div>
      </div>
    </div>
    <div class="clickable bg-white rounded-2xl shadow-lg">
      <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-center py-4 rounded-t-2xl" onclick="showBreakdown('driverabsent')">
        <h2 class="text-lg font-bold">ABSENT</h2>
        <p id="driverAbsentTotal" class="text-3xl font-extrabold">0</p>
      </div>
      <div class="grid grid-cols-2 text-center py-2">
        <div><p class="text-orange-500 text-xs font-semibold">UR ABSENT</p><p id="driverURAbsent" class="font-bold text-orange-500">0</p></div>
        <div><p class="text-green-500 text-xs font-semibold">ABSENT</p><p id="driverAbsent" class="font-bold text-green-500">0</p></div>
      </div>
    </div>
  </div>
</div>
<div class="mt-6 w-full col-span-3"> 
  <h2 class="text-lg font-bold text-gray-700 mb-3">&nbsp;Breakdown</h2>
  <div id="breakdownSection" class="mt-6 hidden"> 
    <div id="breakdownGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
      <div id="breakdownSection2" class="mt-6 hidden"> 
    <div id="breakdownGrid2" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
      <!-- Filled dynamically -->
    </div>
  </div>
</div>
</div>
<!-- ✅ One shared Breakdown Modal -->
<div id="breakdownModal1" 
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" 
     onclick="outsideClick(event)">

  <div class="bg-white rounded-xl shadow-xl w-11/12 max-w-6xl p-0 relative animate-fadeIn"
       onclick="event.stopPropagation()">

    <button onclick="closeBreakdownModal1()" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">✕</button>

    <!-- ✅ Green header bar -->
    <div class="bg-gradient-to-r from-blue-400 to-blue-500 h-3 rounded-t-xl"></div>

    <!-- ✅ Modal title / SHOWING STATUS -->
    <div class="px-6 py-3 text-sm text-gray-700 flex items-center justify-between border-b">
      <div>
        <span class="font-semibold">SHOWING STATUS:</span>
        <span id="modalTitle" class="font-bold"></span>
      </div>
    </div>

    <!-- ✅ Modern Dropdowns + Search Section with total -->
    <div class="px-6 py-3 bg-gradient-to-r from-blue-100 via-blue-200 to-blue-100 flex items-center gap-4 flex-wrap border-b justify-between">
      
      <div class="flex items-center gap-3 flex-wrap w-full md:w-auto">
        <!-- TEAM Dropdown -->
        <div class="relative">
          <label for="teamFilter" class="sr-only">TEAM</label>
          <select id="teamFilter" 
                  class="block appearance-none w-full bg-white border border-gray-300 rounded-lg py-2 px-4 pr-8 text-sm text-gray-700 font-medium shadow-sm hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
            <option value="">TEAM</option>
            <option value="SBUO-1A">SBUO-1A</option>
            <option value="SBUO-1B">SBUO-1B</option>
            <option value="SBUO-1C">SBUO-1C</option>
            <option value="SBUO-1D">SBUO-1D</option>
            <option value="SBUO-2A">SBUO-2A</option>
            <option value="SBUO-2B">SBUO-2B</option>
            <option value="SBUO-3A">SBUO-3A</option>
            <option value="SBUO-4A">SBUO-4A</option>
            <option value="TM">TM</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <path d="M5.516 7.548l4.484 4.484 4.484-4.484z"/>
            </svg>
          </div>
        </div>

        <!-- UR STATUS Dropdown -->
        <div class="relative">
          <label for="statusFilter" class="sr-only">UR STATUS</label>
          <select id="statusFilter" 
                  class="block appearance-none w-full bg-white border border-gray-300 rounded-lg py-2 px-4 pr-8 text-sm text-gray-700 font-medium shadow-sm hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
            <option value="">UR Status</option>
            <option value="ACCEPTED">ACCEPTED</option>
            <option value="APPROVED">APPROVED</option>
            <option value="AT_YARD">AT YARD</option>
            <option value="AVAILABLE">AVAILABLE</option>
            <option value="IDLE">IDLE</option>
            <option value="NOT_RELEASED">NOT RELEASED</option>
            <option value="ON_TRIP">ON TRIP</option>
            <option value="OUTSIDE">OUTSIDE</option>
            <option value="WFP">WFP</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <path d="M5.516 7.548l4.484 4.484 4.484-4.484z"/>
            </svg>
          </div>
        </div>

        <!-- TAG Dropdown -->
        <div class="relative">
          <label for="tagFilter" class="sr-only">TAG</label>
          <select id="tagFilter" 
            class="block appearance-none w-full bg-white border border-gray-300 rounded-lg py-2 px-4 pr-8 text-sm text-gray-700 font-medium shadow-sm hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
            <option value="">TAG</option>
            <option value="Urgent">MINOR</option>
            <option value="Follow-Up">MAJOR</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <path d="M5.516 7.548l4.484 4.484 4.484-4.484z"/>
            </svg>
          </div>
        </div>

        <!-- SEARCH BOX -->
        <div class="relative flex-1 min-w-[250px]">
          <label for="searchInput" class="sr-only">SEARCH</label>
          <input type="text" id="searchInput" placeholder="Search HEAD, TRAILER, JR, JO" 
                 class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-4 text-sm md:text-base text-gray-700 font-medium shadow-sm hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
          <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- TOTAL Display -->
      <div class="flex items-center gap-1">
        <span class="font-semibold">TOTAL:</span> 
        <span id="modalValue" class="font-bold"></span>
      </div>

    </div>

    <!-- ✅ Modal Content -->
    <div class="px-6 pb-6">
      <div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg">
        <table id="modalTable" class="min-w-full text-sm text-left border-collapse">
          <thead id="modalTableHead"></thead>
          <tbody id="modalTableBody"></tbody>
        </table>
      </div>
    </div>

  </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", () => {
  loadData(); // load everything (truck, trailer, driver)
});

// --- Fetch data --- 
async function loadData() {
  try {
    const res = await fetch("/fetch-data");
    const json = await res.json();

    // Truck dataset
    json.result = (json.result || []).slice(1).map(mapFetchedRow);
    // Trailer dataset
    json.trailer_data = (json.trailer_data || []).slice(1).map(mapFetchedRow);
    // Driver dataset
    json.driver_data = (json.driver_data || []).slice(1).map(mapDriverRow);

    // Assign to globals
    truckRows   = json.result;        // truck dataset
    trailerRows = json.trailer_data;  // trailer dataset
    driverRows  = json.driver_data;   // driver dataset 

    // Update UI
    populateDashboard(json);
    updateDriverCards(json.driver_data || []); // driver dataset
    updateLastSynced();

    console.log("✅ All data loaded:", {
      trucks: truckRows.length,
      trailers: trailerRows.length,
      drivers: driverRows.length
    });
  } catch (e) {
    console.error("Error fetching:", e);
  }
}

// Sidebar functions
function openNav(){document.getElementById("mySidebar").classList.remove("closed"); document.getElementById("mySidebar").classList.add("open"); document.getElementById("overlay").classList.add("show");}
function closeNav(){document.getElementById("mySidebar").classList.remove("open"); document.getElementById("mySidebar").classList.add("closed"); document.getElementById("overlay").classList.remove("show");}
function updateLastSynced(){document.getElementById("lastSynced").textContent=new Date().toLocaleString();}

let truckRows = [];   // all truck data
let trailerRows = []; // all trailer data
const breakdownData = {
  availabletruck: [{label:"IDLE", value:0},{label:"AVAILABLE", value:0},{label:"PRELOADED", value:0},{label:"ON TRIP", value:0}],
  pendingtruck: [{label:"AT YARD", value:0},{label:"OUTSIDE", value:0},{label:"APPROVED", value:0},{label:"FOR RESC", value:0}],
  ongoingtruck: [{label:"ACCEPTED", value:0},{label:"WFP", value:0},{label:"NOT RELEASED", value:0},{label:"ON RESC", value:0}],
  availabletrailer: [{label:"IDLE", value:0},{label:"AVAILABLE", value:0},{label:"PRELOADED", value:0},{label:"ON TRIP", value:0}],
  pendingtrailer: [{label:"AT YARD", value:0},{label:"OUTSIDE", value:0},{label:"APPROVED", value:0},{label:"FOR RESC", value:0}],
  ongoingtrailer: [{label:"ACCEPTED", value:0},{label:"WFP", value:0},{label:"NOT RELEASED", value:0},{label:"ON RESC", value:0}],
  driverpresent: [], driverabsent:[]
};
// --- Normalize status strings ---
function normalizeStatus(s){ 
  if(!s) return ""; 
  s = s.trim().toUpperCase();
  if(s.includes("IDL")) return "IDLE"; 
  if(s.includes("AVAILABLE")) return "AVAILABLE";
  if(s.includes("PRELOADED")) return "PRELOADED"; 
  if(s.includes("TRIP")) return "ON TRIP";
  if(s.includes("YARD")) return "AT YARD"; 
  if(s.includes("OUTSIDE")) return "OUTSIDE";
  if(s.includes("APPROV")) return "APPROVED"; 
  if(s.includes("FOR RESC")) return "FOR RESC";
  if(s.includes("ACCEPTED")) return "ACCEPTED"; 
  if(s.includes("WFP")) return "WFP";
  if(s.includes("NOT RELEASED")) return "NOT RELEASED";
  if(s.includes("ON RESC")) return "ON RESC"; 
  return s; 
}
// --- ✅ Map row to object with normalized status (works for truck & trailer) ---
function mapFetchedRow(row){
  return {
    Unit: row[0] || "",
    Team: row[1] || "",
    // Truck has Brand (col 2), Trailer has Classification (col 2)
    BrandOrClassification: row[2] || "",
    Pair: row[3] || "",
    JRNumber: row[4] || "",
    JRStatus: row[5] || "",
    RequestStatus: row[6] || "",
    RepairLocation: row[7] || "",
    Tag: row[8] || "",
    JRAge: row[9] || "",
    ApprovalAge: row[10] || "",
    JONum: row[11] || "",
    JOActivity: row[12] || "",
    ETR: row[13] || "",
    PartsETA: row[14] || "",
    Status: normalizeStatus(row[15] || ""),   // ✅ UR STATUS
    LastLocation: row[16] || "",
    LastUpdate: row[17] || "",
    Yard: row[18] || "",
    AtYardTimestamp: row[19] || "",
    ToAYard: row[20] || "",
    SBUOStatus: row[21] || "",
    OE: row[22] || "",
    OpsRemarks: row[23] || "",
    // Trailer-only fields (safe for truck: will just be "")
    Axle: row[24] || "",
    SubEngine: row[25] || "",
    GPSStatus: row[26] || "",
    TripStatus: row[27] || "",
    ForRescue: row[28] || "",
    JORemarks: row[29] || "",
    PRRemarks: row[30] || ""
  };
}
// --- Dashboard card config ---
const cards = [
  {id: "truck",statuses: ["IDLE","AVAILABLE","PRELOADED","ON TRIP","AT YARD","OUTSIDE","APPROVED","FOR RESC","ACCEPTED","WFP","NOT RELEASED","ON RESC"]},
  {id: "trailer",statuses: ["IDLE","AVAILABLE","PRELOADED","ON TRIP","AT YARD","OUTSIDE","APPROVED","FOR RESC","ACCEPTED","WFP","NOT RELEASED","ON RESC"]}];
// --- Helper to avoid NaN ---
function safeCount(counts, key){ return counts[key] || 0;}
// --- Populate cards ---
function populateDashboard(data){
  cards.forEach(c=>{
    const ds = c.id === "truck" ? data.result : data.trailer_data;
    const counts = {};
    c.statuses.forEach(s=>counts[s]=0);
    ds.forEach(r=>{
      const s = r.Status;
      if(counts.hasOwnProperty(s)) counts[s]++;
    });
    // Update totals
    document.getElementById(`${c.id}Total`).textContent = ds.length;
    if(c.id==="truck"){
      breakdownData.availabletruck[0].value = safeCount(counts,"IDLE");
      breakdownData.availabletruck[1].value = safeCount(counts,"AVAILABLE");
      breakdownData.availabletruck[2].value = safeCount(counts,"PRELOADED");
      breakdownData.availabletruck[3].value = safeCount(counts,"ON TRIP");
      breakdownData.pendingtruck[0].value = safeCount(counts,"AT YARD");
      breakdownData.pendingtruck[1].value = safeCount(counts,"OUTSIDE");
      breakdownData.pendingtruck[2].value = safeCount(counts,"APPROVED");
      breakdownData.pendingtruck[3].value = safeCount(counts,"FOR RESC");
      breakdownData.ongoingtruck[0].value = safeCount(counts,"ACCEPTED");
      breakdownData.ongoingtruck[1].value = safeCount(counts,"WFP");
      breakdownData.ongoingtruck[2].value = safeCount(counts,"NOT RELEASED");
      breakdownData.ongoingtruck[3].value = safeCount(counts,"ON RESC");
      
      // ✅ Subtotals
      document.getElementById("truckIdle").textContent = safeCount(counts,"IDLE");
      document.getElementById("truckAvailable").textContent = safeCount(counts,"AVAILABLE");
      document.getElementById("truckPreloaded").textContent = safeCount(counts,"PRELOADED");
      document.getElementById("truckOnTrip").textContent = safeCount(counts,"ON TRIP");
      document.getElementById("truckAtYard").textContent = safeCount(counts,"AT YARD");
      document.getElementById("truckOutside").textContent = safeCount(counts,"OUTSIDE");
      document.getElementById("truckApproved").textContent = safeCount(counts,"APPROVED");
      document.getElementById("truckForRescue").textContent = safeCount(counts,"FOR RESC");
      document.getElementById("truckAccepted").textContent = safeCount(counts,"ACCEPTED");
      document.getElementById("truckWFP").textContent = safeCount(counts,"WFP");
      document.getElementById("truckNotReleased").textContent = safeCount(counts,"NOT RELEASED");
      document.getElementById("truckOnResc").textContent = safeCount(counts,"ON RESC");

      // ✅ Auto-calc totals
      document.getElementById("truckAvailableTotal").textContent =
        safeCount(counts,"IDLE") + safeCount(counts,"AVAILABLE") + safeCount(counts,"PRELOADED") + safeCount(counts,"ON TRIP");
      document.getElementById("truckPendingTotal").textContent =
        safeCount(counts,"AT YARD") + safeCount(counts,"OUTSIDE") + safeCount(counts,"APPROVED") + safeCount(counts,"FOR RESC");
      document.getElementById("truckOngoingTotal").textContent =
        safeCount(counts,"ACCEPTED") + safeCount(counts,"WFP") + safeCount(counts,"NOT RELEASED") + safeCount(counts,"ON RESC");
    } 
    
    else if(c.id==="trailer"){
      breakdownData.availabletrailer[0].value = safeCount(counts,"IDLE");
      breakdownData.availabletrailer[1].value = safeCount(counts,"AVAILABLE");
      breakdownData.availabletrailer[2].value = safeCount(counts,"PRELOADED");
      breakdownData.availabletrailer[3].value = safeCount(counts,"ON TRIP");

      breakdownData.pendingtrailer[0].value = safeCount(counts,"AT YARD");
      breakdownData.pendingtrailer[1].value = safeCount(counts,"OUTSIDE");
      breakdownData.pendingtrailer[2].value = safeCount(counts,"APPROVED");
      breakdownData.pendingtrailer[3].value = safeCount(counts,"FOR RESC");

      breakdownData.ongoingtrailer[0].value = safeCount(counts,"ACCEPTED");
      breakdownData.ongoingtrailer[1].value = safeCount(counts,"WFP");
      breakdownData.ongoingtrailer[2].value = safeCount(counts,"NOT RELEASED");
      breakdownData.ongoingtrailer[3].value = safeCount(counts,"ON RESC");

      document.getElementById("trailerIdle").textContent = safeCount(counts,"IDLE");
      document.getElementById("trailerAvailable").textContent = safeCount(counts,"AVAILABLE");
      document.getElementById("trailerPreloaded").textContent = safeCount(counts,"PRELOADED");
      document.getElementById("trailerOnTrip").textContent = safeCount(counts,"ON TRIP");
      document.getElementById("trailerAvailableTotal").textContent =
        safeCount(counts,"IDLE") + safeCount(counts,"AVAILABLE") + safeCount(counts,"PRELOADED") + safeCount(counts,"ON TRIP");

      // --- ✅ Pending JR ---
      breakdownData.pendingtrailer[0].value = safeCount(counts,"AT YARD");
      breakdownData.pendingtrailer[1].value = safeCount(counts,"OUTSIDE");
      breakdownData.pendingtrailer[2].value = safeCount(counts,"APPROVED");
      breakdownData.pendingtrailer[3].value = safeCount(counts,"FOR RESC");

      document.getElementById("trailerAtYard").textContent = safeCount(counts,"AT YARD");
      document.getElementById("trailerOutside").textContent = safeCount(counts,"OUTSIDE");
      document.getElementById("trailerApproved").textContent = safeCount(counts,"APPROVED");
      document.getElementById("trailerForRescue").textContent = safeCount(counts,"FOR RESC");
      document.getElementById("trailerPendingTotal").textContent =
        safeCount(counts,"AT YARD") + safeCount(counts,"OUTSIDE") + safeCount(counts,"APPROVED") + safeCount(counts,"FOR RESC");

      // --- ✅ On Going JR ---
      breakdownData.ongoingtrailer[0].value = safeCount(counts,"ACCEPTED");
      breakdownData.ongoingtrailer[1].value = safeCount(counts,"WFP");
      breakdownData.ongoingtrailer[2].value = safeCount(counts,"NOT RELEASED");
      breakdownData.ongoingtrailer[3].value = safeCount(counts,"ON RESC");

      document.getElementById("trailerAccepted").textContent = safeCount(counts,"ACCEPTED");
      document.getElementById("trailerWFP").textContent = safeCount(counts,"WFP");
      document.getElementById("trailerNotReleased").textContent = safeCount(counts,"NOT RELEASED");
      document.getElementById("trailerOnResc").textContent = safeCount(counts,"ON RESC");
      document.getElementById("trailerOngoingTotal").textContent =
        safeCount(counts,"ACCEPTED") + safeCount(counts,"WFP") + safeCount(counts,"NOT RELEASED") + safeCount(counts,"ON RESC");
    }
  });
}
function showBreakdown(type){
  const section=document.getElementById("breakdownSection");
  const grid=document.getElementById("breakdownGrid");
  grid.innerHTML="";
  if(!breakdownData[type]) return;

  // 🎨 Color by type
  let colorClass = "from-blue-400 to-blue-600"; // truck default
  if(type.includes("trailer")) colorClass = "from-green-400 to-green-600";
  if(type.includes("driver")) colorClass = "from-red-400 to-red-600";

  breakdownData[type].forEach(item=>{
    grid.innerHTML+=`
      <div 
        onclick="openBreakdownModal1('${item.label}', '${item.value}', '${type}')"
        class="cursor-pointer text-white p-4 rounded-xl shadow-lg text-center font-bold bg-gradient-to-r ${colorClass}">
        <p class="text-sm">${item.label}</p>
        <p class="text-2xl">${item.value}</p>
      </div>`;
  });

  section.classList.remove("hidden");
}

// Wrap the original data arrays with Proxy to detect changes
function makeReactive(array, onChange) {
  return new Proxy(array, {
    set(target, prop, value) {
      target[prop] = value;
      onChange(); // call refresh function whenever array changes
      return true;
    },
    deleteProperty(target, prop) {
      delete target[prop];
      onChange();
      return true;
    }
  });
}

 function loadDriverData(rawDriverData) {
    driverRows = (rawDriverData || []).slice(1).map(mapDriverRow);
    console.log("Driver rows mapped:", driverRows);
  }
// ================= MAP DRIVER ROW =================
function mapDriverRow(row) {
  return {
    Tractor: row[0] || "",
    Trailer: row[1] || "",
    TeamTMS: row[2] || "",
    Driver: row[3] || "",
    Helper: row[4] || "",
    Team: row[5] || "",
    TripStatus: (row[6] || "").trim().toUpperCase(),
    Attendance: (row[7] || "").trim().toUpperCase(),
    AssignedTrip: row[8] || "",
    Remarks: row[9] || "",
    LastUpdated: row[10] || "",
    PairedTrailer: row[11] || "",
    TractorJR: row[12] || "",
    TractorUR: (row[13] || "").trim().toUpperCase(),
    TrailerJR: row[14] || "",
    TrailerUR: (row[15] || "").trim().toUpperCase(),
    StatusAge: row[16] || ""
  };
}


  let driverRows = [];

let currentModal = null; // store current open modal info


// ================= OPEN MODAL =================
function openBreakdownModal1(label, value, type) {
  const modal = document.getElementById("breakdownModal1");
  const thead = document.getElementById("modalTableHead");

  modal.classList.remove("hidden", "fade-out");
  modal.classList.add("flex");

  document.getElementById("modalTitle").textContent = `${label} (${type.toUpperCase()})`;

  // Match the keys in mapDriverRow exactly
  const modalDriverHeaders = [
    "Tractor", "Trailer", "TeamTMS", "Driver", "Helper", "Team",
    "TripStatus", "Attendance", "AssignedTrip", "Remarks", "LastUpdated",
    "PairedTrailer", "TractorJR", "TractorUR", "TrailerJR", "TrailerUR", "StatusAge"
  ];

  const modalTruckTrailerHeaders = [
    "Unit","Team","BrandOrClassification","Pair","JRNumber","JRStatus","RequestStatus","RepairLocation",
    "Tag","JRAge","ApprovalAge","JONum","JOActivity","ETR","PartsETA","Status",
    "LastLocation","LastUpdate","Yard","AtYardTimestamp","ToAYard","SBUOStatus",
    "OE","OpsRemarks","GPSStatus","TripStatus","ForRescue","JORemarks"
  ];

  const headers = type.includes("driver") ? modalDriverHeaders : modalTruckTrailerHeaders;

  currentModal = { label, type, headers };

  // Build header row
  thead.innerHTML = "";
  const trHead = document.createElement("tr");
  headers.forEach(h => {
    const th = document.createElement("th");
    th.textContent = h;
    trHead.appendChild(th);
  });
  thead.appendChild(trHead);

  refreshModalIfOpen();
}


// ================= REFRESH MODAL =================
function refreshModalIfOpen() {
  if (!currentModal) return;

  const { label, type, headers } = currentModal;
  const tbody = document.getElementById("modalTableBody");
  const modalValue = document.getElementById("modalValue");

  tbody.innerHTML = "";
  let filteredRows = [];

  if (type.includes("driver")) {
    const lbl = (label || "").trim().toUpperCase();

    const mapping = {
      "RUNNING":          { field: "TripStatus", value: "RUNNING" },
      "AVAILABLE":        { field: "TripStatus", value: "AVAILABLE" },
      "DRIVER PREPARING": { field: "TripStatus", value: "DRIVER PREPAIRING" },
      "DRIVER AVAILABLE": { field: "TripStatus", value: "UR AVAILABLE DRIVER" },
      "FOR RESCUE":       { field: "TripStatus", value: "FOR RESCUE" },
      "UR DRIVER":        { field: "TripStatus", value: "UR DRIVER" },
      "UR REST":          { field: "TripStatus", value: "UR REST" },
      "REST":             { field: "TripStatus", value: "REST" },
      "UR ABSENT":        { field: "TripStatus", value: "UR ABSENT" },

      "ABSENT":           { field: "Attendance", value: "ABSENT" },
      "PRESENT":          { field: "Attendance", value: "PRESENT" },

      "SICK LEAVE":       { field: "TripStatus", value: "SICK LEAVE" },
      "SUSPENDED":        { field: "TripStatus", value: "SUSPENDED" },
      "VACATION LEAVE":   { field: "TripStatus", value: "VACATION LEAVE" },
      "HOLD":             { field: "TripStatus", value: "HOLD" },
      "AWOL ALERT":       { field: "TripStatus", value: "AWOL ALERT" }
    };

    const mapEntry = mapping[lbl];
    if (mapEntry) {
      filteredRows = driverRows.filter(r => {
        const val = (r[mapEntry.field] || "").trim().toUpperCase();
        return val === mapEntry.value;
      });
    }
  } else if (type.includes("truck")) {
    filteredRows = truckRows.filter(
      r => (r.Status || "").trim().toUpperCase() === (label || "").trim().toUpperCase()
    );
  } else if (type.includes("trailer")) {
    filteredRows = trailerRows.filter(
      r => (r.Status || "").trim().toUpperCase() === (label || "").trim().toUpperCase()
    );
  }

  modalValue.textContent = `Rows: ${filteredRows.length} (Showing ${filteredRows.length})`;


  if (filteredRows.length === 0) {
    const tr = document.createElement("tr");
    const td = document.createElement("td");
    td.colSpan = headers.length;
    td.className = "text-center py-4";
    td.textContent = "No data available";
    tr.appendChild(td);
    tbody.appendChild(tr);
  } else {
    filteredRows.forEach(r => {
      const tr = document.createElement("tr");
      tr.className = "border-b hover:bg-gray-50";
      headers.forEach(h => {
        const td = document.createElement("td");
        td.className = "px-3 py-2 border";
        td.textContent = r[h] ?? "";   // ✅ this is the correct placement
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });
  }

  console.log("Modal refreshed:", { label, type, count: filteredRows.length });
}

// ================= REACTIVE ARRAYS =================
driverRows  = makeReactive(driverRows, refreshModalIfOpen);
truckRows   = makeReactive(truckRows, refreshModalIfOpen);
trailerRows = makeReactive(trailerRows, refreshModalIfOpen);

// ================= CLOSE MODAL =================
function closeBreakdownModal1() {
  const modal = document.getElementById("breakdownModal1");
  if (!modal) return;

  modal.classList.add("fade-out");
  setTimeout(() => {
    modal.classList.add("hidden");
    modal.classList.remove("flex", "fade-out");
    document.getElementById("modalTableBody").innerHTML = "";
    document.getElementById("modalTableHead").innerHTML = "";
    document.getElementById("modalValue").textContent = "";
    currentModal = null;
  }, 200);
}


// Close modal when clicking outside
function outsideClick(e) {
  if (e.target.id === "breakdownModal1") {
    closeBreakdownModal1();

    // Force reset after fade-out (just in case)
    setTimeout(() => {
      const tbody = document.getElementById("modalTableBody");
      const thead = document.getElementById("modalTableHead");
      const modalValue = document.getElementById("modalValue");

      tbody.innerHTML = "";
      thead.innerHTML = "";
      modalValue.textContent = "";

      currentModal = null; // reset modal state
    }, 200); // match CSS closing animation
  }
}
// Listen for ESC key
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    const modal = document.getElementById("breakdownModal1");
    if (modal && !modal.classList.contains("hidden")) {
      closeBreakdownModal1();
    }
  }
});


// Driver
function updateDriverCards(data){
  const counts={present:0,ur_absent:0,absent:0,running:0,available:0,rest:0,driver_preparing:0,driver_available:0,
    for_rescue:0,ur_driver:0,ur_rest:0,sick_leave:0,suspended:0,vacation_leave:0,hold:0,awol:0};

   data.forEach(row => {
    const trip = (row.TripStatus || "").toUpperCase().trim();
    const att  = (row.Attendance || "").toUpperCase().trim();
    
     switch(trip){
      case "ABSENT": counts.absent++; break;
      case "AVAILABLE": counts.available++; break;
      case "DRIVER PREPAIRING": counts.driver_preparing++; break;
      case "REST": counts.rest++; break;
      case "RUNNING": counts.running++; break;
      case "SICK LEAVE": counts.sick_leave++; break;
      case "SUSPENDED": counts.suspended++; break;
      case "UR ABSENT": counts.ur_absent++; break;
      case "UR AVAILABLE DRIVER": counts.driver_available++; break;
      case "UR REST": counts.ur_rest++; break;
      case "VACATION LEAVE": counts.vacation_leave++; break;
      case "FOR RESCUE": counts.for_rescue++; break;
      case "UR DRIVER": counts.ur_driver++; break;
      case "HOLD": counts.hold++; break;
      case "AWOL ALERT": counts.awol++; break;
 
    }
     switch(att){
      case "PRESENT": counts.present++; break;

    }
    });
  document.getElementById("driverTotal").textContent = 
    (counts.running + counts.ur_absent + counts.absent + counts.available + counts.driver_preparing + counts.driver_available + counts.for_rescue + counts.ur_driver + counts.ur_rest + counts.rest) +
    (counts.sick_leave + counts.suspended + counts.vacation_leave + counts.hold + counts.awol);
  document.getElementById("driverAbsentTotal").textContent=counts.absent + counts.ur_absent;
   document.getElementById("driverActive").textContent=counts.running + counts.ur_absent + counts.absent + counts.available + counts.driver_preparing + counts.driver_available + counts.for_rescue  + counts.ur_driver + counts.ur_rest + counts.rest;
  document.getElementById("driverInactive").textContent=counts.sick_leave + counts.suspended + counts.vacation_leave + counts.hold + counts.awol;
  document.getElementById("driverPresentTotal").textContent=counts.present;
  document.getElementById("driverRunning").textContent=counts.running;
  document.getElementById("driverAvailable").textContent=counts.available;
  document.getElementById("driverRest").textContent=counts.rest;
  document.getElementById("driverURAbsent").textContent=counts.ur_absent;
  document.getElementById("driverAbsent").textContent=counts.absent;

  breakdownData.driveractive=[

    {label:"RUNNING",value:counts.running,color:"from-red-400 to-red-600"},
    {label:"AVAILABLE",value:counts.available,color:"from-green-400 to-green-600"},
    {label:"DRIVER PREPARING",value:counts.driver_preparing,color:"from-red-400 to-red-600"},
    {label:"DRIVER AVAILABLE",value:counts.driver_available,color:"from-green-400 to-green-600"},
    {label:"FOR RESCUE",value:counts.for_rescue,color:"from-red-400 to-red-600"},
    {label:"UR DRIVER",value:counts.ur_driver,color:"from-red-400 to-red-600"},
    {label:"UR REST",value:counts.ur_rest,color:"from-red-400 to-red-600"},
    {label:"REST",value:counts.rest,color:"from-yellow-400 to-yellow-600"},
        {label:"UR ABSENT",value:counts.ur_absent,color:"from-red-400 to-red-600"},
    {label:"ABSENT",value:counts.absent,color:"from-red-400 to-red-600"},
  ];
    breakdownData.driverinactive=[
    {label:"SICK LEAVE",value:counts.sick_leave,color:"from-red-400 to-red-600"},
    {label:"SUSPENDED",value:counts.suspended,color:"from-red-400 to-red-600"},
    {label:"VACATION LEAVE",value:counts.vacation_leave,color:"from-red-400 to-red-600"},
    {label:"HOLD",value:counts.hold,color:"from-red-400 to-red-600"},
    {label:"AWOL ALERT",value:counts.awol,color:"from-green-400 to-green-600"},
  ];

  breakdownData.driverpresent=[
    {label:"RUNNING",value:counts.running,color:"from-red-400 to-red-600"},
    {label:"AVAILABLE",value:counts.available,color:"from-green-400 to-green-600"},
    {label:"REST",value:counts.rest,color:"from-yellow-400 to-yellow-600"},
    {label:"DRIVER PREPARING",value:counts.driver_preparing,color:"from-red-400 to-red-600"},
    {label:"DRIVER AVAILABLE",value:counts.driver_available,color:"from-green-400 to-green-600"},
    {label:"FOR RESCUE",value:counts.for_rescue,color:"from-red-400 to-red-600"},
    {label:"UR DRIVER",value:counts.ur_driver,color:"from-red-400 to-red-600"},
    {label:"UR REST",value:counts.ur_rest,color:"from-red-400 to-red-600"}];
  breakdownData.driverabsent=[
    {label:"UR ABSENT",value:counts.ur_absent,color:"from-red-400 to-red-600"},
    {label:"ABSENT",value:counts.absent,color:"from-red-400 to-red-600"}];
}

// Trigger the first data load
loadData();
setInterval(loadData, 300000);
</script>
</body>
</html>