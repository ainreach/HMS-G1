<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="panel" style="margin-top:16px">
  <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Financial Summary</h2></div>
  <div class="panel-body">
    <div class="row">
      <div class="col-md-4">
        <div class="card text-center mb-3">
          <div class="card-body">
            <h6 class="card-title text-muted">Outstanding Balance</h6>
            <h3 class="text-danger">₱<?= number_format((float)($outstandingBalance ?? 0), 2) ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center mb-3">
          <div class="card-body">
            <h6 class="card-title text-muted">Total Collected</h6>
            <h3 class="text-success">₱<?= number_format((float)($totalCollected ?? 0), 2) ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center mb-3">
          <div class="card-body">
            <h6 class="card-title text-muted">Overdue Amount</h6>
            <h3 class="text-warning">₱<?= number_format((float)($overdueAmount ?? 0), 2) ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="panel" style="margin-top:16px">
  <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Invoice Records</h2></div>
  <div class="panel-body">
    <div style="margin-bottom:16px">
      <input type="text" placeholder="Search invoices..." style="width:300px;padding:8px;border:1px solid #d1d5db;border-radius:4px" id="invoiceSearch">
    </div>
    <div style="overflow:auto">
      <table class="table" style="width:100%;border-collapse:collapse">
        <thead>
          <tr>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Invoice #</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient Name</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Bill Date</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Due Date</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Total Amount</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Amount Paid</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Balance</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
          </tr>
        </thead>
        <tbody id="invoiceTableBody">
          <?php if (!empty($billingRecords ?? [])) : ?>
            <?php foreach ($billingRecords as $bill) : ?>
              <?php
                $patient = $bill['patient'];
                $room = $bill['room'];
                $totalAmount = (float)($bill['totalCharges'] ?? 0);
                $amountPaid = (float)($bill['totalPayments'] ?? 0);
                $balance = (float)($bill['balanceDue'] ?? 0);
                $fullName = trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''));
                $billCode = $patient['patient_id'] ?? ('PAT-' . ($patient['id'] ?? '')); 
                $billDate = $patient['admission_date'] ?? '';
              ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($billCode) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($fullName) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($billDate ? date('M d, Y', strtotime($billDate)) : '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">P<?= number_format($totalAmount, 2) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">P<?= number_format($amountPaid, 2) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">P<?= number_format($balance, 2) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <?php $status = $balance > 0 ? 'Unpaid' : 'Paid'; ?>
                  <span class="badge <?= $balance > 0 ? 'badge-warning' : 'badge-success' ?>"><?= esc($status) ?></span>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <div style="display:flex;gap:6px;flex-wrap:wrap">
                    <a href="<?= site_url('accountant/patients/billing/' . ($patient['id'] ?? '')) ?>" class="btn-small" style="background:#0ea5e9;color:white;padding:4px 8px;border-radius:4px;text-decoration:none;font-size:0.75rem">
                      <i class="fa-solid fa-eye"></i> View
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="9" style="padding:12px;text-align:center;color:#6b7280">No records found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px">
  <section class="panel">
    <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Payment Entry</h2></div>
    <div class="panel-body">
      <form id="paymentForm" method="post" action="<?= site_url('accountant/storePayment') ?>" style="display:flex;flex-direction:column;gap:12px">
        <div>
          <label style="display:block;margin-bottom:4px;font-weight:500">Select Invoice</label>
          <select name="invoice_no" id="invoiceSelect" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px" required>
            <option value="">Choose an invoice...</option>
            <?php if (!empty($invoices)) : ?>
              <?php foreach ($invoices as $i) : ?>
                <?php if (strtolower((string)($i['status'] ?? '')) !== 'paid') : ?>
                  <option value="<?= esc($i['invoice_no'] ?? '') ?>" data-patient="<?= esc($i['patient_name'] ?? '') ?>" data-amount="<?= (float)($i['amount'] ?? 0) ?>"><?= esc($i['invoice_no'] ?? '') ?> - <?= esc($i['patient_name'] ?? '') ?> - P<?= number_format((float)($i['amount'] ?? 0), 2) ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <div>
          <label style="display:block;margin-bottom:4px;font-weight:500">Payment Amount</label>
          <input type="number" name="amount" id="paymentAmount" step="0.01" min="0" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px" placeholder="0.00" required>
        </div>
        <div>
          <label style="display:block;margin-bottom:4px;font-weight:500">Payment Method</label>
          <select name="payment_method" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px" required>
            <option value="">Select method...</option>
            <option value="cash">Cash</option>
            <option value="card">Credit/Debit Card</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="check">Check</option>
            <option value="online">Online Payment</option>
          </select>
        </div>
        <div>
          <label style="display:block;margin-bottom:4px;font-weight:500">Payment Date</label>
          <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px" required>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" style="background:#10b981;color:white;padding:10px 16px;border-radius:6px;border:none;cursor:pointer;font-weight:500">Record Payment</button>
          <button type="button" onclick="resetPaymentForm()" style="background:#6b7280;color:white;padding:10px 16px;border-radius:6px;border:none;cursor:pointer">Clear</button>
        </div>
      </form>
    </div>
  </section>

  <section class="panel">
    <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Payment Transaction</h2></div>
    <div class="panel-body" style="overflow:auto">
      <table class="table" style="width:100%;border-collapse:collapse">
        <thead>
          <tr>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
            <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($payments)) : ?>
            <?php foreach (array_slice($payments, 0, 10) as $p) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('M d, Y', strtotime($p['paid_at']))) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($p['patient_name'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">P<?= number_format((float)($p['amount'] ?? 0), 2) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="3" style="padding:12px;text-align:center;color:#6b7280">No payment transactions.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>

<section class="panel" style="margin-top:16px">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">All Payments</h2>
    <div style="display:flex;gap:10px">
      <button class="btn" id="newInvoiceBtn" style="background:#0ea5e9;color:white;padding:8px 12px;border-radius:6px;border:none;font-size:0.875rem;cursor:pointer"><i class="fa-regular fa-file-lines"></i> Create New Invoice</button>
      <button class="btn" id="newPaymentBtn" style="background:#10b981;color:white;padding:8px 12px;border-radius:6px;border:none;font-size:0.875rem;cursor:pointer"><i class="fa-solid fa-sack-dollar"></i> New Payment</button>
    </div>
  </div>
  <div class="panel-body">
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Payment ID</th>
            <th>Patient Name</th>
            <th>Invoice #</th>
            <th>Amount</th>
            <th>Paid Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($payments)): ?>
            <?php foreach ($payments as $payment): ?>
              <tr>
                <td>#<?= $payment['id'] ?></td>
                <td><?= esc($payment['patient_name'] ?? 'N/A') ?></td>
                <td><?= esc($payment['invoice_no'] ?? 'N/A') ?></td>
                <td style="font-weight:600;color:#10b981">P<?= number_format($payment['amount'], 2) ?></td>
                <td><?= date('M j, Y', strtotime($payment['paid_at'])) ?></td>
                <td>
                  <a href="<?= site_url('admin/payments/' . $payment['id']) ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                    <i class="fas fa-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align:center;padding:2rem;color:#6b7280">
                No payments found
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

<!-- Modal for Create New Invoice -->
<div id="newInvoiceModal" class="modal-overlay">
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:600px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid var(--border);">
      <h3 style="margin:0;font-size:1.2rem">Create New Invoice</h3>
      <button class="modal-close" id="closeInvoiceModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:var(--muted);padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;">
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success" style="background:#f0fdf4;color:#16a34a;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #bbf7d0;"><?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <form id="modalInvoiceForm" method="post" action="<?= site_url('accountant/invoices') ?>" style="display:grid;gap:16px">
        <?= csrf_field() ?>
        <div class="form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div>
            <label style="display:block;margin-bottom:4px;font-weight:600">Patient <span style="color:#dc2626">*</span>
              <select name="patient_id" id="modalPatientSelect" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px" required>
                <option value="">Select Patient</option>
                <?php if (!empty($patients ?? [])): ?>
                  <?php foreach ($patients as $patient): ?>
                    <option value="<?= $patient['id'] ?>">
                      <?= esc($patient['name']) ?> (ID: <?= $patient['id'] ?>) - <?= $patient['mobile'] ?? '' ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </label>
          </div>
          <div>
            <label style="display:block;margin-bottom:4px;font-weight:600">Invoice Date <span style="color:#dc2626">*</span>
              <input type="date" name="invoice_date" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px" value="<?= date('Y-m-d') ?>" required>
            </label>
          </div>
          <div>
            <label style="display:block;margin-bottom:4px;font-weight:600">Invoice # <span style="color:#dc2626">*</span>
              <input type="text" name="invoice_no" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px" value="INV-<?= time() ?>-<?= rand(100, 999) ?>" required>
            </label>
          </div>
          <div>
            <label style="display:block;margin-bottom:4px;font-weight:600">Amount (₱)<span style="color:#dc2626">*</span>
              <input type="number" name="amount" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px" step="0.01" placeholder="0.00" required>
            </label>
          </div>
        </div>
        <div>
          <label style="display:block;margin-bottom:4px;font-weight:600">Status
            <select name="status" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px">
              <option value="unpaid">Unpaid</option>
              <option value="paid">Paid</option>
              <option value="partial">Partial</option>
            </select>
          </label>
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end;">
          <button type="submit" style="background:#0ea5e9;color:white;padding:10px 20px;border-radius:6px;border:none;cursor:pointer;font-weight:600">
            <i class="fa-solid fa-save"></i> Create Invoice
          </button>
          <button type="button" id="cancelInvoiceModal" style="background:var(--border);color:var(--text);padding:10px 20px;border-radius:6px;border:none;cursor:pointer;">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal for New Payment -->
<div id="newPaymentModal" class="modal-overlay">
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:500px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid var(--border);">
      <h3 style="margin:0;font-size:1.2rem">New Payment</h3>
      <button class="modal-close" id="closePaymentModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:var(--muted);padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;">
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <form id="modalPaymentForm" method="post" action="<?= site_url('accountant/storePayment') ?>" style="display:flex;flex-direction:column;gap:12px">
        <?= csrf_field() ?>
        <div>
          <label style="display:block;margin-bottom:4px;font-weight:600">Select Invoice</label>
          <select name="invoice_no" id="modalInvoiceSelect" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px" required>
            <option value="">Choose an invoice...</option>
            <?php if (!empty($invoices)) : ?>
              <?php foreach ($invoices as $i) : ?>
                <?php if (strtolower((string)($i['status'] ?? '')) !== 'paid') : ?>
                  <option value="<?= esc($i['invoice_no'] ?? '') ?>" data-patient="<?= esc($i['patient_name'] ?? '') ?>" data-amount="<?= (float)($i['amount'] ?? 0) ?>"><?= esc($i['invoice_no'] ?? '') ?> - <?= esc($i['patient_name'] ?? '') ?> - P<?= number_format((float)($i['amount'] ?? 0), 2) ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <div>
          <label style="display:block;margin-bottom:4px;font-weight:600">Payment Amount</label>
          <input type="number" name="amount" id="modalPaymentAmount" step="0.01" min="0" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px" placeholder="0.00" required>
        </div>
        <div>
          <label style="display:block;margin-bottom:4px;font-weight:600">Payment Method</label>
          <select name="payment_method" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px" required>
            <option value="">Select method...</option>
            <option value="cash">Cash</option>
            <option value="card">Credit/Debit Card</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="check">Check</option>
            <option value="online">Online Payment</option>
          </select>
        </div>
        <div>
          <label style="display:block;margin-bottom:4px;font-weight:600">Payment Date</label>
          <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" style="width:100%;padding:8px;border:1px solid var(--border);border-radius:6px" required>
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
          <button type="submit" style="background:#10b981;color:white;padding:10px 20px;border-radius:6px;border:none;cursor:pointer;font-weight:600">Record Payment</button>
          <button type="button" id="cancelPaymentModal" style="background:var(--border);color:var(--text);padding:10px 20px;border-radius:6px;border:none;cursor:pointer;">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function selectInvoice(invoiceNo, patientName, amount, status) {
  document.getElementById('invoiceSelect').value = invoiceNo;
  document.getElementById('paymentAmount').value = amount;
  document.getElementById('paymentAmount').max = amount;
}

function resetPaymentForm() {
  document.getElementById('paymentForm').reset();
}

// Invoice search functionality
document.getElementById('invoiceSearch').addEventListener('input', function(e) {
  const searchTerm = e.target.value.toLowerCase();
  const rows = document.querySelectorAll('#invoiceTableBody tr');
  
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(searchTerm) ? '' : 'none';
  });
});

