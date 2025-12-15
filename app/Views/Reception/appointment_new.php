<?php
helper('form');
$errors = session('errors') ?? [];
$patientModel = model('App\\Models\\PatientModel');
$userModel = model('App\\Models\\UserModel');
$patients = $patientModel->where('is_active', 1)->orderBy('last_name', 'ASC')->findAll(200);
$doctors = $userModel->where('role', 'doctor')->where('is_active', 1)->orderBy('first_name', 'ASC')->findAll(50);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book Appointment | HMS</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    :root {
      --primary-blue: #2563eb;
      --primary-light: #3b82f6;
      --primary-dark: #1e40af;
      --text-dark: #1f2937;
      --text-gray: #6b7280;
      --bg-light: #f8fafc;
      --border-color: #e5e7eb;
      --focus-ring: rgba(37, 99, 235, 0.3);
    }

    body {
      background: var(--bg-light);
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .appointment-wrapper {
      max-width: 1200px;
      margin: 24px auto;
      padding: 0 16px;
    }

    .page-header-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 12px;
    }

    .page-title-main {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-dark);
      margin: 0;
    }

    .form-container {
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
      border: 1px solid var(--border-color);
      overflow: hidden;
    }

    .form-content {
      padding: 32px;
    }

    .section-header {
      font-weight: 700;
      font-size: 1.1rem;
      color: var(--text-dark);
      margin: 28px 0 16px;
      padding: 12px 16px;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      border-left: 4px solid var(--primary-blue);
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-header:first-child {
      margin-top: 0;
    }

    .form-label-custom {
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 6px;
      font-size: 0.95rem;
    }

    .form-control-custom,
    .form-select-custom {
      border: 1.5px solid var(--border-color);
      border-radius: 10px;
      padding: 11px 15px;
      font-size: 0.95rem;
      transition: all 0.2s ease;
      background: #fff;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
      border-color: var(--primary-blue);
      outline: none;
      box-shadow: 0 0 0 4px var(--focus-ring);
    }

    .invalid-feedback-custom {
      display: block;
      margin-top: 4px;
      font-size: 0.875rem;
      color: #dc2626;
    }

    .btn-primary-custom {
      background: var(--primary-blue);
      border-color: var(--primary-blue);
      color: #fff;
      padding: 12px 28px;
      font-weight: 600;
      border-radius: 10px;
      transition: all 0.2s ease;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .btn-primary-custom:hover {
      background: var(--primary-dark);
      border-color: var(--primary-dark);
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
    }

    .btn-secondary-custom {
      background: #fff;
      border: 1.5px solid var(--border-color);
      color: var(--text-dark);
      padding: 12px 28px;
      font-weight: 600;
      border-radius: 10px;
      transition: all 0.2s ease;
    }

    .btn-secondary-custom:hover {
      background: var(--bg-light);
      border-color: #cbd5e1;
    }

    .alert-custom {
      border-radius: 12px;
      border: 0;
      padding: 14px 18px;
      margin-bottom: 20px;
    }

    .text-required {
      color: #dc2626;
      font-weight: 600;
    }

    .text-hint {
      font-size: 0.875rem;
      color: var(--text-gray);
      margin-top: 4px;
    }

    .patient-info-card,
    .doctor-info-card {
      background: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 12px;
      margin-top: 8px;
      font-size: 0.875rem;
    }

    .search-input-wrapper {
      position: relative;
    }

    .suggestions-dropdown {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
      max-height: 250px;
      overflow-y: auto;
      z-index: 1000;
      display: none;
      margin-top: 4px;
    }
    
    .form-select-custom optgroup {
      font-weight: 600;
      color: var(--primary-blue);
      background: #f0f9ff;
      padding: 8px 0;
    }
    
    .form-select-custom option {
      padding: 10px 15px;
      font-size: 0.95rem;
    }
    
    .form-select-custom option:checked {
      background: var(--primary-blue);
      color: white;
    }

    .suggestion-item {
      padding: 10px 14px;
      cursor: pointer;
      border-bottom: 1px solid #f3f4f6;
      transition: background 0.15s;
    }

    .suggestion-item:hover {
      background: #eff6ff;
    }

    .suggestion-item:last-child {
      border-bottom: none;
    }

    @media (max-width: 768px) {
      .appointment-wrapper {
        margin: 16px auto;
        padding: 0 12px;
      }
      .form-content {
        padding: 16px;
      }
      .page-header-section {
        flex-direction: column;
        align-items: flex-start;
      }
    }
  </style>
</head>
<body>

<div class="appointment-wrapper">
  <div class="page-header-section">
    <h1 class="page-title-main">
      <i class="fas fa-calendar-plus me-2"></i>Book Appointment
    </h1>
    <a href="<?= site_url('reception/appointments') ?>" class="btn btn-secondary-custom">
      <i class="fas fa-arrow-left me-2"></i>Back to Appointments
    </a>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-custom">
      <i class="fas fa-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-custom">
      <i class="fas fa-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
    </div>
  <?php endif; ?>

  <div class="form-container">
    <div class="form-content">
      <form method="post" action="<?= site_url('reception/appointments') ?>" id="appointmentForm">
        <?= csrf_field() ?>

        <!-- Patient & Doctor Selection Section -->
        <h5 class="section-header">
          <i class="fas fa-user-md"></i>
          <span>Patient & Doctor Selection</span>
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label-custom">
              Patient <span class="text-required">*</span>
            </label>
            <select name="patient_id" id="patientSelect" class="form-select form-select-custom" required>
              <option value="">-- Select Patient --</option>
              <?php if (!empty($patients)): ?>
                <?php foreach ($patients as $patient): ?>
                  <?php
                    $patientName = trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''));
                    $patientId = $patient['id'] ?? '';
                    $patientCode = $patient['patient_id'] ?? 'N/A';
                    $selected = (old('patient_id') == $patientId) ? 'selected' : '';
                  ?>
                  <option value="<?= esc($patientId) ?>" <?= $selected ?>>
                    <?= esc($patientName) ?> (ID: <?= esc($patientCode) ?>)
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label-custom">
              Doctor <span class="text-required">*</span>
            </label>
            <select name="doctor_id" 
                    id="doctorIdField" 
                    class="form-select form-select-custom" 
                    required>
              <option value="">-- Select Doctor --</option>
              <?php if (!empty($doctors)) : 
                // Group doctors by department (preferred) or specialization (fallback)
                $doctorsByDept = [];
                $doctorsBySpecialization = [];
                $doctorsWithoutGroup = [];
                
                foreach ($doctors as $doctor) {
                  if (!empty($doctor['department_name'])) {
                    // Group by department (preferred)
                    $deptName = $doctor['department_name'];
                    if (!isset($doctorsByDept[$deptName])) {
                      $doctorsByDept[$deptName] = [];
                    }
                    $doctorsByDept[$deptName][] = $doctor;
                  } elseif (!empty($doctor['specialization'])) {
                    // Fallback to specialization
                    $specialization = $doctor['specialization'];
                    if (!isset($doctorsBySpecialization[$specialization])) {
                      $doctorsBySpecialization[$specialization] = [];
                    }
                    $doctorsBySpecialization[$specialization][] = $doctor;
                  } else {
                    $doctorsWithoutGroup[] = $doctor;
                  }
                }
                
                // Sort departments alphabetically
                ksort($doctorsByDept);
                ksort($doctorsBySpecialization);
                
                // Display doctors grouped by department first
                foreach ($doctorsByDept as $deptName => $deptDoctors): ?>
                  <optgroup label="<?= esc($deptName) ?>">
                    <?php foreach ($deptDoctors as $doctor) : 
                      $doctorName = trim(($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? ''));
                      $selected = (old('doctor_id') == $doctor['id']) ? 'selected' : '';
                    ?>
                      <option value="<?= esc($doctor['id']) ?>" <?= $selected ?>>
                        Dr. <?= esc($doctorName) ?>
                        <?php if (!empty($doctor['specialization'])): ?>
                          - <?= esc($doctor['specialization']) ?>
                        <?php endif; ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endforeach; ?>
                
                <?php // Display doctors grouped by specialization (if no department)
                foreach ($doctorsBySpecialization as $specialization => $docs) : ?>
                  <optgroup label="<?= esc($specialization) ?>">
                    <?php foreach ($docs as $doctor) : 
                      $doctorName = trim(($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? ''));
                      $selected = (old('doctor_id') == $doctor['id']) ? 'selected' : '';
                    ?>
                      <option value="<?= esc($doctor['id']) ?>" <?= $selected ?>>
                        Dr. <?= esc($doctorName) ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endforeach; ?>
                
                <?php if (!empty($doctorsWithoutGroup)): ?>
                  <optgroup label="Other / No Department">
                    <?php foreach ($doctorsWithoutGroup as $doctor) : 
                      $doctorName = trim(($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? ''));
                      $selected = (old('doctor_id') == $doctor['id']) ? 'selected' : '';
                    ?>
                      <option value="<?= esc($doctor['id']) ?>" <?= $selected ?>>
                        Dr. <?= esc($doctorName) ?>
                        <?php if (!empty($doctor['specialization'])): ?>
                          - <?= esc($doctor['specialization']) ?>
                        <?php endif; ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endif; 
              else: ?>
                <option value="">No doctors available</option>
              <?php endif; ?>
            </select>
            <div class="text-hint">
              <i class="fas fa-user-doctor me-1"></i>
              Select the doctor for this appointment
            </div>
            <div id="doctorInfoCard" class="doctor-info-card" style="display:none;margin-top:8px;">
              <strong id="doctorName"></strong><br>
              <small id="doctorDetails"></small>
            </div>
          </div>
        </div>

        <!-- Appointment Details Section -->
        <h5 class="section-header">
          <i class="fas fa-calendar-alt"></i>
          <span>Appointment Details</span>
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label-custom">
              Appointment Date <span class="text-required">*</span>
            </label>
            <input type="date" 
                   name="appointment_date" 
                   class="form-control form-control-custom" 
                   value="<?= old('appointment_date') ?: date('Y-m-d') ?>" 
                   min="<?= date('Y-m-d') ?>"
                   required>
            <div class="text-hint">
              <i class="fas fa-info-circle me-1"></i>
              Select appointment date
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">
              Appointment Time <span class="text-required">*</span>
            </label>
            <input type="time" 
                   name="appointment_time" 
                   class="form-control form-control-custom" 
                   value="<?= old('appointment_time') ?>" 
                   required>
            <div class="text-hint">
              <i class="fas fa-clock me-1"></i>
              Select appointment time
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Duration (minutes)</label>
            <select name="duration" class="form-select form-select-custom">
              <option value="15" <?= old('duration') == '15' ? 'selected' : '' ?>>15 minutes</option>
              <option value="30" <?= old('duration') == '30' || old('duration') == '' ? 'selected' : '' ?>>30 minutes</option>
              <option value="45" <?= old('duration') == '45' ? 'selected' : '' ?>>45 minutes</option>
              <option value="60" <?= old('duration') == '60' ? 'selected' : '' ?>>1 hour</option>
              <option value="90" <?= old('duration') == '90' ? 'selected' : '' ?>>1.5 hours</option>
              <option value="120" <?= old('duration') == '120' ? 'selected' : '' ?>>2 hours</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label-custom">
              Appointment Type <span class="text-required">*</span>
            </label>
            <select name="type" class="form-select form-select-custom" required>
              <option value="">-- Select Type --</option>
              <option value="consultation" <?= old('type') == 'consultation' || old('type') == '' ? 'selected' : '' ?>>Consultation</option>
              <option value="follow_up" <?= old('type') == 'follow_up' ? 'selected' : '' ?>>Follow-up</option>
              <option value="checkup" <?= old('type') == 'checkup' ? 'selected' : '' ?>>General Checkup</option>
              <option value="emergency" <?= old('type') == 'emergency' ? 'selected' : '' ?>>Emergency</option>
              <option value="surgery" <?= old('type') == 'surgery' ? 'selected' : '' ?>>Surgery Consultation</option>
              <option value="specialist" <?= old('type') == 'specialist' ? 'selected' : '' ?>>Specialist Consultation</option>
              <option value="lab_review" <?= old('type') == 'lab_review' ? 'selected' : '' ?>>Lab Results Review</option>
              <option value="prescription" <?= old('type') == 'prescription' ? 'selected' : '' ?>>Prescription Refill</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label-custom">Priority Level</label>
            <select name="priority" class="form-select form-select-custom">
              <option value="normal" <?= old('priority') == 'normal' || old('priority') == '' ? 'selected' : '' ?>>Normal</option>
              <option value="urgent" <?= old('priority') == 'urgent' ? 'selected' : '' ?>>Urgent</option>
              <option value="routine" <?= old('priority') == 'routine' ? 'selected' : '' ?>>Routine</option>
            </select>
          </div>
        </div>

        <!-- Medical Information Section -->
        <h5 class="section-header">
          <i class="fas fa-stethoscope"></i>
          <span>Medical Information</span>
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-12">
            <label class="form-label-custom">
              Chief Complaint / Reason for Visit <span class="text-required">*</span>
            </label>
            <textarea name="reason" 
                      class="form-control form-control-custom" 
                      rows="3" 
                      placeholder="Describe the patient's main complaint or reason for this appointment..." 
                      required><?= old('reason') ?></textarea>
            <div class="text-hint">
              <i class="fas fa-info-circle me-1"></i>
              Provide detailed information about the patient's complaint or reason for visit
            </div>
          </div>
          <div class="col-md-12">
            <label class="form-label-custom">Symptoms</label>
            <textarea name="symptoms" 
                      class="form-control form-control-custom" 
                      rows="2" 
                      placeholder="List any symptoms the patient is experiencing (e.g., fever, pain, nausea, etc.)"><?= old('symptoms') ?></textarea>
          </div>
          <div class="col-md-12">
            <label class="form-label-custom">Previous Visit Reference</label>
            <input type="text" 
                   name="previous_visit" 
                   class="form-control form-control-custom" 
                   placeholder="Reference previous appointment or visit number (if applicable)"
                   value="<?= old('previous_visit') ?>">
          </div>
        </div>

        <!-- Additional Information Section -->
        <h5 class="section-header">
          <i class="fas fa-clipboard-list"></i>
          <span>Additional Information</span>
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-12">
            <label class="form-label-custom">Special Instructions / Notes</label>
            <textarea name="notes" 
                      class="form-control form-control-custom" 
                      rows="3" 
                      placeholder="Any special instructions, notes, or additional information for this appointment..."><?= old('notes') ?></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label-custom">Insurance Coverage</label>
            <select name="insurance_coverage" class="form-select form-select-custom">
              <option value="">-- Select --</option>
              <option value="self_pay" <?= old('insurance_coverage') == 'self_pay' ? 'selected' : '' ?>>Self Pay</option>
              <option value="philhealth" <?= old('insurance_coverage') == 'philhealth' ? 'selected' : '' ?>>PhilHealth</option>
              <option value="hmo" <?= old('insurance_coverage') == 'hmo' ? 'selected' : '' ?>>HMO</option>
              <option value="private_insurance" <?= old('insurance_coverage') == 'private_insurance' ? 'selected' : '' ?>>Private Insurance</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label-custom">Referred By</label>
            <input type="text" 
                   name="referred_by" 
                   class="form-control form-control-custom" 
                   placeholder="Doctor/Clinic name (if referred)"
                   value="<?= old('referred_by') ?>">
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-3 mt-4 pt-4 border-top">
          <button type="submit" class="btn btn-primary-custom">
            <i class="fas fa-calendar-check me-2"></i>Book Appointment
          </button>
          <a href="<?= site_url('reception/appointments') ?>" class="btn btn-secondary-custom">
            <i class="fas fa-times me-2"></i>Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Patient and Doctor dropdowns are now used instead of autocomplete - no JavaScript needed for selection
