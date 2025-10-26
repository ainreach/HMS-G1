<?= $this->include('admin/sidebar') ?>

<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Payments</h2>
  </div>
  <div class="panel-body">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <div>
        <h3 style="margin:0;font-size:1rem;color:#6b7280">Total Payments: <?= number_format($total) ?></h3>
      </div>
      <div>
        <a href="<?= site_url('accountant/payments/new') ?>" class="btn">
          <i class="fas fa-plus"></i> New Payment
        </a>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Payment ID</th>
            <th>Patient Name</th>
            <th>Invoice #</th>
            <th>Amount</th>
            <th>Paid Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($payments)): ?>
            <?php foreach ($payments as $payment): ?>
              <tr>
                <td>#<?= $payment['id'] ?></td>
                <td><?= esc($payment['patient_name'] ?? 'N/A') ?></td>
                <td><?= esc($payment['invoice_no'] ?? 'N/A') ?></td>
                <td style="font-weight:600;color:#10b981">$<?= number_format($payment['amount'], 2) ?></td>
                <td><?= date('M j, Y', strtotime($payment['paid_at'])) ?></td>
                <td>
                  <a href="<?= site_url('accountant/payments/' . $payment['id']) ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                    <i class="fas fa-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align:center;padding:2rem;color:#6b7280">
                No payments found
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    
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
  </div>
</section>

</main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>