// Auto-fill payment amount when invoice is selected
document.getElementById('invoiceSelect').addEventListener('change', function(e) {
  const selectedOption = e.target.options[e.target.selectedIndex];
  if (selectedOption && selectedOption.value) {
    const amount = selectedOption.getAttribute('data-amount');
    if (amount) {
      document.getElementById('paymentAmount').value = amount;
      document.getElementById('paymentAmount').max = amount;
    }
  }
});

// Modal functionality for Create New Invoice
document.getElementById('newInvoiceBtn').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('newInvoiceModal').classList.add('show');
});

document.getElementById('closeInvoiceModal').addEventListener('click', function() {
  document.getElementById('newInvoiceModal').classList.remove('show');
});

document.getElementById('cancelInvoiceModal').addEventListener('click', function() {
  document.getElementById('newInvoiceModal').classList.remove('show');
});

// Close modal when clicking outside
document.getElementById('newInvoiceModal').addEventListener('click', function(e) {
  if (e.target === this) {
    this.classList.remove('show');
  }
});

// Modal functionality for New Payment
document.getElementById('newPaymentBtn').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('newPaymentModal').classList.add('show');
});

document.getElementById('closePaymentModal').addEventListener('click', function() {
  document.getElementById('newPaymentModal').classList.remove('show');
});

document.getElementById('cancelPaymentModal').addEventListener('click', function() {
  document.getElementById('newPaymentModal').classList.remove('show');
});

// Close modal when clicking outside
document.getElementById('newPaymentModal').addEventListener('click', function(e) {
  if (e.target === this) {
    this.classList.remove('show');
  }
});

// Auto-fill payment amount when invoice is selected in modal
document.getElementById('modalInvoiceSelect').addEventListener('change', function(e) {
  const selectedOption = e.target.options[e.target.selectedIndex];
  if (selectedOption && selectedOption.value) {
    const amount = selectedOption.getAttribute('data-amount');
    if (amount) {
      document.getElementById('modalPaymentAmount').value = amount;
      document.getElementById('modalPaymentAmount').max = amount;
    }
  }
});
</script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<?= $this->endSection() ?>
