<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Invoice</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-grip"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Create Invoice</h1><small>New billing</small></div>
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
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Invoice Details</h2></div>
      <div class="panel-body">
        <form method="post" action="<?= site_url('accountant/invoices') ?>">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <label>Invoice #<input name="invoice_no" type="text" placeholder="e.g. INV-1001" style="width:100%" required></label>
            <label>Invoice Date<input name="issued_at" type="date" style="width:100%" required></label>
            <label>Patient Name<input name="patient_name" type="text" placeholder="e.g. Peter Que" style="width:100%" required></label>
            <label>Amount ($)<input name="amount" type="number" step="0.01" placeholder="0.00" style="width:100%" required></label>
            <label>Status
              <select name="status" style="width:100%">
                <option value="unpaid">Unpaid</option>
                <option value="paid">Paid</option>
              </select>
            </label>
          </div>
          <div style="margin-top:12px">
            <button type="submit" class="btn" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px"><i class="fa-regular fa-floppy-disk"></i> Save Invoice</button>
            <a class="btn" href="<?= site_url('accountant/billing') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Back</a>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>
</body>
</html>
