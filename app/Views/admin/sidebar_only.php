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
      <a href="<?= site_url('admin/roles/assign') ?>" class="<?= (strpos(current_url(), 'admin/roles/assign') !== false) ? 'active' : '' ?>">
        <i class="fas fa-user-tag"></i> Role Management
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
