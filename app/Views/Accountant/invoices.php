<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoices - Hospital Billing System</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <a href="<?= site_url('accountant/billing') ?>" class="menu-btn" aria-label="Back to Billing"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Invoice Management</h1>
        <small>Create and manage patient invoices</small>
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
      <a href="<?= site_url('dashboard/accountant') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
      <a href="<?= site_url('accountant/billing') ?>"><i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px"></i>Billing & Payments</a>
      <a href="<?= site_url('accountant/invoices') ?>" class="active" aria-current="page"><i class="fa-solid fa-file-lines" style="margin-right:8px"></i>Invoices</a>
      <a href="<?= site_url('accountant/payments') ?>"><i class="fa-solid fa-sack-dollar" style="margin-right:8px"></i>Payments</a>
      <a href="<?= site_url('accountant/insurance') ?>"><i class="fa-solid fa-shield-halved" style="margin-right:8px"></i>Insurance</a>
      <a href="<?= site_url('accountant/reports') ?>"><i class="fa-solid fa-chart-line" style="margin-right:8px"></i>Financial Reports</a>
    </nav>
  </aside>

  <main class="content">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (isset($patients)): ?>
      <!-- Invoice Creation Form -->
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Create New Invoice</h2>
          <a href="<?= site_url('accountant/invoices') ?>" class="btn" style="background:#6b7280;color:white;text-decoration:none;padding:8px 12px;border-radius:6px;font-size:0.875rem">
            <i class="fa-solid fa-arrow-left"></i> Back to Invoices
          </a>
        </div>
        <div class="panel-body">
          <form method="post" action="<?= site_url('accountant/invoices') ?>" style="display:grid;gap:16px">
            <div class="form-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
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
                <label>Amount ($)<span class="text-danger">*</span>
                  <input type="number" name="amount" class="form-control" step="0.01" placeholder="0.00" required>
                </label>
              </div>
            </div>
            <div>
              <label>Status
                <select name="status" class="form-control">
                  <option value="unpaid">Unpaid</option>
                  <option value="paid">Paid</option>
                  <option value="partial">Partial</option>
                </select>
              </label>
            </div>
            <div style="display:flex;gap:12px">
              <button type="submit" class="btn" style="background:#0ea5e9;color:white;padding:10px 20px;border-radius:6px">
                <i class="fa-solid fa-save"></i> Create Invoice
              </button>
              <a href="<?= site_url('accountant/invoices') ?>" class="btn" style="background:#6b7280;color:white;text-decoration:none;padding:10px 20px;border-radius:6px">
                Cancel
              </a>
            </div>
          </form>
        </div>
      </section>
    <?php else: ?>
      <!-- Invoice List -->
      <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">All Invoices (<?= count($invoices ?? []) ?>)</h2>
        <div style="display:flex;gap:8px;align-items:center">
          <a href="<?= site_url('accountant/invoices/new') ?>" class="btn" style="background:#0ea5e9;color:white;text-decoration:none;padding:8px 12px;border-radius:6px;font-size:0.875rem">
            <i class="fa-solid fa-plus"></i> Create Invoice
          </a>
          <a href="<?= site_url('accountant/invoices/export') ?>" class="btn" style="background:#8b5cf6;color:white;text-decoration:none;padding:8px 12px;border-radius:6px;font-size:0.875rem">
            <i class="fa-solid fa-download"></i> Export
          </a>
        </div>
      </div>
      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($invoices)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Invoice #</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient Name</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Amount</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Issued Date</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($invoices as $invoice): ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <span style="color:#0ea5e9;font-weight:500"><?= esc($invoice['invoice_no']) ?></span>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($invoice['patient_name']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <span style="color:#0ea5e9;font-weight:600">$<?= number_format($invoice['amount'], 2) ?></span>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php 
                    $status = strtolower($invoice['status'] ?? 'unpaid');
                    $statusColor = $status === 'paid' ? '#10b981' : '#f59e0b';
                    ?>
                    <span style="background:<?= $statusColor ?>20;color:<?= $statusColor ?>;padding:4px 8px;border-radius:12px;font-size:0.75rem;font-weight:500">
                      <?= esc(ucfirst($status)) ?>
                    </span>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($invoice['issued_at']) ?></td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <div style="display:flex;gap:4px">
                      <a href="<?= site_url('accountant/billing') ?>" class="btn-small" style="background:#f3f4f6;color:#374151;padding:4px 8px;border-radius:4px;text-decoration:none;font-size:0.75rem">
                        <i class="fa-solid fa-eye"></i> View
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div style="text-align: center; padding: 3rem; color: #6b7280;">
            <i class="fa-solid fa-file-invoice" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3>No invoices found</h3>
            <p>There are no invoices in the system yet.</p>
            <a href="<?= site_url('accountant/invoices/new') ?>" class="btn" style="background:#0ea5e9;color:white;text-decoration:none;padding:10px 20px;border-radius:6px;display:inline-block;margin-top:1rem">
              <i class="fa-solid fa-plus"></i> Create Your First Invoice
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <?php if (!empty($invoices)): ?>
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Invoice Summary</h2></div>
      <div class="panel-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #0ea5e9">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Total Invoices</div>
            <div style="font-size:1.5rem;font-weight:600;color:#0ea5e9"><?= count($invoices) ?></div>
          </div>
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #10b981">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Paid Invoices</div>
            <div style="font-size:1.5rem;font-weight:600;color:#10b981">
              <?= count(array_filter($invoices, fn($i) => strtolower($i['status'] ?? '') === 'paid')) ?>
            </div>
          </div>
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #f59e0b">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Unpaid Invoices</div>
            <div style="font-size:1.5rem;font-weight:600;color:#f59e0b">
              <?= count(array_filter($invoices, fn($i) => strtolower($i['status'] ?? '') !== 'paid')) ?>
            </div>
          </div>
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #8b5cf6">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Total Amount</div>
            <div style="font-size:1.5rem;font-weight:600;color:#8b5cf6">
              $<?= number_format(array_sum(array_map(fn($i) => (float)($i['amount'] ?? 0), $invoices)), 2) ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php endif; ?>
    <?php endif; ?>

  </main>
</div>
</body>
</html>
