<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pending Charges | Accountant</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    :root {
      --primary-red: #dc2626;
      --primary-red-light: #ef4444;
      --primary-red-dark: #b91c1c;
      --text-dark: #1f2937;
      --text-gray: #6b7280;
      --bg-light: #f9fafb;
      --border-color: #e5e7eb;
      --success-green: #10b981;
      --success-green-dark: #059669;
      --warning-orange: #f97316;
      --info-blue: #3b82f6;
    }

    body {
      background: var(--bg-light);
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      flex-wrap: wrap;
      gap: 16px;
    }

    .page-title {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary-red);
      margin: 0;
    }

    .page-title i {
      font-size: 2.2rem;
    }

    .page-subtitle {
      color: var(--text-gray);
      font-size: 0.95rem;
      margin: 0;
    }

    .filter-tabs {
      display: flex;
      gap: 8px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }

    .filter-tab {
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.95rem;
      text-decoration: none;
      transition: all 0.2s ease;
      border: 2px solid transparent;
      cursor: pointer;
    }

    .filter-tab.active {
      background: var(--primary-red);
      color: #fff;
      border-color: var(--primary-red);
    }

    .filter-tab:not(.active) {
      background: #fff;
      color: var(--text-dark);
      border-color: var(--border-color);
    }

    .filter-tab:not(.active):hover {
      background: var(--bg-light);
      border-color: var(--primary-red-light);
      color: var(--primary-red);
    }

    .content-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      overflow: hidden;
    }

    .table-container {
      overflow-x: auto;
    }

    .table-custom {
      margin: 0;
      width: 100%;
    }

    .table-custom thead {
      background: #fef2f2;
    }

    .table-custom thead th {
      color: var(--primary-red-dark);
      font-weight: 700;
      font-size: 0.9rem;
      padding: 14px 16px;
      border-bottom: 2px solid var(--border-color);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .table-custom tbody td {
      padding: 16px;
      vertical-align: middle;
      border-bottom: 1px solid var(--border-color);
    }

    .table-custom tbody tr:hover {
      background: #fef2f2;
    }

    .charge-link {
      color: var(--info-blue);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.95rem;
    }

    .charge-link:hover {
      text-decoration: underline;
      color: var(--primary-red);
    }

    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-paid {
      background: #d1fae5;
      color: var(--success-green-dark);
    }

    .status-pending {
      background: #fef3c7;
      color: #d97706;
    }

    .status-partial {
      background: #dbeafe;
      color: #2563eb;
    }

    .status-cancelled {
      background: #fee2e2;
      color: var(--primary-red-dark);
    }

    .amount-total {
      font-weight: 700;
      color: var(--primary-red);
      font-size: 1rem;
    }

    .action-buttons {
      display: flex;
      gap: 8px;
      justify-content: center;
    }

    .btn-action {
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 0.875rem;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-view {
      background: var(--info-blue);
      color: #fff;
    }

    .btn-view:hover {
      background: #2563eb;
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-print {
      background: var(--warning-orange);
      color: #fff;
    }

    .btn-print:hover {
      background: #ea580c;
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(249, 115, 22, 0.3);
    }

    .btn-approve {
      background: var(--info-blue);
      color: #fff;
    }

    .btn-approve:hover {
      background: #2563eb;
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-pay {
      background: var(--success-green);
      color: #fff;
    }

    .btn-pay:hover {
      background: var(--success-green-dark);
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-cancel {
      background: var(--primary-red);
      color: #fff;
    }

    .btn-cancel:hover {
      background: var(--primary-red-dark);
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    @media (max-width: 768px) {
      .page-title {
        font-size: 1.5rem;
      }
      .filter-tabs {
        overflow-x: auto;
        flex-wrap: nowrap;
      }
      .table-container {
        overflow-x: scroll;
      }
    }
  </style>
</head>
<body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Pending Charges</h1><small>Auto-generated charges from consultations</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Accountant navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/accountant') ?>"><i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview</a>
  <a href="<?= site_url('accountant/billing') ?>"><i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px"></i>Billing & Payments</a>
  <a href="<?= site_url('accountant/pending-charges') ?>" class="active" aria-current="page"><i class="fa-solid fa-dollar-sign" style="margin-right:8px"></i>Pending Charges</a>
  <a href="<?= site_url('accountant/insurance') ?>"><i class="fa-solid fa-shield-halved" style="margin-right:8px"></i>Insurance</a>
  <a href="<?= site_url('accountant/reports') ?>"><i class="fa-solid fa-chart-line" style="margin-right:8px"></i>Financial Reports</a>
</nav></aside>
  <main class="content">
<div class="page-container" style="max-width:100%;padding:0">

  <!-- Filter Tabs -->
  <div class="filter-tabs">
    <a href="<?= site_url('accountant/pending-charges?status=all') ?>" 
       class="filter-tab <?= $statusFilter === 'all' ? 'active' : '' ?>">
      All (<?= $statusCounts['all'] ?>)
    </a>
    <a href="<?= site_url('accountant/pending-charges?status=pending') ?>" 
       class="filter-tab <?= $statusFilter === 'pending' ? 'active' : '' ?>">
      Pending (<?= $statusCounts['pending'] ?>)
    </a>
    <a href="<?= site_url('accountant/pending-charges?status=approved') ?>" 
       class="filter-tab <?= $statusFilter === 'approved' ? 'active' : '' ?>">
      Approved (<?= $statusCounts['approved'] ?>)
    </a>
    <a href="<?= site_url('accountant/pending-charges?status=paid') ?>" 
       class="filter-tab <?= $statusFilter === 'paid' ? 'active' : '' ?>">
      Paid (<?= $statusCounts['paid'] ?>)
    </a>
    <a href="<?= site_url('accountant/pending-charges?status=cancelled') ?>" 
       class="filter-tab <?= $statusFilter === 'cancelled' ? 'active' : '' ?>">
      Cancelled (<?= $statusCounts['cancelled'] ?>)
    </a>
  </div>

  <!-- Charges Table -->
  <div class="content-card">
    <div class="table-container">
      <table class="table table-custom">
        <thead>
          <tr>
            <th>ID</th>
            <th>Description/Location</th>
            <th>Associated Name</th>
            <th>Quantity</th>
            <th class="text-right">Amount</th>
            <th>Date and Time</th>
            <th class="text-center">Status</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($charges)): ?>
            <tr>
              <td colspan="8" class="text-center" style="padding: 40px;">
                <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-gray); margin-bottom: 16px; display: block;"></i>
                <p style="color: var(--text-gray); margin: 0;">No charges found</p>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($charges as $charge): ?>
              <tr>
                <td>
                  <strong><?= esc($charge['charge_number']) ?></strong>
                </td>
                <td>
                  <?= esc($charge['patient_name'] ?: 'N/A') ?>
                  <?php if (!empty($charge['patient_code'])): ?>
                    <br><small style="color: var(--text-gray);"><?= esc($charge['patient_code']) ?></small>
                  <?php endif; ?>
                </td>
                <td><?= esc($charge['doctor_name'] ?: 'N/A') ?></td>
                <td><?= $charge['item_count'] ?> item(s)</td>
                <td class="text-right amount-total" style="color: #dc2626; font-weight: 700;">₱<?= number_format($charge['total_amount'], 2) ?></td>
                <td>
                  <?php
                    $createdDate = new \DateTime($charge['created_at']);
                    echo $createdDate->format('M d, Y h:i A');
                  ?>
                </td>
                <td class="text-center">
                  <?php
                    $status = strtolower($charge['payment_status']);
                    $statusClass = 'status-pending';
                    $statusText = 'PENDING';
                    
                    if ($status === 'paid') {
                      $statusClass = 'status-paid';
                      $statusText = 'PAID';
                    } elseif ($status === 'partial') {
                      $statusClass = 'status-partial';
                      $statusText = 'PARTIAL';
                    } elseif ($status === 'cancelled') {
                      $statusClass = 'status-cancelled';
                      $statusText = 'CANCELLED';
                    }
                  ?>
                  <span class="status-badge <?= $statusClass ?>">
                    <?= $statusText ?>
                  </span>
                </td>
                <td class="text-center">
                  <div class="action-buttons">
                    <a href="<?= site_url('accountant/billing/view/' . $charge['id']) ?>" 
                       class="btn-action btn-view">
                      <i class="fas fa-eye"></i>
                      View
                    </a>
                    <?php if ($status === 'paid'): ?>
                      <a href="<?= site_url('accountant/billing/print/' . $charge['id']) ?>" 
                         target="_blank"
                         class="btn-action btn-print">
                        <i class="fas fa-file-invoice"></i>
                        Print Receipt
                      </a>
                    <?php else: ?>
                      <a href="<?= site_url('accountant/billing/invoice/' . $charge['id']) ?>" 
                         target="_blank"
                         class="btn-action btn-print">
                        <i class="fas fa-file-invoice"></i>
                        Invoice
                      </a>
                      <?php if ($status === 'pending'): ?>
                        <a href="<?= site_url('accountant/pending-charges/approve/' . $charge['id']) ?>" 
                           class="btn-action btn-approve"
                           onclick="return confirm('Are you sure you want to approve this charge?');">
                          <i class="fas fa-check"></i>
                          Approve
                        </a>
                        <a href="<?= site_url('accountant/pending-charges/pay/' . $charge['id']) ?>" 
                           class="btn-action btn-pay"
                           onclick="return confirm('Are you sure you want to mark this charge as paid?');">
                          <i class="fas fa-money-bill-wave"></i>
                          Pay
                        </a>
                        <a href="<?= site_url('accountant/pending-charges/cancel/' . $charge['id']) ?>" 
                           class="btn-action btn-cancel"
                           onclick="return confirm('Are you sure you want to cancel this charge?');">
                          <i class="fas fa-times"></i>
                          Cancel
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
  </main>
</div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>

