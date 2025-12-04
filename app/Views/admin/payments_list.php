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
                $patient      = $bill['patient'] ?? [];
                $totalAmount  = (float)($bill['totalCharges'] ?? 0);
                $amountPaid   = (float)($bill['totalPayments'] ?? 0);
                $balance      = (float)($bill['balanceDue'] ?? 0);
                $fullName     = trim((string)($patient['last_name'] ?? '') . ', ' . (string)($patient['first_name'] ?? ''));
                $subText      = trim((string)($bill['prescription_summary'] ?? ''));
                $invoiceNo    = (string)($bill['invoice_number'] ?? ($bill['code'] ?? ''));
                $billId       = (int)($bill['id'] ?? 0);
                $billDateRaw  = $bill['bill_date'] ?? ($bill['created_at'] ?? null);
                $dueDateRaw   = $bill['due_date'] ?? null;
                $billDate     = $billDateRaw ? date('M d, Y', strtotime($billDateRaw)) : '';
                $dueDate      = $dueDateRaw ? date('M d, Y', strtotime($dueDateRaw)) : '';
                $status       = $balance > 0 ? 'Unpaid' : 'Paid';
              ?>
              <tr>
                <td style="padding:12px 8px;border-bottom:1px solid #f3f4f6;white-space:nowrap;">
                  <?php if ($billId && $invoiceNo !== ''): ?>
                    <a href="<?= site_url('admin/billing/view/' . $billId) ?>" style="color:#0f766e;font-weight:600;text-decoration:underline;">
                      <?= esc($invoiceNo) ?>
                    </a>
                  <?php else: ?>
                    <span style="font-weight:600;"><?= esc($invoiceNo ?: '—') ?></span>
                  <?php endif; ?>
                </td>
                <td style="padding:12px 8px;border-bottom:1px solid #f3f4f6;">
                  <div style="font-weight:600;"><?= esc($fullName ?: 'N/A') ?></div>
                  <?php if ($subText !== ''): ?>
                    <div style="font-size:0.75rem;color:#6b7280;"><?= esc($subText) ?></div>
                  <?php endif; ?>
                </td>
                <td style="padding:12px 8px;border-bottom:1px solid #f3f4f6;white-space:nowrap;">
                  <?= esc($billDate) ?>
                </td>
                <td style="padding:12px 8px;border-bottom:1px solid #f3f4f6;white-space:nowrap;">
                  <?= esc($dueDate) ?>
                </td>
                <td style="padding:12px 8px;border-bottom:1px solid #f3f4f6;font-weight:600;white-space:nowrap;">
                  ₱<?= number_format($totalAmount, 2) ?>
                </td>
                <td style="padding:12px 8px;border-bottom:1px solid #f3f4f6;color:#16a34a;font-weight:600;white-space:nowrap;">
                  ₱<?= number_format($amountPaid, 2) ?>
                </td>
                <td style="padding:12px 8px;border-bottom:1px solid #f3f4f6;color:#dc2626;font-weight:700;white-space:nowrap;">
                  ₱<?= number_format($balance, 2) ?>
                </td>
                <td style="padding:12px 8px;border-bottom:1px solid #f3f4f6;">
                  <span class="badge" style="background:<?= $balance > 0 ? '#facc15' : '#22c55e' ?>;color:#111827;border-radius:999px;padding:4px 10px;font-size:0.75rem;">
                    <?= esc($status) ?>
                  </span>
                </td>
                <td style="padding:12px 8px;border-bottom:1px solid #f3f4f6;">
                  <div style="display:flex;gap:6px;align-items:center;">
                    <!-- Pay / collect payment icon (placeholder link to accountant payments) -->
                    <a
                      href="<?= site_url('accountant/payments') ?>"
                      title="Record Payment"
                      style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:6px;border:1px solid #22c55e;color:#22c55e;background:white;"
                    >
                      <i class="fa-solid fa-money-bill-wave"></i>
                    </a>

                    <?php if ($billId): ?>
                      <!-- View invoice -->
                      <a
                        href="<?= site_url('admin/billing/view/' . $billId) ?>"
                        title="View Invoice"
                        style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:6px;border:1px solid #0ea5e9;color:#0ea5e9;background:white;"
                      >
                        <i class="fa-regular fa-eye"></i>
                      </a>

                      <!-- Print invoice (opens detailed view and relies on its Print button) -->
                      <a
                        href="<?= site_url('admin/billing/view/' . $billId) ?>"
                        title="Print Invoice"
                        style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:6px;border:1px solid #9ca3af;color:#4b5563;background:white;"
                      >
                        <i class="fa-solid fa-print"></i>
                      </a>
                    <?php endif; ?>
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
                  <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
                    <button
                      type="button"
                      class="btn btn-secondary view-payment-btn"
                      data-payment='<?= json_encode([
                        'id'           => $payment['id'] ?? null,
                        'patient_name' => $payment['patient_name'] ?? 'N/A',
                        'invoice_no'   => $payment['invoice_no'] ?? '',
                        'amount'       => (float)($payment['amount'] ?? 0),
                        'paid_at'      => $payment['paid_at'] ?? '',
                      ]) ?>'
                      style="padding:0.25rem 0.5rem;font-size:0.75rem;"
                    >
                      <i class="fas fa-eye"></i> View
                    </button>

                    <?php if (!empty($payment['billing_id'])): ?>
                      <a
                        href="<?= site_url('admin/billing/view/' . $payment['billing_id']) ?>"
                        class="btn btn-outline-primary"
                        style="padding:0.25rem 0.5rem;font-size:0.75rem;"
                      >
                        <i class="fa-regular fa-file-lines"></i> View Invoice
                      </a>
                    <?php endif; ?>
                  </div>
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

