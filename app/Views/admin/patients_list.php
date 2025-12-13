<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Statistics Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px">
  <div class="panel" style="border-left:4px solid #3b82f6">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Total Patients</p>
        <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($stats['total'] ?? 0) ?></h3>
      </div>
<<<<<<< HEAD
      <div class="panel-body">
        <?php if (!empty($patients)): ?>
          <table class="table">
            <thead>
              <tr>
                <th>Patient ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($patients as $patient): ?>
                <tr>
                  <td><?= esc($patient['patient_id']) ?></td>
                  <td><a href="<?= site_url('admin/patients/view/' . $patient['id']) ?>"><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></a></td>
                  <td><?= esc($patient['phone'] ?? 'N/A') ?></td>
                  <td><?= esc($patient['email'] ?? 'N/A') ?></td>
                  <td><?= date('M j, Y', strtotime($patient['created_at'])) ?></td>
                  <td>
                    <a href="<?= site_url('admin/patients/view/' . $patient['id']) ?>" class="btn btn-info" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                      <i class="fas fa-eye"></i> View
                    </a>
                    <a href="<?= site_url('admin/patients/edit/' . $patient['id']) ?>" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="<?= site_url('admin/patients/delete/' . $patient['id']) ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="return confirm('Are you sure you want to delete this patient?')">
                      <i class="fas fa-trash"></i> Delete
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          
          <!-- Pagination -->
          <?php if ($total > $perPage): ?>
            <div class="pagination">
              <?php if ($hasPrev): ?>
                <a href="?page=<?= $page - 1 ?>">
                  <i class="fas fa-chevron-left"></i> Previous
                </a>
              <?php endif; ?>
              
              <span class="current">Page <?= $page ?> of <?= ceil($total / $perPage) ?></span>
              
              <?php if ($hasNext): ?>
                <a href="?page=<?= $page + 1 ?>">
                  Next <i class="fas fa-chevron-right"></i>
                </a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <div style="text-align: center; padding: 2rem; color: #6b7280;">
            <i class="fas fa-user-injured" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3>No patients found</h3>
            <p>Start by adding your first patient to the system.</p>
            <button type="button" class="btn btn-success" style="margin-top: 1rem;" onclick="showPatientModal()">
              <i class="fas fa-plus"></i> Add First Patient
            </button>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Add Patient Modal -->
    <div id="addPatientModal" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(15,23,42,0.35);z-index:50;">
      <div style="width:95%;max-width:1200px;background:white;border-radius:8px;box-shadow:0 20px 40px rgba(15,23,42,0.35);max-height:95vh;overflow-y:auto;">
        <div style="background:#2563eb;color:white;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #1d4ed8;">
          <h3 style="margin:0;font-size:1.1rem;"><i class="fas fa-user-plus me-2"></i>Add New Patient</h3>
          <button type="button" onclick="closePatientModal()" style="background:none;border:none;font-size:20px;color:white;cursor:pointer;">&times;</button>
        </div>
        <div style="padding:24px;">
          <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;">
              <i class="fas fa-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
            </div>
          <?php endif; ?>

          <?php 
          helper('form');
          $errors = session('errors') ?? [];
          ?>
          
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
          <style>
            .section-header {
              font-weight: 700;
              font-size: 1.05rem;
              color: #1f2937;
              margin: 20px 0 12px;
              padding: 10px 14px;
              background: #f8fafc;
              border-left: 4px solid #2563eb;
              border-radius: 8px;
            }
            .section-header:first-child {
              margin-top: 0;
            }
            .form-label-custom {
              font-weight: 600;
              color: #1f2937;
              margin-bottom: 6px;
              font-size: 0.95rem;
            }
            .form-control-custom,
            .form-select-custom {
              border: 1.5px solid #e5e7eb;
              border-radius: 10px;
              padding: 11px 15px;
              font-size: 0.95rem;
              transition: all 0.2s ease;
              background: #fff;
            }
            .form-control-custom:focus,
            .form-select-custom:focus {
              border-color: #2563eb;
              outline: none;
              box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.3);
            }
            .text-required {
              color: #dc2626;
              font-weight: 600;
            }
            .text-hint {
              font-size: 0.875rem;
              color: #6b7280;
              margin-top: 4px;
            }
            .btn-primary-custom {
              background: #2563eb;
              border-color: #2563eb;
              color: #fff;
              padding: 11px 24px;
              font-weight: 600;
              border-radius: 10px;
              transition: all 0.2s ease;
              box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            }
            .btn-primary-custom:hover {
              background: #1e40af;
              border-color: #1e40af;
              transform: translateY(-1px);
              box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
            }
            .btn-secondary-custom {
              background: #fff;
              border: 1.5px solid #e5e7eb;
              color: #1f2937;
              padding: 11px 24px;
              font-weight: 600;
              border-radius: 10px;
              transition: all 0.2s ease;
            }
            .btn-secondary-custom:hover {
              background: #f8fafc;
              border-color: #cbd5e1;
            }
          </style>

          <form id="addPatientForm" method="POST" action="<?= site_url('admin/patients') ?>">
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
                  <div style="display:block;margin-top:4px;font-size:0.875rem;color:#dc2626;"><?= esc($errors['first_name']) ?></div>
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
                  <div style="display:block;margin-top:4px;font-size:0.875rem;color:#dc2626;"><?= esc($errors['last_name']) ?></div>
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
                  <div style="display:block;margin-top:4px;font-size:0.875rem;color:#dc2626;"><?= esc($errors['gender']) ?></div>
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
              <button type="button" class="btn btn-secondary-custom" onclick="closePatientModal()">
                <i class="fas fa-times me-2"></i>Cancel
              </button>
            </div>
          </form>
        </div>
