<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Record Vitals</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
  <main class="content" style="max-width:720px;margin:20px auto;padding:16px">
    <h1 style="margin:0 0 12px">Record Vitals</h1>
    <?php if (session('error')): ?>
      <div class="alert alert-error"><?= esc(session('error')) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('nurse/vitals') ?>">
      <?= csrf_field() ?>
      <div class="form-row">
        <label>Patient ID</label>
        <input type="number" name="patient_id" required value="<?= old('patient_id') ?>">
      </div>
      <div class="form-row">
        <label>Appointment ID (optional)</label>
        <input type="number" name="appointment_id" value="<?= old('appointment_id') ?>">
      </div>
      <div class="form-row">
        <label>Doctor ID (optional)</label>
        <input type="number" name="doctor_id" value="<?= old('doctor_id') ?>">
      </div>
      <div class="form-row">
        <label>Vital Signs</label>
        <textarea name="vitals" rows="6" placeholder="BP: 120/80, HR: 78, Temp: 36.8°C, RR: 16, SpO2: 98%" required><?= old('vitals') ?></textarea>
      </div>
      <div style="display:flex;gap:8px;margin-top:10px">
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn" href="<?= site_url('dashboard/nurse') ?>">Cancel</a>
      </div>
    </form>
  </main>
</body></html>
