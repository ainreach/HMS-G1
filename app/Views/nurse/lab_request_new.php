<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Lab Request | Nurse</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .nurse-page-container {
        padding: 0;
    }
    
    .page-header {
        background: linear-gradient(135deg, #0288d1 0%, #03a9f4 100%);
        border-radius: 16px;
        padding: 24px 32px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(2, 136, 209, 0.2);
        color: white;
    }
    
    .page-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .modern-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        margin-bottom: 24px;
    }
    
    .card-body-modern {
        padding: 32px;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        display: block;
    }
    
    .form-control, .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #0288d1;
        box-shadow: 0 0 0 3px rgba(2, 136, 209, 0.1);
    }
    
    .btn-modern {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        cursor: pointer;
    }
    
    .btn-modern-primary {
        background: linear-gradient(135deg, #0288d1 0%, #03a9f4 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(2, 136, 209, 0.3);
    }
    
    .btn-modern-secondary {
        background: #64748b;
        color: white;
    }
    
    .text-danger {
        color: #ef4444;
        font-size: 13px;
        margin-top: 4px;
    }
    
    .test-info-box {
        margin-top: 8px;
        padding: 12px;
        background: #f1f5f9;
        border-radius: 8px;
        color: #475569;
        font-size: 13px;
        display: none;
    }
    
    .test-info-box strong {
        color: #1e293b;
    }
  </style>
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">New Lab Request</h1>
        <small>Request laboratory tests for patients</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
      <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
    </div>
  </div>
</header>

<div class="layout">
  <?= $this->include('nurse/sidebar') ?>
  
  <main class="content">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <div class="nurse-page-container">
      <div class="page-header">
        <h1>
          <i class="fas fa-vial"></i>
          New Lab Request
        </h1>
      </div>
      
      <!-- Create Request Form -->
      <div class="modern-card">
        <div class="card-body-modern">
          <h3 style="margin-bottom: 24px; color: #1e293b;">New Lab Request</h3>
          <form action="<?= site_url('nurse/lab-request') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
              <div class="form-group">
                <label for="patient_id" class="form-label">Patient *</label>
                <select class="form-select" id="patient_id" name="patient_id" required onchange="updatePaymentStatus()">
                  <option value="">Select Patient</option>
                  <?php foreach ($patients as $patient): ?>
                    <?php 
                      $pid = (int)($patient['id'] ?? 0);
                      $paymentInfo = $payment_status[$pid] ?? ['has_paid' => false, 'unpaid_amount' => 0, 'can_request_lab' => false];
                    ?>
                    <option value="<?= $pid ?>" 
                            data-has-paid="<?= $paymentInfo['has_paid'] ? '1' : '0' ?>"
                            data-unpaid="<?= $paymentInfo['unpaid_amount'] ?>"
                            data-can-request="<?= $paymentInfo['can_request_lab'] ? '1' : '0' ?>"
                            <?= old('patient_id') == $pid ? 'selected' : '' ?>>
                      <?= esc(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '') . ' [' . ($patient['patient_id'] ?? 'N/A') . ']') ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div id="payment_status_msg" style="margin-top: 8px; display: none;"></div>
                <?php if (isset($validation) && $validation->getError('patient_id')): ?>
                  <div class="text-danger"><?= $validation->getError('patient_id') ?></div>
                <?php endif; ?>
              </div>
              
              <div class="form-group">
                <label for="requested_date" class="form-label">Requested Date *</label>
                <input type="date" class="form-control" id="requested_date" name="requested_date" 
                       value="<?= old('requested_date', date('Y-m-d')) ?>" required>
                <?php if (isset($validation) && $validation->getError('requested_date')): ?>
                  <div class="text-danger"><?= $validation->getError('requested_date') ?></div>
                <?php endif; ?>
              </div>
            </div>
            
            <div class="form-group">
              <label for="test_name" class="form-label">Test Name *</label>
              <select class="form-select" id="test_name" name="test_name" required onchange="updateLabTestInfo(this)" style="font-size: 15px;">
                <option value="">-- Select Lab Test --</option>
                <?php
                $categoryLabels = [
                    'with_specimen' => '🔬 With Specimen (Requires Physical Specimen)',
                    'without_specimen' => '📋 Without Specimen (No Physical Specimen Needed)'
                ];
                
                // Ensure both categories are shown, even if empty
                $allCategories = ['with_specimen', 'without_specimen'];
                ?>
                <?php if (!empty($labTests)): ?>
                  <?php foreach ($allCategories as $category): ?>
                    <?php if (isset($labTests[$category]) && is_array($labTests[$category]) && !empty($labTests[$category])): ?>
                      <optgroup label="<?= esc($categoryLabels[$category] ?? ucfirst(str_replace('_', ' ', $category))) ?>">
                      <?php foreach ($labTests[$category] as $testType => $tests): ?>
                        <?php if (is_array($tests)): ?>
                          <?php foreach ($tests as $test): ?>
                            <?php if (is_array($test)): ?>
                              <option value="<?= esc($test['test_name']) ?>" 
                                  data-type="<?= esc($test['test_type']) ?>"
                                  data-specimen-category="<?= esc($test['specimen_category'] ?? 'with_specimen') ?>"
                                  data-description="<?= esc($test['description'] ?? '') ?>"
                                  data-normal-range="<?= esc($test['normal_range'] ?? '') ?>"
                                  data-price="<?= esc($test['price'] ?? 0) ?>"
                                  <?= old('test_name') == $test['test_name'] ? 'selected' : '' ?>>
                                <?= esc($test['test_name']) ?> (<?= esc($test['test_type']) ?>) - ₱<?= number_format($test['price'] ?? 0, 2) ?>
                              </option>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      <?php endforeach; ?>
                      </optgroup>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="" disabled>No lab tests available. Please add lab tests to the catalog.</option>
                <?php endif; ?>
              </select>
              <input type="hidden" id="test_type" name="test_type" value="<?= old('test_type') ?>">
              <div id="labTestInfo" class="test-info-box">
                <strong>Type:</strong> <span id="labTestType"></span><br>
                <strong>Description:</strong> <span id="labTestDescription"></span><br>
                <strong>Normal Range:</strong> <span id="labTestNormalRange"></span><br>
                <strong>Price:</strong> ₱<span id="labTestPrice"></span>
              </div>
              <?php if (isset($validation) && $validation->getError('test_name')): ?>
                <div class="text-danger"><?= $validation->getError('test_name') ?></div>
              <?php endif; ?>
            </div>
            
            <div class="row" style="display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 20px;">
              <div class="form-group">
                <label for="priority" class="form-label">Priority *</label>
                <select class="form-select" id="priority" name="priority" required>
                  <option value="routine" <?= old('priority') == 'routine' ? 'selected' : '' ?>>Routine</option>
                  <option value="urgent" <?= old('priority') == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                  <option value="stat" <?= old('priority') == 'stat' ? 'selected' : '' ?>>Stat</option>
                </select>
                <?php if (isset($validation) && $validation->getError('priority')): ?>
                  <div class="text-danger"><?= $validation->getError('priority') ?></div>
                <?php endif; ?>
              </div>
            </div>
            
            <div class="form-group">
              <label for="instructions" class="form-label">Instructions</label>
              <textarea class="form-control" id="instructions" name="instructions" rows="4" placeholder="Special instructions for the lab..."><?= old('instructions') ?></textarea>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px;">
              <button type="reset" class="btn-modern btn-modern-secondary">
                <i class="fas fa-redo"></i>
                Reset
              </button>
              <button type="submit" id="submit_btn" class="btn-modern btn-modern-primary">
                <i class="fas fa-save"></i>
                Create Request
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</div>

