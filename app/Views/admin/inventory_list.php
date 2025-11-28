<?= $this->include('admin/sidebar') ?>

<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Inventory</h2>
  </div>
  <div class="panel-body">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <div>
        <h3 style="margin:0;font-size:1rem;color:#6b7280">Total Items: <?= number_format($total) ?></h3>
      </div>
      <div>
        <a href="<?= site_url('pharmacy/add-stock') ?>" class="btn">
          <i class="fas fa-plus"></i> Add Stock
        </a>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Medicine</th>
            <th>Batch #</th>
            <th>Expiry Date</th>
            <th>Quantity in Stock</th>
            <th>Min Stock Level</th>
            <th>Max Stock Level</th>
            <th>Reorder Level</th>
            <th>Location</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($inventory)): ?>
            <?php foreach ($inventory as $item): ?>
              <tr>
                <td style="font-weight:600"><?= esc($item['medicine_name'] ?? 'N/A') ?></td>
                <td><?= esc($item['batch_number'] ?? 'N/A') ?></td>
                <td><?= $item['expiry_date'] ? date('M j, Y', strtotime($item['expiry_date'])) : 'N/A' ?></td>
                <td style="font-weight:600;color:<?= $item['quantity_in_stock'] <= $item['minimum_stock_level'] ? '#ef4444' : '#10b981' ?>">
                  <?= number_format($item['quantity_in_stock'] ?? 0) ?>
                </td>
                <td><?= number_format($item['minimum_stock_level'] ?? 0) ?></td>
                <td><?= number_format($item['maximum_stock_level'] ?? 0) ?></td>
                <td><?= number_format($item['reorder_level'] ?? 0) ?></td>
                <td><?= esc($item['location'] ?? 'N/A') ?></td>
                <td>
                  <?php
                    $quantity = $item['quantity_in_stock'] ?? 0;
                    $minLevel = $item['minimum_stock_level'] ?? 0;
                    $reorderLevel = $item['reorder_level'] ?? 0;
                    
                    if ($quantity <= $minLevel) {
                      $status = 'low_stock';
                      $badgeClass = 'badge-danger';
                    } elseif ($quantity <= $reorderLevel) {
                      $status = 'reorder';
                      $badgeClass = 'badge-warning';
                    } else {
                      $status = 'in_stock';
                      $badgeClass = 'badge-success';
                    }
                  ?>
                  <span class="badge <?= $badgeClass ?>"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                </td>
                <td>
                  <a href="<?= site_url('pharmacy/inventory/' . $item['id']) ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                    <i class="fas fa-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="10" style="text-align:center;padding:2rem;color:#6b7280">
                No inventory items found
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
