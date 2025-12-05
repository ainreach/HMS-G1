<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lab Test Result - Doctor</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Doctor</h1><small>Patient Care • Lab Results</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Doctor navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/doctor') ?>"><i class="fa-solid fa-house-medical" style="margin-right:8px"></i>Overview</a>
  <a href="<?= site_url('doctor/patients') ?>"><i class="fa-solid fa-users" style="margin-right:8px"></i>Patients</a>
  <a href="<?= site_url('doctor/records') ?>"><i class="fa-solid fa-file-medical" style="margin-right:8px"></i>Patient Records</a>
  <a href="<?= site_url('doctor/lab-results') ?>" class="active" aria-current="page"><i class="fa-solid fa-flask" style="margin-right:8px"></i>Lab Results</a>
  <a href="<?= site_url('doctor/prescriptions') ?>"><i class="fa-solid fa-pills" style="margin-right:8px"></i>Prescriptions</a>
</nav></aside>
  <main class="content" style="padding:16px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
      <h1 style="font-size:1.5rem;margin:0">Lab Test Result</h1>
      <a href="<?= site_url('doctor/lab-results') ?>" class="btn" style="padding:8px 16px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">
        <i class="fas fa-arrow-left"></i> Back to Results
      </a>
    </div>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div style="padding:12px;background:#fee2e2;border:1px solid #fecaca;border-radius:6px;margin-bottom:16px;color:#991b1b">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Test Information</h2></div>
      <div class="panel-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
          <div>
            <div style="margin-bottom:12px">
              <strong style="color:#6b7280;font-size:0.875rem">Test Number</strong><br>
              <span style="font-size:1rem;font-weight:600"><?= esc($result['test_number'] ?? 'N/A') ?></span>
            </div>
            <div style="margin-bottom:12px">
              <strong style="color:#6b7280;font-size:0.875rem">Test Name</strong><br>
              <span style="font-size:1rem"><?= esc($result['test_name'] ?? 'N/A') ?></span>
            </div>
            <div style="margin-bottom:12px">
              <strong style="color:#6b7280;font-size:0.875rem">Test Type</strong><br>
              <span style="font-size:1rem"><?= esc($result['test_type'] ?? 'N/A') ?></span>
            </div>
            <div style="margin-bottom:12px">
              <strong style="color:#6b7280;font-size:0.875rem">Test Category</strong><br>
              <span style="font-size:1rem"><?= esc(ucfirst($result['test_category'] ?? 'N/A')) ?></span>
            </div>
          </div>
          <div>
            <div style="margin-bottom:12px">
              <strong style="color:#6b7280;font-size:0.875rem">Patient Name</strong><br>
              <span style="font-size:1rem;font-weight:600"><?= esc(($result['first_name'] ?? '') . ' ' . ($result['last_name'] ?? '')) ?></span>
            </div>
            <div style="margin-bottom:12px">
              <strong style="color:#6b7280;font-size:0.875rem">Patient ID</strong><br>
              <span style="font-size:1rem"><?= esc($result['patient_code'] ?? 'N/A') ?></span>
            </div>
            <div style="margin-bottom:12px">
              <strong style="color:#6b7280;font-size:0.875rem">Requested Date</strong><br>
              <span style="font-size:1rem"><?= esc(!empty($result['requested_date']) ? date('M d, Y H:i', strtotime($result['requested_date'])) : 'N/A') ?></span>
            </div>
            <div style="margin-bottom:12px">
              <strong style="color:#6b7280;font-size:0.875rem">Status</strong><br>
              <?php 
              $status = $result['status'] ?? 'unknown';
              $statusColors = [
                'completed' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                'sample_collected' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                'in_progress' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                'requested' => ['bg' => '#f3f4f6', 'text' => '#374151'],
              ];
              $colors = $statusColors[$status] ?? $statusColors['requested'];
              ?>
              <span style="padding:4px 8px;border-radius:4px;font-size:0.875rem;font-weight:600;background:<?= $colors['bg'] ?>;color:<?= $colors['text'] ?>">
                <?= esc(ucfirst(str_replace('_', ' ', $status))) ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <?php if (!empty($result['notes'])): ?>
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Notes</h2></div>
      <div class="panel-body">
        <p style="margin:0;color:#374151"><?= esc($result['notes']) ?></p>
      </div>
    </section>
    <?php endif; ?>
    
    <?php if (!empty($result['results'])): ?>
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Test Results</h2></div>
      <div class="panel-body">
        <?php 
        // Try to decode JSON results
        $resultsData = $result['results'];
        $decoded = json_decode($resultsData, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
          // If it's JSON with 'text' key, display the text
          if (isset($decoded['text'])) {
            $resultsData = $decoded['text'];
          } else {
            // If it's structured JSON, format it nicely
            $resultsData = '<pre style="white-space:pre-wrap;font-family:inherit">' . esc(json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>';
          }
        }
        ?>
        <div style="padding:16px;background:#f8fafc;border-radius:6px;border-left:4px solid #3b82f6">
          <?= is_string($resultsData) && !str_starts_with($resultsData, '<pre') ? nl2br(esc($resultsData)) : $resultsData ?>
        </div>
      </div>
    </section>
    <?php endif; ?>
    
    <?php if (!empty($result['normal_range'])): ?>
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Normal Range</h2></div>
      <div class="panel-body">
        <p style="margin:0;color:#374151"><?= esc($result['normal_range']) ?></p>
      </div>
    </section>
    <?php endif; ?>
    
    <?php if (!empty($result['interpretation'])): ?>
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Interpretation</h2></div>
      <div class="panel-body">
        <p style="margin:0;color:#374151;white-space:pre-wrap"><?= esc($result['interpretation']) ?></p>
      </div>
    </section>
    <?php endif; ?>
    
    <?php if (!empty($result['result_date'])): ?>
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Result Information</h2></div>
      <div class="panel-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div>
            <strong style="color:#6b7280;font-size:0.875rem">Result Date</strong><br>
            <span style="font-size:1rem"><?= esc(date('M d, Y H:i', strtotime($result['result_date']))) ?></span>
          </div>
          <?php if (!empty($result['sample_collected_date'])): ?>
          <div>
            <strong style="color:#6b7280;font-size:0.875rem">Sample Collected Date</strong><br>
            <span style="font-size:1rem"><?= esc(date('M d, Y H:i', strtotime($result['sample_collected_date']))) ?></span>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>
    
    <div style="margin-top:16px;display:flex;gap:10px">
      <a href="<?= site_url('doctor/lab-results') ?>" class="btn" style="padding:10px 20px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">
        <i class="fas fa-arrow-left"></i> Back to Results
      </a>
      <?php if ($result['status'] === 'completed'): ?>
        <a href="<?= site_url('doctor/lab-results/' . $result['id'] . '/print') ?>" class="btn" style="padding:10px 20px;background:#10b981;color:white;border-radius:6px;text-decoration:none" target="_blank">
          <i class="fas fa-print"></i> Print Report
        </a>
      <?php endif; ?>
    </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

