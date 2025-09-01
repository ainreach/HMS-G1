<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IT Staff Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">IT Staff</h1><small>System maintenance • Security • Backups</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="IT navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/it') ?>" class="active" aria-current="page">Overview</a>
  <a href="<?= site_url('it/reports') ?>" data-feature="reports">System Reports</a>
  <a href="<?= site_url('it/audit-logs') ?>" data-feature="audit">Audit Logs</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>Security Alerts</span><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite">—</div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Backups</span><i class="fa-solid fa-database" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite">—</div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('it/backups') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;">Run Backup</a>
        <a class="btn" href="<?= site_url('it/reports') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;">Check Updates</a>
        <a class="btn" href="<?= site_url('it/audit-logs') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;">View Audit Log</a>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">System Status</h2></div>
      <div class="panel-body">
        <ul style="margin:0;padding-left:18px">
          <li>Web Server: Online</li>
          <li>Database: Online</li>
          <li>Queue Worker: Idle</li>
          <li>Latest Backup: Today 02:00</li>
        </ul>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
