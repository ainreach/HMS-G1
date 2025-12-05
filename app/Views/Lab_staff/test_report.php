<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lab Test Report - Lab Staff</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    @media print {
      .no-print { display: none; }
      body { margin: 0; padding: 20px; }
    }
  </style>
</head><body>
<header class="dash-topbar no-print" role="banner"><div class="topbar-inner">
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
<div class="layout"><aside class="simple-sidebar no-print" role="navigation" aria-label="Lab navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/lab') ?>">Overview</a>
  <a href="<?= site_url('lab/test-requests') ?>" data-feature="lab">Test Requests</a>
  <a href="<?= site_url('lab/results/new') ?>" data-feature="lab">New Result</a>
  <a href="<?= site_url('lab/completed-tests') ?>" data-feature="lab">Completed Tests</a>
  <a href="<?= site_url('lab/statistics') ?>" data-feature="lab">Statistics</a>
</nav></aside>
  <main class="content" style="padding:16px">
    <div class="no-print" style="margin-bottom:16px">
      <button onclick="window.print()" class="btn" style="padding:10px 20px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer">
        <i class="fa-solid fa-print"></i> Print Report
      </button>
      <a href="<?= site_url('lab/completed-tests') ?>" class="btn" style="padding:10px 20px;margin-left:10px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">Back</a>
    </div>
    
    <section class="panel" style="border:2px solid #000;padding:20px">
      <div style="text-align:center;margin-bottom:30px;border-bottom:2px solid #000;padding-bottom:20px">
        <h1 style="margin:0;font-size:1.5rem">LABORATORY TEST REPORT</h1>
        <p style="margin:8px 0 0 0;color:#6b7280">Hospital Management System</p>
      </div>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
        <div>
          <strong>Test Number:</strong> <?= esc($test['test_number'] ?? 'N/A') ?><br>
          <strong>Test Name:</strong> <?= esc($test['test_name'] ?? 'N/A') ?><br>
          <strong>Test Type:</strong> <?= esc($test['test_type'] ?? 'N/A') ?>
        </div>
        <div>
          <strong>Date:</strong> <?= esc(date('M d, Y', strtotime($test['result_date'] ?? $test['requested_date']))) ?><br>
          <strong>Time:</strong> <?= esc(date('H:i', strtotime($test['result_date'] ?? $test['requested_date']))) ?>
        </div>
      </div>
      
      <div style="margin-bottom:20px;padding:12px;background:#f3f4f6;border-radius:6px">
        <strong>Patient Information:</strong><br>
        Name: <?= esc($test['first_name'] . ' ' . $test['last_name']) ?><br>
        Patient ID: <?= esc($test['patient_code'] ?? 'N/A') ?><br>
        Date of Birth: <?= esc($test['date_of_birth'] ? date('M d, Y', strtotime($test['date_of_birth'])) : 'N/A') ?><br>
        Gender: <?= esc(ucfirst($test['gender'] ?? 'N/A')) ?>
      </div>
      
      <div style="margin-bottom:20px;padding:12px;background:#f3f4f6;border-radius:6px">
        <strong>Requesting Physician:</strong> <?= esc($test['doctor_name'] ?? 'N/A') ?>
      </div>
      
      <?php if (!empty($test['results'])): ?>
        <div style="margin-bottom:20px;padding:12px;border:1px solid #000;border-radius:6px">
          <strong style="font-size:1.1rem">Test Results:</strong><br>
          <div style="margin-top:10px;white-space:pre-wrap"><?= esc($test['results']) ?></div>
        </div>
      <?php else: ?>
        <div style="margin-bottom:20px;padding:12px;border:1px solid #000;border-radius:6px">
          <strong style="font-size:1.1rem">Test Results:</strong><br>
          <div style="margin-top:10px;color:#6b7280">Results pending</div>
        </div>
      <?php endif; ?>
      
      <?php if (!empty($test['notes'])): ?>
        <div style="margin-bottom:20px;padding:12px;background:#f3f4f6;border-radius:6px">
          <strong>Notes:</strong><br>
          <?= esc($test['notes']) ?>
        </div>
      <?php endif; ?>
      
      <div style="margin-top:30px;padding-top:20px;border-top:2px solid #000;text-align:right">
        <div>
          <strong>Lab Technician:</strong> <?= esc(session('username') ?? 'N/A') ?><br>
          <strong>Date:</strong> <?= esc(date('M d, Y')) ?>
        </div>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

