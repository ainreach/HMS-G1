<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Record Vitals - Nurse Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Nurse</h1><small>Patient monitoring • Treatment updates</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Nurse navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/nurse') ?>">Overview</a>
  <a href="<?= site_url('nurse/ward-patients') ?>" data-feature="ehr">Ward Patients</a>
  <a href="<?= site_url('nurse/lab-samples') ?>" data-feature="laboratory">Lab Samples</a>
  <a href="<?= site_url('nurse/treatment-updates') ?>" data-feature="ehr">Treatment Updates</a>
  <a href="<?= site_url('nurse/vitals/new') ?>" class="active" aria-current="page" data-feature="ehr">Record Vitals</a>
</nav></aside>
  <main class="content">
    <h1 style="font-size:1.5rem;margin-bottom:1rem">Record Vitals</h1>
    
    <?php if (session('error')): ?>
      <div style="padding:12px;background:#fee2e2;border:1px solid #fecaca;border-radius:6px;margin-bottom:16px;color:#991b1b">
        <?= esc(session('error')) ?>
      </div>
    <?php endif; ?>
    
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Patient Vital Signs</h2></div>
      <div class="panel-body">
        <form method="post" action="<?= site_url('nurse/vitals') ?>">
          <?= csrf_field() ?>
          
          <div style="margin-bottom:16px">
            <label for="patient_id" style="display:block;margin-bottom:6px;font-weight:500">Patient <span style="color:#dc2626">*</span></label>
            <select style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px" id="patient_id" name="patient_id" required>
              <option value="">Select Patient</option>
              <?php foreach ($patients ?? [] as $patient): ?>
                <option value="<?= $patient['id'] ?>" <?= ($preselectedPatientId == $patient['id']) ? 'selected' : '' ?>>
                  <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?> (<?= esc($patient['patient_id']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <div style="margin-bottom:16px">
            <label for="doctor_id" style="display:block;margin-bottom:6px;font-weight:500">Doctor (optional)</label>
            <select style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px" id="doctor_id" name="doctor_id">
              <option value="">Select Doctor</option>
              <?php foreach ($doctors ?? [] as $doctor): ?>
                <option value="<?= $doctor['id'] ?>">
                  Dr. <?= esc($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <div style="margin-bottom:16px">
            <label for="vitals" style="display:block;margin-bottom:6px;font-weight:500">Vital Signs <span style="color:#dc2626">*</span></label>
            <textarea style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;font-family:inherit" id="vitals" name="vitals" rows="6" placeholder="BP: 120/80, HR: 78, Temp: 36.8°C, RR: 16, SpO2: 98%" required><?= old('vitals') ?></textarea>
            <small style="color:#6b7280;display:block;margin-top:4px">Enter vital signs in format: BP: 120/80, Pulse: 72, Temp: 37°C, RR: 16, SpO2: 98%</small>
          </div>
          
          <div style="display:flex;gap:8px;margin-top:16px">
            <button type="submit" style="background:#0ea5e9;color:white;padding:10px 20px;border:none;border-radius:6px;cursor:pointer;font-weight:500">Save Vitals</button>
            <a href="<?= site_url('dashboard/nurse') ?>" style="background:#6b7280;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;display:inline-block">Cancel</a>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>
</body></html>
