<?= $this->include('admin/sidebar') ?>
    
    <!-- KPI Cards -->
    <section class="kpi-grid">
      <!-- User Statistics -->
      <article class="kpi-card kpi-primary">
        <div class="kpi-head">
          <span>Total Users</span>
          <i class="fa-solid fa-users" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= number_format($totalUsers) ?></div>
        <div class="kpi-subtitle">Staff Members</div>
      </article>
      
      <article class="kpi-card kpi-success">
        <div class="kpi-head">
          <span>Total Patients</span>
          <i class="fa-solid fa-user-injured" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= number_format($totalPatients) ?></div>
        <div class="kpi-subtitle">Registered Patients</div>
      </article>
      
      <article class="kpi-card kpi-warning">
        <div class="kpi-head">
          <span>Today's Appointments</span>
          <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= number_format($todayAppointments) ?></div>
        <div class="kpi-subtitle">Scheduled Today</div>
      </article>
      
      <article class="kpi-card kpi-info">
        <div class="kpi-head">
          <span>Total Revenue</span>
          <i class="fa-solid fa-dollar-sign" aria-hidden="true"></i>
        </div>
        <div class="kpi-value">$<?= number_format($totalRevenue, 2) ?></div>
        <div class="kpi-subtitle">All Time</div>
      </article>
      
      <!-- Additional KPIs -->
      <article class="kpi-card kpi-primary">
        <div class="kpi-head">
          <span>Pending Lab Tests</span>
          <i class="fa-solid fa-vial" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= number_format($pendingLabTests) ?></div>
        <div class="kpi-subtitle">Awaiting Results</div>
      </article>
      
      <article class="kpi-card kpi-success">
        <div class="kpi-head">
          <span>Low Stock Items</span>
          <i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= number_format($lowStockItems) ?></div>
        <div class="kpi-subtitle">Need Reorder</div>
      </article>
      
      <article class="kpi-card kpi-warning">
        <div class="kpi-head">
          <span>Pending Claims</span>
          <i class="fa-solid fa-shield-alt" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= number_format($pendingClaims) ?></div>
        <div class="kpi-subtitle">Insurance Claims</div>
      </article>
      
      <article class="kpi-card kpi-info">
        <div class="kpi-head">
          <span>System Status</span>
          <i class="fa-solid fa-server" aria-hidden="true"></i>
        </div>
        <div class="kpi-value"><?= ucfirst($systemStatus['database']) ?></div>
        <div class="kpi-subtitle">Database Online</div>
      </article>
    </section>
    
    <!-- Quick Actions -->
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Quick Actions</h2>
      </div>
      <div class="panel-body" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));gap:1rem;">
        <!-- User Management -->
        <a class="btn" href="<?= site_url('admin/users/new') ?>">
          <i class="fas fa-user-plus"></i> Add New User
        </a>
        <a class="btn" href="<?= site_url('admin/roles/assign') ?>">
          <i class="fas fa-user-tag"></i> Assign Roles
        </a>
        
        <!-- Patient Management -->
        <a class="btn" href="<?= site_url('admin/patients/new') ?>">
          <i class="fas fa-user-injured"></i> Add Patient
        </a>
        <a class="btn" href="<?= site_url('admin/appointments') ?>">
          <i class="fas fa-calendar-check"></i> View Appointments
        </a>
        
        <!-- Financial Management -->
        <a class="btn" href="<?= site_url('admin/invoices') ?>">
          <i class="fas fa-file-invoice"></i> View Invoices
        </a>
        <a class="btn" href="<?= site_url('admin/payments') ?>">
          <i class="fas fa-credit-card"></i> View Payments
        </a>
        
        <!-- Analytics & Reports -->
        <a class="btn" href="<?= site_url('admin/analytics') ?>">
          <i class="fas fa-chart-line"></i> View Analytics
        </a>
        <a class="btn" href="<?= site_url('admin/audit-logs') ?>">
          <i class="fas fa-history"></i> Audit Logs
        </a>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
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

      <!-- Recent Patients -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Recent Patients</h2>
        </div>
        <div class="panel-body" style="overflow:auto">
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px 12px;border-bottom:1px solid #e5e7eb">Patient ID</th>
                <th style="text-align:left;padding:8px 12px;border-bottom:1px solid #e5e7eb">Name</th>
                <th style="text-align:left;padding:8px 12px;border-bottom:1px solid #e5e7eb">Phone</th>
                <th style="text-align:left;padding:8px 12px;border-bottom:1px solid #e5e7eb">Created</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($recentPatients)): ?>
                <?php foreach ($recentPatients as $patient): ?>
                  <tr>
                    <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($patient['patient_id'] ?? 'N/A') ?></td>
                    <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
                    <td style="padding:12px;border-bottom:1px solid #f3f4f6"><?= esc($patient['phone'] ?? 'N/A') ?></td>
                    <td style="padding:12px;border-bottom:1px solid #f3f4f6">
                      <?= date('M j, Y', strtotime($patient['created_at'])) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" style="padding:12px;text-align:center;color:#6b7280">
                    No patients found
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <div style="margin-top:1rem;text-align:right;">
            <a href="<?= site_url('admin/patients') ?>">
              View all patients <i class="fas fa-arrow-right" style="margin-left:0.25rem"></i>
            </a>
          </div>
        </div>
      </section>
    </div>

    <!-- Additional Statistics Row -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem;">
      <!-- System Overview -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">System Overview</h2>
        </div>
        <div class="panel-body">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
              <h4 style="margin:0 0 0.5rem 0;color:#6b7280;font-size:0.875rem">Appointments</h4>
              <div style="font-size:1.5rem;font-weight:600;color:#111827"><?= number_format($totalAppointments) ?></div>
              <div style="font-size:0.875rem;color:#6b7280">Total Scheduled</div>
            </div>
            <div>
              <h4 style="margin:0 0 0.5rem 0;color:#6b7280;font-size:0.875rem">Lab Tests</h4>
              <div style="font-size:1.5rem;font-weight:600;color:#111827"><?= number_format($totalLabTests) ?></div>
              <div style="font-size:0.875rem;color:#6b7280">Total Tests</div>
            </div>
            <div>
              <h4 style="margin:0 0 0.5rem 0;color:#6b7280;font-size:0.875rem">Medicines</h4>
              <div style="font-size:1.5rem;font-weight:600;color:#111827"><?= number_format($totalMedicines) ?></div>
              <div style="font-size:0.875rem;color:#6b7280">In Database</div>
            </div>
            <div>
              <h4 style="margin:0 0 0.5rem 0;color:#6b7280;font-size:0.875rem">Insurance Claims</h4>
              <div style="font-size:1.5rem;font-weight:600;color:#111827"><?= number_format($totalClaims) ?></div>
              <div style="font-size:0.875rem;color:#6b7280">Total Claims</div>
            </div>
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
