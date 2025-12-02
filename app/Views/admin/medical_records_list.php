<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Medical Records</h2>
  </div>
  <div class="panel-body">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <div>
        <h3 style="margin:0;font-size:1rem;color:#6b7280">Total Records: <?= number_format($total) ?></h3>
      </div>
      <div>
        <button class="btn" id="newRecordBtn" style="background:var(--primary);color:white;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;">
          <i class="fas fa-plus"></i> New Medical Record
        </button>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Record #</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Visit Date</th>
            <th>Chief Complaint</th>
            <th>Diagnosis</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($records)): ?>
            <?php foreach ($records as $record): ?>
              <tr>
                <td><?= esc($record['record_number'] ?? 'N/A') ?></td>
                <td><?= esc($record['patient_first_name'] . ' ' . $record['patient_last_name']) ?></td>
                <td>Dr. <?= esc($record['doctor_first_name'] . ' ' . $record['doctor_last_name']) ?></td>
                <td><?= date('M j, Y', strtotime($record['visit_date'])) ?></td>
                <td><?= esc(substr($record['chief_complaint'] ?? '', 0, 50)) ?><?= strlen($record['chief_complaint'] ?? '') > 50 ? '...' : '' ?></td>
                <td><?= esc(substr($record['diagnosis'] ?? '', 0, 50)) ?><?= strlen($record['diagnosis'] ?? '') > 50 ? '...' : '' ?></td>
                <td>
                  <a href="<?= site_url('doctor/records/' . $record['id']) ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                    <i class="fas fa-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" style="text-align:center;padding:2rem;color:#6b7280">
                No medical records found
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($total > $perPage): ?>
      <div class="pagination">
        <?php if ($hasPrev): ?>
          <a href="?page=<?= $page - 1 ?>">
            <i class="fas fa-chevron-left"></i> Previous
          </a>
        <?php endif; ?>
        
        <span class="current">Page <?= $page ?> of <?= ceil($total / $perPage) ?></span>
        
        <?php if ($hasNext): ?>
          <a href="?page=<?= $page + 1 ?>">
            Next <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Modal for New Medical Record -->
<div id="newRecordModal" class="modal-overlay">
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:600px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid var(--border);">
      <h3 style="margin:0;font-size:1.2rem">New Medical Record</h3>
      <button class="modal-close" id="closeModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:var(--muted);padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;">
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <form method="post" action="<?= site_url('doctor/records') ?>" id="modalRecordForm">
        <?= csrf_field() ?>
        <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
          <label style="display:block;margin-bottom:4px;font-weight:600;">Patient ID
            <input type="number" name="patient_id" value="<?= old('patient_id') ?>" required style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600;">Appointment ID
            <input type="number" name="appointment_id" value="<?= old('appointment_id') ?>" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600;">Visit Date/Time
            <input type="datetime-local" name="visit_date" value="<?= old('visit_date') ?>" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
        </div>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Chief Complaint
          <textarea name="chief_complaint" rows="2" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('chief_complaint') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">History of Present Illness
          <textarea name="history_present_illness" rows="3" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('history_present_illness') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Physical Examination
          <textarea name="physical_examination" rows="3" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('physical_examination') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Vital Signs (JSON)
          <textarea name="vital_signs" rows="2" placeholder='{"bp":"120/80","temp":"36.6"}' style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('vital_signs') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Diagnosis
          <textarea name="diagnosis" rows="2" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('diagnosis') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Treatment Plan
          <textarea name="treatment_plan" rows="2" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('treatment_plan') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Medications Prescribed (JSON)
          <textarea name="medications_prescribed" rows="2" placeholder='[{"drug":"Paracetamol","dose":"500mg"}]' style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('medications_prescribed') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Follow-up Instructions
          <textarea name="follow_up_instructions" rows="2" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('follow_up_instructions') ?></textarea>
        </label>
        <label style="display:block;margin-bottom:4px;font-weight:600;">Next Visit Date
          <input type="date" name="next_visit_date" value="<?= old('next_visit_date') ?>" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
        </label>
        <div style="margin-top:16px;display:flex;gap:10px;justify-content:flex-end;">
          <button type="submit" class="btn btn-primary" style="background:var(--primary);color:white;padding:10px 20px;border:none;border-radius:6px;cursor:pointer;">Save</button>
          <button type="button" class="btn" id="cancelModal" style="background:var(--border);color:var(--text);padding:10px 20px;border:none;border-radius:6px;cursor:pointer;">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
// Modal functionality
document.getElementById('newRecordBtn').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('newRecordModal').classList.add('show');
});

document.getElementById('closeModal').addEventListener('click', function() {
  document.getElementById('newRecordModal').classList.remove('show');
});

document.getElementById('cancelModal').addEventListener('click', function() {
  document.getElementById('newRecordModal').classList.remove('show');
});

// Close modal when clicking outside
document.getElementById('newRecordModal').addEventListener('click', function(e) {
  if (e.target === this) {
    this.classList.remove('show');
  }
});

// Handle form submission
document.getElementById('modalRecordForm').addEventListener('submit', function(e) {
  // Form will submit normally to the server
  // Modal will close after successful submission or redirect
});
</script>
<?= $this->endSection() ?>
