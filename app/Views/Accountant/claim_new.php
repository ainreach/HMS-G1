<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Insurance Claim</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Insurance Claims</h1><small>Submit • Track • Reconcile</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout">
<?= $this->include('Accountant/sidebar', ['currentPage' => 'insurance']) ?>
  <main class="content" style="padding-bottom:40px">
    <?php if (session('success')): ?>
      <div class="alert alert-success" style="margin-bottom:1rem;padding:12px 16px;background:#d1fae5;color:#065f46;border-radius:8px;border:1px solid #a7f3d0">
        <?= esc(session('success')) ?>
      </div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div class="alert alert-error" style="margin-bottom:1rem;padding:12px 16px;background:#fee2e2;color:#991b1b;border-radius:8px;border:1px solid #fecaca">
        <?= esc(session('error')) ?>
      </div>
    <?php endif; ?>

    <section class="panel" style="margin-bottom:32px;background:#ffffff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);overflow:hidden;border-top:4px solid #0ea5e9">
      <div class="panel-head" style="background:linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);color:white;padding:20px 24px;display:flex;justify-content:space-between;align-items:center">
        <div>
          <h2 style="margin:0;font-size:1.25rem;font-weight:700;display:flex;align-items:center;gap:12px">
            <i class="fa-solid fa-shield-halved" style="font-size:1.5rem"></i>
            New Insurance Claim
          </h2>
          <p style="margin:8px 0 0 0;font-size:0.875rem;opacity:0.9">Submit a new insurance claim for patient billing</p>
        </div>
        <a href="<?= site_url('accountant/insurance') ?>" style="padding:10px 20px;background:white;color:#0ea5e9;text-decoration:none;border-radius:8px;font-size:0.875rem;font-weight:600;display:inline-flex;align-items:center;gap:8px;transition:all 0.2s;box-shadow:0 2px 4px rgba(0,0,0,0.1)" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
          <i class="fa-solid fa-arrow-left"></i> Back to Claims
        </a>
      </div>
      <div class="panel-body" style="padding:24px">
        <div style="background:#dbeafe;border-left:4px solid #0ea5e9;padding:16px;border-radius:8px;margin-bottom:20px">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
            <i class="fa-solid fa-info-circle" style="font-size:1.25rem;color:#0284c7"></i>
            <div style="font-weight:700;color:#1e40af;font-size:1rem">Insurance Claim Submission</div>
          </div>
          <p style="margin:0;font-size:0.875rem;color:#1e3a8a">
            Fill in all required fields to submit an insurance claim. The claim will be linked to the selected patient and billing record.
          </p>
        </div>
        
        <form method="post" action="<?= site_url('accountant/claims') ?>" style="display:flex;flex-direction:column;gap:20px;max-width:800px">
          <?= csrf_field() ?>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Patient <span style="color:#dc2626">*</span></label>
              <select name="patient_id" id="patientSelect" required style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem" onchange="updateBillingRecords()">
                <option value="">Select patient...</option>
                <?php if (!empty($patients)): ?>
                  <?php foreach ($patients as $patient): ?>
                    <?php
                      $patientName = trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''));
                      $patientId = $patient['id'] ?? '';
                      $patientCode = $patient['patient_id'] ?? 'N/A';
                    ?>
                    <option value="<?= esc($patientId) ?>" data-name="<?= esc($patientName) ?>">
                      <?= esc($patientName) ?> (ID: <?= esc($patientCode) ?>)
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Billing Record <span style="color:#dc2626">*</span></label>
              <select name="billing_id" id="billingSelect" required style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem" onchange="updateInvoiceAndAmount()">
                <option value="">Select billing record...</option>
                <?php if (!empty($billings)): ?>
                  <?php foreach ($billings as $billing): ?>
                    <option value="<?= esc($billing['id']) ?>" 
                            data-patient-id="<?= esc($billing['patient_id'] ?? '') ?>"
                            data-amount="<?= number_format($billing['total_amount'] ?? 0, 2, '.', '') ?>"
                            data-invoice="<?= esc($billing['invoice_number'] ?? '') ?>"
                            data-balance="<?= number_format($billing['balance'] ?? 0, 2, '.', '') ?>">
                      Bill #<?= esc($billing['id']) ?> - <?= esc(trim(($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? ''))) ?> - $<?= number_format($billing['total_amount'] ?? 0, 2) ?>
                      <?= !empty($billing['invoice_number']) ? ' [Invoice: ' . esc($billing['invoice_number']) . ']' : '' ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Invoice Number</label>
              <input type="text" name="invoice_no" id="invoiceNo" readonly style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem;background:#f9fafb" placeholder="Auto-filled from billing record">
            </div>
            
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Claim Number</label>
              <input type="text" name="claim_no" id="claimNo" style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem" placeholder="Auto-generated if left empty">
            </div>
          </div>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Insurance Provider <span style="color:#dc2626">*</span></label>
              <select name="provider" required style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem">
                <option value="">Select provider...</option>
                <option value="Blue Cross Blue Shield">Blue Cross Blue Shield</option>
                <option value="Aetna">Aetna</option>
                <option value="Cigna">Cigna</option>
                <option value="UnitedHealthcare">UnitedHealthcare</option>
                <option value="Humana">Humana</option>
                <option value="Medicare">Medicare</option>
                <option value="Medicaid">Medicaid</option>
                <option value="Kaiser Permanente">Kaiser Permanente</option>
                <option value="Anthem">Anthem</option>
                <option value="Other">Other</option>
              </select>
            </div>
            
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Policy Number <span style="color:#dc2626">*</span></label>
              <input type="text" name="policy_no" required style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem" placeholder="Enter policy number">
            </div>
          </div>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Amount Claimed <span style="color:#dc2626">*</span></label>
              <input type="number" name="amount_claimed" id="amountClaimed" step="0.01" min="0" required style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem" placeholder="0.00" onchange="validateAmount()">
              <small style="color:#6b7280;font-size:0.75rem;margin-top:4px;display:block">Based on billing balance</small>
            </div>
            
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Amount Approved</label>
              <input type="number" name="amount_approved" step="0.01" min="0" style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem" placeholder="0.00" value="0">
              <small style="color:#6b7280;font-size:0.75rem;margin-top:4px;display:block">Leave 0 if pending approval</small>
            </div>
          </div>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Status</label>
              <select name="status" style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem">
                <option value="submitted" selected>Submitted</option>
                <option value="pending">Pending</option>
                <option value="in_review">In Review</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="paid">Paid</option>
              </select>
            </div>
            
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Submitted Date</label>
              <input type="datetime-local" name="submitted_at" value="<?= date('Y-m-d\TH:i') ?>" style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem">
            </div>
          </div>
          
          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Notes</label>
            <textarea name="notes" rows="3" placeholder="Additional notes about this claim..." style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.875rem;resize:vertical"></textarea>
          </div>
          
          <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" style="flex:1;background:linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);color:white;padding:14px 20px;border-radius:8px;border:none;cursor:pointer;font-weight:600;font-size:1rem;display:flex;align-items:center;justify-content:center;gap:8px">
              <i class="fa-solid fa-check"></i> Submit Claim
            </button>
            <a href="<?= site_url('accountant/insurance') ?>" style="background:#6b7280;color:white;padding:14px 20px;border-radius:8px;border:none;cursor:pointer;font-weight:500;text-decoration:none;display:flex;align-items:center;justify-content:center">Cancel</a>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>

