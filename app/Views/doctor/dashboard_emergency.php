<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Emergency Medicine Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text">
      <h1 style="font-size:1.25rem;margin:0"><?= esc($doctorSpecialization ?? 'Emergency Medicine Doctor') ?></h1>
      <small><?= esc($doctorDepartment['name'] ?? 'Emergency') ?> Department</small>
    </div>
  </div>
  <div class="top-right" aria-label="User session">
    <div style="display:flex;align-items:center;gap:12px">
      <span style="padding:6px 12px;background:#fee2e2;color:#991b1b;border-radius:6px;font-size:0.875rem;font-weight:600">
        <i class="fa-solid fa-truck-medical" style="margin-right:4px"></i>
        Emergency
      </span>
      <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? 'User') ?></span>
      <a href="<?= site_url('logout') ?>" class="logout-btn" style="text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
    </div>
  </div>
</div></header>
<div class="layout">
<?= $this->include('doctor/sidebar', [
  'specialization' => $doctorSpecialization ?? 'Emergency Medicine Doctor',
  'department' => $doctorDepartment ?? null,
  'currentPage' => 'dashboard'
]) ?>
  <main class="content">
    <?php if (!empty($highlightPatient)): ?>
      <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); display: flex; align-items: center; justify-content: space-between; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 16px; flex: 1;">
          <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="fa-solid fa-truck-medical" style="font-size: 24px;"></i>
          </div>
          <div style="flex: 1;">
            <div style="font-weight: 700; font-size: 1rem; margin-bottom: 4px;">🚨 EMERGENCY PATIENT REFERRED</div>
            <div style="font-size: 0.875rem; opacity: 0.95;">
              <strong><?= esc(trim(($highlightPatient['first_name'] ?? '') . ' ' . ($highlightPatient['last_name'] ?? ''))) ?></strong>
              (ID: <?= esc($highlightPatient['patient_id'] ?? 'N/A') ?>)
            </div>
          </div>
        </div>
        <div style="display: flex; gap: 8px; flex-shrink: 0;">
          <a href="<?= site_url('doctor/patients/consultation/' . $highlightPatientId) ?>" 
             style="padding: 10px 20px; background: white; color: #ef4444; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;"
             onmouseover="this.style.background='#fef2f2'; this.style.transform='scale(1.05)'"
             onmouseout="this.style.background='white'; this.style.transform='scale(1)'">
            <i class="fa-solid fa-stethoscope"></i>
            Start Consultation
          </a>
          <a href="<?= site_url('doctor/patients/view/' . $highlightPatientId) ?>" 
             style="padding: 10px 20px; background: rgba(255,255,255,0.2); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 8px; border: 2px solid rgba(255,255,255,0.3); transition: all 0.2s;"
             onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1.05)'"
             onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='scale(1)'">
            <i class="fa-solid fa-eye"></i>
            View Patient
          </a>
        </div>
      </div>
    <?php endif; ?>
    
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Pending Results</span><i class="fa-solid fa-flask-vial" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($pendingLabResults ?? 0) ?></div></article>
      <article class="kpi-card" style="background:#f0fdf4;border-left:4px solid #16a34a"><div class="kpi-head"><span>Assigned Patients</span><i class="fa-solid fa-users" aria-hidden="true"></i></div><div class="kpi-value" style="color:#15803d" aria-live="polite"><?= esc(count($assignedPatients ?? [])) ?></div></article>
      <article class="kpi-card" style="background:#fee2e2;border-left:4px solid #ef4444"><div class="kpi-head"><span>Emergency Cases</span><i class="fa-solid fa-truck-medical" aria-hidden="true"></i></div><div class="kpi-value" style="color:#dc2626" aria-live="polite"><?= esc($emergencyCases ?? 0) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('doctor/records/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">New Medical Record</a>
        <a class="btn" href="<?= site_url('doctor/lab-requests/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Request Lab Test</a>
        <a class="btn" href="<?= site_url('doctor/patients') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">View Patients</a>
        <a class="btn" href="<?= site_url('doctor/admit-patients?type=emergency') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none;background:#fee2e2;color:#991b1b"><i class="fa-solid fa-truck-medical" style="margin-right:4px"></i>Emergency Admit</a>
        <a class="btn" href="<?= site_url('doctor/prescriptions') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Prescriptions</a>
        <a class="btn" href="<?= site_url('doctor/lab-results') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Lab Results</a>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px">
      <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Active Emergencies</h2></div>
        <div class="panel-body" style="padding:20px;text-align:center;color:#6b7280">
          <i class="fa-solid fa-truck-medical" style="font-size:3rem;opacity:0.3;margin-bottom:12px;display:block"></i>
          <p style="margin:0">Active emergency cases will be displayed here.</p>
          <p style="margin:8px 0 0 0;font-size:0.875rem">Monitor critical patients requiring immediate attention.</p>
        </div>
      </section>

      <section class="panel">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">My Assigned Patients</h2></div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Admission Date</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Room</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($assignedPatients)) : foreach ($assignedPatients as $patient) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <a href="<?= site_url('doctor/patients/view/' . $patient['id']) ?>" style="color:#3b82f6;text-decoration:none;font-weight:600">
                      <?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?>
                    </a>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc($patient['admission_date'] ? date('M j, Y', strtotime($patient['admission_date'])) : 'N/A') ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php if (!empty($patient['assigned_room_id'])): ?>
                      <span style="padding:4px 8px;border-radius:4px;font-size:0.75rem;background:#d1fae5;color:#065f46">
                        Room <?= esc($patient['assigned_room_id']) ?>
                      </span>
                    <?php else: ?>
                      <span style="color:#9ca3af">Not assigned</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">
                    No patients assigned to you yet.<br>
                    <small style="font-size:0.75rem">Patients will appear here when receptionist assigns them to you.</small>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Medical Records</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Diagnosis</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($recentRecords)) : foreach ($recentRecords as $record) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($record['first_name'] . ' ' . $record['last_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('M j, Y', strtotime($record['visit_date']))) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($record['diagnosis'] ?: 'N/A') ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No recent records.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Pending Lab Tests</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pendingTests)) : foreach ($pendingTests as $test) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['first_name'] . ' ' . $test['last_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($test['test_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucfirst($test['status'] ?: 'Pending')) ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No pending tests.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

