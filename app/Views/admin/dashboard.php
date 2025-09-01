<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
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
  </style>
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home">
      <i class="fa-solid fa-house"></i>
    </a>
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
      <a href="<?= site_url('dashboard/admin') ?>" class="active" aria-current="page">
        <i class="fas fa-tachometer-alt"></i> Overview
      </a>
      <a href="<?= site_url('admin/users') ?>">
        <i class="fas fa-users-cog"></i> User Management
      </a>
      <a href="<?= site_url('admin/roles/assign') ?>">
        <i class="fas fa-user-tag"></i> Role Management
      </a>
      <a href="<?= site_url('admin/reports') ?>">
        <i class="fas fa-chart-bar"></i> Reports
      </a>
    </nav>
  </aside>
  <main class="content">
    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <!-- KPI Cards -->
    <section class="kpi-grid">
      <article class="kpi-card kpi-primary">
        <div class="kpi-head">
          <span>Total Users</span>
          <i class="fa-solid fa-users" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= number_format($totalUsers) ?></div>
      </article>
      
      <article class="kpi-card kpi-success">
        <div class="kpi-head">
          <span>Active Users</span>
          <i class="fa-solid fa-user-check" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= number_format($activeUsers) ?></div>
      </article>
      
      <article class="kpi-card kpi-warning">
        <div class="kpi-head">
          <span>New This Month</span>
          <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= number_format($newUsersThisMonth) ?></div>
      </article>
      
      <article class="kpi-card kpi-info">
        <div class="kpi-head">
          <span>Active Sessions</span>
          <i class="fa-solid fa-plug" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= $systemStatus['activeSessions'] ?></div>
      </article>
    </section>
    
    <!-- Quick Actions -->
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Quick Actions</h2>
      </div>
      <div class="panel-body" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));gap:1rem;">
        <a class="btn" href="<?= site_url('admin/users/new') ?>">
          <i class="fas fa-user-plus"></i> Add New User
        </a>
        <a class="btn" href="<?= site_url('admin/roles/assign') ?>">
          <i class="fas fa-user-tag"></i> Assign Roles
        </a>
        <a class="btn" href="<?= site_url('admin/reports') ?>">
          <i class="fas fa-file-export"></i> Generate Report
        </a>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
      <!-- Recent Users -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Recent Users</h2>
        </div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px 12px;border-bottom:1px solid #e5e7eb">Employee ID</th>
                <th style="text-align:left;padding:8px 12px;border-bottom:1px solid #e5e7eb">Username</th>
                <th style="text-align:left;padding:8px 12px;border-bottom:1px solid #e5e7eb">Role</th>
                <th style="text-align:left;padding:8px 12px;border-bottom:1px solid #e5e7eb">Created</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recentUsers)): ?>
                <?php foreach ($recentUsers as $user): ?>
                  <tr>
                    <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($user['employee_id'] ?? 'N/A') ?></td>
                    <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($user['username']) ?></td>
                    <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                      <?php 
                        $role = strtolower($user['role']);
                        $badgeClass = 'badge-other';
                        if (in_array($role, ['admin', 'doctor', 'nurse', 'accountant', 'it_staff'])) {
                          $badgeClass = 'badge-' . $role;
                        }
                      ?>
                      <span class="badge <?= $badgeClass ?>"><?= ucfirst($role) ?></span>
                    </td>
                    <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                      <?= date('M j, Y', strtotime($user['created_at'])) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" style="padding:12px;text-align:center;color:#6b7280">
                    No users found
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <div style="margin-top:1rem;text-align:right;">
            <a href="<?= site_url('admin/users') ?>">
              View all users <i class="fas fa-arrow-right" style="margin-left:0.25rem"></i>
            </a>
          </div>
        </div>
      </section>

      <!-- System Status -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">System Status</h2>
        </div>
        <div class="panel-body">
          <div style="margin-bottom:1.5rem;">
            <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
              <span style="color:#6b7280;font-size:0.875rem">Database</span>
              <span style="color:#10b981;font-weight:500">
                <i class="fas fa-circle" style="font-size:0.5rem;vertical-align:middle;margin-right:0.25rem;"></i>
                <?= ucfirst($systemStatus['database']) ?>
              </span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
              <span style="color:#6b7280;font-size:0.875rem">Storage</span>
              <span style="font-weight:500"><?= number_format($systemStatus['storage'], 1) ?> GB free</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
              <span style="color:#6b7280;font-size:0.875rem">Last Backup</span>
              <span style="color:#6b7280"><?= date('M j, Y', strtotime($systemStatus['lastBackup'])) ?></span>
            </div>
          </div>
          <div>
            <h3 style="font-size:0.875rem;color:#6b7280;margin-bottom:0.75rem;">Quick Stats</h3>
            <div style="display:grid;gap:0.75rem;">
              <div>
                <div style="display:flex;justify-content:space-between;margin-bottom:0.25rem;">
                  <span style="font-size:0.875rem;">Active Sessions</span>
                  <span style="font-weight:500;"><?= $systemStatus['activeSessions'] ?></span>
                </div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;margin-bottom:0.25rem;">
                  <span style="font-size:0.875rem;">PHP Version</span>
                  <span style="font-weight:500;"><?= phpversion() ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
// Auto-refresh dashboard every 5 minutes
setTimeout(() => {
  window.location.reload();
}, 300000);
</script>
</body>
</html>
