<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Patient | Hospital Management System</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f2f7fb;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .dash-topbar {
      background: #7ec8e3;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .dash-topbar .menu-btn {
      color: #fff;
      text-decoration: none;
      margin-right: 16px;
      font-weight: 600;
    }

    main.content {
      max-width: 900px;
      margin: 30px auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      padding: 32px 40px;
      border-top: 6px solid #7ec8e3;
    }

    h1 {
      text-align: center;
      color: #1e6f9f;
      font-size: 28px;
      margin-bottom: 24px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .form-section {
      border: 1px solid #c9d9e8;
      padding: 16px;
      border-radius: 12px;
      background: #f9fbfd;
    }

    .form-section h2 {
      font-size: 18px;
      margin-bottom: 12px;
      color: #1e6f9f;
      border-bottom: 1px solid #c9d9e8;
      padding-bottom: 6px;
    }

    label {
      display: flex;
      flex-direction: column;
      font-weight: 600;
      margin-bottom: 12px;
      color: #2f4f6f;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="number"],
    select,
    textarea {
      padding: 10px 14px;
      border: 1px solid #c9d9e8;
      border-radius: 8px;
      font-size: 15px;
      background: #fff;
      transition: all 0.2s ease;
      margin-top: 4px;
    }

    input:focus, select:focus, textarea:focus {
      border-color: #7ec8e3;
      outline: none;
      box-shadow: 0 0 0 3px rgba(126, 200, 227, 0.2);
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
    }

    .alert {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 12px;
      font-weight: 500;
    }

    .alert-error {
      background: #ffe5e5;
      color: #b40000;
      border: 1px solid #f5b5b5;
    }

    .alert-success {
      background: #e6f7ef;
      color: #1b7f46;
      border: 1px solid #a7dfb8;
    }

    .btn {
      background: #7ec8e3;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.1s ease;
      text-decoration: none;
      text-align: center;
    }

    .btn:hover {
      background: #6dbcd9;
      transform: translateY(-1px);
    }

    .btn-secondary {
      background: #e0e6ec;
      color: #333;
    }

    .btn-secondary:hover {
      background: #d0d9e2;
    }

    @media (max-width: 600px) {
      main.content {
        padding: 24px;
      }
    }
  </style>
</head>
<body>

<header class="dash-topbar">
  <a href="<?= site_url('dashboard/receptionist') ?>" class="menu-btn">← Back</a>
  <small>Room Management</small>
</header>

<main class="content">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>
  
  <?php
    // This is a temporary script to create rooms.php
    $roomsFile = __DIR__ . '/rooms.php';
    if (!file_exists($roomsFile)) {
        $template = file_get_contents(__DIR__ . '/patient_lookup.php');
        // Replace content for room management
        $roomsContent = str_replace('Patient Lookup', 'Room Management', $template);
        $roomsContent = str_replace('class="active" aria-current="page">Patient Lookup</a>', 'class="active" aria-current="page">Room Management</a>', $roomsContent);
        $roomsContent = str_replace('>Patient Lookup</a>', '>Room Management</a>', $roomsContent);
        $roomsContent = str_replace('Search Patients', 'Room List', $roomsContent);
        file_put_contents($roomsFile, $roomsContent);
        echo '<div style="color:green">rooms.php created successfully!</div>';
    }
  ?>

  <form method="post" action="<?= site_url('reception/patients') ?>">
    <?= csrf_field() ?>

    <!-- Basic Information -->
    <div class="form-section">
      <h2>Basic Information</h2>
      <div class="grid">
        <label>First Name
          <input type="text" name="first_name" value="<?= old('first_name') ?>" required>
        </label>
        <label>Last Name
          <input type="text" name="last_name" value="<?= old('last_name') ?>" required>
        </label>
        <label>Date of Birth
          <input type="date" name="date_of_birth" value="<?= old('date_of_birth') ?>" required>
        </label>
        <label>Gender
          <select name="gender" required>
            <option value="">Select</option>
            <option value="male" <?= old('gender')=='male'?'selected':'' ?>>Male</option>
            <option value="female" <?= old('gender')=='female'?'selected':'' ?>>Female</option>
            <option value="other" <?= old('gender')=='other'?'selected':'' ?>>Other</option>
          </select>
        </label>
        <label>Phone
          <input type="text" name="phone" value="<?= old('phone') ?>" required>
        </label>
        <label>Email
          <input type="email" name="email" value="<?= old('email') ?>">
        </label>
      </div>
    </div>

    <!-- Address Information -->
    <div class="form-section">
      <h2>Address Details</h2>
      <label>Street Address
        <input type="text" name="address_street" value="<?= old('address_street') ?>">
      </label>
      <div class="grid">
        <label>City
          <input type="text" name="city" value="<?= old('city') ?>">
        </label>
        <label>State/Province
          <input type="text" name="state" value="<?= old('state') ?>">
        </label>
        <label>Postal Code
          <input type="text" name="postal_code" value="<?= old('postal_code') ?>">
        </label>
        <label>Country
          <input type="text" name="country" value="<?= old('country') ?>">
        </label>
      </div>
    </div>

    <!-- Medical Information -->
    <div class="form-section">
      <h2>Medical Information</h2>
      <div class="grid">
        <label>Blood Type
          <select name="blood_type">
            <option value="">Select</option>
            <option value="A+" <?= old('blood_type')=='A+'?'selected':'' ?>>A+</option>
            <option value="A-" <?= old('blood_type')=='A-'?'selected':'' ?>>A-</option>
            <option value="B+" <?= old('blood_type')=='B+'?'selected':'' ?>>B+</option>
            <option value="B-" <?= old('blood_type')=='B-'?'selected':'' ?>>B-</option>
            <option value="AB+" <?= old('blood_type')=='AB+'?'selected':'' ?>>AB+</option>
            <option value="AB-" <?= old('blood_type')=='AB-'?'selected':'' ?>>AB-</option>
            <option value="O+" <?= old('blood_type')=='O+'?'selected':'' ?>>O+</option>
            <option value="O-" <?= old('blood_type')=='O-'?'selected':'' ?>>O-</option>
          </select>
        </label>
        <label>Known Allergies
          <textarea name="allergies" rows="2"><?= old('allergies') ?></textarea>
        </label>
        <label>Chronic Conditions
          <textarea name="chronic_conditions" rows="2"><?= old('chronic_conditions') ?></textarea>
        </label>
        <label>Current Medications
          <textarea name="medications" rows="2"><?= old('medications') ?></textarea>
        </label>
      </div>
    </div>

    <!-- Emergency Contact -->
    <div class="form-section">
      <h2>Emergency Contact</h2>
      <div class="grid">
        <label>Contact Name
          <input type="text" name="emergency_name" value="<?= old('emergency_name') ?>">
        </label>
        <label>Relationship
          <input type="text" name="emergency_relation" value="<?= old('emergency_relation') ?>">
        </label>
        <label>Phone
          <input type="text" name="emergency_phone" value="<?= old('emergency_phone') ?>">
        </label>
      </div>
    </div>

    <!-- Insurance Information -->
    <div class="form-section">
      <h2>Insurance Information</h2>
      <div class="grid">
        <label>Insurance Provider
          <input type="text" name="insurance_provider" value="<?= old('insurance_provider') ?>">
        </label>
        <label>Policy Number
          <input type="text" name="insurance_policy_number" value="<?= old('insurance_policy_number') ?>">
        </label>
      </div>
    </div>

    <!-- Admission Information -->
    <div class="form-section">
      <h2>Admission Information</h2>
      <div class="grid">
        <label>Admission Type
          <select name="admission_type" id="admission_type" onchange="toggleRoomAssignment()">
            <option value="checkup" <?= old('admission_type')=='checkup'?'selected':'' ?>>Check-up Only</option>
            <option value="admission" <?= old('admission_type')=='admission'?'selected':'' ?>>Admit Patient</option>
          </select>
        </label>
        <label>Date of Birth
          <input type="date" name="date_of_birth" value="<?= old('date_of_birth') ?>" onchange="calculateAge()">
        </label>
      </div>
      
      <!-- Room Assignment (shown only for admission) -->
      <div id="room_assignment" style="display: <?= old('admission_type')=='admission' ? 'block' : 'none' ?>; margin-top: 20px;">
        <h3>Room Assignment</h3>
        <div class="grid">
          <label>Available Rooms
            <select name="assigned_room_id" id="room_select">
              <option value="">Select a room</option>
            </select>
          </label>
        </div>
        <div id="room_details" style="margin-top: 10px; padding: 10px; background: #f5f5f5; border-radius: 4px; display: none;">
          <small id="room_info"></small>
        </div>
      </div>
    </div>

    <!-- Buttons -->
    <div style="display:flex;gap:12px;margin-top:16px;">
      <button type="submit" class="btn">💾 Save Patient</button>
      <a class="btn btn-secondary" href="<?= site_url('dashboard/receptionist') ?>">Cancel</a>
    </div>

  </form>
</main>
</body>
</html>
