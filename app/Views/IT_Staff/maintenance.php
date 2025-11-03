<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IT Maintenance</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/it') ?>" class="menu-btn" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Maintenance</h1><small>Safe actions for IT staff</small></div>
  </div>
  <div class="top-right"><a href="<?= site_url('logout') ?>" class="logout-btn">Logout</a></div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/it') ?>">Overview</a>
  <a href="<?= site_url('it/maintenance') ?>" class="active" aria-current="page">Maintenance</a>
  <a href="<?= site_url('it/security') ?>">Security</a>
  <a href="<?= site_url('it/backups') ?>">Backups</a>
</nav></aside>
  <main class="content">
    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success" role="status"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head"><h2 style="margin:0">Quick Maintenance</h2></div>
      <div class="panel-body" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));gap:12px;">
        <a class="btn" href="<?= site_url('it/maintenance/clear-cache') ?>" style="display:flex;align-items:center;gap:8px;border:1px solid #e5e7eb;border-radius:8px;padding:10px 12px;text-decoration:none;">
          <i class="fa-solid fa-broom"></i> Clear Application Cache
        </a>
        <a class="btn" href="<?= current_url() ?>" onclick="location.reload();return false;" style="display:flex;align-items:center;gap:8px;border:1px solid #e5e7eb;border-radius:8px;padding:10px 12px;text-decoration:none;">
          <i class="fa-solid fa-rotate"></i> Refresh Status
        </a>
      </div>
    </section>

    <section class="panel">
      <div class="panel-head"><h2 style="margin:0">Runtime Info</h2></div>
      <div class="panel-body">
        <ul style="margin:0;padding-left:18px;color:#374151;">
          <li>Base URL: <?= esc(base_url()) ?></li>
          <li>Environment: <?= esc(ENVIRONMENT) ?></li>
          <li>PHP: <?= esc(PHP_VERSION) ?></li>
          <li>Time: <?= esc(date('Y-m-d H:i:s')) ?></li>
        </ul>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
