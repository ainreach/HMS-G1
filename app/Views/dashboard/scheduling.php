<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scheduling</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <style>
    .table th, .table td { padding:8px; border-bottom:1px solid #e5e7eb; text-align:left }
  </style>
 </head><body>
  <main class="content" style="max-width:1000px;margin:20px auto;padding:16px">
    <?php 
      $role = session('role');
      $backUrl = '/dashboard';
      if ($role === 'admin') $backUrl = '/dashboard/admin';
      elseif ($role === 'receptionist') $backUrl = '/dashboard/receptionist';
      elseif ($role === 'doctor') $backUrl = '/dashboard/doctor';
      elseif ($role === 'nurse') $backUrl = '/dashboard/nurse';
      elseif ($role === 'lab_staff') $backUrl = '/dashboard/lab';
      elseif ($role === 'pharmacist') $backUrl = '/dashboard/pharmacist';
      elseif ($role === 'accountant') $backUrl = '/dashboard/accountant';
      elseif ($role === 'it_staff') $backUrl = '/dashboard/it';
    ?>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <div style="display:flex;align-items:center;gap:10px">
        <a class="btn" href="<?= site_url($backUrl) ?>" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Back</a>
        <h1 style="margin:0">Scheduling</h1>
      </div>
      <?php if (in_array($role, ['receptionist','admin'], true)) : ?>
        <a class="btn" href="<?= site_url('reception/appointments/new') ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">New Appointment</a>
      <?php endif; ?>
    </div>

    <div class="panel">
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th>Appt #</th>
              <th>Patient ID</th>
              <th>Doctor ID</th>
              <th>Date</th>
              <th>Time</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($appointments)) : foreach ($appointments as $a) : ?>
              <tr>
                <td><?= esc($a['appointment_number']) ?></td>
                <td><?= esc($a['patient_id']) ?></td>
                <td><?= esc($a['doctor_id']) ?></td>
                <td><?= esc(date('Y-m-d', strtotime($a['appointment_date']))) ?></td>
                <td><?= esc($a['appointment_time']) ?></td>
                <td><?= esc($a['status']) ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="6" style="padding:12px;text-align:center;color:#6b7280">No appointments found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
 </body></html>
