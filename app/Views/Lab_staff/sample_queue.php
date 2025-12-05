<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sample Queue</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Laboratory Staff</h1><small>Sample Queue</small></div>
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
  <a href="<?= site_url('lab/sample-queue') ?>" class="active" aria-current="page">Sample Queue</a>
  <a href="<?= site_url('lab/completed-tests') ?>">Completed Tests</a>
  <a href="<?= site_url('lab/statistics') ?>">Statistics</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Sample Collection Queue</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Order #</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Collected</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($samples)): ?>
              <?php foreach ($samples as $sample): ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc($sample['test_number'] ?? 'TEST-' . $sample['id']) ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <div><?= esc(($sample['first_name'] ?? '') . ' ' . ($sample['last_name'] ?? '')) ?></div>
                    <small style="color:#6b7280"><?= esc($sample['patient_code'] ?? 'N/A') ?></small>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($sample['test_name'] ?? 'N/A') ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php if (!empty($sample['sample_collected_date'])): ?>
                      <?= esc(date('M d, Y H:i', strtotime($sample['sample_collected_date']))) ?>
                    <?php else: ?>
                      <span style="color:#6b7280">Not collected</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <div style="display:flex;gap:6px">
                      <a href="<?= site_url('lab/results/new?test_id=' . $sample['id']) ?>" class="btn" style="padding:6px 12px;background:#3b82f6;color:white;border-radius:6px;text-decoration:none;font-size:0.875rem">
                        <i class="fas fa-flask"></i> Enter Result
                      </a>
                      <a href="<?= site_url('lab/tests/' . $sample['id']) ?>" class="btn" style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none;font-size:0.875rem">
                        <i class="fas fa-eye"></i> View
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" style="padding:40px;text-align:center;color:#6b7280">
                  <div style="margin-bottom:16px">
                    <i class="fa-solid fa-flask" style="font-size:3rem;color:#d1d5db"></i>
                  </div>
                  <h3 style="margin:0 0 8px 0;font-size:1.125rem;color:#374151;font-weight:600">No Samples in Queue</h3>
                  <p style="margin:0;color:#6b7280;font-size:0.875rem">
                    All collected samples have been processed. New samples will appear here after nurses mark them as collected.
                  </p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
