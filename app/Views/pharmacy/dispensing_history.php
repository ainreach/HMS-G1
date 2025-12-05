<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dispensing History - Pharmacy</title>
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
    <h1 style="font-size:1.5rem;margin-bottom:1rem">Dispensing History</h1>
    
    <section class="panel">
      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($dispensingHistory)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Date</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Patient</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Medicine</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Quantity</th>
                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Dispensed By</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($dispensingHistory as $dispense): ?>
                <tr>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc(date('M d, Y H:i', strtotime($dispense['dispensed_at'] ?? $dispense['created_at']))) ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($dispense['patient_name'] ?? 'N/A') ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($dispense['medicine_name'] ?? 'N/A') ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($dispense['quantity'] ?? 0) ?></td>
                  <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($dispense['dispensed_by_name'] ?? 'N/A') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p style="color:#6b7280;margin:0;padding:40px;text-align:center">No dispensing history found</p>
        <?php endif; ?>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

