<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Medical Records</h2>
  </div>
  <div class="panel-body">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <div>
        <h3 style="margin:0;font-size:1rem;color:#6b7280">Total Records: <?= number_format($total) ?></h3>
      </div>
      <div>
        <button class="btn" id="newRecordBtn" style="background:var(--primary);color:white;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;">
          <i class="fas fa-plus"></i> New Medical Record
        </button>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Record #</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Visit Date</th>
            <th>Chief Complaint</th>
            <th>Diagnosis</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($records)): ?>
            <?php foreach ($records as $record): ?>
              <tr<?= !empty($record['deleted_at']) ? ' style="opacity:0.6;background:#f9fafb;"' : '' ?>>
                <td><?= esc($record['record_number'] ?? 'N/A') ?></td>
                <td><?= esc(($record['patient_first_name'] ?? '') . ' ' . ($record['patient_last_name'] ?? '')) ?></td>
                <td>Dr. <?= esc($record['doctor_first_name'] ?? '') ?></td>
                <td><?= date('M j, Y', strtotime($record['visit_date'])) ?></td>
                <td><?= esc(substr($record['chief_complaint'] ?? '', 0, 50)) ?><?= strlen($record['chief_complaint'] ?? '') > 50 ? '...' : '' ?></td>
                <td><?= esc(substr($record['diagnosis'] ?? '', 0, 50)) ?><?= strlen($record['diagnosis'] ?? '') > 50 ? '...' : '' ?></td>
                <td style="display:flex;gap:4px;flex-wrap:wrap">
                  <button type="button" class="btn btn-secondary view-record-btn" data-record-id="<?= $record['id'] ?>" style="padding:0.25rem 0.5rem;font-size:0.75rem;cursor:pointer;">
                    <i class="fas fa-eye"></i> View
                  </button>
                  <a href="<?= site_url('admin/medical-records/' . $record['id'] . '/edit') ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;text-decoration:none;">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <?php if (empty($record['deleted_at'])): ?>
                    <form method="post" action="<?= site_url('admin/medical-records/' . $record['id'] . '/delete') ?>" style="display:inline" onsubmit="return confirm('Delete this medical record? You can restore it later from this list.');">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-danger" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                        <i class="fas fa-trash"></i> Delete
                      </button>
                    </form>
                  <?php else: ?>
                    <form method="post" action="<?= site_url('admin/medical-records/' . $record['id'] . '/restore') ?>" style="display:inline" onsubmit="return confirm('Restore this medical record?');">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn" style="padding:0.25rem 0.5rem;font-size:0.75rem;background:#16a34a;color:white;border:none;">
                        <i class="fas fa-undo"></i> Restore
                      </button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" style="text-align:center;padding:2rem;color:#6b7280">
                No medical records found
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    
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
  </div>
</section>

<!-- Modal for Viewing Medical Record -->
<div id="viewRecordModal" class="modal-overlay">
  <div class="modal-card" style="background:white;border-radius:12px;width:90%;max-width:900px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid #e5e7eb;">
      <h3 style="margin:0;font-size:1.2rem">Medical Record Details</h3>
      <button class="modal-close" id="closeViewModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:#6b7280;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;" id="recordDetailsContent">
      <div style="text-align:center;padding:40px;color:#6b7280;">
        <i class="fas fa-spinner fa-spin" style="font-size:2rem;margin-bottom:10px;"></i>
        <p>Loading record details...</p>
      </div>
    </div>
  </div>
</div>

