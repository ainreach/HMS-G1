<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Room Management</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
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
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Room List</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
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
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucfirst($room['room_type'] ?? '')) ?></td>
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
                  <button class="btn" onclick="viewRoomDetails(<?= $room['id'] ?? 0 ?>)" style="padding:4px 8px;border:1px solid #e5e7eb;border-radius:4px;text-decoration:none;background-color:#f9fafb">
                    <i class="fa-solid fa-eye"></i> View
                  </button>
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