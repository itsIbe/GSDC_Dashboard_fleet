@php
use App\Models\AuditLog;
AuditLog::create([
    'user_id' => Auth::id(),
    'action' => 'Driver Capacity Dashboard',
    'ip_address' => request()->ip(),
    'description' => 'Viewed Driver Capacity Dashboard',
]);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Driver Capacity Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
  body { font-family: 'Poppins', sans-serif; background-color: #f4f6ff; }
  .sidebar-menu {
    position: fixed; top: 0; left: 0; height: 100%;
    width: 250px; background: linear-gradient(to bottom, #2563eb, #1d4ed8);
    color: white; transition: all 0.3s; z-index: 50;
  }
  .sidebar-menu.closed { left: -250px; }
  .sidebar-menu.open { left: 0; }
  .overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; z-index: 40; }
  .overlay.show { display: block; }
  .clickable { cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; }
  .clickable:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 8px 16px rgba(0,0,0,0.2); }
  .clickable:active { transform: scale(0.98); }
  #cardModal {
  display: none; 
  position: fixed;
  inset: 0; 
  background-color: rgba(0,0,0,0.5);
  z-index: 50;
  align-items: center;
  justify-content: center;
  overflow-y: auto;
  padding: 1rem; /* slightly smaller padding so modal can grow */
  transition: opacity 0.3s ease;
  opacity: 0;
}

/* Show modal with fade-in */
#cardModal.flex {
  display: flex;
  opacity: 1;
}
/* Modal content */

/* Table wrapper */
#cardModal .table-container {
  width: 95%;          /* almost full width */
  max-width: 1400px;   /* cap for very large screens */
  max-height: 1400px;
  overflow: auto;      /* scroll if content exceeds height */
  border-radius: 0.75rem; 
  border: 1px solid #6e9dfcff;
  box-shadow: 0 10px 30px rgba(0,0,0,0.25); 
  background-color: #fff;
  padding: 1.5rem;     /* comfortable spacing */
}



/* Table */
#cardModal table {  
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}
/* Hidden state */
#cardModal.hidden {
  display: none;
}

/* Visible modal */
#cardModal.flex {
  display: flex;
  opacity: 1;
  transition: opacity 0.2s ease;
}

/* Fade out */
#cardModal.fade-out {
  opacity: 0;
}

/* Header cells */
#cardModal th {
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
#cardModal th:first-child { background-color: #004183ff; }
#cardModal td:first-child { background-color: #ffffff; }
/* First column sticky */
#cardModal th:first-child,
#cardModal td:first-child {
  position: sticky;
  left: 0;
  z-index: 6;
  box-shadow: 2px 0 5px rgba(0,0,0,0.05); /* divider shadow for sticky col */
}
#cardModal tbody tr:nth-child(even) {
  background-color: #f9fafb;

}
/* Top-left corner */
#cardModal th:first-child {
  border-top-left-radius: 0.75rem;
  z-index: 7;
}

/* Top-right corner */
#cardModal th:last-child {
  border-top-right-radius: 0.75rem;
}

/* Bottom-left corner */
#cardModal tbody tr:last-child td:first-child {
  border-bottom-left-radius: 0.75rem;
}

/* Bottom-right corner */
#cardModal tbody tr:last-child td:last-child {
  border-bottom-right-radius: 0.75rem;
}


/* Row hover */
#cardModal tbody tr:hover {
  background-color: #9EC6F3;
  cursor: pointer;
}
</style>
</head>

<body>

