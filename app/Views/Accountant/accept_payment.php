<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accept Payment - Consolidated Billing</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('accountant/consolidated-bill/' . $patient['id']) ?>" class="menu-btn" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Accept Payment</h1>
        <small>Record payment for patient's consolidated bill</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
      <a href="<?= site_url('logout') ?>" class="logout-btn">Logout</a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/accountant') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
      <a href="<?= site_url('accountant/consolidated-bills') ?>" class="active"><i class="fa-solid fa-file-invoice" style="margin-right:8px"></i>Consolidated Bills</a>
      <a href="<?= site_url('accountant/billing') ?>"><i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px"></i>Billing & Payments</a>
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
        <h2 style="margin:0;font-size:1.1rem">Record Payment</h2>
        <small style="color:#6b7280">Patient: <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?> (<?= esc($patient['patient_id'] ?? 'N/A') ?>)</small>
      </div>
      <div class="panel-body">
        <?php if ($bill): ?>
        <div style="background:#f8fafc;padding:16px;border-radius:8px;margin-bottom:16px">
          <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="color:#6b7280">Total Bill Amount:</span>
            <strong>$<?= number_format((float)($bill['total_amount'] ?? 0), 2) ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="color:#6b7280">Amount Paid:</span>
            <strong style="color:#10b981">$<?= number_format((float)($bill['paid_amount'] ?? 0), 2) ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;margin-top:8px;padding-top:8px;border-top:2px solid #e5e7eb">
            <span style="color:#6b7280;font-weight:600">Remaining Balance:</span>
            <strong style="color:#ef4444;font-size:1.1rem">$<?= number_format((float)($bill['balance'] ?? 0), 2) ?></strong>
          </div>
        </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('accountant/accept-payment/' . $patient['id']) ?>" style="display:grid;gap:16px">
          <div class="form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div>
              <label>Payment Amount ($) <span class="text-danger">*</span>
                <input type="number" name="amount" class="form-control" step="0.01" min="0.01" 
                       max="<?= $bill ? (float)($bill['balance'] ?? 0) : 999999 ?>" 
                       placeholder="0.00" required>
                <small style="color:#6b7280;font-size:0.875rem">Maximum: $<?= number_format((float)($bill['balance'] ?? 0), 2) ?></small>
              </label>
            </div>
            <div>
              <label>Payment Date <span class="text-danger">*</span>
                <input type="datetime-local" name="payment_date" class="form-control" 
                       value="<?= date('Y-m-d\TH:i') ?>" required>
              </label>
            </div>
            <div>
              <label>Payment Method <span class="text-danger">*</span>
                <select name="payment_method" class="form-control" required>
                  <option value="cash" selected>Cash</option>
                  <option value="card">Card</option>
                  <option value="cheque">Cheque</option>
                  <option value="bank_transfer">Bank Transfer</option>
                  <option value="insurance">Insurance</option>
                  <option value="other">Other</option>
                </select>
              </label>
            </div>
            <div>
              <label>Transaction ID / Reference
                <input type="text" name="transaction_id" class="form-control" 
                       placeholder="Optional transaction reference">
              </label>
            </div>
          </div>
          <div>
            <label>Notes
              <textarea name="notes" class="form-control" rows="3" placeholder="Additional payment notes"></textarea>
            </label>
          </div>
          <div style="display:flex;gap:12px">
            <button type="submit" class="btn" style="background:#10b981;color:white;padding:10px 20px;border-radius:6px;border:none;cursor:pointer">
              <i class="fa-solid fa-money-bill-wave"></i> Record Payment
            </button>
            <a href="<?= site_url('accountant/consolidated-bill/' . $patient['id']) ?>" class="btn" style="background:#6b7280;color:white;text-decoration:none;padding:10px 20px;border-radius:6px">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>
</body>
</html>

