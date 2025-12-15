<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Apply Insurance - Consolidated Billing</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('accountant/consolidated-bill/' . $patient['id']) ?>" class="menu-btn" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Apply Insurance</h1>
        <small>Apply insurance coverage to patient's bill</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
      <a href="<?= site_url('logout') ?>" class="logout-btn">Logout</a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/accountant') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
      <a href="<?= site_url('accountant/consolidated-bills') ?>" class="active"><i class="fa-solid fa-file-invoice" style="margin-right:8px"></i>Consolidated Bills</a>
      <a href="<?= site_url('accountant/billing') ?>"><i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px"></i>Billing & Payments</a>
    </nav>
  </aside>

  <main class="content">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Apply Insurance Coverage</h2>
        <small style="color:#6b7280">Patient: <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?> (<?= esc($patient['patient_id'] ?? 'N/A') ?>)</small>
      </div>
      <div class="panel-body">
        <?php if ($bill): ?>
        <div style="background:#f8fafc;padding:16px;border-radius:8px;margin-bottom:16px">
          <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="color:#6b7280">Total Bill Amount:</span>
            <strong>$<?= number_format((float)($bill['total_amount'] ?? 0), 2) ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:8px">
            <span style="color:#6b7280">Current Balance:</span>
            <strong style="color:#ef4444">$<?= number_format((float)($bill['balance'] ?? 0), 2) ?></strong>
          </div>
          <?php if (!empty($bill['insurance_claim_number'])): ?>
          <div style="display:flex;justify-content:space-between;margin-top:8px;padding-top:8px;border-top:1px solid #e5e7eb">
            <span style="color:#6b7280">Current Insurance Claim:</span>
            <strong><?= esc($bill['insurance_claim_number']) ?></strong>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('accountant/apply-insurance/' . $patient['id']) ?>" id="insuranceForm" style="display:grid;gap:16px">
          <div>
            <label>Insurance Provider *
              <select name="insurance_provider" id="insurance_provider" class="form-control" required onchange="setInsurancePercentage()">
                <option value="">-- Select Insurance Provider --</option>
                <option value="PhilHealth" data-percentage="30" <?= ($bill['insurance_provider'] ?? $patient['insurance_provider'] ?? '') === 'PhilHealth' ? 'selected' : '' ?>>PhilHealth (30%)</option>
                <option value="Maxicare" data-percentage="50" <?= ($bill['insurance_provider'] ?? $patient['insurance_provider'] ?? '') === 'Maxicare' ? 'selected' : '' ?>>Maxicare (50%)</option>
                <option value="Medicard" data-percentage="70" <?= ($bill['insurance_provider'] ?? $patient['insurance_provider'] ?? '') === 'Medicard' ? 'selected' : '' ?>>Medicard (70%)</option>
                <option value="Intellicare" data-percentage="60" <?= ($bill['insurance_provider'] ?? $patient['insurance_provider'] ?? '') === 'Intellicare' ? 'selected' : '' ?>>Intellicare (60%)</option>
                <option value="Pacific Cross" data-percentage="80" <?= ($bill['insurance_provider'] ?? $patient['insurance_provider'] ?? '') === 'Pacific Cross' ? 'selected' : '' ?>>Pacific Cross (80%)</option>
                <option value="Generali" data-percentage="65" <?= ($bill['insurance_provider'] ?? $patient['insurance_provider'] ?? '') === 'Generali' ? 'selected' : '' ?>>Generali (65%)</option>
                <option value="Other" data-percentage="0" <?= ($bill['insurance_provider'] ?? $patient['insurance_provider'] ?? '') === 'Other' ? 'selected' : '' ?>>Other / Not Listed</option>
              </select>
              <small style="color:#6b7280;font-size:0.875rem">Select insurance provider - percentage will be auto-filled</small>
            </label>
          </div>
          <div>
            <label>Insurance Claim Number
              <input type="text" name="insurance_claim_number" class="form-control" 
                     value="<?= esc($bill['insurance_claim_number'] ?? '') ?>" 
                     placeholder="Enter insurance claim number">
            </label>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div>
              <label>Coverage Percentage (%)
                <input type="number" name="insurance_percentage" id="insurance_percentage" class="form-control" 
                       step="0.01" min="0" max="100" 
                       placeholder="0.00" 
                       oninput="calculateInsuranceAmount()">
                <small style="color:#6b7280;font-size:0.875rem">Auto-filled from provider selection (can be adjusted)</small>
              </label>
            </div>
            <div>
              <label>Coverage Amount ($)
                <input type="number" name="insurance_amount" id="insurance_amount" class="form-control" 
                       step="0.01" min="0" 
                       placeholder="0.00" 
                       oninput="calculateInsurancePercentage()">
                <small style="color:#6b7280;font-size:0.875rem">Or enter fixed amount covered</small>
              </label>
            </div>
          </div>
          <div style="background:#e0e7ff;padding:12px;border-radius:6px;border-left:4px solid #6366f1">
            <div style="display:flex;justify-content:space-between;margin-bottom:4px">
              <span style="color:#4f46e5;font-weight:600">Total Bill:</span>
              <strong style="color:#4f46e5" id="total_bill_display">$<?= number_format((float)($bill['total_amount'] ?? 0), 2) ?></strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:4px">
              <span style="color:#6366f1">Insurance Coverage:</span>
              <strong style="color:#6366f1" id="coverage_display">$0.00</strong>
            </div>
            <div style="display:flex;justify-content:space-between;padding-top:8px;border-top:1px solid #c7d2fe">
              <span style="color:#1e40af;font-weight:600">New Balance:</span>
              <strong style="color:#1e40af;font-size:1.1rem" id="new_balance_display">$<?= number_format((float)($bill['balance'] ?? $bill['total_amount'] ?? 0), 2) ?></strong>
            </div>
          </div>
          <div>
            <label>Notes
              <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about insurance coverage"><?= esc($bill['notes'] ?? '') ?></textarea>
            </label>
          </div>
          <div style="display:flex;gap:12px">
            <button type="submit" class="btn" style="background:#8b5cf6;color:white;padding:10px 20px;border-radius:6px;border:none;cursor:pointer">
              <i class="fa-solid fa-shield-halved"></i> Apply Insurance
            </button>
            <a href="<?= site_url('accountant/consolidated-bill/' . $patient['id']) ?>" class="btn" style="background:#6b7280;color:white;text-decoration:none;padding:10px 20px;border-radius:6px">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>
