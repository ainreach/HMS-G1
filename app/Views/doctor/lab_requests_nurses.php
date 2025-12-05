<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lab Requests from Nurses - Doctor</title>
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
          <i class="fa-solid fa-vial" style="color:#16a34a;margin-right:8px"></i>Lab Requests from Nurses
        </h1>
        <small style="color:#6b7280">Review and confirm lab requests</small>
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
      <a href="<?= site_url('doctor/lab-requests-nurses') ?>" class="active" aria-current="page" style="display:flex;align-items:center;padding:12px 20px;color:#1f2937;text-decoration:none;font-weight:500;position:relative;background:#f0f9ff;border-left:4px solid #3b82f6">
        <i class="fa-solid fa-vial" style="width:20px;margin-right:12px;font-size:1rem"></i>Lab Requests
      </a>
      <a href="<?= site_url('doctor/orders') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
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

    <!-- Pending Requests -->
    <section class="panel" style="margin-bottom:20px">
      <div class="panel-head" style="background:#f3f4f6">
        <h2 style="margin:0;font-size:1.1rem">
          <i class="fa-solid fa-clock" style="color:#f59e0b;margin-right:8px"></i>Pending Requests (Awaiting Your Confirmation)
        </h2>
      </div>
      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($pendingRequests)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Test</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Patient</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Requested By</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Date</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pendingRequests as $request): 
                $nurseName = trim(($request['nurse_first'] ?? '') . ' ' . ($request['nurse_last'] ?? ''));
                if (empty($nurseName)) $nurseName = $request['nurse_username'] ?? 'Nurse';
              ?>
                <tr style="border-bottom:1px solid #f3f4f6">
                  <td style="padding:10px">
                    <div style="font-weight:600"><?= esc($request['test_name'] ?? 'N/A') ?></div>
                    <div style="color:#6b7280;font-size:0.875rem"><?= esc($request['test_number'] ?? 'TEST-' . $request['id']) ?></div>
                  </td>
                  <td style="padding:10px">
                    <div style="font-weight:600"><?= esc(trim(($request['first_name'] ?? '') . ' ' . ($request['last_name'] ?? ''))) ?></div>
                    <div style="color:#6b7280;font-size:0.875rem"><?= esc($request['patient_code'] ?? 'N/A') ?></div>
                  </td>
                  <td style="padding:10px"><?= esc($nurseName) ?></td>
                  <td style="padding:10px"><?= esc(date('M d, Y', strtotime($request['requested_date'] ?? 'now'))) ?></td>
                  <td style="padding:10px">
                    <form method="post" action="<?= site_url('doctor/lab-requests-nurses/' . $request['id'] . '/confirm') ?>" style="display:inline">
                      <?= csrf_field() ?>
                      <button type="submit" style="padding:6px 12px;background:#16a34a;color:white;border:none;border-radius:4px;cursor:pointer;font-size:0.875rem">
                        <i class="fa-solid fa-check"></i> Confirm
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div style="padding:60px 20px;text-align:center">
            <i class="fa-solid fa-check-circle" style="font-size:4rem;color:#d1d5db;margin-bottom:16px"></i>
            <h3 style="margin:0 0 8px 0;color:#374151;font-size:1.25rem">No Pending Requests</h3>
            <p style="margin:0;color:#6b7280">All lab requests from nurses have been processed.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Confirmed Requests -->
    <section class="panel">
      <div class="panel-head" style="background:#f3f4f6">
        <h2 style="margin:0;font-size:1.1rem">
          <i class="fa-solid fa-check" style="color:#16a34a;margin-right:8px"></i>Confirmed Requests (In Progress / Completed)
        </h2>
      </div>
      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($confirmedRequests)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Test</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Patient</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Requested By</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Status</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($confirmedRequests as $request): 
                $nurseName = trim(($request['nurse_first'] ?? '') . ' ' . ($request['nurse_last'] ?? ''));
                if (empty($nurseName)) $nurseName = $request['nurse_username'] ?? 'Nurse';
                $statusColor = '#3b82f6';
                if ($request['status'] === 'completed') $statusColor = '#16a34a';
                elseif ($request['status'] === 'in_progress') $statusColor = '#f59e0b';
              ?>
                <tr style="border-bottom:1px solid #f3f4f6">
                  <td style="padding:10px">
                    <div style="font-weight:600"><?= esc($request['test_name'] ?? 'N/A') ?></div>
                    <div style="color:#6b7280;font-size:0.875rem"><?= esc($request['test_number'] ?? 'TEST-' . $request['id']) ?></div>
                  </td>
                  <td style="padding:10px">
                    <div style="font-weight:600"><?= esc(trim(($request['first_name'] ?? '') . ' ' . ($request['last_name'] ?? ''))) ?></div>
                    <div style="color:#6b7280;font-size:0.875rem"><?= esc($request['patient_code'] ?? 'N/A') ?></div>
                  </td>
                  <td style="padding:10px"><?= esc($nurseName) ?></td>
                  <td style="padding:10px">
                    <span style="padding:4px 8px;border-radius:4px;font-size:0.85rem;background:<?= $statusColor ?>1A;color:<?= $statusColor ?>">
                      <?= esc(ucfirst(str_replace('_', ' ', $request['status'] ?? 'pending'))) ?>
                    </span>
                  </td>
                  <td style="padding:10px"><?= esc(date('M d, Y', strtotime($request['requested_date'] ?? 'now'))) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div style="padding:60px 20px;text-align:center">
            <i class="fa-solid fa-vial" style="font-size:4rem;color:#d1d5db;margin-bottom:16px"></i>
            <h3 style="margin:0 0 8px 0;color:#374151;font-size:1.25rem">No Confirmed Requests</h3>
            <p style="margin:0;color:#6b7280">No lab requests have been confirmed yet.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>

