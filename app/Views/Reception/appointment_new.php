<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book Appointment</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/receptionist') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem">Book Appointment</h1>
</div></header>
<main class="content" style="max-width:820px;margin:20px auto">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= site_url('reception/appointments') ?>" class="form">
    <?= csrf_field() ?>
    <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>Patient ID
        <input type="number" min="1" name="patient_id" value="<?= old('patient_id') ?>" required>
      </label>
      <label>Doctor ID
        <input type="number" min="1" name="doctor_id" value="<?= old('doctor_id') ?>" required>
      </label>
      <label>Date
        <input type="date" name="appointment_date" value="<?= old('appointment_date') ?>" required>
      </label>
      <label>Time
        <input type="time" name="appointment_time" value="<?= old('appointment_time') ?>" required>
      </label>
      <label>Duration (minutes)
        <input type="number" name="duration" value="<?= old('duration') ?: 30 ?>" min="5" max="240">
      </label>
      <label>Type
        <select name="type">
          <option value="consultation">Consultation</option>
          <option value="follow_up">Follow-up</option>
          <option value="emergency">Emergency</option>
          <option value="surgery">Surgery</option>
          <option value="checkup">Checkup</option>
        </select>
      </label>
    </div>
    <label>Reason
      <textarea name="reason" rows="3"><?= old('reason') ?></textarea>
    </label>
    <label>Notes
      <textarea name="notes" rows="3"><?= old('notes') ?></textarea>
    </label>
    <div style="margin-top:12px;display:flex;gap:10px">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn" href="<?= site_url('dashboard/receptionist') ?>">Cancel</a>
    </div>
  </form>
</main>
</body></html>
