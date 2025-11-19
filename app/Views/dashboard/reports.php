<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reports</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
  <main class="content" style="max-width:900px;margin:20px auto;padding:16px">
    <h1 style="margin:0 0 12px">Reports</h1>
    <?php $k = $kpi ?? []; ?>
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Total Patients</span></div><div class="kpi-value"><?= (int)($k['total_patients'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Appointments Today</span></div><div class="kpi-value"><?= (int)($k['appointments_today'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Lab Completed</span></div><div class="kpi-value"><?= (int)($k['lab_completed'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>Lab Pending</span></div><div class="kpi-value"><?= (int)($k['lab_pending'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Total Users</span></div><div class="kpi-value"><?= (int)($k['total_users'] ?? 0) ?></div></article>
    </section>
    <div style="margin-top:12px">
      <a class="btn" href="<?= site_url('admin/reports') ?>">Go to Admin Role Reports</a>
    </div>
  </main>
</body></html>


