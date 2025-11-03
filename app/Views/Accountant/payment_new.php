<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Record Payment</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Record Payment</h1><small>Invoices • Receipts</small></div>
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
  <a href="<?= site_url('accountant/reports') ?>">Financial Reports</a>
</nav></aside>
  <main class="content">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Payment Details</h2></div>
      <div class="panel-body">
        <form method="post" action="<?= site_url('accountant/payments') ?>" style="display:grid;gap:10px;max-width:560px">
          <label>Patient Name<input required name="patient_name" class="input" placeholder="e.g. Peter Que"></label>
          <label>Invoice # (optional)<input name="invoice_no" class="input" placeholder="e.g. INV-1001"></label>
          <label>Amount ($)<input required name="amount" type="number" step="0.01" class="input" placeholder="0.00"></label>
          <label>Paid At<input name="paid_at" type="datetime-local" class="input"></label>
          <button class="btn" type="submit" style="width:max-content"><i class="fa-solid fa-sack-dollar"></i> Save Payment</button>
          <a class="btn" href="<?= site_url('accountant/billing') ?>" style="width:max-content;text-decoration:none">Back</a>
        </form>
      </div>
    </section>
  </main>
</div>
</body>
</html>
