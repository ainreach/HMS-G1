<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Upcoming Consultations - Doctor</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <div class="brand">
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0;color:#1f2937">
          <i class="fa-solid fa-calendar-check" style="color:#16a34a;margin-right:8px"></i>Upcoming Consultations
        </h1>
        <small style="color:#6b7280">Scheduled appointments</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
      <a href="<?= site_url('dashboard/doctor') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Doctor navigation" style="background:#ffffff;border-right:1px solid #e5e7eb;padding:0">
    <div style="padding:20px;border-bottom:1px solid #e5e7eb">
      <h2 style="margin:0;font-size:1rem;font-weight:700;color:#1f2937;text-transform:uppercase;letter-spacing:0.5px">DOCTOR PANEL</h2>
    </div>
    <nav class="side-nav" style="padding:10px 0">
      <a href="<?= site_url('dashboard/doctor') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-gauge" style="width:20px;margin-right:12px;font-size:1rem"></i>Dashboard
      </a>
      <a href="<?= site_url('doctor/patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-users" style="width:20px;margin-right:12px;font-size:1rem"></i>Patient List
      </a>
      <a href="<?= site_url('doctor/upcoming-consultations') ?>" class="active" aria-current="page" style="display:flex;align-items:center;padding:12px 20px;color:#1f2937;text-decoration:none;font-weight:500;position:relative;background:#f0f9ff;border-left:4px solid #3b82f6">
        <i class="fa-solid fa-calendar-check" style="width:20px;margin-right:12px;font-size:1rem"></i>Upcoming Consultations
      </a>
      <a href="<?= site_url('doctor/schedule') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-calendar" style="width:20px;margin-right:12px;font-size:1rem"></i>My Schedule
      </a>
      <a href="<?= site_url('doctor/lab-requests-nurses') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-vial" style="width:20px;margin-right:12px;font-size:1rem"></i>Lab Requests
      </a>
      <a href="<?= site_url('doctor/orders') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-prescription" style="width:20px;margin-right:12px;font-size:1rem"></i>Doctor Orders
      </a>
      <a href="<?= site_url('doctor/admit-patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-users" style="width:20px;margin-right:12px;font-size:1rem"></i>Admitted Patients
      </a>
      <a href="<?= site_url('doctor/discharge-patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-sign-out-alt" style="width:20px;margin-right:12px;font-size:1rem"></i>Discharge Patients
      </a>
    </nav>
  </aside>

  <main class="content" style="padding:16px">
    <?php if (session()->getFlashdata('success')): ?>
      <div style="padding:12px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;margin-bottom:16px;color:#065f46">
        <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Upcoming Consultations</h2>
        <div style="display:flex;gap:8px">
          <a href="<?= site_url('doctor/schedule') ?>" style="padding:8px 16px;background:#3b82f6;color:white;border-radius:6px;text-decoration:none;font-size:0.875rem">
            <i class="fa-solid fa-calendar"></i> Full Schedule
          </a>
          <a href="<?= site_url('doctor/patients') ?>" style="padding:8px 16px;background:#16a34a;color:white;border-radius:6px;text-decoration:none;font-size:0.875rem">
            <i class="fa-solid fa-plus"></i> New Consultation
          </a>
        </div>
      </div>
      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($consultations)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Date & Time</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Patient</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Reason</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Status</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($consultations as $consultation): ?>
                <tr style="border-bottom:1px solid #f3f4f6">
                  <td style="padding:10px">
                    <div style="font-weight:600"><?= esc(date('M d, Y', strtotime($consultation['appointment_date']))) ?></div>
                    <div style="color:#6b7280;font-size:0.875rem"><?= esc($consultation['appointment_time']) ?></div>
                  </td>
                  <td style="padding:10px">
                    <div style="font-weight:600"><?= esc(trim(($consultation['first_name'] ?? '') . ' ' . ($consultation['last_name'] ?? ''))) ?></div>
                    <div style="color:#6b7280;font-size:0.875rem"><?= esc($consultation['patient_code'] ?? 'N/A') ?></div>
                  </td>
                  <td style="padding:10px"><?= esc($consultation['reason'] ?? 'Consultation') ?></td>
                  <td style="padding:10px">
                    <span style="padding:4px 8px;border-radius:4px;font-size:0.85rem;background:#dbeafe;color:#1e40af">
                      <?= esc(ucfirst(str_replace('_', ' ', $consultation['status'] ?? 'scheduled'))) ?>
                    </span>
                  </td>
                  <td style="padding:10px">
                    <a href="<?= site_url('doctor/patients/consultation/' . $consultation['patient_id']) ?>" 
                       style="padding:6px 12px;background:#16a34a;color:white;border-radius:4px;text-decoration:none;font-size:0.875rem">
                      <i class="fa-solid fa-stethoscope"></i> Start
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div style="padding:60px 20px;text-align:center">
            <i class="fa-solid fa-calendar" style="font-size:4rem;color:#d1d5db;margin-bottom:16px"></i>
            <h3 style="margin:0 0 8px 0;color:#374151;font-size:1.25rem">No Upcoming Consultations</h3>
            <p style="margin:0;color:#6b7280">You don't have any approved upcoming consultations.</p>
            <a href="<?= site_url('doctor/patients') ?>" style="display:inline-block;margin-top:20px;padding:12px 24px;background:#16a34a;color:white;border-radius:6px;text-decoration:none;font-weight:600">
              <i class="fa-solid fa-plus"></i> Schedule New Consultation
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>

