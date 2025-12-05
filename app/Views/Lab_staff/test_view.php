<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Test - Lab Staff</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Lab Staff</h1><small>Laboratory Management</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Lab navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/lab') ?>">Overview</a>
  <a href="<?= site_url('lab/test-requests') ?>" data-feature="lab">Test Requests</a>
  <a href="<?= site_url('lab/results/new') ?>" data-feature="lab">New Result</a>
  <a href="<?= site_url('lab/completed-tests') ?>" data-feature="lab">Completed Tests</a>
  <a href="<?= site_url('lab/statistics') ?>" data-feature="lab">Statistics</a>
</nav></aside>
  <main class="content" style="padding:16px">
    <h1 style="font-size:1.5rem;margin-bottom:1rem">Lab Test Details</h1>
    
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Test Information</h2></div>
      <div class="panel-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div>
            <strong>Test ID:</strong> #<?= esc($test['id']) ?><br>
            <strong>Test Number:</strong> <?= esc($test['test_number'] ?? 'N/A') ?><br>
            <strong>Test Name:</strong> <?= esc($test['test_name'] ?? 'N/A') ?><br>
            <strong>Test Type:</strong> <?= esc($test['test_type'] ?? 'N/A') ?><br>
            <strong>Status:</strong> 
            <span style="padding:4px 8px;border-radius:4px;font-size:0.875rem;font-weight:600;background:#dbeafe;color:#1e40af">
              <?= esc(ucfirst($test['status'] ?? 'pending')) ?>
            </span>
          </div>
          <div>
            <strong>Patient:</strong> <?= esc($test['first_name'] . ' ' . $test['last_name']) ?><br>
            <strong>Patient ID:</strong> <?= esc($test['patient_code'] ?? 'N/A') ?><br>
            <strong>Doctor:</strong> <?= esc($test['doctor_name'] ?? 'N/A') ?><br>
            <strong>Requested Date:</strong> <?= esc(date('M d, Y H:i', strtotime($test['requested_date']))) ?><br>
            <strong>Priority:</strong> <?= esc(ucfirst($test['priority'] ?? 'routine')) ?>
          </div>
        </div>
        
        <?php if (!empty($test['notes'])): ?>
          <div style="margin-top:16px;padding:12px;background:#f3f4f6;border-radius:6px">
            <strong>Notes:</strong><br>
            <?= esc($test['notes']) ?>
          </div>
        <?php endif; ?>
        
        <?php if (!empty($test['results'])): ?>
          <div style="margin-top:16px;padding:12px;background:#d1fae5;border-radius:6px">
            <strong>Results:</strong><br>
            <?= esc($test['results']) ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
    
    <div style="margin-top:16px;display:flex;gap:10px">
      <a href="<?= site_url('lab/test-requests') ?>" class="btn" style="padding:10px 20px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">Back to Requests</a>
      <?php if ($test['status'] !== 'completed'): ?>
        <a href="<?= site_url('lab/results/new?test_id=' . $test['id']) ?>" class="btn" style="padding:10px 20px;background:#3b82f6;color:white;border-radius:6px;text-decoration:none">Enter Result</a>
      <?php else: ?>
        <a href="<?= site_url('lab/tests/' . $test['id'] . '/print') ?>" class="btn" style="padding:10px 20px;background:#10b981;color:white;border-radius:6px;text-decoration:none">Print Report</a>
      <?php endif; ?>
    </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

