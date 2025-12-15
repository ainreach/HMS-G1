<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Consolidated Bills - Hospital Management System</title>
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
        <h1 style="font-size:1.25rem;margin:0">Consolidated Patient Bills</h1>
        <small>All charges accumulated in one bill per patient</small>
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
<?= $this->include('Accountant/sidebar', ['currentPage' => 'consolidated-bills']) ?>

  <main class="content">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Patient Billing List - Consolidated Bills (<?= count($bills ?? []) ?>)</h2>
        <div style="display:flex;gap:8px;align-items:center">
          <a href="<?= site_url('accountant/billing') ?>" class="btn" style="background:#6b7280;color:white;text-decoration:none;padding:8px 12px;border-radius:6px;font-size:0.875rem">
            <i class="fa-solid fa-arrow-left"></i> Back
          </a>
        </div>
      </div>
      
      <!-- Status Filter Tabs -->
      <?php if (isset($statusCounts)): ?>
      <div style="border-bottom:1px solid #e5e7eb;padding:0 16px;display:flex;gap:8px">
        <a href="<?= site_url('accountant/consolidated-bills?status=all') ?>" 
           style="padding:12px 16px;text-decoration:none;border-bottom:2px solid <?= ($statusFilter ?? 'all') === 'all' ? '#0ea5e9' : 'transparent' ?>;color:<?= ($statusFilter ?? 'all') === 'all' ? '#0ea5e9' : '#6b7280' ?>;font-weight:<?= ($statusFilter ?? 'all') === 'all' ? '600' : '400' ?>">
          All (<?= $statusCounts['all'] ?? 0 ?>)
        </a>
        <a href="<?= site_url('accountant/consolidated-bills?status=pending') ?>" 
           style="padding:12px 16px;text-decoration:none;border-bottom:2px solid <?= ($statusFilter ?? '') === 'pending' ? '#f59e0b' : 'transparent' ?>;color:<?= ($statusFilter ?? '') === 'pending' ? '#f59e0b' : '#6b7280' ?>;font-weight:<?= ($statusFilter ?? '') === 'pending' ? '600' : '400' ?>">
          Pending (<?= $statusCounts['pending'] ?? 0 ?>)
        </a>
        <a href="<?= site_url('accountant/consolidated-bills?status=partial') ?>" 
           style="padding:12px 16px;text-decoration:none;border-bottom:2px solid <?= ($statusFilter ?? '') === 'partial' ? '#f59e0b' : 'transparent' ?>;color:<?= ($statusFilter ?? '') === 'partial' ? '#f59e0b' : '#6b7280' ?>;font-weight:<?= ($statusFilter ?? '') === 'partial' ? '600' : '400' ?>">
          Partially Paid (<?= $statusCounts['partial'] ?? 0 ?>)
        </a>
        <a href="<?= site_url('accountant/consolidated-bills?status=paid') ?>" 
           style="padding:12px 16px;text-decoration:none;border-bottom:2px solid <?= ($statusFilter ?? '') === 'paid' ? '#10b981' : 'transparent' ?>;color:<?= ($statusFilter ?? '') === 'paid' ? '#10b981' : '#6b7280' ?>;font-weight:<?= ($statusFilter ?? '') === 'paid' ? '600' : '400' ?>">
          Fully Paid (<?= $statusCounts['paid'] ?? 0 ?>)
        </a>
      </div>
      <?php endif; ?>
      <div class="panel-body" style="overflow:auto">
        <?php if (!empty($bills)): ?>
          <table class="table" style="width:100%;border-collapse:collapse">
            <thead>
              <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient ID</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient Name</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Type</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Bill #</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Items</th>
                <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Total Amount</th>
                <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Paid Amount</th>
                <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Balance</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Billing Status</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($bills as $billData): 
                $bill = $billData['bill'];
                if (!$bill) {
                  // Patient without bill
                  ?>
                  <tr>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                      <span style="color:#0ea5e9;font-weight:500"><?= esc($billData['patient_code']) ?></span>
                    </td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                      <strong><?= esc($billData['patient_name']) ?></strong>
                    </td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                      <?php if ($billData['is_inpatient'] ?? false): ?>
                        <span style="background:#3b82f6;color:white;padding:4px 8px;border-radius:12px;font-size:0.75rem;font-weight:500">
                          <i class="fa-solid fa-bed"></i> Inpatient
                        </span>
                      <?php else: ?>
                        <span style="background:#6b7280;color:white;padding:4px 8px;border-radius:12px;font-size:0.75rem;font-weight:500">
                          <i class="fa-solid fa-user"></i> Outpatient
                        </span>
                      <?php endif; ?>
                    </td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6" colspan="6">
                      <span style="color:#6b7280;font-style:italic">No active bill</span>
                    </td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                      <a href="<?= site_url('accountant/consolidated-bill/' . $billData['patient_id']) ?>" class="btn-small" style="background:#0ea5e9;color:white;padding:4px 8px;border-radius:4px;text-decoration:none;font-size:0.75rem">
                        <i class="fa-solid fa-plus"></i> Create Bill
                      </a>
                    </td>
                  </tr>
                  <?php
                  continue;
                }
                
                $items = $bill['items'] ?? [];
                $totalPaid = $bill['total_paid'] ?? 0;
                $balance = (float)($bill['balance'] ?? 0);
                $status = strtolower($bill['payment_status'] ?? 'pending');
              ?>
                <tr>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <span style="color:#0ea5e9;font-weight:500"><?= esc($billData['patient_code']) ?></span>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <strong><?= esc($billData['patient_name']) ?></strong>
                    <br>
                    <small style="color:#6b7280"><?= esc($billData['phone']) ?></small>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php if ($billData['is_inpatient'] ?? false): ?>
                      <span style="background:#3b82f6;color:white;padding:4px 8px;border-radius:12px;font-size:0.75rem;font-weight:500">
                        <i class="fa-solid fa-bed"></i> Inpatient
                      </span>
                    <?php else: ?>
                      <span style="background:#6b7280;color:white;padding:4px 8px;border-radius:12px;font-size:0.75rem;font-weight:500">
                        <i class="fa-solid fa-user"></i> Outpatient
                      </span>
                    <?php endif; ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <span style="color:#6b7280;font-size:0.875rem"><?= esc($bill['invoice_number'] ?? 'N/A') ?></span>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <span style="color:#6b7280"><?= count($items) ?> item(s)</span>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right">
                    <strong>$<?= number_format((float)($bill['total_amount'] ?? 0), 2) ?></strong>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right;color:#10b981">
                    $<?= number_format($totalPaid, 2) ?>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right">
                    <strong style="color:<?= $balance > 0 ? '#ef4444' : '#10b981' ?>">
                      $<?= number_format($balance, 2) ?>
                    </strong>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <?php
                      $badgeColor = '#6b7280';
                      if ($status === 'paid') $badgeColor = '#16a34a';
                      elseif ($status === 'partial') $badgeColor = '#f59e0b';
                      elseif ($status === 'overdue') $badgeColor = '#ef4444';
                    ?>
                    <span style="background:<?= $badgeColor ?>20;color:<?= $badgeColor ?>;padding:4px 8px;border-radius:12px;font-size:0.75rem;font-weight:500">
                      <?= esc(ucfirst($status)) ?>
                    </span>
                  </td>
                  <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                    <div style="display:flex;gap:4px;flex-wrap:wrap">
                      <a href="<?= site_url('accountant/consolidated-bill/' . $billData['patient_id']) ?>" class="btn-small" style="background:#3b82f6;color:white;padding:4px 8px;border-radius:4px;text-decoration:none;font-size:0.75rem" title="View Consolidated Bill">
                        <i class="fa-solid fa-eye"></i> View
                      </a>
                      <?php if ($status !== 'paid'): ?>
                      <a href="<?= site_url('accountant/accept-payment/' . $billData['patient_id']) ?>" class="btn-small" style="background:#10b981;color:white;padding:4px 8px;border-radius:4px;text-decoration:none;font-size:0.75rem" title="Accept Payment">
                        <i class="fa-solid fa-money-bill-wave"></i> Pay
                      </a>
                      <?php endif; ?>
                      <a href="<?= site_url('accountant/print-bill/' . $billData['patient_id']) ?>" class="btn-small" style="background:#8b5cf6;color:white;padding:4px 8px;border-radius:4px;text-decoration:none;font-size:0.75rem" target="_blank" title="Print/Export Invoice">
                        <i class="fa-solid fa-print"></i> Print
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
            <h3>No active bills found</h3>
            <p>There are no active consolidated bills at this time.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <?php if (!empty($bills)): ?>
    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Summary</h2></div>
      <div class="panel-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #0ea5e9">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Total Bills</div>
            <div style="font-size:1.5rem;font-weight:600;color:#0ea5e9"><?= count($bills) ?></div>
          </div>
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #8b5cf6">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Total Amount</div>
            <div style="font-size:1.5rem;font-weight:600;color:#8b5cf6">
              $<?= number_format((float)($totalAmount ?? 0), 2) ?>
            </div>
          </div>
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #10b981">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Total Paid</div>
            <div style="font-size:1.5rem;font-weight:600;color:#10b981">
              $<?= number_format((float)($totalPaid ?? 0), 2) ?>
            </div>
          </div>
          <div style="padding:16px;background:#f8fafc;border-radius:8px;border-left:4px solid #f59e0b">
            <div style="color:#64748b;font-size:0.875rem;margin-bottom:4px">Outstanding Balance</div>
            <div style="font-size:1.5rem;font-weight:600;color:#f59e0b">
              $<?= number_format((float)($totalBalance ?? 0), 2) ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php endif; ?>

  </main>
</div>
</body>
</html>

