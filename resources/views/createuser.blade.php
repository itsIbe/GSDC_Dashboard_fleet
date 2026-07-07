    @php
    use App\Models\AuditLog;
AuditLog::create([
    'user_id' => Auth::id(),
    'action' => 'Created new user',
    'ip_address' => request()->ip(),
    'description' => 'Cancelled creating new user',
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
<title>GSDC-Create new user</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/createuser.css') }}">

<style>
  body { overflow-x: hidden; font-family: 'Poppins', sans-serif; transition: all 0.3s; }
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
    <a href="{{ url('/drivercapacitydashboard') }}"><i class="fas fa-truck"></i> Driver Capacity Dashboard</a>
@endif

@if($user->isAdmin())
    <!-- Links visible only to admin -->
    <a href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a>
    <a href="{{ url('/fleetcapacitydashboard') }}"><i class="fas fa-chart-bar"></i> Fleet Capacity Dashboard</a>
    <a href="{{ url('/drivercapacitydashboard') }}"><i class="fas fa-truck"></i> Driver Capacity Dashboard</a>
    <a href="{{ url('/createuser') }}" class="active"><i class="fas fa-user-plus"></i> Create New User</a>
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
    <h3 class="text-2xl md:text-sm font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-500 text-center">
   Great Sierra Development Corporation
  </h3>
  <h1 class="text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-500 text-center">
   Create new user
  </h1>
  <div class="text-center mt-1">
    <p class="text-xs text-gray-500">
      Last synced: <span id="lastSynced"></span>
    </p>
  </div>
</header>


<!-- MAIN CONTENT -->
<div class="pt-24 md:pt-28 flex flex-col md:flex-row items-start gap-6 p-4">

  <div class="wrapper">
      <div class="title-text">
        <div class="title cement">CEMENT FORM</div>
        <div class="title cargo">CARGO FORM</div>
      </div>
      <div class="form-container">
        <div class="slide-controls">
          <input type="radio" name="slide" id="cement" checked>
          <input type="radio" name="slide" id="cargo">
          <label for="cement" class="slide cement">CEMENT</label>
          <label for="cargo" class="slide cargo">CARGO</label>
          <div class="slider-tab"></div>
        </div>
        <div class="form-inner">
        <form action="#" class="cement">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="field"> First Name
                <input type="text" placeholder="" required>
        </div>
        <div class="field"> Last Name
                <input type="text" placeholder="" required>
        </div>
        <div class="field"> Email Address
                <input type="text" placeholder="" required>
        </div>
<div class="field"> Role
  <select required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
    <option value="" disabled selected>Select Role</option>
    <option>Admin</option>
    <option>User</option>
  </select>
</div>

        <div class="field">Department
                <input type="text" placeholder="" required>
        </div>
        <div class="field">Location
                <input type="text" placeholder="" required>
        </div>
        <div class="field">Position
                <input type="text" placeholder="" required>
        </div>
<div class="field">Business Unit(BU)
  <select required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
    <option value="" disabled selected></option>
    <option>SBUO-1A</option>
    <option>SBUO-1B</option>
    <option>SBUO-1C</option>
    <option>SBUO-1D</option>
    <option>SBUO-2A</option>
    <option>SBUO-2B</option>
    <option>SBUO-3A</option>
    <option>SBUO-4A</option>
  </select>
</div>
        <div class="field">Password
                <input type="password" placeholder="" required>
        </div>
        <div class="field">Confirm Password
                <input type="password" placeholder="" required>
        </div>
    </div>
<div class="pt-3 md:pt-3 flex flex-col md:flex-row items-start gap-6 p-4"></div>
  <div class="field btn mt-6">
    <div class="btn-layer"></div>
    <input type="submit" value="Create">
  </div>
</form>
 <form action="#" class="cargo">
         <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="field"> First Name
                <input type="text" placeholder="" required>
        </div>
        <div class="field"> Last Name
                <input type="text" placeholder="" required>
        </div>
        <div class="field"> Email Address
                <input type="text" placeholder="" required>
        </div>
<div class="field"> Role
  <select required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
    <option value="" disabled selected>Select Role</option>
    <option>Admin</option>
    <option>User</option>
  </select>
</div>

        <div class="field">Department
                <input type="text" placeholder="" required>
        </div>
        <div class="field">Location
                <input type="text" placeholder="" required>
        </div>
        <div class="field">Position
                <input type="text" placeholder="" required>
        </div>
<div class="field">Business Unit(BU)
  <select required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
    <option value="" disabled selected></option>
    <option>CARGO-1</option>
    <option>CARGO-2</option>
    <option>CARGO-3</option>
    <option>CARGO-4</option>
    <option>ZION LUZON</option>
    <option>ZION BUKIDNON</option>
    <option>BD-Port</option>
    <option>VTS</option>
    <option>J Express</option>
  </select>
</div>
        <div class="field">Password
                <input type="password" placeholder="" required>
        </div>
        <div class="field">Confirm Password
                <input type="password" placeholder="" required>
        </div>
    </div>
<div class="pt-3 md:pt-3 flex flex-col md:flex-row items-start gap-6 p-4"></div>
  <div class="field btn mt-6">
    <div class="btn-layer"></div>
    <input type="submit" value="Create">
  </div>
</form>
        </div>
      </div>
    </div>
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

 const cementText = document.querySelector(".title-text .cement");
      const cementForm = document.querySelector("form.cement");
      const cementBtn = document.querySelector("label.cement");
      const cargoBtn = document.querySelector("label.cargo");
      const cargoLink = document.querySelector("form .cargo-link a");
      cargoBtn.onclick = (()=>{
        cementForm.style.marginLeft = "-50%";
        cementText.style.marginLeft = "-50%";
      });
      cementBtn.onclick = (()=>{
        cementForm.style.marginLeft = "0%";
        cementText.style.marginLeft = "0%";
      });
      cargoLink.onclick = (()=>{
        cargoBtn.click();
        return false;
      });
</script>
</body>
</html>
