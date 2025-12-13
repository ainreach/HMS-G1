<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="panel">
  <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
    <div>
      <h2 style="margin:0;font-size:1.25rem;font-weight:600">Edit Medical Record</h2>
      <p style="margin:4px 0 0 0;color:#6b7280;font-size:0.875rem">
        Record <?= esc($record['record_number'] ?? ('#' . $record['id'])) ?>
      </p>
    </div>
    <a href="<?= site_url('admin/medical-records') ?>" class="btn" style="text-decoration:none">
      Back to Medical Records
    </a>
  </div>

  <div class="panel-body" style="max-width:900px">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('admin/medical-records/' . $record['id']) ?>">
      <?= csrf_field() ?>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Patient *</label>
          <select name="patient_id" required style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
            <?php foreach ($patients as $p): ?>
              <option value="<?= $p['id'] ?>" <?= (int)($record['patient_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>>
                <?= esc(($p['last_name'] ?? '') . ', ' . ($p['first_name'] ?? '')) ?> (<?= esc($p['patient_id'] ?? '') ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Visit Date/Time *</label>
          <input type="datetime-local" name="visit_date" value="<?= esc(date('Y-m-d\TH:i', strtotime($record['visit_date'] ?? 'now'))) ?>" required 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
        </div>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Chief Complaint</label>
        <textarea name="chief_complaint" rows="2" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;resize:vertical;"><?= esc($record['chief_complaint'] ?? '') ?></textarea>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">History of Present Illness</label>
        <textarea name="history_present_illness" rows="3" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;resize:vertical;"><?= esc($record['history_present_illness'] ?? '') ?></textarea>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Physical Examination</label>
        <textarea name="physical_examination" rows="3" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;resize:vertical;"><?= esc($record['physical_examination'] ?? '') ?></textarea>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Vital Signs (JSON)</label>
        <textarea name="vital_signs" rows="2" placeholder='{"bp":"120/80","temp":"36.6"}' style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;resize:vertical;"><?= esc($record['vital_signs'] ?? '') ?></textarea>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Diagnosis</label>
        <textarea name="diagnosis" rows="2" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;resize:vertical;"><?= esc($record['diagnosis'] ?? '') ?></textarea>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Treatment Plan</label>
        <textarea name="treatment_plan" rows="2" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;resize:vertical;"><?= esc($record['treatment_plan'] ?? '') ?></textarea>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Medications Prescribed (JSON)</label>
        <textarea name="medications_prescribed" rows="2" placeholder='[{"drug":"Paracetamol","dose":"500mg"}]' style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;resize:vertical;"><?= esc($record['medications_prescribed'] ?? '') ?></textarea>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Follow-up Instructions</label>
        <textarea name="follow_up_instructions" rows="2" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;resize:vertical;"><?= esc($record['follow_up_instructions'] ?? '') ?></textarea>
      </div>

      <div style="margin-bottom:20px">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Next Visit Date</label>
        <input type="date" name="next_visit_date" value="<?= esc($record['next_visit_date'] ?? '') ?>" 
               style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:16px;border-top:1px solid #e5e7eb;">
        <a href="<?= site_url('admin/medical-records') ?>" class="btn" style="padding:10px 20px;background:white;border:1px solid #d1d5db;border-radius:6px;text-decoration:none;color:#374151;font-weight:500;">
          Cancel
        </a>
        <button type="submit" class="btn btn-primary" style="padding:10px 20px;background:var(--primary);color:white;border:none;border-radius:6px;font-weight:500;cursor:pointer;">
          <i class="fas fa-save"></i> Update Record
        </button>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>
