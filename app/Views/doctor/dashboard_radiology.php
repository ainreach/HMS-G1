<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Radiologist Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text">
      <h1 style="font-size:1.25rem;margin:0"><?= esc($doctorSpecialization ?? 'Radiologist') ?></h1>
      <small><?= esc($doctorDepartment['name'] ?? 'Radiology') ?> Department</small>
    </div>
  </div>
  <div class="top-right" aria-label="User session">
    <div style="display:flex;align-items:center;gap:12px">
      <span style="padding:6px 12px;background:#dbeafe;color:#1e40af;border-radius:6px;font-size:0.875rem;font-weight:600">
        <i class="fa-solid fa-x-ray" style="margin-right:4px"></i>
        Radiologist
      </span>
      <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? 'User') ?></span>
      <a href="<?= site_url('logout') ?>" class="logout-btn" style="text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
    </div>
  </div>
</div></header>
<div class="layout">
<?= $this->include('doctor/sidebar', [
  'specialization' => $doctorSpecialization ?? 'Radiologist',
  'department' => $doctorDepartment ?? null,
  'currentPage' => 'dashboard'
]) ?>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card" style="background:#dbeafe;border-left:4px solid #3b82f6"><div class="kpi-head"><span>Imaging Requests</span><i class="fa-solid fa-x-ray" aria-hidden="true"></i></div><div class="kpi-value" style="color:#1e40af" aria-live="polite">0</div></article>
      <article class="kpi-card" style="background:#dcfce7;border-left:4px solid #16a34a"><div class="kpi-head"><span>Completed Scans</span><i class="fa-solid fa-check-circle" aria-hidden="true"></i></div><div class="kpi-value" style="color:#15803d" aria-live="polite">0</div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('doctor/imaging/requests') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;background:#dbeafe;color:#1e40af"><i class="fa-solid fa-x-ray" style="margin-right:4px"></i>View Imaging Requests</a>
        <a class="btn" href="<?= site_url('doctor/imaging/upload') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;background:#dcfce7;color:#15803d"><i class="fa-solid fa-upload" style="margin-right:4px"></i>Upload Report</a>
        <a class="btn" href="<?= site_url('doctor/patients') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">View Patients</a>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px">
      <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Imaging Requests</h2></div>
        <div class="panel-body" style="padding:20px;text-align:center;color:#6b7280">
          <i class="fa-solid fa-x-ray" style="font-size:3rem;opacity:0.3;margin-bottom:12px;display:block"></i>
          <p style="margin:0">Imaging requests from doctors will be displayed here.</p>
          <p style="margin:8px 0 0 0;font-size:0.875rem">Review and process X-ray, CT scan, MRI, and ultrasound requests.</p>
        </div>
      </section>

      <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Completed Reports</h2></div>
        <div class="panel-body" style="padding:20px;text-align:center;color:#6b7280">
          <i class="fa-solid fa-file-medical" style="font-size:3rem;opacity:0.3;margin-bottom:12px;display:block"></i>
          <p style="margin:0">Completed imaging reports will be displayed here.</p>
          <p style="margin:8px 0 0 0;font-size:0.875rem">View and manage completed radiology reports.</p>
        </div>
      </section>
    </div>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">My Weekly Schedule</h2></div>
      <div class="panel-body" style="overflow:auto">
        <?php
          $dayNames = ['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'];
        ?>
        <?php if (!empty($schedule)) : ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Day</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Start</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">End</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($schedule as $row) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($dayNames[strtolower($row['day_of_week'] ?? '')] ?? ucfirst($row['day_of_week'] ?? '')) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('g:i A', strtotime($row['start_time'] ?? '00:00:00'))) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('g:i A', strtotime($row['end_time'] ?? '00:00:00'))) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else : ?>
          <p style="padding:8px;color:#6b7280;">No schedule defined. Please contact the administrator.</p>
        <?php endif; ?>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