=======
      <div style="width:48px;height:48px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-users" style="font-size:24px;color:#3b82f6"></i>
>>>>>>> 477d71103649dd21bfaa63da0fa31e258e950254
      </div>
    </div>
  </div>
  
  <div class="panel" style="border-left:4px solid #3b82f6">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Admitted</p>
        <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($stats['admitted'] ?? 0) ?></h3>
      </div>
      <div style="width:48px;height:48px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-bed" style="font-size:24px;color:#3b82f6"></i>
      </div>
    </div>
  </div>
  
  <div class="panel" style="border-left:4px solid #16a34a">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Discharged</p>
        <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($stats['discharged'] ?? 0) ?></h3>
      </div>
      <div style="width:48px;height:48px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-check-circle" style="font-size:24px;color:#16a34a"></i>
      </div>
    </div>
  </div>
  
  <div class="panel" style="border-left:4px solid #6b7280">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <div>
        <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Out-Patients</p>
        <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($stats['outpatient'] ?? 0) ?></h3>
      </div>
      <div style="width:48px;height:48px;background:#f3f4f6;border-radius:12px;display:flex;align-items:center;justify-content:center">
        <i class="fa-solid fa-user" style="font-size:24px;color:#6b7280"></i>
      </div>
    </div>
  </div>
</div>

