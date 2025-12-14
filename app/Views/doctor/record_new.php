<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Medical Record | HMS</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .medical-form {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 0;
      overflow: hidden;
    }
    .form-section {
      padding: 24px;
      border-bottom: 1px solid #e5e7eb;
    }
    .form-section:last-child {
      border-bottom: none;
    }
    .section-header {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 2px solid #3b82f6;
    }
    .section-header h3 {
      margin: 0;
      font-size: 1.1rem;
      font-weight: 700;
      color: #1f2937;
    }
    .section-header i {
      color: #3b82f6;
      font-size: 1.2rem;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #374151;
      font-size: 0.875rem;
    }
    .form-group label .required {
      color: #ef4444;
      margin-left: 4px;
    }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group input[type="date"],
    .form-group input[type="datetime-local"],
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      font-size: 0.875rem;
      transition: all 0.2s;
      font-family: inherit;
    }
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .form-group textarea {
      resize: vertical;
      min-height: 80px;
    }
    .form-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
    }
    .patient-info-card {
      background: #f0f9ff;
      border: 1px solid #bae6fd;
      border-radius: 8px;
      padding: 16px;
      margin-bottom: 20px;
    }
    .patient-info-card h4 {
      margin: 0 0 12px 0;
      color: #0369a1;
      font-size: 1rem;
    }
    .patient-info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 12px;
    }
    .info-item {
      display: flex;
      flex-direction: column;
    }
    .info-item label {
      font-size: 0.75rem;
      color: #6b7280;
      margin-bottom: 4px;
      font-weight: 500;
    }
    .info-item span {
      font-weight: 600;
      color: #1f2937;
      font-size: 0.875rem;
    }
    .vital-signs-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 16px;
    }
    .medication-item {
      background: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 16px;
      margin-bottom: 12px;
    }
    .medication-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }
    .btn-add-medication {
      background: #10b981;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      font-size: 0.875rem;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-add-medication:hover {
      background: #059669;
    }
    .btn-remove {
      background: #ef4444;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.75rem;
    }
    .form-actions {
      display: flex;
      gap: 12px;
      justify-content: flex-end;
      padding: 24px;
      background: #f9fafb;
      border-top: 1px solid #e5e7eb;
    }
    .btn-primary {
      background: #3b82f6;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      font-size: 0.875rem;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .btn-primary:hover {
      background: #2563eb;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    .btn-secondary {
      background: #6b7280;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      font-size: 0.875rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .btn-secondary:hover {
      background: #4b5563;
    }
    .help-text {
      font-size: 0.75rem;
      color: #6b7280;
      margin-top: 4px;
    }
  </style>
</head>
<body>
<header class="dash-topbar">
  <div class="topbar-inner">
    <a href="<?= site_url('dashboard/doctor') ?>" style="text-decoration:none;color:#3b82f6;display:flex;align-items:center;gap:8px">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
    <h1 style="margin:0;font-size:1.1rem;font-weight:600">New Medical Record</h1>
    <div></div>
  </div>
</header>

