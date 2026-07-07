    @php
    use App\Models\AuditLog;
AuditLog::create([
    'user_id' => Auth::id(),
    'action' => 'Driver Capacity Dashboard',
    'ip_address' => request()->ip(),
    'description' => 'Navigated Dashboard',
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

<meta charset="UTF-8">
<title>Cement Truck Capacity Summary</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
<style>
  body { overflow-x: hidden; font-family: Arial, sans-serif; transition: all 0.3s; }
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
    display: flex; align-items: center; gap: 12px; transition: 0.3s;
  }
  .sidebar-menu a:hover { color: #2563eb; background-color: #ffffff; }
  .sidebar-menu a.active { background-color: #1e40af; font-weight: bold; }
  .sidebar-menu.open { left: 0; }
  .sidebar-menu.closed { left: -250px; }
  .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: none; z-index: 999; }
  .overlay.show { display: block; }
  .clickable { cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; }
  .clickable:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 8px 16px rgba(0,0,0,0.2); }
  .clickable:active { transform: scale(0.98); }
</style>
</head>
<body class="bg-gray-50 font-poppins">

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
    <a href="{{ url('/fleetcapacitydashboard') }}"><i class="fas fa-chart-bar"></i> Fleet Capacity Dashboard</a>
    <a href="{{ url('/drivercapacitydashboard') }}" class="active"><i class="fas fa-truck"></i> Driver Capacity Dashboard</a>
@endif

@if($user->isAdmin())
    <!-- Links visible only to admin -->
    <a href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a>
    <a href="{{ url('/fleetcapacitydashboard') }}"><i class="fas fa-chart-bar"></i> Fleet Capacity Dashboard</a>
    <a href="{{ url('/drivercapacitydashboard') }}" class="active"><i class="fas fa-truck"></i> Driver Capacity Dashboard</a>
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
<button class="fixed top-4 left-4 z-50 bg-blue-600 text-white px-3 py-2 rounded-full shadow-lg hover:bg-blue-700 transition text-2xl" onclick="openNav()">&#9776;</button>

<!-- ✅ Fixed Top Bar (Compact Fleet Style) -->
<header class="fixed top-0 left-0 w-full bg-white shadow-sm border-b z-40 flex flex-col gap-1 px-4 py-3">
  <h1 class="text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-500 text-center">
   Driver Capacity Dashboard
  </h1>
  <div class="text-center mt-1">
    <p class="text-xs text-gray-500">
      Last synced: <span id="lastSynced"></span>
    </p>
  </div>
</header>
<!-- Add padding so content isn't hidden under fixed header -->
<div class="pt-32"></div>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- TOTAL -->
    <div id="card-total" class="clickable bg-gradient-to-r from-blue-400 to-blue-600 p-6 rounded-2xl shadow-md text-white">
      <h2 class="text-xl font-bold">TOTAL</h2>
      <p class="value text-5xl font-extrabold mt-4">0</p>
    </div>
    <!-- ACTIVE -->
    <div id="card-active" class="clickable bg-gradient-to-r from-green-400 to-green-600 p-6 rounded-2xl shadow-md text-white">
      <h2 class="text-xl font-bold">ACTIVE</h2>
      <p class="value text-5xl font-extrabold mt-4">0</p>
      <div class="mt-6 grid grid-cols-2 gap-4">
        <div id="count-present" class="clickable bg-white text-gray-700 p-3 rounded-xl shadow text-center">
          <p class="font-semibold">PRESENT</p>
          <p class="value text-lg text-green-600">0</p>
        </div>
        <div id="count-ur_absent" class="clickable bg-white text-gray-700 p-3 rounded-xl shadow text-center">
          <p class="font-semibold">UR ABSENT</p>
          <p class="value text-lg text-red-500">0</p>
        </div>
        <div id="count-absent" class="clickable bg-white text-gray-700 p-3 rounded-xl shadow text-center">
          <p class="font-semibold">ABSENT</p>
          <p class="value text-lg text-red-500">0</p>
        </div>
      </div>
    </div>
    <!-- INACTIVE -->
    <div id="card-inactive" class="clickable bg-gradient-to-r from-red-400 to-red-600 p-6 rounded-2xl shadow-md text-white">
      <h2 class="text-xl font-bold">INACTIVE</h2>
      <p class="value text-5xl font-extrabold mt-4">0</p>
      <div class="mt-6 grid grid-cols-2 gap-4">
        <div id="count-sick_leave" class="clickable bg-white text-gray-700 p-3 rounded-xl shadow text-center">
          <p class="font-semibold">SICK LEAVE</p>
          <p class="value text-lg text-red-500">0</p>
        </div>
        <div id="count-suspended" class="clickable bg-white text-gray-700 p-3 rounded-xl shadow text-center">
          <p class="font-semibold">SUSPENDED</p>
          <p class="value text-lg text-red-500">0</p>
        </div>
        <div id="count-vacation" class="clickable bg-white text-gray-700 p-3 rounded-xl shadow text-center">
          <p class="font-semibold">VACATION</p>
          <p class="value text-lg text-red-500">0</p>
        </div>
        <div id="count-hold" class="clickable bg-white text-gray-700 p-3 rounded-xl shadow text-center">
          <p class="font-semibold">HOLD</p>
          <p class="value text-lg text-red-500">0</p>
        </div>
        <div id="count-awol" class="clickable bg-white text-gray-700 p-3 rounded-xl shadow text-center">
          <p class="font-semibold">AWOL</p>
          <p class="value text-lg text-red-500">0</p>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-6">
    <h2 class="text-lg font-bold text-gray-700 mb-3">Detailed Breakdown</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-2">
      <div id="count-running" class="clickable bg-gradient-to-r from-green-300 to-green-500 p-4 rounded-xl text-center text-white shadow">
        <p class="font-semibold">RUNNING</p>
        <p class="value text-2xl font-bold">0</p>
      </div>
      <div id="count-available" class="clickable bg-gradient-to-r from-green-300 to-green-500 p-4 rounded-xl text-center text-white shadow">
        <p class="font-semibold">AVAILABLE</p>
        <p class="value text-2xl font-bold">0</p>
      </div>
      <div id="count-driver_preparing" class="clickable bg-gradient-to-r from-yellow-300 to-yellow-500 p-4 rounded-xl text-center text-white shadow">
        <p class="font-semibold">DRIVER PREPARING</p>
        <p class="value text-2xl font-bold">0</p>
      </div>
      <div id="count-driver_available" class="clickable bg-gradient-to-r from-green-300 to-green-500 p-4 rounded-xl text-center text-white shadow">
        <p class="font-semibold">DRIVER AVAILABLE</p>
        <p class="value text-2xl font-bold">0</p>
      </div>
      <div id="count-for_rescue" class="clickable bg-gradient-to-r from-red-300 to-red-500 p-4 rounded-xl text-center text-white shadow">
        <p class="font-semibold">FOR RESCUE</p>
        <p class="value text-2xl font-bold">0</p>
      </div>
      <div id="count-ur_driver" class="clickable bg-gradient-to-r from-blue-300 to-blue-500 p-4 rounded-xl text-center text-white shadow">
        <p class="font-semibold">UR DRIVER</p>
        <p class="value text-2xl font-bold">0</p>
      </div>
      <div id="count-ur_rest" class="clickable bg-gradient-to-r from-gray-400 to-gray-600 p-4 rounded-xl text-center text-white shadow">
        <p class="font-semibold">UR REST</p>
        <p class="value text-2xl font-bold">0</p>
      </div>
      <div id="count-rest" class="clickable bg-gradient-to-r from-gray-400 to-gray-600 p-4 rounded-xl text-center text-white shadow">
        <p class="font-semibold">REST</p>
        <p class="value text-2xl font-bold">0</p>
      </div>
    </div>
  </div>
  <div class="max-w-7xl mx-auto p-6">
</div>
<script>
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

const links = document.querySelectorAll(".sidebar-menu a");
links.forEach(link => {
  link.addEventListener("click", function() {
    links.forEach(l => l.classList.remove("active"));
    this.classList.add("active");
  });
});
</script>
</body>
</html>
