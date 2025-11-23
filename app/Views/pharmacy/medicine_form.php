<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($medicine) ? 'Edit' : 'Add New' ?> Medicine</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
            </div>
            <div class="card-body">
              <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                  <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p class="mb-0"><?= $error ?></p>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <form action="<?= isset($medicine) ? site_url('pharmacy/medicines/save/' . $medicine['id']) : site_url('pharmacy/medicines/save') ?>" method="post">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="medicine_code">Medicine Code <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="medicine_code" name="medicine_code" 
                             value="<?= old('medicine_code', $medicine['medicine_code'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="name">Medicine Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="name" name="name" 
                             value="<?= old('name', $medicine['name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="generic_name">Generic Name</label>
                      <input type="text" class="form-control" id="generic_name" name="generic_name" 
                             value="<?= old('generic_name', $medicine['generic_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                      <label for="brand_name">Brand Name</label>
                      <input type="text" class="form-control" id="brand_name" name="brand_name" 
                             value="<?= old('brand_name', $medicine['brand_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                      <label for="category">Category</label>
                      <select class="form-control" id="category" name="category">
                        <option value="">Select Category</option>
                        <option value="Antibiotic" <?= (old('category', $medicine['category'] ?? '') == 'Antibiotic') ? 'selected' : '' ?>>Antibiotic</option>
                        <option value="Analgesic" <?= (old('category', $medicine['category'] ?? '') == 'Analgesic') ? 'selected' : '' ?>>Analgesic</option>
                        <option value="Antiviral" <?= (old('category', $medicine['category'] ?? '') == 'Antiviral') ? 'selected' : '' ?>>Antiviral</option>
                        <option value="Antifungal" <?= (old('category', $medicine['category'] ?? '') == 'Antifungal') ? 'selected' : '' ?>>Antifungal</option>
                        <option value="Antihistamine" <?= (old('category', $medicine['category'] ?? '') == 'Antihistamine') ? 'selected' : '' ?>>Antihistamine</option>
                        <option value="Other" <?= (old('category', $medicine['category'] ?? '') == 'Other') ? 'selected' : '' ?>>Other</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="dosage_form">Dosage Form</label>
                      <select class="form-control" id="dosage_form" name="dosage_form">
                        <option value="">Select Dosage Form</option>
                        <option value="Tablet" <?= (old('dosage_form', $medicine['dosage_form'] ?? '') == 'Tablet') ? 'selected' : '' ?>>Tablet</option>
                        <option value="Capsule" <?= (old('dosage_form', $medicine['dosage_form'] ?? '') == 'Capsule') ? 'selected' : '' ?>>Capsule</option>
                        <option value="Syrup" <?= (old('dosage_form', $medicine['dosage_form'] ?? '') == 'Syrup') ? 'selected' : '' ?>>Syrup</option>
                        <option value="Injection" <?= (old('dosage_form', $medicine['dosage_form'] ?? '') == 'Injection') ? 'selected' : '' ?>>Injection</option>
                        <option value="Ointment" <?= (old('dosage_form', $medicine['dosage_form'] ?? '') == 'Ointment') ? 'selected' : '' ?>>Ointment</option>
                        <option value="Drops" <?= (old('dosage_form', $medicine['dosage_form'] ?? '') == 'Drops') ? 'selected' : '' ?>>Drops</option>
                        <option value="Inhaler" <?= (old('dosage_form', $medicine['dosage_form'] ?? '') == 'Inhaler') ? 'selected' : '' ?>>Inhaler</option>
                        <option value="Other" <?= (old('dosage_form', $medicine['dosage_form'] ?? '') == 'Other') ? 'selected' : '' ?>>Other</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="strength">Strength</label>
                      <input type="text" class="form-control" id="strength" name="strength" 
                             value="<?= old('strength', $medicine['strength'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                      <label for="unit">Unit</label>
                      <input type="text" class="form-control" id="unit" name="unit" 
                             value="<?= old('unit', $medicine['unit'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                      <label for="purchase_price">Purchase Price <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="number" class="form-control" id="purchase_price" name="purchase_price" 
                               step="0.01" min="0" value="<?= old('purchase_price', $medicine['purchase_price'] ?? '0.00') ?>" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="selling_price">Selling Price <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="number" class="form-control" id="selling_price" name="selling_price" 
                               step="0.01" min="0" value="<?= old('selling_price', $medicine['selling_price'] ?? '0.00') ?>" required>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="manufacturer">Manufacturer</label>
                      <input type="text" class="form-control" id="manufacturer" name="manufacturer" 
                             value="<?= old('manufacturer', $medicine['manufacturer'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="supplier">Supplier</label>
                      <input type="text" class="form-control" id="supplier" name="supplier" 
                             value="<?= old('supplier', $medicine['supplier'] ?? '') ?>">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="requires_prescription" name="requires_prescription" value="1" 
                          <?= (old('requires_prescription', $medicine['requires_prescription'] ?? 0) == 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="requires_prescription">Requires Prescription</label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="description" name="description" rows="2"><?= old('description', $medicine['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                  <label for="side_effects">Side Effects</label>
                  <textarea class="form-control" id="side_effects" name="side_effects" rows="2"><?= old('side_effects', $medicine['side_effects'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                  <label for="contraindications">Contraindications</label>
                  <textarea class="form-control" id="contraindications" name="contraindications" rows="2"><?= old('contraindications', $medicine['contraindications'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                  <label for="storage_instructions">Storage Instructions</label>
                  <textarea class="form-control" id="storage_instructions" name="storage_instructions" rows="2"><?= old('storage_instructions', $medicine['storage_instructions'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                          <?= (old('is_active', $medicine['is_active'] ?? 1) == 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_active">Active</label>
                  </div>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?= isset($medicine) ? 'Update' : 'Save' ?> Medicine
                  </button>
                  <a href="<?= site_url('pharmacy/medicines') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate selling price if empty when purchase price changes
    const purchasePrice = document.getElementById('purchase_price');
    const sellingPrice = document.getElementById('selling_price');
    
    if (purchasePrice && sellingPrice) {
      purchasePrice.addEventListener('change', function() {
        if (!sellingPrice.value && purchasePrice.value) {
          // Add 20% markup by default
          const markup = parseFloat(purchasePrice.value) * 1.2;
          sellingPrice.value = markup.toFixed(2);
        }
      });
    }
  });
</script>
</body>
</html>