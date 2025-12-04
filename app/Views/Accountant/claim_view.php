<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Insurance Claim Details</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .overlay-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.65);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 50;
    }
    .floating-card {
      background: #ffffff;
      border-radius: 12px;
      max-width: 520px;
      width: 100%;
      box-shadow: 0 20px 40px rgba(15, 23, 42, 0.25);
      overflow: hidden;
    }
    .floating-card-header {
      padding: 14px 18px;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: linear-gradient(to right, #0ea5e9, #0369a1);
      color: #ffffff;
    }
    .floating-card-body {
      padding: 18px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px 16px;
      font-size: 0.9rem;
    }
    .floating-card-footer {
      padding: 12px 18px;
      border-top: 1px solid #e5e7eb;
      display: flex;
      justify-content: flex-end;
      gap: 8px;
      background: #f9fafb;
    }
    .field-label {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: .04em;
      color: #6b7280;
      margin-bottom: 2px;
    }
    .field-value {
      font-weight: 500;
      color: #111827;
      white-space: nowrap;
    }
    .floating-card .badge {
      display: inline-flex;
      align-items: center;
      padding: 2px 8px;
      white-space: nowrap;
    }
    @media (max-width: 640px) {
      .floating-card-body {
        grid-template-columns: 1fr;
      }
      .field-value {
        white-space: normal;
      }
    }
  </style>
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
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Claim Details</h2></div>
      <div class="panel-body" style="position:relative;min-height:240px;">
        <div class="overlay-backdrop" role="dialog" aria-modal="true">
          <div class="floating-card">
            <div class="floating-card-header">
              <div>
                <div style="font-size:0.8rem;opacity:.9;">Claim #<?= esc($claim['claim_no'] ?? '') ?></div>
                <div style="font-size:1rem;font-weight:600;">Insurance Claim Details</div>
              </div>
              <a href="<?= site_url('accountant/insurance') ?>" aria-label="Close" style="color:#e5e7eb;text-decoration:none;font-size:1.1rem;">
                <i class="fa-solid fa-xmark"></i>
              </a>
            </div>
            <div class="floating-card-body">
              <div>
                <div class="field-label">Invoice #</div>
                <div class="field-value"><?= esc($claim['invoice_no'] ?? '—') ?></div>
              </div>
              <div>
                <div class="field-label">Patient</div>
                <div class="field-value"><?= esc($claim['patient_name'] ?? '—') ?></div>
              </div>
              <div>
                <div class="field-label">Provider</div>
                <div class="field-value"><?= esc($claim['provider'] ?? '—') ?></div>
              </div>
              <div>
                <div class="field-label">Policy #</div>
                <div class="field-value"><?= esc($claim['policy_no'] ?? '—') ?></div>
              </div>
              <div>
                <div class="field-label">Amount Claimed</div>
                <div class="field-value">$<?= number_format((float)($claim['amount_claimed'] ?? 0), 2) ?></div>
              </div>
              <div>
                <div class="field-label">Amount Approved</div>
                <div class="field-value">$<?= number_format((float)($claim['amount_approved'] ?? 0), 2) ?></div>
              </div>
              <div>
                <div class="field-label">Status</div>
                <div class="field-value"><span class="badge"><?= esc(ucfirst($claim['status'] ?? 'submitted')) ?></span></div>
              </div>
              <div>
                <div class="field-label">Submitted At</div>
                <div class="field-value"><?= esc($claim['submitted_at'] ?? '—') ?></div>
              </div>
            </div>
            <div class="floating-card-footer">
              <a href="<?= site_url('accountant/insurance') ?>" class="btn" style="background:#e5e7eb;color:#111827;border:none;">Close</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</div>
</body>
</html>
