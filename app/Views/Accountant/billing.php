<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Billing & Payments</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Billing & Payments</h1><small>Invoices • Payments • Statements</small></div>
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
  <a href="<?= site_url('accountant/billing') ?>" class="active" aria-current="page">Billing & Payments</a>
  <a href="<?= site_url('accountant/insurance') ?>">Insurance</a>
  <a href="<?= site_url('accountant/reports') ?>">Financial Reports</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('accountant/invoices/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none"><i class="fa-regular fa-file-lines"></i> Create Invoice</a>
        <a class="btn" href="<?= site_url('accountant/payments/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none"><i class="fa-solid fa-sack-dollar"></i> Record Payment</a>
        <a class="btn" href="<?= site_url('accountant/statements') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none"><i class="fa-regular fa-file-pdf"></i> Download Statement</a>
        <a class="btn" href="<?= site_url('accountant/invoices/export') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none"><i class="fa-solid fa-file-csv"></i> Invoices CSV</a>
        <a class="btn" href="<?= site_url('accountant/finance/export/zip') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none"><i class="fa-solid fa-file-zipper"></i> Finance ZIP</a>
      </div>
    </section>

    <section class="kpi-grid" style="margin-top:16px" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Open Invoices</span><i class="fa-regular fa-file-invoice"></i></div><div class="kpi-value"><?= esc($openInvoicesCount ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Payments Today</span><i class="fa-solid fa-sack-dollar"></i></div><div class="kpi-value">$<?= number_format((float)($paymentsToday ?? 0), 2) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>AR Balance</span><i class="fa-solid fa-scale-balanced"></i></div><div class="kpi-value">$<?= number_format((float)($arBalance ?? 0), 2) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Invoices</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Invoice #</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Amount</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($invoices)) : ?>
            <?php foreach ($invoices as $i) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($i['invoice_no'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($i['patient_name'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)($i['amount'] ?? 0), 2) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><span class="badge <?= (strtolower((string)($i['status'] ?? '')) === 'paid' ? 'badge-success' : 'badge-warning') ?>"><?= esc(ucfirst($i['status'] ?? 'unpaid')) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="4" style="padding:12px;text-align:center;color:#6b7280">No invoices.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Payments</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Invoice</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Amount</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($payments)) : ?>
            <?php foreach ($payments as $p) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['paid_at']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['patient_name'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['invoice_no'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)($p['amount'] ?? 0), 2) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="4" style="padding:12px;text-align:center;color:#6b7280">No payments.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
</body>
</html>
