<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Admin Dashboard' ?></title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .kpi-card { background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .kpi-primary { border-left: 4px solid #3b82f6; }
    .kpi-success { border-left: 4px solid #10b981; }
    .kpi-warning { border-left: 4px solid #f59e0b; }
    .kpi-info { border-left: 4px solid #60a5fa; }
    .kpi-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; color: #6b7280; font-size: 0.875rem; }
    .kpi-value { font-size: 1.5rem; font-weight: 600; color: #111827; }
    .kpi-subtitle { font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem; }
    .panel { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; overflow: hidden; }
    .panel-head { padding: 1rem 1.25rem; border-bottom: 1px solid #e5e7eb; background-color: #f9fafb; }
    .panel-body { padding: 1.25rem; }
    .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; text-transform: capitalize; }
    .badge-admin { background-color: #dbeafe; color: #1e40af; }
    .badge-doctor { background-color: #d1fae5; color: #065f46; }
    .badge-nurse { background-color: #fef3c7; color: #92400e; }
    .badge-accountant { background-color: #ede9fe; color: #5b21b6; }
    .badge-it_staff { background-color: #f3e8ff; color: #6b21a8; }
    .badge-other { background-color: #e5e7eb; color: #374151; }
    .nav-section { margin-bottom: 1.5rem; }
    .nav-section-title { font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.5rem 0; padding: 0 1rem; }
    .side-nav a { display: flex; align-items: center; padding: 0.75rem 1rem; color: #6b7280; text-decoration: none; transition: all 0.2s; }
    .side-nav a:hover { background-color: #f3f4f6; color: #111827; }
    .side-nav a.active { background-color: #dbeafe; color: #1e40af; font-weight: 500; }
    .side-nav a i { margin-right: 0.5rem; width: 1rem; text-align: center; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { text-align: left; padding: 8px 12px; border-bottom: 1px solid #e5e7eb; }
    .table th { background-color: #f9fafb; font-weight: 600; color: #374151; }
    .table tbody tr:hover { background-color: #f9fafb; }
    .btn { display: inline-block; padding: 0.5rem 1rem; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s; }
    .btn:hover { background-color: #2563eb; }
    .btn-secondary { background-color: #6b7280; }
    .btn-secondary:hover { background-color: #4b5563; }
    .btn-success { background-color: #10b981; }
    .btn-success:hover { background-color: #059669; }
    .btn-warning { background-color: #f59e0b; }
    .btn-warning:hover { background-color: #d97706; }
    .btn-danger { background-color: #ef4444; }
    .btn-danger:hover { background-color: #dc2626; }
    .alert { padding: 1rem; border-radius: 0.375rem; margin-bottom: 1rem; }
    .alert-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .pagination { display: flex; justify-content: center; align-items: center; gap: 0.5rem; margin-top: 1rem; }
    .pagination a { padding: 0.5rem 0.75rem; background-color: #f3f4f6; color: #374151; text-decoration: none; border-radius: 0.375rem; }
    .pagination a:hover { background-color: #e5e7eb; }
    .pagination .current { padding: 0.5rem 0.75rem; background-color: #3b82f6; color: white; border-radius: 0.375rem; }
  </style>
  <script>
    // Global function for modal - available immediately
    function showMedicineModal() {
        console.log('Global showMedicineModal called!');
        const modal = document.getElementById('addMedicineModal');
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.add('show');
            console.log('Modal should be visible now');
        } else {
            console.error('Modal not found');
        }
    }
    
    // Close modal function
    function closeMedicineModal() {
        const modal = document.getElementById('addMedicineModal');
        const form = document.getElementById('addMedicineForm');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('show');
            if (form) form.reset();
        }
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('addMedicineModal');
        if (modal && e.target === modal) {
            closeMedicineModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('addMedicineModal');
        if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
            closeMedicineModal();
        }
    });
  </script>
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
        <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Administrator Dashboard</h1>
        <small>Full control • Users • Reports • System Status</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role">
        <i class="fa-regular fa-user"></i>
        <?= esc(session('username') ?? session('role') ?? 'User') ?>
      </span>
      <a href="<?= site_url('logout') ?>" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </div>
</header>
<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Admin navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/admin') ?>" class="<?= (current_url() == site_url('dashboard/admin')) ? 'active' : '' ?>" aria-current="page">
        <i class="fas fa-tachometer-alt"></i> Overview
      </a>
      
      <!-- User Management -->
      <div class="nav-section">
        <h4 class="nav-section-title">User Management</h4>
        <a href="<?= site_url('admin/users') ?>" class="<?= (strpos(current_url(), 'admin/users') !== false) ? 'active' : '' ?>">
          <i class="fas fa-users-cog"></i> Users
        </a>
      </div>
      
      <!-- Patient Management -->
      <div class="nav-section">
        <h4 class="nav-section-title">Patient Management</h4>
        <a href="<?= site_url('admin/patients') ?>" class="<?= (strpos(current_url(), 'admin/patients') !== false) ? 'active' : '' ?>">
          <i class="fas fa-user-injured"></i> Patients
        </a>
        <a href="<?= site_url('admin/appointments') ?>" class="<?= (strpos(current_url(), 'admin/appointments') !== false) ? 'active' : '' ?>">
          <i class="fas fa-calendar-check"></i> Appointments
        </a>
        <a href="<?= site_url('admin/staff-schedules') ?>" class="<?= (strpos(current_url(), 'admin/staff-schedules') !== false) ? 'active' : '' ?>">
          <i class="fas fa-user-clock"></i> Staff Schedules
        </a>
        <a href="<?= site_url('admin/medical-records') ?>" class="<?= (strpos(current_url(), 'admin/medical-records') !== false) ? 'active' : '' ?>">
          <i class="fas fa-file-medical"></i> Medical Records
        </a>
      </div>
      
      <!-- Financial Management -->
      <div class="nav-section">
        <h4 class="nav-section-title">Financial Management</h4>
        <a href="<?= site_url('admin/invoices') ?>" class="<?= (strpos(current_url(), 'admin/invoices') !== false) ? 'active' : '' ?>">
          <i class="fas fa-file-invoice"></i> Invoices
        </a>
        <a href="<?= site_url('admin/payments') ?>" class="<?= (strpos(current_url(), 'admin/payments') !== false) ? 'active' : '' ?>">
          <i class="fas fa-credit-card"></i> Payments
        </a>
        <a href="<?= site_url('admin/insurance-claims') ?>" class="<?= (strpos(current_url(), 'admin/insurance-claims') !== false) ? 'active' : '' ?>">
          <i class="fas fa-shield-alt"></i> Insurance Claims
        </a>
      </div>
      
      <!-- Lab & Inventory -->
      <div class="nav-section">
        <h4 class="nav-section-title">Lab & Inventory</h4>
        <a href="<?= site_url('admin/lab-tests') ?>" class="<?= (strpos(current_url(), 'admin/lab-tests') !== false) ? 'active' : '' ?>">
          <i class="fas fa-vial"></i> Lab Tests
        </a>
        <a href="<?= site_url('admin/medicines') ?>" class="<?= (strpos(current_url(), 'admin/medicines') !== false) ? 'active' : '' ?>">
          <i class="fas fa-pills"></i> Medicines
        </a>
        <a href="<?= site_url('admin/inventory') ?>" class="<?= (strpos(current_url(), 'admin/inventory') !== false) ? 'active' : '' ?>">
          <i class="fas fa-boxes"></i> Inventory
        </a>
      </div>
      
      <!-- Analytics & Reports -->
      <div class="nav-section">
        <h4 class="nav-section-title">Analytics & Reports</h4>
        <a href="<?= site_url('admin/analytics') ?>" class="<?= (strpos(current_url(), 'admin/analytics') !== false) ? 'active' : '' ?>">
          <i class="fas fa-chart-line"></i> Analytics
        </a>
        <a href="<?= site_url('admin/reports') ?>" class="<?= (strpos(current_url(), 'admin/reports') !== false) ? 'active' : '' ?>">
          <i class="fas fa-chart-bar"></i> Reports
        </a>
        <a href="<?= site_url('admin/audit-logs') ?>" class="<?= (strpos(current_url(), 'admin/audit-logs') !== false) ? 'active' : '' ?>">
          <i class="fas fa-history"></i> Audit Logs
        </a>
      </div>
    </nav>
  </aside>
  <main class="content">
    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <?= $this->renderSection('content') ?>
  </main>
</div>

<?= $this->renderSection('scripts') ?>

</body>
</html>
