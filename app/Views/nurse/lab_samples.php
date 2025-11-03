<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lab Samples - Nurse Dashboard</title>
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
  <a href="<?= site_url('nurse/ward-patients') ?>" data-feature="ehr"><i class="fa-solid fa-bed-pulse" style="margin-right:8px"></i>Ward Patients</a>
  <a href="<?= site_url('nurse/lab-samples') ?>" class="active" aria-current="page" data-feature="laboratory"><i class="fa-solid fa-flask" style="margin-right:8px"></i>Lab Samples</a>
  <a href="<?= site_url('nurse/treatment-updates') ?>" data-feature="ehr"><i class="fa-solid fa-notes-medical" style="margin-right:8px"></i>Treatment Updates</a>
  <a href="<?= site_url('nurse/vitals/new') ?>" data-feature="ehr"><i class="fa-solid fa-heart-pulse" style="margin-right:8px"></i>Record Vitals</a>
</nav></aside>
  <main class="content">
    <h1 style="font-size:1.5rem;margin-bottom:1rem">Lab Samples Collection</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div style="padding:12px;background:#d4edda;border:1px solid #c3e6cb;border-radius:6px;margin-bottom:16px;color:#155724">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <section class="panel">
        <div class="panel-body" style="overflow:auto">
                <table class="table" style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Test ID</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Patient</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Test Type</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Requested Date</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Status</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($samples as $sample): ?>
                        <tr>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb">#<?= $sample['id'] ?></td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                <?= $sample['first_name'] . ' ' . $sample['last_name'] ?>
                                <small style="display:block;color:#6b7280">ID: <?= $sample['patient_code'] ?></small>
                            </td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= $sample['test_type'] ?? 'N/A' ?></td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= date('M d, Y H:i', strtotime($sample['requested_date'])) ?></td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                <span style="padding:4px 8px;background:#fef3c7;color:#92400e;border-radius:4px;font-size:0.875rem"><?= ucfirst(str_replace('_', ' ', $sample['status'])) ?></span>
                            </td>
                            <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                <?php if ($sample['status'] === 'requested'): ?>
                                    <form action="<?= site_url('nurse/lab-samples/' . $sample['id'] . '/collect') ?>" method="post" style="display:inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" style="background:#10b981;color:white;padding:6px 12px;border:none;border-radius:6px;cursor:pointer;font-size:0.875rem" onclick="return confirm('Mark this sample as collected?')">
                                            <i class="fas fa-check"></i> Mark Collected
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="color:#6b7280">Collected</span>
                                <?php endif; ?>
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
