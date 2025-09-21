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
  <a href="<?= site_url('lab/test-requests') ?>" data-feature="laboratory">Test Requests</a>
  <a href="<?= site_url('lab/sample-queue') ?>" data-feature="laboratory">Sample Queue</a>
  <a href="<?= site_url('lab/completed-tests') ?>" data-feature="laboratory">Completed Tests</a>
  <a href="<?= site_url('lab/statistics') ?>" data-feature="laboratory">Statistics</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>Pending Tests</span><i class="fa-solid fa-vial" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($pendingCount ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Results Submitted</span><i class="fa-solid fa-file-waveform" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($completedCount ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Samples Collected</span><i class="fa-solid fa-flask" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($sampleCollectedCount ?? 0) ?></div></article>
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Urgent Tests</span><i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($urgentTests ?? 0) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('lab/test-requests') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Test Requests</a>
        <a class="btn" href="<?= site_url('lab/sample-queue') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Sample Queue</a>
        <a class="btn" href="<?= site_url('lab/results/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Enter Result</a>
        <a class="btn" href="<?= site_url('lab/completed-tests') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Completed Tests</a>
        <a class="btn" href="<?= site_url('lab/statistics') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Statistics</a>
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
            <?php if (!empty($pendingList)) : foreach ($pendingList as $test) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['test_number'] ?: 'TEST-' . $test['id']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['first_name'] . ' ' . $test['last_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['test_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <span style="padding:2px 6px;border-radius:4px;font-size:0.75rem;background-color:<?= $test['priority'] === 'urgent' ? '#fef2f2' : '#f0f9ff' ?>;color:<?= $test['priority'] === 'urgent' ? '#dc2626' : '#1e40af' ?>">
                    <?= esc(ucfirst($test['priority'] ?: 'Routine')) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="4" style="padding:10px;color:#6b7280;text-align:center">No pending tests.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem;">
      <!-- Sample Collection Queue -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Sample Collection Queue</h2>
        </div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Collected</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($sampleQueue)) : foreach ($sampleQueue as $sample) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($sample['first_name'] . ' ' . $sample['last_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($sample['test_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('M j, H:i', strtotime($sample['sample_collected_date']))) ?></td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No samples in queue.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Recently Completed Tests -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Recently Completed Tests</h2>
        </div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Completed</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recentCompleted)) : foreach ($recentCompleted as $test) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['first_name'] . ' ' . $test['last_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['test_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('M j, H:i', strtotime($test['result_date']))) ?></td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No completed tests.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