<main class="content" style="max-width:1000px;margin:20px auto;padding:0 20px">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error" style="margin-bottom:20px;padding:12px;background:#fee2e2;color:#991b1b;border-radius:8px;border-left:4px solid #ef4444">
      <i class="fa-solid fa-exclamation-circle" style="margin-right:8px"></i>
      <?= esc(session()->getFlashdata('error')) ?>
    </div>
  <?php endif; ?>
  
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success" style="margin-bottom:20px;padding:12px;background:#d1fae5;color:#065f46;border-radius:8px;border-left:4px solid #10b981">
      <i class="fa-solid fa-check-circle" style="margin-right:8px"></i>
      <?= esc(session()->getFlashdata('success')) ?>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('doctor/records') ?>" id="medicalRecordForm" class="medical-form">
    <?= csrf_field() ?>
    
    <!-- Patient Information Section -->
    <?php if (!empty($patient)): ?>
    <div class="form-section">
      <div class="patient-info-card">
        <h4><i class="fa-solid fa-user" style="margin-right:8px"></i>Patient Information</h4>
        <div class="patient-info-grid">
          <div class="info-item">
            <label>Patient ID</label>
            <span><?= esc($patient['patient_id'] ?? 'N/A') ?></span>
          </div>
          <div class="info-item">
            <label>Name</label>
            <span><?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?></span>
          </div>
          <div class="info-item">
            <label>Date of Birth</label>
            <span><?= !empty($patient['date_of_birth']) ? date('M d, Y', strtotime($patient['date_of_birth'])) : 'N/A' ?></span>
          </div>
          <div class="info-item">
            <label>Gender</label>
            <span><?= esc(strtoupper($patient['gender'] ?? 'N/A')) ?></span>
          </div>
          <div class="info-item">
            <label>Contact</label>
            <span><?= esc($patient['phone'] ?? 'N/A') ?></span>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Basic Information Section -->
    <div class="form-section">
      <div class="section-header">
        <i class="fa-solid fa-file-medical"></i>
        <h3>Basic Information</h3>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label>Patient ID <span class="required">*</span></label>
          <input type="number" name="patient_id" value="<?= old('patient_id', $patient_id ?? '') ?>" required readonly style="background:#f3f4f6">
        </div>
        <div class="form-group">
          <label>Appointment ID</label>
          <input type="number" name="appointment_id" value="<?= old('appointment_id', $appointment_id ?? '') ?>" placeholder="Optional">
        </div>
        <div class="form-group">
          <label>Visit Date/Time <span class="required">*</span></label>
          <input type="datetime-local" name="visit_date" value="<?= old('visit_date', date('Y-m-d\TH:i')) ?>" required>
        </div>
      </div>
    </div>

    <!-- Clinical Information Section -->
    <div class="form-section">
      <div class="section-header">
        <i class="fa-solid fa-stethoscope"></i>
        <h3>Clinical Information</h3>
      </div>
      
      <div class="form-group">
        <label>Chief Complaint <span class="required">*</span></label>
        <textarea name="chief_complaint" rows="3" placeholder="Enter the patient's primary reason for visit..." required><?= old('chief_complaint') ?></textarea>
        <div class="help-text">Main reason why the patient is seeking medical attention</div>
      </div>
      
      <div class="form-group">
        <label>History of Present Illness</label>
        <textarea name="history_present_illness" rows="4" placeholder="Describe the onset, duration, severity, and progression of symptoms..."><?= old('history_present_illness') ?></textarea>
        <div class="help-text">Detailed history related to the chief complaint</div>
      </div>
      
      <div class="form-group">
        <label>Physical Examination</label>
        <textarea name="physical_examination" rows="4" placeholder="Document findings from physical examination..."><?= old('physical_examination') ?></textarea>
        <div class="help-text">Record all relevant physical examination findings</div>
      </div>
    </div>

    <!-- Vital Signs Section -->
    <div class="form-section">
      <div class="section-header">
        <i class="fa-solid fa-heartbeat"></i>
        <h3>Vital Signs</h3>
      </div>
      
      <div class="vital-signs-grid">
        <div class="form-group">
          <label>Blood Pressure</label>
          <input type="text" name="vital_bp" value="<?= old('vital_bp') ?>" placeholder="e.g., 120/80">
        </div>
        <div class="form-group">
          <label>Temperature (°C)</label>
          <input type="text" name="vital_temp" value="<?= old('vital_temp') ?>" placeholder="e.g., 36.6">
        </div>
        <div class="form-group">
          <label>Pulse Rate (bpm)</label>
          <input type="number" name="vital_pulse" value="<?= old('vital_pulse') ?>" placeholder="e.g., 72">
        </div>
        <div class="form-group">
          <label>Respiratory Rate</label>
          <input type="number" name="vital_respiratory" value="<?= old('vital_respiratory') ?>" placeholder="e.g., 16">
        </div>
        <div class="form-group">
          <label>Oxygen Saturation (%)</label>
          <input type="number" name="vital_spo2" value="<?= old('vital_spo2') ?>" placeholder="e.g., 98" min="0" max="100">
        </div>
        <div class="form-group">
          <label>Weight (kg)</label>
          <input type="number" step="0.1" name="vital_weight" value="<?= old('vital_weight') ?>" placeholder="e.g., 70.5">
        </div>
        <div class="form-group">
          <label>Height (cm)</label>
          <input type="number" step="0.1" name="vital_height" value="<?= old('vital_height') ?>" placeholder="e.g., 170">
        </div>
        <div class="form-group">
          <label>BMI</label>
          <input type="text" name="vital_bmi" value="<?= old('vital_bmi') ?>" placeholder="Auto-calculated" readonly style="background:#f3f4f6">
        </div>
      </div>
      
      <input type="hidden" name="vital_signs" id="vital_signs_json">
    </div>

    <!-- Diagnosis Section -->
    <div class="form-section">
      <div class="section-header">
        <i class="fa-solid fa-diagnoses"></i>
        <h3>Diagnosis & Treatment</h3>
      </div>
      
      <div class="form-group">
        <label>Diagnosis <span class="required">*</span></label>
        <textarea name="diagnosis" rows="3" placeholder="Enter primary and secondary diagnoses..." required><?= old('diagnosis') ?></textarea>
        <div class="help-text">Primary diagnosis and any secondary diagnoses</div>
      </div>
      
      <div class="form-group">
        <label>Treatment Plan</label>
        <textarea name="treatment_plan" rows="4" placeholder="Describe the treatment plan, procedures, and interventions..."><?= old('treatment_plan') ?></textarea>
        <div class="help-text">Detailed treatment strategy and plan of care</div>
      </div>
    </div>

    <!-- Medications Section -->
    <div class="form-section">
      <div class="section-header">
        <i class="fa-solid fa-pills"></i>
        <h3>Medications Prescribed</h3>
      </div>
      
      <div id="medications-container">
        <div class="medication-item" data-index="0">
          <div class="form-row">
            <div class="form-group">
              <label>Medication Name</label>
              <select name="medications[0][drug]" class="med-drug" style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;background:white;">
                <option value="">-- Select Medicine --</option>
                <?php if (!empty($medicines)): ?>
                  <?php foreach ($medicines as $medicine): ?>
                    <?php 
                    $medicineName = $medicine['item_name'] ?? $medicine['name'] ?? '';
                    $medicineStock = $medicine['quantity'] ?? $medicine['stock'] ?? 0;
                    ?>
                    <option value="<?= esc($medicineName) ?>" data-stock="<?= esc($medicineStock) ?>">
                      <?= esc($medicineName) ?>
                      <?php if ($medicineStock < 10): ?>
                        <span style="color: #ef4444;">(Low Stock: <?= $medicineStock ?>)</span>
                      <?php elseif ($medicineStock < 20): ?>
                        <span style="color: #f59e0b;">(Stock: <?= $medicineStock ?>)</span>
                      <?php else: ?>
                        <span style="color: #10b981;">(Stock: <?= $medicineStock ?>)</span>
                      <?php endif; ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="" disabled>No medicines available in pharmacy</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Dosage</label>
              <input type="text" name="medications[0][dose]" placeholder="e.g., 500mg" class="med-dose">
            </div>
            <div class="form-group">
              <label>Frequency</label>
              <input type="text" name="medications[0][frequency]" placeholder="e.g., 3x daily" class="med-frequency">
            </div>
            <div class="form-group">
              <label>Duration</label>
              <input type="text" name="medications[0][duration]" placeholder="e.g., 7 days" class="med-duration">
            </div>
          </div>
        </div>
      </div>
      
      <button type="button" class="btn-add-medication" onclick="addMedication()">
        <i class="fa-solid fa-plus"></i> Add Medication
      </button>
      
      <input type="hidden" name="medications_prescribed" id="medications_json">
    </div>

    <!-- Follow-up Section -->
    <div class="form-section">
      <div class="section-header">
        <i class="fa-solid fa-calendar-check"></i>
        <h3>Follow-up</h3>
      </div>
      
      <div class="form-group">
        <label>Follow-up Instructions</label>
        <textarea name="follow_up_instructions" rows="3" placeholder="Enter follow-up care instructions for the patient..."><?= old('follow_up_instructions') ?></textarea>
        <div class="help-text">Instructions for patient regarding follow-up care, lifestyle changes, or monitoring</div>
      </div>
      
      <div class="form-group">
        <label>Next Visit Date</label>
        <input type="date" name="next_visit_date" value="<?= old('next_visit_date') ?>" min="<?= date('Y-m-d') ?>">
        <div class="help-text">Schedule the next appointment or follow-up visit</div>
      </div>
    </div>

    <!-- Form Actions -->
    <div class="form-actions">
      <a href="<?= site_url('dashboard/doctor') ?>" class="btn-secondary">
        <i class="fa-solid fa-times"></i> Cancel
      </a>
      <button type="submit" class="btn-primary">
        <i class="fa-solid fa-save"></i> Save Medical Record
      </button>
    </div>
  </form>
