<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cement Truck Capacity Summary</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
<style>
  body { overflow-x: hidden; font-family: Arial, sans-serif; transition: all 0.3s; }

  /* Sidebar Menu */
   .sidebar-menu {
    position: fixed; top: 0; left: 0;
    height: 100%; width: 250px;
    color: white;
    background: linear-gradient(to right, #60A5FA, #2563EB);
    transition: all 0.3s; padding: 20px 0;
    overflow-y: auto; z-index: 1000;
  }
  .sidebar-menu a {
    color: #ffffff; padding: 12px 20px;
    text-decoration: none; font-size: 18px;
    display: flex; align-items: center;
    gap: 12px; transition: 0.3s;
  }
  .sidebar-menu a:hover {
    color: #2563eb; background-color: #ffffff;
  }
  .sidebar-menu a.active {
    background-color: #1e40af; /* darker blue */
    font-weight: bold;
  }
  .sidebar-menu.open { left: 0; }
  .sidebar-menu.closed { left: -250px; }

  .overlay {
    position: fixed; top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none; z-index: 999;
  }
  .overlay.show { display: block; }
</style>
</head>
<body class="bg-gray-50">

<!-- Sidebar Menu -->
<div id="mySidebar" class="sidebar-menu closed">
  <h2 class="text-center text-2xl font-bold mb-6">Cement Truck Capacity Summary</h2>
  <a href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a>
  <a href="{{ url('/fleetcapacitydashboard') }}" class="active"><i class="fas fa-chart-bar"></i>Fleet Capacity Dashboard</a>
  <a href="{{ url('/drivercapacitydashboard') }}"><i class="fas fa-truck"></i> Driver Capacity Dashboard</a>
  <a href="{{ url('/trucktrailerdriver') }}"><i class="fas fa-cogs"></i>Truck, Trailer, And Driver Capacity</a>
  <a href="{{ url('/') }}"><i class="fas fa-database"></i> Module B</a>
</div>

<!-- Overlay -->
<div id="overlay" class="overlay" onclick="closeNav()"></div>

<!-- Floating Sidebar Toggle Button -->
<button 
  class="fixed top-4 left-4 z-50 bg-blue-600 text-white px-3 py-2 rounded-full shadow-lg hover:bg-blue-700 transition text-2xl"
  onclick="openNav()">
  &#9776;
</button>

<!-- Header -->
<header class="p-6 border-b bg-white shadow-sm flex flex-col gap-1">
  <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-500 text-center">
    Cement Truck Capacity Summary
  </h1>
  <div class="text-center mt-2">
    <p class="text-gray-600">Great Sierra Development Corporation</p>
  </div>
</header>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cement Truck Capacity Summary</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
<style>
  body { overflow-x: hidden; font-family: Arial, sans-serif; transition: all 0.3s; }

  /* Sidebar Menu */
   .sidebar-menu {
    position: fixed; top: 0; left: 0;
    height: 100%; width: 250px;
    color: white;
    background: linear-gradient(to right, #60A5FA, #2563EB);
    transition: all 0.3s; padding: 20px 0;
    overflow-y: auto; z-index: 1000;
  }
  .sidebar-menu a {
    color: #ffffff; padding: 12px 20px;
    text-decoration: none; font-size: 18px;
    display: flex; align-items: center;
    gap: 12px; transition: 0.3s;
  }
  .sidebar-menu a:hover {
    color: #2563eb; background-color: #ffffff;
  }
  .sidebar-menu a.active {
    background-color: #1e40af; /* darker blue */
    font-weight: bold;
  }
  .sidebar-menu.open { left: 0; }
  .sidebar-menu.closed { left: -250px; }

  .overlay {
    position: fixed; top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none; z-index: 999;
  }
  .overlay.show { display: block; }

  /* === MAIN SUMMARY CARDS === */
.card {
  padding: 1.5rem; /* p-6 */
  border-radius: 1rem; /* rounded-2xl */
  box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* shadow-md */
  color: #fff; /* text-white */
}
.card-total {
  background: linear-gradient(to right, #60a5fa, #2563eb); /* blue-400 → blue-600 */
}
.card-active {
  background: linear-gradient(to right, #4ade80, #16a34a); /* green-400 → green-600 */
}
.card-inactive {
  background: linear-gradient(to right, #f87171, #dc2626); /* red-400 → red-600 */
}

/* === SUB CATEGORY CARDS === */
.sub-card {
  padding: 1rem; /* p-4 */
  border-radius: 0.75rem; /* rounded-xl */
  text-align: center;
  color: #fff; /* text-white */
  box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* shadow */
}
.sub-running,
.sub-available,
.sub-driver-available {
  background: linear-gradient(to right, #86efac, #22c55e); /* green-300 → green-500 */
}
.sub-preparing {
  background: linear-gradient(to right, #fde047, #f59e0b); /* yellow-300 → yellow-500 */
}
.sub-rescue {
  background: linear-gradient(to right, #fca5a5, #ef4444); /* red-300 → red-500 */
}
.sub-ur-driver {
  background: linear-gradient(to right, #93c5fd, #3b82f6); /* blue-300 → blue-500 */
}
.sub-ur-rest,
.sub-rest {
  background: linear-gradient(to right, #9ca3af, #4b5563); /* gray-400 → gray-600 */
}

/* === BUSINESS UNIT CARDS === */
.bu-card {
  background: #fff; /* bg-white */
  padding: 1.5rem; /* p-6 */
  border-radius: 1rem; /* rounded-2xl */
  box-shadow: 0 10px 15px rgba(0,0,0,0.1); /* shadow-lg */
}
.bu-title {
  font-size: 1.125rem; /* text-lg */
  font-weight: 700; /* font-bold */
  color: #2563eb; /* text-blue-600 */
}
.bu-total {
  color: #6b7280; /* text-gray-500 */
  font-size: 0.875rem; /* text-sm */
}
.bu-status {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr)); /* grid-cols-2 */
  gap: 1rem; /* gap-4 */
  text-align: center;
  margin-top: 1rem; /* mt-4 */
}
.bu-box {
  padding: 0.75rem; /* p-3 */
  border-radius: 0.5rem; /* rounded-lg */
}
.bu-present {
  background: #dcfce7; /* bg-green-100 */
  color: #16a34a; /* text-green-600 */
}
.bu-absent {
  background: #fee2e2; /* bg-red-100 */
  color: #dc2626; /* text-red-600 */
}
.bu-sick {
  background: #fef9c3; /* bg-yellow-100 */
  color: #ca8a04; /* text-yellow-600 */
}
.bu-vacation {
  background: #dbeafe; /* bg-blue-100 */
  color: #2563eb; /* text-blue-600 */
}
.bu-label {
  font-size: 0.75rem; /* text-xs */
  font-weight: 600; /* font-semibold */
  color: #4b5563; /* text-gray-600 */
}
.bu-value {
  font-size: 1.125rem; /* text-lg */
  font-weight: 700; /* font-bold */
}

  .clickable {
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .clickable:hover {
    transform: translateY(-4px) scale(1.03);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
  }
  .clickable:active {
    transform: scale(0.98);
  }
</style>
</head>
<body class="bg-gray-50">

<!-- Sidebar Menu -->
<div id="mySidebar" class="sidebar-menu closed">
  <h2 class="text-center text-2xl font-bold mb-6">Cement Truck Capacity Summary</h2>
  <a href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a>
  <a href="test2.html"><i class="fas fa-chart-bar"></i>Fleet Capacity Dashboard</a>
  <a href="{{ url('/drivercapacitydashboard') }}"class="active"><i class="fas fa-truck"></i> Driver Capacity Dashboard</a>
  <a href="{{ url('/') }}"><i class="fas fa-cogs"></i> Module A</a>
  <a href="{{ url('/') }}"><i class="fas fa-database"></i> Module B</a>
</div>

<!-- Overlay -->
<div id="overlay" class="overlay" onclick="closeNav()"></div>

<!-- Floating Sidebar Toggle Button -->
<button 
  class="fixed top-4 left-4 z-50 bg-blue-600 text-white px-3 py-2 rounded-full shadow-lg hover:bg-blue-700 transition text-2xl"
  onclick="openNav()"> &#9776;
</button>


<body class="bg-gray-100 font-sans">
  <div class="max-w-7xl mx-auto p-6">
<!-- ACTIVE CARD -->
  <div class="bg-white rounded-2xl shadow-lg w-full overflow-hidden mb-8">
    <!-- Card Header -->
    <div class="bg-gradient-to-r from-green-400 to-green-600 text-white text-center font-bold py-2">
      ACTIVE DRIVERS
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-100 text-gray-700 text-xs uppercase">
          <tr>
            <th class="p-2 text-left">BU</th>
            <th class="p-2 text-right">SBU0-1A</th>
            <th class="p-2 text-right">SBU0-1B</th>
            <th class="p-2 text-right">SBU0-1C</th>
            <th class="p-2 text-right">SBU0-1D</th>
            <th class="p-2 text-right">SBU0-2A</th>
            <th class="p-2 text-right">SBU0-2B</th>
            <th class="p-2 text-right">SBU0-3A</th>
            <th class="p-2 text-right">SBU0-4A</th>
            <th class="p-2 text-right">GRAND TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <tr class="bg-green-100">
            <td class="p-2">TOTAL ASSIGNED DRIVERS</td>
            <td class="p-2 text-right">33</td>
            <td class="p-2 text-right">37</td>
            <td class="p-2 text-right">37</td>
            <td class="p-2 text-right">32</td>
            <td class="p-2 text-right">24</td>
            <td class="p-2 text-right">12</td>
            <td class="p-2 text-right">20</td>
            <td class="p-2 text-right">5</td>
            <td class="p-2 text-right font-bold">200</td>
          </tr>
          <tr>
            <td class="p-2">TOTAL PRESENT</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
          </tr>
          <tr class="bg-green-100">
            <td class="p-2 ">RUNNING</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
          </tr>
           <tr>
            <td class="p-2">AVAILABLE</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
          </tr>
            <tr class="bg-green-100">
            <td class="p-2 ">DRIVER PREPARING</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
          </tr>
          <tr class="bg-red-50 text-red-600 font-semibold">
            <td class="p-2">ABSENT</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
          </tr>
          <tr class="bg-blue-50 font-bold">
            <td class="p-2">TOTAL</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>


  <!-- INACTIVE CARD -->
  <div class="bg-white rounded-2xl shadow-lg w-full overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-center font-bold py-2">
      INACTIVE DRIVERS
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-100 text-gray-700 text-xs uppercase">
          <tr>
            <th class="p-2 text-left">BU</th>
            <th class="p-2 text-right">SBU0-1A</th>
            <th class="p-2 text-right">SBU0-1B</th>
            <th class="p-2 text-right">SBU0-1C</th>
            <th class="p-2 text-right">SBU0-1D</th>
            <th class="p-2 text-right">SBU0-2A</th>
            <th class="p-2 text-right">SBU0-2B</th>
            <th class="p-2 text-right">SBU0-3A</th>
            <th class="p-2 text-right">SBU0-4A</th>
            <th class="p-2 text-right">GRAND TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <tr class="bg-yellow-100">
            <td class="p-2">ON LEAVE</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
          </tr>
          <tr class="bg-yellow-100">
            <td class="p-2">DAY OFF</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
          </tr>
          <tr class="bg-blue-50 font-bold">
            <td class="p-2">TOTAL</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
            <td class="p-2 text-right">0</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>



  </div>
</div>


<script>
/* Sidebar Menu Logic */
function openNav() {
  document.getElementById("mySidebar").classList.remove("closed");
  document.getElementById("mySidebar").classList.add("open");
  document.getElementById("overlay").classList.add("show");
}
function closeNav() {
  document.getElementById("mySidebar").classList.remove("open");
  document.getElementById("mySidebar").classList.add("closed");
  document.getElementById("overlay").classList.remove("show");
}

/* Active link logic (optional, make clicked link active) */
const links = document.querySelectorAll(".sidebar-menu a");
links.forEach(link => {
  link.addEventListener("click", function() {
    links.forEach(l => l.classList.remove("active"));
    this.classList.add("active");
  });
});
async function loadTables() {
  try {
    // Example fetch – replace with your real API
    const res = await fetch("/fetch-data"); 
    const data = await res.json();

    // Render both tables
    renderTable("activeTable", data.active);
    renderTable("inactiveTable", data.inactive);
  } catch (e) {
    console.error("Error fetching data:", e);
  }
}

function renderTable(tableId, rows) {
  const table = document.getElementById(tableId);
  table.innerHTML = "";

  if (!rows || rows.length === 0) {
    table.innerHTML = `<tr><td class="p-2 text-gray-500">No data</td></tr>`;
    return;         
  }

  // Build header dynamically
  const headers = Object.keys(rows[0]);
  let thead = "<thead><tr>";
  headers.forEach(h => { thead += `<th>${h}</th>`; });
  thead += "</tr></thead>";

  // Build rows
  let tbody = "<tbody>";
  rows.forEach(r => {
    tbody += "<tr>";
    headers.forEach(h => {
      tbody += `<td>${r[h]}</td>`;
    });
    tbody += "</tr>";
  });
  tbody += "</tbody>";

  table.innerHTML = thead + tbody;
}

// Load on page start
loadTables();
</script>

</body>
</html>