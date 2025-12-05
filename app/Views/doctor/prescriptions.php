<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prescriptions</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem">Prescriptions</h1>
</div></header>
<div class="layout"><aside class="simple-sidebar"><nav class="side-nav">
  <a href="<?= site_url('dashboard/doctor') ?>">Overview</a>
  <a href="<?= site_url('doctor/records') ?>">Patient Records</a>
  <a href="<?= site_url('doctor/patients') ?>">Patients</a>
  <a href="<?= site_url('doctor/prescriptions') ?>" class="active" aria-current="page">Prescriptions</a>
  <a href="<?= site_url('doctor/lab-results') ?>">Lab Results</a>
  <a href="<?= site_url('doctor/records/new') ?>">New Record</a>
</nav></aside>
  <main class="content">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Prescriptions</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Medications</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($prescriptions)) : foreach ($prescriptions as $rx) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(($rx['first_name'] ?? '') . ' ' . ($rx['last_name'] ?? '')) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(isset($rx['visit_date']) ? date('M j, Y', strtotime($rx['visit_date'])) : '-') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?php
                    $txt = $rx['medications_prescribed'] ?? '';
                    if ($txt) {
                      $decoded = json_decode($txt, true);
                      if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        // Handle the medication data structure
                        $medicineName = $decoded['medicine_name'] ?? $decoded['drug'] ?? $decoded['name'] ?? 'Unknown Medicine';
                        $dosage = $decoded['dosage'] ?? $decoded['dose'] ?? '';
                        $frequency = $decoded['frequency'] ?? '';
                        $duration = $decoded['duration'] ?? '';
                        $purchaseLocation = $decoded['purchase_location'] ?? '';
                        $status = $decoded['status'] ?? '';
                        
                        // Format purchase location
                        $locationText = '';
                        if ($purchaseLocation === 'hospital_pharmacy') {
                          $locationText = '<span style="color:#3b82f6;font-size:0.75rem"><i class="fas fa-hospital"></i> Hospital Pharmacy</span>';
                        } elseif ($purchaseLocation === 'outside') {
                          $locationText = '<span style="color:#6b7280;font-size:0.75rem"><i class="fas fa-store"></i> Outside</span>';
                        }
                        
                        // Format status badge
                        $statusBadge = '';
                        if ($status === 'pending_pharmacy') {
                          $statusBadge = '<span style="padding:2px 6px;background:#fef3c7;color:#92400e;border-radius:4px;font-size:0.75rem;margin-left:8px">Pending</span>';
                        } elseif ($status === 'dispensed') {
                          $statusBadge = '<span style="padding:2px 6px;background:#d1fae5;color:#065f46;border-radius:4px;font-size:0.75rem;margin-left:8px">Dispensed</span>';
                        }
                  ?>
                    <div style="line-height:1.6">
                      <div style="font-weight:600;color:#1e293b;margin-bottom:4px">
                        <i class="fas fa-pills" style="color:#3b82f6;margin-right:6px"></i><?= esc($medicineName) ?>
                      </div>
                      <div style="font-size:0.875rem;color:#64748b;margin-bottom:2px">
                        <?php if ($dosage): ?>
                          <span><strong>Dosage:</strong> <?= esc($dosage) ?></span>
                        <?php endif; ?>
                        <?php if ($frequency): ?>
                          <span style="margin-left:12px"><strong>Frequency:</strong> <?= esc($frequency) ?></span>
                        <?php endif; ?>
                      </div>
                      <div style="font-size:0.875rem;color:#64748b;margin-bottom:4px">
                        <?php if ($duration): ?>
                          <span><strong>Duration:</strong> <?= esc($duration) ?></span>
                        <?php endif; ?>
                        <?= $locationText ?>
                        <?= $statusBadge ?>
                      </div>
                    </div>
                  <?php
                      } else {
                        // If not valid JSON, just display as text (truncated)
                        $displayText = strlen($txt) > 100 ? substr($txt, 0, 100) . '...' : $txt;
                        echo '<span style="color:#6b7280;font-size:0.875rem">' . esc($displayText) . '</span>';
                      }
                    } else {
                      echo '<span style="color:#9ca3af">—</span>';
                    }
                  ?>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No prescriptions found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
</body></html>


