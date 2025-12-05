<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Start Consultation</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    
    .content-box {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .box-header {
        background: #f3f4f6;
        padding: 16px 20px;
        border-bottom: 2px solid #dc2626;
    }
    
    .box-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .box-header h5 i {
        color: #dc2626;
    }
    
    .box-body {
        padding: 24px;
    }
    
    .info-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    
    .info-box {
        background: #f9fafb;
        padding: 16px;
        border-radius: 4px;
        border-left: 4px solid #dc2626;
    }
    
    .info-box label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    
    .info-box .value {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
    }
    
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 14px;
        display: block;
    }
    
    .form-input,
    .form-select,
    textarea.form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: white;
        font-family: inherit;
    }
    
    .form-input:focus,
    .form-select:focus,
    textarea.form-input:focus {
        outline: none;
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }
    
    textarea.form-input {
        min-height: 100px;
        resize: vertical;
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none;
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
    
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 12px;
    }
    
    .badge-outpatient {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .badge-inpatient {
        background: #fce7f3;
        color: #9f1239;
    }
    
    .divider-line {
        border-top: 2px solid #e5e7eb;
        margin: 24px 0;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('doctor/patients') ?>" class="menu-btn"><i class="fas fa-arrow-left"></i> Back</a>
  <div class="brand">
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Start Consultation</h1><small>Patient consultation</small></div>
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
    <div class="consultation-wrapper">
        <div class="page-title-section">
            <h1>Start Consultation</h1>
        </div>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #a7f3d0;">
                <i class="fas fa-check-circle me-2"></i>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <!-- Patient Information Card -->
        <div class="content-box">
            <div class="box-header">
                <h5><i class="fas fa-user-injured"></i>Patient Information</h5>
            </div>
            <div class="box-body">
                <div class="info-row">
                    <div class="info-box">
                        <label>Patient Name</label>
                        <div class="value"><?= esc(ucwords(trim(($patient['firstname'] ?? '') . ' ' . ($patient['lastname'] ?? '')))) ?></div>
                    </div>
                    
                    <div class="info-box">
                        <label>Age</label>
                        <div class="value"><?= esc($patient['age'] ?? 'N/A') ?> <?= !empty($patient['age']) ? 'years old' : '' ?></div>
                    </div>
                    
                    <div class="info-box">
                        <label>Gender</label>
                        <div class="value"><?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?></div>
                    </div>
                    
                    <div class="info-box">
                        <label>Visit Type</label>
                        <div class="value">
                            <?php 
                            $visitType = $patient['visit_type'] ?? 'Consultation';
                            $visitTypeClass = '';
                            if ($visitType === 'Consultation' || $visitType === 'Out-Patient') $visitTypeClass = 'badge-outpatient';
                            elseif ($visitType === 'Check-up') $visitTypeClass = 'badge-outpatient';
                            elseif ($visitType === 'Follow-up' || $visitType === 'In-Patient') $visitTypeClass = 'badge-inpatient';
                            ?>
                            <span class="badge <?= $visitTypeClass ?>">
                                <?= esc($visitType) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consultation Form -->
        <div class="content-box">
            <div class="box-header">
                <h5><i class="fas fa-notes-medical"></i>Consultation Details</h5>
            </div>
            <div class="box-body">
                <form id="consultationForm" action="<?= site_url('doctor/consultations/save-consultation') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <input type="hidden" name="patient_id" value="<?= esc($patient['id']) ?>">
                    
                <div style="margin-bottom: 20px;">
                    <label class="form-label" for="consultation_date">
                        Consultation Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           name="consultation_date" 
                           id="consultation_date" 
                           class="form-input" 
                           value="<?= old('consultation_date', date('Y-m-d')) ?>" 
                           required>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label class="form-label" for="consultation_time">
                        Consultation Time <span class="text-danger">*</span>
                    </label>
                    <input type="time" 
                           name="consultation_time" 
                           id="consultation_time" 
                           class="form-input" 
                           value="<?= old('consultation_time', date('H:i')) ?>" 
                           required>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label class="form-label" for="observations">
                        Observations / Findings
                    </label>
                    <textarea name="observations" 
                              id="observations" 
                              class="form-input" 
                              rows="5" 
                              placeholder="Record your observations and findings during the consultation..."><?= old('observations') ?></textarea>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label class="form-label" for="diagnosis">
                        Diagnosis
                    </label>
                    <textarea name="diagnosis" 
                              id="diagnosis" 
                              class="form-input" 
                              rows="4" 
                              placeholder="Enter diagnosis..."><?= old('diagnosis') ?></textarea>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label class="form-label" for="notes">
                        Notes / Remarks
                    </label>
                    <textarea name="notes" 
                              id="notes" 
                              class="form-input" 
                              rows="4" 
                              placeholder="Additional notes or remarks..."><?= old('notes') ?></textarea>
                </div>

                <?php if (!($patient['is_admitted'] ?? false)): ?>
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: #fef2f2; border-radius: 4px; border: 1px solid #fca5a5;">
                        <input type="checkbox" 
                               name="for_admission" 
                               id="for_admission" 
                               value="1"
                               <?= old('for_admission') ? 'checked' : '' ?>
                               style="width: 18px; height: 18px; cursor: pointer;">
                        <label for="for_admission" style="margin: 0; font-weight: 600; color: #991b1b; cursor: pointer; flex: 1;">
                            <i class="fas fa-hospital"></i> Mark patient for admission
                        </label>
                    </div>
                    <p style="margin-top: 8px; font-size: 12px; color: #6b7280;">
                        Check this box if the patient needs to be admitted to the hospital.
                    </p>
                </div>
                <?php else: ?>
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px; background: #d1fae5; border-radius: 4px; border: 1px solid #86efac;">
                        <i class="fas fa-check-circle" style="color: #16a34a; font-size: 20px;"></i>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #166534; margin-bottom: 4px;">
                                <i class="fas fa-hospital"></i> Patient is already marked for admission
                            </div>
                            <p style="margin: 0; font-size: 12px; color: #15803d;">
                                This patient is already in the admission queue. No need to mark again.
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                    <!-- Lab Test Request Section -->
                    <div class="divider-line"></div>
                    <div style="margin-bottom: 20px;">
                        <label class="form-label" style="font-size: 15px; margin-bottom: 12px;">
                            Request Lab Test (Optional)
                        </label>
                        <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                            <i class="fas fa-info-circle me-2"></i>
                            You can request laboratory tests for this patient. This is optional and depends on the patient's condition.
                        </p>
                        
                        <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 12px 20px; background: #f8fafc; border-radius: 8px; border: 2px solid #e5e7eb; flex: 1;">
                                <input type="radio" name="request_lab_test" value="yes" id="lab_test_yes" onchange="toggleLabTestForm(true)" style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="font-weight: 600; color: #1e293b;">Yes, request lab test</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 12px 20px; background: #f8fafc; border-radius: 8px; border: 2px solid #e5e7eb; flex: 1;">
                                <input type="radio" name="request_lab_test" value="no" id="lab_test_no" onchange="toggleLabTestForm(false)" checked style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="font-weight: 600; color: #1e293b;">No lab test needed</span>
                            </label>
                        </div>

                        <div id="labTestFields" style="display: none;">
                            <div style="margin-bottom: 16px;">
                                <label class="form-label" for="lab_test_name">
                                    Select Lab Test
                                </label>
                                <select name="lab_test_name" id="lab_test_name" class="form-select" onchange="updateLabTestInfo()">
                                    <option value="">-- Select Lab Test --</option>
                                    <?php
                                    $categoryLabels = [
                                        'with_specimen' => '🔬 With Specimen (Requires Physical Specimen)',
                                        'without_specimen' => '📋 Without Specimen (No Physical Specimen Needed)'
                                    ];
                                    
                                    if (!empty($labTests ?? [])):
                                        foreach ($labTests as $category => $testTypes):
                                            if (is_array($testTypes)):
                                                foreach ($testTypes as $testType => $tests):
                                                    if (is_array($tests)):
                                                        $categoryLabel = $categoryLabels[$category] ?? ucfirst(str_replace('_', ' ', $category));
                                                        ?>
                                                        <optgroup label="<?= esc($categoryLabel) ?>">
                                                            <?php foreach ($tests as $test): ?>
                                                                <?php if (is_array($test)): ?>
                                                                    <option value="<?= esc($test['test_name']) ?>" 
                                                                        data-test-type="<?= esc($test['test_type']) ?>"
                                                                        data-specimen-category="<?= esc($test['specimen_category'] ?? 'with_specimen') ?>"
                                                                        data-description="<?= esc($test['description'] ?? '') ?>"
                                                                        data-normal-range="<?= esc($test['normal_range'] ?? '') ?>"
                                                                        data-price="<?= esc($test['price'] ?? '0.00') ?>">
                                                                        <?= esc($test['test_name']) ?> (<?= esc($test['test_type']) ?>) - ₱<?= number_format($test['price'] ?? 0, 2) ?>
                                                                    </option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </optgroup>
                                                        <?php
                                                    endif;
                                                endforeach;
                                            endif;
                                        endforeach;
                                    else:
                                    ?>
                                        <option value="" disabled>No lab tests available</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div id="lab_test_info" style="display: none; padding: 16px; background: #f8fafc; border-radius: 10px; border: 2px solid #e5e7eb; margin-bottom: 20px;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                                    <div>
                                        <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Test Type</div>
                                        <div style="font-weight: 600; color: #1e293b;" id="test_type_display">-</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Price</div>
                                        <div style="font-weight: 600; color: #1e293b;" id="test_price_display">-</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Normal Range</div>
                                        <div style="font-weight: 600; color: #1e293b;" id="test_range_display">-</div>
                                    </div>
                                </div>
                                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e5e7eb;">
                                    <div style="font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Description</div>
                                    <div style="color: #1e293b; font-size: 14px;" id="test_description_display">-</div>
                                </div>
                            </div>

                            <div id="nurse_field_group" style="margin-bottom: 16px; display: none;">
                                <label class="form-label" for="lab_nurse_id">
                                    Assign Nurse <span id="nurse_required_indicator" style="color: #ef4444; display: none;">*</span> <span id="nurse_label_text">(Will collect specimen)</span>
                                </label>
                                <select name="lab_nurse_id" id="lab_nurse_id" class="form-select">
                                    <option value="">-- Select Nurse --</option>
                                    <?php if (!empty($nurses)): ?>
                                        <?php foreach ($nurses as $nurse): ?>
                                            <option value="<?= esc($nurse['id']) ?>">
                                                <?= esc($nurse['username'] ?? $nurse['first_name'] . ' ' . $nurse['last_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No nurses available</option>
                                    <?php endif; ?>
                                </select>
                                <small id="nurse_help_text" style="color: #64748b; font-size: 13px; margin-top: 4px; display: block;">
                                    <i class="fas fa-info-circle"></i> Select a nurse who will collect the specimen from the patient
                                </small>
                            </div>

                            <div style="margin-bottom: 16px;">
                                <label class="form-label" for="lab_test_remarks">
                                    Remarks / Instructions (Optional)
                                </label>
                                <textarea name="lab_test_remarks" id="lab_test_remarks" class="form-input" rows="3" placeholder="Add any special instructions or remarks for the lab test..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Medication Prescription Section -->
                    <div class="divider-line"></div>
                    <div style="margin-bottom: 20px;">
                        <label class="form-label" style="font-size: 15px; margin-bottom: 12px;">
                            Prescribe Medication (After Consultation)
                        </label>
                        <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                            <i class="fas fa-info-circle me-2"></i>
                            After completing the consultation, you can prescribe medication here. The patient will be asked where they want to purchase the medication.
                        </p>
                        
                        <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 12px 20px; background: #f8fafc; border-radius: 8px; border: 2px solid #e5e7eb; flex: 1;">
                                <input type="radio" name="prescribe_medication" value="yes" id="prescribe_yes" onchange="toggleMedicationForm(true)" style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="font-weight: 600; color: #1e293b;">Yes, prescribe medication</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 12px 20px; background: #f8fafc; border-radius: 8px; border: 2px solid #e5e7eb; flex: 1;">
                                <input type="radio" name="prescribe_medication" value="no" id="prescribe_no" onchange="toggleMedicationForm(false)" checked style="width: 18px; height: 18px; cursor: pointer;">
                                <span style="font-weight: 600; color: #1e293b;">No medication needed</span>
                            </label>
                        </div>

                        <div id="medicationFields" style="display: none;">
                            <div style="margin-bottom: 16px;">
                                <label class="form-label" for="medicine_name">
                                    Select Medicine <span class="text-danger">*</span>
                                </label>
                                <select name="medicine_name" id="medicine_name" class="form-select">
                                    <option value="">-- Select Medicine --</option>
                                    <?php if (!empty($medicines)): ?>
                                        <?php foreach ($medicines as $medicine): ?>
                                            <?php 
                                            $medicineName = $medicine['item_name'] ?? $medicine['name'] ?? '';
                                            $medicinePrice = $medicine['price'] ?? 0;
                                            $medicineStock = $medicine['quantity'] ?? $medicine['stock'] ?? 0;
                                            ?>
                                            <option value="<?= esc($medicineName) ?>" 
                                                data-price="<?= esc($medicinePrice) ?>"
                                                data-stock="<?= esc($medicineStock) ?>">
                                                <?= esc($medicineName) ?> 
                                                <?php if ($medicineStock < 10): ?>
                                                    <span style="color: #ef4444;">(Low Stock: <?= $medicineStock ?>)</span>
                                                <?php elseif ($medicineStock < 20): ?>
                                                    <span style="color: #f59e0b;">(Stock: <?= $medicineStock ?>)</span>
                                                <?php else: ?>
                                                    <span style="color: #10b981;">(Stock: <?= $medicineStock ?>)</span>
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No medicines available in pharmacy</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                <div>
                                    <label class="form-label" for="dosage">
                                        Dosage <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="dosage" id="dosage" class="form-input" placeholder="e.g., 500mg, 1 tablet">
                                </div>
                                <div>
                                    <label class="form-label" for="frequency">
                                        Frequency <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="frequency" id="frequency" class="form-input" placeholder="e.g., Every 8 hours, Twice daily">
                                </div>
                            </div>

                            <div style="margin-bottom: 16px;">
                                <label class="form-label" for="duration">
                                    Duration <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="duration" id="duration" class="form-input" placeholder="e.g., 7 days, 2 weeks">
                            </div>

                            <div style="margin-bottom: 20px;">
                                <label class="form-label">
                                    Where will the patient purchase the medication? <span class="text-danger">*</span>
                                </label>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 8px;">
                                    <label style="display: flex; align-items: center; gap: 12px; padding: 16px; background: #dbeafe; border-radius: 10px; border: 2px solid #3b82f6; cursor: pointer;">
                                        <input type="radio" name="purchase_location" value="hospital_pharmacy" id="purchase_hospital" onchange="toggleNurseField(true)" style="width: 20px; height: 20px; cursor: pointer;">
                                        <div>
                                            <div style="font-weight: 700; color: #1e40af; margin-bottom: 4px;">
                                                <i class="fas fa-hospital me-2"></i>Hospital Pharmacy
                                            </div>
                                            <div style="font-size: 13px; color: #64748b;">
                                                Patient will buy from hospital pharmacy. Medication will be dispensed by pharmacy staff.
                                            </div>
                                        </div>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 12px; padding: 16px; background: #fef3c7; border-radius: 10px; border: 2px solid #f59e0b; cursor: pointer;">
                                        <input type="radio" name="purchase_location" value="outside" id="purchase_outside" onchange="toggleNurseField(false)" style="width: 20px; height: 20px; cursor: pointer;">
                                        <div>
                                            <div style="font-weight: 700; color: #92400e; margin-bottom: 4px;">
                                                <i class="fas fa-store me-2"></i>Outside Hospital
                                            </div>
                                            <div style="font-size: 13px; color: #64748b;">
                                                Patient will buy from external pharmacy/drugstore. Prescription will be provided.
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div id="nurseField" style="margin-bottom: 16px; display: none;">
                                <label class="form-label" for="nurse_id">
                                    Assign to Nurse <span class="text-danger">*</span>
                                </label>
                                <select name="nurse_id" id="nurse_id" class="form-select">
                                    <option value="">-- Select Nurse --</option>
                                    <?php if (!empty($nurses)): ?>
                                        <?php foreach ($nurses as $nurse): ?>
                                            <option value="<?= esc($nurse['id']) ?>">
                                                <?= esc($nurse['username'] ?? $nurse['first_name'] . ' ' . $nurse['last_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No nurses available</option>
                                    <?php endif; ?>
                                </select>
                                <small style="color: #64748b; font-size: 13px; margin-top: 4px; display: block;">
                                    <i class="fas fa-info-circle"></i> Nurse will administer medication after pharmacy dispenses it.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                        <a href="<?= site_url('doctor/patients') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Consultation & Prescription
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
  </main>
</div>
<script>
function toggleLabTestForm(show) {
    const labTestFields = document.getElementById('labTestFields');
    if (show) {
        labTestFields.style.display = 'block';
    } else {
        labTestFields.style.display = 'none';
        document.getElementById('lab_test_name').value = '';
        document.getElementById('lab_nurse_id').value = '';
        document.getElementById('lab_test_remarks').value = '';
        document.getElementById('lab_test_info').style.display = 'none';
        document.getElementById('nurse_field_group').style.display = 'none';
    }
}

function updateLabTestInfo() {
    const select = document.getElementById('lab_test_name');
    const selectedOption = select.options[select.selectedIndex];
    const infoDiv = document.getElementById('lab_test_info');
    const nurseField = document.getElementById('lab_nurse_id');
    const nurseFieldGroup = document.getElementById('nurse_field_group');
    const nurseRequiredIndicator = document.getElementById('nurse_required_indicator');
    const nurseLabelText = document.getElementById('nurse_label_text');
    const nurseHelpText = document.getElementById('nurse_help_text');
    
    if (!selectedOption || !selectedOption.value || selectedOption.value === '') {
        infoDiv.style.display = 'none';
        nurseField.removeAttribute('required');
        nurseRequiredIndicator.style.display = 'none';
        nurseLabelText.textContent = '(Will collect specimen)';
        nurseHelpText.innerHTML = '<i class="fas fa-info-circle"></i> Select a nurse who will collect the specimen from the patient';
        nurseFieldGroup.style.display = 'none';
        return;
    }
    
    const testType = selectedOption.dataset.testType || '';
    const description = selectedOption.dataset.description || '';
    const normalRange = selectedOption.dataset.normalRange || 'N/A';
    const price = selectedOption.dataset.price || '0.00';
    const specimenCategory = selectedOption.dataset.specimenCategory || 'with_specimen';
    
    document.getElementById('test_type_display').textContent = testType || 'N/A';
    document.getElementById('test_price_display').textContent = price > 0 ? '₱' + parseFloat(price).toFixed(2) : 'Free';
    document.getElementById('test_range_display').textContent = normalRange || 'N/A';
    document.getElementById('test_description_display').textContent = description || 'No description available';
    
    infoDiv.style.display = 'block';
    
    if (specimenCategory === 'with_specimen') {
        nurseFieldGroup.style.display = 'block';
        nurseField.setAttribute('required', 'required');
        nurseRequiredIndicator.style.display = 'inline';
        nurseLabelText.textContent = '(Will collect specimen)';
        nurseHelpText.innerHTML = '<i class="fas fa-info-circle"></i> <span style="color: #ef4444;">Required:</span> Select a nurse who will collect the specimen from the patient';
    } else {
        nurseFieldGroup.style.display = 'none';
        nurseField.removeAttribute('required');
        nurseField.value = '';
        nurseRequiredIndicator.style.display = 'none';
        nurseLabelText.textContent = '(Will collect specimen)';
        nurseHelpText.innerHTML = '<i class="fas fa-info-circle"></i> Select a nurse who will collect the specimen from the patient';
    }
}

document.getElementById('consultationForm').addEventListener('submit', function(e) {
    const requestLabTest = document.querySelector('input[name="request_lab_test"]:checked');
    if (requestLabTest && requestLabTest.value === 'yes') {
        const labTestName = document.getElementById('lab_test_name').value;
        if (!labTestName) {
            e.preventDefault();
            alert('Please select a lab test.');
            return false;
        }
        
        const labTestSelect = document.getElementById('lab_test_name');
        const selectedOption = labTestSelect.options[labTestSelect.selectedIndex];
        const specimenCategory = selectedOption ? (selectedOption.dataset.specimenCategory || 'with_specimen') : 'with_specimen';
        const requiresSpecimen = (specimenCategory === 'with_specimen');
        
        if (requiresSpecimen) {
            const labNurseId = document.getElementById('lab_nurse_id').value;
            if (!labNurseId) {
                e.preventDefault();
                alert('Please assign a nurse for lab tests that require specimen collection.');
                return false;
            }
        }
        
        if (!requiresSpecimen) {
            const labNurseField = document.getElementById('lab_nurse_id');
            if (labNurseField) {
                labNurseField.value = '';
            }
        }
    }
    
    return true;
});

function toggleMedicationForm(show) {
    const medicationFields = document.getElementById('medicationFields');
    if (show) {
        medicationFields.style.display = 'block';
    } else {
        medicationFields.style.display = 'none';
        document.getElementById('medicine_name').value = '';
        document.getElementById('dosage').value = '';
        document.getElementById('frequency').value = '';
        document.getElementById('duration').value = '';
        document.getElementById('nurse_id').value = '';
        document.querySelectorAll('input[name="purchase_location"]').forEach(radio => radio.checked = false);
        document.getElementById('nurseField').style.display = 'none';
    }
}

function toggleNurseField(show) {
    const nurseField = document.getElementById('nurseField');
    if (show) {
        nurseField.style.display = 'block';
        document.getElementById('nurse_id').setAttribute('required', 'required');
    } else {
        nurseField.style.display = 'none';
        document.getElementById('nurse_id').removeAttribute('required');
        document.getElementById('nurse_id').value = '';
    }
}

// Form validation before submission
document.getElementById('consultationForm').addEventListener('submit', function(e) {
    const prescribeMedication = document.querySelector('input[name="prescribe_medication"]:checked');
    
    if (prescribeMedication && prescribeMedication.value === 'yes') {
        const medicineName = document.getElementById('medicine_name').value;
        const dosage = document.getElementById('dosage').value;
        const frequency = document.getElementById('frequency').value;
        const duration = document.getElementById('duration').value;
        const purchaseLocation = document.querySelector('input[name="purchase_location"]:checked');
        
        if (!medicineName || !dosage || !frequency || !duration || !purchaseLocation) {
            e.preventDefault();
            alert('Please fill in all medication prescription fields and select where the patient will purchase the medication.');
            return false;
        }
        
        if (purchaseLocation.value === 'hospital_pharmacy') {
            const nurseId = document.getElementById('nurse_id').value;
            if (!nurseId) {
                e.preventDefault();
                alert('Please assign a nurse for hospital pharmacy medication orders.');
                return false;
            }
        }
    }
    
    return true;
});
</script>
</body></html>

