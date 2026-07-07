<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CEMENT CAPACITY REPORT</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 text-sm">
 <style>
    /* Smooth modal animations */
    .modal-enter {
      opacity: 0;
      transform: scale(0.95);
    }
    .modal-enter-active {
      opacity: 1;
      transform: scale(1);
      transition: opacity 0.25s ease-out, transform 0.25s ease-out;
    }
    .modal-exit-active {
      opacity: 0;
      transform: scale(0.95);
      transition: opacity 0.2s ease-in, transform 0.2s ease-in;
    }
  </style>
  <!-- Header -->
  <div class="mb-6 flex justify-between items-center">
    <h1 class="text-xl font-bold">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;CEMENT CAPACITY REPORT</h1>
    <div>
      <span id="syncStatus" class="text-gray-500">⏳ Syncing...</span>
      <span id="timestamp" class="ml-4 text-gray-500"></span>
    </div>
  </div>

  <!-- 🚚 TRUCK DASHBOARD -->
  <div class="mb-8">
    <h2 class="text-lg font-bold mb-2">Truck Dashboard</h2>
    <div class="overflow-x-auto mb-4">
      <table class="w-full border border-gray-300 text-center text-xs">
        <thead>
          <tr>
            <th rowspan="2" class="border px-2 py-1 bg-white">TOTAL</th>
            <th colspan="4" class="border px-2 py-1 bg-green-500 text-white">AVAILABLE CAPACITY</th>
            <th colspan="4" class="border px-2 py-1 bg-yellow-400 text-white">PENDING JR</th>
            <th colspan="3" class="border px-2 py-1 bg-red-400 text-white">ONGOING JR</th>
          </tr>
          <tr>
            <th class="border px-2 py-1">Idle</th>
            <th class="border px-2 py-1">Available</th>
            <th class="border px-2 py-1">Preloaded</th>
            <th class="border px-2 py-1">On Trip</th>
            <th class="border px-2 py-1">At Yard</th>
            <th class="border px-2 py-1">Outside</th>
            <th class="border px-2 py-1">Approved</th>
            <th class="border px-2 py-1">For Resc.</th>
            <th class="border px-2 py-1">Accepted</th>
            <th class="border px-2 py-1">WFP</th>
            <th class="border px-2 py-1">On Resc.</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="totalCount1" class="border px-2 py-1 font-bold bg-white">0</td>
            <td id="idle1" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="available1" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="preloaded1" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="ontrip1" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="atyard1" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="outside1" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="approved1" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="forresc1" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="accepted1" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="wfp1" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="onresc1" class="border px-2 py-1 cursor-pointer">0</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-4 gap-4 text-center">
      <div class="bg-green-200 p-4 rounded-2xl shadow cursor-pointer">
        <div class="font-bold">Available Capacity</div>
        <div id="totalAvailableCapacity1" class="text-xl font-bold">0</div>
      </div>
      <div class="bg-yellow-200 p-4 rounded-2xl shadow cursor-pointer">
        <div class="font-bold">Pending JR</div>
        <div id="totalPendingJR1" class="text-xl font-bold">0</div>
      </div>
      <div class="bg-red-200 p-4 rounded-2xl shadow cursor-pointer">
        <div class="font-bold">Ongoing JR</div>
        <div id="totalOngoingJR1" class="text-xl font-bold">0</div>
      </div>
      <div class="bg-blue-200 p-4 rounded-2xl shadow">
        <div class="font-bold">Utilization %</div>
        <div id="upPercent1" class="text-xl font-bold">0%</div>
        <div class="text-xs">
          Target: <span id="targetCapacity1">0</span>
          (<select id="targetSelect1" class="border rounded px-1 text-xs">
            <option value="75">75%</option>
            <option value="80" selected>80%</option>
            <option value="90">90%</option>
          </select>)
        </div>
      </div>
    </div>
  </div>

  <!-- 🚛 TRAILER DASHBOARD -->
  <div class="mb-8">
    <h2 class="text-lg font-bold mb-2">Trailer Dashboard</h2>
    <div class="overflow-x-auto mb-4">
      <table class="w-full border border-gray-300 text-center text-xs">
        <thead>
          <tr>
            <th rowspan="2" class="border px-2 py-1 bg-white">TOTAL</th>
            <th colspan="4" class="border px-2 py-1 bg-green-500 text-white">AVAILABLE CAPACITY</th>
            <th colspan="4" class="border px-2 py-1 bg-yellow-400 text-white">PENDING JR</th>
            <th colspan="3" class="border px-2 py-1 bg-red-400 text-white">ONGOING JR</th>
          </tr>
          <tr>
            <th class="border px-2 py-1">Idle</th>
            <th class="border px-2 py-1">Available</th>
            <th class="border px-2 py-1">Preloaded</th>
            <th class="border px-2 py-1">On Trip</th>
            <th class="border px-2 py-1">At Yard</th>
            <th class="border px-2 py-1">Outside</th>
            <th class="border px-2 py-1">Approved</th>
            <th class="border px-2 py-1">For Resc.</th>
            <th class="border px-2 py-1">Accepted</th>
            <th class="border px-2 py-1">WFP</th>
            <th class="border px-2 py-1">On Resc.</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="totalCount2" class="border px-2 py-1 font-bold bg-white">0</td>
            <td id="idle2" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="available2" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="preloaded2" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="ontrip2" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="atyard2" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="outside2" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="approved2" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="forresc2" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="accepted2" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="wfp2" class="border px-2 py-1 cursor-pointer">0</td>
            <td id="onresc2" class="border px-2 py-1 cursor-pointer">0</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-4 gap-4 text-center">
      <div class="bg-green-200 p-4 rounded-2xl shadow cursor-pointer">
        <div class="font-bold">Available Capacity</div>
        <div id="totalAvailableCapacity2" class="text-xl font-bold">0</div>
      </div>
      <div class="bg-yellow-200 p-4 rounded-2xl shadow cursor-pointer">
        <div class="font-bold">Pending JR</div>
        <div id="totalPendingJR2" class="text-xl font-bold">0</div>
      </div>
      <div class="bg-red-200 p-4 rounded-2xl shadow cursor-pointer">
        <div class="font-bold">Ongoing JR</div>
        <div id="totalOngoingJR2" class="text-xl font-bold">0</div>
      </div>
      <div class="bg-blue-200 p-4 rounded-2xl shadow">
        <div class="font-bold">Utilization %</div>
        <div id="upPercent2" class="text-xl font-bold">0%</div>
        <div class="text-xs">
          Target: <span id="targetCapacity2">0</span>
          (<select id="targetSelect2" class="border rounded px-1 text-xs">
            <option value="75">75%</option>
            <option value="80" selected>80%</option>
            <option value="90">90%</option>
          </select>)
        </div>
      </div>
    </div>
  </div>

  <!-- TRUCK MODAL -->
  <div id="dataModal1" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between mb-4">
        <h3 class="text-lg font-bold">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;TRUCK UR SUMMARY</h3>
        <button onclick="document.getElementById('dataModal1').classList.add('hidden')" class="text-red-500">✖</button>
      </div>
      <div class="flex gap-4 mb-2">
        <select id="teamFilter1" class="border rounded px-2 py-1 text-xs"></select>
        <select id="urFilter1" class="border rounded px-2 py-1 text-xs"></select>
        <input id="searchInput1" type="text" placeholder="Search..." class="border rounded px-2 py-1 text-xs">
      </div>
      <div class="flex gap-4 mb-2">
        <span id="rowCount1" class="text-sm"></span>
        <span id="majorCount1" class="text-sm"></span>
        <span id="minorCount1" class="text-sm"></span>
      </div>
      <div id="tableContainer1" class="overflow-x-auto"></div>
    </div>
  </div>

  <!-- TRAILER MODAL -->
  <div id="dataModal2" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between mb-4">
        <h3 class="text-lg font-bold">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;Trailer UR SUMMARY</h3>
        <button onclick="document.getElementById('dataModal2').classList.add('hidden')" class="text-red-500">✖</button>
      </div>
      <div class="flex gap-4 mb-2">
        <select id="teamFilter2" class="border rounded px-2 py-1 text-xs"></select>
        <select id="urFilter2" class="border rounded px-2 py-1 text-xs"></select>
        <input id="searchInput2" type="text" placeholder="Search..." class="border rounded px-2 py-1 text-xs">
      </div>
      <div class="flex gap-4 mb-2">
        <span id="rowCount2" class="text-sm"></span>
        <span id="majorCount2" class="text-sm"></span>
        <span id="minorCount2" class="text-sm"></span>
      </div>
      <div id="tableContainer2" class="overflow-x-auto"></div>
    </div>
  </div>

  <!-- JAVASCRIPT -->
  <script>
    let rows = [];         // Truck
    let trailerRows = [];  // Trailer

    async function fetchData() {
      try {
        const res = await fetch("/fetch-data");
        const data = await res.json();

        // 🚚 Truck
        rows = data.result || [];
        calculateDashboard1(rows);
        populateFilters1(rows);

        // 🚛 Trailer
        trailerRows = data.trailer_data || [];
        calculateDashboard2(trailerRows);
        populateFilters2(trailerRows);

        document.getElementById("syncStatus").innerHTML = "✅ Synced";
        document.getElementById("timestamp").innerText =
          "as of " + new Date().toLocaleString();

        // 🔄 Auto-refresh open modals
        if (!document.getElementById("dataModal1").classList.contains("hidden")) {
          filterModalTable1();
        }
        if (!document.getElementById("dataModal2").classList.contains("hidden")) {
          filterModalTable2();
        }

      } catch (err) {
        console.error("Error fetching:", err);
        document.getElementById("syncStatus").innerHTML = "❌ Sync failed";
      }
    }

    /* ---------------- TRUCK FUNCTIONS ---------------- */
    function calculateDashboard1(rows) {
      if (!rows.length) return;
      const header = rows[0];
      const statusIdx = header.indexOf("UR Status");

      let counts = {idle:0, available:0, preloaded:0, ontrip:0, atyard:0, outside:0,
        approved:0, forresc:0, accepted:0, wfp:0, onresc:0};

      rows.slice(1).forEach(r => {
        const st = r[statusIdx]?.toLowerCase();
        if (st in counts) counts[st]++;
      });

      document.getElementById("idle1").innerText = counts.idle;
      document.getElementById("available1").innerText = counts.available;
      document.getElementById("preloaded1").innerText = counts.preloaded;
      document.getElementById("ontrip1").innerText = counts.ontrip;
      document.getElementById("atyard1").innerText = counts.atyard;
      document.getElementById("outside1").innerText = counts.outside;
      document.getElementById("approved1").innerText = counts.approved;
      document.getElementById("forresc1").innerText = counts.forresc;
      document.getElementById("accepted1").innerText = counts.accepted;
      document.getElementById("wfp1").innerText = counts.wfp;
      document.getElementById("onresc1").innerText = counts.onresc;

      const total = rows.length - 1;
      const availableCap = counts.idle + counts.available + counts.preloaded + counts.ontrip;
      const pendingJR = counts.atyard + counts.outside + counts.approved + counts.forresc;
      const ongoingJR = counts.accepted + counts.wfp + counts.onresc;
      const up = total ? ((availableCap / total) * 100).toFixed(1) : 0;

      document.getElementById("totalCount1").innerText = total;
      document.getElementById("totalAvailableCapacity1").innerText = availableCap;
      document.getElementById("totalPendingJR1").innerText = pendingJR;
      document.getElementById("totalOngoingJR1").innerText = ongoingJR;
      document.getElementById("upPercent1").innerText = up + "%";
      document.getElementById("targetCapacity1").innerText =
        Math.round(total * (parseInt(document.getElementById("targetSelect1").value) / 100));
    }

    function populateFilters1(rows) {
      if (!rows.length) return;
      const header = rows[0];
      const teamIdx = header.indexOf("Team");
      const statusIdx = header.indexOf("UR Status");

      const teamSet = new Set();
      const statusSet = new Set();

      rows.slice(1).forEach(r => {
        teamSet.add(r[teamIdx]);
        statusSet.add(r[statusIdx]);
      });

      const teamFilter = document.getElementById("teamFilter1");
      teamFilter.innerHTML = `<option value="">All</option>`;
      teamSet.forEach(t => teamFilter.innerHTML += `<option>${t}</option>`);

      const urFilter = document.getElementById("urFilter1");
      urFilter.innerHTML = `<option value="">All</option>`;
      statusSet.forEach(s => urFilter.innerHTML += `<option>${s}</option>`);
    }

    function openModal1(status) {
      document.getElementById("dataModal1").classList.remove("hidden");
      filterModalTable1(status);
    }

    function filterModalTable1(filterStatus = "") {
      if (!rows.length) return;
      const header = rows[0];
      const statusIdx = header.indexOf("UR Status");
      const teamIdx = header.indexOf("Team");
      const urFilter = document.getElementById("urFilter1").value.toLowerCase();
      const teamFilter = document.getElementById("teamFilter1").value.toLowerCase();
      const searchVal = document.getElementById("searchInput1").value.toLowerCase();

      let filtered = rows.slice(1).filter(r => {
        const s = r[statusIdx]?.toLowerCase() || "";
        const t = r[teamIdx]?.toLowerCase() || "";
        return (!filterStatus || s === filterStatus.toLowerCase()) &&
               (!urFilter || s === urFilter) &&
               (!teamFilter || t === teamFilter) &&
               (!searchVal || r.join(" ").toLowerCase().includes(searchVal));
      });

      let html = "<table class='w-full border text-xs text-center'><thead><tr>";
      header.forEach(h => html += `<th class='border px-2 py-1'>${h}</th>`);
      html += "</tr></thead><tbody>";
      filtered.forEach(r => {
        html += "<tr>" + r.map(c => `<td class='border px-2 py-1'>${c}</td>`).join("") + "</tr>";
      });
      html += "</tbody></table>";

      document.getElementById("tableContainer1").innerHTML = html;
      document.getElementById("rowCount1").innerText = "Rows: " + filtered.length;
      document.getElementById("majorCount1").innerText = "Major: " + filtered.filter(r => r.includes("Major")).length;
      document.getElementById("minorCount1").innerText = "Minor: " + filtered.filter(r => r.includes("Minor")).length;
    }

    /* ---------------- TRAILER FUNCTIONS ---------------- */
    function calculateDashboard2(rows) {
      if (!rows.length) return;
      const header = rows[0];
      const statusIdx = header.indexOf("UR Status");

      let counts = {idle:0, available:0, preloaded:0, ontrip:0, atyard:0, outside:0,
        approved:0, forresc:0, accepted:0, wfp:0, onresc:0};

      rows.slice(1).forEach(r => {
        const st = r[statusIdx]?.toLowerCase();
        if (st in counts) counts[st]++;
      });

      document.getElementById("idle2").innerText = counts.idle;
      document.getElementById("available2").innerText = counts.available;
      document.getElementById("preloaded2").innerText = counts.preloaded;
      document.getElementById("ontrip2").innerText = counts.ontrip;
      document.getElementById("atyard2").innerText = counts.atyard;
      document.getElementById("outside2").innerText = counts.outside;
      document.getElementById("approved2").innerText = counts.approved;
      document.getElementById("forresc2").innerText = counts.forresc;
      document.getElementById("accepted2").innerText = counts.accepted;
      document.getElementById("wfp2").innerText = counts.wfp;
      document.getElementById("onresc2").innerText = counts.onresc;

      const total = rows.length - 1;
      const availableCap = counts.idle + counts.available + counts.preloaded + counts.ontrip;
      const pendingJR = counts.atyard + counts.outside + counts.approved + counts.forresc;
      const ongoingJR = counts.accepted + counts.wfp + counts.onresc;
      const up = total ? ((availableCap / total) * 100).toFixed(1) : 0;

      document.getElementById("totalCount2").innerText = total;
      document.getElementById("totalAvailableCapacity2").innerText = availableCap;
      document.getElementById("totalPendingJR2").innerText = pendingJR;
      document.getElementById("totalOngoingJR2").innerText = ongoingJR;
      document.getElementById("upPercent2").innerText = up + "%";
      document.getElementById("targetCapacity2").innerText =
        Math.round(total * (parseInt(document.getElementById("targetSelect2").value) / 100));
    }

    function populateFilters2(rows) {
      if (!rows.length) return;
      const header = rows[0];
      const teamIdx = header.indexOf("Team");
      const statusIdx = header.indexOf("UR Status");

      const teamSet = new Set();
      const statusSet = new Set();

      rows.slice(1).forEach(r => {
        teamSet.add(r[teamIdx]);
        statusSet.add(r[statusIdx]);
      });

      const teamFilter = document.getElementById("teamFilter2");
      teamFilter.innerHTML = `<option value="">All</option>`;
      teamSet.forEach(t => teamFilter.innerHTML += `<option>${t}</option>`);

      const urFilter = document.getElementById("urFilter2");
      urFilter.innerHTML = `<option value="">All</option>`;
      statusSet.forEach(s => urFilter.innerHTML += `<option>${s}</option>`);
    }

    function openModal2(status) {
      document.getElementById("dataModal2").classList.remove("hidden");
      filterModalTable2(status);
    }

    function filterModalTable2(filterStatus = "") {
      if (!trailerRows.length) return;
      const header = trailerRows[0];
      const statusIdx = header.indexOf("UR Status");
      const teamIdx = header.indexOf("Team");
      const urFilter = document.getElementById("urFilter2").value.toLowerCase();
      const teamFilter = document.getElementById("teamFilter2").value.toLowerCase();
      const searchVal = document.getElementById("searchInput2").value.toLowerCase();

      let filtered = trailerRows.slice(1).filter(r => {
        const s = r[statusIdx]?.toLowerCase() || "";
        const t = r[teamIdx]?.toLowerCase() || "";
        return (!filterStatus || s === filterStatus.toLowerCase()) &&
               (!urFilter || s === urFilter) &&
               (!teamFilter || t === teamFilter) &&
               (!searchVal || r.join(" ").toLowerCase().includes(searchVal));
      });

      let html = "<table class='w-full border text-xs text-center'><thead><tr>";
      header.forEach(h => html += `<th class='border px-2 py-1'>${h}</th>`);
      html += "</tr></thead><tbody>";
      filtered.forEach(r => {
        html += "<tr>" + r.map(c => `<td class='border px-2 py-1'>${c}</td>`).join("") + "</tr>";
      });
      html += "</tbody></table>";

      document.getElementById("tableContainer2").innerHTML = html;
      document.getElementById("rowCount2").innerText = "Rows: " + filtered.length;
      document.getElementById("majorCount2").innerText = "Major: " + filtered.filter(r => r.includes("Major")).length;
      document.getElementById("minorCount2").innerText = "Minor: " + filtered.filter(r => r.includes("Minor")).length;
    }

    /* ---------------- EVENTS ---------------- */
    document.getElementById("targetSelect1").addEventListener("change", () => calculateDashboard1(rows));
    document.getElementById("targetSelect2").addEventListener("change", () => calculateDashboard2(trailerRows));
    document.getElementById("teamFilter1").addEventListener("change", () => filterModalTable1());
    document.getElementById("urFilter1").addEventListener("change", () => filterModalTable1());
    document.getElementById("searchInput1").addEventListener("input", () => filterModalTable1());
    document.getElementById("teamFilter2").addEventListener("change", () => filterModalTable2());
    document.getElementById("urFilter2").addEventListener("change", () => filterModalTable2());
    document.getElementById("searchInput2").addEventListener("input", () => filterModalTable2());

    // Attach modal clicks
    ["idle","available","preloaded","ontrip","atyard","outside","approved","forresc","accepted","wfp","onresc"]
      .forEach(id => document.getElementById(id+"1").addEventListener("click", () => openModal1(id)));
    ["idle","available","preloaded","ontrip","atyard","outside","approved","forresc","accepted","wfp","onresc"]
      .forEach(id => document.getElementById(id+"2").addEventListener("click", () => openModal2(id)));

    // Initial fetch + auto-refresh every 5 mins
     fetchData();
  setInterval(fetchData, 5 * 60 * 1000);
  </script>
</body>
</html>
