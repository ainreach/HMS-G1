<?php
helper('form');
$errors = session('errors') ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Patient | HMS - Admin</title>
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
      max-width: 1400px;
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

    .age-display {
      font-size: 0.875rem;
      color: var(--primary-blue);
      font-weight: 600;
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
    <a href="<?= site_url('admin/patients') ?>" class="btn btn-secondary-custom">
      <i class="fas fa-arrow-left me-2"></i>Back to Patients
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
      <form method="post" action="<?= site_url('admin/patients') ?>" id="patientForm">
        <?= csrf_field() ?>

        <!-- Personal Information Section -->
        <h5 class="section-header">
          <i class="fas fa-id-card"></i>
          <span>Personal Information</span>
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
                   class="form-control form-control-custom" 
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
          <div class="col-md-3">
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
          <div class="col-md-3">
            <label class="form-label-custom">Date of Birth <span class="text-required">*</span></label>
            <input type="date" name="date_of_birth" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('date_of_birth', old('date_of_birth')) ?>" 
                   id="dob_input" required>
            <div class="age-display" id="age_display"></div>
          </div>
          <div class="col-md-3">
            <label class="form-label-custom">Marital Status</label>
            <select name="marital_status" class="form-select form-select-custom">
              <option value="">-- Select --</option>
              <option value="single" <?= set_select('marital_status', 'single', old('marital_status') == 'single') ?>>Single</option>
              <option value="married" <?= set_select('marital_status', 'married', old('marital_status') == 'married') ?>>Married</option>
              <option value="divorced" <?= set_select('marital_status', 'divorced', old('marital_status') == 'divorced') ?>>Divorced</option>
              <option value="widowed" <?= set_select('marital_status', 'widowed', old('marital_status') == 'widowed') ?>>Widowed</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label-custom">Blood Type</label>
            <select name="blood_type" class="form-select form-select-custom">
              <option value="">-- Select --</option>
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
        </div>

        <!-- Contact Information Section -->
        <h5 class="section-header">
          <i class="fas fa-address-book"></i>
          <span>Contact Information</span>
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label-custom">Phone Number <span class="text-required">*</span></label>
            <input type="tel" name="phone" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('phone', old('phone')) ?>" 
                   placeholder="09XX-XXX-XXXX" required>
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Email Address</label>
            <input type="email" name="email" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('email', old('email')) ?>" 
                   placeholder="patient@example.com">
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">City</label>
            <input type="text" name="city" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('city', old('city')) ?>" 
                   placeholder="City">
          </div>
          <div class="col-md-12">
            <label class="form-label-custom">Complete Address</label>
            <textarea name="address" 
                      class="form-control form-control-custom" 
                      rows="2" 
                      placeholder="Street, Barangay, City, Province"><?= set_value('address', old('address')) ?></textarea>
          </div>
        </div>

        <!-- Emergency Contact Section -->
        <h5 class="section-header">
          <i class="fas fa-exclamation-triangle"></i>
          <span>Emergency Contact Information</span>
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label-custom">Emergency Contact Name <span class="text-required">*</span></label>
            <input type="text" name="emergency_contact_name" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('emergency_contact_name', old('emergency_contact_name')) ?>" 
                   placeholder="Full Name" required>
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Relationship <span class="text-required">*</span></label>
            <select name="emergency_contact_relation" class="form-select form-select-custom" required>
              <option value="">-- Select Relationship --</option>
              <option value="spouse" <?= set_select('emergency_contact_relation', 'spouse', old('emergency_contact_relation') == 'spouse') ?>>Spouse</option>
              <option value="parent" <?= set_select('emergency_contact_relation', 'parent', old('emergency_contact_relation') == 'parent') ?>>Parent</option>
              <option value="sibling" <?= set_select('emergency_contact_relation', 'sibling', old('emergency_contact_relation') == 'sibling') ?>>Sibling</option>
              <option value="child" <?= set_select('emergency_contact_relation', 'child', old('emergency_contact_relation') == 'child') ?>>Child</option>
              <option value="relative" <?= set_select('emergency_contact_relation', 'relative', old('emergency_contact_relation') == 'relative') ?>>Relative</option>
              <option value="friend" <?= set_select('emergency_contact_relation', 'friend', old('emergency_contact_relation') == 'friend') ?>>Friend</option>
              <option value="other" <?= set_select('emergency_contact_relation', 'other', old('emergency_contact_relation') == 'other') ?>>Other</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Emergency Contact Phone <span class="text-required">*</span></label>
            <input type="tel" name="emergency_contact_phone" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('emergency_contact_phone', old('emergency_contact_phone')) ?>" 
                   placeholder="09XX-XXX-XXXX" required>
          </div>
        </div>

        <!-- Insurance Information Section -->
        <h5 class="section-header">
          <i class="fas fa-shield-alt"></i>
          <span>Insurance Information</span>
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label-custom">Insurance Provider</label>
            <input type="text" name="insurance_provider" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('insurance_provider', old('insurance_provider')) ?>" 
                   placeholder="e.g., PhilHealth, HMO Name">
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Insurance/Policy Number</label>
            <input type="text" name="insurance_number" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('insurance_number', old('insurance_number')) ?>" 
                   placeholder="Policy Number">
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Group Number</label>
            <input type="text" name="insurance_group" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('insurance_group', old('insurance_group')) ?>" 
                   placeholder="Group Number (if applicable)">
          </div>
        </div>

        <!-- Medical Information Section -->
        <h5 class="section-header">
          <i class="fas fa-heartbeat"></i>
          <span>Medical Information</span>
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-12">
            <label class="form-label-custom">Known Allergies</label>
            <textarea name="allergies" 
                      class="form-control form-control-custom" 
                      rows="3" 
                      placeholder="List any known allergies (medications, food, environmental, etc.). If none, enter 'None'"><?= set_value('allergies', old('allergies')) ?></textarea>
            <div class="text-hint">
              <i class="fas fa-info-circle me-1"></i>
              Include all known allergies. This is critical for patient safety.
            </div>
          </div>
          <div class="col-md-12">
            <label class="form-label-custom">Current Medications</label>
            <textarea name="current_medications" 
                      class="form-control form-control-custom" 
                      rows="3" 
                      placeholder="List all current medications, dosages, and frequency. If none, enter 'None'"><?= set_value('current_medications', old('current_medications')) ?></textarea>
          </div>
          <div class="col-md-12">
            <label class="form-label-custom">Medical History</label>
            <textarea name="medical_history" 
                      class="form-control form-control-custom" 
                      rows="3" 
                      placeholder="Previous medical conditions, surgeries, chronic illnesses, etc."><?= set_value('medical_history', old('medical_history')) ?></textarea>
          </div>
          <div class="col-md-12">
            <label class="form-label-custom">Family Medical History</label>
            <textarea name="family_history" 
                      class="form-control form-control-custom" 
                      rows="2" 
                      placeholder="Significant family medical history (diabetes, heart disease, cancer, etc.)"><?= set_value('family_history', old('family_history')) ?></textarea>
          </div>
          <div class="col-md-12">
            <label class="form-label-custom">Surgical History</label>
            <textarea name="surgical_history" 
                      class="form-control form-control-custom" 
                      rows="2" 
                      placeholder="Previous surgeries and dates (if applicable)"><?= set_value('surgical_history', old('surgical_history')) ?></textarea>
          </div>
        </div>

        <!-- Additional Information Section -->
        <h5 class="section-header">
          <i class="fas fa-info-circle"></i>
          <span>Additional Information</span>
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label-custom">Occupation</label>
            <input type="text" name="occupation" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('occupation', old('occupation')) ?>" 
                   placeholder="Occupation">
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Employer</label>
            <input type="text" name="employer" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('employer', old('employer')) ?>" 
                   placeholder="Employer Name">
          </div>
          <div class="col-md-4">
            <label class="form-label-custom">Referred By</label>
            <input type="text" name="referred_by" 
                   class="form-control form-control-custom" 
                   value="<?= set_value('referred_by', old('referred_by')) ?>" 
                   placeholder="Doctor/Clinic Name">
          </div>
        </div>

        <!-- Admission Information Section -->
        <h5 class="section-header">
          <i class="fas fa-hospital"></i>
          <span>Admission Information</span>
        </h5>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label-custom">Admission Type</label>
            <select name="admission_type" id="admission_type_select" class="form-select form-select-custom">
              <option value="checkup" <?= set_select('admission_type', 'checkup', old('admission_type') == 'checkup' || old('admission_type') == '') ?>>Out-Patient (Check-up)</option>
              <option value="admission" <?= set_select('admission_type', 'admission', old('admission_type') == 'admission') ?>>In-Patient (Admission)</option>
            </select>
            <div class="text-hint">
              <i class="fas fa-info-circle me-1"></i>
              Select admission type. In-Patient requires room assignment by doctor.
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-3 mt-4 pt-4 border-top">
          <button type="submit" class="btn btn-primary-custom">
            <i class="fas fa-save me-2"></i>Register Patient
          </button>
          <a href="<?= site_url('admin/patients') ?>" class="btn btn-secondary-custom">
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
  // Calculate age from date of birth
  const dobInput = document.getElementById('dob_input');
  const ageDisplay = document.getElementById('age_display');
  
  function calculateAge() {
    if (dobInput.value) {
      const birthDate = new Date(dobInput.value);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      
      if (age >= 0) {
        ageDisplay.textContent = `Age: ${age} years old`;
        ageDisplay.style.display = 'block';
      } else {
        ageDisplay.textContent = 'Invalid date';
        ageDisplay.style.color = '#dc2626';
      }
    } else {
      ageDisplay.style.display = 'none';
    }
  }
  
  dobInput.addEventListener('change', calculateAge);
  
  // Form validation
  const form = document.getElementById('patientForm');
  form.addEventListener('submit', function(e) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        isValid = false;
        field.classList.add('is-invalid');
      } else {
        field.classList.remove('is-invalid');
      }
    });
    
    if (!isValid) {
      e.preventDefault();
      alert('Please fill in all required fields marked with *');
    }
  });
});
</script>

</body>
</html>