=======
<?php /* Converted from scheduling.html */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scheduling - FIFTY 50 MEDTECHIE</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <header class="dash-topbar">
    <div class="topbar-inner">
      <button class="menu-btn" id="btnToggle" aria-label="Toggle menu"><i class="fa-solid fa-bars"></i></button>
      <div class="brand">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="FIFTY 50 MEDTECHIE" />
        <div class="brand-text">
          <h2>FIFTY 50 MEDTECHIE</h2>
          <small>"Trust Us... If You’re Feeling Lucky."</small>
        </div>
      </div>
      <div class="top-right">
        <select id="roleSel" title="Role"></select>
        <select id="branchSel" title="Branch"></select>
        <span class="role"><i class="fa-regular fa-user"></i> <span id="roleText">Role</span></span>
        <span class="role" style="margin-left:8px;"><i class="fa-solid fa-code-branch"></i> <span id="branchText">Branch</span></span>
      </div>
    </div>
  </header>

  <div class="layout">
    <aside class="simple-sidebar" id="sidebar">
      <nav class="side-nav">
        <a href="javascript:void(0)" onclick="goto('dashboard')" data-feature="dashboard">Dashboard</a>
        <a href="javascript:void(0)" onclick="goto('records')" data-feature="ehr">Registration & Records</a>
        <a href="javascript:void(0)" onclick="goto('scheduling')" class="active" data-feature="scheduling">Scheduling</a>
        <a href="javascript:void(0)" onclick="goto('billing')" data-feature="billing">Billing & Payment</a>
        <a href="javascript:void(0)" onclick="goto('laboratory')" data-feature="laboratory">Laboratory & Diagnostic</a>
        <a href="javascript:void(0)" onclick="goto('pharmacy')" data-feature="pharmacy">Pharmacy</a>
        <a href="javascript:void(0)" onclick="goto('reports')" data-feature="reports">Reports & Analytics</a>
      </nav>
    </aside>

    <main class="content">
      <h1 class="page-title">Scheduling</h1>

      <section class="actions">
        <button class="action-btn" id="btnNew"><i class="fa-solid fa-calendar-plus"></i> New Appointment</button>
        <button class="action-btn" id="btnToday"><i class="fa-solid fa-calendar-day"></i> Today</button>
        <button class="action-btn" id="btnThisWeek"><i class="fa-solid fa-calendar-week"></i> This Week</button>
        <button class="action-btn" id="btnClear"><i class="fa-solid fa-broom"></i> Clear Demo Data</button>
      </section>

      <section class="form-container" id="formCard" style="display:none;">
        <h2><i class="fa-solid fa-clock"></i> Book Appointment</h2>
        <div class="row">
          <div>
            <label for="patient">Patient Name</label>
            <input id="patient" type="text" placeholder="e.g., Juan Dela Cruz">
          </div>
          <div>
            <label for="doctor">Doctor</label>
            <select id="doctor"></select>
          </div>
        </div>
        <div class="row">
          <div>
            <label for="date">Date</label>
            <input id="date" type="date">
          </div>
          <div>
            <label for="time">Time</label>
            <input id="time" type="time">
          </div>
        </div>
        <div class="row">
          <div>
            <label for="duration">Duration (minutes)</label>
            <select id="duration">
              <option value="15">15</option>
              <option value="30" selected>30</option>
              <option value="45">45</option>
              <option value="60">60</option>
            </select>
          </div>
          <div>
            <label for="status">Status</label>
            <select id="status">
              <option>Scheduled</option>
              <option>Confirmed</option>
              <option>Completed</option>
              <option>Cancelled</option>
              <option>No-Show</option>
            </select>
          </div>
        </div>
        <label for="reason">Reason / Notes</label>
        <input id="reason" type="text" placeholder="e.g., Follow-up checkup">
        <div style="margin-top: 12px; display: flex; gap: 8px;">
          <button class="submit-btn" id="btnSave">Save</button>
          <button class="btn-view" id="btnCancel" type="button">Cancel</button>
        </div>
      </section>

      <section class="form-container">
        <h3><i class="fa-solid fa-filter"></i> Filters</h3>
        <div class="row">
          <div>
            <label for="filterDate">Date</label>
            <input id="filterDate" type="date">
          </div>
          <div>
            <label for="filterDoctor">Doctor</label>
            <input id="filterDoctor" type="text" placeholder="All">
          </div>
        </div>
      </section>

      <section class="form-container">
        <h3><i class="fa-solid fa-calendar-check"></i> Upcoming Appointments</h3>
        <div id="apptList"></div>
      </section>
    </main>
  </div>
  <script>
    window.APP_BASE = '<?= rtrim(site_url('/'), '/') ?>/';
    window.SITE_URL = '<?= rtrim(site_url(), '/') ?>';
  </script>
  <script src="<?= base_url('assets/js/script.js') ?>"></script>
  <script src="<?= base_url('assets/js/rbac.js') ?>"></script>
  <script>
    const btn = document.getElementById('btnToggle');
    const sidebar = document.getElementById('sidebar');
    if (btn && sidebar) {
      btn.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
    }

    const KEY = 'hms_appointments';
    const appts = {
      all: () => JSON.parse(localStorage.getItem(KEY) || '[]'),
      saveAll: (arr) => localStorage.setItem(KEY, JSON.stringify(arr)),
      add: (item) => { const a = appts.all(); a.push(item); appts.saveAll(a); },
      update: (id, patch) => { const a = appts.all().map(x => x.id===id? { ...x, ...patch }: x); appts.saveAll(a); },
      remove: (id) => { const a = appts.all().filter(x => x.id !== id); appts.saveAll(a); },
    };

    const formCard = document.getElementById('formCard');
    const patient = document.getElementById('patient');
    const doctor = document.getElementById('doctor');
    const date = document.getElementById('date');
    const time = document.getElementById('time');
    const duration = document.getElementById('duration');
    const status = document.getElementById('status');
    const reason = document.getElementById('reason');
    const apptList = document.getElementById('apptList');
    const filterDate = document.getElementById('filterDate');
    const filterDoctor = document.getElementById('filterDoctor');

    document.getElementById('btnNew').onclick = () => { formCard.style.display = 'block'; };
    document.getElementById('btnCancel').onclick = () => { formCard.style.display = 'none'; clearForm(); };
    document.getElementById('btnClear').onclick = () => { localStorage.removeItem(KEY); render(); };
    document.getElementById('btnToday').onclick = () => { filterDate.valueAsDate = new Date(); render(); };
    document.getElementById('btnThisWeek').onclick = () => { filterDate.valueAsDate = new Date(); render(); };

    let editingId = null;

    document.getElementById('btnSave').onclick = () => {
      if (!patient.value || !doctor.value || !date.value || !time.value) {
        alert('Please complete all fields.');
        return;
      }
      const id = editingId || ('A' + Math.random().toString(36).slice(2, 8).toUpperCase());
      const start = new Date(date.value + 'T' + time.value);
      const dur = Number(duration.value || 30);
      const end = new Date(start.getTime() + dur*60000);

      const all = appts.all().filter(a => a.id !== id);
      const conflict = all.some(a => a.doctor === doctor.value && (start < (new Date(new Date(a.when).getTime() + (Number(a.duration||30))*60000)) && (new Date(a.when)) < end) && a.status !== 'Cancelled');
      if (conflict) { alert('Conflict: Selected doctor already has an appointment during this time.'); return; }

      const item = { id, patient: patient.value, doctor: doctor.value, reason: reason.value, when: start.toISOString(), duration: dur, status: status.value };
      if (editingId) { appts.update(id, item); } else { appts.add(item); }
      formCard.style.display = 'none';
      clearForm();
      editingId = null;
      render();
    };

    filterDate.onchange = render; filterDoctor.oninput = render;

    function clearForm(){ patient.value=''; doctor.value=''; date.value=''; time.value=''; reason.value=''; duration.value='30'; status.value='Scheduled'; }

    function formatDate(dt){ return dt.toLocaleString([], { year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit' }); }

    function render(){
      const all = appts.all().sort((a,b) => new Date(a.when) - new Date(b.when));
      const dFilter = filterDate.value ? new Date(filterDate.value) : null;
      const drFilter = (filterDoctor.value || '').trim().toLowerCase();
      const list = all.filter(a => { const dt = new Date(a.when); const dateOk = !dFilter || dt.toDateString() === dFilter.toDateString(); const docOk = !drFilter || a.doctor.toLowerCase().includes(drFilter); return dateOk && docOk; });
      if (!list.length) { apptList.innerHTML = '<p class="muted">No appointments found.</p>'; return; }
      apptList.innerHTML = list.map(a => {
        const dt = new Date(a.when);
        const badge = a.status ? `<span class="low" style="background:#e0e7ff;color:#3730a3;">${a.status}</span>` : '';
        return `
          <div class="record">
            <p><strong>${a.patient}</strong> with <strong>${a.doctor}</strong><br>
            <span class="muted">${formatDate(dt)} · ${a.duration||30} mins</span></p>
            <div class="actions">
              ${badge}
              <button class="btn-view" onclick="alert('Notes: ${a.reason ? a.reason.replace(/\"/g,'&quot;') : 'N/A'}')">Notes</button>
              <button class="btn-view" onclick="(function(id){ const a = (JSON.parse(localStorage.getItem('hms_appointments')||'[]')).find(x=>x.id===id); if(!a) return; (function openForEdit(a){ formCard.style.display='block'; editingId=a.id; patient.value=a.patient||''; doctor.value=a.doctor||''; const dt=new Date(a.when); date.value=dt.toISOString().slice(0,10); time.value=dt.toTimeString().slice(0,5); duration.value=String(a.duration||30); status.value=a.status||'Scheduled'; reason.value=a.reason||''; })(a); })('${a.id}')">Edit</button>
              <button class="btn-danger" onclick="(function(id){ const ok = confirm('Cancel this appointment?'); if(ok){ const a=(JSON.parse(localStorage.getItem('hms_appointments')||'[]')).filter(x=>x.id!==id); localStorage.setItem('hms_appointments', JSON.stringify(a)); render(); } })('${a.id}')">Cancel</button>
            </div>
          </div>`; }).join('');
    }
  </script>
</body>
</html>
>>>>>>> 06dc0c9b022abb6f0feca36ea29ea8f5038375ea
