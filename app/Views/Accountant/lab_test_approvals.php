<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lab Test Approvals | Accountant</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .payment-badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 600;
    }
    .payment-paid {
      background: #d1fae5;
      color: #059669;
    }
    .payment-unpaid {
      background: #fee2e2;
      color: #dc2626;
    }
    .specimen-badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: 600;
    }
    .specimen-yes {
      background: #dbeafe;
      color: #2563eb;
    }
    .specimen-no {
      background: #f3f4f6;
      color: #6b7280;
    }
  </style>
</head>
<body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Lab Test Approvals</h1><small>Review and approve lab test requests</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout">
<?= $this->include('Accountant/sidebar', ['currentPage' => 'lab-test-approvals']) ?>
  <main class="content">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger">
        <strong>Database Setup Required:</strong> <?= esc($error) ?>
        <br><br>
        <strong>Option 1: Click the button below to auto-setup (Recommended):</strong>
        <br>
        <a href="<?= site_url('accountant/setup-lab-test-columns') ?>" class="btn-small" style="background:#10b981;color:white;padding:10px 16px;border-radius:6px;text-decoration:none;font-size:0.875rem;display:inline-block;margin-top:8px">
          <i class="fa-solid fa-magic"></i> Auto Setup Database Columns
        </a>
        <br><br>
        <strong>Option 2: Or run this SQL in phpMyAdmin:</strong>
        <pre style="background:#f3f4f6;padding:12px;border-radius:6px;margin-top:8px;overflow-x:auto">ALTER TABLE `lab_tests` 
ADD COLUMN `requires_specimen` TINYINT(1) DEFAULT 1 AFTER `test_category`,
ADD COLUMN `accountant_approved` TINYINT(1) DEFAULT 0 AFTER `requires_specimen`,
ADD COLUMN `accountant_approved_by` INT(11) UNSIGNED NULL AFTER `accountant_approved`,
ADD COLUMN `accountant_approved_at` DATETIME NULL AFTER `accountant_approved_by`;</pre>
      </div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Pending Lab Test Approvals</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test #</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Doctor</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test Name / Category</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Specimen</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Payment</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Requested</th>
              <th style="text-align:center;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($tests)) : ?>
            <?php foreach ($tests as $item): ?>
              <?php 
                $test = $item['test']; 
                $hasPaid = $item['has_paid'];
                $testCategory = strtolower($test['test_category'] ?? '');
                $isImaging = ($testCategory === 'imaging');
                $rowStyle = $isImaging ? 'background:#f3e8ff;border-left:3px solid #7c3aed;' : '';
              ?>
              <tr style="<?= $rowStyle ?>">
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['test_number'] ?? 'N/A') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <strong><?= esc(($test['first_name'] ?? '') . ' ' . ($test['last_name'] ?? '')) ?></strong>
                  <br><small style="color:#6b7280"><?= esc($test['patient_code'] ?? '') ?></small>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['doctor_name'] ?? 'N/A') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?php 
                    $testName = esc($test['test_name'] ?? '');
                    $testCategory = strtolower($test['test_category'] ?? '');
                    $isImaging = ($testCategory === 'imaging');
                  ?>
                  <?php if ($isImaging): ?>
                    <span style="display:inline-flex;align-items:center;gap:4px">
                      <i class="fa-solid fa-brain" style="color:#7c3aed;font-size:0.875rem"></i>
                      <strong style="color:#7c3aed"><?= $testName ?></strong>
                    </span>
                    <br><small style="color:#7c3aed;font-size:0.75rem">Neurological Imaging</small>
                  <?php else: ?>
                    <?= $testName ?>
                  <?php endif; ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?php if ($test['requires_specimen'] == 1): ?>
                    <span class="specimen-badge specimen-yes">With Specimen</span>
                  <?php else: ?>
                    <span class="specimen-badge specimen-no">No Specimen</span>
                  <?php endif; ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?php if ($hasPaid): ?>
                    <span class="payment-badge payment-paid">✓ Paid</span>
                  <?php else: ?>
                    <span class="payment-badge payment-unpaid">✗ Unpaid</span>
                  <?php endif; ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?php
                    $requestedDate = new \DateTime($test['requested_date']);
                    echo $requestedDate->format('M d, Y h:i A');
                  ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:center">
                  <?php if ($hasPaid): ?>
                    <form method="post" action="<?= site_url('accountant/lab-test-approve/' . $test['id']) ?>" style="display:inline">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn-small" style="background:#10b981;color:white;padding:6px 12px;border-radius:4px;border:none;cursor:pointer;font-size:0.875rem;margin-right:4px">
                        <i class="fa-solid fa-check"></i> Approve
                      </button>
                    </form>
                    <form method="post" action="<?= site_url('accountant/lab-test-reject/' . $test['id']) ?>" style="display:inline" onsubmit="return confirm('Are you sure you want to reject this lab test?');">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn-small" style="background:#dc2626;color:white;padding:6px 12px;border-radius:4px;border:none;cursor:pointer;font-size:0.875rem">
                        <i class="fa-solid fa-times"></i> Reject
                      </button>
                    </form>
                  <?php else: ?>
                    <span style="color:#dc2626;font-size:0.875rem">Payment Required</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="8" style="padding:12px;text-align:center;color:#6b7280">No pending lab test approvals.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>

