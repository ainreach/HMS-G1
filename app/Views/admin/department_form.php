<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <?php if (session('success')): ?>
      <div class="alert alert-success" style="margin-bottom: 1rem;"><?= esc(session('success')) ?></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div class="alert alert-error" style="margin-bottom: 1rem;"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <h2 style="margin:0"><?= $department ? 'Edit Department' : 'Create New Department' ?></h2>
      <a href="<?= site_url('admin/departments') ?>" class="btn btn-secondary" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Back to Departments</a>
    </div>

    <section class="panel">
      <div class="panel-body">
        <form method="post" action="<?= esc($formAction) ?>">
          <?= csrf_field() ?>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
            <div>
              <label style="display:block;margin-bottom:0.5rem;font-weight:600">Department Name <span style="color:#ef4444">*</span></label>
              <input type="text" name="name" value="<?= esc($department['name'] ?? old('name', '')) ?>" required 
                     style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem"
                     placeholder="e.g., Cardiology, Pediatrics">
            </div>
            <div>
              <label style="display:block;margin-bottom:0.5rem;font-weight:600">Department Code <span style="color:#ef4444">*</span></label>
              <input type="text" name="code" value="<?= esc($department['code'] ?? old('code', '')) ?>" required 
                     style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem"
                     placeholder="e.g., CARD, PED" maxlength="20">
              <small style="color:#6b7280;font-size:0.75rem">Unique code (max 20 characters)</small>
            </div>
          </div>

          <div style="margin-bottom:1rem">
            <label style="display:block;margin-bottom:0.5rem;font-weight:600">Description</label>
            <textarea name="description" rows="3" 
                      style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem"
                      placeholder="Brief description of the department"><?= esc($department['description'] ?? old('description', '')) ?></textarea>
          </div>

          <div style="margin-bottom:1rem">
            <label style="display:block;margin-bottom:0.5rem;font-weight:600">Head Doctor</label>
            <select name="head_doctor_id" 
                    style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem">
              <option value="">-- No Head Doctor --</option>
              <?php if (!empty($doctors)): ?>
                <?php foreach ($doctors as $doctor): ?>
                  <option value="<?= $doctor['id'] ?>" 
                          <?= ($department['head_doctor_id'] ?? null) == $doctor['id'] ? 'selected' : '' ?>>
                    Dr. <?= esc(trim(($doctor['first_name'] ?? '') . ' ' . ($doctor['last_name'] ?? ''))) ?: esc($doctor['username'] ?? '') ?>
                    <?php if (!empty($doctor['specialization'])): ?>
                      - <?= esc($doctor['specialization']) ?>
                    <?php endif; ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
            <small style="color:#6b7280;font-size:0.75rem">Select the chief/head of this department (optional)</small>
          </div>

          <div style="margin-bottom:1rem">
            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
              <input type="checkbox" name="is_active" value="1" 
                     <?= ($department['is_active'] ?? 1) ? 'checked' : '' ?>>
              <span style="font-weight:600">Active Department</span>
            </label>
            <small style="color:#6b7280;font-size:0.75rem;display:block;margin-top:0.25rem">Inactive departments won't appear in dropdowns</small>
          </div>

          <?php if ($department && !empty($departmentDoctors)): ?>
            <div style="margin-bottom:1rem;padding:1rem;background:#f9fafb;border-radius:8px;border:1px solid #e5e7eb">
              <h4 style="margin:0 0 0.75rem 0;font-size:1rem;font-weight:600">Doctors in this Department (<?= count($departmentDoctors) ?>)</h4>
              <div style="display:flex;flex-wrap:wrap;gap:0.5rem">
                <?php foreach ($departmentDoctors as $doc): ?>
                  <span style="padding:0.25rem 0.75rem;background:#fff;border:1px solid #d1d5db;border-radius:999px;font-size:0.875rem">
                    Dr. <?= esc(trim(($doc['first_name'] ?? '') . ' ' . ($doc['last_name'] ?? ''))) ?: esc($doc['username'] ?? '') ?>
                  </span>
                <?php endforeach; ?>
              </div>
              <small style="color:#6b7280;font-size:0.75rem;display:block;margin-top:0.5rem">
                To reassign doctors, edit them individually in <a href="<?= site_url('admin/users') ?>" style="color:#3b82f6">Users Management</a>
              </small>
            </div>
          <?php endif; ?>

          <div style="display:flex;gap:0.75rem;margin-top:1.5rem">
            <button type="submit" class="btn" style="padding:0.5rem 1.5rem;background:#3b82f6;color:#fff;border:none;border-radius:6px;font-weight:600;cursor:pointer">
              <?= $department ? 'Update Department' : 'Create Department' ?>
            </button>
            <a href="<?= site_url('admin/departments') ?>" class="btn btn-secondary" style="padding:0.5rem 1.5rem;background:#6b7280;color:#fff;border:none;border-radius:6px;text-decoration:none;font-weight:600">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </section>

<?= $this->endSection() ?>

