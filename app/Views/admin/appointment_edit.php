<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Edit Appointment</h2>
  </div>
  <div class="panel-body">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <form action="<?= site_url('admin/appointments/' . ($appointment['id'] ?? 0)) ?>" method="post" style="display:grid;gap:16px;max-width:600px">
      <?= csrf_field() ?>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Patient *</label>
          <select name="patient_id" required style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
            <option value="">-- Select Patient --</option>
            <?php foreach (($patients ?? []) as $p): ?>
              <option value="<?= esc($p['id']) ?>" <?= (int)($appointment['patient_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>>
                <?= esc(($p['last_name'] ?? '') . ', ' . ($p['first_name'] ?? '')) ?> (<?= esc($p['patient_id'] ?? '') ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Doctor *</label>
          <select name="doctor_id" required style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
            <option value="">-- Select Doctor --</option>
            <?php foreach (($doctors ?? []) as $d): ?>
              <option value="<?= esc($d['id']) ?>" <?= (int)($appointment['doctor_id'] ?? 0) === (int)$d['id'] ? 'selected' : '' ?>>
                Dr. <?= esc(($d['first_name'] ?? '') . ' ' . ($d['last_name'] ?? '')) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Date *</label>
          <input type="date" name="appointment_date" value="<?= esc($appointment['appointment_date'] ?? '') ?>" required 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Time *</label>
          <input type="time" name="appointment_time" value="<?= esc($appointment['appointment_time'] ?? '') ?>" required 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Duration (minutes)</label>
          <input type="number" name="duration" min="5" step="5" value="<?= esc($appointment['duration'] ?? 30) ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Status</label>
          <select name="status" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
            <option value="scheduled" <?= ($appointment['status'] ?? '') === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
            <option value="confirmed" <?= ($appointment['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
            <option value="in_progress" <?= ($appointment['status'] ?? '') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="completed" <?= ($appointment['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="cancelled" <?= ($appointment['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            <option value="no_show" <?= ($appointment['status'] ?? '') === 'no_show' ? 'selected' : '' ?>>No Show</option>
          </select>
        </div>
      </div>

      <div>
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Type</label>
        <select name="type" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
          <option value="consultation" <?= ($appointment['type'] ?? '') === 'consultation' ? 'selected' : '' ?>>Consultation</option>
          <option value="follow_up" <?= ($appointment['type'] ?? '') === 'follow_up' ? 'selected' : '' ?>>Follow-up</option>
          <option value="emergency" <?= ($appointment['type'] ?? '') === 'emergency' ? 'selected' : '' ?>>Emergency</option>
          <option value="surgery" <?= ($appointment['type'] ?? '') === 'surgery' ? 'selected' : '' ?>>Surgery</option>
          <option value="checkup" <?= ($appointment['type'] ?? '') === 'checkup' ? 'selected' : '' ?>>Checkup</option>
        </select>
      </div>

      <div>
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Reason</label>
        <input type="text" name="reason" value="<?= esc($appointment['reason'] ?? '') ?>" 
               style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
               placeholder="Appointment reason" />
      </div>

      <div>
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Notes</label>
        <textarea name="notes" rows="3" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;resize:vertical;"
                  placeholder="Additional notes"><?= esc($appointment['notes'] ?? '') ?></textarea>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:16px;border-top:1px solid #e5e7eb;">
        <a href="<?= site_url('admin/appointments') ?>" class="btn" style="padding:10px 20px;background:white;border:1px solid #d1d5db;border-radius:6px;text-decoration:none;color:#374151;font-weight:500;">
          Cancel
        </a>
        <button type="submit" class="btn btn-primary" style="padding:10px 20px;background:var(--primary);color:white;border:none;border-radius:6px;font-weight:500;cursor:pointer;">
          <i class="fas fa-save"></i> Save Changes
        </button>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<?= $this->endSection() ?>

