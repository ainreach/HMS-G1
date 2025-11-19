<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ward Patients - Nurse Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
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
  <a href="<?= site_url('dashboard/nurse') ?>"><i class="fa-solid fa-house-medical" style="margin-right:8px"></i>Overview</a>
  <a href="<?= site_url('nurse/ward-patients') ?>" class="active" aria-current="page" data-feature="ehr"><i class="fa-solid fa-bed-pulse" style="margin-right:8px"></i>Ward Patients</a>
  <a href="<?= site_url('nurse/lab-samples') ?>" data-feature="laboratory"><i class="fa-solid fa-flask" style="margin-right:8px"></i>Lab Samples</a>
  <a href="<?= site_url('nurse/treatment-updates') ?>" data-feature="ehr"><i class="fa-solid fa-notes-medical" style="margin-right:8px"></i>Treatment Updates</a>
  <a href="<?= site_url('nurse/vitals/new') ?>" data-feature="ehr"><i class="fa-solid fa-heart-pulse" style="margin-right:8px"></i>Record Vitals</a>
</nav></aside>
  <main class="content">
    <h1 style="font-size:1.5rem;margin-bottom:1rem">Ward Patients</h1>

    <section class="panel">
        <div class="panel-body" style="overflow:auto">
                <table class="table" style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Patient ID</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Name</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Gender</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Age</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Phone</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Status</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= $patient['patient_code'] ?></td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb"><a href="<?= site_url('nurse/patients/view/' . $patient['id']) ?>"><?= $patient['first_name'] . ' ' . $patient['last_name'] ?></a></td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= ucfirst($patient['gender'] ?? '') ?></td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                <?php 
                                    $dob = new \DateTime($patient['date_of_birth']);
                                    $now = new \DateTime();
                                    $age = $now->diff($dob)->y;
                                    echo $age . ' years';
                                ?>
                            </td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= $patient['phone'] ?? 'N/A' ?></td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                <?php if (!empty($patient['visit_date'])): ?>
                                    <span style="color:#10b981;font-weight:500">Last visit: <?= date('M d, Y', strtotime($patient['visit_date'])) ?></span>
                                <?php else: ?>
                                    <span style="color:#f59e0b;font-weight:500">New Patient</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                <a href="<?= site_url('nurse/patients/view/' . $patient['id']) ?>" style="background:#3498db;color:white;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:0.875rem;display:inline-block;cursor:pointer; margin-right: 5px;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="<?= site_url('nurse/ward-patients/' . $patient['id']) ?>" style="background:#0ea5e9;color:white;padding:6px 12px;border-radius:6px;text-decoration:none;font-size:0.875rem;display:inline-block;cursor:pointer" onmouseover="this.style.background='#0284c7'" onmouseout="this.style.background='#0ea5e9'">
                                    <i class="fas fa-heartbeat"></i> Monitor
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
    </section>
  </main>
</div>
</body>
</html>
