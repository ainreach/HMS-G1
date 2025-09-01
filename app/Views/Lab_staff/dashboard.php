<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laboratory Staff Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Laboratory Staff</h1><small>Manage test requests • Enter results</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Laboratory navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/lab') ?>" class="active" aria-current="page">Overview</a>
  <a href="<?= site_url('lab/results/new') ?>" data-feature="laboratory">Test Requests</a>
  <a href="<?= site_url('records') ?>" data-feature="ehr">Patient Records</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>Pending Tests</span><i class="fa-solid fa-vial" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= isset($pendingCount) ? (int)$pendingCount : 0 ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Results Submitted</span><i class="fa-solid fa-file-waveform" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= isset($completedCount) ? (int)$completedCount : 0 ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('lab/results/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Receive Sample</a>
        <a class="btn" href="<?= site_url('lab/results/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Enter Result</a>
        <a class="btn" href="<?= site_url('lab/results/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Print Report</a>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Pending Tests</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Order #</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php $list = isset($pendingList) && is_array($pendingList) ? $pendingList : []; ?>
            <?php if (count($list) === 0): ?>
              <tr>
                <td colspan="4" style="padding:10px;color:#6b7280">No pending tests.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($list as $row): ?>
                <?php
                  $order = $row['test_number'] ?? ('TEST-'.(int)($row['id'] ?? 0));
                  $patient = trim(($row['patient_name'] ?? 'Patient #'.(int)($row['patient_id'] ?? 0)));
                  $test = $row['test_name'] ?? ($row['type'] ?? 'Lab Test');
                  $status = $row['status'] ?? 'pending';
                ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($order) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($patient) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucfirst($status)) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
