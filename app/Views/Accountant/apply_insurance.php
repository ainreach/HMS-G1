<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Apply Insurance - Consolidated Billing</title>
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
        <h1 style="font-size:1.25rem;margin:0">Apply Insurance</h1>
        <small>Apply insurance coverage to patient's bill</small>
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
        <h2 style="margin:0;font-size:1.1rem">Apply Insurance Coverage</h2>
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
            <span style="color:#6b7280">Current Balance:</span>
            <strong style="color:#ef4444">$<?= number_format((float)($bill['balance'] ?? 0), 2) ?></strong>
          </div>
          <?php if (!empty($bill['insurance_claim_number'])): ?>
          <div style="display:flex;justify-content:space-between;margin-top:8px;padding-top:8px;border-top:1px solid #e5e7eb">
            <span style="color:#6b7280">Current Insurance Claim:</span>
            <strong><?= esc($bill['insurance_claim_number']) ?></strong>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('accountant/apply-insurance/' . $patient['id']) ?>" style="display:grid;gap:16px">
          <div>
            <label>Insurance Claim Number
              <input type="text" name="insurance_claim_number" class="form-control" 
                     value="<?= esc($bill['insurance_claim_number'] ?? '') ?>" 
                     placeholder="Enter insurance claim number">
            </label>
          </div>
          <div>
            <label>Insurance Coverage Amount ($)
              <input type="number" name="insurance_amount" class="form-control" step="0.01" min="0" 
                     placeholder="0.00" 
                     title="This amount will be applied as a discount to the bill">
              <small style="color:#6b7280;font-size:0.875rem">Enter the amount covered by insurance. This will be deducted from the total bill.</small>
            </label>
          </div>
          <div>
            <label>Notes
              <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about insurance coverage"><?= esc($bill['notes'] ?? '') ?></textarea>
            </label>
          </div>
          <div style="display:flex;gap:12px">
            <button type="submit" class="btn" style="background:#8b5cf6;color:white;padding:10px 20px;border-radius:6px;border:none;cursor:pointer">
              <i class="fa-solid fa-shield-halved"></i> Apply Insurance
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

