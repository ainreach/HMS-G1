<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Edit Stock Entry</h2>
  </div>
  <div class="panel-body">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('admin/inventory/' . $stock['id']) ?>" style="max-width:800px;">
      <?= csrf_field() ?>
      
      <div style="margin-bottom:16px;padding:12px;background:#f9fafb;border-radius:8px;border:1px solid #e5e7eb">
        <strong style="color:#6b7280;font-size:0.875rem">Medicine:</strong>
        <div style="margin-top:4px;font-size:1rem;font-weight:600;"><?= esc($stock['medicine_name'] ?? 'N/A') ?></div>
        <small style="color:#6b7280;">Medicine cannot be changed after stock entry is created.</small>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Batch Number *</label>
          <input type="text" name="batch_number" required value="<?= esc($stock['batch_number'] ?? '') ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="e.g., BATCH-2024-001">
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Expiry Date *</label>
          <input type="date" name="expiry_date" required value="<?= esc($stock['expiry_date'] ?? '') ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Quantity in Stock *</label>
          <input type="number" name="quantity_in_stock" min="0" required value="<?= esc($stock['quantity_in_stock'] ?? 0) ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="Enter quantity">
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Minimum Stock Level</label>
          <input type="number" name="minimum_stock_level" min="0" value="<?= esc($stock['minimum_stock_level'] ?? 0) ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="0">
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Maximum Stock Level</label>
          <input type="number" name="maximum_stock_level" min="0" value="<?= esc($stock['maximum_stock_level'] ?? 0) ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="0">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Reorder Level</label>
          <input type="number" name="reorder_level" min="0" value="<?= esc($stock['reorder_level'] ?? 0) ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="0">
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Location</label>
          <input type="text" name="location" value="<?= esc($stock['location'] ?? '') ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="e.g., Main Store, Warehouse A">
        </div>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:16px;border-top:1px solid #e5e7eb;">
        <a href="<?= site_url('admin/inventory') ?>" class="btn" style="padding:10px 20px;background:white;border:1px solid #d1d5db;border-radius:6px;text-decoration:none;color:#374151;font-weight:500;">
          Cancel
        </a>
        <button type="submit" class="btn btn-primary" style="padding:10px 20px;background:var(--primary);color:white;border:none;border-radius:6px;font-weight:500;cursor:pointer;">
          <i class="fas fa-save"></i> Update Stock
        </button>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<?= $this->endSection() ?>

