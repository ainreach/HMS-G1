<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Payments - Hospital Billing System</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('accountant/billing') ?>" class="menu-btn" aria-label="Back to Billing"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Payment History</h1>
        <small>All recorded payments and receipts</small>
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
        <h2 style="margin:0;font-size:1.1rem">Payment Records</h2>
        <div style="display:flex;gap:8px;align-items:center">
          <a href="<?= site_url('accountant/payments/new') ?>" class="btn" style="background:#10b981;color:white;text-decoration:none;padding:8px 12px;border-radius:6px;font-size:0.875rem">
            <i class="fa-solid fa-plus"></i> Record Payment
          </a>
          <a href="<?= site_url('accountant/statements') ?>" class="btn" style="background:#6b7280;color:white;text-decoration:none;padding:8px 12px;border-radius:6px;font-size:0.875rem">
            <i class="fa-solid fa-download"></i> Export
          </a>
        </div>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Invoice #</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Amount</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($payments)) : ?>
            <?php foreach ($payments as $payment) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($payment['paid_at']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($payment['patient_name'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?php if (!empty($payment['invoice_no'])): ?>
                    <span style="color:#0ea5e9;font-weight:500"><?= esc($payment['invoice_no']) ?></span>
                  <?php else: ?>
                    <span style="color:#6b7280">-</span>
                  <?php endif; ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <span style="color:#10b981;font-weight:600">$<?= number_format((float)($payment['amount'] ?? 0), 2) ?></span>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <div style="display:flex;gap:4px">
                    <a href="<?= site_url('accountant/payments/' . $payment['id']) ?>" class="btn-small" style="background:#3b82f6;color:white;padding:4px 8px;border-radius:4px;text-decoration:none;font-size:0.75rem">
                      <i class="fa-solid fa-eye"></i> View
                    </a>
                    <a href="<?= site_url('accountant/payments/edit/' . $payment['id']) ?>" class="btn-small" style="background:#f59e0b;color:white;padding:4px 8px;border-radius:4px;text-decoration:none;font-size:0.75rem">
                      <i class="fa-solid fa-edit"></i> Edit
                    </a>
                    <form method="post" action="<?= site_url('accountant/payments/delete/' . $payment['id']) ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn-small" style="background:#ef4444;color:white;padding:4px 8px;border-radius:4px;border:none;font-size:0.75rem;cursor:pointer">
                        <i class="fa-solid fa-trash"></i> Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" style="padding:12px;text-align:center;color:#6b7280">
                <i class="fa-solid fa-inbox" style="margin-right:8px"></i>No payments recorded yet.
              </td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <?php if (!empty($payments)): ?>
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Payment Summary</h2></div>
      <div class="panel-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #10b981">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Total Payments</div>
            <div style="font-size:1.5rem;font-weight:600;color:#10b981">
              $<?= number_format(array_sum(array_map(fn($p) => (float)($p['amount'] ?? 0), $payments)), 2) ?>
            </div>
          </div>
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #0ea5e9">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Payment Count</div>
            <div style="font-size:1.5rem;font-weight:600;color:#0ea5e9"><?= count($payments) ?></div>
          </div>
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #8b5cf6">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Average Amount</div>
            <div style="font-size:1.5rem;font-weight:600;color:#8b5cf6">
              $<?= number_format(array_sum(array_map(fn($p) => (float)($p['amount'] ?? 0), $payments)) / max(1, count($payments)), 2) ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php endif; ?>

  </main>
</div>
</body>
</html>
