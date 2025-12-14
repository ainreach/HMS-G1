<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patient Lookup | HMS</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .search-container {
      position: relative;
    }
    .search-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: #6b7280;
      font-size: 1.1rem;
    }
    .search-input {
      padding-left: 48px;
      font-size: 1rem;
    }
    .patient-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      border: 1px solid #e5e7eb;
      padding: 20px;
      margin-bottom: 16px;
      transition: all 0.2s;
    }
    .patient-card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.12);
      transform: translateY(-2px);
    }
    .patient-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 16px;
      padding-bottom: 16px;
      border-bottom: 2px solid #f3f4f6;
    }
    .patient-name-section {
      flex: 1;
    }
    .patient-name {
      font-size: 1.5rem;
      font-weight: 700;
      color: #111827;
      margin-bottom: 4px;
    }
    .patient-id {
      color: #3b82f6;
      font-weight: 600;
      font-size: 0.95rem;
    }
    .status-badge {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .status-admitted {
      background: #dcfce7;
      color: #166534;
    }
    .status-discharged {
      background: #e0e7ff;
      color: #3730a3;
    }
    .status-outpatient {
      background: #fef3c7;
      color: #92400e;
    }
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 16px;
      margin-bottom: 16px;
    }
    .info-item {
      display: flex;
      align-items: start;
      gap: 10px;
    }
    .info-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .info-icon.blue { background: #dbeafe; color: #1e40af; }
    .info-icon.green { background: #d1fae5; color: #065f46; }
    .info-icon.purple { background: #e9d5ff; color: #6b21a8; }
    .info-icon.orange { background: #fed7aa; color: #9a3412; }
    .info-icon.red { background: #fee2e2; color: #991b1b; }
    .info-content {
      flex: 1;
    }
    .info-label {
      font-size: 0.75rem;
      color: #6b7280;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 600;
      margin-bottom: 4px;
    }
    .info-value {
      font-size: 0.95rem;
      color: #111827;
      font-weight: 500;
    }
    .room-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      background: #dbeafe;
      color: #1e40af;
      border-radius: 6px;
      font-weight: 600;
      font-size: 0.875rem;
    }
    .bed-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      background: #fef3c7;
      color: #92400e;
      border-radius: 6px;
      font-weight: 600;
      font-size: 0.875rem;
    }
    .action-buttons {
      display: flex;
      gap: 8px;
      margin-top: 16px;
      padding-top: 16px;
      border-top: 1px solid #f3f4f6;
    }
    .btn-action {
      padding: 8px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }
    .btn-view {
      background: #3b82f6;
      color: white;
    }
    .btn-view:hover {
      background: #2563eb;
    }
    .btn-edit {
      background: #10b981;
      color: white;
    }
    .btn-edit:hover {
      background: #059669;
    }
    .btn-appointment {
      background: #8b5cf6;
      color: white;
    }
    .btn-appointment:hover {
      background: #7c3aed;
    }
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #6b7280;
    }
    .empty-state i {
      font-size: 4rem;
      opacity: 0.3;
      margin-bottom: 16px;
      display: block;
    }
    .loading {
      text-align: center;
      padding: 40px;
      color: #6b7280;
    }
    .stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 12px;
      margin-bottom: 20px;
    }
    .stat-item {
      background: white;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      text-align: center;
    }
    .stat-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: #111827;
    }
    .stat-label {
      font-size: 0.75rem;
      color: #6b7280;
      text-transform: uppercase;
      margin-top: 4px;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Patient Lookup</small></div>
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
  <a href="<?= site_url('reception/appointments') ?>"><i class="fa-solid fa-calendar" style="margin-right:8px"></i>Appointment Management</a>
  <a href="<?= site_url('reception/rooms') ?>"><i class="fa-solid fa-door-open" style="margin-right:8px"></i>Room Management</a>
  <a href="<?= site_url('reception/rooms/admit') ?>"><i class="fa-solid fa-bed" style="margin-right:8px"></i>Room Admission</a>
  <a href="<?= site_url('reception/in-patients') ?>"><i class="fa-solid fa-hospital" style="margin-right:8px"></i>In-Patients</a>
  <a href="<?= site_url('reception/patient-lookup') ?>" class="active" aria-current="page"><i class="fa-solid fa-magnifying-glass" style="margin-right:8px"></i>Patient Lookup</a>
</nav></aside>
  <main class="content" style="padding:20px">
    
    <section class="panel" style="margin-bottom:20px">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.25rem;font-weight:600">
          <i class="fa-solid fa-magnifying-glass" style="margin-right:8px;color:#3b82f6"></i>Patient Search & Information
        </h2>
      </div>
      <div class="panel-body">
        <div class="search-container">
          <i class="fa-solid fa-search search-icon"></i>
          <input 
            id="q" 
            type="search" 
            placeholder="Search by name, patient ID, phone number, or email..." 
            class="form-control search-input"
            style="width:100%;padding:14px 16px 14px 48px;border:2px solid #e5e7eb;border-radius:10px;font-size:1rem;transition:all 0.2s"
            autocomplete="off"
          />
        </div>
        <div style="margin-top:12px;font-size:0.875rem;color:#6b7280">
          <i class="fa-solid fa-info-circle"></i> 
          Search by patient name, ID, phone number, or email address. Results will show comprehensive patient information including room assignment, admission status, and medical history.
        </div>
      </div>
    </section>

    <!-- Search Statistics -->
    <div id="searchStats" class="stats-row" style="display:none">
      <div class="stat-item">
        <div class="stat-value" id="statTotal">0</div>
        <div class="stat-label">Total Results</div>
      </div>
      <div class="stat-item">
        <div class="stat-value" id="statAdmitted">0</div>
        <div class="stat-label">Admitted</div>
      </div>
      <div class="stat-item">
        <div class="stat-value" id="statDischarged">0</div>
        <div class="stat-label">Discharged</div>
      </div>
      <div class="stat-item">
        <div class="stat-value" id="statOutpatient">0</div>
        <div class="stat-label">Out-Patients</div>
      </div>
    </div>

    <!-- Search Results -->
    <div id="results">
      <div class="empty-state">
        <i class="fa-solid fa-search"></i>
        <h3 style="margin:0 0 8px;color:#374151">Search for a patient</h3>
        <p style="margin:0;color:#6b7280">Enter a patient name, ID, phone number, or email to begin searching</p>
      </div>
    </div>

  </main></div>

<script>
let searchTimeout;
const searchInput = document.getElementById('q');
const resultsDiv = document.getElementById('results');
const searchStats = document.getElementById('searchStats');

function loadAllPatients() {
  const url = '<?= site_url('reception/patients/all') ?>';

  resultsDiv.innerHTML = `
    <div class="loading">
      <i class="fa-solid fa-spinner fa-spin" style="font-size:2rem;margin-bottom:12px;display:block"></i>
      <p>Loading patients...</p>
    </div>
  `;

  fetch(url, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
    .then(r => r.json())
    .then(patients => {
      displayResults(patients);
      updateStats(patients);
    })
    .catch(error => {
      console.error('Load all patients error:', error);
      resultsDiv.innerHTML = `
        <div class="empty-state">
          <i class="fa-solid fa-exclamation-triangle" style="color:#dc2626"></i>
          <h3 style="margin:0 0 8px;color:#dc2626">Unable to load patients</h3>
          <p style="margin:0;color:#6b7280">Please try refreshing the page or using the search box.</p>
        </div>
      `;
      searchStats.style.display = 'none';
    });
}

// Load all patients on initial page load
window.addEventListener('DOMContentLoaded', function() {
  loadAllPatients();
});

// Real-time search with debounce
searchInput.addEventListener('input', function() {
  const query = this.value.trim();
  
  clearTimeout(searchTimeout);
  
  if (query.length === 0) {
    // If input is cleared, reload full patient list
    loadAllPatients();
    return;
  }
  
  // Show loading state
  resultsDiv.innerHTML = `
    <div class="loading">
      <i class="fa-solid fa-spinner fa-spin" style="font-size:2rem;margin-bottom:12px;display:block"></i>
      <p>Searching patients...</p>
    </div>
  `;
  
  searchTimeout = setTimeout(() => {
    runSearch(query);
  }, 300);
});

// Search on Enter key
searchInput.addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    if (query.length >= 2) {
      runSearch(query);
    }
  }
});

