<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Medical Record - <?= esc($record['record_number'] ?? '#' . $record['id']) ?></title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('doctor/records') ?>" class="menu-btn" aria-label="Back"><i class="fas fa-arrow-left"></i> Back</a>
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Medical Record</h1>
        <small><?= esc($record['record_number'] ?? 'Record #' . $record['id']) ?></small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role"><i class="fa-regular fa-user"></i>
        <?= esc(session('username') ?? session('role') ?? 'User') ?>
      </span>
      <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Doctor navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/doctor') ?>">Overview</a>
      <a href="<?= site_url('doctor/records') ?>" class="active" aria-current="page">Patient Records</a>
      <a href="<?= site_url('doctor/patients') ?>">Patients</a>
      <a href="<?= site_url('doctor/prescriptions') ?>">Prescriptions</a>
      <a href="<?= site_url('doctor/lab-results') ?>">Lab Results</a>
    </nav>
  </aside>

  <main class="content">
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Medical Record Details</h2>
      </div>
      <div class="panel-body">
        <!-- Patient Information -->
        <div style="background:#f9fafb;padding:16px;border-radius:8px;margin-bottom:20px;border:1px solid #e5e7eb">
          <h3 style="margin:0 0 12px 0;font-size:1rem;color:#374151">Patient Information</h3>
          <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:12px">
            <div>
              <strong style="color:#6b7280;font-size:0.875rem">Patient Name:</strong>
              <div style="margin-top:4px;font-size:1rem"><?= esc(($record['first_name'] ?? '') . ' ' . ($record['last_name'] ?? '')) ?></div>
            </div>
            <div>
              <strong style="color:#6b7280;font-size:0.875rem">Patient ID:</strong>
              <div style="margin-top:4px;font-size:1rem"><?= esc($record['patient_code'] ?? 'N/A') ?></div>
            </div>
            <?php if (!empty($record['date_of_birth'])): ?>
            <div>
              <strong style="color:#6b7280;font-size:0.875rem">Date of Birth:</strong>
              <div style="margin-top:4px;font-size:1rem"><?= esc(date('M j, Y', strtotime($record['date_of_birth']))) ?></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($record['gender'])): ?>
            <div>
              <strong style="color:#6b7280;font-size:0.875rem">Gender:</strong>
              <div style="margin-top:4px;font-size:1rem"><?= esc(ucfirst($record['gender'])) ?></div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Record Information -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:16px;margin-bottom:20px">
          <div>
            <strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Record Number:</strong>
            <div style="font-size:1rem"><?= esc($record['record_number'] ?? 'N/A') ?></div>
          </div>
          <?php if (!empty($record['visit_date'])): ?>
          <div>
            <strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Visit Date:</strong>
            <div style="font-size:1rem"><?= esc(date('M j, Y H:i', strtotime($record['visit_date']))) ?></div>
          </div>
          <?php endif; ?>
          <?php if (!empty($record['appointment_id'])): ?>
          <div>
            <strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Appointment ID:</strong>
            <div style="font-size:1rem"><?= esc($record['appointment_id']) ?></div>
          </div>
          <?php endif; ?>
        </div>

        <!-- Chief Complaint -->
        <?php if (!empty($record['chief_complaint'])): ?>
        <div style="margin-bottom:20px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Chief Complaint</h3>
          <div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap"><?= esc($record['chief_complaint']) ?></div>
        </div>
        <?php endif; ?>

        <!-- History of Present Illness -->
        <?php if (!empty($record['history_present_illness'])): ?>
        <div style="margin-bottom:20px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">History of Present Illness</h3>
          <div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap"><?= esc($record['history_present_illness']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Physical Examination -->
        <?php if (!empty($record['physical_examination'])): ?>
        <div style="margin-bottom:20px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Physical Examination</h3>
          <div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap"><?= esc($record['physical_examination']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Vital Signs -->
        <?php if (!empty($record['vital_signs'])): ?>
        <div style="margin-bottom:20px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Vital Signs</h3>
          <div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb">
            <?php
            $vitals = json_decode($record['vital_signs'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($vitals)):
              echo '<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));gap:12px">';
              foreach ($vitals as $key => $value):
                echo '<div><strong style="color:#6b7280;font-size:0.875rem">' . esc(ucfirst(str_replace('_', ' ', $key))) . ':</strong> ';
                echo '<span style="font-size:1rem">' . esc($value) . '</span></div>';
              endforeach;
              echo '</div>';
            else:
              echo '<div style="white-space:pre-wrap">' . esc($record['vital_signs']) . '</div>';
            endif;
            ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Diagnosis -->
        <?php if (!empty($record['diagnosis'])): ?>
        <div style="margin-bottom:20px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Diagnosis</h3>
          <div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap"><?= esc($record['diagnosis']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Treatment Plan -->
        <?php if (!empty($record['treatment_plan'])): ?>
        <div style="margin-bottom:20px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Treatment Plan</h3>
          <div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap"><?= esc($record['treatment_plan']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Medications Prescribed -->
        <?php if (!empty($record['medications_prescribed'])): ?>
        <div style="margin-bottom:20px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Medications Prescribed</h3>
          <div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb">
            <?php
            $medications = json_decode($record['medications_prescribed'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($medications)):
              // Check if it's a single medication object or array of medications
              if (isset($medications['medicine_name']) || isset($medications['drug']) || isset($medications['name'])) {
                // Single medication object (new format)
                $med = $medications;
                $medicineName = $med['medicine_name'] ?? $med['drug'] ?? $med['name'] ?? 'Unknown Medicine';
                $dosage = $med['dosage'] ?? $med['dose'] ?? '';
                $frequency = $med['frequency'] ?? '';
                $duration = $med['duration'] ?? '';
                $purchaseLocation = $med['purchase_location'] ?? '';
                $status = $med['status'] ?? '';
                
                echo '<div style="background:white;padding:12px;border-radius:6px;border:1px solid #e5e7eb">';
                echo '<div style="display:flex;align-items:center;margin-bottom:10px">';
                echo '<i class="fas fa-capsules" style="color:#10b981;margin-right:8px;font-size:1.1rem"></i>';
                echo '<span style="font-weight:600;font-size:1rem;color:#1e293b">' . esc($medicineName) . '</span>';
                if ($status === 'pending_pharmacy') {
                  echo '<span style="padding:4px 8px;background:#fef3c7;color:#92400e;border-radius:4px;font-size:0.75rem;margin-left:12px;font-weight:600">Pending</span>';
                } elseif ($status === 'dispensed') {
                  echo '<span style="padding:4px 8px;background:#d1fae5;color:#065f46;border-radius:4px;font-size:0.75rem;margin-left:12px;font-weight:600">Dispensed</span>';
                }
                echo '</div>';
                echo '<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:8px;font-size:0.875rem;color:#64748b">';
                if ($dosage) echo '<div><strong style="color:#374151">Dosage:</strong> ' . esc($dosage) . '</div>';
                if ($frequency) echo '<div><strong style="color:#374151">Frequency:</strong> ' . esc($frequency) . '</div>';
                if ($duration) echo '<div><strong style="color:#374151">Duration:</strong> ' . esc($duration) . '</div>';
                if ($purchaseLocation) {
                  $locationIcon = $purchaseLocation === 'hospital_pharmacy' ? 'fa-hospital' : 'fa-store';
                  $locationText = $purchaseLocation === 'hospital_pharmacy' ? 'Hospital Pharmacy' : 'Outside';
                  echo '<div><strong style="color:#374151">Purchase:</strong> <i class="fas ' . $locationIcon . '" style="margin-left:4px;color:#3b82f6"></i> ' . esc($locationText) . '</div>';
                }
                echo '</div>';
                echo '</div>';
              } else {
                // Array of medications (old format)
                echo '<table style="width:100%;border-collapse:collapse">';
                echo '<thead><tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb">';
                echo '<th style="text-align:left;padding:8px">Drug</th>';
                echo '<th style="text-align:left;padding:8px">Dose</th>';
                if (isset($medications[0]['frequency'])) echo '<th style="text-align:left;padding:8px">Frequency</th>';
                if (isset($medications[0]['duration'])) echo '<th style="text-align:left;padding:8px">Duration</th>';
                echo '</tr></thead><tbody>';
                foreach ($medications as $med):
                  echo '<tr style="border-bottom:1px solid #f3f4f6">';
                  echo '<td style="padding:8px">' . esc($med['drug'] ?? $med['medicine_name'] ?? 'N/A') . '</td>';
                  echo '<td style="padding:8px">' . esc($med['dose'] ?? $med['dosage'] ?? 'N/A') . '</td>';
                  if (isset($medications[0]['frequency'])) echo '<td style="padding:8px">' . esc($med['frequency'] ?? 'N/A') . '</td>';
                  if (isset($medications[0]['duration'])) echo '<td style="padding:8px">' . esc($med['duration'] ?? 'N/A') . '</td>';
                  echo '</tr>';
                endforeach;
                echo '</tbody></table>';
              }
            else:
              // If not valid JSON, display as formatted text
              $displayText = strlen($record['medications_prescribed']) > 200 ? substr($record['medications_prescribed'], 0, 200) . '...' : $record['medications_prescribed'];
              echo '<div style="white-space:pre-wrap;font-size:0.875rem;color:#64748b">' . esc($displayText) . '</div>';
            endif;
            ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Follow-up Instructions -->
        <?php if (!empty($record['follow_up_instructions'])): ?>
        <div style="margin-bottom:20px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Follow-up Instructions</h3>
          <div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;white-space:pre-wrap"><?= esc($record['follow_up_instructions']) ?></div>
        </div>
        <?php endif; ?>

        <!-- Next Visit Date -->
        <?php if (!empty($record['next_visit_date'])): ?>
        <div style="margin-bottom:20px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#374151">Next Visit Date</h3>
          <div style="background:#fff;padding:12px;border-radius:6px;border:1px solid #e5e7eb;font-size:1rem">
            <?= esc(date('M j, Y', strtotime($record['next_visit_date']))) ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div style="margin-top:24px;display:flex;gap:10px;padding-top:20px;border-top:1px solid #e5e7eb">
          <a href="<?= site_url('doctor/records') ?>" class="btn" style="padding:10px 20px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none;background:#fff">
            <i class="fas fa-arrow-left"></i> Back to Records
          </a>
          <a href="<?= site_url('doctor/records/' . $record['id'] . '/edit') ?>" class="btn" style="padding:10px 20px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none;background:#fff">
            <i class="fas fa-edit"></i> Edit Record
          </a>
          <form method="post" action="<?= site_url('doctor/records/' . $record['id'] . '/delete') ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this medical record? This action cannot be undone.');">
            <?= csrf_field() ?>
            <button type="submit" class="btn" style="padding:10px 20px;border:1px solid #dc2626;border-radius:6px;background:#fff;color:#dc2626;cursor:pointer;">
              <i class="fas fa-trash"></i> Delete Record
            </button>
          </form>
          <a href="<?= site_url('doctor/patients/view/' . $record['patient_id']) ?>" class="btn" style="padding:10px 20px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none;background:#fff">
            <i class="fas fa-user"></i> View Patient
          </a>
        </div>
      </div>
    </section>
  </main>
</div>
</body>
</html>

