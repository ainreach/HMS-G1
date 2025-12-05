<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prescription Fulfillment - Pharmacy</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Pharmacy</h1><small>Prescription Queue Management</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Pharmacy navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/pharmacist') ?>">Overview</a>
  <a href="<?= site_url('pharmacy/prescriptions') ?>" data-feature="pharmacy">Prescription Queue</a>
  <a href="<?= site_url('pharmacy/inventory') ?>" data-feature="pharmacy">Inventory</a>
  <a href="<?= site_url('pharmacy/low-stock-alerts') ?>" data-feature="pharmacy">Low Stock</a>
  <a href="<?= site_url('pharmacy/medicine-expiry') ?>" data-feature="pharmacy">Expiry Alerts</a>
</nav></aside>
  <main class="content" style="padding:16px">
    <h1 style="font-size:1.5rem;margin-bottom:1rem">Prescription Fulfillment</h1>
    
    <section class="panel">
      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($prescriptions)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Prescription ID</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Patient</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Medication</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Status</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($prescriptions as $rx): ?>
                <tr>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb">#<?= esc($rx['id']) ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($rx['patient_name'] ?? 'N/A') ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($rx['medication'] ?? 'N/A') ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                    <span style="padding:4px 8px;border-radius:4px;font-size:0.875rem;font-weight:600;background:<?= $rx['status'] === 'dispensed' ? '#d1fae5' : ($rx['status'] === 'prepared' ? '#dbeafe' : '#fef3c7') ?>;color:<?= $rx['status'] === 'dispensed' ? '#065f46' : ($rx['status'] === 'prepared' ? '#1e40af' : '#92400e') ?>">
                      <?= esc(ucfirst($rx['status'] ?? 'pending')) ?>
                    </span>
                  </td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc(date('M d, Y', strtotime($rx['start_date'] ?? $rx['created_at']))) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p style="color:#6b7280;margin:0;padding:40px;text-align:center">No prescriptions found</p>
        <?php endif; ?>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

