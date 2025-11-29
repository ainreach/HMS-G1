<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Medicine Expiry Alerts</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Expiry Alerts</h1><small>Monitor medicine expiration dates</small></div>
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
  <a href="<?= site_url('pharmacy/low-stock-alerts') ?>" data-feature="pharmacy">Low Stock</a>
  <a href="<?= site_url('pharmacy/medicine-expiry') ?>" class="active" aria-current="page" data-feature="pharmacy">Expiry Alerts</a>
</nav></aside>
  <main class="content" style="padding:16px">
    <?php
    $expired = [];
    $expiringSoon = [];
    $safe = [];
    $today = new DateTime();
    
    foreach (($expiryItems ?? []) as $item) {
      if (empty($item['expiry_date'])) continue;
      $expiry = new DateTime($item['expiry_date']);
      $interval = $today->diff($expiry);
      $daysUntilExpiry = ($expiry < $today) ? -$interval->days : $interval->days;
      
      $item['days_until_expiry'] = $daysUntilExpiry;
      
      if ($daysUntilExpiry < 0) {
        $expired[] = $item;
      } elseif ($daysUntilExpiry <= 90) {
        $expiringSoon[] = $item;
      } else {
        $safe[] = $item;
      }
    }
    ?>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px;margin-bottom:20px">
      <div style="padding:20px;background:#fee2e2;border:1px solid #fca5a5;border-radius:8px">
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div>
            <div style="font-size:0.9rem;color:#991b1b;margin-bottom:4px">Expired</div>
            <div style="font-size:2rem;font-weight:700;color:#7f1d1d"><?= count($expired) ?></div>
          </div>
          <i class="fa-solid fa-circle-xmark" style="font-size:40px;color:#dc2626;opacity:0.5"></i>
        </div>
      </div>
      <div style="padding:20px;background:#fef3c7;border:1px solid #fbbf24;border-radius:8px">
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div>
            <div style="font-size:0.9rem;color:#92400e;margin-bottom:4px">Expiring Soon (90days)</div>
            <div style="font-size:2rem;font-weight:700;color:#78350f"><?= count($expiringSoon) ?></div>
          </div>
          <i class="fa-solid fa-clock" style="font-size:40px;color:#f59e0b;opacity:0.5"></i>
        </div>
      </div>
      <div style="padding:20px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px">
        <div style="display:flex;align-items:center;justify-content:space-between">
          <div>
            <div style="font-size:0.9rem;color:#065f46;margin-bottom:4px">Safe (>90d)</div>
            <div style="font-size:2rem;font-weight:700;color:#064e3b"><?= count($safe) ?></div>
          </div>
          <i class="fa-solid fa-circle-check" style="font-size:40px;color:#10b981;opacity:0.5"></i>
        </div>
      </div>
    </div>

    <?php if (!empty($expired)): ?>
    <section class="panel" style="margin-bottom:16px;border:2px solid #dc2626">
      <div class="panel-head" style="background:#fee2e2;border-bottom:1px solid #fca5a5">
        <h2 style="margin:0;font-size:1.1rem;color:#991b1b">
          <i class="fa-solid fa-triangle-exclamation"></i> Expired Medicines
        </h2>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb">Medicine</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb">Batch No.</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Quantity</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Expiry Date</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Days Overdue</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($expired as $item): ?>
              <tr style="background:#fee2e2;border-bottom:1px solid #fca5a5">
                <td style="padding:10px"><strong><?= esc($item['medicine_name'] ?? $item['item'] ?? 'N/A') ?></strong></td>
                <td style="padding:10px"><?= esc($item['batch_no'] ?? 'N/A') ?></td>
                <td style="padding:10px;text-align:center"><?= esc($item['quantity'] ?? $item['on_hand'] ?? 0) ?></td>
                <td style="padding:10px;text-align:center"><?= esc($item['expiry_date']) ?></td>
                <td style="padding:10px;text-align:center;color:#dc2626;font-weight:600">
                  <?= abs($item['days_until_expiry']) ?> days
                </td>
                <td style="padding:10px;text-align:center">
                  <a href="<?= site_url('pharmacy/remove-expired/' . ($item['id'] ?? 0)) ?>" 
                     class="btn-icon" 
                     onclick="return confirm('Remove this expired medicine from inventory?')"
                     title="Remove from inventory"
                     style="padding:6px 12px;background:#dc2626;color:white;border:none;border-radius:4px;text-decoration:none">
                    <i class="fa-solid fa-trash"></i> Remove
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($expiringSoon)): ?>
    <section class="panel" style="margin-bottom:16px;border:2px solid #f59e0b">
      <div class="panel-head" style="background:#fef3c7;border-bottom:1px solid #fbbf24">
        <h2 style="margin:0;font-size:1.1rem;color:#92400e">
          <i class="fa-solid fa-clock"></i> Expiring Soon (Within 90 Days)
        </h2>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb">Medicine</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb">Batch No.</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Quantity</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Expiry Date</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Days Remaining</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Priority</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($expiringSoon as $item): 
              $priority = $item['days_until_expiry'] <= 30 ? 'High' : ($item['days_until_expiry'] <= 60 ? 'Medium' : 'Low');
              $priorityColor = $item['days_until_expiry'] <= 30 ? '#dc2626' : ($item['days_until_expiry'] <= 60 ? '#f59e0b' : '#3b82f6');
              $rowColor = $item['days_until_expiry'] <= 30 ? '#fee2e2' : '#fef3c7';
            ?>
              <tr style="background:<?= $rowColor ?>;border-bottom:1px solid #f3f4f6">
                <td style="padding:10px"><strong><?= esc($item['medicine_name'] ?? $item['item'] ?? 'N/A') ?></strong></td>
                <td style="padding:10px"><?= esc($item['batch_no'] ?? 'N/A') ?></td>
                <td style="padding:10px;text-align:center"><?= esc($item['quantity'] ?? $item['on_hand'] ?? 0) ?></td>
                <td style="padding:10px;text-align:center"><?= esc($item['expiry_date']) ?></td>
                <td style="padding:10px;text-align:center;font-weight:600;color:<?= $priorityColor ?>">
                  <?= $item['days_until_expiry'] ?> days
                </td>
                <td style="padding:10px;text-align:center">
                  <span style="padding:4px 8px;border-radius:4px;font-size:0.85rem;font-weight:500;background:<?= $priorityColor ?>;color:white">
                    <?= $priority ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($safe)): ?>
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem;color:#065f46">
          <i class="fa-solid fa-circle-check"></i> Safe Inventory (>90 Days)
        </h2>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb">Medicine</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb">Batch No.</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Quantity</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Expiry Date</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb">Days Remaining</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($safe as $item): ?>
              <tr style="border-bottom:1px solid #f3f4f6">
                <td style="padding:10px"><?= esc($item['medicine_name'] ?? $item['item'] ?? 'N/A') ?></td>
                <td style="padding:10px"><?= esc($item['batch_no'] ?? 'N/A') ?></td>
                <td style="padding:10px;text-align:center"><?= esc($item['quantity'] ?? $item['on_hand'] ?? 0) ?></td>
                <td style="padding:10px;text-align:center"><?= esc($item['expiry_date']) ?></td>
                <td style="padding:10px;text-align:center;color:#10b981;font-weight:500">
                  <?= $item['days_until_expiry'] ?> days
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
    <?php endif; ?>

    <?php if (empty($expiryItems)): ?>
    <section class="panel">
      <div class="panel-body" style="padding:48px;text-align:center">
        <i class="fa-solid fa-calendar-check" style="font-size:64px;color:#10b981;margin-bottom:16px"></i>
        <h3 style="margin:0 0 8px;color:#374151">No Expiry Data Available</h3>
        <p style="margin:0;color:#6b7280">Add expiry dates to medicines in your inventory to track them here.</p>
      </div>
    </section>
    <?php endif; ?>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>