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
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Test Name</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Test Type</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Requested Date</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Status</th>
                            <th style="padding:12px;text-align:left;border-bottom:2px solid #e5e7eb;background:#f9fafb">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($samples)): ?>
                            <?php foreach ($samples as $sample): ?>
                            <?php 
                            // Get status and flags
                            $currentStatus = trim($sample['status'] ?? '');
                            $requiresSpecimen = (int)($sample['requires_specimen'] ?? 0);
                            $accountantApproved = (int)($sample['accountant_approved'] ?? 0);
                            $testId = (int)($sample['id'] ?? 0);
                            ?>
                            <tr>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb">#<?= esc($testId) ?></td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                    <strong><?= esc($sample['first_name'] . ' ' . $sample['last_name']) ?></strong>
                                    <small style="display:block;color:#6b7280;font-size:0.75rem">ID: <?= esc($sample['patient_code'] ?? 'N/A') ?></small>
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                    <strong><?= esc($sample['test_name'] ?? 'N/A') ?></strong>
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc($sample['test_type'] ?? 'N/A') ?></td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb"><?= esc(date('M d, Y H:i', strtotime($sample['requested_date'] ?? 'now'))) ?></td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                    <?php if ($currentStatus === 'requested'): ?>
                                        <span style="padding:4px 8px;background:#fef3c7;color:#92400e;border-radius:4px;font-size:0.875rem;font-weight:600">
                                            <i class="fas fa-clock"></i> Pending Collection
                                        </span>
                                    <?php elseif ($currentStatus === 'sample_collected'): ?>
                                        <span style="padding:4px 8px;background:#d1fae5;color:#065f46;border-radius:4px;font-size:0.875rem;font-weight:600">
                                            <i class="fas fa-check-circle"></i> Collected
                                        </span>
                                    <?php else: ?>
                                        <span style="padding:4px 8px;background:#e5e7eb;color:#374151;border-radius:4px;font-size:0.875rem">
                                            <?= esc(ucfirst(str_replace('_', ' ', $currentStatus ?: 'unknown'))) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding:12px;border-bottom:1px solid #e5e7eb">
                                    <?php if ($currentStatus === 'requested' && $requiresSpecimen == 1): ?>
                                        <?php if ($accountantApproved == 1): ?>
                                            <!-- Approved - can mark as collected -->
                                        <form action="<?= site_url('nurse/lab-samples/collect/' . $testId) ?>" method="post" style="display:inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" style="background:#10b981;color:white;padding:8px 16px;border:none;border-radius:6px;cursor:pointer;font-size:0.875rem;font-weight:600;display:inline-flex;align-items:center;gap:6px" onclick="return confirm('Mark this sample as collected? This will send it to the lab for processing.')">
                                                <i class="fas fa-check-circle"></i> Mark as Collected
                                            </button>
                                        </form>
                                        <?php else: ?>
                                            <!-- Not approved yet - show waiting message -->
                                            <span style="color:#f59e0b;font-weight:600;display:inline-flex;align-items:center;gap:6px">
                                                <i class="fas fa-clock"></i> Waiting for Approval
                                            </span>
                                            <br><small style="color:#6b7280;font-size:0.75rem">Needs accountant approval before collection</small>
                                        <?php endif; ?>
                                    <?php elseif ($currentStatus === 'sample_collected'): ?>
                                        <span style="color:#10b981;font-weight:600;display:inline-flex;align-items:center;gap:6px">
                                            <i class="fas fa-check-circle"></i> Collected
                                        </span>
                                        <br><small style="color:#6b7280;font-size:0.75rem">Sent to lab</small>
                                    <?php else: ?>
                                        <span style="color:#6b7280"><?= ucfirst(str_replace('_', ' ', $currentStatus ?: 'unknown')) ?></span>
                                        <?php if ($requiresSpecimen == 0): ?>
                                            <br><small style="color:#6b7280;font-size:0.75rem">No specimen required</small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="padding:40px;text-align:center">
                                    <div style="margin-bottom:16px">
                                        <i class="fa-solid fa-flask" style="font-size:3rem;color:#d1d5db"></i>
                                    </div>
                                    <h3 style="margin:0 0 8px 0;font-size:1.125rem;color:#374151;font-weight:600">No Lab Samples Available</h3>
                                    <p style="margin:0 0 16px 0;color:#6b7280;font-size:0.875rem">
                                        There are no lab samples ready for collection at this time.
                                    </p>
                                    <div style="background:#f3f4f6;border:1px solid #e5e7eb;border-radius:8px;padding:16px;text-align:left;max-width:600px;margin:0 auto">
                                        <h4 style="margin:0 0 12px 0;font-size:0.875rem;font-weight:600;color:#000000">How to see samples here:</h4>
                                        <ol style="margin:0;padding-left:20px;color:#6b7280;font-size:0.875rem;line-height:1.8">
                                            <li>Doctor or Nurse creates a lab test request</li>
                                            <li>Accountant approves the lab test</li>
                                            <li>If the test requires a specimen, it will appear here with status "Pending Collection"</li>
                                            <li>Click "Mark as Collected" button to collect the sample</li>
                                            <li>After collection, the sample will be sent to the lab for processing</li>
                                        </ol>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
        </div>
    </section>
  </main>
</div>
</body>
</html>
