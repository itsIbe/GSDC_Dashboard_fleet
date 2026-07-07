@php
use App\Models\AuditLog;
AuditLog::create([
    'user_id' => Auth::id(),
    'action' => 'Fleet Capacity Dashboard',
    'ip_address' => request()->ip(),
    'description' => 'Navigate and Viewed Fleet Capacity Dashboard',
]);@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<script>
    if (window.performance && window.performance.navigation.type === 2) {
        // Force a full reload if user navigates back
        location.reload();
    }
</script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fleet Dashboard</title>
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
    text-decoration: none; font-size: 14px;
    display: flex; align-items: center;
    gap: 12px; transition: 0.3s;
  }
  .sidebar-menu a:hover { color: #2563eb; background-color: #ffffff; }
  .sidebar-menu a.active { background-color: #1e40af; font-weight: bold; }
  .sidebar-menu.open { left: 0; }
  .sidebar-menu.closed { left: -250px; }
  .overlay {
    position: fixed; top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none; z-index: 999;
  }
  .overlay.show { display: block; }
  /* Dashboard layout */
  /* Replace your existing .layout style with this */


  .layout { display: flex; width: 100%; height: calc(95vh - 120px); overflow: hidden; transition: all 0.4s ease-in-out; }
  #dashboard { display: grid; grid-template-columns: repeat(4, 1fr); grid-auto-rows: auto; gap: 1.5rem; flex: 1; transition: all 0.5s ease-in-out; }
  .dashboard-card { background: white; border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 1.3xrem; transition: transform 0.3s ease, box-shadow 0.3s ease; cursor:pointer;    min-height: 180px;max-height: none; }
  .dashboard-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
  .card-header { font-weight: bold; color: white; padding: 0.25rem; border-radius: 0.25rem; text-align: center; font-size: 0.9rem; }
  .sidebar { width: 80%; height: 100%; background: white; box-shadow: -2px 0 6px rgba(0,0,0,0.2); overflow-y: hidden; overflow-x:auto; padding: 1rem; transform: translateX(100%); transition: transform 0.4s ease-in-out; z-index: 50; position: absolute; top: 0; right: 0; }
  .sidebar.open { transform: translateX(0); }
  .clickable { cursor:pointer; }
  .clickable:hover { opacity:0.8; }
  .shrink-mode { display: flex !important; flex-direction: column; gap: 1rem; width: 30%; max-width: 350px; overflow-y: auto;overflow-x: auto; padding-right: 1rem; }
  .shrink-mode .dashboard-card .truck-header .trailer-header { width: 105%; max-width: 340px; min-height: 220px; margin: 0 auto; }
  .highlighted { box-shadow: 0 0 20px rgba(99,102,241,0.6), 0 0 40px rgba(99,102,241,0.3); transform: scale(1.05); border: 2px solid rgba(99,102,241,0.6); z-index: 10; }
  .status-count { cursor:pointer; }
  .status-count:hover { opacity:0.8; }
  .parent-container{ display: flex; flex-direction: column;height: 100vh; }
//* ✅ Table Wrapper for Horizontal + Vertical Scroll */
/* Sidebar keeps its fixed size */
.sidebar {
  width: 400px;                /* adjust as needed */
  height: 100%;
  background: white;
  box-shadow: -2px 0 6px rgba(0,0,0,0.2);
  display: flex;
  flex-direction: column;
  position: absolute;
  top: 0;
  right: 0;
  z-index: 50;
  overflow: hidden;            /* stop table from spilling outside */
}
 /* ✅ Make the table more compact */
.table-container {
  flex: 1;
  max-height: 55vh;        /* smaller vertical height */
  overflow-y: auto;        /* vertical scroll */
  overflow-x: auto;        /* horizontal scroll */
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  background: white;
}

/* ✅ Table compact styling */
table {
  border-collapse: collapse;
  min-width: 600px;        /* smaller min width */
  width: max-content;      /* shrink to fit content */
  font-size: 0.7rem;       /* smaller text */
}

/* ✅ Sticky header */
th {
  background: #f9fafb;
  position: sticky;
  top: 0;
  z-index: 5;
  font-size: 0.7rem;       /* smaller headers */
  padding: 4px 6px;        /* smaller padding */
}

/* ✅ Cell compact */
td {
  padding: 3px 6px;        /* reduce cell size */
  font-size: 0.7rem;       /* smaller text */
  white-space: nowrap;     /* keep horizontal scroll */
}


/* ✅ Zebra + hover */
tbody tr:nth-child(even) { background-color: #e7e7e7; }
tbody tr:hover { background-color: #e0f2fe; }

/* ✅ Responsive (awd) */
@media (max-width: 768px) {
  table {rgb(120, 188, 255)
    min-width: 500px;
    font-size: 0.65rem;
  }
  th, td {
    padding: 2px 4px;
  }
}
/* Use a media query to add a break point at 800px: */
@media screen and (max-width: 800px) {
  .left, .main, .right {
    width: 100%; /* The width is 100%, when the viewport is 800px or smaller */
  }
}

  /* ✅ Status Label Colors */
  .status-label {
    display: inline-block;
    font-size: x-small;
    font-weight: 750;
    padding: 1px 3px;
    border-radius: 3px;
    margin-bottom: 1px;
  }
  .status-label.total      { background: #ffffff; color: #265999; }
  .status-label.target     { background: #ffffff; color: #7CBFA7; }
  .status-label.idle       { background: #ffffff; color: #ffb412; }
  .status-label.available  { background: #ffffff; color: #22C55E; }
  .status-label.preloaded  { background: #ffffff; color: #CA8A04; }
  .status-label.on-trip    { background: #ffffff; color: #16A34A; }
  .status-label.at-yard    { background: #ffffff; color: #075985; }
  .status-label.outside    { background: #ffffff; color: #FCD34D; }
  .status-label.approved   { background: #ffffff; color: #B91C1C; } 
  .status-label.for-resc   { background: #ffffff; color: #7F1D1D; }
  .status-label.accepted   { background: #ffffff; color: #EA580C; }
  .status-label.wfp        { background: #ffffff; color: #FB923C; }
  .status-label.on-resc    { background: #ffffff; color: #374151; }
  .status-label.not-released { background: #ffffff; color: #F44336; }
.truck-header,
.trailer-header {
  grid-column: 1 / -1;          /* span all grid columns */
  text-align: center;           /* center text */
  font-weight: bold;
  font-size: 1.3rem;              /* slightly smaller text */
  color: #ffffff;
  background: linear-gradient(to right, #74c1ff, #002472);
  padding: 1.7rem;      /* smaller padding */
  margin: 0;
  top: 0rem;                  /* spacing from top */
  z-index: 5;
  border-radius: 0.25rem;       /* softer small rounded corners */
  box-shadow: 0 1px 2px rgba(0,0,0,0.05); /* lighter shadow */
  height: 90px;
  line-height: 20px;
}
/* Default: Desktop (4 columns) */
#dashboard {
  grid-template-columns: repeat(4, 1fr);
}

/* Tablet (2 columns) */
@media (max-width: 1024px) {
  #dashboard {
    grid-template-columns: repeat(2, 1fr);
  }
}
/* ✅ Mobile Layout (like your screenshot) */
@media (max-width: 640px) {
  /* Dashboard becomes single column, scrollable */
  #dashboard {
    display: grid;
    grid-template-columns: 1fr;
    overflow-y: auto;
    overflow-x: hidden;
    gap: 1rem;
    padding-bottom: 1rem;
  }

  /* Layout wrapper scrollable vertically */
  .layout {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: auto;
    overflow-y: auto;
    overflow-x: hidden;
    scroll-behavior: smooth;
  }

  /* ✅ Sticky filter/header section */
  .filter-panel,
  .table-header,
  .export-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: #ffffff;
    padding: 0.75rem;
    border-bottom: 1px solid #ddd;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
  }

  /* Buttons fit full width on small screens */
  .filter-panel button,
  .export-header button {
    width: 100%;
    margin-bottom: 0.25rem;
  }

  /* ✅ Scrollable table container */
  .table-container {
    max-height: 70vh; /* adjust height to fit screen */
    overflow-y: auto;
    overflow-x: auto;
    background: #fff;
    border-radius: 0.5rem;
    padding: 0.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  }

  /* Table style adjustments */
  table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
    font-size: 0.75rem;
  }

  th, td {
    padding: 4px 6px;
    text-align: left;
    white-space: nowrap;
  }

  /* Sidebar responsive - FIXED so it doesn’t scroll with dashboard */
  .sidebar {
    position: fixed; /* changed from absolute */
    top: 0;
    right: 0;
    width: 100%;
    height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;
    border-radius: 0;
    z-index: 50;
    background: white; /* keep sidebar background */
    transition: transform 0.4s ease-in-out;
  }

  /* Flex filters stack vertically */
  .flex.flex-wrap.items-center.gap-2.mb-4.text-sm,
  .flex.flex-wrap.gap-2.mb-4.text-sm {
    flex-direction: column;
    align-items: flex-start;
  }
}

/* ✅ Tablet and smaller laptops */
@media (max-width: 1024px) {
  .sidebar {
    position: fixed; /* keep fixed for consistency */
    width: 85%;
    max-width: 600px;
    height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;
    border-radius: 0;
    z-index: 50;
    background: white;
    transition: transform 0.4s ease-in-out;
  }
}

/*New CSS*/
/* ✅ Base Filter Panel */
.filter-panel {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 0.5rem;
  background: #fff;
  padding: 0.75rem 1rem;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  position: sticky;
  top: 0;
  z-index: 1000;
}

/* Labels and Inputs */
.filter-panel label {
  font-weight: 600;
  white-space: nowrap;
}

.filter-panel select,
.filter-panel input {
  border: 1px solid #ccc;
  border-radius: 6px;
  padding: 4px 8px;
  font-size: 0.9rem;
}

/* Buttons */
#reset-filters {
  background-color: #e2e2e2;
  font-weight: 500;
  transition: background 0.2s;
}

#reset-filters:hover {
  background-color: #d1d1d1;
}

/* ✅ Desktop - Default (No change) */
@media (min-width: 641px) {
  .filter-panel {
    flex-direction: row;
  }
  .filter-panel input {
    width: 14rem;
  }
}

/* ✅ Mobile - Like your screenshot */
@media (max-width: 640px) {
  .filter-panel {
    flex-direction: column;
    align-items: stretch;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 8px;
    position: sticky;
    top: 0;
    background: #fff;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    z-index: 1000;
  }

  .filter-panel label {
    margin-top: 0.25rem;
    font-size: 0.95rem;
  }

  .filter-panel select,
  .filter-panel input {
    width: 100%;
    font-size: 1rem;
    padding: 0.5rem;
  }

  #reset-filters {
    width: 100%;
    margin-top: 0.5rem;
  }

  #row-count {
    text-align: right;
    font-size: 0.9rem;
    color: #666;
  }
}

/* ✅ Table Responsiveness */
@media (max-width: 768px) {
  table {
    font-size: 0.8rem;
    min-width: 600px;
  }

  th, td {
    padding: 4px 6px;
  }
}

</style>
</head>
<body class="bg-gray-50">


<!-- Sidebar Menu -->
<div id="mySidebar" class="sidebar-menu closed flex flex-col justify-between">
  <div>
    <h1 class="text-center text-1xl font-bold mb-6">
      Great Sierra Development Corporation
    </h1>

    <!-- 👤 User Profile Section -->
    <div class="flex flex-col items-center px-4 mb-6">
      <!-- Profile Picture -->
      <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}" 
           alt="User Avatar" 
           class="w-20 h-20 rounded-full border-4 border-white shadow-md mb-3">

      <!-- User Name -->
      <p class="text-lg font-semibold text-white text-center">
        {{ Auth::user()->display_name }}
      </p>
      
      <!-- ✅ User Position -->
      <p class="text-sm text-gray-200 text-center">
        {{ Auth::user()->position ?? 'Employee' }}
      </p>
    </div>

    <!-- Sidebar Links -->
@php
    $user = auth()->user();
@endphp

@if(!$user->isAdmin()) 
    <!-- Links visible only to regular users -->
    <a href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a>
    <a href="{{ url('/fleetcapacitydashboard') }}" class="active"><i class="fas fa-chart-bar"></i> Fleet Capacity Dashboard</a>
    <a href="{{ url('/drivercapacitydashboard') }}"><i class="fas fa-truck"></i> Driver Capacity Dashboard</a>
@endif

@if($user->isAdmin())
    <!-- Links visible only to admin -->
    <a href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a>
    <a href="{{ url('/fleetcapacitydashboard') }}" class="active"><i class="fas fa-chart-bar"></i> Fleet Capacity Dashboard</a>
    <a href="{{ url('/drivercapacitydashboard') }}"><i class="fas fa-truck"></i> Driver Capacity Dashboard</a>
    <a href="{{ url('/createuser') }}"><i class="fas fa-user-plus"></i> Create New User</a>
    <a href="{{ route('audit.index') }}"><i class="fas fa-clipboard-list"></i> Audit Logs</a>
@endif
  </div>
  <!-- ✅ Logout Button -->
  <form method="POST" action="{{ route('logout') }}" class="mt-6 px-4">
    @csrf
    <button type="submit" 
      class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 ease-in-out flex items-center justify-center gap-2">
      <i class="fas fa-sign-out-alt"></i> Logout
    </button>
  </form>
</div>



<div id="overlay" class="overlay" onclick="closeNav()"></div>

<!-- Sidebar Toggle Button -->
<button
  class="fixed top-4 left-4 z-50 bg-blue-600 text-white px-3 py-2 rounded-full shadow-lg hover:bg-blue-700 text-2xl"
  onclick="openNav()"
>
  &#9776;
</button>

<!-- ✅ Fixed Top Bar (Responsive) -->
<div
  class="fixed top-0 left-0 w-full bg-white shadow-md z-40 flex flex-col md:flex-row md:items-center md:justify-between px-4 md:px-6 py-3 gap-2 md:gap-0"
  style="min-height: 90px;"
>
  <!-- Left: Sidebar Button Placeholder (for spacing on desktop) -->
  <div class="hidden md:block w-10"></div>

  <!-- Center: Title + Last Synced -->
  <div class="flex-1 text-center">
    <h1
      class="text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-500"
    >
      Fleet Capacity Dashboard
    </h1>
    <p class="text-xs text-gray-500 mt-1">
      Last synced: <span id="lastSynced"></span>
    </p>
  </div>

  <!-- Right: Repair Location Filter -->
  <div class="flex justify-center md:justify-end">
    <select
      id="repairLocationFilter"
      class="bg-green-500 text-white font-semibold py-2 px-3 md:px-4 rounded-lg shadow hover:bg-green-600 transition w-56 md:w-auto text-sm md:text-base"
    >
      <option value="ALL">Repair Location ▾ (ALL)</option>
      <option value="VASQUEZ">VASQUEZ</option>
      <option value="INTERCITY">INTERCITY</option>
    </select>
  </div>
</div>

<!-- Add padding so content isn't hidden under fixed bar -->
<div class="pt-28 md:pt-24"></div>


<!-- Main Dashboard Layout -->
<div class="p-6 relative">
  <div class="layout">
    <section id="dashboard"></section>
    <aside id="sidebar" class="sidebar"></aside>
  </div>
</div>

<script>
/* --- Sidebar Menu Logic --- */
function openNav(){ 
  document.getElementById("mySidebar").classList.replace("closed","open"); 
  document.getElementById("overlay").classList.add("show"); 
}
function closeNav(){ 
  document.getElementById("mySidebar").classList.replace("open","closed"); 
  document.getElementById("overlay").classList.remove("show"); 
}

/* --- Data & Dashboard Logic --- */
const lastSyncedEl=document.getElementById("lastSynced");
function updateLastSynced(){ lastSyncedEl.innerText=new Date().toLocaleString(); }

const themeColors = {
  trucktotal: "bg-gradient-to-r from-blue-400 to-blue-600",
  truckavailable: "bg-gradient-to-r from-green-400 to-green-600",
  truckpending: "bg-gradient-to-r from-yellow-400 to-yellow-600",
  truckongoing: "bg-gradient-to-r from-red-400 to-red-600",

  trailertotal: "bg-gradient-to-r from-blue-400 to-blue-600",
  traileravailable: "bg-gradient-to-r from-green-400 to-green-600",
  trailerpending: "bg-gradient-to-r from-yellow-400 to-yellow-600",
  trailerongoing: "bg-gradient-to-r from-red-400 to-red-600"
};


const truckHeaders=['Unit','Team','Brand','Pair','JRNumber','JRStatus','RequestStatus','Repair Location','Tag','JR Age','Approval Age','JO Num','JO Activity','ETR','Parts ETA','UR Status','Last Location','Last Update','Yard','At Yard Timestamp','ToA at Yard','SBUO STATUS','OE','Ops Remarks'];
const trailerHeaders=['Unit','Team','Classification','Pair','JRNumber','JRStatus','RequestStatus','Repair Location','Tag','JR Age','Approval Age','ETR','JO Num','JO Activity','Parts ETA','UR Status','LAST LOCATION','LAST UPDATE','YARD','AT YARD TIMESTAMP','TOA','SBUO STATUS','OE','Ops Remarks','Axle','Sub Engine'];

let fetchedData={result:[],trailer_data:[]}; 
let activeCard=null;

/* --- Cards Definition --- */
const cards=[
  {id:'trucktotal',title:'TOTAL',type:'truck',statuses:['TOTAL','UP%','TARGET'],isTotal:true},
  {id:'truckavailable',title:' Available',type:'truck',statuses:['IDLE','AVAILABLE','PRELOADED','ON TRIP']},
  {id:'truckpending',title:'Pending JR',type:'truck',statuses:['AT YARD','OUTSIDE','APPROVED','FOR RESC']},
  {id:'truckongoing',title:'On Going JR',type:'truck',statuses:['ACCEPTED','WFP','NOT RELEASED','ON RESC']},
  {id:'trailertotal',title:'TOTAL',type:'trailer',statuses:['TOTAL','UP%','TARGET'],isTotal:true},
  {id:'traileravailable',title:'Available',type:'trailer',statuses:['IDLE','AVAILABLE','PRELOADED','ON TRIP']},
  {id:'trailerpending',title:'Pending JR',type:'trailer',statuses:['AT YARD','OUTSIDE','APPROVED','FOR RESC']},
  {id:'trailerongoing',title:'On Going JR',type:'trailer',statuses:['ACCEPTED','WFP','NOT RELEASED','ON RESC']}
];

/* --- Status Normalizer --- */
function normalizeStatus(s){ 
  if(!s) return ""; 
  s=s.trim().toUpperCase();
  if(s.includes("IDLE"))return"IDLE"; 
  if(s.includes("AVAILABLE"))return"AVAILABLE";
  if(s.includes("PRELOADED"))return"PRELOADED"; 
  if(s.includes("TRIP"))return"ON TRIP";
  if(s.includes("YARD"))return"AT YARD"; 
  if(s.includes("OUTSIDE"))return"OUTSIDE";
  if(s.includes("APPROV"))return"APPROVED"; 
  if(s.includes("FOR RESC"))return"FOR RESC";
  if(s.includes("ACCEPTED"))return"ACCEPTED"; 
  if(s.includes("WFP"))return"WFP";
  if(s.includes("NOT RELEASED"))return"NOT RELEASED"; 
  if(s.includes("ON RESC"))return"ON RESC"; 
  return s; 
}

function mapFetchedRow(row,t){
  const o={};
  const h=t==='truck'?truckHeaders:trailerHeaders;
  h.forEach((x,i)=>o[x]=row[i]??'');
  o.Status=normalizeStatus(o['UR Status']||o['UR STATUS']||'');
  return o;
}

/* --- Helper to map status class to color --- */
function getStatusColor(cls){
  switch(cls){
    case 'total': return '#265999';
    case 'target': return '#7CBFA7';
    case 'idle': return '#ffb412';
    case 'available': return '#22C55E';
    case 'preloaded': return '#CA8A04';
    case 'on-trip': return '#16A34A';
    case 'at-yard': return '#075985';
    case 'outside': return '#FCD34D';
    case 'approved': return '#B91C1C';
    case 'for-resc': return '#7F1D1D';
    case 'accepted': return '#EA580C';
    case 'wfp': return '#FB923C';
    case 'not-released': return '#F44336';
    case 'on-resc': return '#374151';
  }
}

/* --- Render Dashboard Cards --- */
function renderEmptyDashboard(){
  const dash = document.getElementById('dashboard'); 
  let html = '';

  // ✅ TRUCKS section
  html += `  <div class="truck-header">
    <i class="fas fa-truck-moving text-white-600 mr-2"></i>
    TRUCK
  </div>`;
  cards.filter(c => c.type === 'truck').forEach(c => {
    const hc = c.id.includes('total') ? 'bg-gradient-to-r from-blue-400 to-blue-600 text-white' : 
               c.id.includes('available') ? 'bg-gradient-to-r from-green-400 to-green-600 text-white' : 
               c.id.includes('pending') ? 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white' : 
               'bg-gradient-to-r from-red-400 to-red-600 text-white';
    html += `
      <div class="dashboard-card" data-card="${c.id}">
        <div class="text-center rounded-lg ${hc} p-4">
          <div class="text-lg font-semibold text-white mb-1 uppercase">${c.title}</div>
          <div class="text-3xl font-bold text-white big-number">0</div>
        </div>
        <div class="grid gap-2 text-center text-xs font-semibold mt-3" style="grid-template-columns: repeat(${c.statuses.length},1fr);">
          ${c.statuses.map(s => {
            const cls = s.toLowerCase().replace(/\s+/g,'-');
            return `<div class="status-count">
                      <span class="status-label ${cls}">${s}</span>
                      <div class="font-bold" style="color: ${getStatusColor(cls)}">0</div>
                    </div>`;
          }).join('')}
        </div>
      </div>`;
  });

  // ✅ TRAILERS section
  html += `  <div class="trailer-header">
    <i class="fas fa-trailer text-white-600 mr-2"></i>
    TRAILERS
  </div>`;
  cards.filter(c => c.type === 'trailer').forEach(c => {
     const hc = c.id.includes('total') ? 'bg-gradient-to-r from-blue-400 to-blue-600 text-white' : 
               c.id.includes('available') ? 'bg-gradient-to-r from-green-400 to-green-600 text-white' : 
               c.id.includes('pending') ? 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white' : 
               'bg-gradient-to-r from-red-400 to-red-600 text-white';
    html += `
      <div class="dashboard-card" data-card="${c.id}">
        <div class="text-center rounded-lg ${hc} p-4">
          <div class="text-lg font-semibold text-white mb-1 uppercase">${c.title}</div>
          <div class="text-3xl font-bold text-white big-number">0</div>
        </div>
        <div class="grid gap-2 text-center text-xs font-semibold mt-3" style="grid-template-columns: repeat(${c.statuses.length},1fr);">
          ${c.statuses.map(s => {
            const cls = s.toLowerCase().replace(/\s+/g,'-');
            return `<div class="status-count">
                      <span class="status-label ${cls}">${s}</span>
                      <div class="font-bold" style="color: ${getStatusColor(cls)}">0</div>
                    </div>`;
          }).join('')}
        </div>
      </div>`;
  });

  dash.innerHTML = html;
}


/* --- Data Fetch --- */
async function loadData(){
  try{
    const r=await fetch("/fetch-data");
    const j=await r.json();
    fetchedData.result=j.result?.map(x=>mapFetchedRow(x,'truck'))||[];
    fetchedData.trailer_data=j.trailer_data?.map(x=>mapFetchedRow(x,'trailer'))||[];
  }catch(e){ fetchedData={result:[],trailer_data:[]} }
  populateDashboard(fetchedData);
  updateLastSynced();
}
//populateDashboard
function populateDashboard(data) {
  // We'll compute status counts accurately here and make TOTAL = sum(available+pending+ongoing)
  const AVAILABLE_GROUP = ["IDLE","AVAILABLE","PRELOADED","ON TRIP"];
  const PENDING_GROUP   = ["AT YARD","OUTSIDE","APPROVED","FOR RESC"];
  const ONGOING_GROUP   = ["ACCEPTED","WFP","NOT RELEASED","ON RESC"];

  cards.forEach(c => {
    // Select dataset based on type
    let ds = c.type === 'truck' ? data.result : data.trailer_data;

    // ✅ Apply Repair Location filter
    if (selectedRepairLocation !== "ALL") {
      ds = ds.filter(r => {
        const loc = (r["Repair Location"] || "").toString().trim().toUpperCase();
        return loc === selectedRepairLocation;
      });
    }

    // Build a map of counts per normalized status (single pass)
    const statusCountMap = {}; // e.g. { "AVAILABLE": 5, "ACCEPTED": 2, ... }
    ds.forEach(r => {
      const s = normalizeStatus(r.Status);
      if (!s) return;
      statusCountMap[s] = (statusCountMap[s] || 0) + 1;
    });

    // Initialize counts object for this card's statuses
    const counts = {};
    c.statuses.forEach(s => counts[s] = 0);

    // If this is a normal (non-total) card, populate counts for each of its statuses
    if (!c.isTotal) {
      // For every status label defined in the card (like 'IDLE','AVAILABLE',...), set value from statusCountMap
      c.statuses.forEach(label => {
        // label is already in uppercase in your definition; ensure matching
        counts[label] = statusCountMap[label] || 0;
      });
    }

    // If this is the total card, compute totals from groups (so TOTAL = available+pending+ongoing)
    if (c.isTotal) {
      const availableSum = AVAILABLE_GROUP.reduce((acc, s) => acc + (statusCountMap[s] || 0), 0);
      const pendingSum   = PENDING_GROUP.reduce((acc, s) => acc + (statusCountMap[s] || 0), 0);
      const ongoingSum   = ONGOING_GROUP.reduce((acc, s) => acc + (statusCountMap[s] || 0), 0);

      const totalSum = availableSum + pendingSum + ongoingSum;

      counts["TOTAL"] = totalSum;
      counts["UP%"] = totalSum > 0 ? ((availableSum / totalSum) * 100).toFixed(1) + "%" : "0%";
      counts["TARGET"] = "75%";
    }

    // Sum of counts for display on the big-number (for non-total cards it's sum of that card's statuses)
    const sum = c.isTotal ? counts["TOTAL"] : c.statuses.reduce((a, s) => a + (counts[s] || 0), 0);

    // Update card DOM
    const cardEl = document.querySelector(`.dashboard-card[data-card="${c.id}"]`);
    if (!cardEl) return;

    cardEl.querySelector(".big-number").innerText = sum;

    // Update per-status numbers & attach click handlers
    const scEls = cardEl.querySelectorAll(".status-count");
    c.statuses.forEach((s, i) => {
      // If counts[s] is undefined (e.g., for 'UP%' or 'TARGET'), use counts[s] directly.
      const valueToShow = (counts[s] !== undefined) ? counts[s] : (statusCountMap[s] || 0);
      if (scEls[i] && scEls[i].querySelector(".font-bold")) {
        scEls[i].querySelector(".font-bold").innerText = valueToShow;
      }
      if (scEls[i]) {
        scEls[i].onclick = (e) => {
          e.stopPropagation();
          openSidebar(c.id, s);
        };
      }
    });

    cardEl.querySelector(".big-number").onclick = (e) => {
      e.stopPropagation();
      openSidebar(c.id);
    };
    cardEl.onclick = () => openSidebar(c.id);
  });
}

/* --- Sidebar Logic --- */
function openSidebar(card, statusFilter = null) {
  document.body.style.overflow = "hidden";   // 🚫 freeze page scroll
  const sb = document.getElementById('sidebar');
  sb.classList.add("open");
  const dash = document.getElementById('dashboard');

  if (activeCard) activeCard.classList.remove("highlighted");
  const cc = document.querySelector(`.dashboard-card[data-card="${card}"]`);
  cc.classList.add("highlighted");
  activeCard = cc;

  dash.classList.add('shrink-mode');

let ds = card.includes('truck') ? fetchedData.result : fetchedData.trailer_data;

// ✅ Apply Repair Location filter here too
if (selectedRepairLocation !== "ALL") {
  ds = ds.filter(r => {
    const loc = (r["Repair Location"] || "").toString().trim().toUpperCase();
    return loc === selectedRepairLocation;
  });
}

  if (!ds.length) {
    sb.innerHTML = '<p>No data available</p>';
    return;
  }

  // ✅ Initial filter
  let filtered = [];
  if (statusFilter) {
    filtered = ds.filter(r => normalizeStatus(r.Status) === statusFilter);
  } else {
    const cardObj = cards.find(c => c.id === card);
    if (cardObj && cardObj.statuses && !cardObj.isTotal) {
      filtered = ds.filter(r => cardObj.statuses.includes(normalizeStatus(r.Status)));
    } else {
      filtered = [...ds];
    }
  }

  const cols = Object.keys(ds[0]);

  // ✅ Build statusText function
  function getStatusText(rows) {
    if (statusFilter) {
      return `SHOWING STATUS: <strong>${statusFilter}</strong> &nbsp;&nbsp; TOTAL: <strong>${rows.length}</strong>`;
    } else {
      const cardObj = cards.find(c => c.id === card);
      if (cardObj && cardObj.statuses && cardObj.statuses.length) {
        if (cardObj.isTotal) {
          return `SHOWING ALL STATUSES &nbsp;&nbsp; TOTAL: <strong>${rows.length}</strong>`;
        } else {
          return `SHOWING STATUS: <strong>${cardObj.statuses.join(", ")}</strong> &nbsp;&nbsp; TOTAL: <strong>${rows.length}</strong>`;
        }
      }
    }
    return "";
  }


// ✅ Filter UI (TEAM + UR STATUS + TAG + Search + Limit + Reset)
const filterUI = `
<div id="filter-bar" class="filter-bar flex flex-wrap items-center gap-2 mb-4 text-sm overflow-x-auto pb-2">

  <label>TEAM:</label>
  <select id="filter-team" class="border p-1 rounded min-w-[90px]">
    <option value="">All</option>
    ${[...new Set(ds.map(r => r.Team).filter(v => v && v !== "Team"))]
        .sort((a,b)=>a.localeCompare(b))
        .map(t=>`<option value="${t}">${t}</option>`).join("")}
  </select>

  <label>UR STATUS:</label>
  <select id="filter-ur" class="border p-1 rounded min-w-[110px]">
    <option value="">All</option>
    ${[...new Set(ds.map(r => r["UR Status"] || r["UR STATUS"])
        .filter(v => v && v.toUpperCase() !== "UR STATUS"))]
        .sort((a,b)=>a.localeCompare(b))
        .map(u=>`<option value="${u}">${u}</option>`).join("")}
  </select>

  <label>TAG:</label>
  <select id="filter-tag" class="border p-1 rounded min-w-[90px]">
    <option value="">ALL</option>
    ${[...new Set(ds.map(r => r.Tag || r.TAG || r.tag)
      .filter(v => v && ["MINOR","MAJOR"].includes(v.toUpperCase())))]
      .map(v=>v.toUpperCase()).sort()
      .map(tag=>`<option value="${tag}">${tag}</option>`).join("")}
  </select>

  <label>Search:</label>
  <input type="text" id="filter-search" placeholder="Search HEAD, TRAILER, JR, JO"
    class="border p-1 rounded w-56 sm:w-60">

  <label>SHOW ITEMS:</label>
  <select id="filter-limit" class="border p-1 rounded min-w-[80px]">
    <option value="">All</option>
    <option value="10">10</option>
    <option value="25">25</option>
    <option value="50">50</option>
  </select>

  <span class="text-gray-600 whitespace-nowrap" id="row-count"></span>

  <button id="reset-filters" class="px-3 py-1 bg-gray-300 hover:bg-gray-400 rounded whitespace-nowrap">
    Reset
  </button>
</div>
`;

 // ✅ Sidebar wrapper with fixed table container
sb.innerHTML = `
  <div class="p-4 rounded-lg ${(themeColors[card] || 'bg-gray-500')} text-white mb-4">
    <button onclick="closeSidebar()" 
      class="mb-2 px-4 py-2 bg-white text-black font-bold rounded-lg shadow">← Back</button>
    <button onclick="exportTableToCSV('export.csv')" 
      class="mt-2 px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">⬇ Export CSV</button>
  </div>

  <div id="status-text" class="mb-4 text-sm font-semibold text-gray-800">
    ${getStatusText(filtered)}
  </div>
  ${filterUI}

  <div class="table-container">
    <table id="exportTable">
      <thead><tr>${cols.map(c => `<th>${c}</th>`).join('')}</tr></thead>
      <tbody></tbody>
    </table>
  </div>
`;

 // ✅ After sb.innerHTML assignment in openSidebar, add this:
sb.querySelectorAll('.column-toggle').forEach(cb => {
  cb.addEventListener('change', function() {
    const colName = this.dataset.col;
    const table = document.getElementById('exportTable');
    if(!table) return;
    const idx = Array.from(table.querySelectorAll('th')).findIndex(th => th.innerText === colName);
    if(idx === -1) return;

    // Toggle column visibility
    table.querySelectorAll('tr').forEach(row => {
      if(row.children[idx]) row.children[idx].style.display = this.checked ? '' : 'none';
    });
  });
});
  const tbody = sb.querySelector("#exportTable tbody");
  const rowCountEl = sb.querySelector("#row-count");
  const statusTextEl = sb.querySelector("#status-text");

  function renderRows(rows) {
    const limit = sb.querySelector("#filter-limit").value;
    let shown = rows;
    if (limit) shown = rows.slice(0, parseInt(limit));
    tbody.innerHTML = shown.map(r =>
      `<tr>${cols.map(c => `<td>${r[c] ?? ''}</td>`).join('')}</tr>`
    ).join('');
    rowCountEl.textContent = `${rows.length} rows (showing ${shown.length})`;

    // ✅ Update header text dynamically
    statusTextEl.innerHTML = getStatusText(rows);
  }
  renderRows(filtered);

  // ✅ Apply filters
  function applyFilters() {
    let rows = [...filtered];
    const team = sb.querySelector("#filter-team").value;
    const ur = sb.querySelector("#filter-ur").value;
    const tag = sb.querySelector("#filter-tag").value;
    const search = sb.querySelector("#filter-search").value.toLowerCase();

    if (team) rows = rows.filter(r => r.Team === team);
    if (ur) rows = rows.filter(r => (r["UR Status"] || r["UR STATUS"]) === ur);
   if (tag) {
  const tagUpper = tag.toUpperCase();
  rows = rows.filter(r =>
    (r.Tag || r.TAG || r.tag || "")
      .toString()
      .trim()
      .toUpperCase() === tagUpper
  );
}

    if (search) {
      rows = rows.filter(r =>
        Object.values(r).some(val => (val ?? "").toString().toLowerCase().includes(search))
      );
    }
    renderRows(rows);
  }

  sb.querySelector("#filter-team").onchange = applyFilters;
  sb.querySelector("#filter-ur").onchange = applyFilters;
  sb.querySelector("#filter-tag").onchange = applyFilters;
  sb.querySelector("#filter-search").oninput = applyFilters;
  sb.querySelector("#filter-limit").onchange = applyFilters;

  // ✅ Reset
  sb.querySelector("#reset-filters").onclick = () => {
    sb.querySelector("#filter-team").value = "";
    sb.querySelector("#filter-ur").value = "";
    sb.querySelector("#filter-tag").value = "";
    sb.querySelector("#filter-search").value = "";
    sb.querySelector("#filter-limit").value = "";
    renderRows(filtered);
  };

}

function closeSidebar() {
  // ✅ restore to default, not forced scrollbars
  document.body.style.overflow = "";    

  const sb = document.getElementById('sidebar');
  sb.classList.remove('open');
  document.getElementById('dashboard').classList.remove('shrink-mode');
  if (activeCard) {
    activeCard.classList.remove("highlighted");
    activeCard = null;
  }
}

/* --- Export Function --- */
function exportTableToCSV(filename){
  const t=document.getElementById("exportTable");if(!t)return;
  const rows=t.querySelectorAll("tr");const csv=[];
  rows.forEach(r=>{const cols=r.querySelectorAll("td,th");const row=[];
    cols.forEach(c=>row.push(c.innerText.replace(/,/g,"")));
    csv.push(row.join(","));});
  const blob=new Blob([csv.join("\n")],{type:"text/csv"});
  const link=document.createElement("a");link.download=filename;
  link.href=URL.createObjectURL(blob);document.body.appendChild(link);link.click();
}
window.addEventListener('resize', function() {
  // Your code to execute when the window is resized
  const newWidth = window.innerWidth;
  const newHeight = window.innerHeight;
  console.log(`Window resized to: ${newWidth}px width, ${newHeight}px height`);

  // You can then perform actions based on the new dimensions,
  // like updating UI elements or modifying styles.
});
/* --- Filter --- */
let selectedRepairLocation = "ALL";
document.getElementById("repairLocationFilter").addEventListener("change", e=>{
  selectedRepairLocation = e.target.value.toUpperCase();
  populateDashboard(fetchedData);
});
/* --- Init --- */
window.addEventListener('DOMContentLoaded',()=>{ renderEmptyDashboard(); loadData(); setInterval(loadData,300000); });
</script>


</body>
</html>
