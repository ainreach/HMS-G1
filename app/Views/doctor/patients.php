<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patients</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem">Patients</h1>
</div></header>
<div class="layout"><aside class="simple-sidebar"><nav class="side-nav">
  <a href="<?= site_url('dashboard/doctor') ?>">Overview</a>
  <a href="<?= site_url('doctor/records') ?>">Patient Records</a>
  <a href="<?= site_url('doctor/patients') ?>" class="active" aria-current="page">Patients</a>
  <a href="<?= site_url('doctor/prescriptions') ?>">Prescriptions</a>
  <a href="<?= site_url('doctor/lab-results') ?>">Lab Results</a>
  <a href="<?= site_url('doctor/patients/new') ?>">Register Patient</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Active Patients</h2>
        <a class="btn" href="<?= site_url('doctor/patients/new') ?>" style="text-decoration:none">New</a>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Code</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Name</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">DOB</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Gender</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Phone</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($patients)) : foreach ($patients as $p) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['patient_id'] ?? ('P-' . $p['id'])) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(($p['last_name'] ?? '') . ', ' . ($p['first_name'] ?? '')) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(!empty($p['date_of_birth']) ? date('M j, Y', strtotime($p['date_of_birth'])) : '-') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucfirst($p['gender'] ?? '')) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['phone'] ?? '-') ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="5" style="padding:10px;color:#6b7280;text-align:center">No patients found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
</body></html>


