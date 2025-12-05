<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>In-Patient Details</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .info-card {
      background: white;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
    }
    .info-card h3 {
      margin: 0 0 16px 0;
      font-size: 1rem;
      color: #000000;
      font-weight: 700;
      border-bottom: 2px solid #e5e7eb;
      padding-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 16px;
    }
    .info-item {
      display: flex;
      flex-direction: column;
    }
    .info-label {
      font-size: 0.75rem;
      color: #6b7280;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 4px;
    }
    .info-value {
      font-size: 0.875rem;
      color: #000000;
      font-weight: 500;
    }
    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .badge-room {
      background: #dbeafe;
      color: #1e40af;
    }
    .badge-bed {
      background: #fef3c7;
      color: #92400e;
    }
    .badge-insurance {
      background: #d1fae5;
      color: #065f46;
    }
    .medical-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.875rem;
    }
    .medical-table thead {
      background: #f3f4f6;
    }
    .medical-table th {
      padding: 12px;
      text-align: left;
      font-weight: 600;
      color: #000000;
      font-size: 0.75rem;
      text-transform: uppercase;
    }
    .medical-table td {
      padding: 12px;
      border-bottom: 1px solid #f3f4f6;
      color: #374151;
    }
    .btn-back {
      background: #6b7280;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 20px;
    }
    .btn-back:hover {
      background: #4b5563;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Receptionist</h1><small>In-Patient Details</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Reception navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/receptionist') ?>">Overview</a>
  <a href="<?= site_url('reception/patients') ?>">Patient Management</a>
  <a href="<?= site_url('reception/appointments') ?>">Appointment Management</a>
  <a href="<?= site_url('reception/rooms') ?>">Room Management</a>
  <a href="<?= site_url('reception/in-patients') ?>" class="active">In-Patients</a>
  <a href="<?= site_url('reception/patient-lookup') ?>">Patient Lookup</a>
</nav></aside>
  <main class="content">
    <a href="<?= site_url('reception/in-patients') ?>" class="btn-back">
      <i class="fa-solid fa-arrow-left"></i> Back to In-Patients List
    </a>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <!-- Patient Information -->
    <div class="info-card">
      <h3><i class="fa-solid fa-user"></i> Patient Information</h3>
      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">Patient Name</span>
          <span class="info-value"><?= esc(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''))) ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Patient ID</span>
          <span class="info-value"><?= esc($patient['patient_id'] ?? 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Date of Birth</span>
          <span class="info-value"><?= esc($patient['date_of_birth'] ?? 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Gender</span>
          <span class="info-value"><?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Phone</span>
          <span class="info-value"><?= esc($patient['phone'] ?? 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Email</span>
          <span class="info-value"><?= esc($patient['email'] ?? 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Address</span>
          <span class="info-value"><?= esc($patient['address'] ?? 'N/A') ?></span>
        </div>
      </div>
    </div>

    <!-- Admission Details -->
    <div class="info-card">
      <h3><i class="fa-solid fa-hospital"></i> Admission Details</h3>
      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">Admission Date</span>
          <span class="info-value">
            <?php 
            $admissionDate = $patient['admission_date'] ?? '';
            if ($admissionDate) {
              try {
                echo date('M d, Y h:i A', strtotime($admissionDate));
              } catch (\Exception $e) {
                echo esc($admissionDate);
              }
            } else {
              echo 'N/A';
            }
            ?>
          </span>
        </div>
        <div class="info-item">
          <span class="info-label">Admission Reason</span>
          <span class="info-value"><?= esc($patient['admission_reason'] ?? 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Attending Physician</span>
          <span class="info-value">
            <?php if ($attendingPhysician): ?>
              <?= esc($attendingPhysician['username'] ?? trim(($attendingPhysician['first_name'] ?? '') . ' ' . ($attendingPhysician['last_name'] ?? ''))) ?>
            <?php else: ?>
              <span style="color:#9ca3af">Not assigned</span>
            <?php endif; ?>
          </span>
        </div>
        <div class="info-item">
          <span class="info-label">Admission Notes</span>
          <span class="info-value"><?= esc($patient['admission_notes'] ?? 'N/A') ?></span>
        </div>
      </div>
    </div>

    <!-- Room & Bed Information -->
    <div class="info-card">
      <h3><i class="fa-solid fa-bed-pulse"></i> Room & Bed Information</h3>
      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">Room Number</span>
          <span class="info-value">
            <?php if ($room): ?>
              <span class="badge badge-room"><?= esc($room['room_number']) ?></span>
            <?php else: ?>
              <span style="color:#9ca3af">Not assigned</span>
            <?php endif; ?>
          </span>
        </div>
        <div class="info-item">
          <span class="info-label">Room Type</span>
          <span class="info-value"><?= esc($room ? ucfirst($room['room_type']) : 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Floor</span>
          <span class="info-value"><?= esc($room ? $room['floor'] : 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Bed Number</span>
          <span class="info-value">
            <?php if ($bed): ?>
              <span class="badge badge-bed"><?= esc($bed['bed_number']) ?></span>
            <?php else: ?>
              <span style="color:#9ca3af">Not assigned</span>
            <?php endif; ?>
          </span>
        </div>
        <div class="info-item">
          <span class="info-label">Bed Type</span>
          <span class="info-value"><?= esc($bed ? ucfirst($bed['bed_type']) : 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Rate per Day</span>
          <span class="info-value">₱<?= esc($room ? number_format($room['rate_per_day'] ?? 0, 2) : '0.00') ?></span>
        </div>
      </div>
    </div>

    <!-- Insurance Information -->
    <div class="info-card">
      <h3><i class="fa-solid fa-shield-halved"></i> Insurance Information</h3>
      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">Insurance Provider</span>
          <span class="info-value">
            <?php if (!empty($patient['insurance_provider'])): ?>
              <span class="badge badge-insurance"><?= esc($patient['insurance_provider']) ?></span>
            <?php else: ?>
              <span style="color:#9ca3af">Not insured</span>
            <?php endif; ?>
          </span>
        </div>
        <div class="info-item">
          <span class="info-label">Insurance Number</span>
          <span class="info-value"><?= esc($patient['insurance_number'] ?? 'N/A') ?></span>
        </div>
      </div>
    </div>

    <!-- Emergency Contact -->
    <div class="info-card">
      <h3><i class="fa-solid fa-phone"></i> Emergency Contact</h3>
      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">Contact Name</span>
          <span class="info-value"><?= esc($patient['emergency_contact_name'] ?? 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Contact Phone</span>
          <span class="info-value"><?= esc($patient['emergency_contact_phone'] ?? 'N/A') ?></span>
        </div>
        <div class="info-item">
          <span class="info-label">Relationship</span>
          <span class="info-value"><?= esc($patient['emergency_contact_relation'] ?? 'N/A') ?></span>
        </div>
      </div>
    </div>

    <!-- Medical Information -->
    <div class="info-card">
      <h3><i class="fa-solid fa-notes-medical"></i> Medical Information</h3>
      
      <?php if (!empty($medicalRecords)): ?>
        <h4 style="margin:16px 0 8px 0;font-size:0.875rem;font-weight:600;color:#6b7280">Recent Medical Records</h4>
        <table class="medical-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Diagnosis</th>
              <th>Doctor</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($medicalRecords as $record): ?>
              <tr>
                <td><?= esc($record['visit_date'] ? date('M d, Y', strtotime($record['visit_date'])) : 'N/A') ?></td>
                <td><?= esc($record['diagnosis'] ?? 'N/A') ?></td>
                <td><?= esc($record['doctor_id'] ?? 'N/A') ?></td>
                <td><?= esc(substr($record['notes'] ?? '', 0, 50)) ?><?= strlen($record['notes'] ?? '') > 50 ? '...' : '' ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="color:#9ca3af;font-size:0.875rem">No medical records available</p>
      <?php endif; ?>

      <?php if (!empty($labTests)): ?>
        <h4 style="margin:24px 0 8px 0;font-size:0.875rem;font-weight:600;color:#6b7280">Recent Lab Tests</h4>
        <table class="medical-table">
          <thead>
            <tr>
              <th>Test Name</th>
              <th>Date</th>
              <th>Status</th>
              <th>Result</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($labTests as $test): ?>
              <tr>
                <td><?= esc($test['test_name'] ?? 'N/A') ?></td>
                <td><?= esc($test['created_at'] ? date('M d, Y', strtotime($test['created_at'])) : 'N/A') ?></td>
                <td>
                  <span class="badge" style="background:#dbeafe;color:#1e40af">
                    <?= esc(ucfirst($test['status'] ?? 'pending')) ?>
                  </span>
                </td>
                <td><?= esc($test['result'] ?? 'Pending') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>

      <?php if (!empty($prescriptions)): ?>
        <h4 style="margin:24px 0 8px 0;font-size:0.875rem;font-weight:600;color:#6b7280">Recent Prescriptions</h4>
        <table class="medical-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Medication</th>
              <th>Dosage</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($prescriptions as $prescription): ?>
              <tr>
                <td><?= esc($prescription['prescription_date'] ? date('M d, Y', strtotime($prescription['prescription_date'])) : 'N/A') ?></td>
                <td><?= esc($prescription['medication_name'] ?? 'N/A') ?></td>
                <td><?= esc($prescription['dosage'] ?? 'N/A') ?></td>
                <td>
                  <span class="badge" style="background:#d1fae5;color:#065f46">
                    <?= esc(ucfirst($prescription['status'] ?? 'active')) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

