<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>System Health</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/it') ?>" class="menu-btn" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">System Health</h1><small>Status checks</small></div>
  </div>
  <div class="top-right"><a href="<?= site_url('logout') ?>" class="logout-btn">Logout</a></div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/it') ?>">Overview</a>
  <a href="<?= site_url('it/security') ?>">Security</a>
  <a href="<?= site_url('it/health') ?>" class="active" aria-current="page">Health</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0">Database</h2></div>
      <div class="panel-body">
        <?php if (!empty($dbOk)): ?>
          <div class="alert alert-success">Database connection OK</div>
        <?php else: ?>
          <div class="alert alert-error">Database connection FAILED<?= $dbError? ': '.esc($dbError) : '' ?></div>
        <?php endif; ?>
      </div>
    </section>

    <section class="panel">
      <div class="panel-head"><h2 style="margin:0">Storage</h2></div>
      <div class="panel-body">
        <ul style="margin:0;padding-left:18px;color:#374151;">
          <li>Free space: <?= number_format((float)($storageGbFree ?? 0), 2) ?> GB</li>
          <li>Checked: <?= esc($checkedAt ?? '') ?></li>
        </ul>
      </div>
    </section>
  </main></div>
</body></html>
