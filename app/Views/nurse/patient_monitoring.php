<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patient Monitoring - Nurse Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Nurse</h1><small>Patient monitoring • Treatment updates</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Nurse navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/nurse') ?>">Overview</a>
  <a href="<?= site_url('nurse/ward-patients') ?>" class="active" aria-current="page" data-feature="ehr">Ward Patients</a>
  <a href="<?= site_url('nurse/lab-samples') ?>" data-feature="laboratory">Lab Samples</a>
  <a href="<?= site_url('nurse/treatment-updates') ?>" data-feature="ehr">Treatment Updates</a>
  <a href="<?= site_url('nurse/vitals/new') ?>" data-feature="ehr">Record Vitals</a>
</nav></aside>
  <main class="content">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
        <h1 style="font-size:1.5rem;margin:0">Patient Monitoring</h1>
        <a href="<?= site_url('nurse/ward-patients') ?>" style="background:#6b7280;color:white;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:0.875rem">
            <i class="fas fa-arrow-left"></i> Back to Ward Patients
        </a>
    </div>

    <!-- Patient Info Card -->
    <section class="panel" style="margin-bottom:16px">
        <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Patient Information</h2></div>
        <div class="panel-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <p style="margin-bottom:8px"><strong>Patient ID:</strong> <?= esc($patient['patient_id'] ?? 'N/A') ?></p>
                    <p style="margin-bottom:8px"><strong>Name:</strong> <?= esc(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) ?></p>
                    <p style="margin-bottom:8px"><strong>Gender:</strong> <?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?></p>
                </div>
                <div>
                    <p style="margin-bottom:8px"><strong>Date of Birth:</strong> <?= !empty($patient['date_of_birth']) ? date('M d, Y', strtotime($patient['date_of_birth'])) : 'N/A' ?></p>
                    <p style="margin-bottom:8px"><strong>Phone:</strong> <?= esc($patient['phone'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Vital Signs -->
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px">
        <section class="panel">
            <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
                <h2 style="margin:0;font-size:1.1rem">Vital Signs History</h2>
                <a href="<?= site_url('nurse/vitals/new') ?>?patient_id=<?= $patient['id'] ?>" style="background:#0ea5e9;color:white;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:0.875rem">
                    <i class="fas fa-plus"></i> Record Vitals
                </a>
            </div>
            <div class="panel-body" style="overflow:auto">
                <?php if (!empty($vitals)): ?>
                    <table class="table" style="width:100%;border-collapse:collapse">
                        <thead>
                            <tr>
                                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Date</th>
                                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">BP</th>
                                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Pulse</th>
                                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Temp (°C)</th>
                                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Resp. Rate</th>
                                <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">O2 Sat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vitals as $vital): 
                                $vitalSigns = json_decode($vital['vital_signs'] ?? '{}', true);
                            ?>
                                <tr>
                                    <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= date('M d, Y H:i', strtotime($vital['visit_date'])) ?></td>
                                    <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($vitalSigns['blood_pressure'] ?? 'N/A') ?></td>
                                    <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($vitalSigns['pulse'] ?? 'N/A') ?></td>
                                    <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($vitalSigns['temperature'] ?? 'N/A') ?></td>
                                    <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($vitalSigns['respiratory_rate'] ?? 'N/A') ?></td>
                                    <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($vitalSigns['oxygen_saturation'] ?? 'N/A') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="padding:12px;background:#dbeafe;border:1px solid #93c5fd;border-radius:6px;color:#1e40af">No vital signs recorded yet.</div>
                <?php endif; ?>
            </div>
        </section>

        <div>
            <!-- Quick Actions -->
            <section class="panel" style="margin-bottom:16px">
                <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
                <div class="panel-body">
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <a href="<?= site_url('nurse/vitals/new') ?>?patient_id=<?= $patient['id'] ?>" style="background:#0ea5e9;color:white;padding:10px 14px;border-radius:6px;text-decoration:none;text-align:center">
                            <i class="fas fa-heartbeat"></i> Record Vitals
                        </a>
                        <a href="<?= site_url('nurse/notes/new') ?>?patient_id=<?= $patient['id'] ?>" style="background:#06b6d4;color:white;padding:10px 14px;border-radius:6px;text-decoration:none;text-align:center">
                            <i class="fas fa-notes-medical"></i> Add Clinical Note
                        </a>
                    </div>
                </div>
            </section>

            <!-- Treatment Notes -->
            <section class="panel">
                <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Notes</h2></div>
                <div class="panel-body">
                    <?php if (!empty($treatmentNotes)): ?>
                        <div style="display:flex;flex-direction:column;gap:12px">
                            <?php foreach ($treatmentNotes as $note): ?>
                                <div style="padding:12px;border:1px solid #e5e7eb;border-radius:6px">
                                    <small style="color:#6b7280"><?= date('M d, Y', strtotime($note['visit_date'])) ?></small>
                                    <p style="margin:8px 0 0 0"><?= nl2br(esc($note['treatment_plan'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p style="color:#6b7280">No treatment notes available.</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
  </main>
</div>
</body>
</html>
