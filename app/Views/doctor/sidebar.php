<?php
// Shared sidebar for all doctor dashboards
// Variables are passed via $this->include() and automatically available
$specialization = $specialization ?? 'Doctor';
$department = $department ?? null;
$currentPage = $currentPage ?? 'dashboard';
?>
<aside class="simple-sidebar" role="navigation" style="background:#ffffff;border-right:1px solid #e5e7eb;padding:0">
  <div style="padding:20px;border-bottom:1px solid #e5e7eb">
    <h2 style="margin:0;font-size:1rem;font-weight:700;color:#1f2937;text-transform:uppercase;letter-spacing:0.5px">DOCTOR PANEL</h2>
    <p style="margin:4px 0 0 0;font-size:0.75rem;color:#6b7280">
      <i class="fa-solid fa-stethoscope" style="margin-right:4px"></i>
      <?= esc($specialization) ?>
    </p>
    <?php if (!empty($department)): ?>
      <p style="margin:4px 0 0 0;font-size:0.75rem;color:#6b7280">
        <i class="fa-solid fa-building" style="margin-right:4px"></i>
        <?= esc($department['name'] ?? '') ?>
      </p>
    <?php endif; ?>
  </div>
  <nav class="side-nav" style="padding:10px 0">
    <!-- OVERVIEW -->
    <div style="padding:8px 20px;margin-top:8px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">OVERVIEW</span>
    </div>
    <a href="<?= site_url('dashboard/doctor') ?>" 
       class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>" 
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'dashboard' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'dashboard' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'dashboard' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-gauge" style="width:20px;margin-right:12px;font-size:1rem"></i>Dashboard
    </a>

    <!-- PATIENT MANAGEMENT -->
    <div style="padding:8px 20px;margin-top:12px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">PATIENT MANAGEMENT</span>
    </div>
    <a href="<?= site_url('doctor/patients') ?>" 
       class="<?= $currentPage === 'patients' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'patients' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'patients' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'patients' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-users" style="width:20px;margin-right:12px;font-size:1rem"></i>My Patients
    </a>
    <a href="<?= site_url('doctor/upcoming-consultations') ?>" 
       class="<?= $currentPage === 'consultations' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'consultations' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'consultations' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'consultations' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-calendar-check" style="width:20px;margin-right:12px;font-size:1rem"></i>Appointments
    </a>
    <a href="<?= site_url('doctor/schedule') ?>" 
       class="<?= $currentPage === 'schedule' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'schedule' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'schedule' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'schedule' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-calendar" style="width:20px;margin-right:12px;font-size:1rem"></i>My Schedule
    </a>

    <!-- CLINICAL WORK -->
    <div style="padding:8px 20px;margin-top:12px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">CLINICAL WORK</span>
    </div>
    <a href="<?= site_url('doctor/records') ?>" 
       class="<?= $currentPage === 'records' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'records' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'records' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'records' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-file-medical" style="width:20px;margin-right:12px;font-size:1rem"></i>Medical Records
    </a>
    <a href="<?= site_url('doctor/records/new') ?>" 
       class="<?= $currentPage === 'new-record' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'new-record' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'new-record' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'new-record' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-file-circle-plus" style="width:20px;margin-right:12px;font-size:1rem"></i>New Medical Record
    </a>

    <!-- DIAGNOSTICS -->
    <div style="padding:8px 20px;margin-top:12px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">DIAGNOSTICS</span>
    </div>
    <a href="<?= site_url('doctor/lab-requests/new') ?>" 
       class="<?= $currentPage === 'lab-request' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'lab-request' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'lab-request' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'lab-request' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-vial" style="width:20px;margin-right:12px;font-size:1rem"></i>Request Lab Test
    </a>
    <a href="<?= site_url('doctor/lab-requests-nurses') ?>" 
       class="<?= $currentPage === 'lab-requests' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'lab-requests' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'lab-requests' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'lab-requests' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-flask-vial" style="width:20px;margin-right:12px;font-size:1rem"></i>Pending Lab Requests
    </a>
    <a href="<?= site_url('doctor/lab-results') ?>" 
       class="<?= $currentPage === 'lab-results' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'lab-results' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'lab-results' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'lab-results' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-microscope" style="width:20px;margin-right:12px;font-size:1rem"></i>Lab Results
    </a>

    <!-- TREATMENT -->
    <div style="padding:8px 20px;margin-top:12px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">TREATMENT</span>
    </div>
    <a href="<?= site_url('doctor/prescriptions') ?>" 
       class="<?= $currentPage === 'prescriptions' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'prescriptions' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'prescriptions' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'prescriptions' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-prescription" style="width:20px;margin-right:12px;font-size:1rem"></i>Prescriptions
    </a>
    <a href="<?= site_url('doctor/orders') ?>" 
       class="<?= $currentPage === 'orders' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'orders' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'orders' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'orders' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-clipboard-list" style="width:20px;margin-right:12px;font-size:1rem"></i>Doctor Orders
    </a>

    <!-- PATIENT CARE -->
    <div style="padding:8px 20px;margin-top:12px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">PATIENT CARE</span>
    </div>
    <a href="<?= site_url('doctor/admit-patients') ?>" 
       class="<?= $currentPage === 'admit' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'admit' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'admit' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'admit' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-hospital" style="width:20px;margin-right:12px;font-size:1rem"></i>Admit Patient
    </a>
    <a href="<?= site_url('doctor/discharge-patients') ?>" 
       class="<?= $currentPage === 'discharge' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'discharge' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'discharge' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'discharge' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-sign-out-alt" style="width:20px;margin-right:12px;font-size:1rem"></i>Discharge Patient
    </a>

    <!-- SPECIALIZATION-SPECIFIC LINKS -->
    <?php 
    $specLower = strtolower($specialization);
    if (strpos($specLower, 'pediatric') !== false || strpos($specLower, 'pedia') !== false): 
    ?>
    <div style="padding:8px 20px;margin-top:12px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">PEDIATRICS</span>
    </div>
    <a href="<?= site_url('doctor/vaccinations') ?>" 
       class="<?= $currentPage === 'vaccinations' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'vaccinations' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'vaccinations' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'vaccinations' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-syringe" style="width:20px;margin-right:12px;font-size:1rem"></i>Vaccination Records
    </a>
    <?php endif; ?>

    <?php if (strpos($specLower, 'cardio') !== false): ?>
    <div style="padding:8px 20px;margin-top:12px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">CARDIOLOGY</span>
    </div>
    <a href="<?= site_url('doctor/ecg-requests') ?>" 
       class="<?= $currentPage === 'ecg' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'ecg' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'ecg' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'ecg' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-heart-pulse" style="width:20px;margin-right:12px;font-size:1rem"></i>ECG Requests
    </a>
    <?php endif; ?>

    <?php if (strpos($specLower, 'surgeon') !== false || strpos($specLower, 'orthopedic') !== false || strpos($specLower, 'ortho') !== false): ?>
    <div style="padding:8px 20px;margin-top:12px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">SURGERY</span>
    </div>
    <a href="<?= site_url('doctor/surgeries/schedule') ?>" 
       class="<?= $currentPage === 'surgeries' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'surgeries' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'surgeries' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'surgeries' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-kit-medical" style="width:20px;margin-right:12px;font-size:1rem"></i>Schedule Surgery
    </a>
    <?php endif; ?>

    <?php if (strpos($specLower, 'obstetrician') !== false || strpos($specLower, 'gynecologist') !== false || strpos($specLower, 'ob-gyn') !== false || strpos($specLower, 'obgyn') !== false): ?>
    <div style="padding:8px 20px;margin-top:12px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">OBSTETRICS & GYNECOLOGY</span>
    </div>
    <a href="<?= site_url('doctor/prenatal/new') ?>" 
       class="<?= $currentPage === 'prenatal' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'prenatal' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'prenatal' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'prenatal' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-baby" style="width:20px;margin-right:12px;font-size:1rem"></i>Prenatal Checkup
    </a>
    <?php endif; ?>

    <?php if (strpos($specLower, 'neurologist') !== false || strpos($specLower, 'neurology') !== false): ?>
    <div style="padding:8px 20px;margin-top:12px">
      <span style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.5px">NEUROLOGY</span>
    </div>
    <a href="<?= site_url('doctor/neurology/imaging') ?>" 
       class="<?= $currentPage === 'neurology-imaging' ? 'active' : '' ?>"
       style="display:flex;align-items:center;padding:12px 20px;color:<?= $currentPage === 'neurology-imaging' ? '#1f2937' : '#6b7280' ?>;text-decoration:none;font-weight:<?= $currentPage === 'neurology-imaging' ? '500' : '400' ?>;transition:all 0.2s;<?= $currentPage === 'neurology-imaging' ? 'background:#f0f9ff;border-left:4px solid #3b82f6' : '' ?>">
      <i class="fa-solid fa-brain" style="width:20px;margin-right:12px;font-size:1rem"></i>Neurological Imaging
    </a>
    <?php endif; ?>
  </nav>
</aside>

