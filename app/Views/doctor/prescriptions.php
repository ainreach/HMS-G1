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
                    $summary = $txt;
                    if ($txt) {
                      $decoded = json_decode($txt, true);
                      if (is_array($decoded)) {
                        $parts = [];
                        foreach ($decoded as $item) {
                          if (is_array($item)) {
                            $parts[] = trim(($item['drug'] ?? $item['name'] ?? 'Medicine') . ' ' . ($item['dose'] ?? ''));
                          }
                        }
                        if (!empty($parts)) {
                          $summary = implode(', ', $parts);
                        }
                      }
                    }
                  ?>
                  <?= esc($summary ?: '—') ?>
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


