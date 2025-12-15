<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accountant Dashboard</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Accountant</h1><small>Billing • Payments • Insurance claims</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout">
<?= $this->include('Accountant/sidebar', ['currentPage' => 'overview']) ?>
  <main class="content">
    <section class="kpi-grid" aria-label="Key indicators">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Invoices Today</span><i class="fa-solid fa-receipt" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($invoicesToday ?? 0) ?></div></article>
      <article class="kpi-card kpi-success"><div class="kpi-head"><span>Payments</span><i class="fa-solid fa-circle-dollar-to-slot" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite">$<?= number_format((float)($paymentsToday ?? 0), 2) ?></div></article>
      <article class="kpi-card kpi-warning"><div class="kpi-head"><span>Appointments Today</span><i class="fa-solid fa-calendar-check" aria-hidden="true"></i></div><div class="kpi-value" aria-live="polite"><?= esc($appointmentsToday ?? 0) ?></div></article>
    </section>

    <section class="panel" style="margin-top:16px">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Quick Actions</h2></div>
      <div class="panel-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="<?= site_url('accountant/invoices/new') ?>" style="background:#0ea5e9;color:white;padding:10px 14px;border-radius:6px;text-decoration:none;font-size:0.875rem;display:inline-block">Create Invoice</a>
        <a href="<?= site_url('accountant/payments/new') ?>" style="background:#10b981;color:white;padding:10px 14px;border-radius:6px;text-decoration:none;font-size:0.875rem;display:inline-block">Record Payment</a>
        <a href="<?= site_url('accountant/statements') ?>" style="background:#6b7280;color:white;padding:10px 14px;border-radius:6px;text-decoration:none;font-size:0.875rem;display:inline-block">Download Statement</a>
      </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px">
    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Transactions</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Type</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Amount</th>
            </tr>
          </thead>
            <tbody>
          <?php if (!empty($recent)) : ?>
            <?php foreach ($recent as $row) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('M d', strtotime($row['date']))) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['type']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($row['patient']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">$<?= number_format((float)$row['amount'], 2) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="4" style="padding:12px;text-align:center;color:#6b7280">No recent data.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="panel">
      <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Recent Appointments</h2></div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Patient</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Time</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Status</th>
            </tr>
          </thead>
            <tbody>
          <?php if (!empty($recentAppointments)) : ?>
            <?php foreach ($recentAppointments as $apt) : ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('M d', strtotime($apt['appointment_date']))) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($apt['first_name'] . ' ' . $apt['last_name']) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc(date('h:i A', strtotime($apt['appointment_time']))) ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <span class="badge <?= ($apt['status'] === 'completed' ? 'badge-success' : ($apt['status'] === 'cancelled' ? 'badge-danger' : 'badge-warning')) ?>">
                    <?= esc(ucfirst($apt['status'] ?? 'scheduled')) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="4" style="padding:12px;text-align:center;color:#6b7280">No recent appointments.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>
