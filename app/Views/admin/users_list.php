<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <?php if (session('success')): ?>
      <div class="alert alert-success" style="margin-bottom: 1rem;"><?= esc(session('success')) ?></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div class="alert alert-error" style="margin-bottom: 1rem;"><?= esc(session('error')) ?></div>
    <?php endif; ?>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <h2 style="margin:0">Users</h2>
      <div style="display:flex;gap:8px">
        <a class="btn" href="<?= site_url('admin/users/new') ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Create User</a>
        <a class="btn" href="<?= site_url('admin/fix-doctor-credentials') ?>" style="padding:8px 12px;border:1px solid #10b981;border-radius:8px;text-decoration:none;background:#10b981;color:white" onclick="return confirm('This will set usernames and passwords for all doctors. Continue?')">🔧 Fix Doctor Credentials</a>
      </div>
    </div>

    <section class="panel">
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Employee ID</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Name</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Username</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Role</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Department</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Created</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($users)): foreach ($users as $u): ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($u['employee_id'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?= esc(trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? ''))) ?: esc($u['username'] ?? '') ?>
                  <?php if (!empty($u['specialization'])): ?>
                    <br><small style="color:#6b7280"><?= esc($u['specialization']) ?></small>
                  <?php endif; ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?php if (!empty($u['username'])): ?>
                    <code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:0.9em;"><?= esc($u['username']) ?></code>
                  <?php else: ?>
                    <span style="color:#ef4444;font-size:0.85em;">⚠️ Not set</span>
                  <?php endif; ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><span style="display:inline-block;padding:2px 8px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:.85em;"><?= esc($u['role'] ?? '') ?></span></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?php if (!empty($u['department_name'])): ?>
                    <span style="display:inline-block;padding:2px 8px;border-radius:999px;background:#d1fae5;color:#065f46;font-size:.85em;">
                      <?= esc($u['department_name']) ?>
                    </span>
                  <?php elseif ($u['role'] === 'doctor'): ?>
                    <span style="color:#9ca3af;font-size:.85em;">Not assigned</span>
                  <?php else: ?>
                    <span style="color:#9ca3af;font-size:.85em;">—</span>
                  <?php endif; ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($u['created_at'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?php if ($u['id'] == session('user_id')): ?>
                    <span style="padding:6px 10px;border:1px solid #d1d5db;border-radius:6px;color:#9ca3af;cursor:not-allowed;background:#f3f4f6;">Edit (Self)</span>
                  <?php else: ?>
                    <a class="btn" href="<?= site_url('admin/users/edit/' . $u['id']) ?>" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">Edit</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="7" style="padding:12px;text-align:center;color:#6b7280">No users found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
      <div style="color:#6b7280">Page <?= (int)($page ?? 1) ?><?php if(isset($total,$perPage)): ?> of <?= (int) ceil(($total)/($perPage)) ?> (<?= (int)$total ?> users)<?php endif; ?></div>
      <div style="display:flex;gap:8px">
        <?php if (!empty($hasPrev) && $hasPrev): ?>
          <a class="btn" href="<?= site_url('admin/users?page=' . max(1, ($page??1)-1)) ?>" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">Prev</a>
        <?php endif; ?>
        <?php if (!empty($hasNext) && $hasNext): ?>
          <a class="btn" href="<?= site_url('admin/users?page=' . (($page??1)+1)) ?>" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">Next</a>
        <?php endif; ?>
      </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<?= $this->endSection() ?>
