<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Doctor Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Doctor</h1><small>Patient records • Prescriptions • Requests</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Doctor navigation" style="background:#ffffff;border-right:1px solid #e5e7eb;padding:0">
  <div style="padding:20px;border-bottom:1px solid #e5e7eb">
    <h2 style="margin:0;font-size:1rem;font-weight:700;color:#1f2937;text-transform:uppercase;letter-spacing:0.5px">DOCTOR PANEL</h2>
  </div>
  <nav class="side-nav" style="padding:10px 0">
    <a href="<?= site_url('dashboard/doctor') ?>" class="active" aria-current="page" style="display:flex;align-items:center;padding:12px 20px;color:#1f2937;text-decoration:none;font-weight:500;position:relative;background:#f0f9ff;border-left:4px solid #3b82f6">
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
    <a href="<?= site_url('doctor/admit-patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
      <i class="fa-solid fa-users" style="width:20px;margin-right:12px;font-size:1rem"></i>Admitted Patients
    </a>
    <a href="<?= site_url('doctor/discharge-patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
      <i class="fa-solid fa-sign-out-alt" style="width:20px;margin-right:12px;font-size:1rem"></i>Discharge Patients
    </a>
  </nav>
</aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Today Appointments</span><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($todayAppointments ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Pending Results</span><i class="fa-solid fa-flask-vial" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($pendingLabResults ?? 0) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('doctor/records/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">New Medical Record</a>
        <a class="btn" href="<?= site_url('doctor/lab-requests/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Request Lab Test</a>
        <a class="btn" href="<?= site_url('doctor/patients') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">View Patients</a>
        <a class="btn" href="<?= site_url('doctor/admit-patients') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Admit Patient</a>
        <a class="btn" href="<?= site_url('doctor/prescriptions') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Prescriptions</a>
        <a class="btn" href="<?= site_url('doctor/lab-results') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Lab Results</a>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Today's Patients</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Time</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Reason</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($appointments)) : foreach ($appointments as $appointment) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['appointment_time']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['reason'] ?: 'Consultation') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucfirst($appointment['status'] ?: 'Scheduled')) ?></td>
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

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">My Weekly Schedule</h2></div>
      <div class="panel-body" style="overflow:auto">
        <?php
          $dayNames = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
          ];
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
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc($dayNames[strtolower($row['day_of_week'] ?? '')] ?? ucfirst($row['day_of_week'] ?? '')) ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc(date('g:i A', strtotime($row['start_time'] ?? '00:00:00'))) ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc(date('g:i A', strtotime($row['end_time'] ?? '00:00:00'))) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else : ?>
          <p style="padding:8px;color:#6b7280;">No schedule defined. Please contact the administrator.</p>
        <?php endif; ?>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem;">
      <!-- Recent Medical Records -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Recent Medical Records</h2>
        </div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Diagnosis</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recentRecords)) : foreach ($recentRecords as $record) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($record['first_name'] . ' ' . $record['last_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('M j, Y', strtotime($record['visit_date']))) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($record['diagnosis'] ?: 'N/A') ?></td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No recent records.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Pending Lab Tests -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Pending Lab Tests</h2>
        </div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($pendingTests)) : foreach ($pendingTests as $test) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['first_name'] . ' ' . $test['last_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['test_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucfirst($test['status'] ?: 'Pending')) ?></td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No pending tests.</td>
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