<!-- Modal for New Medical Record -->
<div id="newRecordModal" class="modal-overlay">
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:600px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid var(--border);">
      <h3 style="margin:0;font-size:1.2rem">New Medical Record</h3>
      <button class="modal-close" id="closeModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:var(--muted);padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;">
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <form method="post" action="<?= site_url('doctor/records') ?>" id="modalRecordForm">
        <?= csrf_field() ?>
        <input type="hidden" name="redirect_to" value="<?= site_url('admin/medical-records') ?>">
        <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
          <label style="display:block;margin-bottom:4px;font-weight:600;">Patient (by name or room)
            <div style="position:relative;">
              <input 
                type="text" 
                id="patientSearchInput" 
                placeholder="Search by name, room number, ID, or phone..." 
                autocomplete="off"
                style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
              <input type="hidden" name="patient_id" id="patientIdInput" value="<?= old('patient_id') ?>" required>
              <div id="patientSearchResults" style="position:absolute;z-index:40;top:100%;left:0;right:0;background:white;border:1px solid var(--border);border-radius:0 0 6px 6px;max-height:220px;overflow-y:auto;display:none;"></div>
            </div>
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600;">Appointment
            <select name="appointment_id" id="appointmentSelect" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
              <option value="">No appointment / walk-in</option>
            </select>
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600;">Visit Date/Time
            <input type="datetime-local" name="visit_date" value="<?= old('visit_date') ?>" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
        </div>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Chief Complaint
          <textarea name="chief_complaint" rows="2" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('chief_complaint') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">History of Present Illness
          <textarea name="history_present_illness" rows="3" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('history_present_illness') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Physical Examination
          <textarea name="physical_examination" rows="3" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('physical_examination') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Vital Signs (JSON)
          <textarea name="vital_signs" rows="2" placeholder='{"bp":"120/80","temp":"36.6"}' style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('vital_signs') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Diagnosis
          <textarea name="diagnosis" rows="2" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('diagnosis') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Treatment Plan
          <textarea name="treatment_plan" rows="2" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('treatment_plan') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Medications Prescribed (JSON)
          <textarea name="medications_prescribed" rows="2" placeholder='[{"drug":"Paracetamol","dose":"500mg"}]' style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('medications_prescribed') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Follow-up Instructions
          <textarea name="follow_up_instructions" rows="2" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('follow_up_instructions') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Next Visit Date
          <input type="date" name="next_visit_date" value="<?= old('next_visit_date') ?>" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
        </label>
        <div style="margin-top:16px;display:flex;gap:10px;justify-content:flex-end;">
          <button type="submit" class="btn btn-primary" style="background:var(--primary);color:white;padding:10px 20px;border:none;border-radius:6px;cursor:pointer;">Save</button>
          <button type="button" class="btn" id="cancelModal" style="background:var(--border);color:var(--text);padding:10px 20px;border:none;border-radius:6px;cursor:pointer;">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
// Modal functionality
document.getElementById('newRecordBtn').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('newRecordModal').classList.add('show');
});

document.getElementById('closeModal').addEventListener('click', function() {
  document.getElementById('newRecordModal').classList.remove('show');
});

document.getElementById('cancelModal').addEventListener('click', function() {
  document.getElementById('newRecordModal').classList.remove('show');
});

// Close modal when clicking outside
document.getElementById('newRecordModal').addEventListener('click', function(e) {
  if (e.target === this) {
    this.classList.remove('show');
  }
});

// Handle form submission
document.getElementById('modalRecordForm').addEventListener('submit', function(e) {
  // Ensure a patient is selected before submitting
  var patientIdInput = document.getElementById('patientIdInput');
  if (!patientIdInput || !patientIdInput.value) {
    e.preventDefault();
    alert('Please select a patient from the list first.');
    var patientSearchInput = document.getElementById('patientSearchInput');
    if (patientSearchInput) {
      patientSearchInput.focus();
    }
  }
});

