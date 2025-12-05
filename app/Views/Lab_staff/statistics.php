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
      <article class="kpi-card kpi-primary">
        <div class="kpi-head"><span>Total Tests</span><i class="fa-solid fa-vial" aria-hidden="true"></i></div>
        <div class="kpi-value" aria-live="polite"><?= esc($stats['totalTests'] ?? 0) ?></div>
      </article>
      <article class="kpi-card kpi-success">
        <div class="kpi-head"><span>Completed Today</span><i class="fa-solid fa-check-circle" aria-hidden="true"></i></div>
        <div class="kpi-value" aria-live="polite"><?= esc($stats['completedToday'] ?? 0) ?></div>
      </article>
      <article class="kpi-card kpi-warning">
        <div class="kpi-head"><span>Pending</span><i class="fa-solid fa-clock" aria-hidden="true"></i></div>
        <div class="kpi-value" aria-live="polite"><?= esc($stats['pendingTests'] ?? 0) ?></div>
      </article>
      <article class="kpi-card kpi-info">
        <div class="kpi-head"><span>Urgent Tests</span><i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i></div>
        <div class="kpi-value" aria-live="polite"><?= esc($stats['urgentTests'] ?? 0) ?></div>
      </article>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px">
      <!-- Test Categories -->
      <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Tests by Category</h2></div>
        <div class="panel-body" style="overflow:auto">
          <?php if (!empty($categories)): ?>
            <table class="table" style="width:100%;border-collapse:collapse">
              <thead>
                <tr>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Category</th>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Total</th>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Completed</th>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Pending</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($categories as $cat): ?>
                  <tr>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucfirst($cat['test_category'] ?? 'Other')) ?></td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($cat['count'] ?? 0) ?></td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                      <span style="color:#10b981"><?= esc($cat['completed_count'] ?? 0) ?></span>
                    </td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                      <span style="color:#f59e0b"><?= esc($cat['pending_count'] ?? 0) ?></span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p style="color:#6b7280;text-align:center;padding:20px">No category data available.</p>
          <?php endif; ?>
        </div>
      </section>

      <!-- Status Breakdown -->
      <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Status Breakdown</h2></div>
        <div class="panel-body" style="overflow:auto">
          <?php if (!empty($statusBreakdown)): ?>
            <table class="table" style="width:100%;border-collapse:collapse">
              <thead>
                <tr>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
                  <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Count</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($statusBreakdown as $status): ?>
                  <tr>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                      <?= esc(ucfirst(str_replace('_', ' ', $status['status'] ?? 'unknown'))) ?>
                    </td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                      <strong><?= esc($status['count'] ?? 0) ?></strong>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php else: ?>
            <p style="color:#6b7280;text-align:center;padding:20px">No status data available.</p>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
