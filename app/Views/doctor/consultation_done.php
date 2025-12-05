<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Consultation Done</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .done-container {
        max-width: 600px;
        margin: 60px auto;
        padding: 20px;
    }
    
    .done-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .done-badge {
        background: #dcfce7;
        color: #166534;
        padding: 12px 24px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 24px;
    }
    
    .done-badge i {
        font-size: 18px;
    }
    
    .patient-info {
        background: #f9fafb;
        padding: 20px;
        border-radius: 6px;
        margin: 24px 0;
        text-align: left;
    }
    
    .patient-info h3 {
        margin: 0 0 16px 0;
        font-size: 18px;
        color: #1f2937;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #6b7280;
    }
    
    .info-value {
        color: #1f2937;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 32px;
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary {
        background: #dc2626;
        color: white;
    }
    
    .btn-primary:hover {
        background: #b91c1c;
        color: white;
    }
    
    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
    }
    
    .btn-secondary:hover {
        background: #e5e7eb;
        color: #374151;
    }
    
    .success-message {
        background: #f0fdf4;
        border: 1px solid #86efac;
        color: #166534;
        padding: 16px;
        border-radius: 6px;
        margin-top: 20px;
        font-size: 14px;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('doctor/patients') ?>" class="menu-btn"><i class="fas fa-arrow-left"></i> Back</a>
  <div class="brand">
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Consultation Complete</h1><small>Consultation summary</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Doctor navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/doctor') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
  <a href="<?= site_url('doctor/records') ?>"><i class="fa-solid fa-file-medical" style="margin-right:8px"></i>Patient Records</a>
  <a href="<?= site_url('doctor/patients') ?>"><i class="fa-solid fa-users" style="margin-right:8px"></i>Patients</a>
  <a href="<?= site_url('doctor/admit-patients') ?>"><i class="fa-solid fa-bed-pulse" style="margin-right:8px"></i>Admit Patients</a>
  <a href="<?= site_url('doctor/prescriptions') ?>"><i class="fa-solid fa-prescription" style="margin-right:8px"></i>Prescriptions</a>
  <a href="<?= site_url('doctor/lab-results') ?>"><i class="fa-solid fa-vial" style="margin-right:8px"></i>Lab Results</a>
</nav></aside>
  <main class="content">
    <div class="done-container">
        <div class="done-card">
            <div class="done-badge">
                <i class="fas fa-check-circle"></i>
                Consultation Done
            </div>
            
            <h2 style="margin: 0 0 8px 0; color: #1f2937; font-size: 24px;">
                Consultation Completed Successfully
            </h2>
            <p style="color: #6b7280; margin-bottom: 24px;">
                The consultation has been saved and recorded in the system.
            </p>
            
            <div class="patient-info">
                <h3>Patient Information</h3>
                <div class="info-item">
                    <span class="info-label">Patient Name:</span>
                    <span class="info-value"><?= esc(ucwords(trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')))) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Patient ID:</span>
                    <span class="info-value"><?= esc($patient['patient_id'] ?? ('P-' . $patient['id'])) ?></span>
                </div>
                <?php if ($consultation): ?>
                <div class="info-item">
                    <span class="info-label">Consultation Date:</span>
                    <span class="info-value"><?= esc(date('M d, Y', strtotime($consultation['visit_date']))) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Record Number:</span>
                    <span class="info-value"><?= esc($consultation['record_number'] ?? 'N/A') ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($consultation_data)): ?>
                <div class="success-message">
                    <?php if (!empty($consultation_data['medication_prescribed'])): ?>
                        <p style="margin: 0 0 8px 0;">
                            <i class="fas fa-pills"></i> 
                            <strong>Medication Prescribed:</strong>
                            <?php if ($consultation_data['purchase_location'] === 'hospital_pharmacy'): ?>
                                Medication prescription created and sent to hospital pharmacy.
                            <?php else: ?>
                                Medication prescription created. Patient will purchase from outside pharmacy.
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($consultation_data['lab_test_requested'])): ?>
                        <p style="margin: 0;">
                            <i class="fas fa-vial"></i> 
                            <strong>Lab Test Requested:</strong>
                            Lab test request has been submitted and is pending accountant approval.
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="action-buttons">
                <a href="<?= site_url('doctor/patients') ?>" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Back to Patients
                </a>
                <a href="<?= site_url('doctor/records') ?>" class="btn btn-primary">
                    <i class="fas fa-file-medical"></i> View Records
                </a>
            </div>
        </div>
    </div>
  </main>
</div>
</body></html>

