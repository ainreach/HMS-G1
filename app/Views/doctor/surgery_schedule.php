<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Schedule Surgery</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem"><i class="fa-solid fa-kit-medical" style="margin-right:8px"></i>Schedule Surgery</h1>
</div></header>
<div class="layout">
<?= $this->include('doctor/sidebar', [
  'specialization' => $doctorSpecialization ?? 'Surgeon',
  'department' => $doctorDepartment ?? null,
  'currentPage' => 'surgeries'
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
        <h2 style="margin:0;font-size:1.1rem">Schedule New Surgery</h2>
        <p style="margin:4px 0 0 0;font-size:0.875rem;color:#6b7280">Schedule a surgical procedure for a patient</p>
      </div>
      <div class="panel-body" style="padding:20px">
        <form method="post" action="<?= site_url('doctor/surgeries') ?>">
          <?= csrf_field() ?>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
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
            
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Surgery Type <span style="color:#ef4444">*</span></label>
              <select name="surgery_type" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px">
                <option value="">-- Select Surgery Type --</option>
                <option value="General Surgery">General Surgery</option>
                <option value="Laparoscopic Surgery">Laparoscopic Surgery</option>
                <option value="Orthopedic Surgery">Orthopedic Surgery</option>
                <option value="Cardiac Surgery">Cardiac Surgery</option>
                <option value="Neurosurgery">Neurosurgery</option>
                <option value="Plastic Surgery">Plastic Surgery</option>
                <option value="Urological Surgery">Urological Surgery</option>
                <option value="Gynecological Surgery">Gynecological Surgery</option>
                <option value="ENT Surgery">ENT Surgery</option>
                <option value="Ophthalmic Surgery">Ophthalmic Surgery</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Surgery Date <span style="color:#ef4444">*</span></label>
              <input type="date" name="surgery_date" required 
                     style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px"
                     min="<?= date('Y-m-d') ?>">
            </div>
            
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Surgery Time <span style="color:#ef4444">*</span></label>
              <input type="time" name="surgery_time" required 
                     style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px">
            </div>
          </div>

          <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Notes</label>
            <textarea name="notes" rows="4" 
                      style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:15px;font-family:inherit"
                      placeholder="Enter any additional notes about the surgery..."></textarea>
          </div>

          <div style="display:flex;gap:12px">
            <button type="submit" 
                    style="padding:12px 24px;background:#dc2626;color:white;border:none;border-radius:8px;font-size:15px;font-weight:600;cursor:pointer">
              <i class="fa-solid fa-kit-medical" style="margin-right:6px"></i>Schedule Surgery
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
        <h2 style="margin:0;font-size:1.1rem">Upcoming Surgeries</h2>
      </div>
      <div class="panel-body" style="padding:20px">
        <?php if (empty($surgeries)): ?>
          <div style="text-align:center;color:#6b7280;padding:40px 20px">
            <i class="fa-solid fa-calendar-check" style="font-size:3rem;opacity:0.3;margin-bottom:12px;display:block"></i>
            <p style="margin:0">No scheduled surgeries found.</p>
            <p style="margin:8px 0 0 0;font-size:0.875rem">Schedule a surgery using the form above.</p>
          </div>
        <?php else: ?>
          <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
              <thead>
                <tr style="background:#f3f4f6;border-bottom:2px solid #e5e7eb">
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Surgery #</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Patient</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Surgery Type</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Date</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Time</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Status</th>
                  <th style="padding:12px;text-align:left;font-weight:600;color:#374151;font-size:0.875rem">Notes</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($surgeries as $surgery): ?>
                  <tr style="border-bottom:1px solid #e5e7eb;transition:background 0.2s" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem;font-weight:600">
                      <?= esc($surgery['surgery_number'] ?? 'N/A') ?>
                    </td>
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem">
                      <?= esc(trim(($surgery['patient_first_name'] ?? '') . ' ' . ($surgery['patient_last_name'] ?? ''))) ?>
                      <br>
                      <span style="color:#6b7280;font-size:0.75rem">ID: <?= esc($surgery['patient_code'] ?? 'N/A') ?></span>
                    </td>
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem">
                      <?= esc($surgery['surgery_type'] ?? 'N/A') ?>
                    </td>
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem">
                      <?= !empty($surgery['surgery_date']) ? date('M d, Y', strtotime($surgery['surgery_date'])) : 'N/A' ?>
                    </td>
                    <td style="padding:12px;color:#1f2937;font-size:0.875rem">
                      <?= !empty($surgery['surgery_time']) ? date('h:i A', strtotime($surgery['surgery_time'])) : 'N/A' ?>
                    </td>
                    <td style="padding:12px">
                      <?php
                      $status = $surgery['status'] ?? 'scheduled';
                      $statusColors = [
                        'scheduled' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'label' => 'Scheduled'],
                        'in_progress' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'In Progress'],
                        'completed' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Completed'],
                        'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Cancelled'],
                        'postponed' => ['bg' => '#f3f4f6', 'text' => '#374151', 'label' => 'Postponed'],
                      ];
                      $statusStyle = $statusColors[$status] ?? $statusColors['scheduled'];
                      ?>
                      <span style="display:inline-block;padding:4px 12px;background:<?= $statusStyle['bg'] ?>;color:<?= $statusStyle['text'] ?>;border-radius:12px;font-size:0.75rem;font-weight:600;text-transform:uppercase">
                        <?= $statusStyle['label'] ?>
                      </span>
                    </td>
                    <td style="padding:12px;color:#6b7280;font-size:0.875rem;max-width:200px">
                      <?= !empty($surgery['notes']) ? esc(substr($surgery['notes'], 0, 50)) . (strlen($surgery['notes']) > 50 ? '...' : '') : '-' ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

