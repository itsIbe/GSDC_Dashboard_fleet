<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Fleet Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  body { overflow-x: hidden; font-family: sans-serif; }
  .layout { display: flex; width: 100%; height: calc(100vh - 120px); overflow: hidden; transition: all 0.4s ease-in-out; }
  #dashboard { display: grid; grid-template-columns: repeat(4, 1fr); grid-template-rows: repeat(2, 250px); gap: 1.5rem; flex:1; transition: all 0.5s ease-in-out; }
  .dashboard-card { transition: transform 0.4s ease, opacity 0.4s ease, width 0.4s ease; cursor:pointer; }
  .highlighted { box-shadow: 0 0 20px rgba(99,102,241,0.6); transform: scale(1.05); border: 2px solid rgba(99,102,241,0.6); z-index: 10; }
  .sidebar { width: 70%; height: 100%; background:white; box-shadow:-2px 0 6px rgba(0,0,0,0.2); overflow-y:auto; padding:1rem; transform:translateX(100%); transition: transform 0.4s ease-in-out; z-index:50; position:absolute; top:0; right:0; }
  .sidebar.open { transform: translateX(0); }
  .shrink-mode { display:flex !important; flex-direction:column; gap:1rem; width:30%; max-width:350px; overflow-y:auto; padding-right:1rem; }
  .shrink-mode .dashboard-card { width:100%; max-width:320px; min-height:220px; margin:0 auto; }
</style>
</head>
<body class="bg-gray-100">

<header class="p-6 border-b bg-white shadow-sm flex flex-col gap-1">
  <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-500">Fleet Dashboard</h1>
  <p class="text-gray-600">Real-time fleet & trailer monitoring</p>
  <p class="text-xs text-gray-500">Last synced: <span id="lastSynced"></span></p>
</header>

<div class="p-6 relative">
  <div class="layout">

    <!-- Dashboard Cards -->
    <section id="dashboard">
      <!-- Example Card -->
      <div class="dashboard-card bg-white rounded-2xl shadow p-6" data-card="truck" onclick="openSidebar('truck')">
        <h2 class="text-xl font-semibold mb-4 flex justify-between items-center">Truck UR Summary <span>🚛</span></h2>
        <div class="text-center text-2xl font-bold text-blue-600 mt-2 mb-4" id="truckTotal">0</div>
        <div class="grid grid-cols-4 text-center text-sm font-semibold text-gray-700 border-t pt-2">
          <div><div>IDLE</div><div class="text-yellow-500 font-bold" id="truckIdle">0</div></div>
          <div><div>AVAILABLE</div><div class="text-blue-600 font-bold" id="truckAvailable">0</div></div>
          <div><div>PRELOADED</div><div class="text-orange-500 font-bold" id="truckPreloaded">0</div></div>
          <div><div>ON TRIP</div><div class="text-green-600 font-bold" id="truckOnTrip">0</div></div>
        </div>
      </div>
      <!-- Add more cards as needed following this structure -->
    </section>

    <!-- Sidebar Table -->
    <aside id="sidebar" class="sidebar"></aside>
  </div>
</div>

<script>
document.getElementById("lastSynced").innerText = new Date().toLocaleString();

let rows = []; // data from fetch
const dataSets = {
  truck:{total:214,idle:53,available:7,preloaded:0,onTrip:154}
};
const themeColors = { truck:"bg-green-500" };
let activeCard = null;

function fetchData(){
  // Dummy fetch simulation
  rows = [
    ["Team","UR Status","Tag","Truck ID"],
    ["SBUO-1A","IDLE","MINOR","TR001"],
    ["SBUO-1A","AVAILABLE","MAJOR","TR002"],
    ["SBUO-1B","ON TRIP","MINOR","TR003"]
  ];
  calculateCounts();
}

function calculateCounts(){
  const counts = {IDLE:0,AVAILABLE:0,PRELOADED:0,"ON TRIP":0};
  const urIdx = rows[0].indexOf("UR Status");
  const total = rows.length-1;
  rows.slice(1).forEach(r=>{if(counts[r[urIdx]]!==undefined) counts[r[urIdx]]++;});
  document.getElementById("truckTotal").textContent = total;
  document.getElementById("truckIdle").textContent = counts.IDLE;
  document.getElementById("truckAvailable").textContent = counts.AVAILABLE;
  document.getElementById("truckPreloaded").textContent = counts.PRELOADED;
  document.getElementById("truckOnTrip").textContent = counts["ON TRIP"];
}

function openSidebar(card){
  const sidebar = document.getElementById('sidebar');
  const dashboard = document.getElementById('dashboard');
  const cards = dashboard.querySelectorAll('.dashboard-card');

  if(activeCard) activeCard.classList.remove("highlighted");
  const clickedCard = document.querySelector(`.dashboard-card[data-card="${card}"]`);
  clickedCard.classList.add("highlighted");
  activeCard = clickedCard;

  dashboard.classList.add('shrink-mode');

  sidebar.classList.add('open');

  // Render table inside sidebar
  const idxTeam = rows[0].indexOf("Team");
  const idxUR = rows[0].indexOf("UR Status");
  const filtered = rows.slice(1); // Could filter per card if needed

  let tableHTML = `<div class="p-2 mb-2 ${themeColors[card]} text-white"><button onclick="closeSidebar()" class="mb-2 px-4 py-2 bg-white text-black font-bold rounded-lg">← Back</button><h2 class="font-bold">${card.toUpperCase()} DETAILS</h2></div>`;
  tableHTML += `<table class="w-full border-collapse border text-xs text-center"><thead><tr>${rows[0].map(h=>`<th class="border px-2 py-1 bg-gray-200">${h}</th>`).join('')}</tr></thead><tbody>`;
  filtered.forEach(r=>{ tableHTML+=`<tr>${r.map(c=>`<td class="border px-2 py-1">${c}</td>`).join('')}</tr>`; });
  tableHTML += "</tbody></table>";
  sidebar.innerHTML = tableHTML;
}

function closeSidebar(){
  const sidebar = document.getElementById('sidebar');
  const dashboard = document.getElementById('dashboard');
  sidebar.classList.remove('open');
  dashboard.classList.remove('shrink-mode');
  if(activeCard) activeCard.classList.remove("highlighted");
  activeCard = null;
}

// Initial load
fetchData();
setInterval(fetchData,5*60*1000);
</script>
</body>
</html>
