<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Discharge Patients - Doctor</title>
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
          <i class="fa-solid fa-sign-out-alt" style="color:#16a34a;margin-right:8px"></i>Discharge Patients
        </h1>
        <small style="color:#6b7280">Manage patient discharges</small>
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
      <a href="<?= site_url('doctor/orders') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-prescription" style="width:20px;margin-right:12px;font-size:1rem"></i>Doctor Orders
      </a>
      <a href="<?= site_url('doctor/admit-patients') ?>" style="display:flex;align-items:center;padding:12px 20px;color:#6b7280;text-decoration:none;font-weight:400;transition:all 0.2s">
        <i class="fa-solid fa-users" style="width:20px;margin-right:12px;font-size:1rem"></i>Admitted Patients
      </a>
      <a href="<?= site_url('doctor/discharge-patients') ?>" class="active" aria-current="page" style="display:flex;align-items:center;padding:12px 20px;color:#1f2937;text-decoration:none;font-weight:500;position:relative;background:#f0f9ff;border-left:4px solid #3b82f6">
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
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Active Admissions</h2>
      </div>
      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($patients)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Patient</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Room</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Bed</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Admission Date</th>
                <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($patients as $patient): ?>
                <tr style="border-bottom:1px solid #f3f4f6">
                  <td style="padding:10px">
                    <div style="font-weight:600"><?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?></div>
                    <div style="color:#6b7280;font-size:0.875rem"><?= esc($patient['patient_id'] ?? 'N/A') ?></div>
                  </td>
                  <td style="padding:10px">
                    <?php if (!empty($patient['room_number'])): ?>
                      <span style="padding:4px 8px;background:#dbeafe;color:#1e40af;border-radius:4px;font-size:0.875rem">
                        <?= esc($patient['room_number']) ?>
                      </span>
                      <?php if (!empty($patient['room_type'])): ?>
                        <div style="color:#6b7280;font-size:0.875rem;margin-top:4px"><?= esc($patient['room_type']) ?></div>
                      <?php endif; ?>
                    <?php else: ?>
                      <span style="color:#6b7280">N/A</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:10px">
                    <?php if (!empty($patient['bed_number']) || !empty($patient['assigned_bed_id'])): ?>
                      <?php if (!empty($patient['bed_number'])): ?>
                        <span style="padding:4px 8px;background:#f3f4f6;color:#374151;border-radius:4px;font-size:0.875rem">
                          <?= esc($patient['bed_number']) ?>
                        </span>
                        <?php if (!empty($patient['bed_type'])): ?>
                          <div style="color:#6b7280;font-size:0.875rem;margin-top:4px"><?= esc(ucfirst($patient['bed_type'])) ?></div>
                        <?php endif; ?>
                      <?php else: ?>
                        <span style="color:#6b7280;font-size:0.875rem">Bed ID: <?= esc($patient['assigned_bed_id']) ?></span>
                      <?php endif; ?>
                    <?php else: ?>
                      <span style="color:#6b7280">N/A</span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:10px"><?= esc(date('M d, Y', strtotime($patient['admission_date'] ?? 'now'))) ?></td>
                  <td style="padding:10px">
                    <button onclick="showDischargeModal(<?= $patient['id'] ?>, '<?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?>')" 
                            style="padding:6px 12px;background:#16a34a;color:white;border:none;border-radius:4px;cursor:pointer;font-size:0.875rem">
                      <i class="fa-solid fa-sign-out-alt"></i> Discharge
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div style="padding:60px 20px;text-align:center">
            <i class="fa-solid fa-check-circle" style="font-size:4rem;color:#d1d5db;margin-bottom:16px"></i>
            <h3 style="margin:0 0 8px 0;color:#374151;font-size:1.25rem">No Active Admissions</h3>
            <p style="margin:0;color:#6b7280">You have no patients currently admitted under your care.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</div>

<!-- Discharge Modal -->
<div id="dischargeModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center">
  <div style="background:white;border-radius:8px;padding:24px;max-width:500px;width:90%">
    <h3 style="margin:0 0 16px 0;font-size:1.25rem">Discharge Patient</h3>
    <form method="post" action="" id="dischargeForm">
      <?= csrf_field() ?>
      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:8px;font-weight:600">Patient</label>
        <input type="text" id="modalPatientName" readonly style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;background:#f3f4f6">
      </div>
      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:8px;font-weight:600">Discharge Date <span style="color:#ef4444">*</span></label>
        <input type="date" name="discharge_date" value="<?= date('Y-m-d') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px">
      </div>
      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:8px;font-weight:600">Discharge Notes</label>
        <textarea name="discharge_notes" rows="4" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px"></textarea>
      </div>
      <div style="display:flex;gap:8px;justify-content:flex-end">
        <button type="button" onclick="closeDischargeModal()" style="padding:8px 16px;background:#6b7280;color:white;border:none;border-radius:6px;cursor:pointer">Cancel</button>
        <button type="submit" style="padding:8px 16px;background:#16a34a;color:white;border:none;border-radius:6px;cursor:pointer">Confirm Discharge</button>
      </div>
    </form>
  </div>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
function showDischargeModal(patientId, patientName) {
  document.getElementById('modalPatientName').value = patientName;
  document.getElementById('dischargeForm').action = '<?= site_url('doctor/discharge-patient/') ?>' + patientId;
  document.getElementById('dischargeModal').style.display = 'flex';
}

function closeDischargeModal() {
  document.getElementById('dischargeModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('dischargeModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeDischargeModal();
  }
});
</script>
</body>
</html>

