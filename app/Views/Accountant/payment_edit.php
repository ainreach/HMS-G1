<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Payment - Hospital Billing System</title>
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
        <h1 style="font-size:1.25rem;margin:0">Edit Payment</h1>
        <small>Update payment details</small>
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
        <h2 style="margin:0;font-size:1.1rem">Edit Payment Record</h2>
      </div>
      <div class="panel-body">
        <?php 
          $formAction = $isAdmin ? site_url('admin/payments/' . $payment['id']) : site_url('accountant/payments/' . $payment['id']);
        ?>
        <form method="post" action="<?= $formAction ?>" style="max-width:800px;">
          <?= csrf_field() ?>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Patient *</label>
              <select name="patient_id" required style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
                <option value="">Select Patient</option>
                <?php foreach ($patients as $patient): ?>
                  <option value="<?= $patient['id'] ?>" <?= (isset($payment['patient_name']) && strpos($payment['patient_name'], $patient['first_name']) !== false) ? 'selected' : '' ?>>
                    <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?> (ID: <?= $patient['patient_id'] ?? $patient['id'] ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Payment Date *</label>
              <input type="datetime-local" name="payment_date" value="<?= esc(date('Y-m-d\TH:i', strtotime($payment['paid_at'] ?? 'now'))) ?>" required 
                     style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Invoice # (Optional)</label>
              <input type="text" name="invoice_no" value="<?= esc($payment['invoice_no'] ?? '') ?>" 
                     style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
            </div>
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Amount ($) *</label>
              <input type="number" name="amount" step="0.01" value="<?= esc($payment['amount'] ?? 0) ?>" required 
                     style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
            </div>
          </div>

          <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:16px;border-top:1px solid #e5e7eb;">
            <a href="<?= $backUrl ?>" class="btn" style="padding:10px 20px;background:#6b7280;color:white;text-decoration:none;border-radius:6px;font-weight:500;">
              Cancel
            </a>
            <button type="submit" class="btn" style="padding:10px 20px;background:#10b981;color:white;border:none;border-radius:6px;font-weight:500;cursor:pointer;">
              <i class="fa-solid fa-save"></i> Update Payment
            </button>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>
</body>
</html>
