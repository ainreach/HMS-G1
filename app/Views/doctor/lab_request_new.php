<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Laboratory Request</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar">
  <div class="topbar-inner">
    <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn"><i class="fa-solid fa-arrow-left"></i></a>
    <h1 style="margin:0;font-size:1.1rem">New Laboratory Request</h1>
  </div>
</header>

<main class="content" style="max-width:980px;margin:20px auto">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <section class="panel">
    <div class="panel-head">
      <h2 style="margin:0;font-size:1rem">Request Information</h2>
    </div>
    <div class="panel-body">
      <form method="post" action="<?= site_url('doctor/lab-requests') ?>" class="form">
        <?= csrf_field() ?>

        <div class="grid" style="display:grid;grid-template-columns:2fr 1.5fr;gap:1.25rem;align-items:flex-start">
          <div>
            <label>Patient Record *</label>
            <select name="patient_id" id="patient_select" required onchange="checkPaymentStatus()">
              <option value="">Choose patient...</option>
              <?php if (!empty($patients)): ?>
                <?php foreach ($patients as $p): ?>
                  <?php 
                    $pid = (int)($p['id'] ?? 0);
                    $paymentInfo = $payment_status[$pid] ?? ['has_paid' => false, 'unpaid_amount' => 0, 'can_request_lab' => false];
                    $statusText = $paymentInfo['has_paid'] ? '✓ Paid' : ($paymentInfo['unpaid_amount'] > 0 ? '⚠ Unpaid: ₱' . number_format($paymentInfo['unpaid_amount'], 2) : '⚠ No Payment');
                  ?>
                  <option value="<?= $pid ?>" 
                          data-has-paid="<?= $paymentInfo['has_paid'] ? '1' : '0' ?>"
                          data-unpaid="<?= $paymentInfo['unpaid_amount'] ?>"
                          data-can-request="<?= $paymentInfo['can_request_lab'] ? '1' : '0' ?>"
                          <?= old('patient_id')==$pid ? 'selected' : '' ?>>
                    <?= esc(($p['last_name'] ?? '') . ', ' . ($p['first_name'] ?? '') . ' [' . ($p['patient_id'] ?? 'N/A') . '] - ' . $statusText) ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
            <div id="payment_warning" style="display:none;margin-top:.5rem;padding:.75rem;background:#fff3cd;border:1px solid #ffc107;border-radius:4px;color:#856404;">
              <i class="fa-solid fa-exclamation-triangle"></i>
              <strong>Payment Required:</strong> <span id="payment_message"></span>
            </div>

            <label style="margin-top:.75rem">Requesting Doctor</label>
            <select name="doctor_id">
              <option value="">Use logged-in doctor</option>
              <?php if (!empty($doctors ?? [])): ?>
                <?php foreach ($doctors as $d): ?>
                  <?php $did = (int)($d['id'] ?? 0); ?>
                  <option value="<?= $did ?>" <?= old('doctor_id')==$did ? 'selected' : '' ?>>
                    <?= esc(($d['last_name'] ?? '') . ', ' . ($d['first_name'] ?? '') . ' (' . ($d['specialization'] ?? 'Doctor') . ')') ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <div style="display:grid;grid-template-columns:1fr;gap:.5rem">
            <label>Requested On *</label>
            <?php $today = old('request_date', date('Y-m-d')); ?>
            <input type="date" name="request_date" value="<?= $today ?>" required>

            <div>
              <span style="display:block;margin-bottom:.25rem;font-size:.85rem">Urgency</span>
              <?php $priorityOld = old('priority','routine'); ?>
              <label style="margin-right:.75rem;font-size:.85rem">
                <input type="radio" name="priority" value="routine" <?= $priorityOld==='routine' ? 'checked' : '' ?>> Routine
              </label>
              <label style="margin-right:.75rem;font-size:.85rem">
                <input type="radio" name="priority" value="urgent" <?= $priorityOld==='urgent' ? 'checked' : '' ?>> Urgent
              </label>
              <label style="font-size:.85rem">
                <input type="radio" name="priority" value="stat" <?= $priorityOld==='stat' ? 'checked' : '' ?>> STAT
              </label>
            </div>
          </div>
        </div>

        <div style="margin-top:1.5rem">
          <h3 style="margin:0 0 .5rem;font-size:.95rem">Requested Tests *</h3>
          <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1rem">
            <?php
              $oldTests = (array) old('tests', []);
              $panels = [
                'Blood Panel' => [
                  ['name' => 'Fasting Glucose', 'price' => 220.00],
                  ['name' => 'Serum Creatinine', 'price' => 200.00],
                  ['name' => 'Liver Profile (AST/ALT)', 'price' => 500.00],
                  ['name' => 'Lipid Profile (Cholesterol/Triglycerides)', 'price' => 380.00],
                ],
                'Hematology Panel' => [
                  ['name' => 'Complete Blood Count', 'price' => 250.00],
                  ['name' => 'Packed Cell Volume', 'price' => 150.00],
                  ['name' => 'Hemoglobin Level', 'price' => 150.00],
                ],
                'Microbiology / Others' => [
                  ['name' => 'Blood Culture Study', 'price' => 500.00],
                  ['name' => 'Urine Culture & Sensitivity', 'price' => 400.00],
                  ['name' => 'Other microbiology work-up', 'price' => 0.00],
                ],
              ];
            ?>

            <?php foreach ($panels as $groupName => $tests): ?>
              <div style="border:1px solid #e5e7eb;border-radius:8px;padding:.75rem">
                <strong style="font-size:.9rem;display:block;margin-bottom:.25rem"><?= esc($groupName) ?></strong>
                <?php foreach ($tests as $t): ?>
                  <?php $testName = $t['name']; $price = (float) $t['price']; ?>
                  <label style="display:flex;align-items:flex-start;gap:.5rem;margin-bottom:.25rem;font-size:.85rem">
                    <input type="checkbox" name="tests[]" value="<?= esc($testName) ?>" <?= in_array($testName, $oldTests, true) ? 'checked' : '' ?>>
                    <span>
                      <?= esc($testName) ?><br>
                      <?php if ($price > 0): ?>
                        <small style="color:#6b7280">₱<?= number_format($price, 2) ?></small>
                      <?php else: ?>
                        <small style="color:#9ca3af">Price varies</small>
                      <?php endif; ?>
                    </span>
                  </label>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          </div>
          <small style="display:block;margin-top:.5rem;color:#6b7280">Tick one or more investigations to include in this request.</small>
        </div>

        <div style="margin-top:1.5rem">
          <label>Clinical Notes / Instructions
            <textarea name="notes" rows="3"><?= old('notes') ?></textarea>
          </label>
        </div>

        <div style="margin-top:1.25rem;display:flex;justify-content:flex-end;gap:.75rem">
          <a href="<?= site_url('dashboard/doctor') ?>" class="btn btn-secondary">Back to dashboard</a>
          <button type="submit" id="submit_btn" class="btn btn-primary"><i class="fa-solid fa-flask"></i>&nbsp;Submit Request</button>
        </div>
      </form>
    </div>
  </section>
