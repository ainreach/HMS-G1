<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accountant Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Accountant</h1><small>Billing • Payments • Insurance claims</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Accountant navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/accountant') ?>" class="active" aria-current="page">Overview</a>
  <a href="<?= site_url('accountant/billing') ?>" data-feature="billing">Billing & Payments</a>
  <a href="<?= site_url('accountant/insurance') ?>" data-feature="insurance">Insurance</a>
  <a href="<?= site_url('accountant/reports') ?>" data-feature="reports">Financial Reports</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Invoices Today</span><i class="fa-solid fa-file-invoice" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($invoicesToday ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Payments</span><i class="fa-solid fa-sack-dollar" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite">$<?= number_format((float)($paymentsToday ?? 0), 2) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('accountant/invoices/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Create Invoice</a>
        <a class="btn" href="<?= site_url('accountant/payments/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Record Payment</a>
        <a class="btn" href="<?= site_url('accountant/statements') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Download Statement</a>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Transactions</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Type</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Amount</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
            <tbody>
          <?php if (!empty($recent)) : ?>
            <?php foreach ($recent as $row) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('Y-m-d', strtotime($row['date']))) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['type']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['patient']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)$row['amount'], 2) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['status']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="5" style="padding:12px;text-align:center;color:#6b7280">No recent data.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
