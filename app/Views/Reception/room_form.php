<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($room) ? 'Edit Room' : 'Add New Room' ?></title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Receptionist</h1>
        <small>Room Management</small>
      </div>
  </div>
  <div class="top-right" aria-label="User session">
      <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Reception navigation">
    <nav class="side-nav">
  <a href="<?= site_url('dashboard/receptionist') ?>">Overview</a>
  <a href="<?= site_url('reception/patients') ?>">Patient Management</a>
  <a href="<?= site_url('reception/appointments') ?>">Appointment Management</a>
  <a href="<?= site_url('reception/rooms') ?>">Room Management</a>
  <a href="<?= site_url('reception/patient-lookup') ?>">Patient Lookup</a>
    </nav>
  </aside>

  <main class="content" style="padding:20px">
    <section class="panel" style="max-width:800px;margin:0 auto;background:white;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1)">
      <div class="panel-head" style="padding:20px;border-bottom:1px solid #e5e7eb">
        <h2 style="margin:0;font-size:1.25rem;font-weight:600;color:#1f2937"><?= isset($room) ? 'Edit Room' : 'Add New Room' ?></h2>
      </div>
      <div class="panel-body" style="padding:24px">
        <?php if (session()->getFlashdata('error')): ?>
          <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px;margin-bottom:20px">
            <p style="margin:0;color:#dc2626;font-size:0.875rem">
              <i class="fa-solid fa-exclamation-circle"></i> <?= esc(session()->getFlashdata('error')) ?>
            </p>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
          <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px;margin-bottom:20px">
            <p style="margin:0;color:#065f46;font-size:0.875rem">
              <i class="fa-solid fa-check-circle"></i> <?= esc(session()->getFlashdata('success')) ?>
            </p>
          </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('reception/rooms/save') ?>" id="roomForm" onsubmit="return validateAndSubmit()">
          <?= csrf_field() ?>
          <?php if (isset($room)): ?>
            <input type="hidden" name="id" value="<?= esc($room['id']) ?>">
          <?php endif; ?>
          
          <!-- Room Details Section -->
          <div style="margin-bottom:24px">
            <h3 style="margin:0 0 16px 0;font-size:1rem;font-weight:600;color:#374151">Room Details</h3>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;font-size:0.875rem">
                  Room Number <span style="color:#ef4444">*</span>
                </label>
                <input type="text" name="room_number" value="<?= esc(old('room_number', $room['room_number'] ?? '')) ?>" required
                  style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;transition:border-color 0.2s"
                  placeholder="e.g., 101, ICU-01"
                  onfocus="this.style.borderColor='#3b82f6'"
                  onblur="this.style.borderColor='#d1d5db'">
            </div>
            <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;font-size:0.875rem">
                  Room Type <span style="color:#ef4444">*</span>
                </label>
                <select name="room_type" required 
                  style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;background:white;transition:border-color 0.2s"
                  onfocus="this.style.borderColor='#3b82f6'"
                  onblur="this.style.borderColor='#d1d5db'">
                <option value="">Select Type</option>
                  <option value="private" <?= old('room_type', $room['room_type'] ?? '') === 'private' ? 'selected' : '' ?>>Private</option>
                  <option value="ward" <?= old('room_type', $room['room_type'] ?? '') === 'ward' ? 'selected' : '' ?>>Ward</option>
                  <option value="icu" <?= old('room_type', $room['room_type'] ?? '') === 'icu' ? 'selected' : '' ?>>ICU</option>
                  <option value="emergency" <?= old('room_type', $room['room_type'] ?? '') === 'emergency' ? 'selected' : '' ?>>Emergency</option>
                  <option value="consultation" <?= old('room_type', $room['room_type'] ?? '') === 'consultation' ? 'selected' : '' ?>>Consultation</option>
                  <option value="operating" <?= old('room_type', $room['room_type'] ?? '') === 'operating' ? 'selected' : '' ?>>Operating</option>
              </select>
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;font-size:0.875rem">
                  Floor Number <span style="color:#ef4444">*</span>
                </label>
                <input type="number" name="floor" value="<?= esc(old('floor', $room['floor'] ?? 1)) ?>" required min="1" max="20"
                  style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;transition:border-color 0.2s"
                  onfocus="this.style.borderColor='#3b82f6'"
                  onblur="this.style.borderColor='#d1d5db'">
              </div>
              <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;font-size:0.875rem">
                  Capacity <span style="color:#ef4444">*</span>
                </label>
                <input type="number" name="capacity" value="<?= esc(old('capacity', $room['capacity'] ?? 1)) ?>" required min="1" max="10"
                  style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;transition:border-color 0.2s"
                  onfocus="this.style.borderColor='#3b82f6'"
                  onblur="this.style.borderColor='#d1d5db'">
            </div>
            <div>
                <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;font-size:0.875rem">
                  Rate per Day (₱)
                </label>
                <input type="number" name="rate_per_day" value="<?= esc(old('rate_per_day', $room['rate_per_day'] ?? 0)) ?>" min="0" step="0.01"
                  style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;transition:border-color 0.2s"
                  placeholder="0.00"
                  onfocus="this.style.borderColor='#3b82f6'"
                  onblur="this.style.borderColor='#d1d5db'">
              </div>
            </div>

            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151;font-size:0.875rem">Status</label>
              <select name="status" 
                style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;background:white;transition:border-color 0.2s"
                onfocus="this.style.borderColor='#3b82f6'"
                onblur="this.style.borderColor='#d1d5db'">
                <option value="available" <?= old('status', $room['status'] ?? 'available') === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="occupied" <?= old('status', $room['status'] ?? '') === 'occupied' ? 'selected' : '' ?>>Occupied</option>
                <option value="maintenance" <?= old('status', $room['status'] ?? '') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                <option value="reserved" <?= old('status', $room['status'] ?? '') === 'reserved' ? 'selected' : '' ?>>Reserved</option>
              </select>
            </div>
          </div>

          <!-- Beds Management Section -->
          <div style="margin-bottom:24px;padding:20px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px">
            <h3 style="margin:0 0 12px 0;font-size:1rem;font-weight:600;color:#1f2937;display:flex;align-items:center;gap:8px">
              <i class="fa-solid fa-bed" style="color:#3b82f6"></i>Beds Management
            </h3>
            
            <?php if (isset($room) && $room['id']): ?>
              <!-- Existing Room - Show current beds -->
              <div id="beds-list" style="margin-bottom:16px">
                <?php
                $bedModel = model('App\\Models\\BedModel');
                $beds = $bedModel->getBedsByRoom($room['id']);
                if (!empty($beds)):
                ?>
                  <table style="width:100%;border-collapse:collapse;margin-bottom:12px;background:white;border-radius:6px;overflow:hidden">
                    <thead>
                      <tr style="background:#f3f4f6">
                        <th style="padding:10px;text-align:left;font-size:0.875rem;font-weight:600;color:#374151">Bed Number</th>
                        <th style="padding:10px;text-align:left;font-size:0.875rem;font-weight:600;color:#374151">Type</th>
                        <th style="padding:10px;text-align:left;font-size:0.875rem;font-weight:600;color:#374151">Status</th>
                        <th style="padding:10px;text-align:left;font-size:0.875rem;font-weight:600;color:#374151">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($beds as $bed): ?>
                        <tr style="border-bottom:1px solid #e5e7eb">
                          <td style="padding:10px;font-size:0.875rem;color:#1f2937"><?= esc($bed['bed_number']) ?></td>
                          <td style="padding:10px;font-size:0.875rem;color:#6b7280"><?= esc(ucfirst($bed['bed_type'])) ?></td>
                          <td style="padding:10px">
                            <span style="padding:4px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;
                              <?php if ($bed['status'] === 'available'): ?>
                                background:#d1fae5;color:#065f46
                              <?php elseif ($bed['status'] === 'occupied'): ?>
                                background:#fee2e2;color:#991b1b
                              <?php else: ?>
                                background:#fef3c7;color:#92400e
                              <?php endif; ?>">
                              <?= esc(ucfirst($bed['status'])) ?>
                            </span>
                          </td>
                          <td style="padding:10px">
                            <a href="<?= site_url('reception/beds/delete/' . $bed['id']) ?>" 
                               onclick="return confirm('Are you sure you want to delete this bed?')"
                               style="color:#ef4444;text-decoration:none;font-size:0.875rem;display:inline-flex;align-items:center;gap:4px">
                              <i class="fa-solid fa-trash"></i> Delete
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                <?php else: ?>
                  <p style="margin:0 0 12px 0;color:#6b7280;font-size:0.875rem">No beds added yet. Add beds below.</p>
                <?php endif; ?>
              </div>
              
              <!-- Add Bed Form for Existing Room -->
              <div style="padding-top:16px;border-top:1px solid #e5e7eb">
                <h4 style="margin:0 0 12px 0;font-size:0.875rem;font-weight:600;color:#374151">Add New Bed</h4>
                <form method="post" action="<?= site_url('reception/beds/add') ?>" style="display:grid;grid-template-columns:2fr 1fr auto;gap:12px;align-items:end">
                  <?= csrf_field() ?>
                  <input type="hidden" name="room_id" value="<?= esc($room['id']) ?>">
                  <div>
                    <label style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#6b7280">Bed Number</label>
                    <input type="text" name="bed_number" required 
                           style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem"
                           placeholder="e.g., Bed 1, A1">
                  </div>
                  <div>
                    <label style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#6b7280">Bed Type</label>
                    <select name="bed_type" required style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;background:white">
                      <option value="standard">Standard</option>
                      <option value="electric">Electric</option>
                      <option value="icu">ICU</option>
                      <option value="pediatric">Pediatric</option>
                    </select>
                  </div>
                  <button type="submit" style="padding:8px 16px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer;font-size:0.875rem;font-weight:500;display:inline-flex;align-items:center;gap:6px;transition:background 0.2s" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                    <i class="fa-solid fa-plus"></i> Add
                  </button>
                </form>
              </div>
              <?php else: ?>
              <!-- New Room - Show beds to be added -->
              <div id="beds-to-add" style="margin-bottom:16px">
                <p style="margin:0 0 12px 0;color:#6b7280;font-size:0.875rem">Add beds for this room. Beds will be created after the room is saved.</p>
                <div id="beds-list-new" style="max-height:200px;overflow-y:auto;margin-bottom:12px;background:white;border-radius:6px;padding:12px;border:1px solid #e5e7eb">
                  <p style="margin:0;color:#9ca3af;font-size:0.875rem;font-style:italic;text-align:center;padding:20px">No beds added yet</p>
                </div>
              </div>
              
              <!-- Add Bed Form for New Room -->
              <div style="padding-top:16px;border-top:1px solid #e5e7eb">
                <h4 style="margin:0 0 12px 0;font-size:0.875rem;font-weight:600;color:#374151">Add New Bed</h4>
                <div style="display:grid;grid-template-columns:2fr 1fr auto;gap:12px;align-items:end">
                  <div>
                    <label style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#6b7280">Bed Number</label>
                    <input type="text" id="new_bed_number" 
                           style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem"
                           placeholder="e.g., Bed 1, A1">
                  </div>
                  <div>
                    <label style="display:block;margin-bottom:4px;font-size:0.75rem;font-weight:600;color:#6b7280">Bed Type</label>
                    <select id="new_bed_type" style="width:100%;padding:8px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;background:white">
                      <option value="standard">Standard</option>
                      <option value="electric">Electric</option>
                      <option value="icu">ICU</option>
                      <option value="pediatric">Pediatric</option>
                    </select>
                  </div>
                  <button type="button" onclick="addBedToList()" style="padding:8px 16px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer;font-size:0.875rem;font-weight:500;display:inline-flex;align-items:center;gap:6px;transition:background 0.2s" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                    <i class="fa-solid fa-plus"></i> Add
                  </button>
                </div>
                <!-- Hidden input to store beds data -->
                <input type="hidden" name="beds_data" id="beds_data" value="">
              </div>
              <?php endif; ?>
          </div>

          <!-- Action Buttons -->
          <div style="display:flex;gap:12px;justify-content:flex-end;padding-top:20px;border-top:1px solid #e5e7eb">
            <a href="<?= site_url('reception/rooms') ?>" 
               style="padding:10px 20px;background:#6b7280;color:white;text-decoration:none;border-radius:6px;font-weight:500;display:inline-flex;align-items:center;gap:8px;transition:background 0.2s"
               onmouseover="this.style.background='#4b5563'" onmouseout="this.style.background='#6b7280'">
              <i class="fa-solid fa-times"></i> Cancel
            </a>
            <button type="submit" 
                    style="padding:10px 20px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer;font-weight:600;display:inline-flex;align-items:center;gap:8px;transition:background 0.2s"
                    onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
              <i class="fa-solid fa-bed-pulse"></i> <?= isset($room) ? 'Update Room' : 'Add Room' ?>
            </button>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<?php if (!isset($room) || !$room['id']): ?>
