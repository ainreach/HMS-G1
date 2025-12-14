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
  <form method="post" action="<?= site_url('doctor/records') ?>" id="doctorRecordForm">
    <?= csrf_field() ?>
    <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>Patient (by name or room)
        <div style="position:relative">
          <input 
            type="text" 
            id="doctorPatientSearchInput" 
            placeholder="Search by name, room number, ID, or phone..." 
            autocomplete="off">
          <input type="hidden" name="patient_id" id="doctorPatientIdInput" value="<?= old('patient_id', $patient_id ?? '') ?>" required>
          <div id="doctorPatientSearchResults" style="position:absolute;z-index:40;top:100%;left:0;right:0;background:white;border:1px solid #e5e7eb;border-radius:0 0 6px 6px;max-height:220px;overflow-y:auto;display:none;"></div>
        </div>
      </label>
      <label>Appointment ID
        <input type="number" name="appointment_id" value="<?= old('appointment_id') ?>">
      </label>
      <label>Visit Date/Time
        <input type="datetime-local" name="visit_date" value="<?= old('visit_date') ?>">
      </label>
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
  (function() {
    var form = document.getElementById('doctorRecordForm');
    var searchInput = document.getElementById('doctorPatientSearchInput');
    var resultsBox = document.getElementById('doctorPatientSearchResults');
    var hiddenIdInput = document.getElementById('doctorPatientIdInput');

    if (!form || !searchInput || !resultsBox || !hiddenIdInput) return;

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
        html += '<button type="button" class="patient-option" data-id="' + patient.id + '" style="width:100%;text-align:left;padding:8px 10px;border:none;border-bottom:1px solid #e5e7eb;background:white;cursor:pointer;display:block;">'
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

    document.addEventListener('click', function(e) {
      if (!resultsBox.contains(e.target) && e.target !== searchInput) {
        clearResults();
      }
    });

    form.addEventListener('submit', function(e) {
      if (!hiddenIdInput.value) {
        e.preventDefault();
        alert('Please select a patient from the list first.');
        searchInput.focus();
      }
    });
  })();
</script>
</body></html>
