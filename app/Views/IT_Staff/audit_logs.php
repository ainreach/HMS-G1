<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IT Audit Logs</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">IT Staff</h1><small>Audit Logs</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="IT navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/it') ?>">Overview</a>
  <a href="<?= site_url('it/reports') ?>">System Reports</a>
  <a href="<?= site_url('it/audit-logs') ?>" class="active" aria-current="page">Audit Logs</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0">Audit Trail</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead><tr>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Timestamp</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">User</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Action</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">IP</th>
          </tr></thead>
          <tbody>
          <?php if (!empty($logs)): foreach($logs as $row): ?>
            <tr>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['created_at'] ?? '') ?></td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['username'] ?? '') ?></td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['action'] ?? '') ?></td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['ip_address'] ?? '') ?></td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="4" style="padding:12px;text-align:center;color:#6b7280">No logs yet.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
</body></html>
