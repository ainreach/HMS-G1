<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add New Patient - Admin Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .panel { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; overflow: hidden; }
    .panel-head { padding: 1rem 1.25rem; border-bottom: 1px solid #e5e7eb; background-color: #f9fafb; }
    .panel-body { padding: 1.25rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .btn { display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s; border: none; cursor: pointer; }
    .btn:hover { background-color: #2563eb; }
    .btn-secondary { background-color: #6b7280; }
    .btn-secondary:hover { background-color: #4b5563; }
    .btn i { margin-right: 0.5rem; }
    .alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
    .alert-error { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
  </style>
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('admin/patients') ?>" class="menu-btn" aria-label="Back to Patients">
      <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Add New Patient</h1>
        <small>Register a new patient in the system</small>
      </div>
    </div>
    <div class="top-right">
      <span class="role">
        <i class="fa-regular fa-user"></i>
        <?= esc(session('username') ?? 'Admin') ?>
      </span>
      <a href="<?= site_url('logout') ?>" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/admin') ?>">
        <i class="fas fa-tachometer-alt"></i> Overview
      </a>
      <a href="<?= site_url('admin/patients') ?>" class="active">
        <i class="fas fa-user-injured"></i> Patients
      </a>
      <a href="<?= site_url('admin/users') ?>">
        <i class="fas fa-users-cog"></i> Users
      </a>
      <a href="<?= site_url('admin/appointments') ?>">
        <i class="fas fa-calendar-check"></i> Appointments
      </a>
    </nav>
  </aside>
  
  <main class="content">
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Patient Information</h2>
      </div>
      <div class="panel-body">
        <form method="POST" action="<?= site_url('admin/patients') ?>">
          <div class="form-row">
            <div class="form-group">
              <label for="first_name">First Name *</label>
              <input type="text" id="first_name" name="first_name" value="<?= old('first_name') ?>" required>
            </div>
            <div class="form-group">
              <label for="last_name">Last Name *</label>
              <input type="text" id="last_name" name="last_name" value="<?= old('last_name') ?>" required>
            </div>
          </div>
          
          <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" value="<?= old('middle_name') ?>">
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="date_of_birth">Date of Birth *</label>
              <input type="date" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth') ?>" required>
            </div>
            <div class="form-group">
              <label for="gender">Gender *</label>
              <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="male" <?= old('gender') == 'male' ? 'selected' : '' ?>>Male</option>
                <option value="female" <?= old('gender') == 'female' ? 'selected' : '' ?>>Female</option>
                <option value="other" <?= old('gender') == 'other' ? 'selected' : '' ?>>Other</option>
              </select>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="blood_type">Blood Type</label>
              <select id="blood_type" name="blood_type">
                <option value="">Select Blood Type</option>
                <option value="A+" <?= old('blood_type') == 'A+' ? 'selected' : '' ?>>A+</option>
                <option value="A-" <?= old('blood_type') == 'A-' ? 'selected' : '' ?>>A-</option>
                <option value="B+" <?= old('blood_type') == 'B+' ? 'selected' : '' ?>>B+</option>
                <option value="B-" <?= old('blood_type') == 'B-' ? 'selected' : '' ?>>B-</option>
                <option value="AB+" <?= old('blood_type') == 'AB+' ? 'selected' : '' ?>>AB+</option>
                <option value="AB-" <?= old('blood_type') == 'AB-' ? 'selected' : '' ?>>AB-</option>
                <option value="O+" <?= old('blood_type') == 'O+' ? 'selected' : '' ?>>O+</option>
                <option value="O-" <?= old('blood_type') == 'O-' ? 'selected' : '' ?>>O-</option>
              </select>
            </div>
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" value="<?= old('phone') ?>">
            </div>
          </div>
          
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>">
          </div>
          
          <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3"><?= old('address') ?></textarea>
          </div>
          
          <div class="form-group">
            <label for="city">City</label>
            <input type="text" id="city" name="city" value="<?= old('city') ?>">
          </div>
          
          <h3 style="margin: 2rem 0 1rem 0; color: #374151; font-size: 1.1rem;">Emergency Contact</h3>
          
          <div class="form-row">
            <div class="form-group">
              <label for="emergency_contact_name">Emergency Contact Name</label>
              <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?= old('emergency_contact_name') ?>">
            </div>
            <div class="form-group">
              <label for="emergency_contact_phone">Emergency Contact Phone</label>
              <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="<?= old('emergency_contact_phone') ?>">
            </div>
          </div>
          
          <div class="form-group">
            <label for="emergency_contact_relation">Relationship</label>
            <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" value="<?= old('emergency_contact_relation') ?>" placeholder="e.g., Spouse, Parent, Sibling">
          </div>
          
          <h3 style="margin: 2rem 0 1rem 0; color: #374151; font-size: 1.1rem;">Insurance Information</h3>
          
          <div class="form-row">
            <div class="form-group">
              <label for="insurance_provider">Insurance Provider</label>
              <input type="text" id="insurance_provider" name="insurance_provider" value="<?= old('insurance_provider') ?>">
            </div>
            <div class="form-group">
              <label for="insurance_number">Insurance Number</label>
              <input type="text" id="insurance_number" name="insurance_number" value="<?= old('insurance_number') ?>">
            </div>
          </div>
          
          <h3 style="margin: 2rem 0 1rem 0; color: #374151; font-size: 1.1rem;">Medical Information</h3>
          
          <div class="form-group">
            <label for="allergies">Allergies</label>
            <textarea id="allergies" name="allergies" rows="3" placeholder="List any known allergies"><?= old('allergies') ?></textarea>
          </div>
          
          <div class="form-group">
            <label for="medical_history">Medical History</label>
            <textarea id="medical_history" name="medical_history" rows="3" placeholder="Previous medical conditions, surgeries, etc."><?= old('medical_history') ?></textarea>
          </div>
          
          <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn">
              <i class="fas fa-save"></i> Create Patient
            </button>
            <a href="<?= site_url('admin/patients') ?>" class="btn btn-secondary">
              <i class="fas fa-times"></i> Cancel
            </a>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>
