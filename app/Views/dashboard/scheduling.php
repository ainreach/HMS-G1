<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scheduling</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <style>
    .table th, .table td { padding:8px; border-bottom:1px solid #e5e7eb; text-align:left }
  </style>
 </head><body>
  <main class="content" style="max-width:1000px;margin:20px auto;padding:16px">
    <?php 
      $role = session('role');
      $backUrl = '/dashboard';
      if ($role === 'admin') $backUrl = '/dashboard/admin';
      elseif ($role === 'receptionist') $backUrl = '/dashboard/receptionist';
      elseif ($role === 'doctor') $backUrl = '/dashboard/doctor';
      elseif ($role === 'nurse') $backUrl = '/dashboard/nurse';
      elseif ($role === 'lab_staff') $backUrl = '/dashboard/lab';
      elseif ($role === 'pharmacist') $backUrl = '/dashboard/pharmacist';
      elseif ($role === 'accountant') $backUrl = '/dashboard/accountant';
      elseif ($role === 'it_staff') $backUrl = '/dashboard/it';
    ?>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <div style="display:flex;align-items:center;gap:10px">
        <a class="btn" href="<?= site_url($backUrl) ?>" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Back</a>
        <h1 style="margin:0">Scheduling</h1>
      </div>
      <?php if (in_array($role, ['receptionist','admin'], true)) : ?>
        <a class="btn" href="<?= site_url('reception/appointments/new') ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">New Appointment</a>
      <?php endif; ?>
    </div>

    <div class="panel">
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th>Appt #</th>
              <th>Patient ID</th>
              <th>Doctor ID</th>
              <th>Date</th>
              <th>Time</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($appointments)) : foreach ($appointments as $a) : ?>
              <tr>
                <td><?= esc($a['appointment_number']) ?></td>
                <td><?= esc($a['patient_id']) ?></td>
                <td><?= esc($a['doctor_id']) ?></td>
                <td><?= esc(date('Y-m-d', strtotime($a['appointment_date']))) ?></td>
                <td><?= esc($a['appointment_time']) ?></td>
                <td><?= esc($a['status']) ?></td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="6" style="padding:12px;text-align:center;color:#6b7280">No appointments found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
 </body></html>


