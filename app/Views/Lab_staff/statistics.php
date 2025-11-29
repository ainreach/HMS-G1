<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lab Statistics</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Laboratory Staff</h1><small>Statistics</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Laboratory navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/lab') ?>">Overview</a>
  <a href="<?= site_url('lab/test-requests') ?>">Test Requests</a>
  <a href="<?= site_url('lab/sample-queue') ?>">Sample Queue</a>
  <a href="<?= site_url('lab/completed-tests') ?>">Completed Tests</a>
  <a href="<?= site_url('lab/statistics') ?>" class="active" aria-current="page">Statistics</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Total Tests</span><i class="fa-solid fa-vial" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite">0</div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Completed Today</span><i class="fa-solid fa-check-circle" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite">0</div></article>
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>Pending</span><i class="fa-solid fa-clock" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite">0</div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Urgent Tests</span><i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite">0</div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Test Statistics</h2></div>
      <div class="panel-body">
        <p style="color:#6b7280;text-align:center;padding:20px">No statistics available yet.</p>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
