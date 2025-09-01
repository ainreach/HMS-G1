<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Doctor Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
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
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Doctor navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/doctor') ?>" class="active" aria-current="page">Overview</a>
  <a href="<?= site_url('records') ?>" data-feature="ehr">Patient Records</a>
  <a href="<?= site_url('laboratory') ?>" data-feature="laboratory">Request Tests</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Today Appointments</span><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite">—</div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Pending Results</span><i class="fa-solid fa-flask-vial" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite">—</div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('doctor/records/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">New Medical Record</a>
        <a class="btn" href="<?= site_url('doctor/lab-requests/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Request Lab Test</a>
        <a class="btn" href="<?= site_url('doctor/records/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Write Note</a>
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
            <tr>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">Juan D.</td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">09:00</td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">Follow-up</td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">Waiting</td>
            </tr>
            <tr>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">Maria C.</td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">10:15</td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">Consultation</td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">In progress</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
