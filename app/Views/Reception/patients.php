<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patient Management</title>
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
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>Patient Management</small></div>
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
      <a href="<?= site_url('dashboard/receptionist') ?>" class="<?= (current_url() == site_url('dashboard/receptionist')) ? 'active' : '' ?>">
        <i class="fas fa-tachometer-alt"></i> Overview
      </a>
      
      <!-- Patient Management -->
      <div class="nav-section">
        <h4 class="nav-section-title">Patient Management</h4>
        <a href="<?= site_url('reception/patients') ?>" class="<?= (strpos(current_url(), 'reception/patients') !== false && strpos(current_url(), 'patient-lookup') === false) ? 'active' : '' ?>" aria-current="page">
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
    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">Patients</h2>
        <div>
          <a class="btn" href="<?= site_url('reception/patients/new') ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none"><i class="fa-solid fa-user-plus"></i> Register Patient</a>
          <a class="btn" href="<?= site_url('reception/patient-lookup') ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none"><i class="fa-solid fa-magnifying-glass"></i> Lookup</a>
        </div>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Name</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient ID</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Gender</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date of Birth</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Phone</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Email</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Address</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($patients)) : foreach ($patients as $p) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><a href="<?= site_url('reception/patients/view/' . $p['id']) ?>"><?= esc(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? '')) ?></a></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['patient_id'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucfirst($p['gender'] ?? 'N/A')) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['date_of_birth'] ?? 'N/A') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['phone'] ?? 'N/A') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['email'] ?? 'N/A') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['address'] ?? 'N/A') ?></td>
                              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="7" style="padding:10px;color:#6b7280;text-align:center">No patients found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
