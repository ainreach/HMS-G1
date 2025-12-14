<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vaccination Records</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem"><i class="fa-solid fa-syringe" style="margin-right:8px"></i>Vaccination Records</h1>
</div></header>
<div class="layout">
<?= $this->include('doctor/sidebar', [
  'specialization' => $doctorSpecialization ?? 'Pediatrician',
  'department' => $doctorDepartment ?? null,
  'currentPage' => 'vaccinations'
]) ?>
  <main class="content">
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Pediatric Patients - Vaccination Tracking</h2>
        <p style="margin:4px 0 0 0;font-size:0.875rem;color:#6b7280">Manage and track vaccination records for pediatric patients</p>
      </div>
      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($patients)) : ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient Name</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date of Birth</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Age</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Gender</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($patients as $patient) : ?>
                <?php
                  $birthDate = !empty($patient['date_of_birth']) ? new \DateTime($patient['date_of_birth']) : null;
                  $today = new \DateTime();
                  $age = $birthDate ? $today->diff($birthDate)->y : 'N/A';
                  $ageMonths = $birthDate ? $today->diff($birthDate)->m : 0;
                ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <strong><?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?></strong>
                    <br><small style="color:#6b7280">ID: <?= esc($patient['patient_id'] ?? 'N/A') ?></small>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= $birthDate ? esc($birthDate->format('M j, Y')) : 'N/A' ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php if ($age !== 'N/A'): ?>
                      <?= esc($age) ?> year<?= $age != 1 ? 's' : '' ?>
                      <?php if ($age < 2 && $ageMonths > 0): ?>
                        <?= esc($ageMonths) ?> month<?= $ageMonths != 1 ? 's' : '' ?>
                      <?php endif; ?>
                    <?php else: ?>
                      N/A
                    <?php endif; ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <a href="<?= site_url('doctor/patients/view/' . $patient['id']) ?>" 
                       style="padding:6px 12px;background:#3b82f6;color:white;text-decoration:none;border-radius:6px;font-size:0.875rem;display:inline-block">
                      <i class="fa-solid fa-eye" style="margin-right:4px"></i>View Patient
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else : ?>
          <div style="padding:40px;text-align:center;color:#6b7280">
            <i class="fa-solid fa-syringe" style="font-size:3rem;opacity:0.3;margin-bottom:12px;display:block"></i>
            <p style="margin:0;font-size:1rem;font-weight:500">No pediatric patients found</p>
            <p style="margin:8px 0 0 0;font-size:0.875rem">Pediatric patients (under 18 years) assigned to you will appear here.</p>
            <a href="<?= site_url('doctor/patients') ?>" 
               style="display:inline-block;margin-top:16px;padding:10px 20px;background:#3b82f6;color:white;text-decoration:none;border-radius:8px">
              View All Patients
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Vaccination Schedule Reference</h2>
      </div>
      <div class="panel-body" style="padding:20px">
        <div style="background:#fef3c7;border-left:4px solid #f59e0b;padding:16px;border-radius:8px;margin-bottom:16px">
          <h3 style="margin:0 0 8px 0;font-size:1rem;color:#92400e"><i class="fa-solid fa-info-circle" style="margin-right:6px"></i>Standard Vaccination Schedule</h3>
          <ul style="margin:8px 0 0 0;padding-left:20px;color:#78350f">
            <li><strong>Birth:</strong> Hepatitis B (1st dose)</li>
            <li><strong>2 months:</strong> DPT, Polio, Hib, PCV, Rotavirus</li>
            <li><strong>4 months:</strong> DPT, Polio, Hib, PCV, Rotavirus</li>
            <li><strong>6 months:</strong> DPT, Polio, Hib, PCV, Rotavirus, Hepatitis B</li>
            <li><strong>12 months:</strong> MMR, Varicella, PCV</li>
            <li><strong>18 months:</strong> DPT, Polio, Hib</li>
            <li><strong>4-6 years:</strong> DPT, Polio, MMR, Varicella</li>
            <li><strong>11-12 years:</strong> Tdap, HPV, Meningococcal</li>
          </ul>
        </div>
        <p style="margin:0;font-size:0.875rem;color:#6b7280">
          <i class="fa-solid fa-lightbulb" style="margin-right:4px"></i>
          Click "View Patient" to access individual patient records and update vaccination history.
        </p>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

