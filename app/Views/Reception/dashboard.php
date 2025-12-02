<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Room Management</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Room Management</small></div>
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
  <a href="<?= site_url('reception/patients') ?>" data-feature="ehr">Patient Management</a>
  <a href="<?= site_url('reception/appointments') ?>" data-feature="scheduling">Appointment Management</a>
  <a href="<?= site_url('reception/rooms') ?>" class="active" aria-current="page">Room Management</a>
  <a href="<?= site_url('reception/patient-lookup') ?>" data-feature="ehr">Patient Lookup</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Check-ins Today</span><i class="fa-solid fa-user-check" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($checkinsToday ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Upcoming Appointments</span><i class="fa-solid fa-calendar" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($upcomingAppts ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Total Patients</span><i class="fa-solid fa-users" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($totalPatients ?? 0) ?></div></article>
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>New Today</span><i class="fa-solid fa-user-plus" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($newPatientsToday ?? 0) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('reception/patients/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Register Patient</a>
        <a class="btn" href="<?= site_url('reception/appointments/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Book Appointment</a>
        <a class="btn" href="<?= site_url('reception/patients') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">View Patients</a>
        <a class="btn" href="<?= site_url('reception/appointments') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Manage Appointments</a>
        <a class="btn" href="<?= site_url('reception/rooms') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Room Management</a>
        <a class="btn" href="<?= site_url('reception/patient-lookup') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Patient Lookup</a>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Today's Appointments</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Doctor</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Time</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($appointments)) : foreach ($appointments as $appointment) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">Dr. <?= esc($appointment['doctor_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['appointment_time']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <span style="padding:2px 6px;border-radius:4px;font-size:0.75rem;background-color:<?= $appointment['status'] === 'checked-in' ? '#d1fae5' : ($appointment['status'] === 'scheduled' ? '#dbeafe' : '#fef3c7') ?>;color:<?= $appointment['status'] === 'checked-in' ? '#065f46' : ($appointment['status'] === 'scheduled' ? '#1e40af' : '#92400e') ?>">
                    <?= esc(ucfirst($appointment['status'] ?: 'Scheduled')) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="4" style="padding:10px;color:#6b7280;text-align:center">No appointments today.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem;">
      <!-- Recent Patient Registrations -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Recent Patient Registrations</h2>
        </div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">ID</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Phone</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recentPatients)) : foreach ($recentPatients as $patient) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($patient['patient_id']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($patient['phone'] ?: 'N/A') ?></td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No recent patients.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Tomorrow's Appointments -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Tomorrow's Appointments</h2>
        </div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Doctor</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Time</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($tomorrowAppts)) : foreach ($tomorrowAppts as $appointment) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">Dr. <?= esc($appointment['doctor_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['appointment_time']) ?></td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No appointments tomorrow.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