</main>

<script>
  let medicationIndex = 1;
  
  // Medicines data from PHP
  const medicinesData = <?= json_encode(array_map(function($m) {
    return [
      'name' => $m['item_name'] ?? $m['name'] ?? '',
      'stock' => $m['quantity'] ?? $m['stock'] ?? 0
    ];
  }, $medicines ?? [])) ?>;

  function addMedication() {
    const container = document.getElementById('medications-container');
    const newItem = document.createElement('div');
    newItem.className = 'medication-item';
    newItem.setAttribute('data-index', medicationIndex);
    
    // Build medicines dropdown options
    let medicinesOptions = '<option value="">-- Select Medicine --</option>';
    medicinesData.forEach(medicine => {
      if (medicine.name) {
        const stock = medicine.stock || 0;
        const stockColor = (stock < 10) ? '#ef4444' : ((stock < 20) ? '#f59e0b' : '#10b981');
        const stockLabel = (stock < 10) ? 'Low Stock' : 'Stock';
        medicinesOptions += `<option value="${escapeHtml(medicine.name)}" data-stock="${stock}">${escapeHtml(medicine.name)} <span style="color: ${stockColor};">(${stockLabel}: ${stock})</span></option>`;
      }
    });
    
    newItem.innerHTML = `
      <div class="medication-header">
        <strong>Medication #${medicationIndex + 1}</strong>
        <button type="button" class="btn-remove" onclick="removeMedication(this)">
          <i class="fa-solid fa-trash"></i> Remove
        </button>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Medication Name</label>
          <select name="medications[${medicationIndex}][drug]" class="med-drug" style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem;background:white;">
            ${medicinesOptions}
          </select>
        </div>
        <div class="form-group">
          <label>Dosage</label>
          <input type="text" name="medications[${medicationIndex}][dose]" placeholder="e.g., 500mg" class="med-dose">
        </div>
        <div class="form-group">
          <label>Frequency</label>
          <input type="text" name="medications[${medicationIndex}][frequency]" placeholder="e.g., 3x daily" class="med-frequency">
        </div>
        <div class="form-group">
          <label>Duration</label>
          <input type="text" name="medications[${medicationIndex}][duration]" placeholder="e.g., 7 days" class="med-duration">
        </div>
      </div>
    `;
    container.appendChild(newItem);
    medicationIndex++;
  }
  
  function escapeHtml(text) {
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
  }

  function removeMedication(btn) {
    btn.closest('.medication-item').remove();
  }

  // Calculate BMI
  function calculateBMI() {
    const weight = parseFloat(document.querySelector('input[name="vital_weight"]')?.value) || 0;
    const height = parseFloat(document.querySelector('input[name="vital_height"]')?.value) || 0;
    if (weight > 0 && height > 0) {
      const heightInMeters = height / 100;
      const bmi = (weight / (heightInMeters * heightInMeters)).toFixed(1);
      document.querySelector('input[name="vital_bmi"]').value = bmi;
    }
  }

  document.querySelector('input[name="vital_weight"]')?.addEventListener('input', calculateBMI);
  document.querySelector('input[name="vital_height"]')?.addEventListener('input', calculateBMI);

  // Convert form data to JSON before submit
  document.getElementById('medicalRecordForm').addEventListener('submit', function(e) {
    // Convert vital signs to JSON
    const vitalSigns = {
      bp: document.querySelector('input[name="vital_bp"]')?.value || '',
      temp: document.querySelector('input[name="vital_temp"]')?.value || '',
      pulse: document.querySelector('input[name="vital_pulse"]')?.value || '',
      respiratory: document.querySelector('input[name="vital_respiratory"]')?.value || '',
      spo2: document.querySelector('input[name="vital_spo2"]')?.value || '',
      weight: document.querySelector('input[name="vital_weight"]')?.value || '',
      height: document.querySelector('input[name="vital_height"]')?.value || '',
      bmi: document.querySelector('input[name="vital_bmi"]')?.value || ''
    };
    document.getElementById('vital_signs_json').value = JSON.stringify(vitalSigns);

    // Convert medications to JSON
    const medications = [];
    document.querySelectorAll('.medication-item').forEach(item => {
      const drug = item.querySelector('.med-drug')?.value;
      const dose = item.querySelector('.med-dose')?.value;
      const frequency = item.querySelector('.med-frequency')?.value;
      const duration = item.querySelector('.med-duration')?.value;
      
      if (drug || dose || frequency || duration) {
        medications.push({
          drug: drug || '',
          dose: dose || '',
          frequency: frequency || '',
          duration: duration || ''
        });
      }
    });
    document.getElementById('medications_json').value = JSON.stringify(medications);
  });
</script>
</body>
</html>