<script>
function updateBillingRecords() {
  const patientSelect = document.getElementById('patientSelect');
  const billingSelect = document.getElementById('billingSelect');
  const selectedPatientId = patientSelect.value;
  
  // Filter billing records by selected patient
  if (selectedPatientId) {
    const options = billingSelect.querySelectorAll('option');
    options.forEach(option => {
      if (option.value === '') {
        option.style.display = 'block';
        return;
      }
      const optionPatientId = option.getAttribute('data-patient-id');
      if (optionPatientId === selectedPatientId) {
        option.style.display = 'block';
      } else {
        option.style.display = 'none';
      }
    });
    billingSelect.value = '';
    updateInvoiceAndAmount();
  } else {
    // Show all billing records
    const options = billingSelect.querySelectorAll('option');
    options.forEach(option => {
      option.style.display = 'block';
    });
    billingSelect.value = '';
    updateInvoiceAndAmount();
  }
}

function updateInvoiceAndAmount() {
  const billingSelect = document.getElementById('billingSelect');
  const invoiceNo = document.getElementById('invoiceNo');
  const amountClaimed = document.getElementById('amountClaimed');
  const selectedOption = billingSelect.options[billingSelect.selectedIndex];
  
  if (selectedOption && selectedOption.value) {
    const invoice = selectedOption.getAttribute('data-invoice');
    const balance = selectedOption.getAttribute('data-balance');
    const totalAmount = selectedOption.getAttribute('data-amount');
    
    invoiceNo.value = invoice || '';
    // Use balance if available, otherwise use total amount
    amountClaimed.value = balance && parseFloat(balance) > 0 ? balance : totalAmount;
  } else {
    invoiceNo.value = '';
    amountClaimed.value = '';
  }
}

function validateAmount() {
  const amountClaimed = document.getElementById('amountClaimed');
  const value = parseFloat(amountClaimed.value);
  if (value < 0) {
    amountClaimed.value = '0';
    alert('Amount cannot be negative.');
  }
}
</script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