const doctorsData = <?= json_encode(array_map(function($d){
    return [
      'id' => (int)($d['id'] ?? 0),
      'first_name' => $d['first_name'] ?? '',
      'last_name' => $d['last_name'] ?? '',
      'name' => trim(($d['first_name'] ?? '') . ' ' . ($d['last_name'] ?? '')),
      'username' => $d['username'] ?? '',
      'specialization' => $d['specialization'] ?? 'General',
      'label' => trim('Dr. ' . ($d['first_name'] ?? '') . ' ' . ($d['last_name'] ?? '') . ' (' . ($d['username'] ?? 'doctor') . ')')
    ];
  }, $doctors ?? []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

// Setup doctor dropdown info display
const doctorSelect = document.getElementById('doctorIdField');
const doctorInfoCard = document.getElementById('doctorInfoCard');
const doctorName = document.getElementById('doctorName');
const doctorDetails = document.getElementById('doctorDetails');

if (doctorSelect && doctorInfoCard) {
  doctorSelect.addEventListener('change', function() {
    const selectedDoctorId = this.value;
    
    if (selectedDoctorId) {
      const selectedDoctor = doctorsData.find(d => d.id == selectedDoctorId);
      if (selectedDoctor) {
        doctorName.textContent = selectedDoctor.name || 'Dr. ' + (selectedDoctor.first_name || '') + ' ' + (selectedDoctor.last_name || '');
        doctorDetails.textContent = selectedDoctor.specialization || 'General Practitioner';
        doctorInfoCard.style.display = 'block';
      } else {
        doctorInfoCard.style.display = 'none';
      }
    } else {
      doctorInfoCard.style.display = 'none';
    }
  });
  
  // Show info if doctor is pre-selected
  if (doctorSelect.value) {
    doctorSelect.dispatchEvent(new Event('change'));
  }
}

// Form validation
document.getElementById('appointmentForm').addEventListener('submit', function(e) {
  const patientId = document.getElementById('patientIdField').value;
  const doctorId = document.getElementById('doctorIdField').value;
  
  if (!patientId || !doctorId) {
    e.preventDefault();
    alert('Please select both a patient and a doctor.');
    return false;
  }
});
</script>

</body>
</html>
