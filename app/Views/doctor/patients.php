<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patients</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .patient-table thead {
      background: #dcfce7;
    }
    .patient-table thead th {
      color: #166534;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
    }
    .badge-pill {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .badge-gender {
      background: #dbeafe;
      color: #1e40af;
    }
    .badge-visit-type {
      background: #f3f4f6;
      color: #6b7280;
    }
    .btn-consultation {
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
    }
    .btn-consultation:hover {
      background: #15803d;
    }
    .btn-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-size: 0.875rem;
      border: none;
      cursor: pointer;
    }
    .btn-view {
      background: #3b82f6;
      color: white;
    }
    .btn-edit {
      background: #f97316;
      color: white;
    }
    .btn-delete {
      background: #ef4444;
      color: white;
    }
    .badge-done {
      background: #dcfce7;
      color: #166534;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Patients</h1><small>Patient management</small></div>
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
    <a href="<?= site_url('doctor/patients') ?>" class="active" aria-current="page" style="display:flex;align-items:center;padding:12px 20px;color:#1f2937;text-decoration:none;font-weight:500;position:relative;background:#f0f9ff;border-left:4px solid #3b82f6">
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
    <a href="<?= site_url('doctor/admit-patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
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
        <h2 style="margin:0;font-size:1.1rem">Active Patients</h2>
        <a class="btn" href="<?= site_url('doctor/patients/new') ?>" style="text-decoration:none;background:#3b82f6;color:white;padding:8px 16px;border-radius:6px">
          <i class="fa-solid fa-plus"></i> New Patient
        </a>
      </div>
      <div class="panel-body" style="overflow:auto;padding:0">
        <table class="table patient-table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">ID</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">NAME</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">BIRTHDATE</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">GENDER</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">VISIT TYPE</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">CONTACT</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">ADDRESS</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($patients)) : foreach ($patients as $p) : ?>
              <?php
                $patientId = $p['id'];
                $fullName = trim(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? ''));
                $visitType = $p['admission_type'] ?? 'N/A';
                if ($visitType === 'admission') {
                  $visitType = 'In-Patient';
                } elseif ($visitType === 'checkup') {
                  $visitType = 'Out-Patient';
                }
              ?>
              <tr>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">#<?= esc($patientId) ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><strong><?= esc($fullName) ?></strong></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc(!empty($p['date_of_birth']) ? date('M d, Y', strtotime($p['date_of_birth'])) : 'N/A') ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <span class="badge-pill badge-gender"><?= esc(strtoupper($p['gender'] ?? 'N/A')) ?></span>
                </td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <span class="badge-pill badge-visit-type"><?= esc($visitType) ?></span>
                </td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($p['phone'] ?? 'N/A') ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($p['address'] ?? 'N/A') ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <div style="display:flex;align-items:center;gap:8px">
                    <?php if (!empty($p['consultation_done'])): ?>
                      <span class="badge-done">
                        <i class="fa-solid fa-check-circle"></i> Done
                      </span>
                    <?php else: ?>
                      <a href="<?= site_url('doctor/patients/consultation/' . $patientId) ?>" class="btn-consultation">
                        Start Consultation
                      </a>
                    <?php endif; ?>
                    <a href="<?= site_url('doctor/patients/view/' . $patientId) ?>" class="btn-icon btn-view" title="View">
                      <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="<?= site_url('doctor/patients/edit/' . $patientId) ?>" class="btn-icon btn-edit" title="Edit">
                      <i class="fa-solid fa-pencil"></i>
                    </a>
                    <a href="<?= site_url('doctor/patients/delete/' . $patientId) ?>" class="btn-icon btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this patient?')">
                      <i class="fa-solid fa-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="8" style="padding:20px;color:#6b7280;text-align:center">No patients found.</td>
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


