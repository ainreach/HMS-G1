<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Appointment Management</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Appointment Management</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Reception navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/receptionist') ?>">Overview</a>
  <a href="<?= site_url('reception/patients') ?>">Patient Management</a>
  <a href="<?= site_url('reception/appointments') ?>" class="active" aria-current="page">Appointment Management</a>
  <a href="<?= site_url('reception/patient-lookup') ?>">Patient Lookup</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Appointments</h2>
        <button id="newAppointmentBtn" type="button" class="btn" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;cursor:pointer;background:white"><i class="fa-solid fa-calendar-plus"></i> New Appointment</button>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Doctor</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Time</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($appointments)) : foreach ($appointments as $a) : ?>
              <?php
                $patientName = trim(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? ''));
                $doctorLabel = trim('Dr. ' . ($a['doctor_name'] ?? ($a['doctor_last'] ?? '')));

                $dateLabel = !empty($a['appointment_date']) ? date('M j, Y', strtotime($a['appointment_date'])) : '';
                $timeLabel = !empty($a['appointment_time']) ? date('g:i A', strtotime($a['appointment_time'])) : '';

                $rawStatus = strtolower(trim($a['status'] ?? 'scheduled'));
                if ($rawStatus === '') { $rawStatus = 'scheduled'; }
                $statusLabel = $rawStatus === 'checked-in' ? 'Checked-in'
                             : ($rawStatus === 'cancelled' ? 'Cancelled'
                             : 'Scheduled');
                $statusBg = $rawStatus === 'checked-in' ? '#d1fae5'
                          : ($rawStatus === 'cancelled' ? '#fee2e2'
                          : '#dbeafe');
                $statusColor = $rawStatus === 'checked-in' ? '#065f46'
                              : ($rawStatus === 'cancelled' ? '#b91c1c'
                              : '#1e40af');
              ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($patientName) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($doctorLabel) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;white-space:nowrap;">
                  <?= esc($dateLabel) ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;white-space:nowrap;">
                  <?= esc($timeLabel) ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;white-space:nowrap;"><span style="padding:2px 6px;border-radius:4px;font-size:0.75rem;background-color:<?= $statusBg ?>;color:<?= $statusColor ?>"><?= esc($statusLabel) ?></span></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <a href="<?= site_url('reception/appointments/' . ($a['id'] ?? 0)) ?>" class="btn" style="padding:4px 8px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">View</a>
                  <a href="<?= site_url('reception/appointments/' . ($a['id'] ?? 0) . '/edit') ?>" class="btn" style="padding:4px 8px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">Edit</a>
                  <form action="<?= site_url('reception/appointments/' . ($a['id'] ?? 0) . '/checkin') ?>" method="post" style="display:inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn" style="padding:4px 8px;border:1px solid #e5e7eb;border-radius:6px;background:#f9fafb">Check-in</button>
                  </form>
                  <button type="button" class="btn cancel-btn" data-id="<?= $a['id'] ?? 0 ?>" style="padding:4px 8px;border:1px solid #e5e7eb;border-radius:6px;background:#fff0f0;color:#991b1b">Cancel</button>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="6" style="padding:10px;color:#6b7280;text-align:center">No appointments found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>

<!-- Modal for Cancellation Reason -->
<div id="cancelAppointmentModal" class="modal-overlay" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(15,23,42,0.55);z-index:50;">
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:500px;">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--border);">
      <h3 style="margin:0;font-size:1.05rem;font-weight:600;">Cancel Appointment</h3>
      <button id="closeCancelModal" type="button" style="background:none;border:none;font-size:20px;cursor:pointer;color:#6b7280;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;">
      <form id="cancelAppointmentForm" method="post" action="">
        <?= csrf_field() ?>
        <label for="cancellation_reason">Reason for cancellation:</label>
        <textarea name="cancellation_reason" id="cancellation_reason" rows="4" required style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px;margin-top:8px;"></textarea>
        <div style="margin-top:12px;display:flex;gap:10px;justify-content:flex-end;">
          <button type="submit" class="btn btn-danger" style="background:#ef4444;color:white;">Confirm Cancellation</button>
          <button type="button" class="btn" onclick="document.getElementById('cancelAppointmentModal').style.display='none'">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Floating modal for New Appointment (stays on /reception/appointments) -->