// Floating card for Invoice Records (billing summary)
const invoiceDetailModal = document.createElement('div');
invoiceDetailModal.id = 'invoiceDetailModal';
invoiceDetailModal.className = 'modal-overlay';
invoiceDetailModal.innerHTML = `
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid var(--border);background:linear-gradient(to right,#0ea5e9,#0369a1);color:white;">
      <div>
        <div id="ivd-code" style="font-size:0.8rem;opacity:.9;"></div>
        <h3 style="margin:0;font-size:1.1rem;">Invoice Record Details</h3>
      </div>
      <button class="modal-close" id="closeInvoiceDetailModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:#e5e7eb;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:10px 16px;font-size:0.9rem;">
      <div><div class="field-label">Patient</div><div id="ivd-patient" class="field-value"></div></div>
      <div><div class="field-label">Bill Date</div><div id="ivd-bill-date" class="field-value"></div></div>
      <div><div class="field-label">Total Amount</div><div id="ivd-total" class="field-value"></div></div>
      <div><div class="field-label">Amount Paid</div><div id="ivd-paid" class="field-value"></div></div>
      <div><div class="field-label">Balance</div><div id="ivd-balance" class="field-value"></div></div>
      <div><div class="field-label">Status</div><div id="ivd-status" class="field-value"></div></div>
    </div>
    <div class="modal-footer" style="padding:12px 18px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f9fafb;">
      <button type="button" id="closeInvoiceDetailBtn" class="btn" style="background:#e5e7eb;color:#111827;border:none;">Close</button>
    </div>
  </div>
`;
document.body.appendChild(invoiceDetailModal);

function closeInvoiceDetail() {
  invoiceDetailModal.classList.remove('show');
}

document.getElementById('closeInvoiceDetailModal').addEventListener('click', closeInvoiceDetail);
document.getElementById('closeInvoiceDetailBtn').addEventListener('click', closeInvoiceDetail);
invoiceDetailModal.addEventListener('click', function(e) {
  if (e.target === this) closeInvoiceDetail();
});

document.querySelectorAll('.invoice-view-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const data = JSON.parse(this.getAttribute('data-invoice'));
    document.getElementById('ivd-code').textContent = 'Invoice Code: ' + (data.code || '');
    document.getElementById('ivd-patient').textContent = data.patient_name || '—';
    document.getElementById('ivd-bill-date').textContent = data.bill_date || '—';
    document.getElementById('ivd-total').textContent = 'P' + (Number(data.total || 0).toFixed(2));
    document.getElementById('ivd-paid').textContent = 'P' + (Number(data.paid || 0).toFixed(2));
    document.getElementById('ivd-balance').textContent = 'P' + (Number(data.balance || 0).toFixed(2));
    document.getElementById('ivd-status').textContent = data.status || '—';
    invoiceDetailModal.classList.add('show');
  });
});

// Floating card for Payment details (All Payments)
const viewPaymentModal = document.createElement('div');
viewPaymentModal.id = 'viewPaymentModal';
viewPaymentModal.className = 'modal-overlay';
viewPaymentModal.innerHTML = `
  <div class="modal-card" style="background:white;border-radius:var(--radius);width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow);">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid var(--border);background:linear-gradient(to right,#0ea5e9,#0369a1);color:white;">
      <div>
        <div id="pv-receipt" style="font-size:0.8rem;opacity:.9;"></div>
        <h3 style="margin:0;font-size:1.1rem;">Payment Details</h3>
      </div>
      <button class="modal-close" id="closeViewPaymentModal" style="background:none;border:none;font-size:24px;cursor:pointer;color:#e5e7eb;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">&times;</button>
    </div>
    <div class="modal-body" style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:10px 16px;font-size:0.9rem;">
      <div><div class="field-label">Payment Date</div><div id="pv-date" class="field-value"></div></div>
      <div><div class="field-label">Amount Paid</div><div id="pv-amount" class="field-value"></div></div>
      <div><div class="field-label">Invoice #</div><div id="pv-invoice" class="field-value"></div></div>
      <div><div class="field-label">Patient</div><div id="pv-patient" class="field-value"></div></div>
    </div>
    <div class="modal-footer" style="padding:12px 18px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f9fafb;">
      <button type="button" id="closeViewPaymentBtn" class="btn" style="background:#e5e7eb;color:#111827;border:none;">Close</button>
    </div>
  </div>
`;
document.body.appendChild(viewPaymentModal);

function closeViewPayment() {
  viewPaymentModal.classList.remove('show');
}

document.getElementById('closeViewPaymentModal').addEventListener('click', closeViewPayment);
document.getElementById('closeViewPaymentBtn').addEventListener('click', closeViewPayment);
viewPaymentModal.addEventListener('click', function(e) {
  if (e.target === this) closeViewPayment();
});

document.querySelectorAll('.view-payment-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const data = JSON.parse(this.getAttribute('data-payment'));
    document.getElementById('pv-receipt').textContent = 'Receipt #' + (data.id || '');
    document.getElementById('pv-date').textContent = data.paid_at || '—';
    document.getElementById('pv-amount').textContent = 'P' + (Number(data.amount || 0).toFixed(2));
    document.getElementById('pv-invoice').textContent = data.invoice_no || '—';
    document.getElementById('pv-patient').textContent = data.patient_name || '—';
    viewPaymentModal.classList.add('show');
  });
});
</script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<?= $this->endSection() ?>
