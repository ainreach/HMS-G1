<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Receptionist Dashboard | HMS</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .nav-section { margin-bottom: 1.5rem; }
    .nav-section-title { 
      font-size: 0.75rem; 
      font-weight: 600; 
      color: #6b7280; 
      text-transform: uppercase; 
      letter-spacing: 0.05em; 
      margin: 0 0 0.5rem 0; 
      padding: 0 1rem; 
    }
    .side-nav a { 
      display: flex; 
      align-items: center; 
      padding: 0.75rem 1rem; 
      color: #6b7280; 
      text-decoration: none; 
      transition: all 0.2s; 
    }
    .side-nav a:hover { 
      background-color: #f3f4f6; 
      color: #111827; 
    }
    .side-nav a.active { 
      background-color: #dbeafe; 
      color: #1e40af; 
      font-weight: 500; 
    }
    .side-nav a i { 
      margin-right: 0.5rem; 
      width: 1rem; 
      text-align: center; 
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Dashboard Overview</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Reception navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/receptionist') ?>" class="<?= (current_url() == site_url('dashboard/receptionist')) ? 'active' : '' ?>" aria-current="page">
        <i class="fas fa-tachometer-alt"></i> Overview
      </a>
      
      <!-- Patient Management -->
      <div class="nav-section">
        <h4 class="nav-section-title">Patient Management</h4>
        <a href="<?= site_url('reception/patients') ?>" class="<?= (strpos(current_url(), 'reception/patients') !== false && strpos(current_url(), 'patient-lookup') === false) ? 'active' : '' ?>">
          <i class="fas fa-user-injured"></i> Patients
        </a>
        <a href="<?= site_url('reception/patient-lookup') ?>" class="<?= (strpos(current_url(), 'reception/patient-lookup') !== false) ? 'active' : '' ?>">
          <i class="fas fa-search"></i> Patient Lookup
        </a>
      </div>
      
      <!-- Appointment Management -->
      <div class="nav-section">
        <h4 class="nav-section-title">Appointment Management</h4>
        <a href="<?= site_url('reception/appointments') ?>" class="<?= (strpos(current_url(), 'reception/appointments') !== false) ? 'active' : '' ?>">
          <i class="fas fa-calendar-check"></i> Appointments
        </a>
      </div>
      
      <!-- Room Management -->
      <div class="nav-section">
        <h4 class="nav-section-title">Room Management</h4>
        <a href="<?= site_url('reception/rooms') ?>" class="<?= (strpos(current_url(), 'reception/rooms') !== false || strpos(current_url(), 'reception/in-patients') !== false) ? 'active' : '' ?>">
          <i class="fas fa-bed"></i> Rooms
        </a>
      </div>
    </nav>
  </aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Check-ins Today</span><i class="fa-solid fa-user-check" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($checkinsToday ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Upcoming Appointments</span><i class="fa-solid fa-calendar" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($upcomingAppts ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Total Patients</span><i class="fa-solid fa-users" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($totalPatients ?? 0) ?></div></article>
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>New Today</span><i class="fa-solid fa-user-plus" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($newPatientsToday ?? 0) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('reception/patients/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Register Patient</a>
        <a class="btn" href="<?= site_url('reception/appointments/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Book Appointment</a>
        <a class="btn" href="<?= site_url('reception/patients') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">View Patients</a>
        <a class="btn" href="<?= site_url('reception/appointments') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Manage Appointments</a>
        <a class="btn" href="<?= site_url('reception/rooms') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Room Management</a>
        <a class="btn" href="<?= site_url('reception/patient-lookup') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Patient Lookup</a>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Today's Appointments</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Doctor</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Time</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($appointments)) : foreach ($appointments as $appointment) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">Dr. <?= esc($appointment['doctor_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['appointment_time']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <span style="padding:2px 6px;border-radius:4px;font-size:0.75rem;background-color:<?= $appointment['status'] === 'checked-in' ? '#d1fae5' : ($appointment['status'] === 'scheduled' ? '#dbeafe' : '#fef3c7') ?>;color:<?= $appointment['status'] === 'checked-in' ? '#065f46' : ($appointment['status'] === 'scheduled' ? '#1e40af' : '#92400e') ?>">
                    <?= esc(ucfirst($appointment['status'] ?: 'Scheduled')) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="4" style="padding:10px;color:#6b7280;text-align:center">No appointments today.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem;">
      <!-- Recent Patient Registrations -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Recent Patient Registrations</h2>

<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Reception navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/receptionist') ?>" class="active" aria-current="page"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
  <a href="<?= site_url('reception/patients') ?>"><i class="fa-solid fa-users" style="margin-right:8px"></i>Patient Management</a>
  <a href="<?= site_url('reception/appointments') ?>"><i class="fa-solid fa-calendar" style="margin-right:8px"></i>Appointment Management</a>
  <a href="<?= site_url('reception/rooms') ?>"><i class="fa-solid fa-door-open" style="margin-right:8px"></i>Room Management</a>
  <a href="<?= site_url('reception/rooms/admit') ?>"><i class="fa-solid fa-bed" style="margin-right:8px"></i>Room Admission</a>
  <a href="<?= site_url('reception/in-patients') ?>"><i class="fa-solid fa-hospital" style="margin-right:8px"></i>In-Patients</a>
  <a href="<?= site_url('reception/patient-lookup') ?>"><i class="fa-solid fa-magnifying-glass" style="margin-right:8px"></i>Patient Lookup</a>
</nav></aside>
  <main class="content" style="padding:20px">
    
    <!-- Statistics Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:24px">
      <div class="panel" style="border-left:4px solid #3b82f6">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div>
            <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Check-ins Today</p>
            <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($checkinsToday ?? 0) ?></h3>
          </div>
          <div style="width:48px;height:48px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center">
            <i class="fa-solid fa-user-check" style="font-size:24px;color:#3b82f6"></i>
          </div>

        </div>
      </div>
      
      <div class="panel" style="border-left:4px solid #0ea5e9">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div>
            <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Upcoming Appointments</p>
            <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($upcomingAppts ?? 0) ?></h3>
          </div>
          <div style="width:48px;height:48px;background:#e0f2fe;border-radius:12px;display:flex;align-items:center;justify-content:center">
            <i class="fa-solid fa-calendar" style="font-size:24px;color:#0ea5e9"></i>
          </div>
        </div>
      </div>
      
      <div class="panel" style="border-left:4px solid #10b981">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div>
            <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">Total Patients</p>
            <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($totalPatients ?? 0) ?></h3>
          </div>
          <div style="width:48px;height:48px;background:#d1fae5;border-radius:12px;display:flex;align-items:center;justify-content:center">
            <i class="fa-solid fa-users" style="font-size:24px;color:#10b981"></i>
          </div>
        </div>
      </div>
      
      <div class="panel" style="border-left:4px solid #f59e0b">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div>
            <p style="margin:0;color:#6b7280;font-size:0.875rem;font-weight:500">New Patients Today</p>
            <h3 style="margin:8px 0 0 0;font-size:1.75rem;font-weight:700;color:#111827"><?= number_format($newPatientsToday ?? 0) ?></h3>
          </div>
          <div style="width:48px;height:48px;background:#fef3c7;border-radius:12px;display:flex;align-items:center;justify-content:center">
            <i class="fa-solid fa-user-plus" style="font-size:24px;color:#f59e0b"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <section class="panel" style="margin-bottom:20px">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.25rem;font-weight:600">
          <i class="fa-solid fa-bolt" style="margin-right:8px;color:#3b82f6"></i>Quick Actions
        </h2>
      </div>
      <div class="panel-body" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px">
        <a href="<?= site_url('reception/patients/new') ?>" style="padding:14px 18px;background:linear-gradient(135deg, #10b981 0%, #059669 100%);color:white;text-decoration:none;border-radius:10px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 2px 8px rgba(16,185,129,0.3);transition:transform 0.2s">
          <i class="fa-solid fa-user-plus" style="font-size:1.2rem"></i>
          <span>Register New Patient</span>
        </a>
        <a href="<?= site_url('reception/appointments/new') ?>" style="padding:14px 18px;background:linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);color:white;text-decoration:none;border-radius:10px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 2px 8px rgba(59,130,246,0.3);transition:transform 0.2s">
          <i class="fa-solid fa-calendar-plus" style="font-size:1.2rem"></i>
          <span>Book Appointment</span>
        </a>
        <a href="<?= site_url('reception/rooms/admit') ?>" style="padding:14px 18px;background:linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);color:white;text-decoration:none;border-radius:10px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 2px 8px rgba(139,92,246,0.3);transition:transform 0.2s">
          <i class="fa-solid fa-bed" style="font-size:1.2rem"></i>
          <span>Admit Patient to Room</span>
        </a>
        <a href="<?= site_url('reception/patients') ?>" style="padding:14px 18px;background:linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);color:white;text-decoration:none;border-radius:10px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 2px 8px rgba(6,182,212,0.3);transition:transform 0.2s">
          <i class="fa-solid fa-list" style="font-size:1.2rem"></i>
          <span>View All Patients</span>
        </a>
        <a href="<?= site_url('reception/appointments') ?>" style="padding:14px 18px;background:linear-gradient(135deg, #ec4899 0%, #db2777 100%);color:white;text-decoration:none;border-radius:10px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 2px 8px rgba(236,72,153,0.3);transition:transform 0.2s">
          <i class="fa-solid fa-calendar-check" style="font-size:1.2rem"></i>
          <span>Manage Appointments</span>
        </a>
        <a href="<?= site_url('reception/patient-lookup') ?>" style="padding:14px 18px;background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%);color:white;text-decoration:none;border-radius:10px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 2px 8px rgba(245,158,11,0.3);transition:transform 0.2s">
          <i class="fa-solid fa-magnifying-glass" style="font-size:1.2rem"></i>
          <span>Patient Lookup</span>
        </a>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
      <!-- Today's Appointments -->
      <section class="panel">
        <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
          <h2 style="margin:0;font-size:1.1rem;font-weight:600">
            <i class="fa-solid fa-calendar-day" style="margin-right:8px;color:#3b82f6"></i>Today's Appointments
          </h2>
          <a href="<?= site_url('reception/appointments') ?>" style="font-size:0.875rem;color:#3b82f6;text-decoration:none">View All</a>
        </div>
        <div class="panel-body" style="padding:0;overflow-x:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr style="background:#f9fafb">
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Patient</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Doctor</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Time</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($appointments)) : foreach ($appointments as $appointment) : ?>
                <tr style="border-bottom:1px solid #f3f4f6" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                  <td style="padding:12px;font-size:0.875rem;font-weight:600">
                    <?= esc($appointment['first_name'] . ' ' . $appointment['last_name']) ?>
                  </td>
                  <td style="padding:12px;font-size:0.875rem">Dr. <?= esc($appointment['doctor_name']) ?></td>
                  <td style="padding:12px;font-size:0.875rem;color:#6b7280">
                    <i class="fa-solid fa-clock" style="font-size:0.75rem;margin-right:4px"></i>
                    <?= esc(date('g:i A', strtotime($appointment['appointment_time'] ?? '00:00:00'))) ?>
                  </td>
                  <td style="padding:12px;font-size:0.875rem">
                    <?php
                      $status = strtolower($appointment['status'] ?? 'scheduled');
                      $statusColors = [
                        'checked-in' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                        'scheduled' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                        'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                        'completed' => ['bg' => '#dcfce7', 'text' => '#166534'],
                      ];
                      $colors = $statusColors[$status] ?? $statusColors['scheduled'];
                    ?>
                    <span style="padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;background:<?= $colors['bg'] ?>;color:<?= $colors['text'] ?>">
                      <?= esc(ucfirst($status)) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="4" style="padding:40px;color:#6b7280;text-align:center">
                    <i class="fa-solid fa-calendar-xmark" style="font-size:2rem;opacity:0.3;margin-bottom:8px;display:block"></i>
                    No appointments scheduled for today.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Recent Patient Registrations -->
      <section class="panel">
        <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
          <h2 style="margin:0;font-size:1.1rem;font-weight:600">
            <i class="fa-solid fa-user-plus" style="margin-right:8px;color:#10b981"></i>Recent Patient Registrations
          </h2>
          <a href="<?= site_url('reception/patients') ?>" style="font-size:0.875rem;color:#10b981;text-decoration:none">View All</a>
        </div>
        <div class="panel-body" style="padding:0;overflow-x:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr style="background:#f9fafb">
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Patient</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Patient ID</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Contact</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recentPatients)) : foreach ($recentPatients as $patient) : ?>
                <tr style="border-bottom:1px solid #f3f4f6" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                  <td style="padding:12px;font-size:0.875rem">
                    <div style="display:flex;align-items:center;gap:8px">
                      <div style="width:32px;height:32px;background:#e5e7eb;border-radius:50%;display:flex;align-items:center;justify-content:center">
                        <i class="fa-solid fa-user" style="color:#6b7280;font-size:0.75rem"></i>
                      </div>
                      <strong><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></strong>
                    </div>
                  </td>
                  <td style="padding:12px;font-size:0.875rem">
                    <span style="color:#3b82f6;font-weight:600"><?= esc($patient['patient_id']) ?></span>
                  </td>
                  <td style="padding:12px;font-size:0.875rem;color:#6b7280">
                    <?php if (!empty($patient['phone'])): ?>
                      <i class="fa-solid fa-phone" style="font-size:0.75rem;margin-right:4px"></i>
                      <?= esc($patient['phone']) ?>
                    <?php else: ?>
                      <span style="color:#9ca3af">N/A</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="3" style="padding:40px;color:#6b7280;text-align:center">
                    <i class="fa-solid fa-users-slash" style="font-size:2rem;opacity:0.3;margin-bottom:8px;display:block"></i>
                    No recent patient registrations.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>

    <!-- Tomorrow's Appointments -->
    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem;font-weight:600">
          <i class="fa-solid fa-calendar-week" style="margin-right:8px;color:#f59e0b"></i>Tomorrow's Appointments
        </h2>
        <a href="<?= site_url('reception/appointments') ?>" style="font-size:0.875rem;color:#f59e0b;text-decoration:none">View All</a>
      </div>
      <div class="panel-body" style="padding:0;overflow-x:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr style="background:#f9fafb">
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Patient</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Doctor</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Time</th>
              <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem">Type</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($tomorrowAppts)) : foreach ($tomorrowAppts as $appointment) : ?>
              <tr style="border-bottom:1px solid #f3f4f6" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                <td style="padding:12px;font-size:0.875rem;font-weight:600">
                  <?= esc($appointment['first_name'] . ' ' . $appointment['last_name']) ?>
                </td>
                <td style="padding:12px;font-size:0.875rem">Dr. <?= esc($appointment['doctor_name']) ?></td>
                <td style="padding:12px;font-size:0.875rem;color:#6b7280">
                  <i class="fa-solid fa-clock" style="font-size:0.75rem;margin-right:4px"></i>
                  <?= esc(date('g:i A', strtotime($appointment['appointment_time'] ?? '00:00:00'))) ?>
                </td>
                <td style="padding:12px;font-size:0.875rem">
                  <span style="padding:4px 10px;background:#e0f2fe;color:#0369a1;border-radius:6px;font-size:0.75rem;font-weight:500">
                    <?= esc(ucfirst(str_replace('_', ' ', $appointment['type'] ?? 'consultation'))) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="4" style="padding:40px;color:#6b7280;text-align:center">
                  <i class="fa-solid fa-calendar-xmark" style="font-size:2rem;opacity:0.3;margin-bottom:8px;display:block"></i>
                  No appointments scheduled for tomorrow.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
  // Add hover effects to quick action buttons
  document.querySelectorAll('a[href*="reception"]').forEach(link => {
    link.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-2px)';
    });
    link.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
    });
  });
</script>
</body></html>
