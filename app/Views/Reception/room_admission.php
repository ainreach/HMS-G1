<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Room Admission</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Room Admission</small></div>
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
  <a href="<?= site_url('reception/appointments') ?>">Appointment Management</a>
  <a href="<?= site_url('reception/rooms') ?>">Room Management</a>
  <a href="<?= site_url('reception/patient-lookup') ?>">Patient Lookup</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Admit Patient to Room</h2>
      </div>
      <div class="panel-body">
        <?php if (session()->getFlashdata('error')): ?>
          <div style="background:#fef2f2;border:1px solid #ef4444;border-radius:8px;padding:12px;margin-bottom:16px">
            <p style="margin:0;color:#dc2626"><?= esc(session()->getFlashdata('error')) ?></p>
          </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('reception/rooms/admit') ?>" style="max-width:600px">
          <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:4px;font-weight:600">Select Patient *</label>
            <select name="patient_id" required style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px">
              <option value="">Choose a patient...</option>
              <?php if (!empty($patients)) : foreach ($patients as $patient) : ?>
                <option value="<?= esc($patient['id']) ?>" 
                  <?= (old('patient_id') == $patient['id']) ? 'selected' : '' ?>>
                  <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?> 
                  (<?= esc($patient['patient_id']) ?>)
                </option>
              <?php endforeach; else: ?>
                <option value="">No patients available</option>
              <?php endif; ?>
            </select>
          </div>

          <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:4px;font-weight:600">Select Room *</label>
            <select name="room_id" required style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px">
              <option value="">Choose an available room...</option>
              <?php if (!empty($availableRooms)) : foreach ($availableRooms as $room) : ?>
                <option value="<?= esc($room['id']) ?>" 
                  <?= (old('room_id') == $room['id']) ? 'selected' : '' ?>>
                  <?= esc($room['room_number']) ?> - <?= esc(ucfirst($room['room_type'])) ?>
                  (Floor <?= esc($room['floor']) ?>) - 
                  <?= esc($room['current_occupancy']) ?>/<?= esc($room['capacity']) ?> occupied
                  - ₱<?= number_format($room['rate_per_day'], 2) ?>/day
                </option>
              <?php endforeach; else: ?>
                <option value="">No available rooms</option>
              <?php endif; ?>
            </select>
            
            <!-- Debug Information -->
            <?php if (isset($allRooms) && !empty($allRooms)): ?>
              <div style="margin-top:8px;padding:8px;background:#f3f4f6;border-radius:4px;font-size:0.8rem">
                <strong>Debug Info:</strong><br>
                Total Rooms: <?= count($allRooms) ?><br>
                Available Rooms: <?= count($availableRooms) ?><br>
                <?php foreach ($allRooms as $room): ?>
                  Room <?= esc($room['room_number']) ?>: 
                  Status=<?= esc($room['status']) ?>, 
                  Occupancy=<?= esc($room['current_occupancy']) ?>/<?= esc($room['capacity']) ?>, 
                  Branch=<?= esc($room['branch_id']) ?><br>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>

          <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:4px;font-weight:600">Admission Date</label>
            <input type="date" name="admission_date" value="<?= old('admission_date') ?: date('Y-m-d') ?>" 
              style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px">
          </div>

          <div style="display:flex;gap:12px">
            <button type="submit" style="padding:10px 20px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer">
              <i class="fa-solid fa-bed"></i> Admit Patient
            </button>
            <a href="<?= site_url('reception/rooms') ?>" style="padding:10px 20px;background:#6b7280;color:white;text-decoration:none;border-radius:6px">
              <i class="fa-solid fa-times"></i> Cancel
            </a>
          </div>
        </form>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Room Admission Process</h2>
      </div>
      <div class="panel-body">
        <h3 style="font-size:1rem;color:#1e6f9f;margin-bottom:12px">How Patients Can Avail Rooms:</h3>
        
        <div style="background:#f0f9ff;border:1px solid #0ea5e9;border-radius:8px;padding:16px;margin-bottom:16px">
          <h4 style="margin:0 0 8px;color:#0c4a6e"><i class="fa-solid fa-1"></i> Patient Registration</h4>
          <p style="margin:0">Patient must be registered in the hospital system first.</p>
        </div>

        <div style="background:#f0f9ff;border:1px solid #0ea5e9;border-radius:8px;padding:16px;margin-bottom:16px">
          <h4 style="margin:0 0 8px;color:#0c4a6e"><i class="fa-solid fa-2"></i> Doctor Recommendation</h4>
          <p style="margin:0">Doctor must recommend admission and specify room type needed.</p>
        </div>

        <div style="background:#f0f9ff;border:1px solid #0ea5e9;border-radius:8px;padding:16px;margin-bottom:16px">
          <h4 style="margin:0 0 8px;color:#0c4a6e"><i class="fa-solid fa-3"></i> Room Assignment</h4>
          <p style="margin:0">Receptionist assigns available room based on patient needs and room availability.</p>
        </div>

        <div style="background:#f0f9ff;border:1px solid #0ea5e9;border-radius:8px;padding:16px;margin-bottom:16px">
          <h4 style="margin:0 0 8px;color:#0c4a6e"><i class="fa-solid fa-4"></i> Payment Processing</h4>
          <p style="margin:0">Initial room charges and deposits are processed at billing.</p>
        </div>

        <div style="background:#f0f9ff;border:1px solid #0ea5e9;border-radius:8px;padding:16px">
          <h4 style="margin:0 0 8px;color:#0c4a6e"><i class="fa-solid fa-5"></i> Room Occupancy</h4>
          <p style="margin:0">Patient is officially admitted and can proceed to the assigned room.</p>
        </div>

        <div style="margin-top:20px;padding:12px;background:#fef3c7;border:1px solid #d97706;border-radius:6px">
          <p style="margin:0;font-weight:600;color:#92400e">
            <i class="fa-solid fa-info-circle"></i> <strong>Note:</strong> Use this form to admit patients to available rooms. Only rooms with available capacity will be shown.
          </p>
        </div>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
