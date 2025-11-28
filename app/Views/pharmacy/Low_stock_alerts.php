<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Low Stock Alerts</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Low Stock Alerts</h1><small>Inventory below reorder level</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('dashboard/pharmacist') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Pharmacy navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/pharmacist') ?>">Overview</a>
  <a href="<?= site_url('pharmacy/prescriptions') ?>" data-feature="pharmacy">Prescriptions</a>
  <a href="<?= site_url('pharmacy/dispense/new') ?>" data-feature="pharmacy">Dispense</a>
  <a href="<?= site_url('pharmacy/inventory') ?>" data-feature="pharmacy">Inventory</a>
  <a href="<?= site_url('pharmacy/low-stock-alerts') ?>" class="active" aria-current="page" data-feature="pharmacy">Low Stock</a>
  <a href="<?= site_url('pharmacy/medicine-expiry') ?>" data-feature="pharmacy">Expiry Alerts</a>
</nav></aside>
  <main class="content" style="padding:16px">
    <section class="panel" style="margin-bottom:20px;background:#fef3c7;border:1px solid #fbbf24;border-radius:8px">
      <div class="panel-body" style="padding:16px;display:flex;align-items:center;gap:12px">
        <i class="fa-solid fa-triangle-exclamation" style="font-size:24px;color:#f59e0b"></i>
        <div>
          <h3 style="margin:0 0 4px;font-size:1rem;color:#92400e">Attention Required</h3>
          <p style="margin:0;color:#78350f;font-size:0.9rem">
            <?= count($lowStockItems ?? []) ?> item(s) are below reorder level and need restocking.
          </p>
        </div>
      </div>
    </section>

    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Low Stock Items</h2>
        <a href="<?= site_url('pharmacy/add-stock') ?>" class="btn" style="padding:8px 16px;background:#10b981;color:white;border:none;border-radius:6px;text-decoration:none">
          <i class="fa-solid fa-plus"></i> Add Stock
        </a>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Item</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">SKU</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">On Hand</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Reorder Level</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Shortage</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Supplier</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($lowStockItems)) : foreach ($lowStockItems as $item) : 
              $onHand = (int)($item['on_hand'] ?? 0);
              $reorder = (int)($item['reorder_level'] ?? 0);
              $shortage = max(0, $reorder - $onHand);
              $criticalLevel = $onHand <= ($reorder * 0.25);
              $rowStyle = $criticalLevel ? 'background:#fee2e2' : '';
            ?>
              <tr style="border-bottom:1px solid #f3f4f6;<?= $rowStyle ?>">
                <td style="padding:10px">
                  <div style="display:flex;align-items:center;gap:8px">
                    <?php if ($criticalLevel): ?>
                      <i class="fa-solid fa-circle-exclamation" style="color:#dc2626" title="Critical"></i>
                    <?php else: ?>
                      <i class="fa-solid fa-circle-info" style="color:#f59e0b" title="Low"></i>
                    <?php endif; ?>
                    <strong><?= esc($item['item'] ?? $item['name'] ?? 'N/A') ?></strong>
                  </div>
                </td>
                <td style="padding:10px"><?= esc($item['sku'] ?? 'N/A') ?></td>
                <td style="padding:10px;text-align:center">
                  <span style="padding:4px 8px;border-radius:4px;background:<?= $criticalLevel ? '#fecaca' : '#fed7aa' ?>;color:<?= $criticalLevel ? '#991b1b' : '#92400e' ?>;font-weight:500">
                    <?= $onHand ?>
                  </span>
                </td>
                <td style="padding:10px;text-align:center"><?= $reorder ?></td>
                <td style="padding:10px;text-align:center;color:#dc2626;font-weight:600"><?= $shortage ?></td>
                <td style="padding:10px"><?= esc($item['supplier'] ?? 'Not specified') ?></td>
                <td style="padding:10px;text-align:center">
                  <a href="<?= site_url('pharmacy/restock/' . ($item['id'] ?? 0)) ?>" 
                     class="btn-icon" 
                     title="Restock"
                     style="padding:6px 12px;background:#10b981;color:white;border:none;border-radius:4px;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                    <i class="fa-solid fa-boxes-stacked"></i> Restock
                  </a>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="7" style="padding:24px;text-align:center;color:#6b7280">
                  <i class="fa-solid fa-check-circle" style="font-size:48px;color:#10b981;margin-bottom:12px"></i>
                  <p style="margin:0">All items are adequately stocked!</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <?php if (!empty($lowStockItems) && count($lowStockItems) > 0): ?>
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Stock Summary</h2></div>
      <div class="panel-body" style="padding:16px">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
          <div style="padding:16px;background:#fef3c7;border-radius:8px">
            <div style="font-size:0.85rem;color:#92400e;margin-bottom:4px">Total Items Low</div>
            <div style="font-size:1.5rem;font-weight:700;color:#78350f"><?= count($lowStockItems) ?></div>
          </div>
          <div style="padding:16px;background:#fee2e2;border-radius:8px">
            <div style="font-size:0.85rem;color:#991b1b;margin-bottom:4px">Critical Level</div>
            <div style="font-size:1.5rem;font-weight:700;color:#7f1d1d">
              <?= count(array_filter($lowStockItems, function($item) {
                $onHand = (int)($item['on_hand'] ?? 0);
                $reorder = (int)($item['reorder_level'] ?? 0);
                return $onHand <= ($reorder * 0.25);
              })) ?>
            </div>
          </div>
          <div style="padding:16px;background:#dbeafe;border-radius:8px">
            <div style="font-size:0.85rem;color:#1e40af;margin-bottom:4px">Need Reorder</div>
            <div style="font-size:1.5rem;font-weight:700;color:#1e3a8a">
              <?= array_sum(array_map(function($item) {
                $onHand = (int)($item['on_hand'] ?? 0);
                $reorder = (int)($item['reorder_level'] ?? 0);
                return max(0, $reorder - $onHand);
              }, $lowStockItems)) ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php endif; ?>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>