<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patients</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .patient-table thead {
      background: #dcfce7;
    }
    .patient-table thead th {
      color: #166534;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
    }
    .badge-pill {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .badge-gender {
      background: #dbeafe;
      color: #1e40af;
    }
    .badge-visit-type {
      background: #f3f4f6;
      color: #6b7280;
    }
    .btn-consultation {
      background: #16a34a;
      color: white;
      padding: 8px 16px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border: none;
      cursor: pointer;
    }
    .btn-consultation:hover {
      background: #15803d;
    }
    .btn-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-size: 0.875rem;
      border: none;
      cursor: pointer;
    }
    .btn-view {
      background: #3b82f6;
      color: white;
    }
    .btn-edit {
      background: #f97316;
      color: white;
    }
    .btn-delete {
      background: #ef4444;
      color: white;
    }
    .badge-done {
      background: #dcfce7;
      color: #166534;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Patients</h1><small>Patient management</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout">
<?= $this->include('doctor/sidebar', [
  'specialization' => $doctorSpecialization ?? 'Doctor',
  'department' => $doctorDepartment ?? null,
  'currentPage' => 'patients'
]) ?>
  <main class="content">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <div>
          <h2 style="margin:0;font-size:1.1rem">My Assigned Patients</h2>
          <p style="margin:4px 0 0 0;font-size:0.875rem;color:#6b7280">
            Patients assigned to you as Attending Physician or through Appointments
            <?php if (isset($totalAssigned) || isset($totalAppointments)): ?>
              <br>
              <small>
                <?php if (isset($totalAssigned)): ?>
                  <span style="color:#10b981"><?= $totalAssigned ?> In-Patients</span>
                <?php endif; ?>
                <?php if (isset($totalAppointments)): ?>
                  <span style="color:#3b82f6"><?= $totalAppointments ?> Out-Patients</span>
                <?php endif; ?>
              </small>
            <?php endif; ?>
          </p>
        </div>
      </div>
      <div class="panel-body" style="overflow:auto;padding:20px">
        <div style="margin-bottom:20px">
          <div style="position:relative;max-width:400px">
            <input type="text" 
                   id="patientSearch" 
                   placeholder="Search patients by name, ID, contact, or assignment..." 
                   style="width:100%;padding:12px 16px 12px 44px;border:2px solid #e5e7eb;border-radius:8px;font-size:0.875rem;transition:all 0.2s;outline:none"
                   onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                   onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'"
                   onkeyup="filterPatients()">
            <i class="fa-solid fa-search" style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:0.875rem"></i>
            <button type="button" 
                    id="clearSearch" 
                    onclick="clearSearch()" 
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#9ca3af;cursor:pointer;display:none;padding:4px;border-radius:4px;transition:all 0.2s"
                    onmouseover="this.style.color='#6b7280';this.style.background='#f3f4f6'"
                    onmouseout="this.style.color='#9ca3af';this.style.background='none'"
                    title="Clear search">
              <i class="fa-solid fa-times"></i>
            </button>
          </div>
          <div id="searchResults" style="margin-top:8px;font-size:0.875rem;color:#6b7280;display:none">
            <span id="resultCount">0</span> patient(s) found
          </div>
        </div>
        <table class="table patient-table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">ID</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">NAME</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">ASSIGNMENT</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">BIRTHDATE</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">GENDER</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">VISIT TYPE</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">CONTACT</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #86efac">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($patients)) : foreach ($patients as $p) : ?>
              <?php
                $patientId = $p['id'];
                $fullName = trim(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? ''));
                $visitType = $p['admission_type'] ?? 'N/A';
                if ($visitType === 'admission') {
                  $visitType = 'In-Patient';
                } elseif ($visitType === 'checkup') {
                  $visitType = 'Out-Patient';
                }
                $assignmentType = $p['assignment_type'] ?? 'unknown';
                $assignmentLabel = $p['assignment_label'] ?? 'Unknown';
              ?>
              <tr class="patient-row" data-patient-id="<?= esc($patientId) ?>" data-patient-name="<?= esc(strtolower($fullName)) ?>" data-patient-contact="<?= esc(strtolower($p['phone'] ?? '')) ?>" data-patient-assignment="<?= esc(strtolower($assignmentLabel)) ?>">
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">#<?= esc($patientId) ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><strong><?= esc($fullName) ?></strong></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <?php if ($assignmentType === 'attending_physician'): ?>
                    <span style="padding:4px 10px;border-radius:6px;font-size:0.75rem;background:#dbeafe;color:#1e40af;font-weight:600">
                      <i class="fa-solid fa-user-doctor" style="margin-right:4px"></i>Attending Physician
                    </span>
                  <?php elseif ($assignmentType === 'appointment'): ?>
                    <span style="padding:4px 10px;border-radius:6px;font-size:0.75rem;background:#fef3c7;color:#92400e;font-weight:600">
                      <i class="fa-solid fa-calendar-check" style="margin-right:4px"></i>Appointment
                    </span>
                  <?php else: ?>
                    <span style="padding:4px 10px;border-radius:6px;font-size:0.75rem;background:#f3f4f6;color:#6b7280">
                      <?= esc($assignmentLabel) ?>
                    </span>
                  <?php endif; ?>
                </td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc(!empty($p['date_of_birth']) ? date('M d, Y', strtotime($p['date_of_birth'])) : 'N/A') ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <span class="badge-pill badge-gender"><?= esc(strtoupper($p['gender'] ?? 'N/A')) ?></span>
                </td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <span class="badge-pill badge-visit-type"><?= esc($visitType) ?></span>
                </td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($p['phone'] ?? 'N/A') ?></td>
                <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                  <div style="display:flex;align-items:center;gap:8px">
                    <?php if (!empty($p['consultation_done'])): ?>
                      <span class="badge-done">
                        <i class="fa-solid fa-check-circle"></i> Done
                      </span>
                    <?php else: ?>
                      <a href="<?= site_url('doctor/records/new?patient_id=' . $patientId) ?>" class="btn-consultation">
                        <i class="fa-solid fa-stethoscope"></i> Start Consultation
                      </a>
                    <?php endif; ?>
                    <a href="<?= site_url('doctor/patients/view/' . $patientId) ?>" class="btn-icon btn-view" title="View">
                      <i class="fa-solid fa-eye"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="8" style="padding:40px;color:#6b7280;text-align:center">
                  <i class="fa-solid fa-users-slash" style="font-size:3rem;opacity:0.3;margin-bottom:12px;display:block"></i>
                  <p style="margin:0;font-size:1rem;font-weight:600">No patients assigned to you yet.</p>
                  <p style="margin:8px 0 0 0;font-size:0.875rem">
                    Patients will appear here when receptionist assigns them to you as:<br>
                    • <strong>Attending Physician</strong> (for In-Patients)<br>
                    • <strong>Appointment</strong> (for Out-Patients)
                  </p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
  function filterPatients() {
    const searchInput = document.getElementById('patientSearch');
    const searchTerm = searchInput.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.patient-row');
    const clearBtn = document.getElementById('clearSearch');
    const resultDiv = document.getElementById('searchResults');
    const resultCount = document.getElementById('resultCount');
    
    let visibleCount = 0;
    
    if (searchTerm === '') {
      // Show all rows
      rows.forEach(row => {
        row.style.display = '';
        visibleCount++;
      });
      clearBtn.style.display = 'none';
      resultDiv.style.display = 'none';
    } else {
      // Filter rows
      rows.forEach(row => {
        const patientId = row.getAttribute('data-patient-id') || '';
        const patientName = row.getAttribute('data-patient-name') || '';
        const patientContact = row.getAttribute('data-patient-contact') || '';
        const patientAssignment = row.getAttribute('data-patient-assignment') || '';
        
        const searchableText = `${patientId} ${patientName} ${patientContact} ${patientAssignment}`;
        
        if (searchableText.includes(searchTerm)) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });
      
      clearBtn.style.display = 'block';
      resultDiv.style.display = 'block';
      resultCount.textContent = visibleCount;
    }
  }
  
  function clearSearch() {
    const searchInput = document.getElementById('patientSearch');
    searchInput.value = '';
    filterPatients();
    searchInput.focus();
  }
  
  // Add Enter key support
  document.getElementById('patientSearch').addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      clearSearch();
    }
  });
</script>
</body></html>


