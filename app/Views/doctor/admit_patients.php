<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admit Patients</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .admit-table thead {
      background: #fef3c7;
    }
    .admit-table thead th {
      color: #d97706;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
    }
    .btn-admit {
      background: #16a34a;
      color: white;
      padding: 8px 16px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .btn-admit:hover {
      background: #15803d;
      transform: translateY(-1px);
    }
    .badge-room {
      background: #dbeafe;
      color: #1e40af;
      padding: 4px 10px;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand">
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0;color:#1f2937">Admit Patients</h1><small style="color:#6b7280">In-patient admissions</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Doctor navigation" style="background:#ffffff;border-right:1px solid #e5e7eb;padding:0">
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
    <a href="<?= site_url('doctor/upcoming-consultations') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
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
    <a href="<?= site_url('doctor/admit-patients') ?>" class="active" aria-current="page" style="display:flex;align-items:center;padding:12px 20px;color:#1f2937;text-decoration:none;font-weight:500;position:relative;background:#f0f9ff;border-left:4px solid #3b82f6">
      <i class="fa-solid fa-users" style="width:20px;margin-right:12px;font-size:1rem"></i>Admitted Patients
    </a>
    <a href="<?= site_url('doctor/discharge-patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
      <i class="fa-solid fa-sign-out-alt" style="width:20px;margin-right:12px;font-size:1rem"></i>Discharge Patients
    </a>
  </nav>
</aside>
  <main class="content">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Pending In-Patient Admissions</h2>
        <span style="color:#6b7280;font-size:0.875rem"><?= count($pendingAdmissions) ?> patient(s) waiting</span>
      </div>
      <div class="panel-body" style="overflow:auto;padding:0">
        <table class="table admit-table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #fde68a">ID</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #fde68a">PATIENT NAME</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #fde68a">ROOM</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #fde68a">REGISTERED</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #fde68a">CONTACT</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #fde68a">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pendingAdmissions)) : foreach ($pendingAdmissions as $patient) : ?>
              <tr>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">#<?= esc($patient['id']) ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <strong><?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?></strong><br>
                  <small style="color:#6b7280"><?= esc($patient['patient_id'] ?? 'N/A') ?></small>
                </td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <?php if ($patient['room_number'] !== 'Not assigned'): ?>
                    <span class="badge-room">
                      <?= esc($patient['room_number']) ?>
                      <?php if (!empty($patient['bed_number'])): ?>
                        - Bed <?= esc($patient['bed_number']) ?>
                      <?php endif; ?>
                    </span>
                    <?php if (!empty($patient['room_type'])): ?>
                      <br><small style="color:#6b7280"><?= esc($patient['room_type']) ?></small>
                    <?php endif; ?>
                  <?php else: ?>
                    <span style="color:#ef4444;font-weight:600">Not assigned</span>
                  <?php endif; ?>
                </td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <?= esc(date('M d, Y', strtotime($patient['created_at']))) ?><br>
                  <small style="color:#6b7280"><?= esc(date('h:i A', strtotime($patient['created_at']))) ?></small>
                </td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($patient['phone'] ?? 'N/A') ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <a href="<?= site_url('doctor/admit-patient/' . $patient['id']) ?>" class="btn-admit">
                    <i class="fa-solid fa-bed-pulse"></i> Admit Patient
                  </a>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="6" style="padding:40px;color:#6b7280;text-align:center">
                  <i class="fa-solid fa-check-circle" style="font-size:3rem;color:#d1fae5;margin-bottom:16px;display:block"></i>
                  <p style="margin:0;font-size:1rem">No pending in-patient admissions</p>
                  <p style="margin:8px 0 0 0;font-size:0.875rem;color:#9ca3af">All in-patients have been admitted</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

