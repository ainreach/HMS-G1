<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prescription Details - Pharmacy</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Prescription Details</h1>
        <small>View prescription information</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role">
        <i class="fa-regular fa-user"></i>
        <?= esc(session('username') ?? session('role') ?? 'User') ?>
      </span>
      <a href="<?= site_url('pharmacy/prescriptions') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Pharmacy navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/pharmacist') ?>">Overview</a>
      <a href="<?= site_url('pharmacy/prescriptions') ?>" class="active" aria-current="page">Prescriptions</a>
      <a href="<?= site_url('pharmacy/inventory') ?>">Inventory</a>
      <a href="<?= site_url('pharmacy/low-stock-alerts') ?>">Low Stock</a>
      <a href="<?= site_url('pharmacy/medicine-expiry') ?>">Expiry Alerts</a>
    </nav>
  </aside>

  <main class="content" style="padding:16px">
    <?php if (session()->getFlashdata('error')): ?>
      <div style="padding:12px;background:#fee2e2;border:1px solid #fca5a5;border-radius:6px;margin-bottom:16px;color:#991b1b">
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div style="padding:12px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;margin-bottom:16px;color:#065f46">
        <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">
          <i class="fa-solid fa-prescription-bottle-medical"></i> Prescription #<?= esc($prescription['id']) ?>
        </h2>
      </div>
      <div class="panel-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
          <!-- Patient Information -->
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border:1px solid #e5e7eb">
            <h3 style="margin:0 0 12px 0;font-size:0.95rem;color:#374151;font-weight:600">
              <i class="fa-solid fa-user" style="color:#3b82f6;margin-right:8px"></i>Patient Information
            </h3>
            <div style="display:flex;flex-direction:column;gap:8px;font-size:0.875rem">
              <div>
                <strong style="color:#6b7280">Name:</strong>
                <span style="color:#1e293b;margin-left:8px"><?= esc($prescription['patient_name'] ?? 'N/A') ?></span>
              </div>
              <?php if (!empty($prescription['patient_code'])): ?>
              <div>
                <strong style="color:#6b7280">Patient ID:</strong>
                <span style="color:#1e293b;margin-left:8px"><?= esc($prescription['patient_code']) ?></span>
              </div>
              <?php endif; ?>
              <?php if (!empty($prescription['patient_phone'])): ?>
              <div>
                <strong style="color:#6b7280">Phone:</strong>
                <span style="color:#1e293b;margin-left:8px"><?= esc($prescription['patient_phone']) ?></span>
              </div>
              <?php endif; ?>
              <?php if (!empty($prescription['patient_email'])): ?>
              <div>
                <strong style="color:#6b7280">Email:</strong>
                <span style="color:#1e293b;margin-left:8px"><?= esc($prescription['patient_email']) ?></span>
              </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Doctor Information -->
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border:1px solid #e5e7eb">
            <h3 style="margin:0 0 12px 0;font-size:0.95rem;color:#374151;font-weight:600">
              <i class="fa-solid fa-user-doctor" style="color:#10b981;margin-right:8px"></i>Doctor Information
            </h3>
            <div style="display:flex;flex-direction:column;gap:8px;font-size:0.875rem">
              <div>
                <strong style="color:#6b7280">Name:</strong>
                <span style="color:#1e293b;margin-left:8px"><?= esc($prescription['doctor_name'] ?? $prescription['doctor_username'] ?? 'N/A') ?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Prescription Details -->
        <div style="padding:16px;background:#fff;border-radius:8px;border:1px solid #e5e7eb;margin-bottom:20px">
          <h3 style="margin:0 0 16px 0;font-size:1rem;color:#374151;font-weight:600">
            <i class="fa-solid fa-pills" style="color:#8b5cf6;margin-right:8px"></i>Prescription Details
          </h3>
          <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:16px">
            <div>
              <strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Medication</strong>
              <span style="color:#1e293b;font-size:1rem;font-weight:600"><?= esc($prescription['medicine_name'] ?? $prescription['medication'] ?? 'N/A') ?></span>
            </div>
            <div>
              <strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Dosage</strong>
              <span style="color:#1e293b;font-size:0.95rem"><?= esc($prescription['dosage'] ?? 'N/A') ?></span>
            </div>
            <div>
              <strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Frequency</strong>
              <span style="color:#1e293b;font-size:0.95rem"><?= esc($prescription['frequency'] ?? 'N/A') ?></span>
            </div>
            <div>
              <strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Start Date</strong>
              <span style="color:#1e293b;font-size:0.95rem"><?= esc(date('M d, Y', strtotime($prescription['start_date'] ?? $prescription['prescription_date'] ?? 'now'))) ?></span>
            </div>
            <?php if (!empty($prescription['end_date'])): ?>
            <div>
              <strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">End Date</strong>
              <span style="color:#1e293b;font-size:0.95rem"><?= esc(date('M d, Y', strtotime($prescription['end_date']))) ?></span>
            </div>
            <?php endif; ?>
            <div>
              <strong style="color:#6b7280;font-size:0.875rem;display:block;margin-bottom:4px">Status</strong>
              <?php
              $status = $prescription['status'] ?? 'pending';
              $statusColors = [
                'pending' => 'background:#fef3c7;color:#92400e',
                'approved' => 'background:#dbeafe;color:#1e40af',
                'prepared' => 'background:#fef3c7;color:#92400e',
                'dispensed' => 'background:#d1fae5;color:#065f46',
                'administered' => 'background:#d1fae5;color:#065f46',
                'cancelled' => 'background:#fee2e2;color:#991b1b'
              ];
              $statusStyle = $statusColors[$status] ?? 'background:#f3f4f6;color:#374151';
              ?>
              <span style="padding:4px 8px;border-radius:4px;font-size:0.875rem;font-weight:600;<?= $statusStyle ?>">
                <?= ucfirst(esc($status)) ?>
              </span>
            </div>
          </div>

          <?php if (!empty($prescription['instructions'])): ?>
          <div style="margin-top:16px;padding:12px;background:#f8fafc;border-radius:6px;border-left:4px solid #3b82f6">
            <strong style="color:#374151;font-size:0.875rem;display:block;margin-bottom:4px">Instructions</strong>
            <p style="color:#1e293b;margin:0;white-space:pre-wrap;font-size:0.875rem"><?= esc($prescription['instructions']) ?></p>
          </div>
          <?php endif; ?>
        </div>

        <!-- Medications from Medical Record (if available) -->
        <?php if (!empty($medications)): ?>
        <div style="padding:16px;background:#fff;border-radius:8px;border:1px solid #e5e7eb;margin-bottom:20px">
          <h3 style="margin:0 0 16px 0;font-size:1rem;color:#374151;font-weight:600">
            <i class="fa-solid fa-capsules" style="color:#10b981;margin-right:8px"></i>Medications Prescribed
          </h3>
          <?php
          // Handle single medication object or array
          if (isset($medications['medicine_name']) || isset($medications['drug']) || isset($medications['name'])) {
            // Single medication
            $med = $medications;
            $medicineName = $med['medicine_name'] ?? $med['drug'] ?? $med['name'] ?? 'Unknown Medicine';
            $dosage = $med['dosage'] ?? $med['dose'] ?? '';
            $frequency = $med['frequency'] ?? '';
            $duration = $med['duration'] ?? '';
            $purchaseLocation = $med['purchase_location'] ?? '';
            $status = $med['status'] ?? '';
          ?>
          <div style="background:#f8fafc;padding:12px;border-radius:6px;border:1px solid #e5e7eb">
            <div style="display:flex;align-items:center;margin-bottom:10px">
              <i class="fa-solid fa-capsules" style="color:#10b981;margin-right:8px;font-size:1.1rem"></i>
              <span style="font-weight:600;font-size:1rem;color:#1e293b"><?= esc($medicineName) ?></span>
              <?php if ($status === 'pending_pharmacy'): ?>
                <span style="padding:4px 8px;background:#fef3c7;color:#92400e;border-radius:4px;font-size:0.75rem;margin-left:12px;font-weight:600">Pending</span>
              <?php elseif ($status === 'dispensed'): ?>
                <span style="padding:4px 8px;background:#d1fae5;color:#065f46;border-radius:4px;font-size:0.75rem;margin-left:12px;font-weight:600">Dispensed</span>
              <?php endif; ?>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:8px;font-size:0.875rem;color:#64748b">
              <?php if ($dosage): ?>
                <div><strong style="color:#374151">Dosage:</strong> <?= esc($dosage) ?></div>
              <?php endif; ?>
              <?php if ($frequency): ?>
                <div><strong style="color:#374151">Frequency:</strong> <?= esc($frequency) ?></div>
              <?php endif; ?>
              <?php if ($duration): ?>
                <div><strong style="color:#374151">Duration:</strong> <?= esc($duration) ?></div>
              <?php endif; ?>
              <?php if ($purchaseLocation): ?>
                <div>
                  <strong style="color:#374151">Purchase:</strong>
                  <i class="fas <?= $purchaseLocation === 'hospital_pharmacy' ? 'fa-hospital' : 'fa-store' ?>" style="margin-left:4px;color:#3b82f6"></i>
                  <?= esc($purchaseLocation === 'hospital_pharmacy' ? 'Hospital Pharmacy' : 'Outside') ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <?php } else { ?>
            <!-- Multiple medications (array) -->
            <?php foreach ($medications as $med): ?>
              <?php if (is_array($med) && (isset($med['medicine_name']) || isset($med['drug']) || isset($med['name']))): ?>
              <div style="background:#f8fafc;padding:12px;border-radius:6px;border:1px solid #e5e7eb;margin-bottom:8px">
                <div style="display:flex;align-items:center;margin-bottom:8px">
                  <i class="fa-solid fa-capsules" style="color:#10b981;margin-right:8px"></i>
                  <span style="font-weight:600;color:#1e293b"><?= esc($med['medicine_name'] ?? $med['drug'] ?? $med['name'] ?? 'Unknown') ?></span>
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));gap:8px;font-size:0.875rem;color:#64748b">
                  <?php if (!empty($med['dosage']) || !empty($med['dose'])): ?>
                    <div><strong style="color:#374151">Dosage:</strong> <?= esc($med['dosage'] ?? $med['dose'] ?? '') ?></div>
                  <?php endif; ?>
                  <?php if (!empty($med['frequency'])): ?>
                    <div><strong style="color:#374151">Frequency:</strong> <?= esc($med['frequency']) ?></div>
                  <?php endif; ?>
                  <?php if (!empty($med['duration'])): ?>
                    <div><strong style="color:#374151">Duration:</strong> <?= esc($med['duration']) ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php } ?>
        </div>
        <?php endif; ?>

        <!-- Actions -->
        <div style="display:flex;gap:10px;margin-top:20px">
          <a href="<?= site_url('pharmacy/prescriptions') ?>" 
             style="padding:10px 20px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none;color:#374151;background:#fff">
            <i class="fa-solid fa-arrow-left"></i> Back to Prescriptions
          </a>
          <?php if (($prescription['status'] ?? 'pending') === 'pending'): ?>
          <a href="<?= site_url('pharmacy/dispense/from-prescription/' . $prescription['id']) ?>" 
             style="padding:10px 20px;background:#10b981;color:white;border-radius:6px;text-decoration:none;font-weight:600">
            <i class="fa-solid fa-prescription-bottle"></i> Dispense Medication
          </a>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>

