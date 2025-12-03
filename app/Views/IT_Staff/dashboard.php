<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IT Staff Dashboard</title>
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
  <a href="<?= site_url('dashboard/it') ?>" class="active" aria-current="page">Overview</a>
  <a href="<?= site_url('it/reports') ?>" data-feature="reports">System Reports</a>
  <a href="<?= site_url('it/audit-logs') ?>" data-feature="audit">Audit Logs</a>
  <a href="<?= site_url('it/branch-integration') ?>" data-feature="branch">Branch Integration</a>
  <a href="<?= site_url('it/system-monitoring') ?>" data-feature="monitoring">System Monitoring</a>
  <a href="<?= site_url('it/security-scan') ?>" data-feature="security">Security Scan</a>
  <a href="<?= site_url('it/database-maintenance') ?>" data-feature="database">Database Maintenance</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Total Users</span><i class="fa-solid fa-users" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($userMetrics['totalUsers'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Active Users</span><i class="fa-solid fa-user-check" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($userMetrics['activeUsers'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>System Health</span><i class="fa-solid fa-heart-pulse" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($systemHealth['database'] ?? 'Unknown') ?></div></article>
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>Storage Free</span><i class="fa-solid fa-hdd" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc(number_format($systemHealth['storageFree'] ?? 0, 1)) ?> GB</div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('it/health') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;">System Health</a>
        <a class="btn" href="<?= site_url('it/backups') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;">Run Backup</a>
        <a class="btn" href="<?= site_url('it/security-scan') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;">Security Scan</a>
        <a class="btn" href="<?= site_url('it/branch-integration') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;">Branch Status</a>
        <a class="btn" href="<?= site_url('it/database-maintenance') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;">DB Maintenance</a>
        <a class="btn" href="<?= site_url('it/audit-logs') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;">Audit Logs</a>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">System Status</h2></div>
      <div class="panel-body">
        <ul style="margin:0;padding-left:18px">
          <li>Web Server: Online</li>
          <li>Database: <?= esc($systemHealth['database'] ?? 'Unknown') ?></li>
          <li>PHP Version: <?= esc($systemHealth['phpVersion'] ?? 'Unknown') ?></li>
          <li>Storage Free: <?= esc(number_format($systemHealth['storageFree'] ?? 0, 1)) ?> GB</li>
          <li>Server Time: <?= esc($systemHealth['serverTime'] ?? 'Unknown') ?></li>
        </ul>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem;">
      <!-- Security Alerts -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Security Alerts</h2>
        </div>
        <div class="panel-body">
          <?php if (!empty($security['alerts'])) : ?>
            <?php foreach ($security['alerts'] as $alert) : ?>
              <div style="padding:8px;margin-bottom:8px;border-radius:4px;background-color:<?= $alert['severity'] === 'high' ? '#fef2f2' : ($alert['severity'] === 'medium' ? '#fef3c7' : '#f0f9ff') ?>;color:<?= $alert['severity'] === 'high' ? '#dc2626' : ($alert['severity'] === 'medium' ? '#d97706' : '#1e40af') ?>;border-left:4px solid <?= $alert['severity'] === 'high' ? '#dc2626' : ($alert['severity'] === 'medium' ? '#d97706' : '#1e40af') ?>">
                <strong><?= esc(ucfirst($alert['type'])) ?>:</strong> <?= esc($alert['message']) ?>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color:#6b7280;margin:0">No security alerts</p>
          <?php endif; ?>
        </div>
      </section>

      <!-- Branch Integration Status -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Branch Integration</h2>
        </div>
        <div class="panel-body">
          <?php if (!empty($branchStatus)) : ?>
            <?php foreach ($branchStatus as $branch) : ?>
              <div style="padding:8px;margin-bottom:8px;border-radius:4px;background-color:<?= $branch['status'] === 'online' ? '#f0f9ff' : '#fef2f2' ?>;color:<?= $branch['status'] === 'online' ? '#1e40af' : '#dc2626' ?>">
                <div style="display:flex;justify-content:space-between;align-items:center">
                  <span><strong><?= esc($branch['name']) ?></strong></span>
                  <span style="padding:2px 6px;border-radius:4px;font-size:0.75rem;background-color:<?= $branch['status'] === 'online' ? '#d1fae5' : '#fecaca' ?>;color:<?= $branch['status'] === 'online' ? '#065f46' : '#dc2626' ?>">
                    <?= esc(ucfirst($branch['status'])) ?>
                  </span>
                </div>
                <div style="font-size:0.875rem;color:#6b7280;margin-top:4px">
                  Last sync: <?= esc($branch['last_sync']) ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color:#6b7280;margin:0">No branch information available</p>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