<div id="newAppointmentModal" class="modal-overlay" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(15,23,42,0.55);z-index:50;">
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:720px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid var(--border);background:#f9fafb;color:#111827;">
      <h3 style="margin:0;font-size:1.05rem;font-weight:600;">Book Appointment</h3>
      <button id="closeNewAppointmentModal" type="button" style="background:none;border:none;font-size:20px;cursor:pointer;color:#6b7280;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;">
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error" style="margin-bottom:12px;"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <form id="newAppointmentForm" method="post" action="<?= site_url('reception/appointments') ?>" class="form">
        <?= csrf_field() ?>
        <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
          <label>Patient
            <input type="text" id="patientSearch" placeholder="Search patient..." autocomplete="off" style="width:100%;padding:6px 8px;border:1px solid #e5e7eb;border-radius:6px;">
            <input type="hidden" name="patient_id" id="patientIdField" value="<?= esc(old('patient_id') ?? '') ?>">
            <div id="patientSuggestions" style="position:relative;z-index:60;">
              <div class="suggestions-inner" style="position:absolute;top:4px;left:0;right:0;background:white;border:1px solid #e5e7eb;border-radius:6px;box-shadow:0 10px 25px rgba(15,23,42,0.15);max-height:220px;overflow-y:auto;display:none;"></div>
            </div>
          </label>
          <label>Doctor
            <input type="text" id="doctorSearch" placeholder="Search doctor..." autocomplete="off" style="width:100%;padding:6px 8px;border:1px solid #e5e7eb;border-radius:6px;">
            <input type="hidden" name="doctor_id" id="doctorIdField" value="<?= esc(old('doctor_id') ?? '') ?>">
            <div id="doctorSuggestions" style="position:relative;z-index:60;">
              <div class="suggestions-inner" style="position:absolute;top:4px;left:0;right:0;background:white;border:1px solid #e5e7eb;border-radius:6px;box-shadow:0 10px 25px rgba(15,23,42,0.15);max-height:220px;overflow-y:auto;display:none;"></div>
            </div>
          </label>
          <label>Date
            <input type="date" name="appointment_date" value="<?= old('appointment_date') ?>" required>
          </label>
          <label>Time
            <input type="time" name="appointment_time" value="<?= old('appointment_time') ?>" required>
          </label>
          <label>Duration (minutes)
            <input type="number" name="duration" value="<?= old('duration') ?: 30 ?>" min="5" max="240">
          </label>
          <label>Type
            <select name="type">
              <option value="consultation" <?= old('type') === 'consultation' ? 'selected' : '' ?>>Consultation</option>
              <option value="follow_up" <?= old('type') === 'follow_up' ? 'selected' : '' ?>>Follow-up</option>
              <option value="emergency" <?= old('type') === 'emergency' ? 'selected' : '' ?>>Emergency</option>
              <option value="surgery" <?= old('type') === 'surgery' ? 'selected' : '' ?>>Surgery</option>
              <option value="checkup" <?= old('type') === 'checkup' ? 'selected' : '' ?>>Checkup</option>
            </select>
          </label>
        </div>
        <label>Reason
          <textarea name="reason" rows="3"><?= old('reason') ?></textarea>
        </label>
        <label>Notes
          <textarea name="notes" rows="3"><?= old('notes') ?></textarea>
        </label>
        <div style="margin-top:12px;display:flex;gap:10px;justify-content:flex-end;">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" id="cancelNewAppointment" class="btn">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
  const newAppointmentBtn = document.getElementById('newAppointmentBtn');
  const newAppointmentModal = document.getElementById('newAppointmentModal');
  const closeNewAppointmentModal = document.getElementById('closeNewAppointmentModal');
  const cancelNewAppointment = document.getElementById('cancelNewAppointment');

  function openNewAppointmentModal() {
    if (newAppointmentModal) {
      newAppointmentModal.style.display = 'flex';
    }
  }

  function closeNewAppointment() {
    if (newAppointmentModal) {
      newAppointmentModal.style.display = 'none';
    }
  }

  if (newAppointmentBtn) {
    newAppointmentBtn.addEventListener('click', function (e) {
      e.preventDefault();
      openNewAppointmentModal();
    });
  }

  if (closeNewAppointmentModal) {
    closeNewAppointmentModal.addEventListener('click', closeNewAppointment);
  }

  if (cancelNewAppointment) {
    cancelNewAppointment.addEventListener('click', function (e) {
      e.preventDefault();
      closeNewAppointment();
    });
  }

  if (newAppointmentModal) {
    newAppointmentModal.addEventListener('click', function (e) {
      if (e.target === this) {
        closeNewAppointment();
      }
    });
  }

  // --- Autocomplete search for Patient & Doctor ---
  const patientsData = <?= json_encode(array_values(array_map(function($p){
      return [
        'id' => (int)($p['id'] ?? 0),
        'label' => trim(($p['patient_id'] ?? '') . ' • ' . ($p['last_name'] ?? '') . ', ' . ($p['first_name'] ?? '')),
      ];
    }, $patients ?? [])), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

  const doctorsData = <?= json_encode(array_values(array_map(function($d){
      return [
        'id' => (int)($d['id'] ?? 0),
        'label' => trim(($d['first_name'] ?? '') . ' ' . ($d['last_name'] ?? '') . ' (' . ($d['username'] ?? 'doctor') . ')'),
      ];
    }, $doctors ?? [])), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

  function setupAutocomplete(inputId, hiddenId, containerId, items) {
    const input   = document.getElementById(inputId);
    const hidden  = document.getElementById(hiddenId);
    const wrapper = document.getElementById(containerId);
    if (!input || !hidden || !wrapper) return;
    const listEl  = wrapper.querySelector('.suggestions-inner');
    if (!listEl) return;

    function hideList() { listEl.style.display = 'none'; }

    function showSuggestions(term) {
      const t = term.trim().toLowerCase();
      listEl.innerHTML = '';
      if (!t) { hideList(); return; }
      const matches = items.filter(it => it.label.toLowerCase().includes(t));
      if (!matches.length) { hideList(); return; }

      matches.slice(0, 20).forEach(it => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = it.label;
        btn.style.display = 'block';
        btn.style.width = '100%';
        btn.style.textAlign = 'left';
        btn.style.padding = '6px 10px';
        btn.style.border = 'none';
        btn.style.background = 'white';
        btn.style.cursor = 'pointer';
        btn.addEventListener('mouseenter', () => btn.style.background = '#eff6ff');
        btn.addEventListener('mouseleave', () => btn.style.background = 'white');
        btn.addEventListener('click', () => {
          input.value  = it.label;
          hidden.value = it.id;
          hideList();
        });
        listEl.appendChild(btn);
      });

      listEl.style.display = 'block';
    }

    input.addEventListener('input', function () {
      if (this.value.trim() === '') {
        hidden.value = '';
        hideList();
      } else {
        showSuggestions(this.value);
      }
    });

    input.addEventListener('focus', function () {
      if (this.value.trim() !== '') {
        showSuggestions(this.value);
      }
    });

    document.addEventListener('click', function (e) {
      if (!wrapper.contains(e.target) && e.target !== input) {
        hideList();
      }
    });
  }

  setupAutocomplete('patientSearch', 'patientIdField', 'patientSuggestions', patientsData);
  setupAutocomplete('doctorSearch', 'doctorIdField', 'doctorSuggestions', doctorsData);

  // --- Cancel Appointment Modal ---
  const cancelModal = document.getElementById('cancelAppointmentModal');
  const closeCancelModal = document.getElementById('closeCancelModal');
  const cancelForm = document.getElementById('cancelAppointmentForm');
  const appointmentIdField = document.getElementById('appointmentIdField');

  document.querySelectorAll('.cancel-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const appointmentId = this.dataset.id;
      if (appointmentId && cancelForm) {
        let action = '<?= site_url('reception/appointments') ?>/' + appointmentId + '/cancel';
        cancelForm.action = action;
        if (cancelModal) cancelModal.style.display = 'flex';
      }
    });
  });

  if (closeCancelModal) {
    closeCancelModal.addEventListener('click', () => {
      if (cancelModal) cancelModal.style.display = 'none';
    });
  }

  if (cancelModal) {
    cancelModal.addEventListener('click', function(e) {
      if (e.target === this) {
        this.style.display = 'none';
      }
    });
  }
</script>
</body></html>