// Patient search with dropdown suggestions
(function() {
  var searchInput = document.getElementById('patientSearchInput');
  var resultsBox = document.getElementById('patientSearchResults');
  var hiddenIdInput = document.getElementById('patientIdInput');
  var appointmentSelect = document.getElementById('appointmentSelect');

  if (!searchInput || !resultsBox || !hiddenIdInput) return;

  var searchTimer;

  function clearResults() {
    resultsBox.style.display = 'none';
    resultsBox.innerHTML = '';
  }

  function renderResults(patients) {
    if (!patients || patients.length === 0) {
      clearResults();
      return;
    }

    var html = '';
    patients.forEach(function(patient) {
      var fullName = ((patient.first_name || '') + ' ' + (patient.last_name || '')).trim();
      var room = patient.room_number ? ('Room ' + patient.room_number) : '';
      var code = patient.patient_id || ('#' + patient.id);
      var phone = patient.phone || '';
      html += '<button type="button" class="patient-option" data-id="' + patient.id + '" style="width:100%;text-align:left;padding:8px 10px;border:none;border-bottom:1px solid var(--border);background:white;cursor:pointer;display:block;">'
           +   '<div style="font-weight:600;color:#111827;">' + fullName + (room ? ' • <span style="color:#2563eb;font-weight:500;">' + room + '</span>' : '') + '</div>'
           +   '<div style="font-size:0.8rem;color:#6b7280;">' + code + (phone ? ' • ' + phone : '') + '</div>'
           + '</button>';
    });

    resultsBox.innerHTML = html;
    resultsBox.style.display = 'block';

    Array.prototype.forEach.call(resultsBox.querySelectorAll('.patient-option'), function(btn) {
      btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var label = this.textContent.trim();
        hiddenIdInput.value = id;
        searchInput.value = label;
        clearResults();

        // Load appointments for this patient into the dropdown
        if (appointmentSelect) {
          loadAppointmentsForPatient(id);
        }
      });
    });
  }

  function runSearch(query) {
    if (!query || query.length < 1) {
      clearResults();
      hiddenIdInput.value = '';
      return;
    }

    var url = '<?= site_url('doctor/patients/search') ?>?q=' + encodeURIComponent(query);
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
      .then(function(r) { return r.json(); })
      .then(function(patients) { renderResults(patients); })
      .catch(function() { clearResults(); });
  }

  function loadAppointmentsForPatient(patientId) {
    if (!appointmentSelect) return;

    if (!patientId) {
      appointmentSelect.innerHTML = '<option value="">No appointment / walk-in</option>';
      return;
    }

    appointmentSelect.innerHTML = '<option value="">Loading appointments...</option>';

    var url = '<?= site_url('doctor/appointments/patient') ?>/' + encodeURIComponent(patientId);

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
      .then(function(r) { return r.json(); })
      .then(function(appointments) {
        var options = '<option value="">No appointment / walk-in</option>';

        if (appointments && appointments.length) {
          appointments.forEach(function(appt) {
            var id = appt.id;
            var date = appt.appointment_date || '';
            var time = appt.appointment_time || '';
            var status = appt.status || '';
            var label = 'Appt #' + id;
            if (date || time) {
              label += ' - ' + [date, time].filter(Boolean).join(' ');
            }
            if (status) {
              label += ' (' + status + ')';
            }
            options += '<option value="' + id + '\">' + label + '</option>';
          });
        }

        appointmentSelect.innerHTML = options;
      })
      .catch(function() {
        appointmentSelect.innerHTML = '<option value="">No appointment / walk-in</option>';
      });
  }

  searchInput.addEventListener('input', function() {
    var query = this.value.trim();
    clearTimeout(searchTimer);
    searchTimer = setTimeout(function() {
      runSearch(query);
    }, 250);
  });

  searchInput.addEventListener('focus', function() {
    var query = this.value.trim();
    if (query.length > 0) {
      runSearch(query);
    }
  });

  // Hide results when clicking outside
  document.addEventListener('click', function(e) {
    if (!resultsBox.contains(e.target) && e.target !== searchInput) {
      clearResults();
    }
  });
})();

// View Record Modal functionality
const viewRecordModal = document.getElementById('viewRecordModal');
const recordDetailsContent = document.getElementById('recordDetailsContent');
const closeViewModal = document.getElementById('closeViewModal');

// Handle view record button clicks
document.querySelectorAll('.view-record-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const recordId = this.getAttribute('data-record-id');
    viewRecordModal.classList.add('show');
    loadRecordDetails(recordId);
  });
});

