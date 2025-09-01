<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Reports</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
  <main class="content" style="max-width:900px;margin:20px auto;padding:16px">
    <h1 style="margin:0 0 12px">User Reports</h1>
    <?php $c = $counts ?? []; ?>
    <div class="kpi-grid">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Total</span></div><div class="kpi-value"><?= (int)($c['total'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Admins</span></div><div class="kpi-value"><?= (int)($c['admins'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Doctors</span></div><div class="kpi-value"><?= (int)($c['doctors'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Nurses</span></div><div class="kpi-value"><?= (int)($c['nurses'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Receptionists</span></div><div class="kpi-value"><?= (int)($c['receptionists'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Pharmacists</span></div><div class="kpi-value"><?= (int)($c['pharmacists'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Lab Staff</span></div><div class="kpi-value"><?= (int)($c['lab_staff'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Accountants</span></div><div class="kpi-value"><?= (int)($c['accountants'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>IT Staff</span></div><div class="kpi-value"><?= (int)($c['it_staff'] ?? 0) ?></div></article>
    </div>
    <div style="margin-top:12px">
      <a class="btn" href="<?= site_url('dashboard/admin') ?>">Back</a>
    </div>
  </main>
</body></html>