function runSearch(query) {
  const url = '<?= site_url('reception/patients/search') ?>?q=' + encodeURIComponent(query);
  
  fetch(url, { 
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
    .then(r => r.json())
    .then(patients => {
      displayResults(patients);
      updateStats(patients);
    })
    .catch(error => {
      console.error('Search error:', error);
      resultsDiv.innerHTML = `
        <div class="empty-state">
          <i class="fa-solid fa-exclamation-triangle" style="color:#dc2626"></i>
          <h3 style="margin:0 0 8px;color:#dc2626">Search Failed</h3>
          <p style="margin:0;color:#6b7280">An error occurred while searching. Please try again.</p>
        </div>
      `;
      searchStats.style.display = 'none';
    });
}

function displayResults(patients) {
  if (!patients || patients.length === 0) {
    resultsDiv.innerHTML = `
      <div class="empty-state">
        <i class="fa-solid fa-user-xmark"></i>
        <h3 style="margin:0 0 8px;color:#374151">No patients found</h3>
        <p style="margin:0;color:#6b7280">Try a different search term or check the spelling</p>
      </div>
    `;
    return;
  }
  
  let html = '';
  patients.forEach(patient => {
    const fullName = `${patient.first_name || ''} ${patient.middle_name || ''} ${patient.last_name || ''}`.trim();
    const age = patient.date_of_birth ? calculateAge(patient.date_of_birth) : 'N/A';
    const status = patient.patient_status || 'Out-Patient';
    const statusClass = status.toLowerCase().replace(' ', '-');
    
    html += `
      <div class="patient-card">
        <div class="patient-header">
          <div class="patient-name-section">
            <div class="patient-name">${escapeHtml(fullName)}</div>
            <div class="patient-id">
              <i class="fa-solid fa-id-card"></i> ${escapeHtml(patient.patient_id || 'N/A')}
            </div>
          </div>
          <span class="status-badge status-${statusClass}">
            <i class="fa-solid ${getStatusIcon(status)}"></i>
            ${escapeHtml(status)}
          </span>
        </div>
        
        <div class="info-grid">
          ${patient.room_number ? `
            <div class="info-item">
              <div class="info-icon blue">
                <i class="fa-solid fa-door-open"></i>
              </div>
              <div class="info-content">
                <div class="info-label">Room Assignment</div>
                <div class="info-value">
                  <span class="room-badge">
                    <i class="fa-solid fa-bed"></i>
                    Room ${escapeHtml(patient.room_number)} - ${escapeHtml(patient.room_type || 'N/A')}
                    ${patient.floor ? ` (Floor ${escapeHtml(patient.floor)})` : ''}
                  </span>
                </div>
              </div>
            </div>
          ` : ''}
          
          ${patient.bed_number ? `
            <div class="info-item">
              <div class="info-icon orange">
                <i class="fa-solid fa-bed"></i>
              </div>
              <div class="info-content">
                <div class="info-label">Bed Assignment</div>
                <div class="info-value">
                  <span class="bed-badge">
                    <i class="fa-solid fa-bed"></i>
                    Bed ${escapeHtml(patient.bed_number)}
                    ${patient.bed_type_name ? ` (${escapeHtml(patient.bed_type_name)})` : ''}
                  </span>
                </div>
              </div>
            </div>
          ` : ''}
          
          ${patient.doctor_first_name || patient.doctor_last_name ? `
            <div class="info-item">
              <div class="info-icon purple">
                <i class="fa-solid fa-user-doctor"></i>
              </div>
              <div class="info-content">
                <div class="info-label">Attending Physician</div>
                <div class="info-value">
                  Dr. ${escapeHtml((patient.doctor_first_name || '') + ' ' + (patient.doctor_last_name || ''))}
                  ${patient.doctor_specialization ? `<br><small style="color:#6b7280">${escapeHtml(patient.doctor_specialization)}</small>` : ''}
                </div>
              </div>
            </div>
          ` : ''}
          
          <div class="info-item">
            <div class="info-icon green">
              <i class="fa-solid fa-user"></i>
            </div>
            <div class="info-content">
              <div class="info-label">Personal Information</div>
              <div class="info-value">
                ${escapeHtml(patient.gender || 'N/A')} • Age ${age}
                ${patient.blood_type ? ` • Blood Type: ${escapeHtml(patient.blood_type)}` : ''}
              </div>
            </div>
          </div>
          
          <div class="info-item">
            <div class="info-icon blue">
              <i class="fa-solid fa-phone"></i>
            </div>
            <div class="info-content">
              <div class="info-label">Contact Information</div>
              <div class="info-value">
                ${escapeHtml(patient.phone || 'N/A')}
                ${patient.email ? `<br>${escapeHtml(patient.email)}` : ''}
              </div>
            </div>
          </div>
          
          ${patient.admission_date ? `
            <div class="info-item">
              <div class="info-icon ${status === 'Admitted' ? 'green' : 'orange'}">
                <i class="fa-solid fa-calendar-check"></i>
              </div>
              <div class="info-content">
                <div class="info-label">${status === 'Admitted' ? 'Admission Date' : 'Discharge Date'}</div>
                <div class="info-value">
                  ${formatDate(patient.admission_date)}
                  ${patient.days_admitted && status === 'Admitted' ? ` (${patient.days_admitted} days)` : ''}
                  ${patient.discharge_date ? `<br>Discharged: ${formatDate(patient.discharge_date)}` : ''}
                </div>
              </div>
            </div>
          ` : ''}
          
          ${patient.admission_reason ? `
            <div class="info-item">
              <div class="info-icon red">
                <i class="fa-solid fa-stethoscope"></i>
              </div>
              <div class="info-content">
                <div class="info-label">Admission Reason</div>
                <div class="info-value">${escapeHtml(patient.admission_reason)}</div>
              </div>
            </div>
          ` : ''}
          
          ${patient.allergies ? `
            <div class="info-item">
              <div class="info-icon red">
                <i class="fa-solid fa-exclamation-triangle"></i>
              </div>
              <div class="info-content">
                <div class="info-label">Allergies</div>
                <div class="info-value" style="color:#dc2626;font-weight:600">${escapeHtml(patient.allergies)}</div>
              </div>
            </div>
          ` : ''}
          
          ${patient.emergency_contact_name ? `
            <div class="info-item">
              <div class="info-icon orange">
                <i class="fa-solid fa-phone-alt"></i>
              </div>
              <div class="info-content">
                <div class="info-label">Emergency Contact</div>
                <div class="info-value">
                  ${escapeHtml(patient.emergency_contact_name)}
                  ${patient.emergency_contact_relation ? ` (${escapeHtml(patient.emergency_contact_relation)})` : ''}
                  ${patient.emergency_contact_phone ? `<br>${escapeHtml(patient.emergency_contact_phone)}` : ''}
                </div>
              </div>
            </div>
          ` : ''}
          
          <div class="info-item">
            <div class="info-icon purple">
              <i class="fa-solid fa-file-medical"></i>
            </div>
            <div class="info-content">
              <div class="info-label">Medical History</div>
              <div class="info-value">
                ${patient.total_consultations || 0} Consultations
                ${patient.total_appointments ? ` • ${patient.total_appointments} Appointments` : ''}
              </div>
            </div>
          </div>
        </div>
        
        <div class="action-buttons">
          <a href="${'<?= site_url('reception/patients/view') ?>/' + patient.id}" class="btn-action btn-view">
            <i class="fa-solid fa-eye"></i> View Full Profile
          </a>
          <a href="${'<?= site_url('reception/patients') ?>?search=' + encodeURIComponent(patient.patient_id)}" class="btn-action btn-edit">
            <i class="fa-solid fa-edit"></i> Edit Patient
          </a>
          <a href="${'<?= site_url('reception/appointments/new') ?>?patient_id=' + patient.id}" class="btn-action btn-appointment">
            <i class="fa-solid fa-calendar-plus"></i> Book Appointment
          </a>
          ${patient.room_number ? `
            <a href="${'<?= site_url('reception/in-patients/view') ?>/' + patient.id}" class="btn-action btn-view">
              <i class="fa-solid fa-hospital"></i> View Admission
            </a>
          ` : ''}
        </div>
      </div>
    `;
  });
  
  resultsDiv.innerHTML = html;
}

function updateStats(patients) {
  if (!patients || patients.length === 0) {
    searchStats.style.display = 'none';
    return;
  }
  
  const stats = {
    total: patients.length,
    admitted: patients.filter(p => p.patient_status === 'Admitted').length,
    discharged: patients.filter(p => p.patient_status === 'Discharged').length,
    outpatient: patients.filter(p => p.patient_status === 'Out-Patient').length
  };
  
  document.getElementById('statTotal').textContent = stats.total;
  document.getElementById('statAdmitted').textContent = stats.admitted;
  document.getElementById('statDischarged').textContent = stats.discharged;
  document.getElementById('statOutpatient').textContent = stats.outpatient;
  
  searchStats.style.display = 'grid';
}

function getStatusIcon(status) {
  switch(status) {
    case 'Admitted': return 'fa-hospital';
    case 'Discharged': return 'fa-check-circle';
    default: return 'fa-user';
  }
}

function calculateAge(dateOfBirth) {
  if (!dateOfBirth) return 'N/A';
  const dob = new Date(dateOfBirth);
  const today = new Date();
  let age = today.getFullYear() - dob.getFullYear();
  const monthDiff = today.getMonth() - dob.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
    age--;
  }
  return age;
}

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  });
}

function escapeHtml(str) {
  if (!str) return '';
  return String(str).replace(/[&<>"]+/g, function(s) { 
    return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[s]; 
  });
}
</script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
