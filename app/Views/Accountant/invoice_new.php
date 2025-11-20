<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Invoice - Hospital Billing System</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
    .form-full { grid-column: 1 / -1; }
    .item-row { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 0.5rem; margin-bottom: 0.5rem; align-items: center; }
    .totals { margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 4px; }
    .btn-add-item { margin: 0.5rem 0; }
    .btn-remove { color: #dc3545; cursor: pointer; }
    .admission-details { margin-top: 1rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; }
  </style>
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('accountant/billing') ?>" class="menu-btn" aria-label="Back to Billing"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Create Detailed Invoice</h1>
        <small>New patient billing with itemized charges</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role"><i class="fa-regular fa-user"></i>
        <?= esc(session('username') ?? session('role') ?? 'User') ?>
      </span>
      <a href="<?= site_url('logout') ?>" class="logout-btn">Logout</a>
    </div>
  </div>
</header>

<div class="layout">
  <aside class="simple-sidebar" role="navigation" aria-label="Accountant navigation">
    <nav class="side-nav">
      <a href="<?= site_url('dashboard/accountant') ?>"><i class="fa-solid fa-chart-pie"></i> Overview</a>
      <a href="<?= site_url('accountant/billing') ?>" class="active"><i class="fa-solid fa-file-invoice-dollar"></i> Billing & Payments</a>
      <a href="<?= site_url('accountant/insurance') ?>"><i class="fa-solid fa-shield-halved"></i> Insurance</a>
      <a href="<?= site_url('accountant/reports') ?>"><i class="fa-solid fa-chart-line"></i> Financial Reports</a>
    </nav>
  </aside>

  <main class="content">
    <form id="invoiceForm" method="post" action="<?= site_url('accountant/invoices/save') ?>">
      <!-- Basic Information Section -->
      <section class="panel">
        <div class="panel-head">
          <h2><i class="fa-solid fa-user-injured"></i> Patient Information</h2>
        </div>
        <div class="panel-body">
          <div class="form-grid">
            <div>
              <label>Patient <span class="text-danger">*</span>
                <select name="patient_id" id="patientSelect" class="form-control" required>
                  <option value="">Select Patient</option>
                  <?php foreach ($patients as $patient): ?>
                    <option value="<?= $patient['id'] ?>">
                      <?= esc($patient['name']) ?> (ID: <?= $patient['id'] ?>) - <?= $patient['mobile'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </label>
            </div>
            <div>
              <label>Invoice Date <span class="text-danger">*</span>
                <input type="date" name="invoice_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
              </label>
            </div>
            <div>
              <label>Invoice # <span class="text-danger">*</span>
                <input type="text" name="invoice_no" class="form-control" value="INV-<?= time() ?>-<?= rand(100, 999) ?>" required>
              </label>
            </div>
            <div>
              <label>Status <span class="text-danger">*</span>
                <select name="status" class="form-control" required>
                  <option value="draft">Draft</option>
                  <option value="unpaid" selected>Unpaid</option>
                  <option value="partially_paid">Partially Paid</option>
                  <option value="paid">Paid</option>
                </select>
              </label>
            </div>
          </div>

          <!-- Admission Details (Dynamically shown if patient is admitted) -->
          <div id="admissionDetails" class="admission-details" style="display: none;">
            <h4><i class="fa-solid fa-bed"></i> Admission Details</h4>
            <div class="form-grid">
              <div>
                <label>Room Type
                  <input type="text" id="roomType" class="form-control" readonly>
                </label>
              </div>
              <div>
                <label>Room Rate (per day)
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" id="roomRate" name="room_rate" class="form-control" step="0.01" min="0" value="0">
                  </div>
                </label>
              </div>
              <div>
                <label>Admission Date
                  <input type="date" id="admissionDate" name="admission_date" class="form-control">
                </label>
              </div>
              <div>
                <label>Number of Days
                  <input type="number" id="daysStayed" name="days_stayed" class="form-control" min="1" value="1">
                </label>
              </div>
            </div>
            <div class="form-group">
              <label>Room Charges Notes
                <textarea name="room_notes" class="form-control" rows="2" placeholder="Any additional notes about room charges"></textarea>
              </label>
            </div>
          </div>
        </div>
      </section>

      <!-- Services and Items Section -->
      <section class="panel">
        <div class="panel-head">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2><i class="fa-solid fa-list-check"></i> Services & Items</h2>
            <button type="button" id="addItem" class="btn btn-primary btn-sm">
              <i class="fa-solid fa-plus"></i> Add Item
            </button>
          </div>
        </div>
        <div class="panel-body">
          <div id="itemsContainer">
            <!-- Item rows will be added here by JavaScript -->
            <div class="item-row">
              <div>
                <input type="text" name="items[0][description]" class="form-control" placeholder="Description" required>
              </div>
              <div>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" name="items[0][amount]" class="form-control item-amount" placeholder="0.00" step="0.01" min="0" required>
                </div>
              </div>
              <div>
                <input type="number" name="items[0][quantity]" class="form-control item-quantity" value="1" min="1" required>
              </div>
              <div>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" name="items[0][total]" class="form-control item-total" value="0.00" step="0.01" readonly>
                </div>
              </div>
              <div>
                <button type="button" class="btn btn-link btn-remove-item" disabled>
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </div>
          </div>

          <div class="totals">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Notes
                    <textarea name="notes" class="form-control" rows="2" placeholder="Any additional notes for this invoice"></textarea>
                  </label>
                </div>
              </div>
              <div class="col-md-6 text-end">
                <div class="mb-2">
                  <strong>Subtotal: </strong> 
                  <span id="subtotal">$0.00</span>
                  <input type="hidden" name="subtotal" id="subtotalInput" value="0">
                </div>
                <div class="mb-2">
                  <label>Tax Rate (%):
                    <input type="number" name="tax_rate" id="taxRate" class="form-control d-inline-block" style="width: 80px;" value="0" min="0" max="100">
                  </label>
                </div>
                <div class="mb-2">
                  <strong>Tax Amount: </strong> 
                  <span id="taxAmount">$0.00</span>
                  <input type="hidden" name="tax_amount" id="taxAmountInput" value="0">
                </div>
                <div class="mb-2">
                  <label>Discount ($):
                    <input type="number" name="discount" id="discount" class="form-control d-inline-block" style="width: 120px;" value="0" min="0" step="0.01">
                  </label>
                </div>
                <div class="mb-2">
                  <strong>Room Charges: </strong> 
                  <span id="roomCharges">$0.00</span>
                  <input type="hidden" name="room_charges" id="roomChargesInput" value="0">
                </div>
                <div class="h4">
                  <strong>Total: </strong> 
                  <span id="total">$0.00</span>
                  <input type="hidden" name="total" id="totalInput" value="0">
                </div>
                <div class="mb-2">
                  <label>Amount Paid:
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input type="number" name="amount_paid" id="amountPaid" class="form-control" value="0" min="0" step="0.01">
                    </div>
                  </label>
                </div>
                <div class="mb-2">
                  <strong>Balance Due: </strong> 
                  <span id="balanceDue">$0.00</span>
                  <input type="hidden" name="balance_due" id="balanceDueInput" value="0">
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Payment Information -->
      <section class="panel">
        <div class="panel-head">
          <h2><i class="fa-solid fa-credit-card"></i> Payment Information</h2>
        </div>
        <div class="panel-body">
          <div class="form-grid">
            <div>
              <label>Payment Method <span class="text-danger">*</span>
                <select name="payment_method" class="form-control" required>
                  <option value="">Select Payment Method</option>
                  <option value="cash">Cash</option>
                  <option value="credit_card">Credit Card</option>
                  <option value="debit_card">Debit Card</option>
                  <option value="bank_transfer">Bank Transfer</option>
                  <option value="insurance">Insurance</option>
                  <option value="other">Other</option>
                </select>
              </label>
            </div>
            <div>
              <label>Payment Date <span class="text-danger">*</span>
                <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
              </label>
            </div>
            <div class="form-full">
              <label>Payment Notes
                <textarea name="payment_notes" class="form-control" rows="2" placeholder="Any additional payment information"></textarea>
              </label>
            </div>
          </div>
        </div>
      </section>

      <div class="text-end mt-3">
        <a href="<?= site_url('accountant/billing') ?>" class="btn btn-secondary me-2">
          <i class="fa-solid fa-times"></i> Cancel
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="fa-regular fa-floppy-disk"></i> Save Invoice
        </button>
      </div>
    </form>
  </main>
</div>

<script>
$(document).ready(function() {
  let itemCount = 1; // Start from 1 since we have one default row
  
  // Add new item row
  $('#addItem').click(function() {
    const newRow = `
      <div class="item-row">
        <div>
          <input type="text" name="items[${itemCount}][description]" class="form-control" placeholder="Description" required>
        </div>
        <div>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="number" name="items[${itemCount}][amount]" class="form-control item-amount" placeholder="0.00" step="0.01" min="0" required>
          </div>
        </div>
        <div>
          <input type="number" name="items[${itemCount}][quantity]" class="form-control item-quantity" value="1" min="1" required>
        </div>
        <div>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="number" name="items[${itemCount}][total]" class="form-control item-total" value="0.00" step="0.01" readonly>
          </div>
        </div>
        <div>
          <button type="button" class="btn btn-link btn-remove-item text-danger">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>
      </div>`;
    
    $('#itemsContainer').append(newRow);
    itemCount++;
    
    // Enable remove button for all but the first row
    $('.btn-remove-item').prop('disabled', $('.item-row').length <= 1);
  });
  
  // Remove item row
  $(document).on('click', '.btn-remove-item', function() {
    if ($('.item-row').length > 1) {
      $(this).closest('.item-row').remove();
      renumberItemRows();
      calculateTotals();
      
      // Disable remove button if only one row remains
      $('.btn-remove-item').prop('disabled', $('.item-row').length <= 1);
    }
  });
  
  // Renumber item rows to maintain sequential indices
  function renumberItemRows() {
    $('.item-row').each(function(index) {
      $(this).find('input, select, textarea').each(function() {
        const name = $(this).attr('name');
        if (name) {
          $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
        }
      });
    });
  }
  
  // Calculate item total when amount or quantity changes
  $(document).on('input', '.item-amount, .item-quantity', function() {
    const $row = $(this).closest('.item-row');
    const amount = parseFloat($row.find('.item-amount').val()) || 0;
    const quantity = parseInt($row.find('.item-quantity').val()) || 0;
    const total = (amount * quantity).toFixed(2);
    
    $row.find('.item-total').val(total);
    calculateTotals();
  });
  
  // Calculate all totals
  function calculateTotals() {
    let subtotal = 0;
    
    // Calculate subtotal from items
    $('.item-row').each(function() {
      const total = parseFloat($(this).find('.item-total').val()) || 0;
      subtotal += total;
    });
    
    // Get room charges
    const roomRate = parseFloat($('#roomRate').val()) || 0;
    const daysStayed = parseInt($('#daysStayed').val()) || 0;
    const roomCharges = roomRate * daysStayed;
    
    // Calculate tax
    const taxRate = parseFloat($('#taxRate').val()) || 0;
    const taxAmount = (subtotal + roomCharges) * (taxRate / 100);
    
    // Get discount
    const discount = parseFloat($('#discount').val()) || 0;
    
    // Calculate total
    const total = (subtotal + roomCharges + taxAmount) - discount;
    
    // Get amount paid
    const amountPaid = parseFloat($('#amountPaid').val()) || 0;
    
    // Calculate balance due
    const balanceDue = Math.max(0, total - amountPaid);
    
    // Update UI
    $('#subtotal').text('$' + subtotal.toFixed(2));
    $('#subtotalInput').val(subtotal.toFixed(2));
    
    $('#roomCharges').text('$' + roomCharges.toFixed(2));
    $('#roomChargesInput').val(roomCharges.toFixed(2));
    
    $('#taxAmount').text('$' + taxAmount.toFixed(2));
    $('#taxAmountInput').val(taxAmount.toFixed(2));
    
    $('#total').text('$' + total.toFixed(2));
    $('#totalInput').val(total.toFixed(2));
    
    $('#balanceDue').text('$' + balanceDue.toFixed(2));
    $('#balanceDueInput').val(balanceDue.toFixed(2));
    
    // Update status based on payment
    if (amountPaid >= total) {
      $('select[name="status"]').val('paid');
    } else if (amountPaid > 0) {
      $('select[name="status"]').val('partially_paid');
    } else if ($('select[name="status"]').val() !== 'draft') {
      $('select[name="status"]').val('unpaid');
    }
  }
  
  // Recalculate when tax rate, discount, or amount paid changes
  $('#taxRate, #discount, #amountPaid, #roomRate, #daysStayed').on('input', calculateTotals);
  
  // Check if patient is admitted and show/hide admission details
  $('#patientSelect').change(function() {
    const patientId = $(this).val();
    
    // In a real application, you would make an AJAX call to check if the patient is admitted
    // and get their admission details. For now, we'll simulate it with a simple check.
    if (patientId) {
      // Simulate API call to check admission status
      // This is where you would make an actual AJAX call to your backend
      // For demo purposes, we'll just show/hide based on a simple condition
      const isAdmitted = Math.random() > 0.5; // 50% chance of being admitted
      
      if (isAdmitted) {
        $('#admissionDetails').show();
        
        // Simulate getting room details
        const roomTypes = ['General Ward', 'Semi-Private', 'Private', 'ICU', 'Pediatric'];
        const roomRates = [150, 250, 400, 800, 300];
        const randomIndex = Math.floor(Math.random() * roomTypes.length);
        
        $('#roomType').val(roomTypes[randomIndex]);
        $('#roomRate').val(roomRates[randomIndex]);
        
        // Set admission date to a random date in the past 30 days
        const admissionDate = new Date();
        admissionDate.setDate(admissionDate.getDate() - Math.floor(Math.random() * 30));
        $('#admissionDate').val(admissionDate.toISOString().split('T')[0]);
        
        // Calculate days stayed
        const daysStayed = Math.ceil((new Date() - admissionDate) / (1000 * 60 * 60 * 24));
        $('#daysStayed').val(daysStayed);
        
        // Recalculate totals with room charges
        calculateTotals();
      } else {
        $('#admissionDetails').hide();
        $('#roomRate, #daysStayed').val('0');
        calculateTotals();
      }
    } else {
      $('#admissionDetails').hide();
      $('#roomRate, #daysStayed').val('0');
      calculateTotals();
    }
  });
  
  // Initialize calculations
  calculateTotals();
});
</script>
</body>
</html>