<!-- Sidebar -->
<div id="mySidebar" class="sidebar-menu closed p-4">
  <h1 class="text-center font-bold text-white text-lg mb-6">
    Great Sierra Development Corporation
  </h1>

  <div class="flex flex-col items-center mb-6">
    <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}" class="w-16 h-16 rounded-full border-4 border-white shadow mb-3">
    <p class="font-semibold">{{ Auth::user()->display_name }}</p>
    <p class="text-sm text-gray-200">{{ Auth::user()->position ?? 'Employee' }}</p>
  </div>

  @php $user = auth()->user(); @endphp
  <a href="{{ url('/home') }}" class="block py-2 hover:bg-white hover:text-blue-600 px-4 rounded"><i class="fa fa-home"></i> Home</a>
  <a href="{{ url('/fleetcapacitydashboard') }}" class="block py-2 hover:bg-white hover:text-blue-600 px-4 rounded"><i class="fa fa-chart-bar"></i> Fleet Dashboard</a>
  <a href="{{ url('/drivercapacitydashboard') }}" class="block py-2 bg-blue-900 px-4 rounded"><i class="fa fa-truck"></i> Driver Dashboard</a>
  @if($user->isAdmin())
  <a href="{{ url('/createuser') }}" class="block py-2 hover:bg-white hover:text-blue-600 px-4 rounded"><i class="fa fa-user-plus"></i> Create User</a>
  <a href="{{ route('audit.index') }}" class="block py-2 hover:bg-white hover:text-blue-600 px-4 rounded"><i class="fa fa-list"></i> Audit Logs</a>
  @endif

  <form method="POST" action="{{ route('logout') }}" class="mt-6">
    @csrf
    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 py-2 rounded-md">
      <i class="fa fa-sign-out-alt"></i> Logout
    </button>
  </form>
</div>
<div id="overlay" class="overlay" onclick="closeNav()"></div>
<button class="fixed top-4 left-4 z-50 bg-blue-600 text-white px-3 py-2 rounded-full shadow-lg hover:bg-blue-700 transition text-2xl" onclick="openNav()">&#9776;</button>
<!-- ✅ Responsive Fixed Top Bar -->
<header class="fixed top-0 left-0 w-full bg-white shadow-sm border-b z-40 flex flex-col md:flex-row md:items-center md:justify-between px-4 md:px-6 py-3 md:py-4 gap-1">
  <!-- Optional: Left placeholder for sidebar button on desktop -->
  <div class="hidden md:block w-10"></div>
  <!-- Center: Title + Last Synced -->
  <div class="flex-1 text-center">
    <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-500">
      CARGO CAPACITY DASHBOARD
    </h1>
    <p class="text-xs sm:text-sm text-gray-500 mt-1">
      Last synced: <span id="lastSynced"></span>
    </p>
  </div>
  <!-- Optional: Right placeholder for spacing -->
  <div class="hidden md:block w-10"></div>
