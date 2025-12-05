<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prescription Queue</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .queue-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
      padding-bottom: 16px;
      border-bottom: 2px solid #e5e7eb;
    }
    .queue-header h1 {
      margin: 0;
      font-size: 1.5rem;
      color: #000000;
      font-weight: 700;
    }
    .queue-header i {
      font-size: 1.75rem;
      color: #3b82f6;
    }
    .status-tabs {
      display: flex;
      gap: 8px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }
    .status-tab {
      padding: 12px 20px;
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      background: #ffffff;
      color: #6b7280;
      font-weight: 600;
      font-size: 0.875rem;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .status-tab:hover {
      border-color: #3b82f6;
      color: #3b82f6;
    }
    .status-tab.active {
      background: #10b981;
      border-color: #10b981;
      color: #ffffff;
    }
    .status-tab.active.light {
      background: #d1fae5;
      border-color: #10b981;
      color: #000000;
    }
    .status-count {
      background: rgba(0, 0, 0, 0.1);
      padding: 2px 8px;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 700;
    }
    .status-tab.active .status-count {
      background: rgba(255, 255, 255, 0.3);
    }
    .status-tab.active.light .status-count {
      background: rgba(0, 0, 0, 0.1);
    }
    .summary-bar {
      display: flex;
      gap: 16px;
      padding: 12px 16px;
      background: #f9fafb;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      margin-bottom: 24px;
      flex-wrap: wrap;
      font-size: 0.875rem;
      color: #6b7280;
    }
    .summary-item {
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .summary-item strong {
      color: #000000;
      font-weight: 600;
    }
    .empty-state {
      text-align: center;
      padding: 60px 20px;
    }
    .empty-state-icon {
      width: 120px;
      height: 120px;
      margin: 0 auto 24px;
      background: #f3f4f6;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #9ca3af;
      font-size: 3rem;
    }
    .empty-state h3 {
      margin: 0 0 8px 0;
      font-size: 1.25rem;
      color: #374151;
      font-weight: 600;
    }
    .empty-state p {
      margin: 0;
      color: #6b7280;
      font-size: 0.875rem;
    }
    .prescription-card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 16px;
      margin-bottom: 12px;
      transition: all 0.2s;
    }
    .prescription-card:hover {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      border-color: #d1d5db;
    }
    .prescription-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 12px;
    }
    .prescription-id {
      font-weight: 700;
      color: #000000;
      font-size: 1rem;
    }
    .prescription-date {
      color: #6b7280;
      font-size: 0.875rem;
    }
    .prescription-info {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 12px;
      margin-bottom: 12px;
    }
    .info-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .info-label {
      font-size: 0.75rem;
      color: #6b7280;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .info-value {
      font-size: 0.875rem;
      color: #000000;
      font-weight: 500;
    }
    .prescription-actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      padding-top: 12px;
      border-top: 1px solid #f3f4f6;
    }
    .btn-action {
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 0.875rem;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      border: none;
      transition: all 0.2s;
    }
    .btn-approve {
      background: #3b82f6;
      color: white;
    }
    .btn-approve:hover {
      background: #2563eb;
    }
    .btn-prepare {
      background: #f59e0b;
      color: white;
    }
    .btn-prepare:hover {
      background: #d97706;
    }
    .btn-dispense {
      background: #10b981;
      color: white;
    }
    .btn-dispense:hover {
      background: #059669;
    }
    .btn-administer {
      background: #8b5cf6;
      color: white;
    }
    .btn-administer:hover {
      background: #7c3aed;
    }
    .btn-view {
      background: #6b7280;
      color: white;
    }
    .btn-view:hover {
      background: #4b5563;
    }
  </style>
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Pharmacy</h1><small>Prescription Queue Management</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i> <?= esc(session('username') ?? session('role') ?? 'User') ?></span>
    <a href="<?= site_url('dashboard/pharmacist') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px"><i class="fa-solid fa-arrow-left"></i> Back</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Pharmacy navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/pharmacist') ?>">Overview</a>
  <a href="<?= site_url('pharmacy/prescriptions') ?>" class="active" aria-current="page" data-feature="pharmacy">Prescription Queue</a>
  <a href="<?= site_url('pharmacy/inventory') ?>" data-feature="pharmacy">Inventory</a>
  <a href="<?= site_url('pharmacy/low-stock-alerts') ?>" data-feature="pharmacy">Low Stock</a>
  <a href="<?= site_url('pharmacy/medicine-expiry') ?>" data-feature="pharmacy">Expiry Alerts</a>
