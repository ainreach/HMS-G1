<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IT Security</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/it') ?>" class="menu-btn" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Security</h1><small>Environment & best practices</small></div>
  </div>
  <div class="top-right"><a href="<?= site_url('logout') ?>" class="logout-btn">Logout</a></div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/it') ?>">Overview</a>
  <a href="<?= site_url('it/maintenance') ?>">Maintenance</a>
  <a href="<?= site_url('it/security') ?>" class="active" aria-current="page">Security</a>
  <a href="<?= site_url('it/backups') ?>">Backups</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0">Runtime Security</h2></div>
      <div class="panel-body">
        <ul style="margin:0;padding-left:18px;color:#374151;">
          <li>Environment: <strong><?= esc($appEnv ?? 'unknown') ?></strong></li>
          <li>Debug mode: <strong><?= isset($debugEnabled) && $debugEnabled ? 'ON' : 'OFF' ?></strong></li>
          <li>PHP: <strong><?= esc($phpVersion ?? phpversion()) ?></strong></li>
        </ul>
      </div>
    </section>

    <section class="panel">
      <div class="panel-head"><h2 style="margin:0">Recommendations</h2></div>
      <div class="panel-body">
        <ul style="margin:0;padding-left:18px;color:#374151;">
          <li>Ensure <code>ENVIRONMENT</code> is <strong>production</strong> on live servers.</li>
          <li>Disable display of detailed errors in production.</li>
          <li>Rotate admin and database passwords regularly.</li>
          <li>Enable HTTPS and set secure session cookies.</li>
          <li>Regularly update dependencies via Composer.</li>
        </ul>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
