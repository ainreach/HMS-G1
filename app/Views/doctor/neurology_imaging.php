<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Neurological Imaging</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem"><i class="fa-solid fa-brain" style="margin-right:8px"></i>Neurological Imaging</h1>
</div></header>
<div class="layout">
<?= $this->include('doctor/sidebar', [
  'specialization' => $doctorSpecialization ?? 'Neurologist',
  'department' => $doctorDepartment ?? null,
  'currentPage' => 'neurology-imaging'
]) ?>
  <main class="content">
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Neurological Imaging Requests & Results</h2>
        <p style="margin:4px 0 0 0;font-size:0.875rem;color:#6b7280">View and manage CT scans, MRI, EEG, and other neurological imaging studies</p>
      </div>
      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($imagingTests)) : ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test Type</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Requested Date</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($imagingTests as $test) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <strong><?= esc(trim(($test['first_name'] ?? '') . ' ' . ($test['last_name'] ?? ''))) ?></strong>
                    <br><small style="color:#6b7280">ID: <?= esc($test['patient_code'] ?? 'N/A') ?></small>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <span style="padding:4px 8px;background:#f3e8ff;color:#7c3aed;border-radius:4px;font-size:0.875rem;font-weight:600">
                      <?= esc($test['test_name'] ?? 'N/A') ?>
                    </span>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc($test['requested_date'] ? date('M j, Y', strtotime($test['requested_date'])) : 'N/A') ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php
                      $status = strtolower($test['status'] ?? 'pending');
                      $statusColors = [
                        'pending' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                        'in_progress' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                        'completed' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                        'cancelled' => ['bg' => '#fee2e2', 'text' => '#991b1b']
                      ];
                      $colors = $statusColors[$status] ?? $statusColors['pending'];
                    ?>
                    <span style="padding:4px 8px;background:<?= $colors['bg'] ?>;color:<?= $colors['text'] ?>;border-radius:4px;font-size:0.75rem;font-weight:600">
                      <?= esc(ucfirst($status)) ?>
                    </span>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php if ($status === 'completed'): ?>
                      <a href="<?= site_url('doctor/lab-results/' . $test['id']) ?>" 
                         style="padding:6px 12px;background:#3b82f6;color:white;text-decoration:none;border-radius:6px;font-size:0.875rem;display:inline-block">
                        <i class="fa-solid fa-eye" style="margin-right:4px"></i>View Results
                      </a>
                    <?php else: ?>
                      <span style="padding:6px 12px;background:#e5e7eb;color:#6b7280;border-radius:6px;font-size:0.875rem;display:inline-block">
                        Pending
                      </span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else : ?>
          <div style="padding:40px;text-align:center;color:#6b7280">
            <i class="fa-solid fa-brain" style="font-size:3rem;opacity:0.3;margin-bottom:12px;display:block"></i>
            <p style="margin:0;font-size:1rem;font-weight:500">No neurological imaging tests found</p>
            <p style="margin:8px 0 0 0;font-size:0.875rem">Neurological imaging requests (CT, MRI, EEG, etc.) will appear here.</p>
            <a href="<?= site_url('doctor/lab-requests/new') ?>" 
               style="display:inline-block;margin-top:16px;padding:10px 20px;background:#7c3aed;color:white;text-decoration:none;border-radius:8px">
              <i class="fa-solid fa-plus" style="margin-right:6px"></i>Request Imaging
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Common Neurological Imaging Types</h2>
      </div>
      <div class="panel-body" style="padding:20px">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
          <div style="background:#f3e8ff;border-left:4px solid #7c3aed;padding:16px;border-radius:8px">
            <h3 style="margin:0 0 8px 0;font-size:0.875rem;color:#7c3aed;font-weight:600">CT Scan</h3>
            <p style="margin:0;font-size:0.75rem;color:#6b7280">Computed Tomography - Brain imaging</p>
          </div>
          <div style="background:#f3e8ff;border-left:4px solid #7c3aed;padding:16px;border-radius:8px">
            <h3 style="margin:0 0 8px 0;font-size:0.875rem;color:#7c3aed;font-weight:600">MRI</h3>
            <p style="margin:0;font-size:0.75rem;color:#6b7280">Magnetic Resonance Imaging</p>
          </div>
          <div style="background:#f3e8ff;border-left:4px solid #7c3aed;padding:16px;border-radius:8px">
            <h3 style="margin:0 0 8px 0;font-size:0.875rem;color:#7c3aed;font-weight:600">EEG</h3>
            <p style="margin:0;font-size:0.75rem;color:#6b7280">Electroencephalogram</p>
          </div>
          <div style="background:#f3e8ff;border-left:4px solid #7c3aed;padding:16px;border-radius:8px">
            <h3 style="margin:0 0 8px 0;font-size:0.875rem;color:#7c3aed;font-weight:600">PET Scan</h3>
            <p style="margin:0;font-size:0.75rem;color:#6b7280">Positron Emission Tomography</p>
          </div>
        </div>
        <p style="margin:16px 0 0 0;font-size:0.875rem;color:#6b7280">
          <i class="fa-solid fa-lightbulb" style="margin-right:4px"></i>
          Click "Request Imaging" to order neurological imaging tests for your patients.
        </p>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

