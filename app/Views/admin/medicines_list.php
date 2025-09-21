<?= $this->include('admin/sidebar') ?>

<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Medicines</h2>
  </div>
  <div class="panel-body">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <div>
        <h3 style="margin:0;font-size:1rem;color:#6b7280">Total Medicines: <?= number_format($total) ?></h3>
      </div>
      <div>
        <a href="<?= site_url('pharmacy/medicines/new') ?>" class="btn">
          <i class="fas fa-plus"></i> New Medicine
        </a>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Generic Name</th>
            <th>Brand Name</th>
            <th>Category</th>
            <th>Dosage Form</th>
            <th>Strength</th>
            <th>Purchase Price</th>
            <th>Selling Price</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($medicines)): ?>
            <?php foreach ($medicines as $medicine): ?>
              <tr>
                <td><?= esc($medicine['medicine_code'] ?? 'N/A') ?></td>
                <td style="font-weight:600"><?= esc($medicine['name'] ?? 'N/A') ?></td>
                <td><?= esc($medicine['generic_name'] ?? 'N/A') ?></td>
                <td><?= esc($medicine['brand_name'] ?? 'N/A') ?></td>
                <td><?= esc($medicine['category'] ?? 'N/A') ?></td>
                <td><?= esc($medicine['dosage_form'] ?? 'N/A') ?></td>
                <td><?= esc($medicine['strength'] ?? 'N/A') ?></td>
                <td style="color:#6b7280">$<?= number_format($medicine['purchase_price'] ?? 0, 2) ?></td>
                <td style="font-weight:600;color:#10b981">$<?= number_format($medicine['selling_price'] ?? 0, 2) ?></td>
                <td>
                  <?php
                    $status = $medicine['is_active'] ? 'active' : 'inactive';
                    $badgeClass = $status === 'active' ? 'badge-success' : 'badge-other';
                  ?>
                  <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                </td>
                <td>
                  <a href="<?= site_url('pharmacy/medicines/' . $medicine['id']) ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                    <i class="fas fa-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="11" style="text-align:center;padding:2rem;color:#6b7280">
                No medicines found
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
