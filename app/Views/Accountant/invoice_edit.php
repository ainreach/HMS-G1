<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Invoice - Hospital Billing System</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('accountant/invoices') ?>" class="menu-btn" aria-label="Back to Invoices"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Edit Invoice</h1>
        <small>Update invoice details</small>
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
<?= $this->include('Accountant/sidebar', ['currentPage' => 'invoices']) ?>

  <main class="content">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Edit Invoice #<?= esc($invoice['invoice_no'] ?? '') ?></h2>
      </div>
      <div class="panel-body">
        <form method="post" action="<?= site_url('accountant/invoices/' . $invoice['id']) ?>" style="max-width:800px;">
          <?= csrf_field() ?>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Patient *</label>
              <select name="patient_id" id="patientSelect" required style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
                <option value="">Select Patient</option>
                <?php foreach ($patients as $patient): ?>
                  <option value="<?= $patient['id'] ?>" <?= (isset($invoice['patient_name']) && strpos($invoice['patient_name'], $patient['name']) !== false) ? 'selected' : '' ?>>
                    <?= esc($patient['name']) ?> (ID: <?= $patient['id'] ?>) - <?= $patient['mobile'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Invoice Date *</label>
              <input type="date" name="invoice_date" value="<?= esc($invoice['issued_at'] ?? date('Y-m-d')) ?>" required 
                     style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Invoice # *</label>
              <input type="text" name="invoice_no" value="<?= esc($invoice['invoice_no'] ?? '') ?>" required 
                     style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
            </div>
            <div>
              <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Amount ($) *</label>
              <input type="number" name="amount" step="0.01" value="<?= esc($invoice['amount'] ?? 0) ?>" required 
                     style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;" />
            </div>
          </div>

          <div style="margin-bottom:20px">
            <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Status</label>
            <select name="status" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
              <option value="unpaid" <?= ($invoice['status'] ?? '') === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
              <option value="paid" <?= ($invoice['status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
              <option value="partial" <?= ($invoice['status'] ?? '') === 'partial' ? 'selected' : '' ?>>Partial</option>
            </select>
          </div>

          <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:16px;border-top:1px solid #e5e7eb;">
            <a href="<?= site_url('accountant/invoices') ?>" class="btn" style="padding:10px 20px;background:#6b7280;color:white;text-decoration:none;border-radius:6px;font-weight:500;">
              Cancel
            </a>
            <button type="submit" class="btn" style="padding:10px 20px;background:#0ea5e9;color:white;border:none;border-radius:6px;font-weight:500;cursor:pointer;">
              <i class="fa-solid fa-save"></i> Update Invoice
            </button>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>
</body>
</html>
