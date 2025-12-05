<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dispense from Prescription</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .form-container {
      max-width: 700px;
      margin: 0 auto;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-label {
      display: block;
      font-weight: 600;
      color: #000000;
      margin-bottom: 8px;
      font-size: 0.875rem;
    }
    .form-input, .form-select, .form-textarea {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 0.875rem;
      background: white;
      color: #000000;
      font-family: inherit;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .form-textarea {
      resize: vertical;
      min-height: 100px;
    }
    .btn-primary {
      background: #3b82f6;
      color: white;
      padding: 12px 24px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-primary:hover {
      background: #2563eb;
    }
    .btn-secondary {
      background: #6b7280;
      color: white;
      padding: 12px 24px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .info-card {
      background: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 16px;
      margin-bottom: 24px;
    }
    .info-item {
      margin-bottom: 12px;
    }
    .info-label {
      font-size: 0.75rem;
      color: #6b7280;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .info-value {
      font-size: 0.875rem;
      color: #000000;
      font-weight: 500;
      margin-top: 4px;
    }
    .stock-warning {
      background: #fef3c7;
      border: 1px solid #fbbf24;
      border-radius: 6px;
      padding: 12px;
      margin-bottom: 16px;
      color: #92400e;
      font-size: 0.875rem;
    }
    .stock-success {
      background: #d1fae5;
      border: 1px solid #6ee7b7;
      border-radius: 6px;
      padding: 12px;
      margin-bottom: 16px;
      color: #065f46;
      font-size: 0.875rem;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Pharmacy</h1><small>Dispense from Prescription</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Pharmacy navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/pharmacist') ?>">Overview</a>
  <a href="<?= site_url('pharmacy/prescriptions') ?>">Prescriptions</a>
  <a href="<?= site_url('pharmacy/inventory') ?>">Inventory</a>
  <a href="<?= site_url('pharmacy/low-stock-alerts') ?>">Low Stock</a>
  <a href="<?= site_url('pharmacy/medicine-expiry') ?>">Expiry Alerts</a>
</nav></aside>
  <main class="content">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="form-container">
      <a href="<?= site_url('pharmacy/prescriptions') ?>" class="btn-secondary" style="margin-bottom:20px">
        <i class="fa-solid fa-arrow-left"></i> Back to Prescriptions
      </a>

      <!-- Prescription Information -->
      <div class="info-card">
        <h3 style="margin:0 0 16px 0;font-size:1rem;color:#000000;font-weight:700">
          <i class="fa-solid fa-prescription-bottle-medical"></i> Prescription Details
        </h3>
        <div class="info-item">
          <span class="info-label">Patient</span>
          <div class="info-value"><?= esc($prescription['patient_name'] ?? 'N/A') ?></div>
        </div>
        <div class="info-item">
          <span class="info-label">Doctor</span>
          <div class="info-value"><?= esc($prescription['doctor_name'] ?? 'N/A') ?></div>
        </div>
        <div class="info-item">
          <span class="info-label">Medication</span>
          <div class="info-value"><strong><?= esc($prescription['medication'] ?? 'N/A') ?></strong></div>
        </div>
        <div class="info-item">
          <span class="info-label">Dosage</span>
          <div class="info-value"><?= esc($prescription['dosage'] ?? 'N/A') ?></div>
        </div>
        <div class="info-item">
          <span class="info-label">Frequency</span>
          <div class="info-value"><?= esc($prescription['frequency'] ?? 'N/A') ?></div>
        </div>
        <?php if (!empty($prescription['instructions'])): ?>
        <div class="info-item">
          <span class="info-label">Instructions</span>
          <div class="info-value"><?= esc($prescription['instructions']) ?></div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Stock Information -->
      <?php if ($medicine): ?>
        <?php if ($availableStock > 0): ?>
          <div class="stock-success">
            <i class="fa-solid fa-check-circle"></i> <strong>Stock Available:</strong> <?= esc($availableStock) ?> units
          </div>
        <?php else: ?>
          <div class="stock-warning">
            <i class="fa-solid fa-exclamation-triangle"></i> <strong>Out of Stock</strong> - This medicine is currently unavailable.
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="stock-warning">
          <i class="fa-solid fa-exclamation-triangle"></i> <strong>Medicine Not Found</strong> - The prescribed medicine "<?= esc($prescription['medication']) ?>" is not in the inventory. Please select a medicine manually.
        </div>
      <?php endif; ?>

      <!-- Dispense Form -->
      <form method="post" action="<?= site_url('pharmacy/dispense/from-prescription/' . $prescription['id']) ?>">
        <?= csrf_field() ?>
        
        <div class="form-group">
          <label class="form-label" for="medicine_id">
            Medicine <span style="color:#ef4444">*</span>
          </label>
          <select name="medicine_id" id="medicine_id" class="form-select" required>
            <option value="">-- Select Medicine --</option>
            <?php foreach ($medicinesWithStock ?? [] as $med): ?>
              <option value="<?= esc($med['id']) ?>" 
                      <?= ($medicine && $medicine['id'] == $med['id']) ? 'selected' : '' ?>
                      data-stock="<?= esc($med['stock'] ?? 0) ?>">
                <?= esc($med['name']) ?> 
                (Stock: <?= esc($med['stock'] ?? 0) ?>)
              </option>
            <?php endforeach; ?>
          </select>
          <small style="color:#6b7280;font-size:0.75rem;margin-top:4px;display:block">
            <i class="fa-solid fa-info-circle"></i> Select the medicine to dispense
          </small>
        </div>

        <div class="form-group">
          <label class="form-label" for="quantity">
            Quantity <span style="color:#ef4444">*</span>
          </label>
          <input type="number" 
                 name="quantity" 
                 id="quantity" 
                 class="form-input" 
                 min="1" 
                 value="1" 
                 required>
          <small style="color:#6b7280;font-size:0.75rem;margin-top:4px;display:block">
            <i class="fa-solid fa-info-circle"></i> Enter the quantity to dispense
          </small>
        </div>

        <div class="form-group">
          <label class="form-label" for="notes">
            Notes (Optional)
          </label>
          <textarea name="notes" 
                    id="notes" 
                    class="form-textarea"
                    placeholder="Additional notes about this dispensing..."></textarea>
        </div>

        <div style="display:flex;gap:12px;margin-top:32px;justify-content:flex-end">
          <a href="<?= site_url('pharmacy/prescriptions') ?>" class="btn-secondary">
            <i class="fa-solid fa-times"></i> Cancel
          </a>
          <button type="submit" id="dispenseBtn" class="btn-primary" disabled>
            <i class="fa-solid fa-prescription-bottle-medical"></i> Dispense Medicine
          </button>
        </div>
      </form>
    </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const medicineSelect = document.getElementById('medicine_id');
  const quantityInput = document.getElementById('quantity');
  const dispenseBtn = document.getElementById('dispenseBtn');
  
  function updateButtonState() {
    const selectedOption = medicineSelect.options[medicineSelect.selectedIndex];
    const medicineId = medicineSelect.value;
    const stock = parseInt(selectedOption.getAttribute('data-stock') || 0);
    const quantity = parseInt(quantityInput.value) || 0;
    
    // Enable button if medicine is selected and has stock and quantity is valid
    if (medicineId && stock > 0 && quantity > 0 && quantity <= stock) {
      dispenseBtn.disabled = false;
      dispenseBtn.style.opacity = '1';
      dispenseBtn.style.cursor = 'pointer';
    } else {
      dispenseBtn.disabled = true;
      dispenseBtn.style.opacity = '0.5';
      dispenseBtn.style.cursor = 'not-allowed';
    }
  }
  
  medicineSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const stock = parseInt(selectedOption.getAttribute('data-stock') || 0);
    
    if (stock > 0) {
      quantityInput.max = stock;
      quantityInput.value = Math.min(parseInt(quantityInput.value) || 1, stock);
    } else {
      quantityInput.max = '';
      quantityInput.value = 1;
    }
    updateButtonState();
  });
  
  quantityInput.addEventListener('input', function() {
    updateButtonState();
  });
  
  // Initial state check
  updateButtonState();
});
</script>
</body></html>

