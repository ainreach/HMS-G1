<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patient Records</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem">Patient Records</h1>
</div></header>
<div class="layout"><aside class="simple-sidebar"><nav class="side-nav">
  <a href="<?= site_url('dashboard/doctor') ?>">Overview</a>
  <a href="<?= site_url('doctor/records') ?>" class="active" aria-current="page">Patient Records</a>
  <a href="<?= site_url('doctor/patients') ?>">Patients</a>
  <a href="<?= site_url('doctor/prescriptions') ?>">Prescriptions</a>
  <a href="<?= site_url('doctor/lab-results') ?>">Lab Results</a>
  <a href="<?= site_url('doctor/records/new') ?>">New Record</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Records</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Record #</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Visit Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Diagnosis</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($records)) : foreach ($records as $rec) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <a href="<?= site_url('doctor/records/' . (int)$rec['id']) ?>" style="text-decoration:none;font-weight:600;"><?= esc($rec['record_number'] ?? ('#' . $rec['id'])) ?></a>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(($rec['first_name'] ?? '') . ' ' . ($rec['last_name'] ?? '')) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(isset($rec['visit_date']) ? date('M j, Y H:i', strtotime($rec['visit_date'])) : '-') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($rec['diagnosis'] ?: '—') ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="4" style="padding:10px;color:#6b7280;text-align:center">No records found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
</body></html>


