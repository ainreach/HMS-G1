<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nurse Dashboard</title>
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
  <a href="<?= site_url('dashboard/nurse') ?>" class="active" aria-current="page">Overview</a>
  <a href="<?= site_url('records') ?>" data-feature="ehr">Patient Records</a>
  <a href="<?= site_url('scheduling') ?>" data-feature="scheduling">Scheduling</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Active Patients</span><i class="fa-solid fa-bed-pulse" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($activePatients ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Pending Tasks</span><i class="fa-solid fa-list-check" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($pendingTasks ?? 0) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('nurse/vitals/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Record Vitals</a>
        <a class="btn" href="<?= site_url('nurse/notes/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Update Care Notes</a>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Ward Tasks</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Task</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Due</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($tasks)) : foreach ($tasks as $t) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($t['patient']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($t['task']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('m/d H:i', strtotime(($t['due_date'] ?? date('Y-m-d')).' '.($t['due_time'] ?? '00:00')))) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($t['status']) ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="4" style="padding:10px;color:#6b7280;text-align:center">No upcoming ward tasks.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
