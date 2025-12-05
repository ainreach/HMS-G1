<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Completed Tests</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Laboratory Staff</h1><small>Completed Tests</small></div>
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
  <a href="<?= site_url('lab/completed-tests') ?>" class="active" aria-current="page">Completed Tests</a>
  <a href="<?= site_url('lab/statistics') ?>">Statistics</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recently Completed Tests</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Order #</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Completed</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Result</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($tests)): ?>
              <?php foreach ($tests as $test): ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc($test['test_number'] ?? 'TEST-' . $test['id']) ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <div><?= esc(($test['first_name'] ?? '') . ' ' . ($test['last_name'] ?? '')) ?></div>
                    <small style="color:#6b7280"><?= esc($test['patient_code'] ?? 'N/A') ?></small>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <div><?= esc($test['test_name'] ?? 'N/A') ?></div>
                    <small style="color:#6b7280"><?= esc($test['test_type'] ?? '') ?></small>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php if (!empty($test['result_date'])): ?>
                      <?= esc(date('M d, Y H:i', strtotime($test['result_date']))) ?>
                    <?php else: ?>
                      <span style="color:#6b7280">N/A</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php 
                    $resultsData = $test['results'] ?? '';
                    if (!empty($resultsData)) {
                      $decoded = json_decode($resultsData, true);
                      if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        if (isset($decoded['text'])) {
                          $resultsData = $decoded['text'];
                        } else {
                          $resultsData = json_encode($decoded, JSON_UNESCAPED_UNICODE);
                        }
                      }
                      // Truncate if too long
                      $displayResults = strlen($resultsData) > 50 ? substr($resultsData, 0, 50) . '...' : $resultsData;
                    } else {
                      $displayResults = 'No results';
                    }
                    ?>
                    <span style="color:#374151"><?= esc($displayResults) ?></span>
                    <br>
                    <a href="<?= site_url('lab/tests/' . $test['id']) ?>" style="color:#3b82f6;text-decoration:none;font-size:0.75rem">
                      <i class="fas fa-eye"></i> View Details
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" style="padding:40px;text-align:center;color:#6b7280">
                  <div style="margin-bottom:16px">
                    <i class="fa-solid fa-check-circle" style="font-size:3rem;color:#d1d5db"></i>
                  </div>
                  <h3 style="margin:0 0 8px 0;font-size:1.125rem;color:#374151;font-weight:600">No Completed Tests</h3>
                  <p style="margin:0;color:#6b7280;font-size:0.875rem">
                    Completed test results will appear here after lab staff enter and save the results.
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
