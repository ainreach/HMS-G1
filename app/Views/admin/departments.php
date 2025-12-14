<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="panel">
    <div class="panel-head" style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="margin: 0; font-size: 1.25rem; font-weight: 600;">
            <i class="fas fa-building me-2"></i>Hospital Departments
        </h2>
    </div>
    <div class="panel-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div style="margin-bottom:1rem">
            <a href="<?= site_url('admin/departments/new') ?>" class="btn" style="padding:8px 16px;background:#3b82f6;color:#fff;border:none;border-radius:6px;text-decoration:none;font-weight:600">
                <i class="fas fa-plus"></i> Create New Department
            </a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Department Name</th>
                        <th>Description</th>
                        <th>Doctors</th>
                        <th>Head Doctor</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($departments)): ?>
                        <?php foreach ($departments as $dept): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-admin"><?= esc($dept['code']) ?></span>
                                </td>
                                <td>
                                    <strong><?= esc($dept['name']) ?></strong>
                                </td>
                                <td class="text-muted" style="max-width:200px">
                                    <?= esc($dept['description'] ?? 'N/A') ?>
                                </td>
                                <td>
                                    <span style="display:inline-block;padding:2px 8px;border-radius:999px;background:#dbeafe;color:#1e40af;font-size:.85em;font-weight:600">
                                        <?= (int)($dept['doctor_count'] ?? 0) ?> doctor(s)
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($dept['head_doctor'])): ?>
                                        Dr. <?= esc(trim(($dept['head_doctor']['first_name'] ?? '') . ' ' . ($dept['head_doctor']['last_name'] ?? ''))) ?: esc($dept['head_doctor']['username'] ?? '') ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($dept['is_active']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-other">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display:flex;gap:0.5rem">
                                        <a href="<?= site_url('admin/departments/edit/' . $dept['id']) ?>" 
                                           class="btn" 
                                           style="padding:4px 8px;background:#3b82f6;color:#fff;border:none;border-radius:4px;text-decoration:none;font-size:0.75rem">
                                            Edit
                                        </a>
                                        <form method="post" action="<?= site_url('admin/departments/delete/' . $dept['id']) ?>" 
                                              style="margin:0" 
                                              onsubmit="return confirm('Are you sure you want to delete this department? This action cannot be undone.');">
                                            <?= csrf_field() ?>
                                            <button type="submit" 
                                                    class="btn" 
                                                    style="padding:4px 8px;background:#ef4444;color:#fff;border:none;border-radius:4px;font-size:0.75rem;cursor:pointer">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No departments found. <a href="<?= site_url('admin/departments/new') ?>">Create your first department</a> or run the DepartmentSeeder to populate default departments.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 p-3" style="background:#f9fafb;border-radius:8px;border:1px solid #e5e7eb">
            <h4 class="mb-2" style="font-size:1rem;font-weight:600">How Departments Work in This System:</h4>
            <ul class="mb-0" style="list-style: none; padding: 0;">
                <li class="mb-2" style="padding:0.5rem 0">
                    <i class="fas fa-user-md" style="color:#10b981;margin-right:0.5rem"></i>
                    <strong>Doctor Assignment:</strong> Assign doctors to departments via <a href="<?= site_url('admin/users') ?>" style="color:#3b82f6">Users Management</a>
                </li>
                <li class="mb-2" style="padding:0.5rem 0">
                    <i class="fas fa-calendar-check" style="color:#3b82f6;margin-right:0.5rem"></i>
                    <strong>Appointment Booking:</strong> Doctors are automatically grouped by department when booking appointments
                </li>
                <li class="mb-2" style="padding:0.5rem 0">
                    <i class="fas fa-user-injured" style="color:#f59e0b;margin-right:0.5rem"></i>
                    <strong>Patient Registration:</strong> When admitting in-patients, doctors are grouped by department for easy selection
                </li>
                <li class="mb-2" style="padding:0.5rem 0">
                    <i class="fas fa-user-tie" style="color:#8b5cf6;margin-right:0.5rem"></i>
                    <strong>Head Doctor:</strong> Assign a chief/head doctor to each department for leadership structure
                </li>
            </ul>
            <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #e5e7eb">
                <strong style="color:#6b7280;font-size:0.875rem">💡 Tip:</strong>
                <span style="color:#6b7280;font-size:0.875rem">
                    Departments help organize doctors by medical specialty (Cardiology, Pediatrics, etc.), making it easier to find the right specialist for patients.
                </span>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

