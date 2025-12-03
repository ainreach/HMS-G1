<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Appointment</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Edit Appointment</small></div>
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
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Edit Appointment</h2></div>
      <div class="panel-body">
        <form action="<?= site_url('reception/appointments/' . ($appointment['id'] ?? 0)) ?>" method="post" style="display:grid;gap:12px;max-width:560px">
          <?= csrf_field() ?>
          <label>Patient
            <select name="patient_id" required style="width:100%">
              <?php foreach (($patients ?? []) as $p): ?>
                <option value="<?= esc($p['id']) ?>" <?= (int)($appointment['patient_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>><?= esc(($p['last_name'] ?? '') . ', ' . ($p['first_name'] ?? '')) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label>Doctor
            <select name="doctor_id" required style="width:100%">
              <?php foreach (($doctors ?? []) as $d): ?>
                <option value="<?= esc($d['id']) ?>" <?= (int)($appointment['doctor_id'] ?? 0) === (int)$d['id'] ? 'selected' : '' ?>>Dr. <?= esc(($d['last_name'] ?? '')) ?> (<?= esc($d['first_name'] ?? '') ?>)</option>
              <?php endforeach; ?>
            </select>
          </label>
          <label>Date
            <input type="date" name="appointment_date" value="<?= esc($appointment['appointment_date'] ?? '') ?>" required />
          </label>
          <label>Time
            <input type="time" name="appointment_time" value="<?= esc($appointment['appointment_time'] ?? '') ?>" required />
          </label>
          <label>Duration (minutes)
            <input type="number" name="duration" min="5" step="5" value="<?= esc($appointment['duration'] ?? 30) ?>" />
          </label>
          <label>Type
            <input type="text" name="type" value="<?= esc($appointment['type'] ?? 'consultation') ?>" />
          </label>
          <label>Reason
            <input type="text" name="reason" value="<?= esc($appointment['reason'] ?? '') ?>" />
          </label>
          <label>Notes
            <textarea name="notes" rows="3"><?= esc($appointment['notes'] ?? '') ?></textarea>
          </label>
          <div style="margin-top:8px">
            <button type="submit" class="btn" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;background:#111827;color:#fff">Save Changes</button>
            <a href="<?= site_url('reception/appointments') ?>" class="btn" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Cancel</a>
          </div>
        </form>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>


