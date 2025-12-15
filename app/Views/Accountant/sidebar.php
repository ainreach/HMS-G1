<?php
// Accountant Sidebar - Shared across all accountant pages
// $currentPage should be passed when including this sidebar
$currentPage = $currentPage ?? 'overview';
?>
<aside class="simple-sidebar" role="navigation" aria-label="Accountant navigation">
  <nav class="side-nav">
    <a href="<?= site_url('dashboard/accountant') ?>" 
       class="<?= $currentPage === 'overview' ? 'active' : '' ?>" 
       <?= $currentPage === 'overview' ? 'aria-current="page"' : '' ?>>
      <i class="fa-solid fa-chart-pie" style="margin-right:8px"></i>Overview
    </a>
    <a href="<?= site_url('accountant/billing') ?>" 
       class="<?= $currentPage === 'billing' ? 'active' : '' ?>" 
       <?= $currentPage === 'billing' ? 'aria-current="page"' : '' ?>>
      <i class="fa-solid fa-file-invoice-dollar" style="margin-right:8px"></i>Billing & Payments
    </a>
    <a href="<?= site_url('accountant/consolidated-bills') ?>" 
       class="<?= $currentPage === 'consolidated-bills' ? 'active' : '' ?>" 
       <?= $currentPage === 'consolidated-bills' ? 'aria-current="page"' : '' ?>>
      <i class="fa-solid fa-file-invoice" style="margin-right:8px"></i>Consolidated Bills
    </a>
    <a href="<?= site_url('accountant/pending-charges') ?>" 
       class="<?= $currentPage === 'pending-charges' ? 'active' : '' ?>" 
       <?= $currentPage === 'pending-charges' ? 'aria-current="page"' : '' ?>>
      <i class="fa-solid fa-dollar-sign" style="margin-right:8px"></i>Pending Charges
    </a>
    <a href="<?= site_url('accountant/invoices') ?>" 
       class="<?= $currentPage === 'invoices' ? 'active' : '' ?>" 
       <?= $currentPage === 'invoices' ? 'aria-current="page"' : '' ?>>
      <i class="fa-solid fa-file-lines" style="margin-right:8px"></i>Invoices
    </a>
    <a href="<?= site_url('accountant/payments') ?>" 
       class="<?= $currentPage === 'payments' ? 'active' : '' ?>" 
       <?= $currentPage === 'payments' ? 'aria-current="page"' : '' ?>>
      <i class="fa-solid fa-sack-dollar" style="margin-right:8px"></i>Payments
    </a>
    <a href="<?= site_url('accountant/lab-test-approvals') ?>" 
       class="<?= $currentPage === 'lab-test-approvals' ? 'active' : '' ?>" 
       <?= $currentPage === 'lab-test-approvals' ? 'aria-current="page"' : '' ?>>
      <i class="fa-solid fa-vial" style="margin-right:8px"></i>Lab Test Approvals
    </a>
    <a href="<?= site_url('accountant/patients/bills') ?>" 
       class="<?= $currentPage === 'patient-room-bills' ? 'active' : '' ?>" 
       <?= $currentPage === 'patient-room-bills' ? 'aria-current="page"' : '' ?>>
      <i class="fa-solid fa-bed" style="margin-right:8px"></i>Patient Room Bills
    </a>
    <a href="<?= site_url('accountant/insurance') ?>" 
       class="<?= $currentPage === 'insurance' ? 'active' : '' ?>" 
       <?= $currentPage === 'insurance' ? 'aria-current="page"' : '' ?>>
      <i class="fa-solid fa-shield-halved" style="margin-right:8px"></i>Insurance
    </a>
    <a href="<?= site_url('accountant/reports') ?>" 
       class="<?= $currentPage === 'reports' ? 'active' : '' ?>" 
       <?= $currentPage === 'reports' ? 'aria-current="page"' : '' ?>>
      <i class="fa-solid fa-chart-line" style="margin-right:8px"></i>Financial Reports
    </a>
  </nav>
</aside>

