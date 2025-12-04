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
                  <button
                    type="button"
                    class="btn btn-secondary view-claim-btn"
                    data-claim='<?= json_encode([
                      'claim_no' => $claim['claim_no'] ?? '',
                      'invoice_no' => $claim['invoice_no'] ?? '',
                      'patient_name' => $claim['patient_name'] ?? '',
                      'provider' => $claim['provider'] ?? '',
                      'policy_no' => $claim['policy_no'] ?? '',
                      'amount_claimed' => (float)($claim['amount_claimed'] ?? 0),
                      'amount_approved' => (float)($claim['amount_approved'] ?? 0),
                      'status' => $claim['status'] ?? 'submitted',
                      'submitted_at' => $claim['submitted_at'] ?? '',
                    ]) ?>'
                    style="padding:0.25rem 0.5rem;font-size:0.75rem;"
                  >
                    <i class="fas fa-eye"></i> View
                  </button>
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

// View Claim modal (stays on /admin/insurance-claims)
const viewClaimModal = document.createElement('div');
viewClaimModal.id = 'viewClaimModal';
viewClaimModal.className = 'modal-overlay';
viewClaimModal.innerHTML = `
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:560px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid var(--border);background:linear-gradient(to right,#0ea5e9,#0369a1);color:white;">
      <div>
        <div id="vc-claim-no" style="font-size:0.8rem;opacity:.9;"></div>
        <h3 style="margin:0;font-size:1.1rem;">Insurance Claim Details</h3>
      </div>
      <button class="modal-close" id="closeViewClaimModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:#e5e7eb;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:10px 16px;font-size:0.9rem;">
      <div><div class="field-label">Invoice #</div><div id="vc-invoice" class="field-value"></div></div>
      <div><div class="field-label">Patient</div><div id="vc-patient" class="field-value"></div></div>
      <div><div class="field-label">Provider</div><div id="vc-provider" class="field-value"></div></div>
      <div><div class="field-label">Policy #</div><div id="vc-policy" class="field-value"></div></div>
      <div><div class="field-label">Amount Claimed</div><div id="vc-amount-claimed" class="field-value"></div></div>
      <div><div class="field-label">Amount Approved</div><div id="vc-amount-approved" class="field-value"></div></div>
      <div><div class="field-label">Status</div><div id="vc-status" class="field-value"></div></div>
      <div><div class="field-label">Submitted At</div><div id="vc-submitted" class="field-value"></div></div>
    </div>
    <div class="modal-footer" style="padding:12px 18px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f9fafb;">
      <button type="button" id="closeViewClaimBtn" class="btn" style="background:#e5e7eb;color:#111827;border:none;">Close</button>
    </div>
  </div>
`;
document.body.appendChild(viewClaimModal);

function closeViewClaim() {
  viewClaimModal.classList.remove('show');
}

document.getElementById('closeViewClaimModal').addEventListener('click', closeViewClaim);
document.getElementById('closeViewClaimBtn').addEventListener('click', closeViewClaim);
viewClaimModal.addEventListener('click', function(e) {
  if (e.target === this) closeViewClaim();
});

document.querySelectorAll('.view-claim-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const data = JSON.parse(this.getAttribute('data-claim'));
    document.getElementById('vc-claim-no').textContent = 'Claim #' + (data.claim_no || '');
    document.getElementById('vc-invoice').textContent = data.invoice_no || '—';
    document.getElementById('vc-patient').textContent = data.patient_name || '—';
    document.getElementById('vc-provider').textContent = data.provider || '—';
    document.getElementById('vc-policy').textContent = data.policy_no || '—';
    document.getElementById('vc-amount-claimed').textContent = '$' + (Number(data.amount_claimed || 0).toFixed(2));
    document.getElementById('vc-amount-approved').textContent = '$' + (Number(data.amount_approved || 0).toFixed(2));
    document.getElementById('vc-status').textContent = (data.status || 'submitted').charAt(0).toUpperCase() + (data.status || 'submitted').slice(1);
    document.getElementById('vc-submitted').textContent = data.submitted_at || '—';
    viewClaimModal.classList.add('show');
  });
});
</script>
<?= $this->endSection() ?>
