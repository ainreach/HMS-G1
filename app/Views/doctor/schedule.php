<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Complete Schedule - Doctor</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <div class="brand">
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0;color:#ffffff">
          <i class="fa-solid fa-calendar" style="color:#ffffff;margin-right:8px"></i>My Complete Schedule
        </h1>
        <small style="color:#d1fae5">All consultations and appointments</small>
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
      <a href="<?= site_url('doctor/schedule') ?>" class="active" aria-current="page" style="display:flex;align-items:center;padding:12px 20px;color:#1f2937;text-decoration:none;font-weight:500;position:relative;background:#f0f9ff;border-left:4px solid #3b82f6">
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
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center;background:#16a34a;color:white">
        <h2 style="margin:0;font-size:1.1rem;color:white;display:flex;align-items:center">
          <i class="fa-solid fa-calendar" style="margin-right:8px"></i>My Complete Schedule
        </h2>
        <div style="display:flex;gap:8px">
          <a href="<?= site_url('doctor/schedule?filter=upcoming') ?>" 
             style="padding:8px 16px;background:<?= $currentFilter === 'upcoming' ? '#15803d' : '#22c55e' ?>;color:white;border-radius:6px;text-decoration:none;font-size:0.875rem;border:1px solid #15803d">
            <i class="fa-solid fa-clock"></i> Upcoming Only
          </a>
          <a href="<?= site_url('doctor/patients') ?>" 
             style="padding:8px 16px;background:#22c55e;color:white;border-radius:6px;text-decoration:none;font-size:0.875rem;border:1px solid #15803d">
            <i class="fa-solid fa-plus"></i> New Consultation
          </a>
        </div>
      </div>
      <div class="panel-body" style="overflow:auto;padding:0">
        <?php if (!empty($consultations)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr style="background:#f9fafb">
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem;text-transform:uppercase">DATE</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem;text-transform:uppercase">TIME</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem;text-transform:uppercase">PATIENT</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem;text-transform:uppercase">TYPE</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem;text-transform:uppercase">STATUS</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem;text-transform:uppercase">NOTES</th>
                <th style="text-align:left;padding:12px;border-bottom:2px solid #e5e7eb;font-weight:600;font-size:0.875rem;text-transform:uppercase">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($consultations as $index => $consultation): 
                $statusText = strtoupper($consultation['status'] ?? 'approved');
                $typeText = $consultation['type'] ?? 'COMPLETED';
              ?>
                <tr style="border-bottom:1px solid #f3f4f6;background:<?= $index % 2 === 0 ? '#ffffff' : '#f9fafb' ?>">
                  <td style="padding:12px"><?= esc(date('M d, Y', strtotime($consultation['date']))) ?></td>
                  <td style="padding:12px"><?= esc(date('g:i A', strtotime($consultation['time']))) ?></td>
                  <td style="padding:12px">
                    <div style="font-weight:600"><?= esc($consultation['patient_name']) ?></div>
                    <div style="color:#6b7280;font-size:0.875rem"><?= esc($consultation['patient_code']) ?></div>
                  </td>
                  <td style="padding:12px">
                    <span style="padding:4px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;background:#d1fae5;color:#065f46">
                      <?= esc($typeText) ?>
                    </span>
                  </td>
                  <td style="padding:12px">
                    <span style="padding:4px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;background:#d1fae5;color:#065f46">
                      <?= esc($statusText) ?>
                    </span>
                  </td>
                  <td style="padding:12px;color:#6b7280;font-size:0.875rem"><?= esc($consultation['notes']) ?></td>
                  <td style="padding:12px">
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                      <?php if ($consultation['needs_admission']): ?>
                        <button onclick="markForAdmission(<?= $consultation['patient_id'] ?>)" 
                                style="padding:6px 12px;background:#ef4444;color:white;border:none;border-radius:4px;font-size:0.875rem;cursor:pointer;display:inline-flex;align-items:center;gap:4px">
                          <i class="fa-solid fa-bed-pulse"></i> FOR ADMISSION
                        </button>
                        <span style="color:#6b7280;font-size:0.75rem">Nurse/Receptionist will process</span>
                      <?php endif; ?>
                      <a href="<?= site_url('doctor/records/' . $consultation['id'] . '/edit') ?>" 
                         style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#f59e0b;color:white;border-radius:50%;text-decoration:none;font-size:0.875rem"
                         title="Edit">
                        <i class="fa-solid fa-pencil"></i>
                      </a>
                      <a href="<?= site_url('doctor/records/' . $consultation['id']) ?>" 
                         style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#3b82f6;color:white;border-radius:50%;text-decoration:none;font-size:0.875rem"
                         title="View">
                        <i class="fa-solid fa-user"></i>
                      </a>
                      <button onclick="deleteConsultation(<?= $consultation['id'] ?>)" 
                              style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:#ef4444;color:white;border:none;border-radius:50%;cursor:pointer;font-size:0.875rem"
                              title="Delete">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div style="padding:60px 20px;text-align:center">
            <i class="fa-solid fa-calendar" style="font-size:4rem;color:#d1d5db;margin-bottom:16px"></i>
            <h3 style="margin:0 0 8px 0;color:#374151;font-size:1.25rem">No Consultations Found</h3>
            <p style="margin:0;color:#6b7280"><?= $currentFilter === 'upcoming' ? 'No upcoming consultations scheduled.' : 'You haven\'t completed any consultations yet.' ?></p>
            <a href="<?= site_url('doctor/patients') ?>" style="display:inline-block;margin-top:20px;padding:12px 24px;background:#16a34a;color:white;border-radius:6px;text-decoration:none;font-weight:600">
              <i class="fa-solid fa-plus"></i> New Consultation
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
function markForAdmission(patientId) {
  if (confirm('Mark this patient for admission?')) {
    window.location.href = '<?= site_url('doctor/admit-patient/') ?>' + patientId;
  }
}

function deleteConsultation(recordId) {
  if (confirm('Are you sure you want to delete this consultation record?')) {
    fetch('<?= site_url('doctor/records/') ?>' + recordId + '/delete', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        alert('Error deleting record: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      // Fallback to form submission
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '<?= site_url('doctor/records/') ?>' + recordId + '/delete';
      const csrf = document.createElement('input');
      csrf.type = 'hidden';
      csrf.name = '<?= csrf_token() ?>';
      csrf.value = '<?= csrf_hash() ?>';
      form.appendChild(csrf);
      document.body.appendChild(form);
      form.submit();
    });
  }
}
</script>
</body>
</html>