// Close view modal
closeViewModal.addEventListener('click', function() {
  viewRecordModal.classList.remove('show');
});

// Close modal when clicking outside
viewRecordModal.addEventListener('click', function(e) {
  if (e.target === this) {
    this.classList.remove('show');
  }
});

// Load record details via AJAX
function loadRecordDetails(recordId) {
  recordDetailsContent.innerHTML = '<div style="text-align:center;padding:40px;color:#6b7280;"><i class="fas fa-spinner fa-spin" style="font-size:2rem;margin-bottom:10px;"></i><p>Loading record details...</p></div>';
  
  fetch('<?= site_url("admin/medical-records/") ?>' + recordId)
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        recordDetailsContent.innerHTML = '<div style="text-align:center;padding:40px;color:#dc2626;"><p>' + data.error + '</p></div>';
        return;
      }
      
      // Format and display record details
      let html = '';
      
      // Patient Information
      html += '<div style="background:#f9fafb;padding:16px;border-radius:8px;margin-bottom:20px;border:1px solid #e5e7eb">';
      html += '<h3 style="margin:0 0 12px 0;font-size:1rem;color:#374151">Patient Information</h3>';
      html += '<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:12px">';
      html += '<div><strong style="color:#6b7280;font-size:0.875rem">Patient Name:</strong><div style="margin-top:4px;font-size:1rem">' + (data.patient_first_name || '') + ' ' + (data.patient_last_name || '') + '</div></div>';
      if (data.patient_code) html += '<div><strong style="color:#6b7280;font-size:0.875rem">Patient ID:</strong><div style="margin-top:4px;font-size:1rem">' + data.patient_code + '</div></div>';
      if (data.date_of_birth) html += '<div><strong style="color:#6b7280;font-size:0.875rem">Date of Birth:</strong><div style="margin-top:4px;font-size:1rem">' + new Date(data.date_of_birth).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'}) + '</div></div>';
      if (data.gender) html += '<div><strong style="color:#6b7280;font-size:0.875rem">Gender:</strong><div style="margin-top:4px;font-size:1rem">' + data.gender.charAt(0).toUpperCase() + data.gender.slice(1) + '</div></div>';
      html += '</div></div>';
      
      // Record Information
      html += '<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:16px;margin-bottom:20px">';
      html += '<div><strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Record Number:</strong><div style="font-size:1rem">' + (data.record_number || 'N/A') + '</div></div>';
      if (data.visit_date) html += '<div><strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Visit Date:</strong><div style="font-size:1rem">' + new Date(data.visit_date).toLocaleString('en-US', {year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'}) + '</div></div>';
      if (data.doctor_first_name || data.doctor_last_name) html += '<div><strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Doctor:</strong><div style="font-size:1rem">Dr. ' + (data.doctor_first_name || '') + ' ' + (data.doctor_last_name || '') + '</div></div>';
      if (data.appointment_id) html += '<div><strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Appointment ID:</strong><div style="font-size:1rem">' + data.appointment_id + '</div></div>';
      html += '</div>';
      
      // Chief Complaint
      if (data.chief_complaint) {
        html += '<div style="margin-bottom:20px"><h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Chief Complaint</h3>';
        html += '<div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap">' + escapeHtml(data.chief_complaint) + '</div></div>';
      }
      
      // History of Present Illness
      if (data.history_present_illness) {
        html += '<div style="margin-bottom:20px"><h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">History of Present Illness</h3>';
        html += '<div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap">' + escapeHtml(data.history_present_illness) + '</div></div>';
      }
      
      // Physical Examination
      if (data.physical_examination) {
        html += '<div style="margin-bottom:20px"><h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Physical Examination</h3>';
        html += '<div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap">' + escapeHtml(data.physical_examination) + '</div></div>';
      }
      
      // Vital Signs
      if (data.vital_signs) {
        html += '<div style="margin-bottom:20px"><h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Vital Signs</h3>';
        html += '<div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb">';
        try {
          const vitals = JSON.parse(data.vital_signs);
          if (typeof vitals === 'object') {
            html += '<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));gap:12px">';
            for (const [key, value] of Object.entries(vitals)) {
              html += '<div><strong style="color:#6b7280;font-size:0.875rem">' + key.charAt(0).toUpperCase() + key.slice(1).replace(/_/g, ' ') + ':</strong> <span style="font-size:1rem">' + escapeHtml(value) + '</span></div>';
            }
            html += '</div>';
          } else {
            html += '<div style="white-space:pre-wrap">' + escapeHtml(data.vital_signs) + '</div>';
          }
        } catch (e) {
          html += '<div style="white-space:pre-wrap">' + escapeHtml(data.vital_signs) + '</div>';
        }
        html += '</div></div>';
      }
      
      // Diagnosis
      if (data.diagnosis) {
        html += '<div style="margin-bottom:20px"><h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Diagnosis</h3>';
        html += '<div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap">' + escapeHtml(data.diagnosis) + '</div></div>';
      }
      
      // Treatment Plan
      if (data.treatment_plan) {
        html += '<div style="margin-bottom:20px"><h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Treatment Plan</h3>';
        html += '<div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap">' + escapeHtml(data.treatment_plan) + '</div></div>';
      }
      
      // Medications Prescribed
      if (data.medications_prescribed) {
        html += '<div style="margin-bottom:20px"><h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Medications Prescribed</h3>';
        html += '<div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb">';
        try {
          const medications = JSON.parse(data.medications_prescribed);
          if (Array.isArray(medications) && medications.length > 0) {
            html += '<table style="width:100%;border-collapse:collapse"><thead><tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb">';
            html += '<th style="text-align:left;padding:8px">Drug</th><th style="text-align:left;padding:8px">Dose</th>';
            if (medications[0].frequency) html += '<th style="text-align:left;padding:8px">Frequency</th>';
            if (medications[0].duration) html += '<th style="text-align:left;padding:8px">Duration</th>';
            html += '</tr></thead><tbody>';
            medications.forEach(med => {
              html += '<tr style="border-bottom:1px solid #f3f4f6">';
              html += '<td style="padding:8px">' + escapeHtml(med.drug || 'N/A') + '</td>';
              html += '<td style="padding:8px">' + escapeHtml(med.dose || 'N/A') + '</td>';
              if (medications[0].frequency) html += '<td style="padding:8px">' + escapeHtml(med.frequency || 'N/A') + '</td>';
              if (medications[0].duration) html += '<td style="padding:8px">' + escapeHtml(med.duration || 'N/A') + '</td>';
              html += '</tr>';
            });
            html += '</tbody></table>';
          } else {
            html += '<div style="white-space:pre-wrap">' + escapeHtml(data.medications_prescribed) + '</div>';
          }
        } catch (e) {
          html += '<div style="white-space:pre-wrap">' + escapeHtml(data.medications_prescribed) + '</div>';
        }
        html += '</div></div>';
      }
      
      // Follow-up Instructions
      if (data.follow_up_instructions) {
        html += '<div style="margin-bottom:20px"><h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Follow-up Instructions</h3>';
        html += '<div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap">' + escapeHtml(data.follow_up_instructions) + '</div></div>';
      }
      
      // Next Visit Date
      if (data.next_visit_date) {
        html += '<div style="margin-bottom:20px"><h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Next Visit Date</h3>';
        html += '<div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;font-size:1rem">' + new Date(data.next_visit_date).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'}) + '</div></div>';
      }
      
      recordDetailsContent.innerHTML = html;
    })
    .catch(error => {
      recordDetailsContent.innerHTML = '<div style="text-align:center;padding:40px;color:#dc2626;"><p>Error loading record details. Please try again.</p></div>';
      console.error('Error:', error);
    });
}

// Helper function to escape HTML
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}
</script>
<?= $this->endSection() ?>
