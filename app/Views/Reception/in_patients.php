<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>In-Patients</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .inpatient-table thead {
      background: #f3f4f6;
    }
    .inpatient-table thead th {
      color: #000000;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
      padding: 12px;
    }
    .inpatient-table tbody td {
      padding: 12px;
      border-bottom: 1px solid #f3f4f6;
    }
    .btn-view {
      background: #3b82f6;
      color: white;
      padding: 8px 16px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-view:hover {
      background: #2563eb;
    }
    .badge-room {
      background: #dbeafe;
      color: #1e40af;
      padding: 4px 10px;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .badge-bed {
      background: #fef3c7;
      color: #92400e;
      padding: 4px 10px;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>In-Patients Management</small></div>
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
  <main class="content" style="max-width:100%;width:100%">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
        <div>
          <h2 style="margin:0;font-size:1.1rem">In-Patients List</h2>
          <span style="color:#6b7280;font-size:0.875rem"><?= count($inPatients) ?> patient(s) admitted</span>
        </div>
      </div>
      
      <!-- Search Bar -->
      <div class="panel-body" style="padding:16px;border-bottom:1px solid #e5e7eb;background:#f9fafb">
        <form method="get" action="<?= site_url('reception/in-patients') ?>" style="display:flex;gap:8px;align-items:center">
          <div style="flex:1;position:relative">
            <input type="text" 
                   name="search" 
                   value="<?= esc($search ?? '') ?>" 
                   placeholder="Search by name, patient ID, phone, room, bed, or doctor..." 
                   style="width:100%;padding:10px 16px 10px 40px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:0.95rem;transition:all 0.2s"
                   onfocus="this.style.borderColor='#3b82f6';this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.1)'"
                   onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
            <i class="fa-solid fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none"></i>
          </div>
          <button type="submit" style="padding:10px 20px;background:#3b82f6;color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:0.95rem;display:flex;align-items:center;gap:6px;white-space:nowrap">
            <i class="fa-solid fa-search"></i> Search
          </button>
          <?php if (!empty($search)): ?>
          <a href="<?= site_url('reception/in-patients') ?>" style="padding:10px 20px;background:#6b7280;color:white;text-decoration:none;border-radius:8px;font-weight:600;font-size:0.95rem;display:flex;align-items:center;gap:6px;white-space:nowrap">
            <i class="fa-solid fa-times"></i> Clear
          </a>
          <?php endif; ?>
        </form>
      </div>
      
      <div class="panel-body" style="overflow:auto;padding:0">
        <table class="table inpatient-table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th>PATIENT NAME</th>
              <th>PATIENT ID</th>
              <th>ROOM</th>
              <th>BED</th>
              <th>ATTENDING PHYSICIAN</th>
              <th>ADMISSION DATE</th>
              <th>ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($inPatients)) : foreach ($inPatients as $patient): 
              $admissionDate = $patient['admission_date'] ?? '';
              if ($admissionDate) {
                try {
                  $admissionDate = date('M d, Y', strtotime($admissionDate));
                } catch (\Exception $e) {
                  $admissionDate = $patient['admission_date'];
                }
              }
            ?>
              <tr>
                <td>
                  <strong><?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?></strong><br>
                  <small style="color:#6b7280"><?= esc($patient['phone'] ?? 'N/A') ?></small>
                </td>
                <td><?= esc($patient['patient_id'] ?? 'N/A') ?></td>
                <td>
                  <?php if ($patient['room_number']): ?>
                    <span class="badge-room">
                      <?= esc($patient['room_number']) ?> - <?= esc(ucfirst($patient['room_type'] ?? 'N/A')) ?>
                    </span>
                  <?php else: ?>
                    <span style="color:#9ca3af">Not assigned</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($patient['bed_number']): ?>
                    <span class="badge-bed"><?= esc($patient['bed_number']) ?></span>
                  <?php else: ?>
                    <span style="color:#9ca3af">N/A</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($patient['attending_physician_name']): ?>
                    <?= esc($patient['attending_physician_name']) ?>
                  <?php elseif ($patient['doctor_first_name'] || $patient['doctor_last_name']): ?>
                    <?= esc(trim(($patient['doctor_first_name'] ?? '') . ' ' . ($patient['doctor_last_name'] ?? ''))) ?>
                  <?php else: ?>
                    <span style="color:#9ca3af">Not assigned</span>
                  <?php endif; ?>
                </td>
                <td><?= esc($admissionDate) ?></td>
                <td>
                  <div style="display:flex;gap:6px;align-items:center">
                    <a href="<?= site_url('reception/in-patients/view/' . $patient['id']) ?>" class="btn-view">
                      <i class="fa-solid fa-eye"></i> View Details
                    </a>
                    <a href="<?= site_url('reception/in-patients/edit/' . $patient['id']) ?>" style="background:#f59e0b;color:white;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:0.875rem;font-weight:600;display:inline-flex;align-items:center;gap:6px">
                      <i class="fa-solid fa-edit"></i> Edit
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="7" style="padding:40px;color:#6b7280;text-align:center">
                  <i class="fa-solid fa-hospital" style="font-size:3rem;color:#d1d5db;margin-bottom:16px;display:block"></i>
                  <p style="margin:0;font-size:1rem">No in-patients found</p>
                  <p style="margin:8px 0 0 0;font-size:0.875rem;color:#9ca3af">All patients are currently out-patients</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

