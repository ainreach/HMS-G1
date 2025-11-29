<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lab Test Requests</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Laboratory Staff</h1><small>Test Requests</small></div>
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
  <a href="<?= site_url('lab/test-requests') ?>" class="active" aria-current="page">Test Requests</a>
  <a href="<?= site_url('lab/sample-queue') ?>">Sample Queue</a>
  <a href="<?= site_url('lab/completed-tests') ?>">Completed Tests</a>
  <a href="<?= site_url('lab/statistics') ?>">Statistics</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Pending Test Requests</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Order #</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Doctor</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Priority</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($requests)) : foreach ($requests as $request) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($request['test_number'] ?: 'TEST-' . $request['id']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <div><?= esc($request['first_name'] . ' ' . $request['last_name']) ?></div>
                  <small style="color:#6b7280"><?= esc($request['patient_code']) ?></small>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($request['test_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">Dr. <?= esc($request['doctor_first'] . ' ' . $request['doctor_last']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <span style="padding:2px 6px;border-radius:4px;font-size:0.75rem;background-color:<?= $request['priority'] === 'urgent' ? '#fef2f2' : '#f0f9ff' ?>;color:<?= $request['priority'] === 'urgent' ? '#dc2626' : '#1e40af' ?>">
                    <?= esc(ucfirst($request['priority'] ?: 'Routine')) ?>
                  </span>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <div style="display:flex;gap:6px">
                    <a href="<?= site_url('lab/collect-sample/' . $request['id']) ?>" style="padding:4px 8px;background:#10b981;color:white;border-radius:4px;text-decoration:none;font-size:0.75rem">Collect Sample</a>
                    <a href="<?= site_url('lab/enter-result/' . $request['id']) ?>" style="padding:4px 8px;background:#3b82f6;color:white;border-radius:4px;text-decoration:none;font-size:0.75rem">Enter Result</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="6" style="padding:12px;text-align:center;color:#6b7280">No pending test requests.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
