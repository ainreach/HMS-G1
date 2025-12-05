<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Doctor Orders - Doctor</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <div class="brand">
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0;color:#1f2937">
          <i class="fa-solid fa-prescription" style="color:#16a34a;margin-right:8px"></i>Doctor Orders
        </h1>
        <small style="color:#6b7280">Medical orders and prescriptions</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
      <a href="<?= site_url('dashboard/doctor') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Doctor navigation" style="background:#ffffff;border-right:1px solid #e5e7eb;padding:0">
    <div style="padding:20px;border-bottom:1px solid #e5e7eb">
      <h2 style="margin:0;font-size:1rem;font-weight:700;color:#1f2937;text-transform:uppercase;letter-spacing:0.5px">DOCTOR PANEL</h2>
    </div>
    <nav class="side-nav" style="padding:10px 0">
      <a href="<?= site_url('dashboard/doctor') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-gauge" style="width:20px;margin-right:12px;font-size:1rem"></i>Dashboard
      </a>
      <a href="<?= site_url('doctor/patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-users" style="width:20px;margin-right:12px;font-size:1rem"></i>Patient List
      </a>
      <a href="<?= site_url('doctor/upcoming-consultations') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-calendar-check" style="width:20px;margin-right:12px;font-size:1rem"></i>Upcoming Consultations
      </a>
      <a href="<?= site_url('doctor/schedule') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-calendar" style="width:20px;margin-right:12px;font-size:1rem"></i>My Schedule
      </a>
      <a href="<?= site_url('doctor/lab-requests-nurses') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-vial" style="width:20px;margin-right:12px;font-size:1rem"></i>Lab Requests
      </a>
      <a href="<?= site_url('doctor/orders') ?>" class="active" aria-current="page" style="display:flex;align-items:center;padding:12px 20px;color:#1f2937;text-decoration:none;font-weight:500;position:relative;background:#f0f9ff;border-left:4px solid #3b82f6">
        <i class="fa-solid fa-prescription" style="width:20px;margin-right:12px;font-size:1rem"></i>Doctor Orders
      </a>
      <a href="<?= site_url('doctor/admit-patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-users" style="width:20px;margin-right:12px;font-size:1rem"></i>Admitted Patients
      </a>
      <a href="<?= site_url('doctor/discharge-patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-sign-out-alt" style="width:20px;margin-right:12px;font-size:1rem"></i>Discharge Patients
      </a>
    </nav>
  </aside>

  <main class="content" style="padding:16px">
    <?php if (session()->getFlashdata('success')): ?>
      <div style="padding:12px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;margin-bottom:16px;color:#065f46">
        <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Doctor Orders</h2>
        <a href="<?= site_url('doctor/patients') ?>" style="padding:8px 16px;background:#16a34a;color:white;border-radius:6px;text-decoration:none;font-size:0.875rem;border:1px solid #15803d">
          <i class="fa-solid fa-plus"></i> Create New Order
        </a>
      </div>
      
      <!-- Status Tabs -->
      <div style="border-bottom:1px solid #e5e7eb;display:flex;gap:8px;padding:0 16px">
        <a href="<?= site_url('doctor/orders?status=all') ?>" 
           style="padding:12px 16px;text-decoration:none;color:<?= $currentStatus === 'all' ? '#16a34a' : '#6b7280' ?>;border-bottom:2px solid <?= $currentStatus === 'all' ? '#16a34a' : 'transparent' ?>;font-weight:<?= $currentStatus === 'all' ? '600' : '400' ?>">
          <i class="fa-solid fa-list"></i> All Orders (<?= $counts['all'] ?>)
        </a>
        <a href="<?= site_url('doctor/orders?status=pending') ?>" 
           style="padding:12px 16px;text-decoration:none;color:<?= $currentStatus === 'pending' ? '#16a34a' : '#6b7280' ?>;border-bottom:2px solid <?= $currentStatus === 'pending' ? '#16a34a' : 'transparent' ?>;font-weight:<?= $currentStatus === 'pending' ? '600' : '400' ?>">
          <i class="fa-solid fa-clock"></i> Pending (<?= $counts['pending'] ?>)
        </a>
        <a href="<?= site_url('doctor/orders?status=in_progress') ?>" 
           style="padding:12px 16px;text-decoration:none;color:<?= $currentStatus === 'in_progress' ? '#16a34a' : '#6b7280' ?>;border-bottom:2px solid <?= $currentStatus === 'in_progress' ? '#16a34a' : 'transparent' ?>;font-weight:<?= $currentStatus === 'in_progress' ? '600' : '400' ?>">
          <i class="fa-solid fa-spinner"></i> In Progress (<?= $counts['in_progress'] ?>)
        </a>
        <a href="<?= site_url('doctor/orders?status=completed') ?>" 
           style="padding:12px 16px;text-decoration:none;color:<?= $currentStatus === 'completed' ? '#16a34a' : '#6b7280' ?>;border-bottom:2px solid <?= $currentStatus === 'completed' ? '#16a34a' : 'transparent' ?>;font-weight:<?= $currentStatus === 'completed' ? '600' : '400' ?>">
          <i class="fa-solid fa-check"></i> Completed (<?= $counts['completed'] ?>)
        </a>
        <a href="<?= site_url('doctor/orders?status=cancelled') ?>" 
           style="padding:12px 16px;text-decoration:none;color:<?= $currentStatus === 'cancelled' ? '#16a34a' : '#6b7280' ?>;border-bottom:2px solid <?= $currentStatus === 'cancelled' ? '#16a34a' : 'transparent' ?>;font-weight:<?= $currentStatus === 'cancelled' ? '600' : '400' ?>">
          <i class="fa-solid fa-x"></i> Cancelled (<?= $counts['cancelled'] ?>)
        </a>
      </div>

      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($orders)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Order ID</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Patient</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Medication</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Date</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): 
                $statusColor = '#6b7280';
                if ($order['status'] === 'completed' || $order['status'] === 'dispensed') $statusColor = '#16a34a';
                elseif ($order['status'] === 'pending') $statusColor = '#f59e0b';
                elseif ($order['status'] === 'cancelled') $statusColor = '#ef4444';
              ?>
                <tr style="border-bottom:1px solid #f3f4f6">
                  <td style="padding:10px"><?= esc($order['id']) ?></td>
                  <td style="padding:10px">
                    <div style="font-weight:600"><?= esc($order['patient_name']) ?></div>
                    <div style="color:#6b7280;font-size:0.875rem"><?= esc($order['patient_code']) ?></div>
                  </td>
                  <td style="padding:10px"><?= esc($order['medication']) ?></td>
                  <td style="padding:10px"><?= esc(date('M d, Y', strtotime($order['date']))) ?></td>
                  <td style="padding:10px">
                    <span style="padding:4px 8px;border-radius:4px;font-size:0.85rem;background:<?= $statusColor ?>1A;color:<?= $statusColor ?>">
                      <?= esc(ucfirst(str_replace('_', ' ', $order['status']))) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div style="padding:60px 20px;text-align:center">
            <i class="fa-solid fa-prescription" style="font-size:4rem;color:#d1d5db;margin-bottom:16px"></i>
            <h3 style="margin:0 0 8px 0;color:#374151;font-size:1.25rem">No Orders Found</h3>
            <p style="margin:0;color:#6b7280">You haven't created any medical orders yet.</p>
            <a href="<?= site_url('doctor/patients') ?>" style="display:inline-block;margin-top:20px;padding:12px 24px;background:#16a34a;color:white;border-radius:6px;text-decoration:none;font-weight:600">
              <i class="fa-solid fa-plus"></i> Create First Order
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>

