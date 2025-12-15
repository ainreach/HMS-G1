<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Care Note</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
  <main class="content" style="max-width:800px;margin:20px auto;padding:24px">
    <div style="background:#ffffff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);overflow:hidden;border-top:4px solid #3b82f6">
      <div style="background:linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);color:white;padding:20px 24px">
        <h1 style="margin:0;font-size:1.5rem;font-weight:700;display:flex;align-items:center;gap:12px">
          <i class="fa-solid fa-file-medical" style="font-size:1.75rem"></i>
          New Care Note
        </h1>
        <p style="margin:8px 0 0 0;font-size:0.875rem;opacity:0.9">Record patient care notes and observations</p>
      </div>
      
      <div style="padding:24px">
        <?php if (session('error')): ?>
          <div class="alert alert-error" style="margin-bottom:16px;padding:12px 16px;background:#fee2e2;color:#991b1b;border-radius:8px;border:1px solid #fecaca">
            <?= esc(session('error')) ?>
          </div>
        <?php endif; ?>
        <?php if (session('success')): ?>
          <div class="alert alert-success" style="margin-bottom:16px;padding:12px 16px;background:#d1fae5;color:#065f46;border-radius:8px;border:1px solid #a7f3d0">
            <?= esc(session('success')) ?>
          </div>
        <?php endif; ?>
        
        <form method="post" action="<?= site_url('nurse/notes') ?>" style="display:flex;flex-direction:column;gap:20px">
          <?= csrf_field() ?>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Patient <span style="color:#dc2626">*</span></label>
              <select name="patient_id" id="patientSelect" required style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem" onchange="updateAppointments()">
                <option value="">Select patient...</option>
                <?php if (!empty($patients)): ?>
                  <?php foreach ($patients as $patient): ?>
                    <?php
                      $patientName = trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''));
                      $patientId = $patient['id'] ?? '';
                      $patientCode = $patient['patient_id'] ?? 'N/A';
                      $selected = (old('patient_id', $patient_id ?? '') == $patientId) ? 'selected' : '';
                    ?>
                    <option value="<?= esc($patientId) ?>" <?= $selected ?>>
                      <?= esc($patientName) ?> (ID: <?= esc($patientCode) ?>)
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            
            <div>
              <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Doctor (optional)</label>
              <select name="doctor_id" id="doctorSelect" style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem">
                <option value="">Select doctor...</option>
                <?php if (!empty($doctors)): ?>
                  <?php foreach ($doctors as $doctor): ?>
                    <?php
                      $doctorName = trim(($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? ''));
                      $doctorId = $doctor['id'] ?? '';
                      $specialization = $doctor['specialization'] ?? 'General';
                      $selected = (old('doctor_id') == $doctorId) ? 'selected' : '';
                    ?>
                    <option value="<?= esc($doctorId) ?>" <?= $selected ?>>
                      Dr. <?= esc($doctorName) ?> - <?= esc($specialization) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>
          
          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Appointment (optional)</label>
            <select name="appointment_id" id="appointmentSelect" style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.95rem">
              <option value="">Select appointment...</option>
              <?php if (!empty($appointments)): ?>
                <?php foreach ($appointments as $appointment): ?>
                  <?php
                    $appointmentId = $appointment['id'] ?? '';
                    $appointmentDate = !empty($appointment['appointment_date']) ? date('M j, Y', strtotime($appointment['appointment_date'])) : '';
                    $appointmentTime = !empty($appointment['appointment_time']) ? date('g:i A', strtotime($appointment['appointment_time'])) : '';
                    $doctorName = trim(($appointment['doctor_first_name'] ?? '') . ' ' . ($appointment['doctor_last_name'] ?? ''));
                    $selected = (old('appointment_id') == $appointmentId) ? 'selected' : '';
                  ?>
                  <option value="<?= esc($appointmentId) ?>" <?= $selected ?>>
                    <?= esc($appointmentDate) ?> at <?= esc($appointmentTime) ?> - Dr. <?= esc($doctorName) ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
            <small style="color:#6b7280;font-size:0.75rem;margin-top:4px;display:block">Appointments will load when a patient is selected</small>
          </div>
          
          <div>
            <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151;font-size:0.875rem">Care Note <span style="color:#dc2626">*</span></label>
            <textarea name="note" rows="8" required style="width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.875rem;resize:vertical;font-family:inherit"><?= old('note') ?></textarea>
            <small style="color:#6b7280;font-size:0.75rem;margin-top:4px;display:block">Enter detailed care notes, observations, and patient status</small>
          </div>
          
          <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" style="flex:1;background:linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);color:white;padding:14px 20px;border-radius:8px;border:none;cursor:pointer;font-weight:600;font-size:1rem;display:flex;align-items:center;justify-content:center;gap:8px">
              <i class="fa-solid fa-check"></i> Save Note
            </button>
            <a href="<?= site_url('dashboard/nurse') ?>" style="background:#6b7280;color:white;padding:14px 20px;border-radius:8px;border:none;cursor:pointer;font-weight:500;text-decoration:none;display:flex;align-items:center;justify-content:center">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </main>

<script>
function updateAppointments() {
  const patientSelect = document.getElementById('patientSelect');
  const appointmentSelect = document.getElementById('appointmentSelect');
  const selectedPatientId = patientSelect.value;
  
  if (selectedPatientId) {
    // Fetch appointments for selected patient via AJAX
    fetch('<?= site_url('nurse/notes/appointments') ?>?patient_id=' + selectedPatientId)
      .then(response => response.json())
      .then(data => {
        appointmentSelect.innerHTML = '<option value="">Select appointment...</option>';
        if (data.appointments && data.appointments.length > 0) {
          data.appointments.forEach(apt => {
            const option = document.createElement('option');
            option.value = apt.id;
            option.textContent = apt.date + ' at ' + apt.time + ' - Dr. ' + apt.doctor;
            appointmentSelect.appendChild(option);
          });
        } else {
          const option = document.createElement('option');
          option.value = '';
          option.textContent = 'No appointments found';
          appointmentSelect.appendChild(option);
        }
      })
      .catch(error => {
        console.error('Error fetching appointments:', error);
        appointmentSelect.innerHTML = '<option value="">Error loading appointments</option>';
      });
  } else {
    appointmentSelect.innerHTML = '<option value="">Select appointment...</option>';
  }
}

// If patient_id is pre-selected from query string, trigger appointment load
<?php if (!empty($patient_id)): ?>
  document.addEventListener('DOMContentLoaded', function() {
    updateAppointments();
  });
<?php endif; ?>
</script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
