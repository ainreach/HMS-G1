<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Appointment Management</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Appointment Management</small></div>
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
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Appointments</h2>
        <a class="btn" href="<?= site_url('reception/appointments/new') ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none"><i class="fa-solid fa-calendar-plus"></i> New Appointment</a>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Doctor</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Time</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($appointments)) : foreach ($appointments as $a) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? '')) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">Dr. <?= esc($a['doctor_last'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($a['appointment_date'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($a['appointment_time'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><span style="padding:2px 6px;border-radius:4px;font-size:0.75rem;background-color:<?= ($a['status'] ?? '') === 'checked-in' ? '#d1fae5' : (($a['status'] ?? '') === 'scheduled' ? '#dbeafe' : '#fef3c7') ?>;color:<?= ($a['status'] ?? '') === 'checked-in' ? '#065f46' : (($a['status'] ?? '') === 'scheduled' ? '#1e40af' : '#92400e') ?>"><?= esc(ucfirst($a['status'] ?? 'Scheduled')) ?></span></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <a href="<?= site_url('reception/appointments/' . ($a['id'] ?? 0)) ?>" class="btn" style="padding:4px 8px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">View</a>
                  <a href="<?= site_url('reception/appointments/' . ($a['id'] ?? 0) . '/edit') ?>" class="btn" style="padding:4px 8px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">Edit</a>
                  <form action="<?= site_url('reception/appointments/' . ($a['id'] ?? 0) . '/checkin') ?>" method="post" style="display:inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn" style="padding:4px 8px;border:1px solid #e5e7eb;border-radius:6px;background:#f9fafb">Check-in</button>
                  </form>
                  <form action="<?= site_url('reception/appointments/' . ($a['id'] ?? 0) . '/cancel') ?>" method="post" style="display:inline" onsubmit="return confirm('Cancel this appointment?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn" style="padding:4px 8px;border:1px solid #e5e7eb;border-radius:6px;background:#fff0f0;color:#991b1b">Cancel</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="6" style="padding:10px;color:#6b7280;text-align:center">No appointments found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>


