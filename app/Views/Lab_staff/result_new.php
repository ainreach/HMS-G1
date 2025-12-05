<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Enter Lab Result</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
  <main class="content" style="max-width:720px;margin:20px auto;padding:16px">
    <h1 style="margin:0 0 12px">Enter Lab Result</h1>
    <?php if (session('error')): ?>
      <div class="alert alert-error"><?= esc(session('error')) ?></div>
    <?php endif; ?>
    <?php if (session('success')): ?>
      <div class="alert alert-success"><?= esc(session('success')) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($selectedTest)): ?>
      <!-- Patient Information Card -->
      <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:16px;margin-bottom:20px">
        <h3 style="margin:0 0 12px;font-size:1rem;color:#1e293b">Patient Information</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:0.875rem">
          <div>
            <strong>Patient Name:</strong><br>
            <?= esc($selectedTest['first_name'] . ' ' . $selectedTest['last_name']) ?>
          </div>
          <div>
            <strong>Patient ID:</strong><br>
            <?= esc($selectedTest['patient_code'] ?? 'N/A') ?>
          </div>
          <div>
            <strong>Test Name:</strong><br>
            <?= esc($selectedTest['test_name'] ?? 'N/A') ?>
          </div>
          <div>
            <strong>Test Number:</strong><br>
            <?= esc($selectedTest['test_number'] ?? 'N/A') ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
    
    <form method="post" action="<?= site_url('lab/results') ?>">
      <?= csrf_field() ?>
      <div class="form-row">
        <label>Lab Test <span style="color:#ef4444">*</span></label>
        <?php if (!empty($pendingTests)): ?>
          <select name="lab_test_id" id="lab_test_id" required onchange="updateTestInfo()">
            <option value="">-- Select Pending Test --</option>
            <?php foreach ($pendingTests as $t): 
              $tid = (int)($t['id'] ?? 0);
              $patientName = trim(($t['first_name'] ?? '') . ' ' . ($t['last_name'] ?? ''));
              $label = ($t['test_number'] ?? ('TEST-'.$tid)) . ' • ' . ($t['test_name'] ?? '') . ' - ' . $patientName;
              $isSelected = ($selectedTest && $selectedTest['id'] == $tid) || old('lab_test_id') == $tid;
            ?>
              <option value="<?= $tid ?>" <?= $isSelected ? 'selected' : '' ?> data-patient="<?= esc($patientName) ?>" data-test-name="<?= esc($t['test_name'] ?? '') ?>">
                <?= esc($label) ?>
              </option>
            <?php endforeach; ?>
          </select>
        <?php else: ?>
          <input type="number" name="lab_test_id" required value="<?= $testId ?? old('lab_test_id') ?>">
        <?php endif; ?>
      </div>
      <div class="form-row">
        <label>Results <span style="color:#ef4444">*</span></label>
        <textarea name="results" id="results" rows="6" required placeholder="Enter test results here..."><?= old('results') ?></textarea>
      </div>
      <div class="form-row">
        <label>Normal Range (optional)</label>
        <textarea name="normal_range" id="normal_range" rows="3" placeholder="e.g., 70-100 mg/dL"><?= old('normal_range') ?></textarea>
      </div>
      <div class="form-row">
        <label>Interpretation (optional)</label>
        <textarea name="interpretation" id="interpretation" rows="4" placeholder="Enter interpretation or notes about the results..."><?= old('interpretation') ?></textarea>
      </div>
      <div style="display:flex;gap:8px;margin-top:10px">
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn" href="<?= site_url('lab/test-requests') ?>">Cancel</a>
      </div>
    </form>
  </main>
  <script>
    function updateTestInfo() {
      const select = document.getElementById('lab_test_id');
      const selectedOption = select.options[select.selectedIndex];
      if (selectedOption && selectedOption.value) {
        // Optionally update form based on selected test
        console.log('Selected test:', selectedOption.value);
      }
    }
  </script>
</body></html>
