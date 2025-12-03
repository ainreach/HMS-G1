<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($room) ? 'Edit Room' : 'Add New Room' ?></title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Room Management</small></div>
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
        <h2 style="margin:0;font-size:1.1rem"><?= isset($room) ? 'Edit Room' : 'Add New Room' ?></h2>
      </div>
      <div class="panel-body">
        <?php if (session()->getFlashdata('error')): ?>
          <div style="background:#fef2f2;border:1px solid #ef4444;border-radius:8px;padding:12px;margin-bottom:16px">
            <p style="margin:0;color:#dc2626"><?= esc(session()->getFlashdata('error')) ?></p>
          </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('reception/rooms/save') ?>" style="max-width:600px">
          <?php if (isset($room)): ?>
            <input type="hidden" name="id" value="<?= esc($room['id']) ?>">
          <?php endif; ?>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="display:block;margin-bottom:4px;font-weight:600">Room Number *</label>
              <input type="text" name="room_number" value="<?= esc($room['room_number'] ?? '') ?>" required
                style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px"
                placeholder="e.g., 101, ICU-01">
            </div>
            <div>
              <label style="display:block;margin-bottom:4px;font-weight:600">Room Type *</label>
              <select name="room_type" required style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px">
                <option value="">Select Type</option>
                <option value="private" <?= (isset($room) && $room['room_type'] === 'private') ? 'selected' : '' ?>>Private</option>
                <option value="ward" <?= (isset($room) && $room['room_type'] === 'ward') ? 'selected' : '' ?>>Ward</option>
                <option value="icu" <?= (isset($room) && $room['room_type'] === 'icu') ? 'selected' : '' ?>>ICU</option>
                <option value="emergency" <?= (isset($room) && $room['room_type'] === 'emergency') ? 'selected' : '' ?>>Emergency</option>
                <option value="consultation" <?= (isset($room) && $room['room_type'] === 'consultation') ? 'selected' : '' ?>>Consultation</option>
                <option value="operating" <?= (isset($room) && $room['room_type'] === 'operating') ? 'selected' : '' ?>>Operating</option>
              </select>
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="display:block;margin-bottom:4px;font-weight:600">Floor Number *</label>
              <input type="number" name="floor" value="<?= esc($room['floor'] ?? 1) ?>" required min="1" max="20"
                style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px">
            </div>
            <div>
              <label style="display:block;margin-bottom:4px;font-weight:600">Capacity *</label>
              <input type="number" name="capacity" value="<?= esc($room['capacity'] ?? 1) ?>" required min="1" max="10"
                style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px">
            </div>
            <div>
              <label style="display:block;margin-bottom:4px;font-weight:600">Rate per Day (₱)</label>
              <input type="number" name="rate_per_day" value="<?= esc($room['rate_per_day'] ?? 0) ?>" min="0" step="0.01"
                style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px"
                placeholder="0.00">
            </div>
          </div>

          <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:4px;font-weight:600">Status</label>
            <select name="status" style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:6px">
              <option value="available" <?= (isset($room) && $room['status'] === 'available') ? 'selected' : '' ?>>Available</option>
              <option value="occupied" <?= (isset($room) && $room['status'] === 'occupied') ? 'selected' : '' ?>>Occupied</option>
              <option value="maintenance" <?= (isset($room) && $room['status'] === 'maintenance') ? 'selected' : '' ?>>Maintenance</option>
              <option value="reserved" <?= (isset($room) && $room['status'] === 'reserved') ? 'selected' : '' ?>>Reserved</option>
            </select>
          </div>

          <div style="display:flex;gap:12px">
            <button type="submit" style="padding:10px 20px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer">
              <i class="fa-solid fa-save"></i> <?= isset($room) ? 'Update Room' : 'Add Room' ?>
            </button>
            <a href="<?= site_url('reception/rooms') ?>" style="padding:10px 20px;background:#6b7280;color:white;text-decoration:none;border-radius:6px">
              <i class="fa-solid fa-times"></i> Cancel
            </a>
          </div>
        </form>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
