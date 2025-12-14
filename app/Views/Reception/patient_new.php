<?php
helper('form');
$errors = session('errors') ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Patient | HMS</title>
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

    .registration-wrapper {
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
      padding: 24px;
    }

    .section-header {
      font-weight: 700;
      font-size: 1.05rem;
      color: var(--text-dark);
      margin: 20px 0 12px;
      padding: 10px 14px;
      background: var(--bg-light);
      border-left: 4px solid var(--primary-blue);
      border-radius: 8px;
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
      padding: 11px 24px;
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
      padding: 11px 24px;
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

    @media (max-width: 768px) {
      .registration-wrapper {
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

<div class="registration-wrapper">
  <div class="page-header-section">
    <h1 class="page-title-main">
      <i class="fas fa-user-plus me-2"></i>Register New Patient
    </h1>
    <a href="<?= site_url('dashboard/receptionist') ?>" class="btn btn-secondary-custom">
      <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
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
      <form method="post" action="<?= site_url('reception/patients') ?>">
        <?= csrf_field() ?>

        <!-- Personal Information Section -->
        <h5 class="section-header">
          <i class="fas fa-id-card me-2"></i>Personal Information
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label-custom">
              First Name <span class="text-required">*</span>
            </label>
            <input type="text" name="first_name" 
                   class="form-control form-control-custom <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" 
                   value="<?= set_value('first_name', old('first_name')) ?>" required>
            <?php if (isset($errors['first_name'])): ?>
              <div class="invalid-feedback-custom"><?= esc($errors['first_name']) ?></div>
            <?php endif; ?>
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Middle Name</label>
            <input type="text" name="middle_name" 
                   class="form-control form-control-custom <?= isset($errors['middle_name']) ? 'is-invalid' : '' ?>" 
                   value="<?= set_value('middle_name', old('middle_name')) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">
              Last Name <span class="text-required">*</span>
            </label>
            <input type="text" name="last_name" 
                   class="form-control form-control-custom <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" 
                   value="<?= set_value('last_name', old('last_name')) ?>" required>
            <?php if (isset($errors['last_name'])): ?>
              <div class="invalid-feedback-custom"><?= esc($errors['last_name']) ?></div>
            <?php endif; ?>
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">
              Gender <span class="text-required">*</span>
            </label>
            <select name="gender" 
                    class="form-select form-select-custom <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" required>
              <option value="">-- Select Gender --</option>
              <option value="male" <?= set_select('gender', 'male', old('gender') == 'male') ?>>Male</option>
              <option value="female" <?= set_select('gender', 'female', old('gender') == 'female') ?>>Female</option>
              <option value="other" <?= set_select('gender', 'other', old('gender') == 'other') ?>>Other</option>
            </select>
            <?php if (isset($errors['gender'])): ?>
              <div class="invalid-feedback-custom"><?= esc($errors['gender']) ?></div>
            <?php endif; ?>
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Marital Status</label>
            <select name="marital_status" class="form-select form-select-custom">
              <option value="">-- Select Status --</option>
              <option value="single" <?= set_select('marital_status', 'single', old('marital_status') == 'single') ?>>Single</option>
              <option value="married" <?= set_select('marital_status', 'married', old('marital_status') == 'married') ?>>Married</option>
              <option value="divorced" <?= set_select('marital_status', 'divorced', old('marital_status') == 'divorced') ?>>Divorced</option>
              <option value="widowed" <?= set_select('marital_status', 'widowed', old('marital_status') == 'widowed') ?>>Widowed</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Date of Birth</label>
            <input type="date" name="date_of_birth" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('date_of_birth', old('date_of_birth')) ?>" 
                   id="dob_input">
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Phone Number</label>
            <input type="text" name="phone" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('phone', old('phone')) ?>" 
                   placeholder="09XX-XXX-XXXX">
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Email Address</label>
            <input type="email" name="email" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('email', old('email')) ?>" 
                   placeholder="patient@example.com">
          </div>
          <div class="col-md-12">
            <label class="form-label-custom">Complete Address</label>
            <input type="text" name="address" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('address', old('address')) ?>" 
                   placeholder="Street, City, Province">
          </div>
        </div>

        <!-- Emergency Contact Section -->
        <h5 class="section-header">
          <i class="fas fa-address-book me-2"></i>Emergency Contact
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label-custom">Full Name</label>
            <input type="text" name="emergency_contact_name" class="form-control form-control-custom" value="<?= set_value('emergency_contact_name', old('emergency_contact_name')) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Phone Number</label>
            <input type="text" name="emergency_contact_phone" class="form-control form-control-custom" value="<?= set_value('emergency_contact_phone', old('emergency_contact_phone')) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Relation</label>
            <input type="text" name="emergency_contact_relation" class="form-control form-control-custom" value="<?= set_value('emergency_contact_relation', old('emergency_contact_relation')) ?>">
          </div>
        </div>

        <!-- Medical History Section -->
        <h5 class="section-header">
            <i class="fas fa-notes-medical me-2"></i>Medical History
        </h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label-custom">Blood Type</label>
                <select name="blood_type" class="form-select form-select-custom">
                    <option value="">-- Select Blood Type --</option>
                    <option value="unknown" <?= set_select('blood_type', 'unknown', old('blood_type') == 'unknown') ?>>Unknown / Not sure</option>
                    <option value="A+" <?= set_select('blood_type', 'A+', old('blood_type') == 'A+') ?>>A+</option>
                    <option value="A-" <?= set_select('blood_type', 'A-', old('blood_type') == 'A-') ?>>A-</option>
                    <option value="B+" <?= set_select('blood_type', 'B+', old('blood_type') == 'B+') ?>>B+</option>
                    <option value="B-" <?= set_select('blood_type', 'B-', old('blood_type') == 'B-') ?>>B-</option>
                    <option value="AB+" <?= set_select('blood_type', 'AB+', old('blood_type') == 'AB+') ?>>AB+</option>
                    <option value="AB-" <?= set_select('blood_type', 'AB-', old('blood_type') == 'AB-') ?>>AB-</option>
                    <option value="O+" <?= set_select('blood_type', 'O+', old('blood_type') == 'O+') ?>>O+</option>
                    <option value="O-" <?= set_select('blood_type', 'O-', old('blood_type') == 'O-') ?>>O-</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label-custom">Known Allergies</label>
                <input type="text" name="allergies" class="form-control form-control-custom" value="<?= set_value('allergies', old('allergies')) ?>" placeholder="e.g., Peanuts, Penicillin">
            </div>
            <div class="col-md-12">
                <label class="form-label-custom">Past Medical History</label>
                <textarea name="medical_history" class="form-control form-control-custom" rows="3" placeholder="Describe any significant past illnesses, surgeries, or conditions."><?= set_value('medical_history', old('medical_history')) ?></textarea>
            </div>
        </div>

        <!-- Admission Information Section -->
        <h5 class="section-header">
          <i class="fas fa-hospital me-2"></i>Admission Information
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label-custom">Admission Type</label>
            <select name="admission_type" id="admission_type_select" class="form-select form-select-custom">
              <option value="checkup" <?= set_select('admission_type', 'checkup', old('admission_type') == 'checkup') ?>>Out-Patient (Check-up)</option>
              <option value="admission" <?= set_select('admission_type', 'admission', old('admission_type') == 'admission') ?>>In-Patient (Admission)</option>
            </select>
            <div class="text-hint">
              <i class="fas fa-info-circle me-1"></i>
              Select admission type. In-Patient requires room assignment.
            </div>
          </div>
          <div class="col-md-6" id="room_selection_section" style="display: none;">
            <label class="form-label-custom">Room Assignment <span class="text-required">*</span></label>
            <select name="assigned_room_id" id="room_select" class="form-select form-select-custom" required>
              <option value="">-- Select Room --</option>
              <?php if (!empty($availableRooms)): ?>
                <?php foreach ($availableRooms as $room): ?>
                  <option value="<?= esc($room['id']) ?>">
                    <?= esc($room['room_number']) ?> - 
                    <?= esc($room['room_type'] ?? 'Standard') ?> 
                    (₱<?= number_format($room['rate_per_day'] ?? 0, 2) ?>/day)
                  </option>
                <?php endforeach; ?>
              <?php else: ?>
                <option value="" disabled>No available rooms</option>
              <?php endif; ?>
            </select>
            <div class="text-hint">Room assignment is required for In-Patient admission</div>
          </div>
          <div class="col-md-6" id="bed_selection_section" style="display: none;">
            <label class="form-label-custom">Bed Assignment (Optional)</label>
            <select name="assigned_bed_id" id="bed_select" class="form-select form-select-custom">
              <option value="">-- Select Bed (Optional) --</option>
            </select>
            <div class="text-hint">Select a specific bed if needed. Leave empty to assign any available bed.</div>
          </div>
          <div class="col-md-6" id="attending_physician_section" style="display: none;">
            <label class="form-label-custom">Attending Physician <span class="text-required">*</span></label>
            <select name="attending_physician_id" id="attending_physician_select" class="form-select form-select-custom" required>
              <option value="">-- Select Attending Physician --</option>
              <?php if (!empty($doctors)): 
                // Group doctors by department
                $doctorsByDept = [];
                $doctorsWithoutDept = [];
                
                foreach ($doctors as $doctor) {
                  if (!empty($doctor['department_name'])) {
                    $deptName = $doctor['department_name'];
                    if (!isset($doctorsByDept[$deptName])) {
                      $doctorsByDept[$deptName] = [];
                    }
                    $doctorsByDept[$deptName][] = $doctor;
                  } else {
                    $doctorsWithoutDept[] = $doctor;
                  }
                }
                
                // Sort departments alphabetically
                ksort($doctorsByDept);
                
                // Display doctors grouped by department
                foreach ($doctorsByDept as $deptName => $deptDoctors): ?>
                  <optgroup label="<?= esc($deptName) ?>">
                    <?php foreach ($deptDoctors as $doctor): ?>
                      <option value="<?= esc($doctor['id']) ?>" <?= set_select('attending_physician_id', $doctor['id'], old('attending_physician_id') == $doctor['id']) ?>>
                        Dr. <?= esc($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                        <?php if (!empty($doctor['specialization'])): ?>
                          - <?= esc($doctor['specialization']) ?>
                        <?php endif; ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endforeach; ?>
                
                <?php if (!empty($doctorsWithoutDept)): ?>
                  <optgroup label="Other / No Department">
                    <?php foreach ($doctorsWithoutDept as $doctor): ?>
                      <option value="<?= esc($doctor['id']) ?>" <?= set_select('attending_physician_id', $doctor['id'], old('attending_physician_id') == $doctor['id']) ?>>
                        Dr. <?= esc($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                        <?php if (!empty($doctor['specialization'])): ?>
                          - <?= esc($doctor['specialization']) ?>
                        <?php endif; ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endif; ?>
              <?php endif; ?>
            </select>
            <div class="text-hint">Select the attending physician for this in-patient admission. Doctors are grouped by department.</div>
          </div>
          <div class="col-md-6" id="admission_reason_section" style="display: none;">
            <label class="form-label-custom">Admission Reason <span class="text-required">*</span></label>
            <textarea name="admission_reason" id="admission_reason_input" class="form-control form-control-custom" rows="2" placeholder="Enter reason for admission" required></textarea>
            <div class="text-hint">Brief description of why the patient is being admitted.</div>
          </div>
        </div>

        <!-- Insurance Information Section -->
        <h5 class="section-header">
          <i class="fas fa-shield-alt me-2"></i>Insurance Information (Optional)
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label-custom">Insurance Provider</label>
            <select name="insurance_provider" class="form-select form-select-custom">
              <option value="">-- Select Insurance Provider --</option>
              <option value="PhilHealth" <?= set_select('insurance_provider', 'PhilHealth', old('insurance_provider') == 'PhilHealth') ?>>PhilHealth</option>
              <option value="Maxicare" <?= set_select('insurance_provider', 'Maxicare', old('insurance_provider') == 'Maxicare') ?>>Maxicare</option>
              <option value="Medicard" <?= set_select('insurance_provider', 'Medicard', old('insurance_provider') == 'Medicard') ?>>Medicard</option>
              <option value="Intellicare" <?= set_select('insurance_provider', 'Intellicare', old('insurance_provider') == 'Intellicare') ?>>Intellicare</option>
              <option value="Pacific Cross" <?= set_select('insurance_provider', 'Pacific Cross', old('insurance_provider') == 'Pacific Cross') ?>>Pacific Cross</option>
              <option value="Generali" <?= set_select('insurance_provider', 'Generali', old('insurance_provider') == 'Generali') ?>>Generali</option>
              <option value="Other" <?= set_select('insurance_provider', 'Other', old('insurance_provider') == 'Other') ?>>Other / Not Listed</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label-custom">Policy Number</label>
            <input type="text" name="policy_number" class="form-control form-control-custom" value="<?= set_value('policy_number', old('policy_number')) ?>" placeholder="Enter policy or ID number">
          </div>
        </div>

        <!-- Consultation Information Section (for Out-Patients) -->
        <div id="consultation_section">
            <h5 class="section-header">
              <i class="fas fa-stethoscope me-2"></i>Consultation Information
            </h5>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label-custom">Assign Doctor</label>
                <select name="doctor_id" class="form-select form-select-custom">
                  <option value="">-- Select Doctor --</option>
                  <?php if (!empty($doctors)): ?>
                    <?php foreach ($doctors as $doctor): ?>
                      <option value="<?= esc($doctor['id']) ?>" <?= set_select('doctor_id', $doctor['id'], old('doctor_id') == $doctor['id']) ?>>
                        <?= esc($doctor['first_name'] . ' ' . $doctor['last_name']) ?> (<?= esc(ucfirst($doctor['specialization'] ?? 'General')) ?>)
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label-custom">Appointment Date</label>
                <input type="datetime-local" name="appointment_date" class="form-control form-control-custom" value="<?= set_value('appointment_date', old('appointment_date')) ?>">
              </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-3 mt-4 pt-3 border-top">
          <button type="submit" class="btn btn-primary-custom">
            <i class="fas fa-save me-2"></i>Register Patient
          </button>
          <a href="<?= site_url('dashboard/receptionist') ?>" class="btn btn-secondary-custom">
            <i class="fas fa-times me-2"></i>Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Toggle room selection based on admission type
  const admissionSelect = document.getElementById('admission_type_select');
  const roomSection = document.getElementById('room_selection_section');
  const bedSection = document.getElementById('bed_selection_section');
  const consultationSection = document.getElementById('consultation_section');
  const roomSelect = document.getElementById('room_select');
  const bedSelect = document.getElementById('bed_select');
  
  function toggleSections() {
      const admissionType = admissionSelect.value;
      
      if (roomSection) {
          roomSection.style.display = admissionType === 'admission' ? 'block' : 'none';
      }
      
      if (bedSection) {
          bedSection.style.display = admissionType === 'admission' ? 'block' : 'none';
      }
      
      const attendingPhysicianSection = document.getElementById('attending_physician_section');
      const admissionReasonSection = document.getElementById('admission_reason_section');
      
      if (attendingPhysicianSection) {
          attendingPhysicianSection.style.display = admissionType === 'admission' ? 'block' : 'none';
      }
      
      if (admissionReasonSection) {
          admissionReasonSection.style.display = admissionType === 'admission' ? 'block' : 'none';
      }
      
      if (consultationSection) {
          consultationSection.style.display = admissionType === 'checkup' ? 'block' : 'none';
      }
      
      // Reset bed selection when switching admission types
      if (admissionType !== 'admission' && bedSelect) {
          bedSelect.innerHTML = '<option value="">-- Select Bed (Optional) --</option>';
      }
      
      // Make room selection required/optional based on admission type
      if (roomSelect) {
          roomSelect.required = admissionType === 'admission';
      }
      
      // Make attending physician required/optional based on admission type
      const attendingPhysicianSelect = document.getElementById('attending_physician_select');
      if (attendingPhysicianSelect) {
          attendingPhysicianSelect.required = admissionType === 'admission';
      }
      
      // Make admission reason required/optional based on admission type
      const admissionReasonInput = document.getElementById('admission_reason_input');
      if (admissionReasonInput) {
          admissionReasonInput.required = admissionType === 'admission';
      }
  }
  
  // Load beds when room is selected
  if (roomSelect) {
      roomSelect.addEventListener('change', function() {
          const roomId = this.value;
          
          // Reset bed selection
          if (bedSelect) {
              bedSelect.innerHTML = '<option value="">Loading beds...</option>';
          }
          
          if (roomId) {
              // Fetch beds for selected room
              fetch('<?= site_url('reception/get-beds-by-room') ?>/' + roomId)
                  .then(response => response.json())
                  .then(data => {
                      if (bedSelect) {
                          bedSelect.innerHTML = '<option value="">-- Select Bed (Optional) --</option>';
                          
                          if (data.success && data.beds && data.beds.length > 0) {
                              data.beds.forEach(bed => {
                                  const option = document.createElement('option');
                                  option.value = bed.id;
                                  option.textContent = bed.bed_number + ' (' + (bed.bed_type || 'Standard') + ')';
                                  bedSelect.appendChild(option);
                              });
                          } else {
                              const option = document.createElement('option');
                              option.value = '';
                              option.textContent = 'No available beds in this room';
                              option.disabled = true;
                              bedSelect.appendChild(option);
                          }
                      }
                  })
                  .catch(error => {
                      console.error('Error loading beds:', error);
                      if (bedSelect) {
                          bedSelect.innerHTML = '<option value="">-- Select Bed (Optional) --</option>';
                      }
                  });
          } else {
              if (bedSelect) {
                  bedSelect.innerHTML = '<option value="">-- Select Bed (Optional) --</option>';
              }
          }
      });
  }
  
  if (admissionSelect) {
      admissionSelect.addEventListener('change', toggleSections);
      toggleSections(); // Initial check on page load
  }
});
</script>

</body>
</html>
