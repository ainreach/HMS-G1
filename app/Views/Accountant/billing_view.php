<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice Details - Accountant</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Billing & Payments</h1>
        <small>Invoice Details</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
      <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Accountant navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/accountant') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
      <a href="<?= site_url('accountant/billing') ?>" class="active" aria-current="page"><i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px"></i>Billing & Payments</a>
      <a href="<?= site_url('accountant/consolidated-bills') ?>"><i class="fa-solid fa-file-invoice" style="margin-right:8px"></i>Consolidated Bills</a>
      <a href="<?= site_url('accountant/pending-charges') ?>"><i class="fa-solid fa-dollar-sign" style="margin-right:8px"></i>Pending Charges</a>
      <a href="<?= site_url('accountant/lab-test-approvals') ?>"><i class="fa-solid fa-vial" style="margin-right:8px"></i>Lab Test Approvals</a>
      <a href="<?= site_url('accountant/patients/bills') ?>"><i class="fa-solid fa-bed" style="margin-right:8px"></i>Patient Room Bills</a>
      <a href="<?= site_url('accountant/insurance') ?>"><i class="fa-solid fa-shield-halved" style="margin-right:8px"></i>Insurance</a>
      <a href="<?= site_url('accountant/reports') ?>"><i class="fa-solid fa-chart-line" style="margin-right:8px"></i>Financial Reports</a>
    </nav>
  </aside>

  <main class="content" style="padding:16px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
      <div>
        <h2 style="margin:0;font-size:1.25rem;font-weight:600">
          <?= isset($isConsolidated) && $isConsolidated ? 'Consolidated Patient Bill' : 'Invoice Details' ?>
        </h2>
        <small style="color:#6b7280">
          Invoice Number: <?= esc($billing['invoice_number'] ?? '') ?>
          <?php if (isset($isConsolidated) && $isConsolidated): ?>
            <span style="background:#0ea5e9;color:white;padding:2px 8px;border-radius:12px;font-size:0.75rem;margin-left:8px">Consolidated</span>
          <?php endif; ?>
        </small>
      </div>
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <?php 
          $userRole = session('role');
          $isAdmin = ($userRole === 'admin');
          $backUrl = $isAdmin ? site_url('admin/patients') : site_url('accountant/consolidated-bills');
          $patientId = $patient['id'] ?? null;
        ?>
        <a href="<?= $backUrl ?>" style="padding:8px 16px;background:#6b7280;color:white;text-decoration:none;border-radius:6px;font-size:0.875rem;display:inline-flex;align-items:center;gap:6px">
          <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <?php if (isset($isConsolidated) && $isConsolidated && $patientId): ?>
        <a href="<?= site_url('accountant/add-charge/' . $patientId) ?>" style="padding:8px 16px;background:#0ea5e9;color:white;text-decoration:none;border-radius:6px;font-size:0.875rem;display:inline-flex;align-items:center;gap:6px" title="Add/Remove Charges">
          <i class="fa-solid fa-plus"></i> Add Charge
        </a>
        <a href="<?= site_url('accountant/apply-insurance/' . $patientId) ?>" style="padding:8px 16px;background:#8b5cf6;color:white;text-decoration:none;border-radius:6px;font-size:0.875rem;display:inline-flex;align-items:center;gap:6px" title="Apply Insurance">
          <i class="fa-solid fa-shield-halved"></i> Apply Insurance
        </a>
        <?php if (strtolower($billing['payment_status'] ?? 'pending') !== 'paid'): ?>
        <a href="<?= site_url('accountant/accept-payment/' . $patientId) ?>" style="padding:8px 16px;background:#10b981;color:white;text-decoration:none;border-radius:6px;font-size:0.875rem;display:inline-flex;align-items:center;gap:6px" title="Accept Payment">
          <i class="fa-solid fa-money-bill-wave"></i> Accept Payment
        </a>
        <?php endif; ?>
        <a href="<?= site_url('accountant/print-bill/' . $patientId) ?>" target="_blank" style="padding:8px 16px;background:#3b82f6;color:white;text-decoration:none;border-radius:6px;font-size:0.875rem;display:inline-flex;align-items:center;gap:6px" title="Print/Export Invoice">
          <i class="fa-solid fa-print"></i> Print / Export
        </a>
        <?php else: ?>
        <button type="button" onclick="window.print()" style="padding:8px 16px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer;font-size:0.875rem;display:inline-flex;align-items:center;gap:6px">
          <i class="fa-solid fa-print"></i> Print Invoice
        </button>
        <?php endif; ?>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
      <!-- Patient Information -->
      <section class="panel">
        <div class="panel-head">
          <h3 style="margin:0;font-size:1rem;font-weight:600">Patient Information</h3>
        </div>
        <div class="panel-body">
          <p style="margin:0 0 8px 0"><strong>Name:</strong> <?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?></p>
          <p style="margin:0 0 8px 0"><strong>Patient Code:</strong> <?= esc($patient['patient_id'] ?? 'N/A') ?></p>
          <p style="margin:0 0 8px 0">
            <strong>Patient Type:</strong> 
            <?php 
              $isInpatient = ($patient['admission_type'] ?? '') === 'admission' && !empty($patient['assigned_room_id']);
            ?>
            <?php if ($isInpatient): ?>
              <span style="background:#3b82f6;color:white;padding:2px 8px;border-radius:12px;font-size:0.75rem;font-weight:500">
                <i class="fa-solid fa-bed"></i> Inpatient
              </span>
            <?php else: ?>
              <span style="background:#6b7280;color:white;padding:2px 8px;border-radius:12px;font-size:0.75rem;font-weight:500">
                <i class="fa-solid fa-user"></i> Outpatient
              </span>
            <?php endif; ?>
          </p>
          <p style="margin:0 0 8px 0"><strong>Date of Birth:</strong> <?= esc($patient['date_of_birth'] ?? 'N/A') ?></p>
          <p style="margin:0 0 8px 0"><strong>Gender:</strong> <?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?></p>
          <p style="margin:0 0 8px 0"><strong>Contact:</strong> <?= esc($patient['phone'] ?? 'N/A') ?></p>
          <p style="margin:0"><strong>Insurance:</strong> <?= esc($patient['insurance_provider'] ?? 'N/A') ?></p>
        </div>
      </section>

      <!-- Invoice Summary -->
      <section class="panel">
        <div class="panel-head">
          <h3 style="margin:0;font-size:1rem;font-weight:600">Invoice Summary</h3>
        </div>
        <div class="panel-body">
          <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="color:#6b7280">Invoice Number</span>
            <strong><?= esc($billing['invoice_number'] ?? '') ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="color:#6b7280">Bill Date</span>
            <span><?= esc($billing['bill_date'] ?? '') ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="color:#6b7280">Due Date</span>
            <span><?= esc($billing['due_date'] ?? '') ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:16px">
            <span style="color:#6b7280">Status</span>
            <?php $status = strtolower((string)($billing['payment_status'] ?? 'pending')); ?>
            <?php
              $badgeColor = '#6b7280';
              if ($status === 'paid') $badgeColor = '#16a34a';
              elseif ($status === 'partial') $badgeColor = '#f59e0b';
              elseif ($status === 'overdue') $badgeColor = '#ef4444';
            ?>
            <span style="padding:4px 10px;background:<?= $badgeColor ?>;color:white;border-radius:12px;font-size:0.75rem;font-weight:600">
              <?= esc(ucfirst($status)) ?>
            </span>
          </div>

          <hr style="margin:16px 0;border:none;border-top:1px solid #e5e7eb">

          <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="color:#6b7280">Total Amount</span>
            <strong>$<?= number_format((float)($billing['total_amount'] ?? 0), 2) ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="color:#6b7280">Insurance Coverage</span>
            <span style="color:#16a34a">-$<?= number_format((float)($insuranceCoverage ?? 0), 2) ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="color:#6b7280">Amount Paid</span>
            <span style="color:#3b82f6">$<?= number_format((float)($totalPaid ?? 0), 2) ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;margin-top:12px;padding-top:12px;border-top:2px solid #e5e7eb">
            <strong>Balance</strong>
            <?php $balance = (float)($billing['balance'] ?? 0); ?>
            <strong style="color:<?= $balance > 0 ? '#ef4444' : '#16a34a' ?>">
              $<?= number_format($balance, 2) ?>
            </strong>
          </div>
        </div>
      </section>
    </div>

    <!-- Services & Products -->
    <section class="panel" style="margin-bottom:16px">
      <div class="panel-head">
        <h3 style="margin:0;font-size:1rem;font-weight:600">Services & Products</h3>
      </div>
      <div class="panel-body" style="padding:0">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr style="background:#f9fafb">
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;width:15%">Type</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600">Description</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;width:15%">Date</th>
              <th style="text-align:right;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;width:15%">Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($items)): ?>
              <?php foreach ($items as $item): ?>
                <tr style="border-bottom:1px solid #f3f4f6">
                  <td style="padding:12px"><?= esc(ucfirst($item['item_type'] ?? '')) ?></td>
                  <td style="padding:12px">
                    <?= esc($item['item_name'] ?? '') ?>
                    <?php if (!empty($item['description'])): ?>
                      <span style="color:#6b7280"> - <?= esc($item['description']) ?></span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:12px;color:#6b7280;font-size:0.875rem">
                    <?= esc(date('M d, Y', strtotime($item['created_at'] ?? 'now'))) ?>
                    <br>
                    <small><?= esc(date('h:i A', strtotime($item['created_at'] ?? 'now'))) ?></small>
                  </td>
                  <td style="padding:12px;text-align:right;font-weight:600">
                    $<?= number_format((float)($item['total_price'] ?? 0), 2) ?>
                    <?php if (isset($isConsolidated) && $isConsolidated): ?>
                    <br>
                    <a href="<?= site_url('accountant/remove-charge/' . $item['id']) ?>" 
                       onclick="return confirm('Are you sure you want to remove this charge?');"
                       style="color:#ef4444;font-size:0.75rem;text-decoration:none;margin-top:4px;display:inline-block" 
                       title="Remove Charge">
                      <i class="fa-solid fa-trash"></i> Remove
                    </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" style="padding:40px;text-align:center;color:#6b7280">No items found for this invoice.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <tfoot style="background:#f9fafb">
            <tr>
              <td colspan="3" style="padding:12px;text-align:right;font-weight:600">Subtotal:</td>
              <td style="padding:12px;text-align:right;font-weight:600">$<?= number_format((float)($billing['subtotal'] ?? 0), 2) ?></td>
            </tr>
            <tr>
              <td colspan="3" style="padding:12px;text-align:right">Insurance Coverage:</td>
              <td style="padding:12px;text-align:right;color:#16a34a">-$<?= number_format((float)($insuranceCoverage ?? 0), 2) ?></td>
            </tr>
            <tr style="border-top:2px solid #e5e7eb">
              <td colspan="3" style="padding:12px;text-align:right;font-weight:700;font-size:1.1rem">Total Amount:</td>
              <td style="padding:12px;text-align:right;font-weight:700;font-size:1.1rem">$<?= number_format((float)($billing['total_amount'] ?? 0), 2) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </section>

    <!-- Payment History -->
    <section class="panel">
      <div class="panel-head">
        <h3 style="margin:0;font-size:1rem;font-weight:600">Payment History</h3>
      </div>
      <div class="panel-body" style="padding:0">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr style="background:#f9fafb">
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;width:25%">Payment Date</th>
              <th style="text-align:right;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;width:20%">Amount</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;width:20%">Method</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600">Notes</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($payments)): ?>
              <?php foreach ($payments as $p): ?>
                <tr style="border-bottom:1px solid #f3f4f6">
                  <td style="padding:12px">
                    <?= esc(date('M d, Y', strtotime($p['paid_at'] ?? $p['created_at'] ?? 'now'))) ?>
                    <br>
                    <small style="color:#6b7280"><?= esc(date('h:i A', strtotime($p['paid_at'] ?? $p['created_at'] ?? 'now'))) ?></small>
                  </td>
                  <td style="padding:12px;text-align:right;font-weight:600;color:#16a34a">$<?= number_format((float)($p['amount'] ?? 0), 2) ?></td>
                  <td style="padding:12px"><?= esc(ucfirst($p['payment_method'] ?? 'N/A')) ?></td>
                  <td style="padding:12px;color:#6b7280;font-size:0.875rem"><?= esc($p['notes'] ?? '-') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" style="padding:40px;text-align:center;color:#6b7280">No payments recorded yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <tfoot style="background:#f9fafb">
            <tr style="border-top:2px solid #e5e7eb">
              <td style="padding:12px;text-align:right;font-weight:700;font-size:1.1rem" colspan="2">Total Paid:</td>
              <td colspan="2" style="padding:12px;font-weight:700;font-size:1.1rem;color:#16a34a">$<?= number_format((float)($totalPaid ?? 0), 2) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>
