<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($medicine) ? 'Edit' : 'Add New' ?> Medicine</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Redesigned Pastel Theme -->
  <style>
    body {
      background: #f3f7fc;
      font-family: "Segoe UI", sans-serif;
      color: #333;
    }

    /* TOPBAR */
    .dash-topbar {
      background: linear-gradient(to right, #8ec5fc, #e0f0ff);
      padding: 12px 0;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .topbar-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 90%;
      margin: auto;
    }
    .menu-btn i {
      font-size: 1.3rem;
      color: #1e4d78;
    }
    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .brand img {
      width: 40px;
      height: 40px;
    }
    .brand-text small {
      color: #1e4d78;
    }
    .top-right span.role {
      background: #eaf5ff;
      padding: 6px 12px;
      border-radius: 8px;
      color: #1e4d78;
      font-weight: 600;
    }
    .logout-btn {
      background: white;
      color: #1e4d78;
      border: 1px solid #c4dff5 !important;
    }

    /* SIDEBAR */
    .simple-sidebar {
      width: 240px;
      background: #ffffff;
      border-right: 1px solid #dce8f5;
      padding-top: 20px;
    }
    .side-nav a {
      display: block;
      padding: 14px 20px;
      color: #1e4d78;
      text-decoration: none;
      font-weight: 500;
      border-radius: 8px;
      margin: 5px 10px;
      transition: 0.25s;
    }
    .side-nav a:hover {
      background: #e8f3ff;
    }
    .side-nav a.active {
      background: #b5dbff;
      font-weight: 600;
      color: #0c3a61;
    }

    /* LAYOUT */
    .layout {
      display: flex;
    }

    /* CONTENT */
    .content {
      padding: 30px;
      width: 100%;
    }

    .card {
      background: white;
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      width: 100%;
    }

    .form-group label {
      font-weight: 600;
      color: #1e4d78;
      margin-bottom: 6px;
    }

    .form-control, .form-check-input, select {
      border-radius: 10px !important;
      border: 1px solid #c9dff1 !important;
      padding: 8px 12px;
    }

    .form-control:focus, select:focus {
      border-color: #7db8f5 !important;
      box-shadow: 0 0 0 2px #d9ecff;
    }

    .btn-primary {
      background: #4a90e2;
      border: none;
      padding: 10px 18px;
      border-radius: 10px;
    }

    .btn-secondary {
      background: #cbd9e6;
      border: none;
      padding: 10px 18px;
      border-radius: 10px;
      color: #0a2f4d;
    }

    textarea {
      border-radius: 12px !important;
    }

    .alert-danger {
      background: #ffe5e7;
      border-left: 4px solid #ff6b6b;
      color: #b32626;
    }
  </style>
</head>

<body>

<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home">
      <i class="fa-solid fa-house"></i>
    </a>

    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0;color:#003b67;">Pharmacist</h1>
        <small>Track and dispense medicines</small>
      </div>
    </div>

    <div class="top-right">
      <span class="role"><i class="fa-regular fa-user"></i>
        <?= esc(session('username') ?? session('role') ?? 'User') ?>
      </span>
      <a href="<?= site_url('logout') ?>" class="logout-btn">Logout</a>
    </div>
  </div>
</header>

<div class="layout">

  <!-- SIDEBAR -->
  <aside class="simple-sidebar" role="navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/pharmacist') ?>" class="active">Overview</a>
      <a href="<?= site_url('pharmacy/prescriptions') ?>">Prescriptions</a>
      <a href="<?= site_url('pharmacy/dispense/new') ?>">Dispense</a>
      <a href="<?= site_url('pharmacy/inventory') ?>">Inventory</a>
      <a href="<?= site_url('pharmacy/low-stock-alerts') ?>">Low Stock</a>
      <a href="<?= site_url('pharmacy/medicine-expiry') ?>">Expiry Alerts</a>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="content">
    <div class="card">

      <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
          <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <p class="mb-0"><?= $error ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <h2 style="color:#003b67; margin-bottom:20px;">
        <?= isset($medicine) ? 'Edit Medicine' : 'Add New Medicine' ?>
      </h2>

      <!-- FORM START -->
      <form action="<?= isset($medicine) ? site_url('pharmacy/medicines/save/' . $medicine['id']) : site_url('pharmacy/medicines/save') ?>" method="post">

        <div class="row">
          <div class="col-md-6">

            <div class="form-group">
              <label>Medicine Code *</label>
              <input type="text" class="form-control"
                name="medicine_code"
                value="<?= old('medicine_code', $medicine['medicine_code'] ?? '') ?>" required>
            </div>

            <div class="form-group">
              <label>Medicine Name *</label>
              <input type="text" class="form-control"
                name="name"
                value="<?= old('name', $medicine['name'] ?? '') ?>" required>
            </div>

            <div class="form-group">
              <label>Generic Name</label>
              <input type="text" class="form-control"
                name="generic_name"
                value="<?= old('generic_name', $medicine['generic_name'] ?? '') ?>">
            </div>

            <div class="form-group">
              <label>Brand Name</label>
              <input type="text" class="form-control"
                name="brand_name"
                value="<?= old('brand_name', $medicine['brand_name'] ?? '') ?>">
            </div>

            <div class="form-group">
              <label>Category</label>
              <select class="form-control" name="category">
                <option value="">Select</option>
                <?php foreach (['Antibiotic','Analgesic','Antiviral','Antifungal','Antihistamine','Other'] as $cat): ?>
                  <option value="<?= $cat ?>" <?= (old('category', $medicine['category'] ?? '') == $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
              </select>
            </div>

          </div>
          <div class="col-md-6">

            <div class="form-group">
              <label>Dosage Form</label>
              <select class="form-control" name="dosage_form">
                <option value="">Select</option>
                <?php foreach (['Tablet','Capsule','Syrup','Injection','Ointment','Drops','Inhaler','Other'] as $d): ?>
                  <option value="<?= $d ?>" <?= (old('dosage_form', $medicine['dosage_form'] ?? '') == $d) ? 'selected' : '' ?>><?= $d ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Strength</label>
              <input type="text" class="form-control"
                name="strength"
                value="<?= old('strength', $medicine['strength'] ?? '') ?>">
            </div>

            <div class="form-group">
              <label>Unit</label>
              <input type="text" class="form-control"
                name="unit"
                value="<?= old('unit', $medicine['unit'] ?? '') ?>">
            </div>

            <div class="form-group">
              <label>Purchase Price *</label>
              <input type="number" class="form-control"
                step="0.01" min="0"
                id="purchase_price"
                name="purchase_price"
                value="<?= old('purchase_price', $medicine['purchase_price'] ?? '0.00') ?>" required>
            </div>

            <div class="form-group">
              <label>Selling Price *</label>
              <input type="number" class="form-control"
                step="0.01" min="0"
                id="selling_price"
                name="selling_price"
                value="<?= old('selling_price', $medicine['selling_price'] ?? '0.00') ?>" required>
            </div>

          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <label>Manufacturer</label>
            <input type="text" class="form-control"
              name="manufacturer"
              value="<?= old('manufacturer', $medicine['manufacturer'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label>Supplier</label>
            <input type="text" class="form-control"
              name="supplier"
              value="<?= old('supplier', $medicine['supplier'] ?? '') ?>">
          </div>
        </div>

        <br>

        <div class="form-check">
          <input type="checkbox" class="form-check-input"
            id="requires_prescription"
            name="requires_prescription" value="1"
            <?= (old('requires_prescription', $medicine['requires_prescription'] ?? 0) == 1) ? 'checked' : '' ?>>
          <label class="form-check-label">Requires Prescription</label>
        </div>

        <div class="form-group mt-3">
          <label>Description</label>
          <textarea class="form-control" name="description" rows="2"><?= old('description', $medicine['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label>Side Effects</label>
          <textarea class="form-control" name="side_effects" rows="2"><?= old('side_effects', $medicine['side_effects'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label>Contraindications</label>
          <textarea class="form-control" name="contraindications" rows="2"><?= old('contraindications', $medicine['contraindications'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label>Storage Instructions</label>
          <textarea class="form-control" name="storage_instructions" rows="2"><?= old('storage_instructions', $medicine['storage_instructions'] ?? '') ?></textarea>
        </div>

        <div class="form-check">
          <input type="checkbox" class="form-check-input"
            id="is_active"
            name="is_active" value="1"
            <?= (old('is_active', $medicine['is_active'] ?? 1) == 1) ? 'checked' : '' ?>>
          <label class="form-check-label">Active</label>
        </div>

        <br>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> <?= isset($medicine) ? 'Update' : 'Save' ?> Medicine
        </button>

        <a href="<?= site_url('pharmacy/medicines') ?>" class="btn btn-secondary">
          <i class="fas fa-times"></i> Cancel
        </a>

      </form>
      <!-- FORM END -->

    </div>
  </main>

</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const purchasePrice = document.getElementById('purchase_price');
    const sellingPrice = document.getElementById('selling_price');

    purchasePrice.addEventListener('change', function() {
      if (!sellingPrice.value && purchasePrice.value) {
        const markup = parseFloat(purchasePrice.value) * 1.2;
        sellingPrice.value = markup.toFixed(2);
      }
    });
  });
</script>

</body>
</html>