<script>
let bedsList = [];

function addBedToList() {
  const bedNumber = document.getElementById('new_bed_number').value.trim();
  const bedType = document.getElementById('new_bed_type').value;
  
  if (!bedNumber) {
    alert('Please enter a bed number');
    return;
  }
  
  // Check if bed number already exists
  if (bedsList.some(bed => bed.bed_number.toLowerCase() === bedNumber.toLowerCase())) {
    alert('Bed number already added');
    return;
  }
  
  // Add to list
  bedsList.push({
    bed_number: bedNumber,
    bed_type: bedType
  });
  
  // Update display
  updateBedsDisplay();
  
  // Clear inputs
  document.getElementById('new_bed_number').value = '';
  document.getElementById('new_bed_type').value = 'standard';
  
  // Update hidden input
  document.getElementById('beds_data').value = JSON.stringify(bedsList);
}

function removeBedFromList(index) {
  bedsList.splice(index, 1);
  updateBedsDisplay();
  document.getElementById('beds_data').value = JSON.stringify(bedsList);
}

function updateBedsDisplay() {
  const container = document.getElementById('beds-list-new');
  
  if (bedsList.length === 0) {
    container.innerHTML = '<p style="margin:0;color:#9ca3af;font-size:0.875rem;font-style:italic;text-align:center;padding:20px">No beds added yet</p>';
    return;
  }
  
  let html = '<table style="width:100%;border-collapse:collapse;font-size:0.875rem">';
  html += '<thead><tr style="background:#f3f4f6"><th style="padding:10px;text-align:left;font-weight:600;color:#374151">Bed Number</th><th style="padding:10px;text-align:left;font-weight:600;color:#374151">Type</th><th style="padding:10px;text-align:left;font-weight:600;color:#374151">Action</th></tr></thead>';
  html += '<tbody>';
  
  bedsList.forEach((bed, index) => {
    html += '<tr style="border-bottom:1px solid #e5e7eb">';
    html += '<td style="padding:10px;color:#1f2937">' + bed.bed_number + '</td>';
    html += '<td style="padding:10px;color:#6b7280">' + bed.bed_type.charAt(0).toUpperCase() + bed.bed_type.slice(1) + '</td>';
    html += '<td style="padding:10px"><button type="button" onclick="removeBedFromList(' + index + ')" style="background:#ef4444;color:white;border:none;padding:4px 10px;border-radius:4px;cursor:pointer;font-size:0.75rem;display:inline-flex;align-items:center;gap:4px" onmouseover="this.style.background=\'#dc2626\'" onmouseout="this.style.background=\'#ef4444\'"><i class="fa-solid fa-trash"></i> Remove</button></td>';
    html += '</tr>';
  });
  
  html += '</tbody></table>';
  container.innerHTML = html;
}

// Initialize display
updateBedsDisplay();

// Validate and submit form
function validateAndSubmit() {
  // Ensure beds_data is set before submit
  const bedsDataInput = document.getElementById('beds_data');
  if (bedsDataInput) {
    bedsDataInput.value = JSON.stringify(bedsList);
  }
  
  // Validate required fields
  const roomNumber = document.querySelector('input[name="room_number"]').value.trim();
  const roomType = document.querySelector('select[name="room_type"]').value;
  const floor = document.querySelector('input[name="floor"]').value;
  const capacity = document.querySelector('input[name="capacity"]').value;
  
  if (!roomNumber || !roomType || !floor || !capacity) {
    alert('Please fill all required fields (*)');
    return false;
  }
  
  if (parseInt(floor) < 1 || parseInt(capacity) < 1) {
    alert('Floor and Capacity must be at least 1');
    return false;
  }
  
  return true;
}
</script>
<?php endif; ?>
</body>
</html>
