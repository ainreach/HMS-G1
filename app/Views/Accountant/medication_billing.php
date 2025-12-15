<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Medication Billing | Accountant</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
<header class="dash-topbar" role="banner">
  <div class="topbar-inner">
    <div class="brand">
      <img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
      <div class="brand-text">
        <h1 style="font-size:1.25rem;margin:0">Medication Billing</h1>
        <small>Summary of medication-related charges</small>
      </div>
    </div>
    <div class="top-right" aria-label="User session">
      <span class="role">
        <i class="fa-regular fa-user"></i>
        <?= esc(session('username') ?? session('role') ?? 'User') ?>
      </span>
      <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">
        Logout
      </a>
    </div>
  </div>
</header>

<div class="layout">
<?= $this->include('Accountant/sidebar', ['currentPage' => 'billing']) ?>

  <main class="content" style="padding:16px">
    <?php if (session()->getFlashdata('error')): ?>
      <div style="padding:12px;background:#fee2e2;border:1px solid #fca5a5;border-radius:6px;margin-bottom:16px;color:#991b1b">
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div style="padding:12px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;margin-bottom:16px;color:#065f46">
        <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">
          <i class="fa-solid fa-pills"></i> Medication Billing
        </h2>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient ID</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Medicine / Item</th>
              <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Qty</th>
              <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Unit Price</th>
              <th style="text-align:right;padding:8px;border-bottom:1px solid #e5e7eb">Total</th>
              <th style="text-align:center;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
              <?php
                $rawDate = $item['bill_date'] ?? ($item['created_at'] ?? null);
                $displayDate = $rawDate ? date('M d, Y', strtotime($rawDate)) : 'N/A';
                $status = strtolower($item['payment_status'] ?? 'pending');
              ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;white-space:nowrap;"><?= esc($displayDate) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;">
                  <?= esc(trim(($item['first_name'] ?? '') . ' ' . ($item['last_name'] ?? ''))) ?: 'N/A' ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;">
                  <?= esc($item['patient_code'] ?? 'N/A') ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;">
                  <?= esc($item['item_name'] ?? '') ?>
                  <?php if (!empty($item['description'])): ?>
                    <br><small style="color:#6b7280;"><?= esc($item['description']) ?></small>
                  <?php endif; ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right;">
                  <?= number_format((float)($item['quantity'] ?? 0), 2) ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right;">
                  ₱<?= number_format((float)($item['unit_price'] ?? 0), 2) ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:right;font-weight:600;color:#dc2626;">
                  ₱<?= number_format((float)($item['total_price'] ?? 0), 2) ?>
                </td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:center;">
                  <?php
                    $badgeStyle = 'background:#fef3c7;color:#92400e;';
                    $label = 'Pending';
                    if ($status === 'paid') {
                      $badgeStyle = 'background:#d1fae5;color:#065f46;';
                      $label = 'Paid';
                    } elseif ($status === 'partial') {
                      $badgeStyle = 'background:#dbeafe;color:#1e40af;';
                      $label = 'Partial';
                    } elseif ($status === 'cancelled') {
                      $badgeStyle = 'background:#fee2e2;color:#b91c1c;';
                      $label = 'Cancelled';
                    }
                  ?>
                  <span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:0.75rem;font-weight:600;<?= $badgeStyle ?>">
                    <?= esc($label) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" style="padding:40px;text-align:center;color:#6b7280;">
                <i class="fas fa-pills" style="font-size:2rem;margin-bottom:8px;display:block;color:#cbd5f5;"></i>
                No medication billing records found.
              </td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>