<!-- Main Panel -->
<section class="panel">
  <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
    <div>
      <h2 style="margin:0;font-size:1.25rem;font-weight:600">Patient Records</h2>
      <p style="margin:4px 0 0 0;color:#6b7280;font-size:0.875rem">
        Showing <?= number_format($total) ?> patient<?= $total != 1 ? 's' : '' ?>
        <?php if (!empty($search)): ?>
          <span style="color:#3b82f6">matching "<?= esc($search) ?>"</span>
        <?php endif; ?>
      </p>
    </div>
    <button type="button" class="btn btn-success" onclick="showPatientModal()" style="display:inline-flex;align-items:center;gap:6px">
      <i class="fa-solid fa-plus"></i> Add New Patient
    </button>
  </div>

  <!-- Search and Filter Section -->
  <div class="panel-body" style="background:#f9fafb;border-bottom:1px solid #e5e7eb;padding:16px">
    <form method="GET" action="<?= site_url('admin/patients') ?>" id="filterForm" style="display:grid;gap:12px">
      <!-- Search Bar -->
      <div style="display:grid;grid-template-columns:1fr auto;gap:8px">
        <div style="position:relative">
          <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
          <input 
            type="text" 
            name="search" 
            value="<?= esc($search ?? '') ?>" 
            placeholder="Search by name, patient ID, phone, or email..." 
            style="width:100%;padding:10px 12px 10px 40px;border:1px solid #d1d5db;border-radius:8px;font-size:0.875rem"
            aria-label="Search patients"
          >
        </div>
        <button type="submit" class="btn" style="padding:10px 20px;white-space:nowrap">
          <i class="fa-solid fa-search"></i> Search
        </button>
      </div>

      <!-- Filter Row -->
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px">
        <!-- Status Filter -->
        <div>
          <label for="status" style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#374151">Status</label>
          <select name="status" id="status" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;background:white">
            <option value="all" <?= ($statusFilter ?? 'all') === 'all' ? 'selected' : '' ?>>All Status</option>
            <option value="admitted" <?= ($statusFilter ?? '') === 'admitted' ? 'selected' : '' ?>>Admitted</option>
            <option value="discharged" <?= ($statusFilter ?? '') === 'discharged' ? 'selected' : '' ?>>Discharged</option>
            <option value="outpatient" <?= ($statusFilter ?? '') === 'outpatient' ? 'selected' : '' ?>>Out-Patient</option>
          </select>
        </div>

        <!-- Gender Filter -->
        <div>
          <label for="gender" style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#374151">Gender</label>
          <select name="gender" id="gender" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;background:white">
            <option value="all" <?= ($genderFilter ?? 'all') === 'all' ? 'selected' : '' ?>>All Genders</option>
            <option value="male" <?= ($genderFilter ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= ($genderFilter ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
            <option value="other" <?= ($genderFilter ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
          </select>
        </div>

        <!-- Date From -->
        <div>
          <label for="date_from" style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#374151">Date From</label>
          <input 
            type="date" 
            name="date_from" 
            id="date_from" 
            value="<?= esc($dateFrom ?? '') ?>" 
            onchange="document.getElementById('filterForm').submit()"
            style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem"
          >
        </div>

        <!-- Date To -->
        <div>
          <label for="date_to" style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#374151">Date To</label>
          <input 
            type="date" 
            name="date_to" 
            id="date_to" 
            value="<?= esc($dateTo ?? '') ?>" 
            onchange="document.getElementById('filterForm').submit()"
            style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem"
          >
        </div>

        <!-- Per Page -->
        <div>
          <label for="per_page" style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#374151">Per Page</label>
          <select name="per_page" id="per_page" onchange="document.getElementById('filterForm').submit()" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;background:white">
            <option value="10" <?= ($perPage ?? 25) == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= ($perPage ?? 25) == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= ($perPage ?? 25) == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= ($perPage ?? 25) == 100 ? 'selected' : '' ?>>100</option>
          </select>
        </div>

        <!-- Clear Filters -->
        <div style="display:flex;align-items:flex-end">
          <a href="<?= site_url('admin/patients') ?>" class="btn" style="width:100%;padding:8px;background:#6b7280;color:white;text-decoration:none;border-radius:6px;text-align:center;font-size:0.875rem">
            <i class="fa-solid fa-xmark"></i> Clear
          </a>
        </div>
      </div>

      <!-- Preserve other filters in hidden inputs -->
      <input type="hidden" name="sort" value="<?= esc($sortBy ?? 'created_at') ?>">
      <input type="hidden" name="order" value="<?= esc($sortOrder ?? 'DESC') ?>">
    </form>
  </div>

  <!-- Table Section -->
  <div class="panel-body" style="padding:0;overflow-x:auto">
    <?php if (!empty($patients)): ?>
      <table class="table" style="width:100%;border-collapse:collapse" role="table" aria-label="Patient records table">
        <thead>
          <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb">
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['sort' => 'patient_id', 'order' => ($sortBy === 'patient_id' && $sortOrder === 'ASC') ? 'DESC' : 'ASC']))) ?>" 
                 style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:6px">
                Patient ID
                <?php if ($sortBy === 'patient_id'): ?>
                  <i class="fa-solid fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>" style="font-size:0.75rem"></i>
                <?php else: ?>
                  <i class="fa-solid fa-sort" style="font-size:0.75rem;opacity:0.3"></i>
                <?php endif; ?>
              </a>
            </th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['sort' => 'last_name', 'order' => ($sortBy === 'last_name' && $sortOrder === 'ASC') ? 'DESC' : 'ASC']))) ?>" 
                 style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:6px">
                Name
                <?php if ($sortBy === 'last_name'): ?>
                  <i class="fa-solid fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>" style="font-size:0.75rem"></i>
                <?php else: ?>
                  <i class="fa-solid fa-sort" style="font-size:0.75rem;opacity:0.3"></i>
                <?php endif; ?>
              </a>
            </th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">Gender</th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">Contact</th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">Status</th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">Room/Bed</th>
            <th style="text-align:left;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['sort' => 'created_at', 'order' => ($sortBy === 'created_at' && $sortOrder === 'ASC') ? 'DESC' : 'ASC']))) ?>" 
                 style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:6px">
                Registered
                <?php if ($sortBy === 'created_at'): ?>
                  <i class="fa-solid fa-sort-<?= $sortOrder === 'ASC' ? 'up' : 'down' ?>" style="font-size:0.75rem"></i>
                <?php else: ?>
                  <i class="fa-solid fa-sort" style="font-size:0.75rem;opacity:0.3"></i>
                <?php endif; ?>
              </a>
            </th>
            <th style="text-align:center;padding:12px;font-weight:600;font-size:0.875rem;color:#374151;white-space:nowrap">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($patients as $patient): ?>
            <tr style="border-bottom:1px solid #f3f4f6;transition:background 0.15s" 
                onmouseover="this.style.background='#f9fafb'" 
                onmouseout="this.style.background=''"
                role="row">
              <td style="padding:12px;font-size:0.875rem">
                <span style="font-weight:600;color:#3b82f6"><?= esc($patient['patient_id'] ?? 'N/A') ?></span>
              </td>
              <td style="padding:12px;font-size:0.875rem">
                <div style="display:flex;align-items:center;gap:8px">
                  <div style="width:36px;height:36px;background:#e5e7eb;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fa-solid fa-user" style="color:#6b7280;font-size:0.875rem"></i>
                  </div>
                  <div>
                    <a href="<?= site_url('admin/patients/view/' . $patient['id']) ?>" 
                       style="font-weight:600;color:#111827;text-decoration:none;display:block"
                       onmouseover="this.style.color='#3b82f6'" 
                       onmouseout="this.style.color='#111827'">
                      <?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?>
                    </a>
                    <?php if (!empty($patient['date_of_birth'])): ?>
                      <small style="color:#6b7280;font-size:0.75rem">
                        DOB: <?= esc(date('M d, Y', strtotime($patient['date_of_birth']))) ?>
                      </small>
                    <?php endif; ?>
                  </div>
                </div>
              </td>
              <td style="padding:12px;font-size:0.875rem">
                <span style="padding:4px 10px;background:#f3f4f6;color:#374151;border-radius:12px;font-size:0.75rem;font-weight:500;text-transform:uppercase">
                  <?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?>
                </span>
              </td>
              <td style="padding:12px;font-size:0.875rem">
                <div style="color:#374151">
                  <?php if (!empty($patient['phone'])): ?>
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
                      <i class="fa-solid fa-phone" style="color:#6b7280;font-size:0.75rem"></i>
                      <span><?= esc($patient['phone']) ?></span>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($patient['email'])): ?>
                    <div style="display:flex;align-items:center;gap:6px">
                      <i class="fa-solid fa-envelope" style="color:#6b7280;font-size:0.75rem"></i>
                      <span style="color:#6b7280;font-size:0.8125rem"><?= esc($patient['email']) ?></span>
                    </div>
                  <?php endif; ?>
                  <?php if (empty($patient['phone']) && empty($patient['email'])): ?>
                    <span style="color:#9ca3af">N/A</span>
                  <?php endif; ?>
                </div>
              </td>
              <td style="padding:12px;font-size:0.875rem">
                <span style="padding:6px 12px;background:<?= esc($patient['status_color'] ?? '#6b7280') ?>20;color:<?= esc($patient['status_color'] ?? '#6b7280') ?>;border-radius:6px;font-size:0.75rem;font-weight:600;display:inline-flex;align-items:center;gap:6px">
                  <span style="width:8px;height:8px;background:<?= esc($patient['status_color'] ?? '#6b7280') ?>;border-radius:50%"></span>
                  <?= esc($patient['status_label'] ?? 'N/A') ?>
                </span>
              </td>
              <td style="padding:12px;font-size:0.875rem">
                <?php if (!empty($patient['room_display']) && $patient['room_display'] !== 'N/A'): ?>
                  <div style="display:flex;align-items:center;gap:6px;color:#374151">
                    <i class="fa-solid fa-bed" style="color:#3b82f6"></i>
                    <span><?= esc($patient['room_display']) ?></span>
                  </div>
                <?php else: ?>
                  <span style="color:#9ca3af">—</span>
                <?php endif; ?>
              </td>
              <td style="padding:12px;font-size:0.875rem;color:#6b7280">
                <?= esc(date('M d, Y', strtotime($patient['created_at'] ?? 'now'))) ?>
                <br>
                <small style="font-size:0.75rem"><?= esc(date('h:i A', strtotime($patient['created_at'] ?? 'now'))) ?></small>
              </td>
              <td style="padding:12px;text-align:center">
                <div style="display:flex;gap:4px;justify-content:center;flex-wrap:wrap">
                  <a href="<?= site_url('admin/patients/view/' . $patient['id']) ?>" 
                     class="btn btn-sm btn-info" 
                     style="padding:6px 10px;font-size:0.75rem;text-decoration:none;border-radius:4px"
                     title="View Patient Details"
                     aria-label="View patient <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  <a href="<?= site_url('admin/patients/edit/' . $patient['id']) ?>" 
                     class="btn btn-sm btn-warning" 
                     style="padding:6px 10px;font-size:0.75rem;text-decoration:none;border-radius:4px"
                     title="Edit Patient"
                     aria-label="Edit patient <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>">
                    <i class="fa-solid fa-pencil"></i>
                  </a>
                  <?php if ($patient['status'] === 'admitted'): ?>
                    <a href="<?= site_url('doctor/discharge-patients') ?>" 
                       class="btn btn-sm" 
                       style="padding:6px 10px;font-size:0.75rem;text-decoration:none;border-radius:4px;background:#16a34a;color:white"
                       title="Discharge Patient"
                       aria-label="Discharge patient <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>">
                      <i class="fa-solid fa-sign-out-alt"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div style="text-align:center;padding:60px 20px;color:#6b7280">
        <i class="fa-solid fa-user-injured" style="font-size:4rem;margin-bottom:16px;opacity:0.3"></i>
        <h3 style="margin:0 0 8px 0;font-size:1.125rem;font-weight:600;color:#374151">No patients found</h3>
        <p style="margin:0 0 24px 0;font-size:0.875rem">
          <?php if (!empty($search) || $statusFilter !== 'all'): ?>
            Try adjusting your search or filters.
          <?php else: ?>
            Start by adding your first patient to the system.
          <?php endif; ?>
        </p>
        <?php if (empty($search) && $statusFilter === 'all'): ?>
          <button type="button" class="btn btn-success" onclick="showPatientModal()" style="display:inline-flex;align-items:center;gap:6px">
            <i class="fa-solid fa-plus"></i> Add First Patient
          </button>
        <?php else: ?>
          <a href="<?= site_url('admin/patients') ?>" class="btn" style="display:inline-flex;align-items:center;gap:6px;text-decoration:none">
            <i class="fa-solid fa-xmark"></i> Clear Filters
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
    <div class="panel-body" style="border-top:1px solid #e5e7eb;padding:16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;background:#f9fafb">
      <div style="color:#6b7280;font-size:0.875rem">
        Showing <strong><?= number_format($offset + 1) ?></strong> to <strong><?= number_format(min($offset + $perPage, $total)) ?></strong> of <strong><?= number_format($total) ?></strong> patients
      </div>
      <div style="display:flex;gap:8px;align-items:center">
        <?php if ($hasPrev): ?>
          <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['page' => $page - 1]))) ?>" 
             class="btn" 
             style="padding:8px 12px;text-decoration:none;border-radius:6px;display:inline-flex;align-items:center;gap:6px">
            <i class="fa-solid fa-chevron-left"></i> Previous
          </a>
        <?php endif; ?>
        
        <div style="display:flex;gap:4px">
          <?php
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            
            if ($startPage > 1): ?>
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['page' => 1]))) ?>" 
                 class="btn" 
                 style="padding:8px 12px;text-decoration:none;border-radius:6px;min-width:40px;text-align:center">1</a>
              <?php if ($startPage > 2): ?>
                <span style="padding:8px 4px;color:#6b7280">...</span>
              <?php endif; ?>
            <?php endif; ?>
            
            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['page' => $i]))) ?>" 
                 class="btn" 
                 style="padding:8px 12px;text-decoration:none;border-radius:6px;min-width:40px;text-align:center;<?= $i == $page ? 'background:#3b82f6;color:white;font-weight:600' : '' ?>">
                <?= $i ?>
              </a>
            <?php endfor; ?>
            
            <?php if ($endPage < $totalPages): ?>
              <?php if ($endPage < $totalPages - 1): ?>
                <span style="padding:8px 4px;color:#6b7280">...</span>
              <?php endif; ?>
              <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['page' => $totalPages]))) ?>" 
                 class="btn" 
                 style="padding:8px 12px;text-decoration:none;border-radius:6px;min-width:40px;text-align:center"><?= $totalPages ?></a>
            <?php endif; ?>
        </div>
        
        <?php if ($hasNext): ?>
          <a href="<?= site_url('admin/patients?' . http_build_query(array_merge($_GET, ['page' => $page + 1]))) ?>" 
             class="btn" 
             style="padding:8px 12px;text-decoration:none;border-radius:6px;display:inline-flex;align-items:center;gap:6px">
            Next <i class="fa-solid fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>
