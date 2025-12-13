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
  <a href="<?= site_url('dashboard/receptionist') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
  <a href="<?= site_url('reception/patients') ?>"><i class="fa-solid fa-users" style="margin-right:8px"></i>Patient Management</a>
  <a href="<?= site_url('reception/appointments') ?>" class="active" aria-current="page"><i class="fa-solid fa-calendar" style="margin-right:8px"></i>Appointment Management</a>
  <a href="<?= site_url('reception/rooms') ?>"><i class="fa-solid fa-door-open" style="margin-right:8px"></i>Room Management</a>
  <a href="<?= site_url('reception/rooms/admit') ?>"><i class="fa-solid fa-bed" style="margin-right:8px"></i>Room Admission</a>
  <a href="<?= site_url('reception/in-patients') ?>"><i class="fa-solid fa-hospital" style="margin-right:8px"></i>In-Patients</a>
  <a href="<?= site_url('reception/patient-lookup') ?>"><i class="fa-solid fa-magnifying-glass" style="margin-right:8px"></i>Patient Lookup</a>
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
            <tr style="background:#f9fafb">
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600">Appointment #</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600">Patient</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600">Doctor</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600">Date & Time</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600">Type</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600">Status</th>
              <th style="text-align:center;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600">Actions</th>
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
              <?php
                $appointmentNumber = $a['appointment_number'] ?? 'N/A';
                $typeLabel = ucfirst(str_replace('_', ' ', $a['type'] ?? 'consultation'));
                $duration = $a['duration'] ?? 30;
              ?>
              <tr style="border-bottom:1px solid #f3f4f6" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                <td style="padding:12px;font-size:0.875rem">
                  <span style="font-weight:600;color:#3b82f6"><?= esc($appointmentNumber) ?></span>
                  <br>
                  <small style="color:#6b7280"><?= esc($duration) ?> min</small>
                </td>
                <td style="padding:12px;font-size:0.875rem">
                  <div style="font-weight:600;color:#111827"><?= esc($patientName) ?></div>
                  <small style="color:#6b7280"><?= esc($a['patient_code'] ?? '') ?></small>
                  <?php if (!empty($a['phone'])): ?>
                    <br><small style="color:#6b7280"><i class="fa-solid fa-phone" style="font-size:0.7rem"></i> <?= esc($a['phone']) ?></small>
                  <?php endif; ?>
                </td>
                <td style="padding:12px;font-size:0.875rem">
                  <div style="font-weight:600;color:#111827"><?= esc($doctorLabel) ?></div>
                </td>
                <td style="padding:12px;font-size:0.875rem;white-space:nowrap;">
                  <div style="font-weight:600;color:#111827"><?= esc($dateLabel) ?></div>
                  <div style="color:#6b7280"><i class="fa-solid fa-clock" style="font-size:0.7rem"></i> <?= esc($timeLabel) ?></div>
                </td>
                <td style="padding:12px;font-size:0.875rem">
                  <span style="padding:4px 10px;background:#e0f2fe;color:#0369a1;border-radius:6px;font-size:0.75rem;font-weight:500">
                    <?= esc($typeLabel) ?>
                  </span>
                </td>
                <td style="padding:12px;font-size:0.875rem;white-space:nowrap;">
                  <span style="padding:6px 12px;border-radius:6px;font-size:0.75rem;font-weight:600;background-color:<?= $statusBg ?>;color:<?= $statusColor ?>">
                    <?= esc($statusLabel) ?>
                  </span>
                </td>
                <td style="padding:12px;text-align:center">
                  <div style="display:flex;gap:4px;justify-content:center;flex-wrap:wrap">
                    <a href="<?= site_url('reception/appointments/' . ($a['id'] ?? 0)) ?>" 
                       class="btn" 
                       style="padding:6px 10px;font-size:0.75rem;text-decoration:none;border-radius:4px;background:#3b82f6;color:white"
                       title="View Details">
                      <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="<?= site_url('reception/appointments/' . ($a['id'] ?? 0) . '/edit') ?>" 
                       class="btn" 
                       style="padding:6px 10px;font-size:0.75rem;text-decoration:none;border-radius:4px;background:#f59e0b;color:white"
                       title="Edit Appointment">
                      <i class="fa-solid fa-pencil"></i>
                    </a>
                    <?php if ($rawStatus === 'scheduled'): ?>
                      <form action="<?= site_url('reception/appointments/' . ($a['id'] ?? 0) . '/checkin') ?>" method="post" style="display:inline">
                        <?= csrf_field() ?>
                        <button type="submit" 
                                class="btn" 
                                style="padding:6px 10px;font-size:0.75rem;border-radius:4px;background:#10b981;color:white;border:none;cursor:pointer"
                                title="Check-in Patient">
                          <i class="fa-solid fa-check"></i>
                        </button>
                      </form>
                    <?php endif; ?>
                    <?php if ($rawStatus !== 'cancelled'): ?>
                      <form action="<?= site_url('reception/appointments/' . ($a['id'] ?? 0) . '/cancel') ?>" 
                            method="post" 
                            style="display:inline" 
                            onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                        <?= csrf_field() ?>
                        <button type="submit" 
                                class="btn" 
                                style="padding:6px 10px;font-size:0.75rem;border-radius:4px;background:#ef4444;color:white;border:none;cursor:pointer"
                                title="Cancel Appointment">
                          <i class="fa-solid fa-times"></i>
                        </button>
                      </form>
                    <?php endif; ?>
                  </div>
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
          <label>Doctor <span style="color:#dc2626">*</span>
            <select name="doctor_id" id="doctorIdField" required style="width:100%;padding:8px 12px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:0.95rem;background:white;">
              <option value="">-- Select Doctor --</option>
              <?php 
              $userModel = model('App\\Models\\UserModel');
              $doctors = $userModel->where('role', 'doctor')->where('is_active', 1)->orderBy('first_name', 'ASC')->findAll(100);
              
              if (!empty($doctors)) : 
                // Group doctors by specialization
                $groupedDoctors = [];
                foreach ($doctors as $doctor) {
                  $specialization = $doctor['specialization'] ?? 'General';
                  if (!isset($groupedDoctors[$specialization])) {
                    $groupedDoctors[$specialization] = [];
                  }
                  $groupedDoctors[$specialization][] = $doctor;
                }
                
                // Sort specializations
                ksort($groupedDoctors);
                
                foreach ($groupedDoctors as $specialization => $docs) : ?>
                  <optgroup label="<?= esc($specialization) ?>">
                    <?php foreach ($docs as $doctor) : 
                      $doctorName = trim(($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? ''));
                      $selected = (old('doctor_id') == $doctor['id']) ? 'selected' : '';
                    ?>
                      <option value="<?= esc($doctor['id']) ?>" <?= $selected ?>>
                        Dr. <?= esc($doctorName) ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endforeach; 
              else: ?>
                <option value="">No doctors available</option>
              <?php endif; ?>
            </select>
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

  // Doctor is now a dropdown, no need for autocomplete data

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

  // Setup autocomplete for patient search only (doctor is now a dropdown)
  setupAutocomplete('patientSearch', 'patientIdField', 'patientSuggestions', patientsData);
</script>
</body></html>