</header>
<!-- MAIN CONTENT -->
<div class="pt-24 md:pt-28 flex flex-col md:flex-row items-start gap-6 p-4">
  <!-- LEFT PANEL (CARD STYLE) -->
  <div class="bg-gradient-to-b from-[#b1c5ff] to-[#66a1f7] text-blue-800 rounded-2xl p-5 shadow-lg md:w-1/3 w-full">
    <!-- TOTAL -->
    <div class="bg-white rounded-2xl py-3 shadow-sm mb-4 flex justify-between items-center px-4">
      <h2 class="text-xl font-extrabold text-blue-700">TOTAL</h2>
      <span class="text-4xl font-extrabold text-blue-700">193</span>
    </div>
    <!-- ACTIVE -->
    <div class="bg-white rounded-2xl p-4 shadow-sm mb-4">
      <div class="text-left space-y-1 mb-2">
        <p class="text-blue-700 font-extrabold">ACTIVE <span class="float-right text-orange-600 ">184</span></p>
      <div class="border-t border-orange-500 text-xs text-blue-700 font-semibold mt-3 text-center"></div>
        <p class="text-blue-700 font-semibold">PRESENT <span class="float-right font-extrabold text-orange-600">166</span></p>
        <p class="text-blue-700 font-semibold">ABSENT <span class="float-right font-extrabold text-orange-600">18</span></p>
      </div>
      <div class="border-t border-orange-500 text-xs text-blue-700 font-semibold mt-3 text-center">BREAKDOWN</div>
      <div class="grid grid-cols-3 gap-2 mt-3">
        <div class="clickable bg-[#e3e4ff] rounded-xl p-2">
          <p class="text-blue-700 text-center font-bold text-sm">RUNNING</p>
          <p class="text-orange-600 text-center text-2xl font-extrabold">151</p>
        </div>
        <div class="clickable bg-[#e3e4ff] rounded-xl p-2">
          <p class="text-blue-700 text-center font-bold text-sm">AVAILABLE</p>
          <p class="text-orange-600 text-center text-2xl font-extrabold">1</p>
        </div>
        <div class="clickable bg-[#e3e4ff] rounded-xl p-2">
          <p class="text-blue-700 text-center font-bold text-sm">PNT</p>
          <p class="text-orange-600 text-center text-2xl font-extrabold">0</p>
        </div>
        <div class="clickable bg-[#e3e4ff] rounded-xl p-2">
          <p class="text-blue-700 text-center font-bold text-sm">PRESENT-UR</p>
          <p class="text-orange-600 text-center text-2xl font-extrabold">0</p>
        </div>
        <div class="clickable bg-[#e3e4ff] rounded-xl p-2">
          <p class="text-blue-700 text-center font-bold text-sm">PRESENT-AWAITING TRIP</p>
          <p class="text-orange-600 text-center text-2xl font-extrabold">0</p>
        </div>
        <div class="clickable bg-[#e3e4ff] rounded-xl p-2">
          <p class="text-blue-700 text-center font-bold text-sm">RESTDAY</p>
          <p class="text-orange-600 text-center text-2xl font-extrabold">0</p>
        </div>
      </div>
    </div>
    <!-- INACTIVE -->
    <div class="bg-white rounded-2xl p-4 shadow-sm">
      <div class="text-left mb-2">
        <p class="text-blue-700 font-extrabold">INACTIVE <span class="float-right">9</span></p>
      </div>
      <div class="border-t border-orange-500 text-xs text-blue-700 font-semibold mt-3 text-center">BREAKDOWN</div>
      <div class="grid grid-cols-3 gap-2 mt-3">
        <div class="clickable bg-[#e3e4ff] rounded-xl p-2">
          <p class="text-blue-700 text-center font-bold text-sm">LEAVE</p>
          <p class="text-orange-600 text-center text-2xl font-extrabold">5</p>
        </div>
        <div class="clickable bg-[#e3e4ff] rounded-xl p-2">
          <p class="text-blue-700 text-center font-bold text-sm">SUSPENDED</p>
          <p class="text-orange-600 text-center text-2xl font-extrabold">1</p>
        </div>
        <div class="clickable bg-[#e3e4ff] rounded-xl p-2">
          <p class="text-blue-700 text-center font-bold text-sm">AWOL ALERT</p>
          <p class="text-orange-600 text-center text-2xl font-extrabold">3</p>
        </div>
      </div>
    </div>
  </div>
