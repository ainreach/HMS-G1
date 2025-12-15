<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Payment Details - Hospital Billing System</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <?php 
      $isAdmin = isset($isAdmin) && $isAdmin;
      $backUrl = $isAdmin ? site_url('admin/payments') : site_url('accountant/payments');
    ?>
    <a href="<?= $backUrl ?>" class="menu-btn" aria-label="Back to Payments"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Payment Details</h1>
        <small>View payment receipt information</small>
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
<?= $this->include('Accountant/sidebar', ['currentPage' => 'payments']) ?>

  <main class="content">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Payment Receipt</h2>
        <div style="display:flex;gap:8px;align-items:center">
          <button onclick="window.print()" class="btn" style="background:#6b7280;color:white;padding:8px 12px;border-radius:6px;font-size:0.875rem;border:none;cursor:pointer">
            <i class="fa-solid fa-print"></i> Print
          </button>
          <a href="<?= $backUrl ?>" class="btn" style="background:#0ea5e9;color:white;text-decoration:none;padding:8px 12px;border-radius:6px;font-size:0.875rem">
            <i class="fa-solid fa-list"></i> Back to Payments
          </a>
        </div>
      </div>
      <div class="panel-body">
        <div style="max-width:600px;margin:0 auto">
          <!-- Receipt Header -->
          <div style="text-align:center;padding:20px 0;border-bottom:2px solid #e5e7eb;margin-bottom:20px">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" style="height:60px;margin-bottom:10px">
            <h3 style="margin:0;color:#111827">Payment Receipt</h3>
            <p style="margin:5px 0;color:#6b7280;font-size:0.875rem">Receipt #: <?= esc($payment['id']) ?></p>
          </div>

          <!-- Payment Details -->
          <div style="background:#f9fafb;padding:20px;border-radius:8px;margin-bottom:20px">
            <h4 style="margin:0 0 15px 0;color:#374151">Payment Information</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
              <div>
                <label style="display:block;font-size:0.875rem;color:#6b7280;margin-bottom:4px">Payment Date</label>
                <p style="margin:0;font-weight:500"><?= esc(date('M j, Y', strtotime($payment['paid_at']))) ?></p>
              </div>
              <div>
                <label style="display:block;font-size:0.875rem;color:#6b7280;margin-bottom:4px">Amount Paid</label>
                <p style="margin:0;font-weight:600;color:#10b981;font-size:1.25rem">$<?= number_format((float)($payment['amount'] ?? 0), 2) ?></p>
              </div>
              <?php if (!empty($payment['invoice_no'])): ?>
                <div>
                  <label style="display:block;font-size:0.875rem;color:#6b7280;margin-bottom:4px">Invoice Number</label>
                  <p style="margin:0;font-weight:500"><?= esc($payment['invoice_no']) ?></p>
                </div>
              <?php endif; ?>
              <div>
                <label style="display:block;font-size:0.875rem;color:#6b7280;margin-bottom:4px">Payment Method</label>
                <p style="margin:0;font-weight:500">Cash/Card</p>
              </div>
            </div>
          </div>

          <!-- Patient Information -->
          <div style="background:#f9fafb;padding:20px;border-radius:8px;margin-bottom:20px">
            <h4 style="margin:0 0 15px 0;color:#374151">Patient Information</h4>
            <div>
              <label style="display:block;font-size:0.875rem;color:#6b7280;margin-bottom:4px">Patient Name</label>
              <p style="margin:0;font-weight:500;font-size:1.1rem"><?= esc($payment['patient_name'] ?? 'N/A') ?></p>
            </div>
          </div>

          <!-- Payment Summary -->
          <div style="background:#eff6ff;padding:20px;border-radius:8px;border-left:4px solid #3b82f6">
            <div style="display:flex;justify-content:space-between;align-items:center">
              <div>
                <h4 style="margin:0;color:#1e40af">Total Amount Received</h4>
                <p style="margin:5px 0 0 0;color:#64748b;font-size:0.875rem">Payment completed successfully</p>
              </div>
              <div style="text-align:right">
                <p style="margin:0;font-size:1.5rem;font-weight:700;color:#10b981">$<?= number_format((float)($payment['amount'] ?? 0), 2) ?></p>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div style="text-align:center;margin-top:30px;padding-top:20px;border-top:1px solid #e5e7eb">
            <p style="margin:0;color:#6b7280;font-size:0.875rem">Thank you for your payment!</p>
            <p style="margin:5px 0 0 0;color:#9ca3af;font-size:0.75rem">This is a system-generated receipt</p>
          </div>
        </div>
      </div>
    </section>

  </main>
</div>
</body>
</html>
