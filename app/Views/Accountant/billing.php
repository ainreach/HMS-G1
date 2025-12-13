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
  <a href="<?= site_url('dashboard/accountant') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
  <a href="<?= site_url('accountant/billing') ?>" class="active" aria-current="page"><i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px"></i>Billing & Payments</a>
  <a href="<?= site_url('accountant/consolidated-bills') ?>"><i class="fa-solid fa-file-invoice" style="margin-right:8px"></i>Consolidated Bills</a>
  <a href="<?= site_url('accountant/pending-charges') ?>"><i class="fa-solid fa-dollar-sign" style="margin-right:8px"></i>Pending Charges</a>
  <a href="<?= site_url('accountant/lab-test-approvals') ?>"><i class="fa-solid fa-vial" style="margin-right:8px"></i>Lab Test Approvals</a>
  <a href="<?= site_url('accountant/patients/bills') ?>"><i class="fa-solid fa-bed" style="margin-right:8px"></i>Patient Room Bills</a>
  <a href="<?= site_url('accountant/insurance') ?>"><i class="fa-solid fa-shield-halved" style="margin-right:8px"></i>Insurance</a>
  <a href="<?= site_url('accountant/reports') ?>"><i class="fa-solid fa-chart-line" style="margin-right:8px"></i>Financial Reports</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <?php
          // Get pending charges count for badge
          $billingModel = new \App\Models\BillingModel();
          $pendingCount = $billingModel->where('payment_status', 'pending')->countAllResults();
        ?>
        <a href="<?= site_url('accountant/consolidated-bills') ?>" style="background:#0ea5e9;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-size:0.875rem;display:inline-flex;align-items:center;gap:8px;font-weight:600">
          <i class="fa-solid fa-file-invoice"></i> Consolidated Bills
        </a>
        <a href="<?= site_url('accountant/pending-charges') ?>" style="background:#dc2626;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-size:0.875rem;display:inline-flex;align-items:center;gap:8px;position:relative;font-weight:600">
          <i class="fa-solid fa-file-invoice-dollar"></i> Pending Charges
          <?php if ($pendingCount > 0): ?>
            <span style="background:rgba(255,255,255,0.3);border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;margin-left:4px"><?= $pendingCount ?></span>
          <?php endif; ?>
        </a>
        <?php
          // Get pending lab test approvals count
          try {
            $labTestModel = model('App\\Models\\LabTestModel');
            $db = \Config\Database::connect();
            $columns = $db->getFieldNames('lab_tests');
            if (in_array('accountant_approved', $columns)) {
              $pendingLabApprovals = $labTestModel->where('accountant_approved', 0)->where('status !=', 'cancelled')->countAllResults();
            } else {
              $pendingLabApprovals = 0;
            }
          } catch (\Exception $e) {
            $pendingLabApprovals = 0;
          }
        ?>
        <a href="<?= site_url('accountant/lab-test-approvals') ?>" style="background:#0891b2;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-size:0.875rem;display:inline-flex;align-items:center;gap:8px;font-weight:600">
          <i class="fa-solid fa-vial"></i> Lab Test Approvals
          <?php if ($pendingLabApprovals > 0): ?>
            <span style="background:rgba(255,255,255,0.3);border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;margin-left:4px"><?= $pendingLabApprovals ?></span>
          <?php endif; ?>
        </a>
        <a href="<?= site_url('accountant/reports') ?>" style="background:#15803d;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-size:0.875rem;display:inline-flex;align-items:center;gap:8px;font-weight:600">
          <i class="fa-solid fa-chart-line"></i> Finance Overview
        </a>
        <a href="<?= site_url('accountant/payments') ?>" style="background:#2563eb;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-size:0.875rem;display:inline-flex;align-items:center;gap:8px;font-weight:600">
          <i class="fa-solid fa-money-bills"></i> Payment Reports
        </a>
        <a href="<?= site_url('accountant/medication-billing') ?>" style="background:#0891b2;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-size:0.875rem;display:inline-flex;align-items:center;gap:8px;font-weight:600">
          <i class="fa-solid fa-pills"></i> Medication Billing
        </a>
        <a href="<?= site_url('accountant/expenses') ?>" style="background:#f97316;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-size:0.875rem;display:inline-flex;align-items:center;gap:8px;font-weight:600">
          <i class="fa-solid fa-receipt"></i> Expense Tracking
        </a>
        <a href="<?= site_url('accountant/patients/bills') ?>" style="background:#8b5cf6;color:white;padding:10px 14px;border-radius:8px;text-decoration:none;font-size:0.875rem;display:inline-flex;align-items:center;gap:8px;font-weight:600">
          <i class="fa-solid fa-bed"></i> Patient Room Bills
        </a>
      </div>
    </section>

    <section class="kpi-grid" style="margin-top:16px" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Open Invoices</span><i class="fa-solid fa-file-invoice"></i></div><div class="kpi-value"><?= esc($openInvoicesCount ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Payments Today</span><i class="fa-solid fa-money-bill-wave"></i></div><div class="kpi-value">$<?= number_format((float)($paymentsToday ?? 0), 2) ?></div></article>
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>Invoices Today</span><i class="fa-solid fa-file-lines"></i></div><div class="kpi-value"><?= esc($invoicesToday ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Outstanding</span><i class="fa-solid fa-wallet"></i></div><div class="kpi-value">$<?= number_format((float)($outstandingBalance ?? 0), 2) ?></div></article>
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
              <?php
                $rawStatus = strtolower(trim($i['status'] ?? ''));
                $status = $rawStatus !== '' ? $rawStatus : 'unpaid';
                $statusLabel = ucfirst($status);
                $statusClass = $status === 'paid' ? 'badge-success' : ($status === 'partial' ? 'badge-warning' : 'badge-warning');
              ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($i['invoice_no'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($i['patient_name'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)($i['amount'] ?? 0), 2) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><span class="badge <?= $statusClass ?>"><?= esc($statusLabel) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="4" style="padding:12px;text-align:center;color:#6b7280">No invoices.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Payments</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Amount</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($payments)) : ?>
            <?php foreach ($payments as $p) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('M d', strtotime($p['paid_at']))) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['patient_name'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)($p['amount'] ?? 0), 2) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="3" style="padding:12px;text-align:center;color:#6b7280">No payments.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Patients</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient ID</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Name</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($recentPatients)) : ?>
            <?php foreach ($recentPatients as $patient) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($patient['patient_id']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <a href="<?= site_url('accountant/consolidated-bill/' . $patient['id']) ?>" class="btn-small" style="background:#0ea5e9;color:white;padding:4px 8px;border-radius:4px;text-decoration:none;font-size:0.75rem" title="View all charges for this patient">
                    <i class="fa-solid fa-file-invoice"></i> View All Charges
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="3" style="padding:12px;text-align:center;color:#6b7280">No recent patients.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>
  </main>
</div>
</body>
</html>
