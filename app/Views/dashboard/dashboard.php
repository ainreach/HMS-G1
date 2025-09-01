<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HMS Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <header class="dash-topbar" role="banner">
    <div class="topbar-inner">
      <a class="menu-btn" href="<?= site_url('/') ?>" aria-label="Home"><i class="fa-solid fa-house"></i></a>
      <div class="brand">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
        <div class="brand-text">
          <h1 style="font-size:1.25rem;margin:0">Hospital Management System</h1>
          <small>Dashboard</small>
        </div>
      </div>
      <div class="top-right" aria-label="User session">
        <span class="role"><i class="fa-regular fa-user"></i>
          <?= esc(session('username') ?? session('role') ?? 'User') ?>
        </span>
        <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
      </div>
    </div>
  </header>

  <div class="layout">
    <aside class="simple-sidebar" id="sidebar" role="navigation" aria-label="Main navigation">
      <nav class="side-nav">
        <a href="<?= site_url('dashboard') ?>" class="active" aria-current="page">Dashboard</a>
        <a href="<?= site_url('records') ?>">Registration & Records</a>
        <a href="<?= site_url('scheduling') ?>">Scheduling</a>
        <a href="<?= site_url('billing') ?>">Billing</a>
        <a href="<?= site_url('laboratory') ?>">Laboratory</a>
        <a href="<?= site_url('pharmacy') ?>">Pharmacy</a>
        <a href="<?= site_url('reports') ?>">Reports</a>
      </nav>
    </aside>

    <main class="content">
      <section class="kpi-grid" aria-label="Key indicators">
        <article class="kpi-card kpi-primary">
          <div class="kpi-head"><span>Total Patients</span><i class="fa-solid fa-users" aria-hidden="true"></i></div>
          <div class="kpi-value" aria-live="polite">—</div>
        </article>
        <article class="kpi-card kpi-info">
          <div class="kpi-head"><span>Appointments Today</span><i class="fa-solid fa-calendar-day" aria-hidden="true"></i></div>
          <div class="kpi-value" aria-live="polite">—</div>
        </article>
      </section>

      <section class="panel" style="margin-top:16px">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
        <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
          <a class="btn" href="<?= site_url('records') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Create Patient</a>
          <a class="btn" href="<?= site_url('scheduling') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">New Appointment</a>
          <a class="btn" href="<?= site_url('reports') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Generate Report</a>
        </div>
      </section>

      <section class="panel" style="margin-top:16px">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Activity</h2></div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">When</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">User</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="3" style="padding:12px;text-align:center;color:#6b7280">No recent activity.</td></tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <script>
    window.APP_BASE = '<?= rtrim(site_url('/'), '/') ?>/';
  </script>
  <script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>


