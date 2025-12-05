<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Patient</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('doctor/patients') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem">Edit Patient</h1>
</div></header>
<main class="content" style="max-width:900px;margin:20px auto">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= site_url('doctor/patients/update/' . $patient['id']) ?>">
    <?= csrf_field() ?>
    <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>First Name <span style="color:#dc2626">*</span>
        <input type="text" name="first_name" value="<?= old('first_name', $patient['first_name'] ?? '') ?>" required>
      </label>
      <label>Last Name <span style="color:#dc2626">*</span>
        <input type="text" name="last_name" value="<?= old('last_name', $patient['last_name'] ?? '') ?>" required>
      </label>
      <label>Middle Name
        <input type="text" name="middle_name" value="<?= old('middle_name', $patient['middle_name'] ?? '') ?>">
      </label>
      <label>Date of Birth <span style="color:#dc2626">*</span>
        <input type="date" name="date_of_birth" value="<?= old('date_of_birth', $patient['date_of_birth'] ?? '') ?>" required>
      </label>
      <label>Gender <span style="color:#dc2626">*</span>
        <select name="gender" required>
          <option value="">Select Gender</option>
          <option value="male" <?= old('gender', $patient['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
          <option value="female" <?= old('gender', $patient['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
          <option value="other" <?= old('gender', $patient['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
        </select>
      </label>
      <label>Blood Type
        <select name="blood_type">
          <option value="">Select Blood Type</option>
          <?php $bt = old('blood_type', $patient['blood_type'] ?? ''); ?>
          <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $type): ?>
            <option value="<?= $type ?>" <?= $bt === $type ? 'selected' : '' ?>><?= $type ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Phone
        <input type="tel" name="phone" value="<?= old('phone', $patient['phone'] ?? '') ?>">
      </label>
      <label>Email
        <input type="email" name="email" value="<?= old('email', $patient['email'] ?? '') ?>">
      </label>
    </div>
    <label>Address
      <textarea name="address" rows="2"><?= old('address', $patient['address'] ?? '') ?></textarea>
    </label>
    <label>City
      <input type="text" name="city" value="<?= old('city', $patient['city'] ?? '') ?>">
    </label>
    <h3 style="margin:1.5rem 0 0.75rem 0">Emergency Contact</h3>
    <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>Emergency Contact Name
        <input type="text" name="emergency_contact_name" value="<?= old('emergency_contact_name', $patient['emergency_contact_name'] ?? '') ?>">
      </label>
      <label>Emergency Contact Phone
        <input type="tel" name="emergency_contact_phone" value="<?= old('emergency_contact_phone', $patient['emergency_contact_phone'] ?? '') ?>">
      </label>
      <label>Relationship
        <input type="text" name="emergency_contact_relation" value="<?= old('emergency_contact_relation', $patient['emergency_contact_relation'] ?? '') ?>" placeholder="e.g., Spouse, Parent">
      </label>
    </div>
    <h3 style="margin:1.5rem 0 0.75rem 0">Insurance Information</h3>
    <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>Insurance Provider
        <input type="text" name="insurance_provider" value="<?= old('insurance_provider', $patient['insurance_provider'] ?? '') ?>">
      </label>
      <label>Insurance Number
        <input type="text" name="insurance_number" value="<?= old('insurance_number', $patient['insurance_number'] ?? '') ?>">
      </label>
    </div>
    <h3 style="margin:1.5rem 0 0.75rem 0">Medical Information</h3>
    <label>Allergies
      <textarea name="allergies" rows="2" placeholder="List any known allergies"><?= old('allergies', $patient['allergies'] ?? '') ?></textarea>
    </label>
    <label>Medical History
      <textarea name="medical_history" rows="3" placeholder="Previous medical conditions, surgeries, etc."><?= old('medical_history', $patient['medical_history'] ?? '') ?></textarea>
    </label>
    <label>Admission Type
      <select name="admission_type">
        <option value="checkup" <?= old('admission_type', $patient['admission_type'] ?? 'checkup') === 'checkup' ? 'selected' : '' ?>>Out-Patient (Check-up Only)</option>
        <option value="admission" <?= old('admission_type', $patient['admission_type'] ?? 'checkup') === 'admission' ? 'selected' : '' ?>>In-Patient (Admit Patient)</option>
      </select>
    </label>
    <div style="margin-top:12px;display:flex;gap:10px">
      <button type="submit" class="btn btn-primary">Update Patient</button>
      <a class="btn" href="<?= site_url('doctor/patients') ?>">Cancel</a>
    </div>
  </form>
</main>
</body></html>