</main>

<script>
function checkPaymentStatus() {
  const select = document.getElementById('patient_select');
  const warning = document.getElementById('payment_warning');
  const message = document.getElementById('payment_message');
  const submitBtn = document.getElementById('submit_btn');
  const selectedOption = select.options[select.selectedIndex];
  
  if (!selectedOption || selectedOption.value === '') {
    warning.style.display = 'none';
    submitBtn.disabled = false;
    return;
  }
  
  const hasPaid = selectedOption.getAttribute('data-has-paid') === '1';
  const unpaidAmount = parseFloat(selectedOption.getAttribute('data-unpaid')) || 0;
  const canRequest = selectedOption.getAttribute('data-can-request') === '1';
  
  if (!canRequest && !hasPaid) {
    if (unpaidAmount > 0) {
      message.textContent = 'Patient must pay registration fee first. Unpaid amount: ₱' + unpaidAmount.toFixed(2);
    } else {
      message.textContent = 'Patient must pay registration fee first before lab test can be requested.';
    }
    warning.style.display = 'block';
    submitBtn.disabled = true;
  } else {
    warning.style.display = 'none';
    submitBtn.disabled = false;
  }
}

// Check on page load if patient is pre-selected
document.addEventListener('DOMContentLoaded', function() {
  checkPaymentStatus();
});
</script>
      </form>
    </div>
  </section>
</main>

</body>
</html>
