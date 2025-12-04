<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Appointment Details</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Appointment Details</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Reception navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/receptionist') ?>">Overview</a>
  <a href="<?= site_url('reception/patients') ?>">Patient Management</a>
  <a href="<?= site_url('reception/appointments') ?>" class="active" aria-current="page">Appointment Management</a>
  <a href="<?= site_url('reception/patient-lookup') ?>">Patient Lookup</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Appointment #<?= esc($appointment['appointment_number'] ?? $appointment['id'] ?? '') ?></h2></div>
      <div class="panel-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
          <div>
            <h3 style="margin:0 0 .5rem 0;font-size:1rem;color:#374151">Patient</h3>
            <p style="margin:.25rem 0">Name: <strong><?= esc(($appointment['first_name'] ?? '') . ' ' . ($appointment['last_name'] ?? '')) ?></strong></p>
            <p style="margin:.25rem 0">Patient ID: <strong><?= esc($appointment['patient_code'] ?? '') ?></strong></p>
            <p style="margin:.25rem 0">Phone: <?= esc($appointment['phone'] ?? 'N/A') ?></p>
            <p style="margin:.25rem 0">Email: <?= esc($appointment['email'] ?? 'N/A') ?></p>
          </div>
          <?php
            $doctorLabel = 'Dr. ' . trim(($appointment['doctor_first'] ?? '') . ' ' . ($appointment['doctor_last'] ?? ''));
            if (trim($doctorLabel) === 'Dr.') {
                // Fallback to joined doctor_name (username) or raw doctor_id
                $doctorLabel = isset($appointment['doctor_name'])
                  ? 'Dr. ' . $appointment['doctor_name']
                  : 'Doctor ID: ' . (string)($appointment['doctor_id'] ?? 'Unknown');
            }

            $dateLabel = !empty($appointment['appointment_date']) ? date('M j, Y', strtotime($appointment['appointment_date'])) : '';
            $timeLabel = !empty($appointment['appointment_time']) ? date('g:i A', strtotime($appointment['appointment_time'])) : '';

            $rawStatus = strtolower(trim($appointment['status'] ?? 'scheduled'));
            if ($rawStatus === '') { $rawStatus = 'scheduled'; }
            $statusLabel = $rawStatus === 'checked-in' ? 'Checked-in'
                         : ($rawStatus === 'cancelled' ? 'Cancelled'
                         : 'Scheduled');
            $statusBg = $rawStatus === 'checked-in' ? '#d1fae5'
                      : ($rawStatus === 'cancelled' ? '#fee2e2'
                      : '#dbeafe');
            $statusColor = $rawStatus === 'checked-in' ? '#065f46'
                          : ($rawStatus === 'cancelled' ? '#b91c1c'
                          : '#1e40af');
          ?>
          <div>
            <h3 style="margin:0 0 .5rem 0;font-size:1rem;color:#374151">Appointment</h3>
            <p style="margin:.25rem 0">Doctor: <strong><?= esc($doctorLabel) ?></strong></p>
            <p style="margin:.25rem 0">Date: <?= esc($dateLabel) ?></p>
            <p style="margin:.25rem 0">Time: <?= esc($timeLabel) ?></p>
            <p style="margin:.25rem 0">Type: <?= esc(ucfirst($appointment['type'] ?? 'consultation')) ?></p>
            <p style="margin:.25rem 0">Status:
              <span style="padding:2px 6px;border-radius:4px;font-size:0.8rem;background-color:<?= $statusBg ?>;color:<?= $statusColor ?>;margin-left:4px;display:inline-block;min-width:80px;text-align:center;">
                <?= esc($statusLabel) ?>
              </span>
            </p>
          </div>
        </div>
        <div style="margin-top:1rem">
          <a href="<?= site_url('reception/appointments') ?>" class="btn" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Back</a>
          <a href="<?= site_url('reception/appointments/' . ($appointment['id'] ?? 0) . '/edit') ?>" class="btn" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Edit</a>
        </div>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>


