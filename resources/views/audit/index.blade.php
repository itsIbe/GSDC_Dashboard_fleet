@php
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

AuditLog::create([
    'user_id' => Auth::id(),
    'action' => 'Viewed Audit Logs',
    'ip_address' => request()->ip(),
    'description' => 'Accessed the Audit Logs page',
]);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Audit Logs - GSDC</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>

<style>
  body { overflow-x: hidden; font-family: 'Poppins', sans-serif; }

  /* Sidebar Styles */
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
  .sidebar-menu.closed { left: -250px; }
  .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: none; z-index: 999; }
  .overlay.show { display: block; }

  /* Table Styles */
  .table-striped tr:nth-child(even) { background-color: rgba(230,240,255,0.6); }
  .table-striped tr:hover { background-color: rgba(59,130,246,0.1); }

  .flat-panel {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(8px);
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }
</style>
</head>

<body class="bg-gradient-to-br from-blue-100 via-blue-50 to-white min-h-screen">

<!-- Sidebar -->
<div id="mySidebar" class="sidebar-menu closed flex flex-col justify-between">
  <div>
    <h1 class="text-center text-1xl font-bold mb-6 px-4">
      Great Sierra Development Corporation
    </h1>

    <!-- User Profile -->
    <div class="flex flex-col items-center px-4 mb-6">
      <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}" 
           alt="User Avatar" 
           class="w-20 h-20 rounded-full border-4 border-white shadow-md mb-3">

      <p class="text-lg font-semibold text-white text-center">
        {{ Auth::user()->display_name }}
      </p>
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
    <a href="{{ url('/createuser') }}"><i class="fas fa-user-plus"></i> Create New User</a>
    <a href="{{ route('audit.index') }}" class="active"><i class="fas fa-clipboard-list"></i> Audit Logs</a>
@endif

  </div>

  <!-- Logout -->
  <form method="POST" action="{{ route('logout') }}" class="mt-6 px-4">
    @csrf
    <button type="submit" 
      class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center justify-center gap-2 transition duration-200">
      <i class="fas fa-sign-out-alt"></i> Logout
    </button>
  </form>
</div>

<!-- Sidebar Toggle -->
<div id="overlay" class="overlay" onclick="closeNav()"></div>
<button class="fixed top-4 left-4 z-50 bg-blue-600 text-white px-3 py-2 rounded-full shadow-lg hover:bg-blue-700 transition text-2xl" onclick="openNav()">&#9776;</button>

<!-- Main Content -->
<div id="mainContent" class="transition-all duration-300 ml-0">

  <header class="p-6 border-b bg-white/70 backdrop-blur-md shadow-sm text-center">
    <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-blue-500">
       Audit Logs
    </h1>
    <p class="text-xs text-gray-500 mt-1">Last synced: {{ now() }}</p>
  </header>

  <main class="p-8">
    <!-- Filters -->
    <div class="mb-4 flex flex-wrap items-center gap-3 bg-gradient-to-r from-blue-400 to-blue-600  border border-blue-100 p-4 rounded-lg flat-panel">
      <div class="flex items-center gap-2">
        <label for="userFilter" class="font-semibold text-white-500">User:</label>
        <select id="userFilter" class="border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-blue-500 focus:border-blue-500">
          <option value="all">All Users</option>
          @foreach($logs->groupBy('user_id') as $userId => $userLogs)
            <option value="{{ $userLogs->first()->user->name ?? 'System' }}">
              {{ $userLogs->first()->user->name ?? 'System' }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="flex items-center gap-2">
        <label for="actionFilter" class="font-semibold text-gray-700">Action:</label>
        <select id="actionFilter" class="border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-blue-500 focus:border-blue-500">
          <option value="all">All Actions</option>
          @foreach($logs->groupBy('action') as $action => $entries)
            <option value="{{ $action }}">{{ ucfirst($action) }}</option>
          @endforeach
        </select>
      </div>

      <div class="flex items-center gap-2 flex-grow max-w-md">
        <label for="searchInput" class="font-semibold text-gray-700">Search:</label>
        <input id="searchInput" type="text" placeholder="Search logs..." class="border-gray-300 rounded-lg px-3 py-1 text-sm w-full focus:ring-blue-500 focus:border-blue-500" />
      </div>

      <button id="resetBtn" class="ml-auto bg-gradient-to-r from-red-400 to-red-600  hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg shadow">
        <i class="fas fa-sync-alt mr-1"></i> Reset
      </button>

      <span id="summaryText" class="text-sm text-white w-full text-right mt-2">Showing all logs.</span>
    </div>

    <!-- Table -->
    <div class="relative flat-panel p-4 border border-gray-200 overflow-x-auto" style="max-height: 550px; overflow-y: auto;">
      <!-- Scroll fade (top) -->
      <div class="pointer-events-none absolute top-0 left-0 right-0 h-6 bg-gradient-to-b from-blue-300/40 to-transparent z-10"></div>

      <table class="w-full text-sm text-left text-gray-700 table-striped" id="auditTable">
        <thead class="bg-blue-700 text-white text-xs uppercase sticky top-0 z-20">
          <tr>
            <th class="px-6 py-3">User Name</th>
            <th class="px-6 py-3">User Email</th>
            <th class="px-6 py-3">Action</th>
            <th class="px-6 py-3">Timestamp</th>
            <th class="px-6 py-3">IP Address</th>
            <th class="px-6 py-3">Description</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs as $log)
          <tr class="user-row"
              data-user="{{ $log->user->name ?? 'System' }}"
              data-action="{{ $log->action }}"
              data-search="{{ strtolower(($log->user->name ?? '') . ' ' . ($log->user->email ?? '') . ' ' . ($log->description ?? '')) }}">
            <td class="px-6 py-3">{{ $log->user->name ?? 'System' }}</td>
            <td class="px-6 py-3 text-blue-700">{{ $log->user->email ?? '-' }}</td>
            <td class="px-6 py-3">{{ ucfirst($log->action) }}</td>
            <td class="px-6 py-3">{{ $log->created_at->format('M-d-y H:i:s') }}</td>
            <td class="px-6 py-3">{{ $log->ip_address }}</td>
            <td class="px-6 py-3">{{ $log->description }}</td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center py-4 text-gray-500">No audit logs found.</td></tr>
          @endforelse
        </tbody>
      </table>

      <!-- Scroll fade (bottom) -->
      <div class="pointer-events-none absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-blue-300/40 to-transparent z-10"></div>
    </div>
  </main>
