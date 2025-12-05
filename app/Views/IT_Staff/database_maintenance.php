<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Database Maintenance - IT Staff</title>
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
  <a href="<?= site_url('it/system-monitoring') ?>" data-feature="monitoring">System Monitoring</a>
  <a href="<?= site_url('it/security-scan') ?>" data-feature="security">Security Scan</a>
  <a href="<?= site_url('it/database-maintenance') ?>" class="active" aria-current="page" data-feature="database">Database Maintenance</a>
</nav></aside>
  <main class="content">
    <h1 style="font-size:1.5rem;margin-bottom:1rem">Database Maintenance</h1>
    
    <?php if (session()->getFlashdata('success')): ?>
      <div style="padding:12px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;margin-bottom:16px;color:#065f46">
        <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
      <div style="padding:12px;background:#fee2e2;border:1px solid #fca5a5;border-radius:6px;margin-bottom:16px;color:#991b1b">
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>
    
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Database Statistics</h2>
      </div>
      <div class="panel-body">
        <p style="color:#6b7280;margin:0 0 16px 0">
          <strong>Total Database Size:</strong> <?= esc(number_format(($dbSize ?? 0) / (1024 * 1024), 2)) ?> MB
        </p>
        
        <form action="<?= site_url('it/database-maintenance/optimize') ?>" method="post" style="margin-top:16px">
          <?= csrf_field() ?>
          <button type="submit" class="btn" style="background:#3b82f6;color:white;padding:10px 20px;border:none;border-radius:6px;cursor:pointer;font-weight:600" onclick="return confirm('This will optimize all database tables. This may take a few minutes. Continue?')">
            <i class="fa-solid fa-database"></i> Optimize Database
          </button>
        </form>
      </div>
    </section>
    
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Table Statistics</h2></div>
      <div class="panel-body">
        <?php if (!empty($tableStats)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Table Name</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Rows</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Size</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tableStats as $stat): ?>
                <tr>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($stat['name']) ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc(number_format($stat['rows'])) ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc(number_format($stat['size'] / 1024, 2)) ?> KB</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p style="color:#6b7280;margin:0">No table statistics available</p>
        <?php endif; ?>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

