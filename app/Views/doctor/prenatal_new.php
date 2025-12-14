<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prenatal Checkup</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem"><i class="fa-solid fa-baby" style="margin-right:8px"></i>Prenatal Checkup</h1>
</div></header>
<div class="layout">
<?= $this->include('doctor/sidebar', [
  'specialization' => $doctorSpecialization ?? 'OB-GYN',
  'department' => $doctorDepartment ?? null,
  'currentPage' => 'prenatal'
]) ?>
  <main class="content">
    <?php if (session('success')): ?>
      <div class="alert alert-success" style="margin-bottom:1rem;padding:12px 16px;background:#d1fae5;color:#065f46;border-radius:8px;border:1px solid #a7f3d0">
        <?= esc(session('success')) ?>
      </div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div class="alert alert-error" style="margin-bottom:1rem;padding:12px 16px;background:#fee2e2;color:#991b1b;border-radius:8px;border:1px solid #fecaca">
        <?= esc(session('error')) ?>
      </div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">New Prenatal Checkup</h2>
        <p style="margin:4px 0 0 0;font-size:0.875rem;color:#6b7280">Record a prenatal checkup for an expecting mother</p>
      </div>
      <div class="panel-body" style="padding:20px">
        <form method="post" action="<?= site_url('doctor/prenatal') ?>">
          <?= csrf_field() ?>
          
          <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Patient <span style="color:#ef4444">*</span></label>
            <select name="patient_id" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px">
              <option value="">-- Select Patient --</option>
              <?php if (!empty($patients)): foreach ($patients as $patient): ?>
                <option value="<?= $patient['id'] ?>">
                  <?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?>
                  (ID: <?= esc($patient['patient_id'] ?? 'N/A') ?>)
                </option>
              <?php endforeach; endif; ?>
            </select>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Checkup Date <span style="color:#ef4444">*</span></label>
              <input type="date" name="checkup_date" required 
                     style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px"
                     value="<?= date('Y-m-d') ?>">
            </div>
            
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Gestational Age (weeks)</label>
              <input type="number" name="gestational_age" min="0" max="42" step="0.1"
                     style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px"
                     placeholder="e.g., 28.5">
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Blood Pressure</label>
              <input type="text" name="blood_pressure" 
                     style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px"
                     placeholder="e.g., 120/80">
            </div>
            
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Weight (kg)</label>
              <input type="number" name="weight" min="0" step="0.1"
                     style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px"
                     placeholder="e.g., 65.5">
            </div>
            
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Fetal Heart Rate (bpm)</label>
              <input type="number" name="fetal_heart_rate" min="0" max="200"
                     style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px"
                     placeholder="e.g., 140">
            </div>
          </div>

          <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Notes</label>
            <textarea name="notes" rows="4" 
                      style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px;font-family:inherit"
                      placeholder="Enter any additional notes about the prenatal checkup..."></textarea>
          </div>

          <div style="display:flex;gap:12px">
            <button type="submit" 
                    style="padding:12px 24px;background:#ec4899;color:white;border:none;border-radius:8px;font-size:15px;font-weight:600;cursor:pointer">
              <i class="fa-solid fa-baby" style="margin-right:6px"></i>Record Checkup
            </button>
            <a href="<?= site_url('dashboard/doctor') ?>" 
               style="padding:12px 24px;background:#e5e7eb;color:#374151;text-decoration:none;border-radius:8px;font-size:15px;font-weight:600;display:inline-flex;align-items:center">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Prenatal Checkup History</h2>
      </div>
      <div class="panel-body" style="padding:20px">
        <?php if (empty($checkups)): ?>
          <div style="text-align:center;color:#6b7280;padding:40px 20px">
            <i class="fa-solid fa-baby" style="font-size:3rem;opacity:0.3;margin-bottom:12px;display:block"></i>
            <p style="margin:0">No prenatal checkups recorded yet.</p>
            <p style="margin:8px 0 0 0;font-size:0.875rem">Record a prenatal checkup using the form above.</p>
          </div>
        <?php else: ?>
          <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
              <thead>
                <tr style="background:#f3f4f6;border-bottom:2px solid #e5e7eb">
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Checkup #</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Patient</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Date</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Gestational Age</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">BP</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Weight</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">FHR</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Notes</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($checkups as $checkup): ?>
                  <tr style="border-bottom:1px solid #e5e7eb;transition:background 0.2s" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem;font-weight:600">
                      <?= esc($checkup['checkup_number'] ?? 'N/A') ?>
                    </td>
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem">
                      <?= esc(trim(($checkup['patient_first_name'] ?? '') . ' ' . ($checkup['patient_last_name'] ?? ''))) ?>
                      <br>
                      <span style="color:#6b7280;font-size:0.75rem">ID: <?= esc($checkup['patient_code'] ?? 'N/A') ?></span>
                    </td>
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem">
                      <?= !empty($checkup['checkup_date']) ? date('M d, Y', strtotime($checkup['checkup_date'])) : 'N/A' ?>
                    </td>
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem">
                      <?= !empty($checkup['gestational_age']) ? esc($checkup['gestational_age']) . ' weeks' : '-' ?>
                    </td>
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem">
                      <?= esc($checkup['blood_pressure'] ?? '-') ?>
                    </td>
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem">
                      <?= !empty($checkup['weight']) ? esc($checkup['weight']) . ' kg' : '-' ?>
                    </td>
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem">
                      <?= !empty($checkup['fetal_heart_rate']) ? esc($checkup['fetal_heart_rate']) . ' bpm' : '-' ?>
                    </td>
                    <td style="padding:12px;color:#6b7280;font-size:0.875rem;max-width:200px">
                      <?= !empty($checkup['notes']) ? esc(substr($checkup['notes'], 0, 50)) . (strlen($checkup['notes']) > 50 ? '...' : '') : '-' ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Prenatal Care Guidelines</h2>
      </div>
      <div class="panel-body" style="padding:20px">
        <div style="background:#fce7f3;border-left:4px solid #ec4899;padding:16px;border-radius:8px;margin-bottom:16px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#db2777"><i class="fa-solid fa-info-circle" style="margin-right:6px"></i>Standard Prenatal Visit Schedule</h3>
          <ul style="margin:8px 0 0 0;padding-left:20px;color:#9f1239">
            <li><strong>Weeks 4-28:</strong> Monthly visits</li>
            <li><strong>Weeks 28-36:</strong> Bi-weekly visits</li>
            <li><strong>Weeks 36-40:</strong> Weekly visits</li>
            <li><strong>After 40 weeks:</strong> As needed until delivery</li>
          </ul>
        </div>
        <p style="margin:0;font-size:0.875rem;color:#6b7280">
          <i class="fa-solid fa-lightbulb" style="margin-right:4px"></i>
          Regular prenatal checkups help monitor the health of both mother and baby throughout pregnancy.
        </p>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