<script>
const paymentStatusData = <?= json_encode($payment_status ?? []) ?>;

function updatePaymentStatus() {
  const patientSelect = document.getElementById('patient_id');
  const selectedPatientId = parseInt(patientSelect.value);
  const statusMsg = document.getElementById('payment_status_msg');
  const submitBtn = document.getElementById('submit_btn');
  
  if (!selectedPatientId) {
    statusMsg.style.display = 'none';
    submitBtn.disabled = false;
    return;
  }
  
  const status = paymentStatusData[selectedPatientId];
  if (status) {
    // Allow if can_request_lab is true OR unpaid_amount is 0
    if (status.can_request_lab || (status.unpaid_amount === 0 || status.unpaid_amount === 0.00)) {
      if (status.has_paid) {
        statusMsg.innerHTML = '<span style="color: #10b981; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> Patient has paid registration fee.</span>';
      } else {
        statusMsg.innerHTML = '<span style="color: #10b981; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> Patient has no unpaid amount. Lab test can be requested.</span>';
      }
      statusMsg.style.display = 'block';
      submitBtn.disabled = false;
    } else {
      statusMsg.innerHTML = `<span style="color: #ef4444; font-weight: 600;"><i class="fa-solid fa-exclamation-triangle"></i> Patient has unpaid amount: ₱${status.unpaid_amount.toFixed(2)}. Lab test cannot be requested.</span>`;
      statusMsg.style.display = 'block';
      submitBtn.disabled = true;
    }
  } else {
    // If no status data, allow by default (might be new patient)
    statusMsg.innerHTML = '<span style="color: #3b82f6; font-weight: 600;"><i class="fa-solid fa-info-circle"></i> No payment information found. Proceeding with request.</span>';
    statusMsg.style.display = 'block';
    submitBtn.disabled = false;
  }
}

function updateLabTestInfo(select) {
  const option = select.options[select.selectedIndex];
  const infoDiv = document.getElementById('labTestInfo');
  const typeSpan = document.getElementById('labTestType');
  const descSpan = document.getElementById('labTestDescription');
  const rangeSpan = document.getElementById('labTestNormalRange');
  const priceSpan = document.getElementById('labTestPrice');
  const testTypeInput = document.getElementById('test_type');
  
  if (option.value && option.dataset.type) {
    typeSpan.textContent = option.dataset.type || 'N/A';
    descSpan.textContent = option.dataset.description || 'N/A';
    rangeSpan.textContent = option.dataset.normalRange || 'N/A';
    priceSpan.textContent = parseFloat(option.dataset.price || 0).toFixed(2);
    testTypeInput.value = option.dataset.type || '';
    infoDiv.style.display = 'block';
  } else {
    infoDiv.style.display = 'none';
    testTypeInput.value = '';
  }
}

// Initial check
updatePaymentStatus();
</script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>

