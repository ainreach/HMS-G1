<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prescriptions</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Prescriptions</h1><small>Manage patient prescriptions</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('dashboard/pharmacist') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Pharmacy navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/pharmacist') ?>">Overview</a>
  <a href="<?= site_url('pharmacy/prescriptions') ?>" class="active" aria-current="page" data-feature="pharmacy">Prescriptions</a>
  <a href="<?= site_url('pharmacy/dispense/new') ?>" data-feature="pharmacy">Dispense</a>
  <a href="<?= site_url('pharmacy/inventory') ?>" data-feature="pharmacy">Inventory</a>
  <a href="<?= site_url('pharmacy/low-stock-alerts') ?>" data-feature="pharmacy">Low Stock</a>
  <a href="<?= site_url('pharmacy/medicine-expiry') ?>" data-feature="pharmacy">Expiry Alerts</a>
</nav></aside>
  <main class="content" style="padding:16px">
    <?php if (session('success')): ?>
      <div class="alert alert-success" style="margin-bottom:16px;padding:12px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;color:#065f46">
        <?= esc(session('success')) ?>
      </div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div class="alert alert-error" style="margin-bottom:16px;padding:12px;background:#fee2e2;border:1px solid #fca5a5;border-radius:6px;color:#991b1b">
        <?= esc(session('error')) ?>
      </div>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
        <h2 style="margin:0;font-size:1.1rem">All Prescriptions</h2>
        <div style="display:flex;gap:8px">
          <input type="text" id="searchInput" placeholder="Search prescriptions..." style="padding:6px 12px;border:1px solid #e5e7eb;border-radius:6px;min-width:250px">
        </div>
      </div>
      <div class="panel-body" style="overflow:auto">
        <table class="table" id="prescriptionsTable" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">ID</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Patient</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Doctor</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Medicine</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Dosage</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Frequency</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Date</th>
              <th style="text-align:left;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Status</th>
              <th style="text-align:center;padding:10px;border-bottom:2px solid #e5e7eb;font-weight:600">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($prescriptions)) : foreach ($prescriptions as $rx) : 
              $statusColors = [
                'pending' => 'background:#fef3c7;color:#92400e',
                'dispensed' => 'background:#d1fae5;color:#065f46',
                'cancelled' => 'background:#fee2e2;color:#991b1b'
              ];
              $statusStyle = $statusColors[$rx['status'] ?? 'pending'] ?? 'background:#f3f4f6;color:#374151';
            ?>
              <tr style="border-bottom:1px solid #f3f4f6">
                <td style="padding:10px"><?= esc($rx['id']) ?></td>
                <td style="padding:10px"><?= esc($rx['patient_name'] ?? 'N/A') ?></td>
                <td style="padding:10px"><?= esc($rx['doctor_name'] ?? 'N/A') ?></td>
                <td style="padding:10px"><?= esc($rx['medicine_name'] ?? 'N/A') ?></td>
                <td style="padding:10px"><?= esc($rx['dosage'] ?? 'N/A') ?></td>
                <td style="padding:10px"><?= esc($rx['frequency'] ?? 'N/A') ?></td>
                <td style="padding:10px"><?= esc($rx['prescription_date'] ?? date('Y-m-d')) ?></td>
                <td style="padding:10px">
                  <span style="padding:4px 8px;border-radius:4px;font-size:0.85rem;font-weight:500;<?= $statusStyle ?>">
                    <?= ucfirst(esc($rx['status'] ?? 'pending')) ?>
                  </span>
                </td>
                <td style="padding:10px;text-align:center">
                  <div style="display:flex;gap:6px;justify-content:center">
                    <a href="<?= site_url('pharmacy/prescription/view/' . $rx['id']) ?>" 
                       class="btn-icon" 
                       title="View Details"
                       style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:4px;text-decoration:none;display:inline-flex;align-items:center">
                      <i class="fa-solid fa-eye"></i>
                    </a>
                    <?php if (($rx['status'] ?? 'pending') === 'pending'): ?>
                      <a href="<?= site_url('pharmacy/dispense/from-prescription/' . $rx['id']) ?>" 
                         class="btn-icon" 
                         title="Dispense"
                         style="padding:6px 10px;border:1px solid #10b981;background:#10b981;color:white;border-radius:4px;text-decoration:none;display:inline-flex;align-items:center">
                        <i class="fa-solid fa-prescription-bottle"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="9" style="padding:24px;text-align:center;color:#6b7280">No prescriptions found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
// Simple search functionality
document.getElementById('searchInput')?.addEventListener('input', function(e) {
  const search = e.target.value.toLowerCase();
  const rows = document.querySelectorAll('#prescriptionsTable tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(search) ? '' : 'none';
  });
});
</script>
</body></html>