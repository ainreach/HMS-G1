<?php
helper('form');
$specializations = \Config\DoctorSpecializations::getGrouped();
?>
<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit In-Patient | HMS</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .form-section {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      border: 1px solid #e5e7eb;
      padding: 24px;
      margin-bottom: 20px;
    }
    .section-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: #111827;
      margin-bottom: 16px;
      padding-bottom: 12px;
      border-bottom: 2px solid #3b82f6;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-label {
      display: block;
      font-weight: 600;
      color: #374151;
      margin-bottom: 6px;
      font-size: 0.95rem;
    }
    .form-label .required {
      color: #dc2626;
      margin-left: 4px;
    }
    .form-control {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid #d1d5db;
      border-radius: 8px;
      font-size: 0.95rem;
      transition: all 0.2s;
    }
    .form-control:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .form-hint {
      font-size: 0.875rem;
      color: #6b7280;
      margin-top: 4px;
    }
    .room-info-card {
      background: #f0f9ff;
      border: 1px solid #bae6fd;
      border-radius: 8px;
      padding: 12px;
      margin-top: 8px;
      font-size: 0.875rem;
    }
    .bed-selection {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
      gap: 12px;
      margin-top: 12px;
      padding: 16px;
      background: #f9fafb;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
    }
    .bed-option {
      padding: 16px;
      border: 2px solid #d1d5db;
      border-radius: 10px;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s;
      background: white;
      min-height: 100px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .bed-option:hover:not(.unavailable) {
      border-color: #3b82f6;
      background: #eff6ff;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(59, 130, 246, 0.2);
    }
    .bed-option.selected {
      border-color: #3b82f6;
      background: #dbeafe;
      border-width: 3px;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .bed-option.unavailable {
      opacity: 0.6;
      cursor: not-allowed;
      background: #f3f4f6;
      border-color: #d1d5db;
    }
    .bed-number {
      font-size: 1.25rem;
      font-weight: 700;
      color: #111827;
      margin-bottom: 6px;
    }
    .bed-type {
      font-size: 0.875rem;
      color: #6b7280;
      margin-bottom: 8px;
    }
    .bed-status {
      font-size: 0.75rem;
      font-weight: 600;
      padding: 4px 8px;
      border-radius: 4px;
      margin-top: 4px;
    }
    .bed-status.available {
      background: #d1fae5;
      color: #065f46;
    }
    .bed-status.occupied {
      background: #fee2e2;
      color: #991b1b;
    }
    .no-beds-message {
      padding: 20px;
      text-align: center;
      color: #6b7280;
      background: white;
      border-radius: 8px;
      border: 1px dashed #d1d5db;
    }
    .patient-info-display {
      background: #f0f9ff;
      border: 1px solid #bae6fd;
      border-radius: 8px;
      padding: 16px;
      margin-top: 8px;
    }
    .patient-info-display strong {
      display: block;
      margin-bottom: 8px;
      color: #1e40af;
      font-size: 1rem;
    }
    .patient-info-display div {
      margin-bottom: 6px;
      font-size: 0.875rem;
      color: #374151;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Edit In-Patient</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Reception navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/receptionist') ?>">Overview</a>
  <a href="<?= site_url('reception/patients') ?>">Patient Management</a>
  <a href="<?= site_url('reception/appointments') ?>">Appointment Management</a>
  <a href="<?= site_url('reception/rooms') ?>">Room Management</a>
  <a href="<?= site_url('reception/in-patients') ?>" class="active">In-Patients</a>
  <a href="<?= site_url('reception/patient-lookup') ?>">Patient Lookup</a>
</nav></aside>
  <main class="content" style="padding:20px">
    
    <a href="<?= site_url('reception/in-patients/view/' . $patient['id']) ?>" style="display:inline-flex;align-items:center;gap:8px;margin-bottom:20px;color:#6b7280;text-decoration:none;font-weight:600;font-size:0.875rem">
      <i class="fa-solid fa-arrow-left"></i> Back to Patient Details
    </a>

    <?php if (session()->getFlashdata('error')): ?>
      <div style="background:#fef2f2;border:1px solid #ef4444;border-radius:8px;padding:14px;margin-bottom:20px;display:flex;align-items:center;gap:10px">
        <i class="fa-solid fa-exclamation-circle" style="color:#dc2626;font-size:1.2rem"></i>
        <p style="margin:0;color:#dc2626;font-weight:600"><?= esc(session()->getFlashdata('error')) ?></p>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div style="background:#dcfce7;border:1px solid #16a34a;border-radius:8px;padding:14px;margin-bottom:20px;display:flex;align-items:center;gap:10px">
        <i class="fa-solid fa-check-circle" style="color:#16a34a;font-size:1.2rem"></i>
        <p style="margin:0;color:#166534;font-weight:600"><?= esc(session()->getFlashdata('success')) ?></p>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('reception/in-patients/update/' . $patient['id']) ?>" id="editForm">
      <?= csrf_field() ?>

      <!-- Patient Information Section -->
      <div class="form-section">
        <div class="section-title">
          <i class="fa-solid fa-user-injured"></i>
          <span>Patient Information</span>
        </div>
        
        <div style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);border-radius:12px;padding:24px;color:white;margin-bottom:20px">
          <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px">
            <div style="width:60px;height:60px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.5rem">
              <i class="fa-solid fa-user"></i>
            </div>
            <div>
              <h3 style="margin:0;font-size:1.25rem;font-weight:700;color:white">
                <?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['middle_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?>
              </h3>
              <p style="margin:4px 0 0 0;font-size:0.875rem;color:rgba(255,255,255,0.9)">
                Patient ID: <span style="font-family:monospace;font-weight:600"><?= esc($patient['patient_id'] ?? 'N/A') ?></span>
              </p>
            </div>
          </div>
          
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;background:rgba(255,255,255,0.1);border-radius:8px;padding:16px">
            <div style="display:flex;align-items:center;gap:10px">
              <i class="fa-solid fa-phone" style="font-size:1.1rem;width:24px"></i>
              <div>
                <div style="font-size:0.75rem;opacity:0.9;margin-bottom:2px">Phone</div>
                <div style="font-weight:600;font-size:0.95rem"><?= esc($patient['phone'] ?? 'N/A') ?></div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
              <i class="fa-solid fa-envelope" style="font-size:1.1rem;width:24px"></i>
              <div>
                <div style="font-size:0.75rem;opacity:0.9;margin-bottom:2px">Email</div>
                <div style="font-weight:600;font-size:0.95rem;word-break:break-word"><?= esc($patient['email'] ?? 'N/A') ?></div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
              <i class="fa-solid fa-calendar-check" style="font-size:1.1rem;width:24px"></i>
              <div>
                <div style="font-size:0.75rem;opacity:0.9;margin-bottom:2px">Admission Date</div>
                <div style="font-weight:600;font-size:0.95rem">
                  <?= esc($patient['admission_date'] ? date('M d, Y', strtotime($patient['admission_date'])) : 'N/A') ?>
                </div>
              </div>
            </div>
            <?php if (!empty($patient['date_of_birth'])): 
              $dob = new \DateTime($patient['date_of_birth']);
              $age = $dob->diff(new \DateTime())->y;
            ?>
            <div style="display:flex;align-items:center;gap:10px">
              <i class="fa-solid fa-birthday-cake" style="font-size:1.1rem;width:24px"></i>
              <div>
                <div style="font-size:0.75rem;opacity:0.9;margin-bottom:2px">Age</div>
                <div style="font-weight:600;font-size:0.95rem"><?= $age ?> years old</div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Room & Bed Selection Section -->
      <div class="form-section">
        <div class="section-title">
          <i class="fa-solid fa-bed"></i>
          <span>Room & Bed Assignment</span>
        </div>
        
        <div class="form-group">
          <label class="form-label">
            Select Room <span class="required">*</span>
          </label>
          <select name="room_id" id="roomSelect" class="form-control" required>
            <option value="">-- Select Room --</option>
            <?php if (!empty($allRooms)) : foreach ($allRooms as $r): ?>
              <option value="<?= esc($r['id']) ?>" 
                <?= (old('room_id', $patient['assigned_room_id'] ?? '') == $r['id']) ? 'selected' : '' ?>
                data-room-number="<?= esc($r['room_number']) ?>"
                data-room-type="<?= esc($r['room_type']) ?>"
                data-floor="<?= esc($r['floor'] ?? 1) ?>"
                data-rate="<?= esc($r['rate_per_day'] ?? 0) ?>"
                data-capacity="<?= esc($r['capacity'] ?? 1) ?>"
                data-occupancy="<?= esc($r['current_occupancy'] ?? 0) ?>">
                <?= esc($r['room_number']) ?> - <?= esc(ucfirst($r['room_type'])) ?>
                <?php if (isset($r['floor'])): ?>
                  (Floor <?= esc($r['floor']) ?>)
                <?php endif; ?>
                - <?= esc($r['current_occupancy']) ?>/<?= esc($r['capacity']) ?> occupied
                - ₱<?= number_format($r['rate_per_day'], 2) ?>/day
              </option>
            <?php endforeach; else: ?>
              <option value="">No available rooms</option>
            <?php endif; ?>
          </select>
          <div class="form-hint">
            <i class="fa-solid fa-info-circle"></i> Select the room for this patient
          </div>
          <div id="roomInfo" class="room-info-card" style="display:none;">
            <strong>Room Details:</strong>
            <div id="roomDetails"></div>
          </div>
        </div>

        <div class="form-group" id="bedSelectionGroup" style="display:none;">
          <label class="form-label">
            Select Bed <span class="required">*</span>
          </label>
          <div id="bedSelection" class="bed-selection">
            <div class="no-beds-message">
              <i class="fa-solid fa-spinner fa-spin" style="font-size:1.5rem;margin-bottom:8px;display:block"></i>
              <p style="margin:0">Loading beds...</p>
            </div>
          </div>
          <input type="hidden" name="bed_id" id="bedIdField" required value="<?= old('bed_id', $patient['assigned_bed_id'] ?? '') ?>">
          <div class="form-hint">
            <i class="fa-solid fa-info-circle"></i> Click on an available bed to select it. Selected bed will be highlighted in blue.
          </div>
          <div id="bedSelectionError" style="display:none;margin-top:8px;padding:10px;background:#fef2f2;border:1px solid #ef4444;border-radius:6px;color:#dc2626;font-size:0.875rem">
            <i class="fa-solid fa-exclamation-triangle"></i> <span id="bedErrorText"></span>
          </div>
        </div>
      </div>

      <!-- Medical Information Section -->
      <div class="form-section">
        <div class="section-title">
          <i class="fa-solid fa-stethoscope"></i>
          <span>Medical Information</span>
        </div>
        
        <div class="form-group">
          <label class="form-label">
            Attending Physician <span class="required">*</span>
          </label>
          <select name="attending_physician_id" class="form-control" required>
            <option value="">-- Select Attending Physician --</option>
            <?php if (!empty($doctors)) : foreach ($doctors as $doctor) : ?>
              <option value="<?= esc($doctor['id']) ?>" 
                <?= (old('attending_physician_id', $patient['attending_physician_id'] ?? '') == $doctor['id']) ? 'selected' : '' ?>>
                Dr. <?= esc($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                <?php if (!empty($doctor['specialization'])): ?>
                  - <?= esc($doctor['specialization']) ?>
                <?php endif; ?>
              </option>
            <?php endforeach; else: ?>
              <option value="">No doctors available</option>
            <?php endif; ?>
          </select>
          <div class="form-hint">
            <i class="fa-solid fa-info-circle"></i> Select the doctor who will be responsible for this patient's care
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">
            Admission Reason <span class="required">*</span>
          </label>
          <textarea name="admission_reason" class="form-control" rows="3" required placeholder="Describe the reason for admission (e.g., surgery, observation, treatment, etc.)"><?= esc(old('admission_reason', $patient['admission_reason'] ?? '')) ?></textarea>
          <div class="form-hint">
            <i class="fa-solid fa-info-circle"></i> Provide detailed reason for patient admission
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Admission Notes</label>
          <textarea name="admission_notes" class="form-control" rows="3" placeholder="Additional notes, special instructions, or important information about this admission"><?= esc(old('admission_notes', $patient['admission_notes'] ?? '')) ?></textarea>
        </div>
      </div>

      <!-- Action Buttons -->
      <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:20px;border-top:1px solid #e5e7eb">
        <a href="<?= site_url('reception/in-patients/view/' . $patient['id']) ?>" 
           style="padding:12px 24px;background:#6b7280;color:white;text-decoration:none;border-radius:6px;font-weight:600;display:inline-flex;align-items:center;gap:8px;transition:background 0.2s"
           onmouseover="this.style.background='#4b5563'" onmouseout="this.style.background='#6b7280'">
          <i class="fa-solid fa-times"></i> Cancel
        </a>
        <button type="submit" 
                style="padding:12px 24px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer;font-weight:600;display:inline-flex;align-items:center;gap:8px;transition:background 0.2s"
                onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
          <i class="fa-solid fa-save"></i> Update In-Patient
        </button>
      </div>
    </form>
  </main></div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
// Room info display
document.getElementById('roomSelect').addEventListener('change', function() {
  const selected = this.options[this.selectedIndex];
  const infoDiv = document.getElementById('roomInfo');
  const detailsDiv = document.getElementById('roomDetails');
  const bedGroup = document.getElementById('bedSelectionGroup');
  const bedSelection = document.getElementById('bedSelection');
  const bedIdField = document.getElementById('bedIdField');
  
  if (this.value && selected.dataset.roomNumber) {
    let details = '<div><strong>Room:</strong> ' + selected.dataset.roomNumber + '</div>';
    details += '<div><strong>Type:</strong> ' + selected.dataset.roomType + '</div>';
    details += '<div><strong>Floor:</strong> ' + (selected.dataset.floor || 'N/A') + '</div>';
    details += '<div><strong>Rate:</strong> ₱' + parseFloat(selected.dataset.rate).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '/day</div>';
    details += '<div><strong>Occupancy:</strong> ' + selected.dataset.occupancy + '/' + selected.dataset.capacity + '</div>';
    detailsDiv.innerHTML = details;
    infoDiv.style.display = 'block';
    
    // Load beds for this room
    loadBedsForRoom(this.value);
  } else {
    infoDiv.style.display = 'none';
    bedGroup.style.display = 'none';
    bedIdField.value = '';
  }
});

function loadBedsForRoom(roomId) {
  const bedGroup = document.getElementById('bedSelectionGroup');
  const bedSelection = document.getElementById('bedSelection');
  const bedIdField = document.getElementById('bedIdField');
  const bedError = document.getElementById('bedSelectionError');
  const bedErrorText = document.getElementById('bedErrorText');
  const currentBedId = <?= json_encode($patient['assigned_bed_id'] ?? null) ?>;
  
  if (!roomId) {
    bedGroup.style.display = 'none';
    return;
  }
  
  // Show loading state
  bedGroup.style.display = 'block';
  bedError.style.display = 'none';
  bedSelection.innerHTML = `
    <div class="no-beds-message">
      <i class="fa-solid fa-spinner fa-spin" style="font-size:1.5rem;margin-bottom:8px;display:block"></i>
      <p style="margin:0">Loading beds for this room...</p>
    </div>
  `;
  
  // Fetch beds for this room
  fetch('<?= site_url('reception/get-beds-by-room') ?>/' + roomId)
    .then(response => {
      if (!response.ok) {
        throw new Error('Failed to fetch beds');
      }
      return response.json();
    })
    .then(data => {
      bedSelection.innerHTML = '';
      bedError.style.display = 'none';
      
      if (data.success && data.beds && data.beds.length > 0) {
        const availableBeds = data.beds.filter(bed => !bed.is_occupied || bed.id == currentBedId);
        const occupiedBeds = data.beds.filter(bed => bed.is_occupied && bed.id != currentBedId);
        
        if (availableBeds.length === 0) {
          bedSelection.innerHTML = `
            <div class="no-beds-message" style="grid-column:1/-1">
              <i class="fa-solid fa-bed" style="font-size:2rem;margin-bottom:8px;display:block;opacity:0.3"></i>
              <p style="margin:0;font-weight:600;color:#dc2626">No available beds in this room</p>
            </div>
          `;
          bedError.style.display = 'block';
          bedErrorText.textContent = 'Please select a different room with available beds.';
          return;
        }
        
        // Show available beds first
        availableBeds.forEach(bed => {
          const bedOption = document.createElement('div');
          bedOption.className = 'bed-option' + (bed.id == currentBedId ? ' selected' : '');
          bedOption.innerHTML = `
            <div class="bed-number">
              <i class="fa-solid fa-bed"></i> Bed ${bed.bed_number}
            </div>
            <div class="bed-type">${bed.bed_type || 'Standard'}</div>
            <div class="bed-status available">
              <i class="fa-solid fa-check-circle"></i> Available
            </div>
          `;
          
          bedOption.addEventListener('click', function() {
            // Remove selected class from all
            document.querySelectorAll('.bed-option').forEach(opt => opt.classList.remove('selected'));
            // Add selected class to this
            this.classList.add('selected');
            bedIdField.value = bed.id;
            bedError.style.display = 'none';
            
            // Visual feedback
            this.style.transform = 'scale(1.05)';
            setTimeout(() => {
              this.style.transform = '';
            }, 200);
          });
          
          // Pre-select current bed
          if (bed.id == currentBedId) {
            bedIdField.value = bed.id;
          }
          
          bedSelection.appendChild(bedOption);
        });
        
        // Show occupied beds (grayed out) if any
        if (occupiedBeds.length > 0) {
          occupiedBeds.forEach(bed => {
            const bedOption = document.createElement('div');
            bedOption.className = 'bed-option unavailable';
            bedOption.innerHTML = `
              <div class="bed-number" style="opacity:0.6">
                <i class="fa-solid fa-bed"></i> Bed ${bed.bed_number}
              </div>
              <div class="bed-type" style="opacity:0.6">${bed.bed_type || 'Standard'}</div>
              <div class="bed-status occupied">
                <i class="fa-solid fa-times-circle"></i> Occupied
              </div>
            `;
            bedSelection.appendChild(bedOption);
          });
        }
        
        bedGroup.style.display = 'block';
      } else {
        bedSelection.innerHTML = `
          <div class="no-beds-message" style="grid-column:1/-1">
            <i class="fa-solid fa-exclamation-triangle" style="font-size:2rem;margin-bottom:8px;display:block;color:#f59e0b"></i>
            <p style="margin:0;font-weight:600">No beds found for this room</p>
          </div>
        `;
        bedError.style.display = 'block';
        bedErrorText.textContent = 'This room has no beds configured.';
      }
    })
    .catch(error => {
      console.error('Error loading beds:', error);
      bedSelection.innerHTML = `
        <div class="no-beds-message" style="grid-column:1/-1">
          <i class="fa-solid fa-exclamation-triangle" style="font-size:2rem;margin-bottom:8px;display:block;color:#dc2626"></i>
          <p style="margin:0;font-weight:600;color:#dc2626">Error loading beds</p>
        </div>
      `;
      bedError.style.display = 'block';
      bedErrorText.textContent = 'Failed to load beds. Please try again.';
    });
}

// Form validation
document.getElementById('editForm').addEventListener('submit', function(e) {
  const bedId = document.getElementById('bedIdField').value;
  const roomId = document.getElementById('roomSelect').value;
  const bedGroup = document.getElementById('bedSelectionGroup');
  
  if (roomId && bedGroup.style.display !== 'none') {
    if (!bedId) {
      e.preventDefault();
      const bedError = document.getElementById('bedSelectionError');
      const bedErrorText = document.getElementById('bedErrorText');
      bedError.style.display = 'block';
      bedErrorText.textContent = 'Please select a bed before submitting the form.';
      bedError.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      return false;
    }
  }
  
  return true;
});

// Load beds on page load if room is already selected
document.addEventListener('DOMContentLoaded', function() {
  const roomSelect = document.getElementById('roomSelect');
  if (roomSelect.value) {
    loadBedsForRoom(roomSelect.value);
  }
});
</script>
</body></html>

