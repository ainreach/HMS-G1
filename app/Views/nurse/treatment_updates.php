<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Treatment Updates - Nurse Dashboard</title>
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
  <a href="<?= site_url('nurse/treatment-updates') ?>" class="active" aria-current="page" data-feature="ehr">Treatment Updates</a>
  <a href="<?= site_url('nurse/vitals/new') ?>" data-feature="ehr">Record Vitals</a>
</nav></aside>
  <main class="content">
    <h1 style="font-size:1.5rem;margin-bottom:1rem">Treatment Updates</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div style="padding:12px;background:#d4edda;border:1px solid #c3e6cb;border-radius:6px;margin-bottom:16px;color:#155724">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Add New Treatment Update Form -->
    <section class="panel" style="margin-bottom:16px">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Add New Treatment Update</h2></div>
        <div class="panel-body">
            <form action="<?= site_url('nurse/treatment-updates') ?>" method="post">
                <?= csrf_field() ?>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                    <div>
                        <label for="patient_id" style="display:block;margin-bottom:6px;font-weight:500">Patient</label>
                        <select style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px" id="patient_id" name="patient_id" required>
                            <option value="">Select Patient</option>
                            <?php foreach ($patients ?? [] as $patient): ?>
                                <option value="<?= $patient['id'] ?>">
                                    <?= $patient['first_name'] . ' ' . $patient['last_name'] . ' (' . $patient['patient_id'] . ')' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="doctor_id" style="display:block;margin-bottom:6px;font-weight:500">Doctor</label>
                        <select style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px" id="doctor_id" name="doctor_id">
                            <option value="">Select Doctor</option>
                            <?php foreach ($doctors ?? [] as $doctor): ?>
                                <option value="<?= $doctor['id'] ?>">
                                    <?= $doctor['first_name'] . ' ' . $doctor['last_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom:16px">
                    <label for="treatment_update" style="display:block;margin-bottom:6px;font-weight:500">Treatment Update</label>
                    <textarea style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;font-family:inherit" id="treatment_update" name="treatment_update" rows="3" required></textarea>
                </div>
                <button type="submit" style="background:#0ea5e9;color:white;padding:10px 20px;border:none;border-radius:6px;cursor:pointer;font-weight:500">Save Update</button>
            </form>
        </div>
    </section>

    <!-- Treatment Updates List -->
    <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Treatment Updates</h2></div>
        <div class="panel-body" style="overflow:auto">
                <table class="table" style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Date</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Patient</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Doctor</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($updates as $update): ?>
                        <tr>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= date('M d, Y H:i', strtotime($update['visit_date'])) ?></td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                <?= $update['first_name'] . ' ' . $update['last_name'] ?>
                                <small style="display:block;color:#6b7280">ID: <?= $update['patient_code'] ?></small>
                            </td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb">Dr. <?= $update['doctor_name'] ?? 'N/A' ?></td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= nl2br(htmlspecialchars($update['treatment_plan'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
    </section>
  </main>
</div>
</body>
</html>
