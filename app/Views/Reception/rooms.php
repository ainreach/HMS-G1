<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Room Management</title>
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
  <a href="<?= site_url('reception/rooms') ?>" class="active" aria-current="page">Room Management</a>
  <a href="<?= site_url('reception/patient-lookup') ?>">Patient Lookup</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Total Rooms</span><i class="fa-solid fa-door-open" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($stats['total'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Available</span><i class="fa-solid fa-check-circle" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($stats['available'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>Occupied</span><i class="fa-solid fa-bed" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($stats['occupied'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Occupancy Rate</span><i class="fa-solid fa-chart-pie" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($stats['occupancy_rate'] ?? 0) ?>%</div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Patient Room Lookup</h2></div>
      <div class="panel-body">
        <form method="get" action="<?= site_url('reception/find-patient-room') ?>" style="display:flex;gap:12px;align-items:center;margin-bottom:16px">
          <input type="text" name="search" placeholder="Enter patient name or ID..." style="flex:1;padding:8px;border:1px solid #e5e7eb;border-radius:6px" value="<?= esc($_GET['search'] ?? '') ?>">
          <button type="submit" style="padding:8px 16px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer">
            <i class="fa-solid fa-search"></i> Find Patient
          </button>
        </form>
        
        <?php if (isset($patientRoom)): ?>
          <div style="background:#e0f2fe;border:1px solid #0ea5e9;border-radius:8px;padding:12px;margin-bottom:16px">
            <h4 style="margin:0 0 8px;color:#0c4a6e">Patient Found!</h4>
            <p style="margin:4px 0"><strong>Name:</strong> <?= esc($patientRoom['patient_name'] ?? '') ?></p>
            <p style="margin:4px 0"><strong>Room:</strong> <?= esc($patientRoom['room_number'] ?? '') ?> (<?= esc($patientRoom['room_type'] ?? '') ?>)</p>
            <p style="margin:4px 0"><strong>Floor:</strong> <?= esc($patientRoom['floor'] ?? '') ?></p>
            <p style="margin:4px 0"><strong>Admitted:</strong> <?= esc($patientRoom['admission_date'] ?? '') ?></p>
          </div>
        <?php elseif (isset($notFound)): ?>
          <div style="background:#fef2f2;border:1px solid #ef4444;border-radius:8px;padding:12px;margin-bottom:16px">
            <p style="margin:0;color:#dc2626">Patient not found or not currently admitted to any room.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Room List</h2>
        <div>
          <a href="<?= site_url('reception/rooms/admit') ?>" class="btn" style="padding:8px 12px;background:#10b981;color:white;border:none;border-radius:6px;text-decoration:none;margin-right:8px">
            <i class="fa-solid fa-bed"></i> Admit Patient
          </a>
          <a href="<?= site_url('reception/rooms/new') ?>" class="btn" style="padding:8px 12px;background:#3b82f6;color:white;border:none;border-radius:6px;text-decoration:none">
            <i class="fa-solid fa-plus"></i> Add Room
          </a>
        </div>
      </div>
      <div class="panel-body">
        <?php if (session()->getFlashdata('success')): ?>
          <div style="background:#dcfce7;border:1px solid #16a34a;border-radius:8px;padding:12px;margin-bottom:16px">
            <p style="margin:0;color:#16a34a"><?= esc(session()->getFlashdata('success')) ?></p>
          </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
          <div style="background:#fef2f2;border:1px solid #ef4444;border-radius:8px;padding:12px;margin-bottom:16px">
            <p style="margin:0;color:#dc2626"><?= esc(session()->getFlashdata('error')) ?></p>
          </div>
        <?php endif; ?>
        <div style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Room Number</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Type</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Floor</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Capacity</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Current Occupancy</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Rate/Day</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($rooms)) : foreach ($rooms as $room) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($room['room_number'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucwords(str_replace('_', ' ', $room['room_type'] ?? ''))) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($room['floor'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($room['capacity'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <span style="padding:2px 8px;border-radius:12px;font-size:0.875rem;<?= ($room['current_occupancy'] ?? 0) >= ($room['capacity'] ?? 1) ? 'background-color:#fee2e2;color:#dc2626' : 'background-color:#dbeafe;color:#2563eb' ?>">
                    <?= ($room['current_occupancy'] ?? 0) ?>/<?= ($room['capacity'] ?? 0) ?>
                  </span>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <span style="padding:2px 8px;border-radius:12px;font-size:0.875rem;<?= ($room['status'] ?? '') === 'available' ? 'background-color:#dcfce7;color:#16a34a' : (($room['status'] ?? '') === 'occupied' ? 'background-color:#fef3c7;color:#d97706' : 'background-color:#f3f4f6;color:#6b7280') ?>">
                    <?= esc(ucfirst($room['status'] ?? 'N/A')) ?>
                  </span>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">₱<?= number_format($room['rate_per_day'] ?? 0, 2) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <div style="display:flex;gap:6px">
                    <a href="<?= site_url('reception/rooms/edit/' . $room['id']) ?>" style="padding:4px 8px;background:#3b82f6;color:white;border-radius:4px;text-decoration:none;font-size:0.75rem">
                      <i class="fa-solid fa-edit"></i> Edit
                    </a>
                    <a href="<?= site_url('reception/rooms/delete/' . $room['id']) ?>" style="padding:4px 8px;background:#ef4444;color:white;border-radius:4px;text-decoration:none;font-size:0.75rem" onclick="return confirm('Are you sure you want to delete this room?')">
                      <i class="fa-solid fa-trash"></i> Delete
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="8" style="padding:10px;color:#6b7280;text-align:center">No rooms found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script>
function viewRoomDetails(roomId) {
  alert('Room details for room ID: ' + roomId);
}
</script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>