</div>

<!-- Sidebar Script -->
<script>
// Sidebar toggle
function openNav() {
    document.getElementById("mySidebar").classList.remove("closed");
    document.getElementById("overlay").classList.add("show");
}
function closeNav() {
    document.getElementById("mySidebar").classList.add("closed");
    document.getElementById("overlay").classList.remove("show");
}

// Filter elements
const userFilter = document.getElementById('userFilter');
const actionFilter = document.getElementById('actionFilter');
const searchInput = document.getElementById('searchInput');
const resetBtn = document.getElementById('resetBtn');
const summary = document.getElementById('summaryText');

// Dynamically get rows from the table
function getRows() {
    return document.querySelectorAll('#auditTable .user-row');
}

// Apply filters to current rows
function applyFilters() {
    const user = userFilter.value.toLowerCase();
    const action = actionFilter.value.toLowerCase();
    const search = searchInput.value.toLowerCase().trim();
    let count = 0;

    getRows().forEach(row => {
        const matchUser = (user === 'all' || row.dataset.user.toLowerCase() === user);
        const matchAction = (action === 'all' || row.dataset.action.toLowerCase() === action);
        const matchSearch = (!search || row.dataset.search.includes(search));
        const visible = matchUser && matchAction && matchSearch;
        row.style.display = visible ? '' : 'none';
        if (visible) count++;
    });

    let summaryText = `Showing ${count} log${count !== 1 ? 's' : ''}`;
    if (user !== 'all') summaryText += ` for ${userFilter.value}`;
    if (action !== 'all') summaryText += ` (${actionFilter.value})`;
    if (search) summaryText += ` matching “${search}”`;
    summary.textContent = summaryText + '.';
}

// Event listeners
[userFilter, actionFilter].forEach(el => el.addEventListener('change', applyFilters));
searchInput.addEventListener('input', applyFilters);
resetBtn.addEventListener('click', () => {
    userFilter.value = 'all';
    actionFilter.value = 'all';
    searchInput.value = '';
    applyFilters();
});

// Store IDs of existing rows to detect new ones
let existingRowKeys = new Set();

async function fetchLogs() {
    try {
        const res = await fetch("{{ route('audit.fetch') }}");
        const logs = await res.json();

        const tbody = document.querySelector('#auditTable tbody');
        tbody.innerHTML = ''; // clear current rows

        if (logs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-gray-500">No audit logs found.</td></tr>';
            existingRowKeys.clear();
            return;
        }

        const userSet = new Set();
        const actionSet = new Set();

        logs.forEach(log => {
            const row = document.createElement('tr');
            row.classList.add('user-row');

            const userName = log.user ? log.user.name : 'System';
            const rowKey = `${userName}_${log.action}_${log.created_at}`; // unique key
            row.dataset.user = userName;
            row.dataset.action = log.action;
            row.dataset.search = ((log.user?.name ?? '') + ' ' + (log.user?.email ?? '') + ' ' + (log.description ?? '')).toLowerCase();

            row.innerHTML = `
                <td class="px-6 py-3">${userName}</td>
                <td class="px-6 py-3 text-blue-700">${log.user?.email ?? '-'}</td>
                <td class="px-6 py-3">${log.action.charAt(0).toUpperCase() + log.action.slice(1)}</td>
                <td class="px-6 py-3">${new Date(log.created_at).toLocaleString()}</td>
                <td class="px-6 py-3">${log.ip_address}</td>
                <td class="px-6 py-3">${log.description}</td>
            `;
            tbody.appendChild(row);

            // Highlight new rows
            if (!existingRowKeys.has(rowKey)) {
                row.style.backgroundColor = '#ffffff'; // light yellow
                setTimeout(() => {
                    row.style.backgroundColor = '';
                }, 2000);
            }

            existingRowKeys.add(rowKey);
            userSet.add(userName);
            actionSet.add(log.action);
        });

        // Update User Filter Dropdown
        const currentUserValue = userFilter.value;
        userFilter.innerHTML = '<option value="all">All Users</option>';
        Array.from(userSet).sort().forEach(u => {
            const option = document.createElement('option');
            option.value = u;
            option.textContent = u;
            userFilter.appendChild(option);
        });
        userFilter.value = userSet.has(currentUserValue) ? currentUserValue : 'all';

        // Update Action Filter Dropdown
        const currentActionValue = actionFilter.value;
        actionFilter.innerHTML = '<option value="all">All Actions</option>';
        Array.from(actionSet).sort().forEach(a => {
            const option = document.createElement('option');
            option.value = a;
            option.textContent = a.charAt(0).toUpperCase() + a.slice(1);
            actionFilter.appendChild(option);
        });
        actionFilter.value = actionSet.has(currentActionValue) ? currentActionValue : 'all';

        applyFilters(); // reapply filters to new rows

    } catch (error) {
        console.error('Error fetching audit logs:', error);
    }
}

// Initial fetch
fetchLogs();

// Poll every 5 seconds
setInterval(fetchLogs, 5000);
</script>


</body>
</html>
