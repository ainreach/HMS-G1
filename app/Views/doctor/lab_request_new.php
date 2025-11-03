<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>New Lab Test Request</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem">Request Lab Test</h1>
</div></header>
<main class="content" style="max-width:820px;margin:20px auto">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= site_url('doctor/lab-requests') ?>">
    <?= csrf_field() ?>
    <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>Patient ID
        <input type="number" name="patient_id" value="<?= old('patient_id') ?>" required>
      </label>
      <label>Test Category
        <select name="test_category" required>
          <option value="">Select</option>
          <option value="blood">Blood</option>
          <option value="urine">Urine</option>
          <option value="imaging">Imaging</option>
          <option value="pathology">Pathology</option>
          <option value="microbiology">Microbiology</option>
          <option value="other">Other</option>
        </select>
      </label>
      <label>Test Name
        <input type="text" name="test_name" value="<?= old('test_name') ?>" required>
      </label>
      <label>Priority
        <select name="priority">
          <option value="routine">Routine</option>
          <option value="urgent">Urgent</option>
          <option value="stat">STAT</option>
        </select>
      </label>
    </div>
    <label>Notes
      <textarea name="notes" rows="3"><?= old('notes') ?></textarea>
    </label>
    <div style="margin-top:12px;display:flex;gap:10px">
      <button type="submit" class="btn btn-primary">Submit</button>
      <a class="btn" href="<?= site_url('dashboard/doctor') ?>">Cancel</a>
    </div>
  </form>
</main>
</body></html>
