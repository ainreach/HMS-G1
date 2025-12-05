<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pending Admissions</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .admit-table thead {
      background: #f3f4f6;
    }
    .admit-table thead th {
      color: #000000;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
    }
    .btn-admit {
      background: #000000;
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
      background: #333333;
      transform: translateY(-1px);
    }
    .badge-room {
      background: #e5e7eb;
      color: #000000;
      padding: 4px 10px;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand">
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0;color:#1f2937">Pending Admissions</h1><small style="color:#6b7280">In-patient admissions</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Nurse navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/nurse') ?>"><i class="fa-solid fa-house-medical" style="margin-right:8px"></i>Overview</a>
  <a href="<?= site_url('nurse/ward-patients') ?>"><i class="fa-solid fa-bed-pulse" style="margin-right:8px"></i>Ward Patients</a>
  <a href="<?= site_url('nurse/lab-samples') ?>"><i class="fa-solid fa-flask" style="margin-right:8px"></i>Lab Samples</a>
  <a href="<?= site_url('nurse/lab-request') ?>"><i class="fa-solid fa-vial" style="margin-right:8px"></i>New Lab Request</a>
  <a href="<?= site_url('nurse/treatment-updates') ?>"><i class="fa-solid fa-notes-medical" style="margin-right:8px"></i>Treatment Updates</a>
  <a href="<?= site_url('nurse/vitals/new') ?>"><i class="fa-solid fa-heart-pulse" style="margin-right:8px"></i>Record Vitals</a>
  <a href="<?= site_url('nurse/pending-admissions') ?>" class="active" aria-current="page"><i class="fa-solid fa-hospital" style="margin-right:8px"></i>Pending Admissions</a>
</nav></aside>
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
              <th style="text-align:left;padding:12px;border-bottom:2px solid #d1d5db">ID</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #d1d5db">PATIENT NAME</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #d1d5db">DOCTOR</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #d1d5db">CONSULTATION</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #d1d5db">ROOM</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #d1d5db">CONTACT</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #d1d5db">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pendingAdmissions)) : foreach ($pendingAdmissions as $patient): 
              $consultationDate = $patient['consultation_date'] ?? $patient['created_at'] ?? date('Y-m-d');
              if ($consultationDate) {
                try {
                  $consultationDate = date('M d, Y', strtotime($consultationDate));
                } catch (\Exception $e) {
                  $consultationDate = date('M d, Y');
                }
              }
            ?>
              <tr>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">#<?= esc($patient['id']) ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <strong><?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?></strong><br>
                  <small style="color:#6b7280"><?= esc($patient['patient_id'] ?? 'N/A') ?></small>
                </td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($patient['doctor_name'] ?? 'N/A') ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($consultationDate) ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <?php if ($patient['room_number'] !== 'Not assigned'): ?>
                    <span class="badge-room"><?= esc($patient['room_number']) ?></span>
                    <?php if (!empty($patient['room_type'])): ?>
                      <br><small style="color:#6b7280"><?= esc($patient['room_type']) ?></small>
                    <?php endif; ?>
                  <?php else: ?>
                    <span style="color:#ef4444;font-weight:600">Not assigned</span>
                  <?php endif; ?>
                </td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($patient['phone'] ?? 'N/A') ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <div style="display:flex;gap:8px;align-items:center">
                    <?php if ($patient['room_number'] === 'Not assigned'): ?>
                      <a href="<?= site_url('nurse/admit-patient/' . $patient['id']) ?>" class="btn-admit">
                        <i class="fa-solid fa-bed-pulse"></i> Admit to Room
                      </a>
                    <?php else: ?>
                      <span style="color:#10b981;font-weight:600;font-size:0.875rem">Admitted</span>
                    <?php endif; ?>
                    <a href="<?= site_url('nurse/patients/view/' . $patient['id']) ?>" style="background:#6b7280;color:white;padding:8px 12px;border-radius:6px;text-decoration:none;font-size:0.875rem;font-weight:600;display:inline-flex;align-items:center;gap:4px">
                      <i class="fa-solid fa-eye"></i> View
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="7" style="padding:40px;color:#6b7280;text-align:center">
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
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

