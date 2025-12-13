<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Medical Record</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem">New Medical Record</h1>
</div></header>
<main class="content" style="max-width:900px;margin:20px auto">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
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
    <label>Chief Complaint
      <textarea name="chief_complaint" rows="2"><?= old('chief_complaint') ?></textarea>
    </label>
    <label>History of Present Illness
      <textarea name="history_present_illness" rows="3"><?= old('history_present_illness') ?></textarea>
    </label>
    <label>Physical Examination
      <textarea name="physical_examination" rows="3"><?= old('physical_examination') ?></textarea>
    </label>
    <label>Vital Signs (JSON)
      <textarea name="vital_signs" rows="2" placeholder='{"bp":"120/80","temp":"36.6"}'><?= old('vital_signs') ?></textarea>
    </label>
    <label>Diagnosis
      <textarea name="diagnosis" rows="2"><?= old('diagnosis') ?></textarea>
    </label>
    <label>Treatment Plan
      <textarea name="treatment_plan" rows="2"><?= old('treatment_plan') ?></textarea>
    </label>
    <label>Medications Prescribed (JSON)
      <textarea name="medications_prescribed" rows="2" placeholder='[{"drug":"Paracetamol","dose":"500mg"}]'><?= old('medications_prescribed') ?></textarea>
    </label>
    <label>Follow-up Instructions
      <textarea name="follow_up_instructions" rows="2"><?= old('follow_up_instructions') ?></textarea>
    </label>
    <label>Next Visit Date
      <input type="date" name="next_visit_date" value="<?= old('next_visit_date') ?>">
    </label>
    <div style="margin-top:12px;display:flex;gap:10px">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn" href="<?= site_url('dashboard/doctor') ?>">Cancel</a>
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
