<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patient Billing - Accountant</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Accountant</h1><small>Patient Billing</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout">
<?= $this->include('Accountant/sidebar', ['currentPage' => 'patient-room-bills']) ?>
  <main class="content">
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Patient Billing Information</h2>
      </div>
      <div class="panel-body">
        <!-- Patient Information -->
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:16px;margin-bottom:20px">
          <h3 style="margin:0 0 12px;color:#1e6f9f;font-size:1rem">Patient Details</h3>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px">
            <div>
              <strong>Patient ID:</strong><br>
              <?= esc($patient['patient_id']) ?>
            </div>
            <div>
              <strong>Name:</strong><br>
              <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>
            </div>
            <div>
              <strong>Status:</strong><br>
              <span style="padding:2px 8px;border-radius:12px;font-size:0.875rem;background-color:#dbeafe;color:#2563eb">
                <?= esc(ucfirst($patient['status'] ?? 'Unknown')) ?>
              </span>
            </div>
            <?php if ($patient['phone']): ?>
            <div>
              <strong>Phone:</strong><br>
              <?= esc($patient['phone']) ?>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Room Information -->
        <?php if ($room): ?>
        <div style="background:#f0f9ff;border:1px solid #0ea5e9;border-radius:8px;padding:16px;margin-bottom:20px">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <h3 style="margin:0;color:#0c4a6e;font-size:1rem">Room Information</h3>
            <a href="<?= site_url('accountant/patients/discharge/' . $patient['id']) ?>" 
               class="btn" 
               style="background:#dc2626;color:white;text-decoration:none;padding:8px 16px;border-radius:6px;font-size:0.875rem;display:inline-flex;align-items:center;gap:6px"
               onclick="return confirm('Are you sure you want to discharge this patient? This will free up the room.')">
              <i class="fa-solid fa-bed-pulse"></i> Discharge Patient
            </a>
          </div>
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px">
            <div>
              <strong>Room Number:</strong><br>
              <?= esc($room['room_number']) ?>
            </div>
            <div>
              <strong>Room Type:</strong><br>
              <?= esc(ucfirst($room['room_type'])) ?>
            </div>
            <div>
              <strong>Floor:</strong><br>
              <?= esc($room['floor']) ?>
            </div>
            <div>
              <strong>Daily Rate:</strong><br>
              ₱<?= number_format($room['rate_per_day'], 2) ?>
            </div>
            <div>
              <strong>Admission Date:</strong><br>
              <?= esc($patient['admission_date']) ?>
            </div>
            <div>
              <strong>Days Stayed:</strong><br>
              <?= esc($daysStayed) ?> days
            </div>
          </div>
        </div>
        <?php else: ?>
        <div style="background:#fef2f2;border:1px solid #ef4444;border-radius:8px;padding:16px;margin-bottom:20px">
          <p style="margin:0;color:#dc2626">
            <i class="fa-solid fa-info-circle"></i> Patient is not currently assigned to any room.
          </p>
        </div>
        <?php endif; ?>

        <!-- Financial Summary -->
        <div style="background:#f0fdf4;border:1px solid #16a34a;border-radius:8px;padding:16px;margin-bottom:20px">
          <h3 style="margin:0 0 12px;color:#14532d;font-size:1rem">Financial Summary</h3>
          
          <?php if ($room): ?>
          <div style="margin-bottom:16px">
            <h4 style="margin:0 0 8px;font-size:0.9rem;color:#166534">Room Charges:</h4>
            <div style="background:white;border:1px solid #d4d4d8;border-radius:6px;padding:12px">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                <span><?= esc($room['room_number']) ?> - <?= esc(ucfirst($room['room_type'])) ?></span>
                <span>₱<?= number_format($room['rate_per_day'], 2) ?> × <?= esc($daysStayed) ?> days</span>
              </div>
              <div style="display:flex;justify-content:space-between;align-items:center;font-weight:600;border-top:1px solid #e5e7eb;padding-top:8px">
                <span>Room Subtotal:</span>
                <span>₱<?= number_format($roomCharges, 2) ?></span>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- Invoices -->
          <div style="margin-bottom:16px">
            <h4 style="margin:0 0 8px;font-size:0.9rem;color:#166534">Invoices:</h4>
            <div style="background:white;border:1px solid #d4d4d8;border-radius:6px;padding:12px">
              <?php if (!empty($invoices)): ?>
                <?php foreach ($invoices as $invoice): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                  <span><?= esc($invoice['invoice_number'] ?? 'INV-' . $invoice['id']) ?></span>
                  <span>₱<?= number_format($invoice['amount'], 2) ?></span>
                </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                  <span>No invoices</span>
                  <span>₱0.00</span>
                </div>
              <?php endif; ?>
              <div style="display:flex;justify-content:space-between;align-items:center;font-weight:600;border-top:1px solid #e5e7eb;padding-top:8px">
                <span>Invoices Subtotal:</span>
                <span>₱<?= number_format($totalInvoices, 2) ?></span>
              </div>
            </div>
          </div>

          <!-- Payments -->
          <div style="margin-bottom:16px">
            <h4 style="margin:0 0 8px;font-size:0.9rem;color:#166534">Payments Received:</h4>
            <div style="background:white;border:1px solid #d4d4d8;border-radius:6px;padding:12px">
              <?php if (!empty($payments)): ?>
                <?php foreach ($payments as $payment): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                  <span><?= esc($payment['payment_number'] ?? 'PAY-' . $payment['id']) ?> (<?= esc($payment['payment_method'] ?? 'Cash') ?>)</span>
                  <span>-₱<?= number_format($payment['amount'], 2) ?></span>
                </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                  <span>No payments</span>
                  <span>₱0.00</span>
                </div>
              <?php endif; ?>
              <div style="display:flex;justify-content:space-between;align-items:center;font-weight:600;border-top:1px solid #e5e7eb;padding-top:8px">
                <span>Total Payments:</span>
                <span>₱<?= number_format($totalPayments, 2) ?></span>
              </div>
            </div>
          </div>

          <!-- Total Summary -->
          <div style="background:#dcfce7;border:1px solid #16a34a;border-radius:6px;padding:16px">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;font-size:1rem">
              <span><strong>Total Charges:</strong></span>
              <span>₱<?= number_format($totalCharges, 2) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;font-size:1rem">
              <span><strong>Total Payments:</strong></span>
              <span>₱<?= number_format($totalPayments, 2) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:1.1rem;font-weight:700;border-top:1px solid #16a34a;padding-top:8px;margin-top:8px">
              <span><i class="fa-solid fa-calculator"></i> Balance Due:</span>
              <span style="color:<?= $balanceDue > 0 ? '#dc2626' : '#16a34a' ?>">₱<?= number_format($balanceDue, 2) ?></span>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div style="display:flex;gap:12px;margin-top:20px">
          <a href="<?= site_url('accountant/billing') ?>" style="padding:10px 20px;background:#6b7280;color:white;text-decoration:none;border-radius:6px">
            <i class="fa-solid fa-arrow-left"></i> Back to Billing
          </a>
          <?php if ($balanceDue > 0): ?>
          <a href="<?= site_url('accountant/payments/new?patient_id=' . $patient['id']) ?>" style="padding:10px 20px;background:#10b981;color:white;text-decoration:none;border-radius:6px">
            <i class="fa-solid fa-money-bill"></i> Record Payment
          </a>
          <?php endif; ?>
          <button onclick="window.print()" style="padding:10px 20px;background:#3b82f6;color:white;border:none;border-radius:6px;cursor:pointer">
            <i class="fa-solid fa-print"></i> Print Statement
          </button>
        </div>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
