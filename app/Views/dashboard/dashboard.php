<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patient Dashboard - FIFTY 50 MEDTECHIE</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <a class="btn-logout" href="#" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
      </div>
    </div>
  </header>
  <div class="layout">
    <aside class="simple-sidebar" id="sidebar">
      <nav class="side-nav">
        <a href="javascript:void(0)" onclick="goto('dashboard')" class="active" data-feature="dashboard">Dashboard</a>
        <a href="javascript:void(0)" onclick="goto('records')" data-feature="ehr">Registration & Records</a>
        <a href="javascript:void(0)" onclick="goto('scheduling')" data-feature="scheduling">Scheduling</a>
        <a href="javascript:void(0)" onclick="goto('billing')" data-feature="billing">Billing & Payment</a>
        <a href="javascript:void(0)" onclick="goto('laboratory')" data-feature="laboratory">Laboratory & Diagnostic</a>
        <a href="javascript:void(0)" onclick="goto('pharmacy')" data-feature="pharmacy">Pharmacy</a>
        <a href="javascript:void(0)" onclick="goto('reports')" data-feature="reports">Reports & Analytics</a>
      </nav>
    </aside>

    <main class="content">
    <section class="actions">
      <button class="action-btn" onclick="location.href='<?= site_url('scheduling') ?>'"><i class="fa-solid fa-calendar-plus"></i> Book Appointment</button>
      <button class="action-btn"><i class="fa-solid fa-pills"></i> View Prescriptions</button>
      <button class="action-btn"><i class="fa-solid fa-file-waveform"></i> Request Lab</button>
      <button class="action-btn" onclick="location.href='<?= site_url('billing') ?>'"><i class="fa-solid fa-receipt"></i> Pay Bill</button>
    </section>

    <section class="kpi-grid">
      <article class="kpi-card kpi-primary">
        <div class="kpi-head"><span>Upcoming Appointments</span><i class="fa-solid fa-calendar-check"></i></div>
        <div class="kpi-value">2</div>
        <div class="kpi-sub up"><i class="fa-solid fa-arrow-trend-up"></i> Next: Aug 20, 10:00 AM</div>
      </article>
      <article class="kpi-card kpi-success">
        <div class="kpi-head"><span>Active Prescriptions</span><i class="fa-solid fa-pills"></i></div>
        <div class="kpi-value">3</div>
        <div class="kpi-sub up"><i class="fa-solid fa-arrow-trend-up"></i> 1 refill due soon</div>
      </article>
      <article class="kpi-card kpi-warning">
        <div class="kpi-head"><span>Pending Lab Results</span><i class="fa-solid fa-flask-vial"></i></div>
        <div class="kpi-value">1</div>
        <div class="kpi-sub down"><i class="fa-solid fa-hourglass-half"></i> Processing</div>
      </article>
      <article class="kpi-card kpi-info">
        <div class="kpi-head"><span>Outstanding Balance</span><i class="fa-solid fa-receipt"></i></div>
        <div class="kpi-value">₱1,250</div>
        <div class="kpi-sub up"><i class="fa-solid fa-arrow-trend-up"></i> Due: Aug 25</div>
      </article>
    </section>

    <section class="panels">
      <article class="panel panel-lg">
        <div class="panel-head">
          <h3>My Health Trend</h3>
          <div class="chips">
            <button class="chip active">7d</button>
            <button class="chip">30d</button>
            <button class="chip">12m</button>
          </div>
        </div>
        <div class="panel-body"><canvas id="lineChart"></canvas></div>
      </article>
      <article class="panel">
        <div class="panel-head"><h3>My Appointments</h3></div>
        <div class="panel-body"><canvas id="pieChart"></canvas></div>
      </article>
    </section>

    <section class="panel table-panel">
      <div class="panel-head"><h3>Recent Activity</h3></div>
      <div class="panel-body">
        <ul class="activity">
          <li><span class="badge green"></span> 10:05 AM — Appointment confirmed with Dr. Santos</li>
          <li><span class="badge blue"></span> 09:40 AM — New prescription added: Amoxicillin</li>
          <li><span class="badge orange"></span> 09:10 AM — Lab request submitted: CBC</li>
        </ul>
      </div>
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
  </script>
</body>
</html>
