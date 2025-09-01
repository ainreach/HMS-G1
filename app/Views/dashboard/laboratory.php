<?php /* Converted from laboratory.html */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laboratory & Diagnostic - FIFTY 50 MEDTECHIE</title>
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
        <a href="javascript:void(0)" onclick="goto('scheduling')" data-feature="scheduling">Scheduling</a>
        <a href="javascript:void(0)" onclick="goto('billing')" data-feature="billing">Billing & Payment</a>
        <a href="javascript:void(0)" onclick="goto('laboratory')" class="active" data-feature="laboratory">Laboratory & Diagnostic</a>
        <a href="javascript:void(0)" onclick="goto('pharmacy')" data-feature="pharmacy">Pharmacy</a>
        <a href="javascript:void(0)" onclick="goto('reports')" data-feature="reports">Reports & Analytics</a>
      </nav>
    </aside>

    <main class="content">
    <h1 class="page-title">Laboratory & Diagnostic</h1>

    <section class="form-container">
      <h2>Laboratory & Diagnostic</h2>
      <h3>Past Medical History</h3>

      <div class="record">
        <p><strong>John Smith</strong> - ID: PT001 <br>
        Age: 45 | Room: 302A</p>
        <p><strong>Request:</strong> 2 hours ago</p>
        <p><strong>Test:</strong> Blood Work <br> <strong>Doctor:</strong> Dr. Olarte</p>
        <button class="btn-view">View Details</button>
        <button class="btn-send">Send to doctor</button>
      </div>

      <div class="record">
        <p><strong>Jade Cuaton</strong> - ID: PT221 <br>
        Age: 32 | Room: 267B</p>
        <p><strong>Request:</strong> 3 hours ago</p>
        <p><strong>Test:</strong> Male Circumcision <br> <strong>Doctor:</strong> Dr. Brian</p>
        <button class="btn-view">View Details</button>
        <button class="btn-send">Send to doctor</button>
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
    if (btn && sidebar) { btn.addEventListener('click', () => sidebar.classList.toggle('collapsed')); }
  </script>
</body>
</html>
