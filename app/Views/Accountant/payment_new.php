<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Record New Payment - Hospital Billing System</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('accountant/payments') ?>" class="menu-btn" aria-label="Back to Payments"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Record New Payment</h1>
        <small>Add a new payment transaction</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role"><i class="fa-regular fa-user"></i>
        <?= esc(session('username') ?? session('role') ?? 'User') ?>
      </span>
      <a href="<?= site_url('logout') ?>" class="logout-btn">Logout</a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Accountant navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/accountant') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
      <a href="<?= site_url('accountant/billing') ?>"><i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px"></i>Billing & Payments</a>
      <a href="<?= site_url('accountant/insurance') ?>"><i class="fa-solid fa-shield-halved" style="margin-right:8px"></i>Insurance</a>
      <a href="<?= site_url('accountant/reports') ?>"><i class="fa-solid fa-chart-line" style="margin-right:8px"></i>Financial Reports</a>
    </nav>
  </aside>

  <main class="content">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Payment Details</h2>
      </div>
      <div class="panel-body">
        <form action="<?= site_url('accountant/payments') ?>" method="post" style="max-width:600px">
          <?= csrf_field() ?>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label for="patient_id" style="display:block;margin-bottom:4px;font-weight:500;color:#374151">Patient *</label>
              <select name="patient_id" id="patient_id" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px">
                <option value="">Select Patient</option>
                <?php if (!empty($patients)): ?>
                  <?php foreach ($patients as $patient): ?>
                    <option value="<?= esc($patient['id']) ?>">
                      <?= esc($patient['patient_id'] . ' - ' . $patient['last_name'] . ', ' . $patient['first_name']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            
            <div>
              <label for="invoice_no" style="display:block;margin-bottom:4px;font-weight:500;color:#374151">Invoice #</label>
              <input type="text" name="invoice_no" id="invoice_no" placeholder="Optional" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px">
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label for="amount" style="display:block;margin-bottom:4px;font-weight:500;color:#374151">Amount *</label>
              <input type="number" name="amount" id="amount" step="0.01" min="0" required placeholder="0.00" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px">
            </div>
            
            <div>
              <label for="payment_date" style="display:block;margin-bottom:4px;font-weight:500;color:#374151">Payment Date *</label>
              <input type="date" name="payment_date" id="payment_date" value="<?= date('Y-m-d') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px">
            </div>
          </div>

          <div style="display:flex;gap:8px;justify-content:flex-end">
            <a href="<?= site_url('accountant/payments') ?>" class="btn" style="background:#6b7280;color:white;text-decoration:none;padding:10px 16px;border-radius:6px">
              <i class="fa-solid fa-times"></i> Cancel
            </a>
            <button type="submit" class="btn" style="background:#10b981;color:white;padding:10px 16px;border-radius:6px;border:none;cursor:pointer">
              <i class="fa-solid fa-save"></i> Record Payment
            </button>
          </div>
        </form>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Recent Payments</h2>
      </div>
      <div class="panel-body">
        <div style="text-align:center;color:#6b7280;padding:20px">
          <i class="fa-solid fa-clock-rotate-left" style="font-size:2rem;margin-bottom:8px"></i>
          <p>View recent payment history after recording this payment</p>
          <a href="<?= site_url('accountant/payments') ?>" class="btn" style="background:#0ea5e9;color:white;text-decoration:none;padding:8px 12px;border-radius:6px;font-size:0.875rem;margin-top:8px;display:inline-block">
            <i class="fa-solid fa-list"></i> View All Payments
          </a>
        </div>
      </div>
    </section>

  </main>
</div>
</body>
</html>
