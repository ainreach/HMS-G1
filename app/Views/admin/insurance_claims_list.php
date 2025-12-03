<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Insurance Claims</h2>
  </div>
  <div class="panel-body">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <div>
        <h3 style="margin:0;font-size:1rem;color:#6b7280">Total Claims: <?= number_format($total) ?></h3>
      </div>
      <div>
        <button class="btn" id="newClaimBtn" style="background:var(--primary);color:white;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;">
          <i class="fas fa-plus"></i> New Claim
        </button>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Claim #</th>
            <th>Patient Name</th>
            <th>Provider</th>
            <th>Policy #</th>
            <th>Amount Claimed</th>
            <th>Amount Approved</th>
            <th>Status</th>
            <th>Submitted Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($claims)): ?>
            <?php foreach ($claims as $claim): ?>
              <tr>
                <td><?= esc($claim['claim_no'] ?? 'N/A') ?></td>
                <td><?= esc($claim['patient_name'] ?? 'N/A') ?></td>
                <td><?= esc($claim['provider'] ?? 'N/A') ?></td>
                <td><?= esc($claim['policy_no'] ?? 'N/A') ?></td>
                <td style="font-weight:600">$<?= number_format($claim['amount_claimed'], 2) ?></td>
                <td style="font-weight:600;color:#10b981">$<?= number_format($claim['amount_approved'] ?? 0, 2) ?></td>
                <td>
                  <?php
                    $status = strtolower($claim['status'] ?? 'submitted');
                    $badgeClass = 'badge-other';
                    if (in_array($status, ['approved', 'submitted', 'rejected', 'pending'])) {
                      $badgeClass = 'badge-' . $status;
                    }
                  ?>
                  <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                </td>
                <td><?= date('M j, Y', strtotime($claim['submitted_at'])) ?></td>
                <td>
                  <a href="<?= site_url('accountant/claims/' . $claim['id']) ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                    <i class="fas fa-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" style="text-align:center;padding:2rem;color:#6b7280">
                No insurance claims found
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

<!-- Modal for New Claim -->
<div id="newClaimModal" class="modal-overlay">
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:560px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid var(--border);">
      <h3 style="margin:0;font-size:1.2rem">New Insurance Claim</h3>
      <button class="modal-close" id="closeClaimModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:var(--muted);padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;">
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <form id="modalClaimForm" method="post" action="<?= site_url('accountant/claims') ?>" style="display:grid;gap:12px">
        <?= csrf_field() ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <label style="display:block;margin-bottom:4px;font-weight:600">Claim # <span style="color:#dc2626">*</span>
            <input type="text" name="claim_no" value="CLAIM-<?= time() ?>-<?= rand(100, 999) ?>" required style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600">Invoice #
            <input type="text" name="invoice_no" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <label style="display:block;margin-bottom:4px;font-weight:600">Patient Name <span style="color:#dc2626">*</span>
            <input type="text" name="patient_name" required style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600">Provider
            <input type="text" name="provider" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <label style="display:block;margin-bottom:4px;font-weight:600">Policy #
            <input type="text" name="policy_no" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600">Status
            <select name="status" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
              <option value="submitted">Submitted</option>
              <option value="in_review">In Review</option>
              <option value="approved">Approved</option>
              <option value="denied">Denied</option>
              <option value="paid">Paid</option>
            </select>
          </label>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <label style="display:block;margin-bottom:4px;font-weight:600">Amount Claimed ($) <span style="color:#dc2626">*</span>
            <input type="number" name="amount_claimed" step="0.01" required style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
          <label style="display:block;margin-bottom:4px;font-weight:600">Amount Approved ($)
            <input type="number" name="amount_approved" step="0.01" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
          </label>
        </div>
        <label style="display:block;margin-bottom:4px;font-weight:600">Submitted At
          <input type="datetime-local" name="submitted_at" value="<?= date('Y-m-d\TH:i') ?>" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px;">
        </label>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
          <button type="submit" style="background:var(--primary);color:white;padding:10px 20px;border-radius:6px;border:none;cursor:pointer;font-weight:600">Save Claim</button>
          <button type="button" id="cancelClaimModal" style="background:var(--border);color:var(--text);padding:10px 20px;border-radius:6px;border:none;cursor:pointer;">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
// Modal functionality for New Claim
document.getElementById('newClaimBtn').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('newClaimModal').classList.add('show');
});

document.getElementById('closeClaimModal').addEventListener('click', function() {
  document.getElementById('newClaimModal').classList.remove('show');
});

document.getElementById('cancelClaimModal').addEventListener('click', function() {
  document.getElementById('newClaimModal').classList.remove('show');
});

// Close modal when clicking outside
document.getElementById('newClaimModal').addEventListener('click', function(e) {
  if (e.target === this) {
    this.classList.remove('show');
  }
});
</script>
<?= $this->endSection() ?>
