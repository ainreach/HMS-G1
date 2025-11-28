<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Details | Hospital Management System</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f2f7fb;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      padding: 0 16px;
    }

    .card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      overflow: hidden;
      border-top: 6px solid #7ec8e3;
    }

    .card-header {
      background: #7ec8e3;
      color: #fff;
      padding: 16px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 1.2rem;
      font-weight: 600;
    }

    .card-header a {
      background: #fff;
      color: #1e6f9f;
      padding: 6px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .card-header a:hover {
      background: #e0f0fa;
    }

    .card-body {
      padding: 24px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 0;
    }

    th, td {
      text-align: left;
      padding: 12px 10px;
      border-bottom: 1px solid #c9d9e8;
    }

    th {
      background: #e6f2f9;
      color: #1e6f9f;
      width: 220px;
    }

    tr:last-child td {
      border-bottom: none;
    }

    .section-title {
      font-size: 18px;
      color: #1e6f9f;
      margin: 20px 0 12px;
      border-bottom: 1px solid #c9d9e8;
      padding-bottom: 6px;
    }

    .text-center {
      text-align: center;
      color: #555;
      margin-top: 20px;
    }

    @media (max-width: 600px) {
      th, td {
        display: block;
        width: 100%;
      }
      th {
        background: transparent;
        color: #1e6f9f;
        font-weight: 600;
      }
      td {
        margin-bottom: 12px;
        padding-left: 0;
      }
      table {
        border: none;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="card-header">
      <span>Patient Details</span>
      <a href="<?= site_url('admin/patients') ?>">Back to List</a>
    </div>
    <div class="card-body">
      <?php if (isset($patient) && !empty($patient)): ?>
        <div class="section">
          <div class="section-title">Basic Information</div>
          <table>
            <tbody>
              <tr><th>Patient ID</th><td><?= esc($patient['patient_id'] ?? 'N/A') ?></td></tr>
              <tr><th>Name</th><td><?= esc(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) ?></td></tr>
              <tr><th>Date of Birth</th><td><?= esc($patient['date_of_birth'] ?? 'N/A') ?></td></tr>
              <tr><th>Gender</th><td><?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?></td></tr>
              <tr><th>Phone</th><td><?= esc($patient['phone'] ?? 'N/A') ?></td></tr>
              <tr><th>Email</th><td><?= esc($patient['email'] ?? 'N/A') ?></td></tr>
            </tbody>
          </table>
        </div>

        <div class="section">
          <div class="section-title">Address</div>
          <table>
            <tbody>
              <tr><th>Address</th><td><?= esc($patient['address'] ?? 'N/A') ?></td></tr>
            </tbody>
          </table>
        </div>

        <div class="section">
          <div class="section-title">Emergency Contact</div>
          <table>
            <tbody>
              <tr><th>Name & Relationship</th><td><?= esc($patient['emergency_contact_name'] ?? 'N/A') ?> (<?= esc($patient['emergency_contact_relation'] ?? 'N/A') ?>)</td></tr>
              <tr><th>Phone</th><td><?= esc($patient['emergency_contact_phone'] ?? 'N/A') ?></td></tr>
            </tbody>
          </table>
        </div>

        <div class="section">
          <div class="section-title">Insurance & Medical Info</div>
          <table>
            <tbody>
              <tr><th>Insurance Provider</th><td><?= esc($patient['insurance_provider'] ?? 'N/A') ?></td></tr>
              <tr><th>Insurance Number</th><td><?= esc($patient['insurance_number'] ?? 'N/A') ?></td></tr>
              <tr><th>Allergies</th><td><?= esc($patient['allergies'] ?? 'N/A') ?></td></tr>
              <tr><th>Medical History</th><td><?= esc($patient['medical_history'] ?? 'N/A') ?></td></tr>
              <tr><th>Registered On</th><td><?= esc($patient['created_at'] ?? 'N/A') ?></td></tr>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-center">Patient data not found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
