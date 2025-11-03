<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Stock</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Add Stock</h1><small>Replenish inventory</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <a href="<?= site_url('dashboard/pharmacist') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
</div></header>
<main class="content" style="max-width:720px;margin:20px auto;padding:16px">
  <h1 style="margin:0 0 12px">Add Stock to Inventory</h1>
  
  <?php if (session('success')): ?>
    <div class="alert alert-success" style="margin-bottom:16px;padding:12px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;color:#065f46">
      <?= esc(session('success')) ?>
    </div>
  <?php endif; ?>
  
  <?php if (session('error')): ?>
    <div class="alert alert-error" style="margin-bottom:16px;padding:12px;background:#fee2e2;border:1px solid #fca5a5;border-radius:6px;color:#991b1b">
      <?= esc(session('error')) ?>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('pharmacy/add-stock') ?>" style="background:white;padding:24px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1)">
    <?= csrf_field() ?>
    
    <div class="form-row" style="margin-bottom:16px">
      <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Medicine *</label>
      <?php if (!empty($medicines)): ?>
        <select name="medicine_id" required style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem">
          <option value="">-- Select Medicine --</option>
          <?php foreach ($medicines as $m): 
            $mid = (int)($m['id'] ?? 0);
            $label = trim(($m['name'] ?? '') . ' ' . ($m['strength'] ?? ''));
            $sku = $m['sku'] ?? '';
          ?>
            <option value="<?= $mid ?>" <?= old('medicine_id') == $mid ? 'selected' : '' ?>>
              <?= esc($label) ?> <?= $sku ? '(SKU: ' . esc($sku) . ')' : '' ?>
            </option>
          <?php endforeach; ?>
        </select>
      <?php else: ?>
        <input type="number" name="medicine_id" required value="<?= old('medicine_id') ?>" 
               style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px">
      <?php endif; ?>
      <small style="display:block;margin-top:4px;color:#6b7280">Select the medicine to restock</small>
    </div>

    <div class="form-row" style="margin-bottom:16px">
      <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Quantity to Add *</label>
      <input type="number" name="quantity" min="1" required value="<?= old('quantity') ?>" 
             style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem"
             placeholder="Enter quantity">
      <small style="display:block;margin-top:4px;color:#6b7280">Number of units to add to inventory</small>
    </div>

    <div class="form-row" style="margin-bottom:16px">
      <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Batch Number</label>
      <input type="text" name="batch_no" value="<?= old('batch_no') ?>" 
             style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem"
             placeholder="e.g., BATCH-2024-001">
      <small style="display:block;margin-top:4px;color:#6b7280">Optional batch/lot number for tracking</small>
    </div>

    <div class="form-row" style="margin-bottom:16px">
      <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Expiry Date</label>
      <input type="date" name="expiry_date" value="<?= old('expiry_date') ?>" 
             style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem">
      <small style="display:block;margin-top:4px;color:#6b7280">Medicine expiration date (if applicable)</small>
    </div>

    <div class="form-row" style="margin-bottom:16px">
      <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Supplier</label>
      <input type="text" name="supplier" value="<?= old('supplier') ?>" 
             style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem"
             placeholder="Supplier name">
      <small style="display:block;margin-top:4px;color:#6b7280">Source of this stock</small>
    </div>

    <div class="form-row" style="margin-bottom:24px">
      <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Cost per Unit</label>
      <input type="number" name="cost_per_unit" step="0.01" min="0" value="<?= old('cost_per_unit') ?>" 
             style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem"
             placeholder="0.00">
      <small style="display:block;margin-top:4px;color:#6b7280">Purchase cost per unit (optional)</small>
    </div>

    <div style="display:flex;gap:8px">
      <button type="submit" class="btn btn-primary" 
              style="padding:10px 20px;background:#10b981;color:white;border:none;border-radius:6px;font-weight:500;cursor:pointer">
        <i class="fa-solid fa-check"></i> Add Stock
      </button>
      <a class="btn" href="<?= site_url('pharmacy/inventory') ?>" 
         style="padding:10px 20px;background:white;border:1px solid #d1d5db;border-radius:6px;text-decoration:none;color:#374151;font-weight:500">
        Cancel
      </a>
    </div>
  </form>

  <div style="margin-top:24px;padding:16px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px">
    <h3 style="margin:0 0 8px;font-size:0.95rem;color:#374151">
      <i class="fa-solid fa-info-circle" style="color:#3b82f6"></i> Quick Tips
    </h3>
    <ul style="margin:0;padding-left:20px;color:#6b7280;font-size:0.9rem">
      <li style="margin-bottom:4px">Always verify the batch number and expiry date</li>
      <li style="margin-bottom:4px">Keep track of supplier information for reordering</li>
      <li style="margin-bottom:4px">Record cost per unit for accurate inventory valuation</li>
      <li>Stock will be automatically added to existing inventory</li>
    </ul>
  </div>
</main>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>