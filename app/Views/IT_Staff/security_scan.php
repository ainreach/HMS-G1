<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Security Scan - IT Staff</title>
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
  <a href="<?= site_url('it/security-scan') ?>" class="active" aria-current="page" data-feature="security">Security Scan</a>
  <a href="<?= site_url('it/database-maintenance') ?>" data-feature="database">Database Maintenance</a>
</nav></aside>
  <main class="content">
    <h1 style="font-size:1.5rem;margin-bottom:1rem">Security Scan Results</h1>
    
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Scan Information</h2>
      </div>
      <div class="panel-body">
        <p style="color:#6b7280;margin:0">Last scan: <?= esc($last_scan ?? date('Y-m-d H:i:s')) ?></p>
      </div>
    </section>
    
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Vulnerabilities</h2></div>
      <div class="panel-body">
        <?php if (!empty($vulnerabilities)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Type</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Severity</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Description</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Count</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($vulnerabilities as $vuln): ?>
                <tr>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc(ucfirst(str_replace('_', ' ', $vuln['type']))) ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                    <span style="padding:4px 8px;border-radius:4px;font-size:0.875rem;font-weight:600;background:<?= $vuln['severity'] === 'high' ? '#fecaca' : ($vuln['severity'] === 'medium' ? '#fef3c7' : '#d1fae5') ?>;color:<?= $vuln['severity'] === 'high' ? '#dc2626' : ($vuln['severity'] === 'medium' ? '#d97706' : '#065f46') ?>">
                      <?= esc(ucfirst($vuln['severity'])) ?>
                    </span>
                  </td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($vuln['description']) ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($vuln['count']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p style="color:#6b7280;margin:0">No vulnerabilities detected</p>
        <?php endif; ?>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

