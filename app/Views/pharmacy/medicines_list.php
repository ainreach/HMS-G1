<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Medicines List</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Medicines</h1><small>Pharmacy Inventory</small></div>
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
  <a href="<?= site_url('pharmacy/prescriptions') ?>">Prescriptions</a>
  <a href="<?= site_url('pharmacy/medicines') ?>" class="active" aria-current="page">Medicines</a>
  <a href="<?= site_url('pharmacy/dispense/new') ?>">Dispense</a>
  <a href="<?= site_url('pharmacy/inventory') ?>">Inventory</a>
  <a href="<?= site_url('pharmacy/low-stock-alerts') ?>">Low Stock</a>
  <a href="<?= site_url('pharmacy/medicine-expiry') ?>">Expiry Alerts</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Medicines List</h2>
        <div>
          <a href="<?= site_url('pharmacy/medicines/new') ?>" class="btn" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none"><i class="fa-solid fa-plus"></i> Add Medicine</a>
        </div>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Code</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Name</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Generic</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Category</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Stock</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Price</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($medicines)): foreach ($medicines as $medicine): ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($medicine['medicine_code'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($medicine['name'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($medicine['generic_name'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($medicine['category'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($medicine['stock_quantity'] ?? 0) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">₱<?= number_format($medicine['selling_price'] ?? 0, 2) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <div style="display:flex;gap:6px">
                    <a href="<?= site_url('pharmacy/medicines/edit/' . $medicine['id']) ?>" style="padding:4px 8px;background:#3b82f6;color:white;border-radius:4px;text-decoration:none;font-size:0.75rem">Edit</a>
                    <a href="<?= site_url('pharmacy/medicines/delete/' . $medicine['id']) ?>" style="padding:4px 8px;background:#ef4444;color:white;border-radius:4px;text-decoration:none;font-size:0.75rem" onclick="return confirm('Are you sure?')">Delete</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="7" style="padding:12px;text-align:center;color:#6b7280">No medicines found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