<!-- ✅ Full Breakdown Modal (LIKE IN HOME MODAL) -->
<div id="cardModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
     onclick="closeModalOutside(event)">
 <div class="bg-white rounded-xl shadow-xl w-11/13 max-w-10xl p-0 relative animate-fadeIn
              max-h-[100vh] overflow-y-auto"
       onclick="event.stopPropagation()">
    <!-- ✕ Close Button -->
    <button onclick="closeModal()"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl">✕</button>
    <!-- ✅ Blue Header Bar -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-t-xl"></div>
    <!-- ✅ Modal Title -->
    <div class="px-6 py-3 flex justify-between items-center border-b bg-blue-50 text-sm text-gray-700">
      <div>
        <span class="font-semibold">SHOWING STATUS:</span>
        <span id="modalTitle" class="font-bold text-blue-700">RUNNING</span>
      </div>
      <div class="font-semibold">
        TOTAL: Rows: <span id="totalRows" class="font-bold text-blue-700">151</span> 
        <span class="text-gray-500">(Showing 151)</span>
      </div>
    </div>
    <!-- ✅ Filters Section -->
    <div class="px-6 py-3 bg-gradient-to-r from-blue-100 via-blue-200 to-blue-100 flex flex-wrap gap-3 border-b items-center justify-between">
      <div class="flex flex-wrap gap-3 items-center">
        <!-- TEAM Dropdown -->
        <div class="relative">
          <select id="teamFilter"
                  class="appearance-none bg-white border border-gray-300 rounded-lg py-2 px-4 pr-8 text-sm font-medium shadow-sm hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
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
            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.516 7.548l4.484 4.484 4.484-4.484z"/></svg>
          </div>
        </div>
        <!-- UR Status Dropdown -->
        <div class="relative">
          <select id="statusFilter"
              class="appearance-none bg-white border border-gray-300 rounded-lg py-2 px-4 pr-8 text-sm font-medium shadow-sm hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
            <option value="">UR Status</option>
            <option value="ACCEPTED">ACCEPTED</option>
            <option value="APPROVED">APPROVED</option>
            <option value="RUNNING">RUNNING</option>
            <option value="AVAILABLE">AVAILABLE</option>
            <option value="ON TRIP">ON TRIP</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.516 7.548l4.484 4.484 4.484-4.484z"/></svg>
          </div>
        </div>
        <!-- TAG Dropdown -->
        <div class="relative">
          <select id="tagFilter"
                  class="appearance-none bg-white border border-gray-300 rounded-lg py-2 px-4 pr-8 text-sm font-medium shadow-sm hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
            <option value="">TAG</option>
            <option value="Minor">MINOR</option>
            <option value="Major">MAJOR</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.516 7.548l4.484 4.484 4.484-4.484z"/></svg>
          </div>
        </div>
        <!-- Search Input -->
        <div class="relative min-w-[250px]">
          <input type="text" id="searchInput"
                 placeholder="Search HEAD, TRAILER, JRQ"
                 class="w-full bg-white border border-gray-300 rounded-lg py-2 px-4 text-sm shadow-sm hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
          <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
          </div>
        </div>
      </div>
    </div>
    <!-- ✅ Scrollable Table -->
    <div class="p-6">
      <div class="overflow-x-auto max-h-[60vh] border rounded-lg">
        <table id="modalTable" class="min-w-full text-sm text-left border-collapse">
          <thead class="bg-blue-700 text-white sticky top-0 z-10">
            <tr>
              <th class="px-3 py-2">Tractor</th>
              <th class="px-3 py-2">Trailer</th>
              <th class="px-3 py-2">TeamTMS</th>
              <th class="px-3 py-2">Driver</th>
              <th class="px-3 py-2">Helper</th>
              <th class="px-3 py-2">Team</th>
              <th class="px-3 py-2">TripStatus</th>
              <th class="px-3 py-2">Attendance</th>
              <th class="px-3 py-2">AssignedTrip</th>
              <th class="px-3 py-2">Remarks</th>
            </tr>
          </thead>
          <tbody id="modalTableBody">
            <!-- Example Rows -->
            <tr class="odd:bg-blue-50 even:bg-blue-100 hover:bg-blue-200">
              <td class="px-3 py-2 font-semibold">H-997</td> <td>BO 833</td><td>SBUO-1C</td><td>Esmundo, Edmar Carpio</td><td>Diaz, Joel Labastida</td><td>SBUO-1C</td>
              <td class="px-3 py-2 text-blue-700 font-bold">RUNNING</td><td class="px-3 py-2 text-green-700 font-bold">PRESENT</td>
              <td>PLD-100250072</td><td>FROM PREMIER PASIG → GMEC FOR FORTUNE BATANGAS</td>
            </tr>
            <tr class="odd:bg-blue-50 even:bg-blue-100 hover:bg-blue-200">
              <td class="px-3 py-2 font-semibold">H-997</td> <td>BO 833</td><td>SBUO-1C</td><td>Esmundo, Edmar Carpio</td><td>Diaz, Joel Labastida</td><td>SBUO-1C</td>
              <td class="px-3 py-2 text-blue-700 font-bold">RUNNING</td><td class="px-3 py-2 text-red-700 font-bold">ABSENT</td>
              <td>PLD-100250072</td><td>FROM PREMIER PASIG → GMEC FOR FORTUNE BATANGAS</td>
            </tr>
            <tr class="odd:bg-blue-50 even:bg-blue-100 hover:bg-blue-200">
              <td class="px-3 py-2 font-semibold">H-997</td> <td>BO 833</td><td>SBUO-1C</td><td>Esmundo, Edmar Carpio</td><td>Diaz, Joel Labastida</td><td>SBUO-1C</td>
              <td class="px-3 py-2 text-blue-700 font-bold">RUNNING</td><td class="px-3 py-2 text-green-700 font-bold">PRESENT</td>
              <td>PLD-100250072</td><td>FROM PREMIER PASIG → GMEC FOR FORTUNE BATANGAS</td>
            </tr>
            <tr class="odd:bg-blue-50 even:bg-blue-100 hover:bg-blue-200">
              <td class="px-3 py-2 font-semibold">H-997</td> <td>BO 833</td><td>SBUO-1C</td><td>Esmundo, Edmar Carpio</td><td>Diaz, Joel Labastida</td><td>SBUO-1C</td>
              <td class="px-3 py-2 text-blue-700 font-bold">RUNNING</td><td class="px-3 py-2 text-green-700 font-bold">PRESENT</td>
              <td>PLD-100250072</td><td>FROM PREMIER PASIG → GMEC FOR FORTUNE BATANGAS</td>
            </tr>
            <tr class="odd:bg-blue-50 even:bg-blue-100 hover:bg-blue-200">
              <td class="px-3 py-2 font-semibold">H-997</td> <td>BO 833</td><td>SBUO-1C</td><td>Esmundo, Edmar Carpio</td><td>Diaz, Joel Labastida</td><td>SBUO-1C</td>
              <td class="px-3 py-2 text-blue-700 font-bold">RUNNING</td><td class="px-3 py-2 text-green-700 font-bold">PRESENT</td>
              <td>PLD-100250072</td><td>FROM PREMIER PASIG → GMEC FOR FORTUNE BATANGAS</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--RIGHT PANEL-->
