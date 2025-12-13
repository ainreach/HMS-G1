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
<<<<<<< HEAD
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
=======
>>>>>>> 477d71103649dd21bfaa63da0fa31e258e950254
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
<<<<<<< HEAD
      max-width: 1200px;
=======
      max-width: 1400px;
>>>>>>> 477d71103649dd21bfaa63da0fa31e258e950254
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
<<<<<<< HEAD
      padding: 24px;
=======
      padding: 32px;
>>>>>>> 477d71103649dd21bfaa63da0fa31e258e950254
    }

    .section-header {
      font-weight: 700;
<<<<<<< HEAD
      font-size: 1.05rem;
      color: var(--text-dark);
      margin: 20px 0 12px;
      padding: 10px 14px;
      background: var(--bg-light);
      border-left: 4px solid var(--primary-blue);
      border-radius: 8px;
=======
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
>>>>>>> 477d71103649dd21bfaa63da0fa31e258e950254
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
<<<<<<< HEAD
      padding: 11px 24px;
=======
      padding: 12px 28px;
>>>>>>> 477d71103649dd21bfaa63da0fa31e258e950254
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
<<<<<<< HEAD
      padding: 11px 24px;
=======
      padding: 12px 28px;
>>>>>>> 477d71103649dd21bfaa63da0fa31e258e950254
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

<<<<<<< HEAD
=======
    .age-display {
      font-size: 0.875rem;
      color: var(--primary-blue);
      font-weight: 600;
      margin-top: 4px;
    }

>>>>>>> 477d71103649dd21bfaa63da0fa31e258e950254
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
<<<<<<< HEAD
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/admin') ?>">
        <i class="fas fa-tachometer-alt"></i> Overview
      </a>
      <a href="<?= site_url('admin/patients') ?>" class="active">
        <i class="fas fa-user-injured"></i> Patients
      </a>
      <a href="<?= site_url('admin/users') ?>">
        <i class="fas fa-users-cog"></i> Users
      </a>
      <a href="<?= site_url('admin/appointments') ?>">
        <i class="fas fa-calendar-check"></i> Appointments
      </a>
    </nav>
  </aside>
  
  <main class="content">
    <div class="registration-wrapper">
      <div class="page-header-section">
        <h1 class="page-title-main">
          <i class="fas fa-user-plus me-2"></i>Add New Patient
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
          <form method="post" action="<?= site_url('admin/patients') ?>">
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
                <label class="form-label-custom">Room Assignment</label>
                <select name="assigned_room_id" class="form-select form-select-custom">
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
            </div>

            <!-- Insurance Information Section -->
            <h5 class="section-header">
              <i class="fas fa-shield-alt me-2"></i>Insurance Information (Optional)
            </h5>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="form-label-custom">Insurance Provider</label>
                <input type="text" name="insurance_provider" class="form-control form-control-custom" value="<?= set_value('insurance_provider', old('insurance_provider')) ?>" placeholder="e.g., PhilHealth, Maxicare">
              </div>
              <div class="col-md-6">
                <label class="form-label-custom">Policy Number</label>
                <input type="text" name="insurance_number" class="form-control form-control-custom" value="<?= set_value('insurance_number', old('insurance_number')) ?>" placeholder="Enter policy or ID number">
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
                <i class="fas fa-save me-2"></i>Create Patient
              </button>
              <a href="<?= site_url('admin/patients') ?>" class="btn btn-secondary-custom">
                <i class="fas fa-times me-2"></i>Cancel
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
=======
>>>>>>> 477d71103649dd21bfaa63da0fa31e258e950254
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
<<<<<<< HEAD
  // Toggle room selection based on admission type
  const admissionSelect = document.getElementById('admission_type_select');
  const roomSection = document.getElementById('room_selection_section');
  const consultationSection = document.getElementById('consultation_section');
  
  function toggleSections() {
      const admissionType = admissionSelect.value;
      
      if (roomSection) {
          roomSection.style.display = admissionType === 'admission' ? 'block' : 'none';
      }
      
      if (consultationSection) {
          consultationSection.style.display = admissionType === 'checkup' ? 'block' : 'none';
      }
  }
  
  if (admissionSelect) {
      admissionSelect.addEventListener('change', toggleSections);
      toggleSections(); // Initial check on page load
  }
});
</script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
=======
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

>>>>>>> 477d71103649dd21bfaa63da0fa31e258e950254
</body>
</html>
