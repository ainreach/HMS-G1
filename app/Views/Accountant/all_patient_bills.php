<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>All Patient Bills - Accountant</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Accountant</h1><small>All Patient Bills</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout">
<?= $this->include('Accountant/sidebar', ['currentPage' => 'billing']) ?>
  <main class="content">
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">All Patient Bills</h2>
      </div>
      <div class="panel-body">
        <?php if (session()->getFlashdata('success')): ?>
          <div style="background:#dcfce7;border:1px solid #16a34a;border-radius:8px;padding:12px;margin-bottom:16px">
            <p style="margin:0;color:#16a34a"><?= esc(session()->getFlashdata('success')) ?></p>
          </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
          <div style="background:#fef2f2;border:1px solid #ef4444;border-radius:8px;padding:12px;margin-bottom:16px">
            <p style="margin:0;color:#dc2626"><?= esc(session()->getFlashdata('error')) ?></p>
          </div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">
          <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px">
            <h3 style="margin:0 0 8px;color:#64748b;font-size:0.875rem">Total Patients</h3>
            <p style="margin:0;font-size:1.5rem;font-weight:700"><?= count($allBills) ?></p>
          </div>
          <div style="background:#f0f9ff;border:1px solid #0ea5e9;border-radius:8px;padding:16px">
            <h3 style="margin:0 0 8px;color:#0284c7;font-size:0.875rem">Total Charges</h3>
            <p style="margin:0;font-size:1.5rem;font-weight:700">₱<?= number_format(array_sum(array_column($allBills, 'totalCharges')), 2) ?></p>
          </div>
          <div style="background:#dcfce7;border:1px solid #16a34a;border-radius:8px;padding:16px">
            <h3 style="margin:0 0 8px;color:#16a34a;font-size:0.875rem">Total Payments</h3>
            <p style="margin:0;font-size:1.5rem;font-weight:700">₱<?= number_format(array_sum(array_column($allBills, 'totalPayments')), 2) ?></p>
          </div>
          <div style="background:#fef3c7;border:1px solid #d97706;border-radius:8px;padding:16px">
            <h3 style="margin:0 0 8px;color:#d97706;font-size:0.875rem">Total Balance Due</h3>
            <p style="margin:0;font-size:1.5rem;font-weight:700">₱<?= number_format(array_sum(array_column($allBills, 'balanceDue')), 2) ?></p>
          </div>
        </div>

        <!-- Patient Bills Table -->
        <div style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Room</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Days Stayed</th>
                <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Room Charges</th>
                <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Other Charges</th>
                <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Total Charges</th>
                <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Payments</th>
                <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Balance Due</th>
                <th style="text-align:center;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
              </tr>
            </tr>
            <tbody>
              <?php if (!empty($allBills)) : foreach ($allBills as $bill) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <div>
                      <strong><?= esc($bill['patient']['first_name'] . ' ' . $bill['patient']['last_name']) ?></strong><br>
                      <small style="color:#6b7280"><?= esc($bill['patient']['patient_id']) ?></small>
                    </div>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php if ($bill['room']): ?>
                      <?= esc($bill['room']['room_number']) ?> - <?= esc(ucfirst($bill['room']['room_type'])) ?>
                    <?php else: ?>
                      <span style="color:#6b7280">No room</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($bill['daysStayed']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right">₱<?= number_format($bill['roomCharges'], 2) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right">₱<?= number_format($bill['totalInvoices'], 2) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right;font-weight:600">₱<?= number_format($bill['totalCharges'], 2) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right">₱<?= number_format($bill['totalPayments'], 2) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right;font-weight:700;color:<?= $bill['balanceDue'] > 0 ? '#dc2626' : '#16a34a' ?>">
                    ₱<?= number_format($bill['balanceDue'], 2) ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:center">
                    <div style="display:flex;gap:6px;justify-content:center">
                      <a href="<?= site_url('accountant/patients/billing/' . $bill['patient']['id']) ?>" style="padding:4px 8px;background:#3b82f6;color:white;border-radius:4px;text-decoration:none;font-size:0.75rem">
                        <i class="fa-solid fa-eye"></i> View
                      </a>
                      <?php if ($bill['balanceDue'] > 0): ?>
                      <a href="<?= site_url('accountant/payments/new?patient_id=' . $bill['patient']['id']) ?>" style="padding:4px 8px;background:#10b981;color:white;border-radius:4px;text-decoration:none;font-size:0.75rem">
                        <i class="fa-solid fa-money-bill"></i> Pay
                      </a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="9" style="padding:20px;color:#6b7280;text-align:center">No patients with room assignments found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Action Buttons -->
        <div style="display:flex;gap:12px;margin-top:20px">
          <a href="<?= site_url('accountant/billing') ?>" style="padding:10px 20px;background:#6b7280;color:white;text-decoration:none;border-radius:6px">
            <i class="fa-solid fa-arrow-left"></i> Back to Billing
          </a>
          <button onclick="window.print()" style="padding:10px 20px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer">
            <i class="fa-solid fa-print"></i> Print Summary
          </button>
        </div>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