<div class="bg-gradient-to-b from-blue-200 to-blue-400 rounded-2xl p-2 shadow-lg flex-1 space-y-4 md:mt-[-0.5rem]">
  <!-- ACTIVE -->
  <div>
    <h2 class="text-xl font-extrabold text-white text-center mb-3">ACTIVE</h2>
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full table-fixed text-center text-sm border-collapse">
        <thead>
          <tr class="bg-blue-600 text-white">
            <th class="py-2 px-3 w-2/12">BU</th>
            <th class="w-1/12">CARGO 2A</th>
            <th class="w-1/12">CARGO 3A</th>
            <th class="w-1/12">ZION</th>
            <th class="w-1/12">ZION BUKIDNON</th>
            <th class="w-1/12">BD-Port</th>
            <th class="w-1/12">VTS</th>
            <th class="w-1/12">J Express</th>
            <th class="w-1/12">TM</th>
            <th class="w-1/12">GRAND TOTAL</th>
          </tr>
        </thead>
      <tbody>
          <tr class="bg-green-50">
            <td class="font-bold text-left pl-3">TOTAL ASSIGNED DRIVERS</td>
            <td>33</td><td>37</td><td>37</td><td>32</td><td>24</td><td>12</td><td>20</td><td>5</td><td>200</td>
          </tr>
          <tr><td class="text-left pl-3">TOTAL PRESENT:</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr class="bg-green-50"><td class="text-left pl-3">RUNNING</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr><td class="text-left pl-3">AVAILABLE</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr class="bg-green-50"><td class="italic text-right pl-3">PNT</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr><td class="italic text-right pl-3">PRESENT-UR</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr class="bg-green-50"><td class="italic text-right pl-3">PRESENT-AWAITING TRIP</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr><td class="italic text-right pl-3">RESTDAY</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr class="bg-red-100 text-red-600 font-semibold">
            <td class="text-left pl-3">ABSENT</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          </tr>
          <tr class="bg-green-100 font-bold">
            <td class="text-left pl-3">TOTAL</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- INACTIVE -->
  <div>
    <h2 class="text-xl font-extrabold text-white text-center mb-3">INACTIVE DRIVERS</h2>
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full table-fixed text-center text-sm border-collapse">
        <thead>
          <tr class="bg-blue-600 text-white">
            <th class="py-2 px-3 w-2/12">BU</th>
            <th class="w-1/12">CARGO 2A</th>
            <th class="w-1/12">CARGO 3A</th>
            <th class="w-1/12">ZION</th>
            <th class="w-1/12">ZION BUKIDNON</th>
            <th class="w-1/12">BD-Port</th>
            <th class="w-1/12">VTS</th>
            <th class="w-1/12">J Express</th>
            <th class="w-1/12">TM</th>
            <th class="w-1/12">GRAND TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <tr class="bg-green-50">
            <td class="font-medium text-left pl-3">LEAVE</td>
            <td>33</td><td>37</td><td>37</td><td>32</td><td>24</td><td>12</td><td>20</td><td>5</td><td>200</td>
          </tr>
          <tr><td class="text-left pl-3">SUSPENDED</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr class="bg-green-50"><td class="text-left pl-3">ON TRIP</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr><td class="text-left pl-3">AWOL ALERT</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr class="bg-green-100 font-bold"><td class="text-left pl-3">TOTAL</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- TRACTOR -->
  <div>
    <h2 class="text-xl font-extrabold text-white text-center mb-3">TRACTOR</h2>
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full table-fixed text-center text-sm border-collapse">
        <thead>
          <tr class="bg-blue-600 text-white">
            <th class="py-2 px-3 w-2/12">BU</th>
            <th class="w-1/12">CARGO 2A</th>
            <th class="w-1/12">CARGO 3A</th>
            <th class="w-1/12">ZION</th>
            <th class="w-1/12">ZION BUKIDNON</th>
            <th class="w-1/12">BD-Port</th>
            <th class="w-1/12">VTS</th>
            <th class="w-1/12">J Express</th>
            <th class="w-1/12">TM</th>
            <th class="w-1/12">TOTAL RENTED</th>
            <th class="w-1/12">TOTAL UNRENTED</th>
            <th class="w-1/12">GRAND TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <tr class="bg-green-50">
            <td class="font-bold text-left pl-3">TOTAL ASSIGNED TRACTOR</td>
            <td>33</td><td>37</td><td>37</td><td>32</td><td>24</td><td>12</td><td>20</td><td>5</td><td>5</td><td>5</td><td>200</td>
          </tr>
          <tr><td class="text-left pl-3">AVAILABLE</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr class="bg-green-50"><td class="text-left pl-3">ON TRIP</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr><td class="text-left pl-3">PRELOADED</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr><td class="text-left pl-3">UR</td></td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr><td class="text-left pl-3">IDLE</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
          <tr class="bg-green-100 font-bold"><td class="text-left pl-3">TOTAL</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<!-- Sidebar Logic -->
