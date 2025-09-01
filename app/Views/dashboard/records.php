<!DOCTYPE html>
<<<<<<< HEAD
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Records</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
  <main class="content" style="max-width:1000px;margin:20px auto;padding:16px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <h1 style="margin:0">Patient Records</h1>
      <?php $role = session('role'); $createUrl = ($role === 'doctor') ? site_url('doctor/patients/new') : site_url('reception/patients/new'); ?>
      <a class="btn" href="<?= $createUrl ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Create Patient</a>
    </div>

    <div class="panel">
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient ID</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Name</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">DOB</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Phone</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($patients)): foreach ($patients as $p): ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['patient_id'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(($p['last_name'] ?? '').', '.($p['first_name'] ?? '')) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['date_of_birth'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['phone'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <span style="display:inline-block;padding:2px 8px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:.85em;">
                    <?= isset($p['is_active']) && (int)$p['is_active'] === 1 ? 'Active' : 'Inactive' ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="5" style="padding:12px;text-align:center;color:#6b7280">No patients found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body></html>
=======
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration & Records</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <header class="dash-topbar">
    <div class="topbar-inner">
      <button class="menu-btn" id="btnToggle" aria-label="Toggle menu"><i class="fa-solid fa-bars"></i></button>
      <div class="brand">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="FIFTY 50 MEDTECHIE" />
        <div class="brand-text">
          <h2>FIFTY 50 MEDTECHIE</h2>
          <small>"Trust Us... If You’re Feeling Lucky."</small>
        </div>
      </div>
    </div>
  </header>
  <div class="layout">
    <aside class="simple-sidebar" id="sidebar">
      <nav class="side-nav">
        <a href="javascript:void(0)" onclick="goto('dashboard')" data-feature="dashboard">Dashboard</a>
        <a href="javascript:void(0)" onclick="goto('records')" class="active" data-feature="ehr">Registration & Records</a>
        <a href="javascript:void(0)" onclick="goto('scheduling')" data-feature="scheduling">Scheduling</a>
        <a href="javascript:void(0)" onclick="goto('billing')" data-feature="billing">Billing & Payment</a>
        <a href="javascript:void(0)" onclick="goto('laboratory')" data-feature="laboratory">Laboratory & Diagnostic</a>
        <a href="javascript:void(0)" onclick="goto('pharmacy')" data-feature="pharmacy">Pharmacy</a>
        <a href="javascript:void(0)" onclick="goto('reports')" data-feature="reports">Reports & Analytics</a>
      </nav>
    </aside>

    <main class="content">
      <h1 class="page-title">Registration & Records</h1>

      <div class="form-container">
        <h2>Patient Registration</h2>
        <h3>Emergency Contact Details</h3>
        <div class="row">
          <div>
            <label for="ec-name">Full Name</label>
            <input id="ec-name" type="text" placeholder="e.g., Juan Dela Cruz">
          </div>
          <div>
            <label for="ec-rel">Relationship to Patient</label>
            <input id="ec-rel" type="text" placeholder="e.g., Parent, Spouse, Sibling">
          </div>
        </div>
        <div class="row">
          <div>
            <label for="ec-phone">Contact Number</label>
            <input id="ec-phone" type="text" placeholder="e.g., 0917 123 4567">
          </div>
          <div>
            <label for="ec-address">Address</label>
            <input id="ec-address" type="text" placeholder="House No., Street, City, Province">
          </div>
        </div>

        <h3>Insurance Details</h3>
        <div class="row">
          <div>
            <label for="ins-provider">Insurance Provider</label>
            <input id="ins-provider" type="text" placeholder="e.g., PhilHealth / HMO name">
          </div>
          <div>
            <label for="ins-policy">Policy Number</label>
            <input id="ins-policy" type="text" placeholder="e.g., PH-1234-5678">
          </div>
        </div>
        <label for="ins-coverage">Coverage Details</label>
        <input id="ins-coverage" type="text" placeholder="e.g., Inpatient, Outpatient, Laboratory, Pharmacy">

        <h3>Registration Information</h3>
        <div class="row">
          <div>
            <label for="patient-id">Patient ID</label>
            <input id="patient-id" type="text" placeholder="e.g., PT-000123">
          </div>
          <div>
            <label for="reg-datetime">Date and Time</label>
            <input id="reg-datetime" type="datetime-local">
          </div>
        </div>
        <label for="assigned-doc">Assigned Doctor</label>
        <input id="assigned-doc" type="text" placeholder="e.g., Dr. Santos">

        <button class="submit-btn">SUBMIT</button>
      </div>

      <div class="form-container">
        <h2>Electronic Health Records</h2>
        <h3>Past Medical History</h3>
        <div class="row">
          <div>
            <label for="pmh-chronic">Chronic Illnesses</label>
            <input id="pmh-chronic" type="text" placeholder="e.g., Hypertension, Diabetes">
          </div>
          <div>
            <label for="pmh-surgeries">Past Surgeries or Hospitalizations</label>
            <input id="pmh-surgeries" type="text" placeholder="e.g., Appendectomy (2019)">
          </div>
        </div>
        <div class="row">
          <div>
            <label for="pmh-allergies">Allergies</label>
            <input id="pmh-allergies" type="text" placeholder="e.g., Penicillin, Peanuts">
          </div>
          <div>
            <label for="pmh-immunization">Immunization Records</label>
            <input id="pmh-immunization" type="text" placeholder="e.g., Tetanus (2024), Hep B (2023)">
          </div>
        </div>
        <h3>Current Health Information</h3>
        <label for="presenting">Presenting Complaint / Reason for Visit</label>
        <input id="presenting" type="text" placeholder="e.g., Fever and cough for 3 days">
        <h4>Vital Signs</h4>
        <div class="row">
          <input type="text" placeholder="Blood Pressure (mmHg)" aria-label="Blood Pressure">
          <input type="text" placeholder="Heart Rate (bpm)" aria-label="Heart Rate">
        </div>
        <div class="row">
          <input type="text" placeholder="Temperature (°C)" aria-label="Temperature">
          <input type="text" placeholder="Respiratory Rate (cpm)" aria-label="Respiratory Rate">
        </div>
        <label>Current Medications</label>
        <div class="row">
          <input type="text" placeholder="Medication 1">
          <input type="text" placeholder="Medication 2">
        </div>
        <input type="text" placeholder="Medication 3">
        <button class="submit-btn">SUBMIT</button>
      </div>
    </main>
  </div>

  <script>
    window.APP_BASE = '<?= rtrim(site_url('/'), '/') ?>/';
    window.SITE_URL = '<?= rtrim(site_url(), '/') ?>';
  </script>
  <script src="<?= base_url('assets/js/script.js') ?>"></script>
  <script src="<?= base_url('assets/js/rbac.js') ?>"></script>
  <script>
    const btn = document.getElementById('btnToggle');
    const sidebar = document.getElementById('sidebar');
    if (btn && sidebar) { btn.addEventListener('click', () => sidebar.classList.toggle('collapsed')); }
  </script>
</body>
</html>
>>>>>>> 06dc0c9b022abb6f0feca36ea29ea8f5038375ea
