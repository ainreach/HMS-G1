<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Medical Record</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem">New Medical Record</h1>
</div></header>
<main class="content" style="max-width:900px;margin:20px auto">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= site_url('doctor/records') ?>">
    <?= csrf_field() ?>
    <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>Patient ID
        <input type="number" name="patient_id" value="<?= old('patient_id') ?>" required>
      </label>
      <label>Appointment ID
        <input type="number" name="appointment_id" value="<?= old('appointment_id') ?>">
      </label>
      <label>Visit Date/Time
        <input type="datetime-local" name="visit_date" value="<?= old('visit_date') ?>">
      </label>
    </div>
    <label>Chief Complaint
      <textarea name="chief_complaint" rows="2"><?= old('chief_complaint') ?></textarea>
    </label>
    <label>History of Present Illness
      <textarea name="history_present_illness" rows="3"><?= old('history_present_illness') ?></textarea>
    </label>
    <label>Physical Examination
      <textarea name="physical_examination" rows="3"><?= old('physical_examination') ?></textarea>
    </label>
    <label>Vital Signs (JSON)
      <textarea name="vital_signs" rows="2" placeholder='{"bp":"120/80","temp":"36.6"}'><?= old('vital_signs') ?></textarea>
    </label>
    <label>Diagnosis
      <textarea name="diagnosis" rows="2"><?= old('diagnosis') ?></textarea>
    </label>
    <label>Treatment Plan
      <textarea name="treatment_plan" rows="2"><?= old('treatment_plan') ?></textarea>
    </label>
    <label>Medications Prescribed (JSON)
      <textarea name="medications_prescribed" rows="2" placeholder='[{"drug":"Paracetamol","dose":"500mg"}]'><?= old('medications_prescribed') ?></textarea>
    </label>
    <label>Follow-up Instructions
      <textarea name="follow_up_instructions" rows="2"><?= old('follow_up_instructions') ?></textarea>
    </label>
    <label>Next Visit Date
      <input type="date" name="next_visit_date" value="<?= old('next_visit_date') ?>">
    </label>
    <div style="margin-top:12px;display:flex;gap:10px">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn" href="<?= site_url('dashboard/doctor') ?>">Cancel</a>
    </div>
  </form>
</main>
</body></html>
