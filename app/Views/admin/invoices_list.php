<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">All Invoices (<?= number_format($total) ?>)</h2>
  </div>
  <div class="panel-body">
    <?php if (!empty($invoices)): ?>
      <table class="table">
        <thead>
          <tr>
            <th>Invoice #</th>
            <th>Patient Name</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Issued Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($invoices as $invoice): ?>
            <tr>
              <td><?= esc($invoice['invoice_no']) ?></td>
              <td><?= esc($invoice['patient_name']) ?></td>
              <td>$<?= number_format($invoice['amount'], 2) ?></td>
              <td>
                <span class="badge badge-<?= $invoice['status'] ?>">
                  <?= ucfirst($invoice['status']) ?>
                </span>
              </td>
              <td><?= date('M j, Y', strtotime($invoice['issued_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      
      <!-- Pagination -->
      <?php if ($total > $perPage): ?>
        <div class="pagination">
          <?php if ($hasPrev): ?>
            <a href="?page=<?= $page - 1 ?>">
              <i class="fas fa-chevron-left"></i> Previous
            </a>
          <?php endif; ?>
          
          <span class="current">Page <?= $page ?> of <?= ceil($total / $perPage) ?></span>
          
          <?php if ($hasNext): ?>
            <a href="?page=<?= $page + 1 ?>">
              Next <i class="fas fa-chevron-right"></i>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <div style="text-align: center; padding: 2rem; color: #6b7280;">
        <i class="fas fa-file-invoice" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
        <h3>No invoices found</h3>
        <p>There are no invoices in the system yet.</p>
      </div>
    <?php endif; ?>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<?= $this->endSection() ?>