<script>
function openNav(){document.getElementById("mySidebar").classList.remove("closed"); document.getElementById("mySidebar").classList.add("open"); document.getElementById("overlay").classList.add("show");}
function closeNav(){document.getElementById("mySidebar").classList.remove("open"); document.getElementById("mySidebar").classList.add("closed"); document.getElementById("overlay").classList.remove("show");}
// Set last synced time
document.getElementById("lastSynced").textContent = new Date().toLocaleString();
/* ✅ Function: Open Modal */
function openModal(title = "DETAILS", total = 0) {
  const modal = document.getElementById("cardModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
  // Set title + total
  document.getElementById("modalTitle").textContent = title;
  document.getElementById("totalRows").textContent = total;
}
/* ✅ Function: Close Modal */
function closeModal() {
  const modal = document.getElementById("cardModal");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
}
/* ✅ Close if clicked outside the modal box */
function closeModalOutside(event) {
  if (event.target.id === "cardModal") {
    closeModal();
  }
}
/* ✅ Automatically make all cards with .clickable open modal */
document.querySelectorAll(".clickable").forEach(card => {
  card.addEventListener("click", () => {
    const title = card.querySelector("p.text-sm").textContent.trim();
    const count = card.querySelector("p.text-2xl").textContent.trim();
    openModal(title, count);
  });
});
  // ✅ ESC key closes modal
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });
// Listener for ESC key - sidebar
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    const sidebar = document.getElementById("mySidebar");
    if (sidebar && sidebar.classList.contains("open")) {
      closeNav();
    }
  }
});
// Keyboard listener for sidebar control
document.addEventListener("keydown", (e) => {
  const sidebar = document.getElementById("mySidebar");
  if (!sidebar) return;
  // 🔹 ESC or → ArrowRight = CLOSE sidebar
  if ((e.key === "Escape" || e.key === "ArrowLeft") && sidebar.classList.contains("open")) {
    closeNav();
  }
  // 🔹 ← ArrowLeft = OPEN sidebar
  if (e.key === "ArrowRight" && sidebar.classList.contains("closed")) {
    openNav();
  }
});
</script>
</body>
</html>
