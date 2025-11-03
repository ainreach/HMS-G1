<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventory</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Inventory</h1><small>Pharmacy Stock</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <a href="<?= site_url('dashboard/pharmacist') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
</div></header>
<main class="content" style="padding:16px">
  <section class="panel">
    <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">All Medicines</h2></div>
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
          <?php if (!empty($rows)) : foreach ($rows as $r) : ?>
            <tr>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($r['item']) ?></td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($r['sku']) ?></td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($r['on_hand']) ?></td>
              <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($r['reorder']) ?></td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="4" style="padding:12px;text-align:center;color:#6b7280">No inventory data.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