<script>
const totalBill = <?= (float)($bill['total_amount'] ?? 0) ?>;
const currentBalance = <?= (float)($bill['balance'] ?? $bill['total_amount'] ?? 0) ?>;

function calculateInsuranceAmount() {
  const percentage = parseFloat(document.getElementById('insurance_percentage').value) || 0;
  const amount = (totalBill * percentage) / 100;
  document.getElementById('insurance_amount').value = amount.toFixed(2);
  updateDisplay();
}

function calculateInsurancePercentage() {
  const amount = parseFloat(document.getElementById('insurance_amount').value) || 0;
  const percentage = totalBill > 0 ? (amount / totalBill) * 100 : 0;
  document.getElementById('insurance_percentage').value = percentage.toFixed(2);
  updateDisplay();
}

function setInsurancePercentage() {
  const select = document.getElementById('insurance_provider');
  const selectedOption = select.options[select.selectedIndex];
  const percentage = selectedOption ? parseFloat(selectedOption.getAttribute('data-percentage') || 0) : 0;
  
  if (percentage > 0) {
    document.getElementById('insurance_percentage').value = percentage;
    calculateInsuranceAmount();
  } else if (selectedOption && selectedOption.value === 'Other') {
    // Clear percentage for "Other" so user can enter manually
    document.getElementById('insurance_percentage').value = '';
    document.getElementById('insurance_amount').value = '';
    updateDisplay();
  }
}

function updateDisplay() {
  const coverageAmount = parseFloat(document.getElementById('insurance_amount').value) || 0;
  const newBalance = Math.max(0, currentBalance - coverageAmount);
  
  document.getElementById('coverage_display').textContent = '$' + coverageAmount.toFixed(2);
  document.getElementById('new_balance_display').textContent = '$' + newBalance.toFixed(2);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  // Pre-fill from patient's insurance if available
  const patientInsurance = '<?= esc($patient['insurance_provider'] ?? '') ?>';
  if (patientInsurance) {
    const select = document.getElementById('insurance_provider');
    for (let i = 0; i < select.options.length; i++) {
      if (select.options[i].value === patientInsurance) {
        select.selectedIndex = i;
        setInsurancePercentage(); // Auto-set percentage
        break;
      }
    }
  }
  
  updateDisplay();
});
</script>
</body>
</html>

