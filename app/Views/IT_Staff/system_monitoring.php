<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>System Monitoring - IT Staff</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
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
  <a href="<?= site_url('dashboard/it') ?>">Overview</a>
  <a href="<?= site_url('it/reports') ?>" data-feature="reports">System Reports</a>
  <a href="<?= site_url('it/audit-logs') ?>" data-feature="audit">Audit Logs</a>
  <a href="<?= site_url('it/branch-integration') ?>" data-feature="branch">Branch Integration</a>
  <a href="<?= site_url('it/system-monitoring') ?>" class="active" aria-current="page" data-feature="monitoring">System Monitoring</a>
  <a href="<?= site_url('it/security-scan') ?>" data-feature="security">Security Scan</a>
  <a href="<?= site_url('it/database-maintenance') ?>" data-feature="database">Database Maintenance</a>
</nav></aside>
  <main class="content">
    <h1 style="font-size:1.5rem;margin-bottom:1rem">System Monitoring</h1>
    
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem;">
      <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">CPU Usage</h2></div>
        <div class="panel-body">
          <div style="font-size:2rem;font-weight:600;color:#3b82f6"><?= esc($cpu_usage ?? 0) ?>%</div>
          <div style="width:100%;height:20px;background:#e5e7eb;border-radius:10px;margin-top:8px;overflow:hidden">
            <div style="width:<?= esc($cpu_usage ?? 0) ?>%;height:100%;background:#3b82f6;transition:width 0.3s"></div>
          </div>
        </div>
      </section>
      
      <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Memory Usage</h2></div>
        <div class="panel-body">
          <div style="font-size:2rem;font-weight:600;color:#10b981"><?= esc($memory_usage ?? 0) ?>%</div>
          <div style="width:100%;height:20px;background:#e5e7eb;border-radius:10px;margin-top:8px;overflow:hidden">
            <div style="width:<?= esc($memory_usage ?? 0) ?>%;height:100%;background:#10b981;transition:width 0.3s"></div>
          </div>
        </div>
      </section>
      
      <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Disk Usage</h2></div>
        <div class="panel-body">
          <div style="font-size:2rem;font-weight:600;color:#f59e0b"><?= esc($disk_usage ?? 0) ?>%</div>
          <div style="width:100%;height:20px;background:#e5e7eb;border-radius:10px;margin-top:8px;overflow:hidden">
            <div style="width:<?= esc($disk_usage ?? 0) ?>%;height:100%;background:#f59e0b;transition:width 0.3s"></div>
          </div>
        </div>
      </section>
      
      <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Network Status</h2></div>
        <div class="panel-body">
          <div style="font-size:2rem;font-weight:600;color:<?= ($network_status ?? 'offline') === 'online' ? '#10b981' : '#ef4444' ?>">
            <?= esc(ucfirst($network_status ?? 'offline')) ?>
          </div>
          <div style="margin-top:8px;display:flex;align-items:center;gap:8px">
            <i class="fa-solid fa-circle" style="color:<?= ($network_status ?? 'offline') === 'online' ? '#10b981' : '#ef4444' ?>;font-size:0.75rem"></i>
            <span style="color:#6b7280"><?= ($network_status ?? 'offline') === 'online' ? 'Connected' : 'Disconnected' ?></span>
          </div>
        </div>
      </section>
    </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

