<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Financial Reports</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Financial Reports</h1><small>Revenue • AR • Aging</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Accountant navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/accountant') ?>">Overview</a>
  <a href="<?= site_url('accountant/billing') ?>">Billing & Payments</a>
  <a href="<?= site_url('accountant/insurance') ?>">Insurance</a>
  <a href="<?= site_url('accountant/reports') ?>" class="active" aria-current="page">Financial Reports</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Revenue (30d)</span><i class="fa-solid fa-chart-line"></i></div><div class="kpi-value">$<?= number_format((float)($revenue30d ?? 0), 2) ?></div></article>
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>AR Aging > 60d</span><i class="fa-regular fa-clock"></i></div><div class="kpi-value">$<?= number_format((float)($arOver60d ?? 0), 2) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Avg. Days to Pay</span><i class="fa-solid fa-hourglass-half"></i></div><div class="kpi-value"><?= esc($avgDaysToPay ?? 0) ?></div></article>
    </section>

    <!-- Removed demo 'Revenue by Department' section. Replace later when department attribution is available. -->

    <section class="panel" style="margin-top:16px">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Statements</h2>
        <a class="btn" href="<?= site_url('accountant/statements/export') ?>" style="text-decoration:none"><i class="fa-regular fa-file-csv"></i> Export CSV</a>
      </div>
      <div class="panel-body">
        <p style="margin:0;color:#6b7280">Export all payments as CSV or view detailed statements on the Statements page.</p>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">AR Aging Summary</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Bucket</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Balance</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">0-30 days</td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)($aging['0-30'] ?? 0), 2) ?></td>
            </tr>
            <tr>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">31-60 days</td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)($aging['31-60'] ?? 0), 2) ?></td>
            </tr>
            <tr>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">61-90 days</td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)($aging['61-90'] ?? 0), 2) ?></td>
            </tr>
            <tr>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">> 90 days</td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)($aging['>90'] ?? 0), 2) ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
</body>
</html>
