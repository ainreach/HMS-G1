<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admit Patient - Doctor</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .form-container {
      max-width: 700px;
      margin: 0 auto;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-label {
      display: block;
      font-weight: 600;
      color: #000000;
      margin-bottom: 8px;
      font-size: 0.875rem;
    }
    .form-input, .form-select, .form-textarea {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 0.875rem;
      background: white;
      color: #000000;
      font-family: inherit;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .form-input.error, .form-select.error {
      border-color: #ef4444;
    }
    .form-textarea {
      resize: vertical;
      min-height: 100px;
    }
    .btn-primary {
      background: #3b82f6;
      color: white;
      padding: 12px 24px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background 0.2s;
    }
    .btn-primary:hover {
      background: #2563eb;
    }
    .btn-primary:disabled {
      background: #9ca3af;
      cursor: not-allowed;
    }
    .btn-secondary {
      background: #6b7280;
      color: white;
      padding: 12px 24px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-secondary:hover {
      background: #4b5563;
    }
    .help-text {
      color: #6b7280;
      font-size: 0.75rem;
      margin-top: 4px;
      display: block;
    }
    .required {
      color: #ef4444;
    }
    .date-input-wrapper {
      position: relative;
    }
    .date-input-wrapper input[type="date"]::-webkit-calendar-picker-indicator {
      position: absolute;
      right: 12px;
      cursor: pointer;
    }
    .patient-info-card {
      background: #f8fafc;
      padding: 16px;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      margin-bottom: 24px;
    }
    .patient-info-card h3 {
      margin: 0 0 12px 0;
      font-size: 1rem;
      color: #374151;
      font-weight: 600;
    }
    .patient-info-card .info-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      font-size: 0.875rem;
    }
    .patient-info-card .info-item {
      display: flex;
      flex-direction: column;
    }
    .patient-info-card .info-label {
      color: #6b7280;
      font-weight: 500;
      margin-bottom: 4px;
    }
    .patient-info-card .info-value {
      color: #1e293b;
      font-weight: 600;
    }
  </style>
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <div class="brand">
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0;color:#1f2937">Admit Patient</h1>
        <small style="color:#6b7280">Room and bed assignment</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role">
        <i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?>
      </span>
      <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Doctor navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/doctor') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
      <a href="<?= site_url('doctor/records') ?>"><i class="fa-solid fa-file-medical" style="margin-right:8px"></i>Patient Records</a>
      <a href="<?= site_url('doctor/patients') ?>"><i class="fa-solid fa-users" style="margin-right:8px"></i>Patients</a>
      <a href="<?= site_url('doctor/admit-patients') ?>" class="active" aria-current="page"><i class="fa-solid fa-bed-pulse" style="margin-right:8px"></i>Admit Patients</a>
      <a href="<?= site_url('doctor/prescriptions') ?>"><i class="fa-solid fa-prescription" style="margin-right:8px"></i>Prescriptions</a>
      <a href="<?= site_url('doctor/lab-results') ?>"><i class="fa-solid fa-vial" style="margin-right:8px"></i>Lab Results</a>
      <a href="<?= site_url('doctor/patients/new') ?>"><i class="fa-solid fa-user-plus" style="margin-right:8px"></i>Register Patient</a>
    </nav>
  </aside>

  <main class="content">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <!-- Patient Information Card -->
    <div class="patient-info-card">
      <h3><i class="fa-solid fa-user" style="color:#3b82f6;margin-right:8px"></i>Patient Information</h3>
      <div class="info-row">
        <div class="info-item">
          <span class="info-label">Name</span>
          <span class="info-value"><?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Patient ID</span>
          <span class="info-value"><?= esc($patient['patient_id'] ?? 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Phone</span>
          <span class="info-value"><?= esc($patient['phone'] ?? 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Email</span>
          <span class="info-value"><?= esc($patient['email'] ?? 'N/A') ?></span>
        </div>
      </div>
    </div>

    <div class="form-container">
      <form method="post" action="<?= site_url('doctor/admit-patient/' . $patient['id']) ?>" id="admissionForm">
        <?= csrf_field() ?>
        
        <!-- Room Selection -->
        <div class="form-group">
          <label class="form-label" for="room_id">
            Room <span class="required">*</span>
          </label>
          <?php if (!empty($availableRooms)): ?>
            <select name="room_id" id="room_id" class="form-select" required>
              <option value="">Select Room</option>
              <?php foreach ($availableRooms as $room): ?>
                <option value="<?= esc($room['id']) ?>" 
                        data-room-type="<?= esc($room['room_type'] ?? '') ?>"
                        data-capacity="<?= esc($room['capacity'] ?? 0) ?>">
                  <?= esc($room['room_number']) ?> - 
                  <?= esc(ucfirst($room['room_type'] ?? 'Standard')) ?> 
                  (Floor <?= esc($room['floor'] ?? 'N/A') ?>, 
                  Capacity: <?= esc($room['current_occupancy'] ?? 0) ?>/<?= esc($room['capacity'] ?? 0) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          <?php else: ?>
            <div style="padding:16px;background:#fef2f2;border:1px solid #fecaca;border-radius:6px;color:#991b1b">
              <i class="fa-solid fa-exclamation-triangle"></i> No available rooms. Please contact reception to add rooms.
            </div>
          <?php endif; ?>
        </div>

        <!-- Bed Selection (Optional) -->
        <div class="form-group">
          <label class="form-label" for="bed_id">
            Bed (Optional)
          </label>
          <select name="bed_id" id="bed_id" class="form-select">
            <option value="">No specific bed</option>
          </select>
          <span class="help-text">Select a room first to see available beds</span>
        </div>

        <!-- Room Type (Auto-filled) -->
        <div class="form-group">
          <label class="form-label" for="room_type_display">
            Room Type
          </label>
          <input type="text" id="room_type_display" class="form-input" readonly 
                 placeholder="e.g., Private, Semi-Private, Ward" 
                 style="background:#f3f4f6;cursor:not-allowed">
          <span class="help-text">Auto filled from selected room</span>
        </div>

        <!-- Admission Reason -->
        <div class="form-group">
          <label class="form-label" for="admission_reason">
            Admission Reason <span class="required">*</span>
          </label>
          <textarea name="admission_reason" id="admission_reason" class="form-textarea" required
                    placeholder="Enter the reason for admission..."><?= old('admission_reason') ?></textarea>
        </div>

        <!-- Attending Physician -->
        <div class="form-group">
          <label class="form-label" for="attending_physician_id">
            Attending Physician <span class="required">*</span>
          </label>
          <select name="attending_physician_id" id="attending_physician_id" class="form-select" required>
            <option value="">Select Physician</option>
            <?php if (!empty($doctors)): ?>
              <?php foreach ($doctors as $doctor): ?>
                <option value="<?= esc($doctor['id']) ?>" <?= old('attending_physician_id') == $doctor['id'] ? 'selected' : '' ?>>
                  <?= esc($doctor['username'] ?? trim(($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? ''))) ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>

        <!-- Initial Notes -->
        <div class="form-group">
          <label class="form-label" for="admission_notes">
            Initial Notes
          </label>
          <textarea name="admission_notes" id="admission_notes" class="form-textarea"
                    placeholder="Additional notes about the admission..."><?= old('admission_notes') ?></textarea>
        </div>

        <!-- Admission Date -->
        <div class="form-group">
          <label class="form-label" for="admission_date">
            Admission Date <span class="required">*</span>
          </label>
          <div class="date-input-wrapper">
            <input type="date" 
                   name="admission_date" 
                   id="admission_date" 
                   class="form-input" 
                   value="<?= old('admission_date', date('Y-m-d')) ?>" 
                   required>
          </div>
        </div>

        <!-- Action Buttons -->
        <div style="display:flex;gap:12px;margin-top:32px;justify-content:flex-end">
          <a href="<?= site_url('doctor/admit-patients') ?>" class="btn-secondary">
            <i class="fa-solid fa-times"></i> Cancel
          </a>
          <button type="submit" class="btn-primary" <?= empty($availableRooms) ? 'disabled' : '' ?>>
            <i class="fa-solid fa-bed-pulse"></i> Admit Patient
          </button>
        </div>
      </form>
    </div>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const roomSelect = document.getElementById('room_id');
  const bedSelect = document.getElementById('bed_id');
  const roomTypeDisplay = document.getElementById('room_type_display');
  
  roomSelect.addEventListener('change', function() {
    const roomId = this.value;
    const selectedOption = this.options[this.selectedIndex];
    const roomType = selectedOption ? selectedOption.getAttribute('data-room-type') : '';
    
    // Update room type display
    if (roomType) {
      roomTypeDisplay.value = roomType.charAt(0).toUpperCase() + roomType.slice(1);
    } else {
      roomTypeDisplay.value = '';
    }
    
    // Load beds for selected room
    bedSelect.innerHTML = '<option value="">Loading beds...</option>';
    
    if (roomId) {
      fetch('<?= site_url('doctor/get-beds-by-room/') ?>' + roomId)
        .then(response => response.json())
        .then(data => {
          bedSelect.innerHTML = '<option value="">No specific bed</option>';
          if (data.beds && data.beds.length > 0) {
            data.beds.forEach(bed => {
              const option = document.createElement('option');
              option.value = bed.id;
              option.textContent = bed.bed_number + ' (' + bed.bed_type + ')';
              bedSelect.appendChild(option);
            });
          }
        })
        .catch(error => {
          console.error('Error loading beds:', error);
          bedSelect.innerHTML = '<option value="">No specific bed</option>';
        });
    } else {
      bedSelect.innerHTML = '<option value="">No specific bed</option>';
    }
  });
});
</script>
</body>
</html>

