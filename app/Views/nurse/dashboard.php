<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nurse Dashboard</title>
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
  <a href="<?= site_url('dashboard/nurse') ?>" class="active" aria-current="page"><i class="fa-solid fa-house-medical" style="margin-right:8px"></i>Overview</a>
  <a href="<?= site_url('nurse/ward-patients') ?>" data-feature="ehr"><i class="fa-solid fa-bed-pulse" style="margin-right:8px"></i>Ward Patients</a>
  <a href="<?= site_url('nurse/lab-samples') ?>" data-feature="laboratory"><i class="fa-solid fa-flask" style="margin-right:8px"></i>Lab Samples</a>
  <a href="<?= site_url('nurse/lab-request') ?>" data-feature="laboratory"><i class="fa-solid fa-vial" style="margin-right:8px"></i>New Lab Request</a>
  <a href="<?= site_url('nurse/treatment-updates') ?>" data-feature="ehr"><i class="fa-solid fa-notes-medical" style="margin-right:8px"></i>Treatment Updates</a>
  <a href="<?= site_url('nurse/vitals/new') ?>" data-feature="ehr"><i class="fa-solid fa-heart-pulse" style="margin-right:8px"></i>Record Vitals</a>
</nav></aside>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Active Patients</span><i class="fa-solid fa-bed-pulse" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($activePatients ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Pending Tasks</span><i class="fa-solid fa-list-check" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($pendingTasks ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Vitals Recorded</span><i class="fa-solid fa-heartbeat" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($vitalsRecorded ?? 0) ?></div></article>
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>Lab Samples</span><i class="fa-solid fa-vial" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($pendingLabSamples ?? 0) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a class="btn" href="<?= site_url('nurse/vitals/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Record Vitals</a>
        <a class="btn" href="<?= site_url('nurse/notes/new') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Update Care Notes</a>
        <a class="btn" href="<?= site_url('nurse/ward-patients') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Ward Patients</a>
        <a class="btn" href="<?= site_url('nurse/lab-samples') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Lab Samples</a>
        <a class="btn" href="<?= site_url('nurse/lab-request') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none"><i class="fa-solid fa-vial" style="margin-right:4px"></i>New Lab Request</a>
        <a class="btn" href="<?= site_url('nurse/treatment-updates') ?>" style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Treatment Updates</a>
      </div>
    </section>

    <!-- Pending Admissions Section -->
    <?php if (!empty($pendingAdmissions)): ?>
    <section class="panel" style="margin-top:16px">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <div style="display:flex;align-items:center;gap:8px">
          <i class="fa-solid fa-hospital" style="color:#000000;font-size:1.1rem"></i>
          <i class="fa-solid fa-user" style="color:#000000;font-size:1.1rem"></i>
          <h2 style="margin:0;font-size:1.1rem;color:#000000">Pending Admissions</h2>
        </div>
        <a href="<?= site_url('nurse/pending-admissions') ?>" style="background:#000000;color:white;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:0.875rem;font-weight:600;display:inline-flex;align-items:center;gap:6px">
          View All <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
      <div class="panel-body" style="padding:16px">
        <div style="display:flex;flex-direction:column;gap:12px">
          <?php foreach ($pendingAdmissions as $admission): 
            $fullName = trim(($admission['first_name'] ?? '') . ' ' . ($admission['last_name'] ?? ''));
            $doctorName = $admission['doctor_name'] ?? 'N/A';
            $consultationDate = $admission['consultation_date'] ?? $admission['created_at'] ?? date('Y-m-d');
            if ($consultationDate) {
              try {
                $consultationDate = date('M d, Y', strtotime($consultationDate));
              } catch (\Exception $e) {
                $consultationDate = date('M d, Y');
              }
            }
          ?>
            <div style="background:#ffffff;border:1px solid #000000;border-left:4px solid #000000;border-radius:6px;padding:16px;display:flex;justify-content:space-between;align-items:center">
              <div style="flex:1">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                  <i class="fa-solid fa-user" style="color:#000000;font-size:0.875rem"></i>
                  <strong style="color:#000000;font-size:1rem"><?= esc($fullName) ?></strong>
                </div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;color:#000000;font-size:0.875rem">
                  <i class="fa-solid fa-user-doctor" style="color:#000000;font-size:0.875rem"></i>
                  <span>Doctor: <?= esc($doctorName) ?></span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;color:#000000;font-size:0.875rem">
                  <i class="fa-solid fa-calendar" style="color:#000000;font-size:0.875rem"></i>
                  <span>Consultation: <?= esc($consultationDate) ?></span>
                </div>
              </div>
              <?php if (($admission['room_number'] ?? 'Not assigned') === 'Not assigned'): ?>
                <a href="<?= site_url('nurse/admit-patient/' . $admission['id']) ?>" style="background:#000000;color:white;padding:10px 20px;border-radius:6px;text-decoration:none;font-size:0.875rem;font-weight:600;display:inline-flex;align-items:center;gap:6px;white-space:nowrap">
                  <i class="fa-solid fa-bed-pulse"></i> Admit Patient
                </a>
              <?php else: ?>
                <span style="color:#10b981;font-weight:600;font-size:0.875rem">Already Admitted</span>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">My Weekly Schedule</h2></div>
      <div class="panel-body" style="overflow:auto">
        <?php
          $dayNames = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
          ];
        ?>
        <?php if (!empty($schedule)) : ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Day</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Start</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">End</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($schedule as $row) : ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc($dayNames[strtolower($row['day_of_week'] ?? '')] ?? ucfirst($row['day_of_week'] ?? '')) ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc(date('g:i A', strtotime($row['start_time'] ?? '00:00:00'))) ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?= esc(date('g:i A', strtotime($row['end_time'] ?? '00:00:00'))) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else : ?>
          <p style="padding:8px;color:#6b7280;">No schedule defined. Please contact the administrator.</p>
        <?php endif; ?>
      </div>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Ward Tasks</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Task</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Due</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($appointments)) : foreach ($appointments as $appointment) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['first_name'] . ' ' . $appointment['last_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['reason'] ?: 'Check-up') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($appointment['appointment_time']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucfirst($appointment['status'] ?: 'Scheduled')) ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="4" style="padding:10px;color:#6b7280;text-align:center">No appointments today.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem;">
      <!-- Recent Vital Signs -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Recent Vital Signs</h2>
        </div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Vitals</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recentVitals)) : foreach ($recentVitals as $vital) : 
                $vitals = json_decode($vital['vital_signs'] ?? '{}', true);
                $vitalDisplay = [];
                if (!empty($vitals['blood_pressure'])) $vitalDisplay[] = 'BP: ' . $vitals['blood_pressure'];
                if (!empty($vitals['temperature'])) $vitalDisplay[] = 'Temp: ' . $vitals['temperature'] . '°C';
                if (!empty($vitals['pulse'])) $vitalDisplay[] = 'Pulse: ' . $vitals['pulse'];
                $vitalText = !empty($vitalDisplay) ? implode(', ', $vitalDisplay) : 'N/A';
              ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($vital['first_name'] . ' ' . $vital['last_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('M j, H:i', strtotime($vital['visit_date']))) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6;font-size:0.875rem"><?= esc($vitalText) ?></td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="3" style="padding:10px;color:#6b7280;text-align:center">No recent vitals recorded.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Pending Lab Samples -->
      <section class="panel">
        <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
          <h2 style="margin:0;font-size:1.1rem">Pending Lab Samples</h2>
          <a href="<?= site_url('nurse/lab-samples') ?>" style="padding:6px 12px;background:#3b82f6;color:white;border-radius:6px;text-decoration:none;font-size:0.875rem;font-weight:600;display:inline-flex;align-items:center;gap:6px">
            <i class="fa-solid fa-flask"></i> View All Samples
          </a>
        </div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Test</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Priority</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($pendingSamples)) : foreach ($pendingSamples as $sample) : 
                $currentStatus = trim($sample['status'] ?? '');
                $requiresSpecimen = (int)($sample['requires_specimen'] ?? 0);
                $accountantApproved = (int)($sample['accountant_approved'] ?? 0);
                $testId = (int)($sample['id'] ?? 0);
              ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($sample['first_name'] . ' ' . $sample['last_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($sample['test_name'] ?? 'N/A') ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(ucfirst($sample['priority'] ?: 'Routine')) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php if ($currentStatus === 'requested'): ?>
                      <span style="padding:4px 8px;background:#fef3c7;color:#92400e;border-radius:4px;font-size:0.75rem;font-weight:600">
                        <i class="fas fa-clock"></i> Ready to Collect
                      </span>
                    <?php elseif ($currentStatus === 'sample_collected'): ?>
                      <span style="padding:4px 8px;background:#d1fae5;color:#065f46;border-radius:4px;font-size:0.75rem;font-weight:600">
                        <i class="fas fa-check-circle"></i> Collected
                      </span>
                    <?php else: ?>
                      <span style="padding:4px 8px;background:#e5e7eb;color:#374151;border-radius:4px;font-size:0.75rem">
                        <?= esc(ucfirst(str_replace('_', ' ', $currentStatus ?: 'pending'))) ?>
                      </span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php if ($currentStatus === 'requested' && $requiresSpecimen == 1): ?>
                      <?php if ($accountantApproved == 1): ?>
                        <!-- Approved - can mark as collected -->
                      <form action="<?= site_url('nurse/lab-samples/collect/' . $testId) ?>" method="post" style="display:inline">
                        <?= csrf_field() ?>
                        <button type="submit" style="background:#10b981;color:white;padding:6px 12px;border:none;border-radius:6px;cursor:pointer;font-size:0.75rem;font-weight:600;display:inline-flex;align-items:center;gap:4px" onclick="return confirm('Mark this sample as collected? This will send it to the lab for processing.')">
                          <i class="fas fa-check-circle"></i> Mark as Collected
                        </button>
                      </form>
                      <?php else: ?>
                        <!-- Not approved yet - show waiting message -->
                        <span style="color:#f59e0b;font-size:0.75rem;display:inline-flex;align-items:center;gap:4px">
                          <i class="fas fa-clock"></i> Waiting for Approval
                        </span>
                        <br><small style="color:#6b7280;font-size:0.7rem">Needs accountant approval first</small>
                      <?php endif; ?>
                    <?php elseif ($currentStatus === 'sample_collected'): ?>
                      <span style="color:#10b981;font-weight:600;font-size:0.75rem;display:inline-flex;align-items:center;gap:4px">
                        <i class="fas fa-check-circle"></i> Collected
                      </span>
                      <br><small style="color:#6b7280;font-size:0.7rem">Sent to lab</small>
                    <?php else: ?>
                      <span style="color:#6b7280;font-size:0.75rem">
                        <?php if ($requiresSpecimen == 0): ?>
                          <small style="color:#6b7280;font-size:0.7rem">No specimen required</small>
                        <?php else: ?>
                          <small style="color:#6b7280;font-size:0.7rem">Status: <?= esc(ucfirst(str_replace('_', ' ', $currentStatus ?: 'unknown'))) ?></small>
                        <?php endif; ?>
                      </span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr>
                  <td colspan="5" style="padding:20px;color:#6b7280;text-align:center">
                    <div style="margin-bottom:8px">
                      <i class="fa-solid fa-flask" style="font-size:2rem;color:#d1d5db"></i>
                    </div>
                    <div style="font-weight:600;margin-bottom:4px">No pending samples</div>
                    <div style="font-size:0.875rem">Go to <a href="<?= site_url('nurse/lab-samples') ?>" style="color:#3b82f6;text-decoration:underline">Lab Samples</a> to view all samples and mark as collected.</div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