</section>

<!-- Add Patient Modal (keeping existing modal code) -->
<div id="addPatientModal" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(15,23,42,0.35);z-index:50;">
  <div style="width:95%;max-width:900px;background:white;border-radius:8px;box-shadow:0 20px 40px rgba(15,23,42,0.35);max-height:90vh;overflow-y:auto;">
    <div style="background:#2563eb;color:white;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #1d4ed8;">
      <h3 style="margin:0;font-size:1.1rem;">Add New Patient</h3>
      <button type="button" onclick="closePatientModal()" style="background:none;border:none;font-size:20px;color:white;cursor:pointer;" aria-label="Close modal">&times;</button>
    </div>
    <div style="padding:16px 20px;">
      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;">
          <?= esc(session()->getFlashdata('error')) ?>
        </div>
      <?php endif; ?>

      <form id="addPatientForm" method="POST" action="<?= site_url('admin/patients') ?>" style="display:grid;gap:12px;">
        <?= csrf_field() ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label for="first_name" style="display:block;margin-bottom:4px;font-weight:600;">First Name <span style="color:#dc2626">*</span></label>
            <input type="text" id="first_name" name="first_name" value="<?= old('first_name') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
          <div>
            <label for="last_name" style="display:block;margin-bottom:4px;font-weight:600;">Last Name <span style="color:#dc2626">*</span></label>
            <input type="text" id="last_name" name="last_name" value="<?= old('last_name') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
        </div>

        <div>
          <label for="middle_name" style="display:block;margin-bottom:4px;font-weight:600;">Middle Name</label>
          <input type="text" id="middle_name" name="middle_name" value="<?= old('middle_name') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label for="date_of_birth" style="display:block;margin-bottom:4px;font-weight:600;">Date of Birth <span style="color:#dc2626">*</span></label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
          <div>
            <label for="gender" style="display:block;margin-bottom:4px;font-weight:600;">Gender <span style="color:#dc2626">*</span></label>
            <select id="gender" name="gender" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              <option value="">Select Gender</option>
              <option value="male" <?= old('gender') == 'male' ? 'selected' : '' ?>>Male</option>
              <option value="female" <?= old('gender') == 'female' ? 'selected' : '' ?>>Female</option>
              <option value="other" <?= old('gender') == 'other' ? 'selected' : '' ?>>Other</option>
            </select>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label for="blood_type" style="display:block;margin-bottom:4px;font-weight:600;">Blood Type</label>
            <select id="blood_type" name="blood_type" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              <option value="">Select Blood Type</option>
              <option value="A+" <?= old('blood_type') == 'A+' ? 'selected' : '' ?>>A+</option>
              <option value="A-" <?= old('blood_type') == 'A-' ? 'selected' : '' ?>>A-</option>
              <option value="B+" <?= old('blood_type') == 'B+' ? 'selected' : '' ?>>B+</option>
              <option value="B-" <?= old('blood_type') == 'B-' ? 'selected' : '' ?>>B-</option>
              <option value="AB+" <?= old('blood_type') == 'AB+' ? 'selected' : '' ?>>AB+</option>
              <option value="AB-" <?= old('blood_type') == 'AB-' ? 'selected' : '' ?>>AB-</option>
              <option value="O+" <?= old('blood_type') == 'O+' ? 'selected' : '' ?>>O+</option>
              <option value="O-" <?= old('blood_type') == 'O-' ? 'selected' : '' ?>>O-</option>
            </select>
          </div>
          <div>
            <label for="phone" style="display:block;margin-bottom:4px;font-weight:600;">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="<?= old('phone') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
        </div>

        <div>
          <label for="email" style="display:block;margin-bottom:4px;font-weight:600;">Email Address</label>
          <input type="email" id="email" name="email" value="<?= old('email') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>

        <div>
          <label for="address" style="display:block;margin-bottom:4px;font-weight:600;">Address</label>
          <textarea id="address" name="address" rows="3" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('address') ?></textarea>
        </div>

        <div>
          <label for="city" style="display:block;margin-bottom:4px;font-weight:600;">City</label>
          <input type="text" id="city" name="city" value="<?= old('city') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>

        <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Emergency Contact</h3>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label for="emergency_contact_name" style="display:block;margin-bottom:4px;font-weight:600;">Emergency Contact Name</label>
            <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?= old('emergency_contact_name') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
          <div>
            <label for="emergency_contact_phone" style="display:block;margin-bottom:4px;font-weight:600;">Emergency Contact Phone</label>
            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="<?= old('emergency_contact_phone') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
        </div>

        <div>
          <label for="emergency_contact_relation" style="display:block;margin-bottom:4px;font-weight:600;">Relationship</label>
          <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" value="<?= old('emergency_contact_relation') ?>" placeholder="e.g., Spouse, Parent, Sibling" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>

        <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Insurance Information</h3>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label for="insurance_provider" style="display:block;margin-bottom:4px;font-weight:600;">Insurance Provider</label>
            <input type="text" id="insurance_provider" name="insurance_provider" value="<?= old('insurance_provider') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
          <div>
            <label for="insurance_number" style="display:block;margin-bottom:4px;font-weight:600;">Insurance Number</label>
            <input type="text" id="insurance_number" name="insurance_number" value="<?= old('insurance_number') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
          </div>
        </div>

        <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Medical Information</h3>

        <div>
          <label for="allergies" style="display:block;margin-bottom:4px;font-weight:600;">Allergies</label>
          <textarea id="allergies" name="allergies" rows="3" placeholder="List any known allergies" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('allergies') ?></textarea>
        </div>

        <div>
          <label for="medical_history" style="display:block;margin-bottom:4px;font-weight:600;">Medical History</label>
          <textarea id="medical_history" name="medical_history" rows="3" placeholder="Previous medical conditions, surgeries, etc." style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('medical_history') ?></textarea>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:12px;">
          <button type="button" class="btn btn-secondary" onclick="closePatientModal()">Cancel</button>
          <button type="submit" class="btn">
            <i class="fas fa-save"></i> Create Patient
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
  function showPatientModal() {
    var modal = document.getElementById('addPatientModal');
    if (modal) {
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      // Initialize sections on modal open
      toggleSections();
    }
  }

  function closePatientModal() {
    var modal = document.getElementById('addPatientModal');
    if (modal) {
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
      var form = document.getElementById('addPatientForm');
      if (form) form.reset();
    }
  }

  function toggleSections() {
    const admissionSelect = document.getElementById('admission_type_select');
    const roomSection = document.getElementById('room_selection_section');
    const consultationSection = document.getElementById('consultation_section');
    
    if (admissionSelect) {
      const admissionType = admissionSelect.value;
      
      if (roomSection) {
        roomSection.style.display = admissionType === 'admission' ? 'block' : 'none';
      }
      
      if (consultationSection) {
        consultationSection.style.display = admissionType === 'checkup' ? 'block' : 'none';
      }
    }
  }

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    const admissionSelect = document.getElementById('admission_type_select');
    if (admissionSelect) {
      admissionSelect.addEventListener('change', toggleSections);
      toggleSections(); // Initial check
    }
  });

  // Close modal when clicking outside
  document.addEventListener('click', function(e) {
    var modal = document.getElementById('addPatientModal');
    if (modal && e.target === modal) {
      closePatientModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    var modal = document.getElementById('addPatientModal');
    if (modal && modal.style.display === 'flex' && e.key === 'Escape') {
      closePatientModal();
    }
  });

  // Keyboard navigation for table rows
  document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'A' && e.key === 'Enter') {
      e.target.click();
    }
  });
</script>
<?= $this->endSection() ?>
