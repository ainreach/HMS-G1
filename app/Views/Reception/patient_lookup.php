<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patient Lookup</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Patient Lookup</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Reception navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/receptionist') ?>">Overview</a>
  <a href="<?= site_url('reception/patients') ?>">Patient Management</a>
  <a href="<?= site_url('reception/appointments') ?>">Appointment Management</a>
  <a href="<?= site_url('reception/patient-lookup') ?>" class="active" aria-current="page">Patient Lookup</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Search Patients</h2></div>
      <div class="panel-body">
        <div style="display:flex;gap:8px">
          <input id="q" type="search" placeholder="Search by name, patient ID, phone" style="flex:1;padding:8px;border:1px solid #e5e7eb;border-radius:8px" />
          <button id="btnSearch" class="btn" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px">Search</button>
        </div>
        <div id="results" style="margin-top:12px;overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Name</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient ID</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Phone</th>
              </tr>
            </thead>
            <tbody id="tbody"></tbody>
          </table>
        </div>
      </div>
    </section>
  </main></div>
<script>
document.getElementById('btnSearch').addEventListener('click', runSearch);
document.getElementById('q').addEventListener('keydown', function(e){ if(e.key==='Enter'){ runSearch(); }});
function runSearch(){
  const q = document.getElementById('q').value.trim();
  const url = '<?= site_url('reception/patients/search') ?>?q=' + encodeURIComponent(q);
  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
    .then(r => r.json())
    .then(rows => {
      const tbody = document.getElementById('tbody');
      tbody.innerHTML = '';
      if(!rows || rows.length === 0){
        tbody.innerHTML = '<tr><td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No results.</td></tr>';
        return;
      }
      rows.forEach(p => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td style="padding:8px;border-bottom:1px solid #f3f4f6">${escapeHtml((p.first_name||'')+' '+(p.last_name||''))}</td>
                        <td style=\"padding:8px;border-bottom:1px solid #f3f4f6\">${escapeHtml(p.patient_id||'')}</td>
                        <td style=\"padding:8px;border-bottom:1px solid #f3f4f6\">${escapeHtml(p.phone||'N/A')}</td>`;
        tbody.appendChild(tr);
      });
    })
    .catch(()=>{
      const tbody = document.getElementById('tbody');
      tbody.innerHTML = '<tr><td colspan="3" style="padding:10px;color:#b91c1c;text-align:center">Search failed.</td></tr>';
    });
}
function escapeHtml(str){
  return String(str).replace(/[&<>"]+/g, function(s){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[s]); });
}
</script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>