</nav></aside>
  <main class="content" style="padding:16px">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success" style="margin-bottom:16px;padding:12px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:6px;color:#065f46">
        <?= esc(session()->getFlashdata('success')) ?>
      </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-error" style="margin-bottom:16px;padding:12px;background:#fee2e2;border:1px solid #fca5a5;border-radius:6px;color:#991b1b">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <div class="queue-header">
      <i class="fa-solid fa-clipboard-list"></i>
      <h1>Prescription Queue</h1>
    </div>

    <!-- Status Tabs -->
    <div class="status-tabs">
      <a href="<?= site_url('pharmacy/prescriptions?status=pending') ?>" 
         class="status-tab <?= $currentStatus === 'pending' ? 'active' : '' ?>">
        <i class="fa-solid fa-clock"></i> Pending <span class="status-count"><?= esc($counts['pending']) ?></span>
      </a>
      <a href="<?= site_url('pharmacy/prescriptions?status=approved') ?>" 
         class="status-tab <?= $currentStatus === 'approved' ? 'active' : '' ?>">
        <i class="fa-solid fa-check-circle"></i> Approved <span class="status-count"><?= esc($counts['approved']) ?></span>
      </a>
      <a href="<?= site_url('pharmacy/prescriptions?status=prepared') ?>" 
         class="status-tab <?= $currentStatus === 'prepared' ? 'active' : '' ?>">
        <i class="fa-solid fa-box"></i> Prepared <span class="status-count"><?= esc($counts['prepared']) ?></span>
      </a>
      <a href="<?= site_url('pharmacy/prescriptions?status=dispensed') ?>" 
         class="status-tab <?= $currentStatus === 'dispensed' ? 'active light' : '' ?>">
        <i class="fa-solid fa-prescription-bottle-medical"></i> Dispensed <span class="status-count"><?= esc($counts['dispensed']) ?></span>
      </a>
      <a href="<?= site_url('pharmacy/prescriptions?status=administered') ?>" 
         class="status-tab <?= $currentStatus === 'administered' ? 'active light' : '' ?>">
        <i class="fa-solid fa-syringe"></i> Administered <span class="status-count"><?= esc($counts['administered']) ?></span>
      </a>
    </div>

    <!-- Summary Bar -->
    <div class="summary-bar">
      <div class="summary-item">
        <strong>Pending:</strong> <span><?= esc($counts['pending']) ?></span>
      </div>
      <div class="summary-item">
        <strong>Approved:</strong> <span><?= esc($counts['approved']) ?></span>
      </div>
      <div class="summary-item">
        <strong>Prepared:</strong> <span><?= esc($counts['prepared']) ?></span>
      </div>
      <div class="summary-item">
        <strong>Dispensed:</strong> <span><?= esc($counts['dispensed']) ?></span>
      </div>
      <div class="summary-item">
        <strong>Administered:</strong> <span><?= esc($counts['administered']) ?></span>
      </div>
    </div>

    <!-- Prescriptions List -->
    <?php if (!empty($prescriptions)): ?>
      <?php foreach ($prescriptions as $rx): ?>
        <div class="prescription-card">
          <div class="prescription-header">
            <div>
              <span class="prescription-id">Prescription #<?= esc($rx['id']) ?></span>
            </div>
            <div class="prescription-date">
              <i class="fa-solid fa-calendar"></i> <?= esc(date('M d, Y', strtotime($rx['prescription_date'] ?? $rx['start_date']))) ?>
            </div>
          </div>
          
          <div class="prescription-info">
            <div class="info-item">
              <span class="info-label">Patient</span>
              <span class="info-value"><?= esc($rx['patient_name'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
              <span class="info-label">Doctor</span>
              <span class="info-value"><?= esc($rx['doctor_name'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
              <span class="info-label">Medication</span>
              <span class="info-value"><strong><?= esc($rx['medicine_name'] ?? $rx['medication'] ?? 'N/A') ?></strong></span>
            </div>
            <div class="info-item">
              <span class="info-label">Dosage</span>
              <span class="info-value"><?= esc($rx['dosage'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
              <span class="info-label">Frequency</span>
              <span class="info-value"><?= esc($rx['frequency'] ?? 'N/A') ?></span>
            </div>
            <?php if (!empty($rx['instructions'])): ?>
            <div class="info-item">
              <span class="info-label">Instructions</span>
              <span class="info-value"><?= esc($rx['instructions']) ?></span>
            </div>
            <?php endif; ?>
          </div>

          <div class="prescription-actions">
            <?php if ($currentStatus === 'pending'): ?>
              <form action="<?= site_url('pharmacy/prescriptions/' . $rx['id'] . '/update-status') ?>" method="post" style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn-action btn-approve" onclick="return confirm('Approve this prescription?')">
                  <i class="fa-solid fa-check"></i> Approve
                </button>
              </form>
            <?php elseif ($currentStatus === 'approved'): ?>
              <form action="<?= site_url('pharmacy/prescriptions/' . $rx['id'] . '/update-status') ?>" method="post" style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="status" value="prepared">
                <button type="submit" class="btn-action btn-prepare" onclick="return confirm('Mark this prescription as prepared?')">
                  <i class="fa-solid fa-box"></i> Mark as Prepared
                </button>
              </form>
            <?php elseif ($currentStatus === 'prepared'): ?>
              <a href="<?= site_url('pharmacy/dispense/from-prescription/' . $rx['id']) ?>" class="btn-action btn-dispense">
                <i class="fa-solid fa-prescription-bottle-medical"></i> Dispense
              </a>
            <?php elseif ($currentStatus === 'dispensed'): ?>
              <form action="<?= site_url('pharmacy/prescriptions/' . $rx['id'] . '/update-status') ?>" method="post" style="display:inline">
                <?= csrf_field() ?>
                <input type="hidden" name="status" value="administered">
                <button type="submit" class="btn-action btn-administer" onclick="return confirm('Mark this prescription as administered?')">
                  <i class="fa-solid fa-syringe"></i> Mark as Administered
                </button>
              </form>
            <?php endif; ?>
            <a href="<?= site_url('pharmacy/prescription/view/' . $rx['id']) ?>" class="btn-action btn-view">
              <i class="fa-solid fa-eye"></i> View Details
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state">
        <div class="empty-state-icon">
          <i class="fa-solid fa-check-circle"></i>
        </div>
        <h3>No <?= ucfirst(esc($currentStatus)) ?> Prescriptions</h3>
        <p>
          <?php if ($currentStatus === 'dispensed'): ?>
            No prescriptions are waiting for nurse administration.
          <?php elseif ($currentStatus === 'administered'): ?>
            No prescriptions have been administered yet.
          <?php else: ?>
            No prescriptions found in this queue.
          <?php endif; ?>
        </p>
      </div>
    <?php endif; ?>
  </main></div>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body></html>

