<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Insurance Claims</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Insurance Claims</h1><small>Submit • Track • Reconcile</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Accountant navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/accountant') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
  <a href="<?= site_url('accountant/billing') ?>"><i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px"></i>Billing & Payments</a>
  <a href="<?= site_url('accountant/insurance') ?>" class="active" aria-current="page"><i class="fa-solid fa-shield-halved" style="margin-right:8px"></i>Insurance</a>
  <a href="<?= site_url('accountant/reports') ?>"><i class="fa-solid fa-chart-line" style="margin-right:8px"></i>Financial Reports</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="<?= site_url('accountant/claims/new') ?>" style="background:#0ea5e9;color:white;padding:10px 14px;border-radius:6px;text-decoration:none;font-size:0.875rem;display:inline-block"><i class="fa-solid fa-plus"></i> New Claim</a>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Claims</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Claim #</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Invoice #</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Provider</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Claimed</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Approved</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Submitted</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($claims)) : ?>
            <?php foreach ($claims as $c) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?= esc($c['claim_no'] ?? '') ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($c['invoice_no'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($c['patient_name'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($c['provider'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)($c['amount_claimed'] ?? 0), 2) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)($c['amount_approved'] ?? 0), 2) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><span class="badge"><?= esc(ucfirst($c['status'] ?? 'submitted')) ?></span></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($c['submitted_at'] ?? '') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="8" style="padding:12px;text-align:center;color:#6b7280">No claims found.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
</body>
</html>
