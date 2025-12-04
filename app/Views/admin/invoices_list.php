<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">All Invoices (<?= number_format($total) ?>)</h2>
  </div>
  <div class="panel-body">
    <?php if (!empty($invoices)): ?>
      <table class="table">
        <thead>
          <tr>
            <th>Invoice #</th>
            <th>Patient Name</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Issued Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($invoices as $invoice): ?>
            <?php
              $rawStatus = strtolower(trim($invoice['status'] ?? ''));
              $status = $rawStatus !== '' ? $rawStatus : 'unpaid';
              $statusLabel = ucfirst($status);
              $statusClass = $status === 'paid' ? 'success' : ($status === 'partial' ? 'warning' : 'warning');
            ?>
            <tr>
              <td><?= esc($invoice['invoice_no']) ?></td>
              <td><?= esc($invoice['patient_name']) ?></td>
              <td>$<?= number_format($invoice['amount'], 2) ?></td>
              <td>
                <span class="badge badge-<?= $statusClass ?>">
                  <?= esc($statusLabel) ?>
                </span>
              </td>
              <td><?= date('M j, Y', strtotime($invoice['issued_at'])) ?></td>
              <td>
                <button
                  type="button"
                  class="btn btn-secondary view-invoice-btn"
                  data-invoice='<?= json_encode([
                    'invoice_no'   => $invoice['invoice_no'] ?? '',
                    'patient_name' => $invoice['patient_name'] ?? '',
                    'amount'       => (float)($invoice['amount'] ?? 0),
                    'status'       => $invoice['status'] ?? 'unpaid',
                    'issued_at'    => $invoice['issued_at'] ?? '',
                  ]) ?>'
                  style="padding:0.25rem 0.5rem;font-size:0.75rem;"
                >
                  <i class="fas fa-eye"></i> View
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      
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
    <?php else: ?>
      <div style="text-align: center; padding: 2rem; color: #6b7280;">
        <i class="fas fa-file-invoice" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
        <h3>No invoices found</h3>
        <p>There are no invoices in the system yet.</p>
      </div>
    <?php endif; ?>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
// Floating modal for invoice details (stays on /admin/invoices)
const viewInvoiceModal = document.createElement('div');
viewInvoiceModal.id = 'viewInvoiceModal';
viewInvoiceModal.className = 'modal-overlay';
viewInvoiceModal.innerHTML = `
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid var(--border);background:linear-gradient(to right,#0ea5e9,#0369a1);color:white;">
      <div>
        <div id="iv-invoice-no" style="font-size:0.8rem;opacity:.9;"></div>
        <h3 style="margin:0;font-size:1.1rem;">Invoice Details</h3>
      </div>
      <button class="modal-close" id="closeViewInvoiceModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:#e5e7eb;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:10px 16px;font-size:0.9rem;">
      <div><div class="field-label">Patient</div><div id="iv-patient" class="field-value"></div></div>
      <div><div class="field-label">Amount</div><div id="iv-amount" class="field-value"></div></div>
      <div><div class="field-label">Status</div><div id="iv-status" class="field-value"></div></div>
      <div><div class="field-label">Issued At</div><div id="iv-issued" class="field-value"></div></div>
    </div>
    <div class="modal-footer" style="padding:12px 18px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f9fafb;">
      <button type="button" id="closeViewInvoiceBtn" class="btn" style="background:#e5e7eb;color:#111827;border:none;">Close</button>
    </div>
  </div>
`;
document.body.appendChild(viewInvoiceModal);

function closeViewInvoice() {
  viewInvoiceModal.classList.remove('show');
}

document.getElementById('closeViewInvoiceModal').addEventListener('click', closeViewInvoice);
document.getElementById('closeViewInvoiceBtn').addEventListener('click', closeViewInvoice);
viewInvoiceModal.addEventListener('click', function(e) {
  if (e.target === this) closeViewInvoice();
});

document.querySelectorAll('.view-invoice-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const data = JSON.parse(this.getAttribute('data-invoice'));
    document.getElementById('iv-invoice-no').textContent = 'Invoice #' + (data.invoice_no || '');
    document.getElementById('iv-patient').textContent = data.patient_name || '—';
    document.getElementById('iv-amount').textContent = '$' + (Number(data.amount || 0).toFixed(2));
    document.getElementById('iv-status').textContent = (data.status || 'unpaid').charAt(0).toUpperCase() + (data.status || 'unpaid').slice(1);
    document.getElementById('iv-issued').textContent = data.issued_at || '—';
    viewInvoiceModal.classList.add('show');
  });
});
</script>
<?= $this->endSection() ?>
