<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Lab Tests</h2>
  </div>
  <div class="panel-body">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <div>
        <h3 style="margin:0;font-size:1rem;color:#6b7280">Total Tests: <?= number_format($total) ?></h3>
      </div>
      <div>
        <button class="btn" id="newLabRequestBtn" style="background:var(--primary);color:white;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;">
          <i class="fas fa-plus"></i> New Lab Request
        </button>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Test #</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Test Type</th>
            <th>Test Name</th>
            <th>Status</th>
            <th>Requested Date</th>
            <th>Result Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($labTests)): ?>
            <?php foreach ($labTests as $test): ?>
              <tr>
                <td><?= esc($test['test_number'] ?? 'N/A') ?></td>
                <td><?= esc($test['patient_first_name'] . ' ' . $test['patient_last_name']) ?></td>
                <td>Dr. <?= esc($test['doctor_first_name'] . ' ' . $test['doctor_last_name']) ?></td>
                <td><?= esc($test['test_type'] ?? 'N/A') ?></td>
                <td><?= esc($test['test_name'] ?? 'N/A') ?></td>
                <td>
                  <?php
                    $status = strtolower($test['status'] ?? 'requested');
                    $badgeClass = 'badge-other';
                    if (in_array($status, ['requested', 'in_progress', 'completed', 'cancelled'])) {
                      $badgeClass = 'badge-' . $status;
                    }
                  ?>
                  <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                </td>
                <td><?= date('M j, Y', strtotime($test['requested_date'])) ?></td>
                <td><?= $test['result_date'] ? date('M j, Y', strtotime($test['result_date'])) : 'Pending' ?></td>
                <td>
                  <a href="<?= site_url('lab/tests/' . $test['id']) ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                    <i class="fas fa-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" style="text-align:center;padding:2rem;color:#6b7280">
                No lab tests found
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

<!-- Modal for New Lab Request -->
<div id="newLabRequestModal" class="modal-overlay">
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:600px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid var(--border);">
      <h3 style="margin:0;font-size:1.2rem">New Lab Request</h3>
      <button class="modal-close" id="closeLabRequestModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:var(--muted);padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;">
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <form id="modalLabRequestForm" method="post" action="<?= site_url('doctor/lab-requests') ?>" style="display:grid;gap:12px">
        <?= csrf_field() ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
          <label style="display:block;margin-bottom:4px;font-weight:600">Patient ID <span style="color:#dc2626">*</span>
            <input type="number" name="patient_id" value="<?= old('patient_id') ?>" required style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600">Test Category <span style="color:#dc2626">*</span>
            <select name="test_category" required style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
              <option value="">Select</option>
              <option value="blood">Blood</option>
              <option value="urine">Urine</option>
              <option value="imaging">Imaging</option>
              <option value="pathology">Pathology</option>
              <option value="microbiology">Microbiology</option>
              <option value="other">Other</option>
            </select>
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600">Test Name <span style="color:#dc2626">*</span>
            <input type="text" name="test_name" value="<?= old('test_name') ?>" required style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600">Priority
            <select name="priority" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
              <option value="routine">Routine</option>
              <option value="urgent">Urgent</option>
              <option value="stat">STAT</option>
            </select>
          </label>
        </div>
        <label style="display:block;margin-bottom:4px;font-weight:600">Notes
          <textarea name="notes" rows="3" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;resize:vertical;"><?= old('notes') ?></textarea>
        </label>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
          <button type="submit" style="background:var(--primary);color:white;padding:10px 20px;border-radius:6px;border:none;cursor:pointer;font-weight:600">Submit</button>
          <button type="button" id="cancelLabRequestModal" style="background:var(--border);color:var(--text);padding:10px 20px;border-radius:6px;border:none;cursor:pointer;">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
// Modal functionality for New Lab Request
document.getElementById('newLabRequestBtn').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('newLabRequestModal').classList.add('show');
});

document.getElementById('closeLabRequestModal').addEventListener('click', function() {
  document.getElementById('newLabRequestModal').classList.remove('show');
});

document.getElementById('cancelLabRequestModal').addEventListener('click', function() {
  document.getElementById('newLabRequestModal').classList.remove('show');
});

// Close modal when clicking outside
document.getElementById('newLabRequestModal').addEventListener('click', function(e) {
  if (e.target === this) {
    this.classList.remove('show');
  }
});
</script>
<?= $this->endSection() ?>
