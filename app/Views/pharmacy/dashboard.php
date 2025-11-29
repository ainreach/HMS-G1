<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pharmacist Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Pharmacist</h1><small>Track and dispense medicines</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Pharmacy navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/pharmacist') ?>" class="active" aria-current="page">Overview</a>
  <a href="<?= site_url('pharmacy/prescriptions') ?>" data-feature="pharmacy">Prescriptions</a>
  <a href="<?= site_url('pharmacy/dispense/new') ?>" data-feature="pharmacy">Dispense</a>
  <a href="<?= site_url('pharmacy/inventory') ?>" data-feature="pharmacy">Inventory</a>
  <a href="<?= site_url('pharmacy/low-stock-alerts') ?>" data-feature="pharmacy">Low Stock</a>
  <a href="<?= site_url('pharmacy/medicine-expiry') ?>" data-feature="pharmacy">Expiry Alerts</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Prescriptions</span><i class="fa-solid fa-pills" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($prescriptionsToday ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Low Stock Items</span><i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($lowStock ?? 0) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('pharmacy/medicines/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;background-color:#10b981;color:white">
          <i class="fas fa-plus"></i> Add Medicine
        </a>
        <a class="btn" href="<?= site_url('pharmacy/dispense/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">
          <i class="fas fa-pills"></i> Dispense
        </a>
        <a class="btn" href="<?= site_url('pharmacy/prescriptions') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">
          <i class="fas fa-prescription"></i> Prescriptions
        </a>
        <a class="btn" href="<?= site_url('pharmacy/add-stock') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">
          <i class="fas fa-boxes-stacked"></i> Add Stock
        </a>
        <a class="btn" href="<?= site_url('pharmacy/low-stock-alerts') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">
          <i class="fas fa-exclamation-triangle"></i> Low Stock
        </a>
        <a class="btn" href="<?= site_url('pharmacy/medicine-expiry') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">
          <i class="fas fa-calendar-xmark"></i> Expiry Alerts
        </a>
      </div>
    </section>

    <section id="inventory" class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Inventory Snapshot</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Item</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">SKU</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">On Hand</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Reorder</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($snapshot)) : foreach ($snapshot as $row) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['item']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['sku']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['on_hand']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['reorder']) ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="4" style="padding:12px;text-align:center;color:#6b7280">No inventory data.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
