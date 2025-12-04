<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Patient (Doctor)</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar">
  <div class="topbar-inner">
    <a href="<?= site_url('dashboard/doctor') ?>" class="menu-btn"><i class="fa-solid fa-arrow-left"></i></a>
    <h1 style="margin:0;font-size:1.1rem">Patient Registration</h1>
  </div>
</header>

<main class="content" style="max-width:1000px;margin:20px auto">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('doctor/patients') ?>" class="form">
    <?= csrf_field() ?>

    <!-- Patient Type Selection -->
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1rem">Patient Type Selection</h2>
      </div>
      <div class="panel-body" style="display:flex;gap:1rem;flex-wrap:wrap">
        <label style="display:flex;align-items:center;gap:.5rem">
          <input type="radio" name="admission_type" value="checkup" <?= old('admission_type','checkup')==='checkup' ? 'checked' : '' ?>>
          <span>Outpatient (check-up / consultation)</span>
        </label>
        <label style="display:flex;align-items:center;gap:.5rem">
          <input type="radio" name="admission_type" value="admission" <?= old('admission_type')==='admission' ? 'checked' : '' ?>>
          <span>Inpatient (admission)</span>
        </label>
      </div>
    </section>

    <!-- Personal Information -->
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1rem">Personal Information</h2>
      </div>
      <div class="panel-body">
        <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
          <label>First Name *
            <input type="text" name="first_name" value="<?= old('first_name') ?>" required>
          </label>
          <label>Middle Name
            <input type="text" name="middle_name" value="<?= old('middle_name') ?>">
          </label>
          <label>Last Name *
            <input type="text" name="last_name" value="<?= old('last_name') ?>" required>
          </label>
          <label>Date of Birth *
            <input type="date" name="date_of_birth" value="<?= old('date_of_birth') ?>" required>
          </label>
          <label>Gender
            <select name="gender">
              <option value="">Select gender</option>
              <option value="male" <?= old('gender')==='male' ? 'selected' : '' ?>>Male</option>
              <option value="female" <?= old('gender')==='female' ? 'selected' : '' ?>>Female</option>
              <option value="other" <?= old('gender')==='other' ? 'selected' : '' ?>>Other</option>
            </select>
          </label>
          <label>Blood Type
            <select name="blood_type">
              <option value="">Select blood type</option>
              <?php $bloodTypes = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
              foreach ($bloodTypes as $bt): ?>
                <option value="<?= $bt ?>" <?= old('blood_type')===$bt ? 'selected' : '' ?>><?= $bt ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        </div>
      </div>
    </section>

    <!-- Contact Information -->
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1rem">Contact Information</h2>
      </div>
      <div class="panel-body">
        <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
          <label>City
            <input type="text" name="city" value="<?= old('city') ?>">
          </label>
          <label>Phone
            <input type="text" name="phone" value="<?= old('phone') ?>">
          </label>
          <label>Email
            <input type="email" name="email" value="<?= old('email') ?>">
          </label>
        </div>
        <label style="margin-top:1rem;display:block">Address
          <textarea name="address" rows="2"><?= old('address') ?></textarea>
        </label>
      </div>
    </section>

    <!-- Emergency Contact -->
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1rem">Emergency Contact</h2>
      </div>
      <div class="panel-body">
        <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
          <label>Contact Person
            <input type="text" name="emergency_contact_name" value="<?= old('emergency_contact_name') ?>">
          </label>
          <label>Relationship
            <input type="text" name="emergency_contact_relation" value="<?= old('emergency_contact_relation') ?>">
          </label>
          <label>Cellphone Number
            <input type="text" name="emergency_contact_phone" value="<?= old('emergency_contact_phone') ?>">
          </label>
        </div>
      </div>
    </section>

    <!-- Insurance Information -->
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1rem">Insurance Information</h2>
      </div>
      <div class="panel-body">
        <div class="grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
          <label>Insurance Provider
            <input type="text" name="insurance_provider" value="<?= old('insurance_provider') ?>">
          </label>
          <label>Policy / Insurance Number
            <input type="text" name="insurance_number" value="<?= old('insurance_number') ?>">
          </label>
        </div>
      </div>
    </section>

    <!-- Initial Medical Notes -->
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1rem">Initial Medical Notes</h2>
      </div>
      <div class="panel-body">
        <label>Allergies
          <textarea name="allergies" rows="2"><?= old('allergies') ?></textarea>
        </label>
        <label>Medical History / Initial Notes
          <textarea name="medical_history" rows="3"><?= old('medical_history') ?></textarea>
        </label>
      </div>
    </section>

    <div style="margin-top:1rem;display:flex;gap:.75rem;justify-content:flex-end">
      <a class="btn" href="<?= site_url('dashboard/doctor') ?>">Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i>&nbsp;Save Patient</button>
    </div>
  </form>
</main>

</body>
</html>
