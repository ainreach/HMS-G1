<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Weekly Schedule</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar">
  <div class="topbar-inner">
    <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn"><i class="fa-solid fa-arrow-left"></i></a>
    <h1 style="margin:0;font-size:1.1rem">Manage Weekly Schedule</h1>
  </div>
</header>

<main class="content" style="max-width:800px;margin:20px auto">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>

  <section class="panel">
    <div class="panel-head">
      <h2 style="margin:0;font-size:1rem">Set Your Clinic Hours</h2>
    </div>
    <div class="panel-body">
      <form method="post" action="<?= site_url('doctor/schedule') ?>" class="form">
        <?= csrf_field() ?>

        <p style="font-size:.9rem;color:#6b7280;margin-bottom:1rem">
          Select the days you are available and the time range for consultations.
        </p>

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
          $currentDays = [];
          if (!empty($schedule)) {
              foreach ($schedule as $row) {
                  $currentDays[] = strtolower($row['day_of_week']);
              }
          }
        ?>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:.5rem;margin-bottom:1rem">
          <?php foreach ($dayNames as $key => $label): ?>
            <label style="display:flex;align-items:center;gap:.35rem;font-size:.9rem">
              <input type="checkbox" name="days_of_week[]" value="<?= $key ?>" <?= in_array($key, $currentDays, true) ? 'checked' : '' ?>>
              <span><?= esc($label) ?></span>
            </label>
          <?php endforeach; ?>
        </div>

        <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem">
          <label>Start Time
            <input type="time" name="start_time" value="<?= old('start_time') ?>" required>
          </label>
          <label>End Time
            <input type="time" name="end_time" value="<?= old('end_time') ?>" required>
          </label>
        </div>

        <div style="margin-top:1.25rem;display:flex;justify-content:flex-end;gap:.75rem">
          <a href="<?= site_url('dashboard/doctor') ?>" class="btn btn-secondary">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-clock"></i>&nbsp;Save Schedule</button>
        </div>
      </form>
    </div>
  </section>

  <section class="panel" style="margin-top:1.5rem">
    <div class="panel-head">
      <h2 style="margin:0;font-size:1rem">Current Weekly Schedule</h2>
    </div>
    <div class="panel-body">
      <?php if (!empty($schedule)): ?>
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Day</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Start</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">End</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($schedule as $row): ?>
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
      <?php else: ?>
        <p style="padding:8px;color:#6b7280;">No schedule defined yet.</p>
      <?php endif; ?>
    </div>
  </section>
</main>

</body>
</html>
