<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IT Backups</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">IT Staff</h1><small>Backups</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="IT navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/it') ?>">Overview</a>
  <a href="<?= site_url('it/reports') ?>">System Reports</a>
  <a href="<?= site_url('it/audit-logs') ?>">Audit Logs</a>
  <a href="<?= site_url('it/backups') ?>" class="active" aria-current="page">Backups</a>
</nav></aside>
  <main class="content">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <h2 style="margin:0">Backup Jobs</h2>
      <div style="display:flex;gap:8px">
        <a class="btn" href="<?= site_url('it/backups/export/users') ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Download Users CSV</a>
        <a class="btn" href="<?= site_url('it/backups/export/zip') ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Download ZIP Backup</a>
      </div>
    </div>

    <section class="panel">
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead><tr>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Timestamp</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Size</th>
          </tr></thead>
          <tbody>
            <tr><td colspan="3" style="padding:12px;text-align:center;color:#6b7280">No backups yet.</td></tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
</body></html>
