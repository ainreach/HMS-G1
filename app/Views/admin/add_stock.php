<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Add Stock to Inventory</h2>
  </div>
  <div class="panel-body">
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #6ee7b7;">
        <?= esc(session()->getFlashdata('success')) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('admin/add-stock') ?>" style="max-width:800px;">
      <?= csrf_field() ?>
      
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Medicine *</label>
          <?php if (!empty($medicines)): ?>
            <select name="medicine_id" required style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
              <option value="">-- Select Medicine --</option>
              <?php foreach ($medicines as $medicine): ?>
                <option value="<?= $medicine['id'] ?>" <?= old('medicine_id') == $medicine['id'] ? 'selected' : '' ?>>
                  <?= esc($medicine['name'] ?? '') ?> <?= !empty($medicine['strength']) ? '(' . esc($medicine['strength']) . ')' : '' ?>
                </option>
              <?php endforeach; ?>
            </select>
          <?php else: ?>
            <input type="number" name="medicine_id" required value="<?= old('medicine_id') ?>" 
                   style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                   placeholder="Medicine ID">
            <small style="display:block;margin-top:4px;color:#6b7280;">No medicines found. Enter medicine ID manually.</small>
          <?php endif; ?>
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Batch Number *</label>
          <input type="text" name="batch_number" required value="<?= old('batch_number') ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="e.g., BATCH-2024-001">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Expiry Date *</label>
          <input type="date" name="expiry_date" required value="<?= old('expiry_date') ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;">
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Quantity in Stock *</label>
          <input type="number" name="quantity_in_stock" min="1" required value="<?= old('quantity_in_stock') ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="Enter quantity">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Minimum Stock Level</label>
          <input type="number" name="minimum_stock_level" min="0" value="<?= old('minimum_stock_level', 0) ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="0">
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Maximum Stock Level</label>
          <input type="number" name="maximum_stock_level" min="0" value="<?= old('maximum_stock_level', 0) ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="0">
        </div>

        <div>
          <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Reorder Level</label>
          <input type="number" name="reorder_level" min="0" value="<?= old('reorder_level', 0) ?>" 
                 style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
                 placeholder="0">
        </div>
      </div>

      <div style="margin-bottom:16px;">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Location</label>
        <input type="text" name="location" value="<?= old('location') ?>" 
               style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;"
               placeholder="e.g., Main Store, Warehouse A">
      </div>

      <div style="margin-bottom:20px;">
        <label style="display:block;margin-bottom:6px;font-weight:600;color:#374151">Notes</label>
        <textarea name="notes" rows="3" style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:1rem;resize:vertical;"
                  placeholder="Additional notes about this stock entry"><?= old('notes') ?></textarea>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:16px;border-top:1px solid #e5e7eb;">
        <a href="<?= site_url('admin/inventory') ?>" class="btn" style="padding:10px 20px;background:white;border:1px solid #d1d5db;border-radius:6px;text-decoration:none;color:#374151;font-weight:500;">
          Cancel
        </a>
        <button type="submit" class="btn btn-primary" style="padding:10px 20px;background:var(--primary);color:white;border:none;border-radius:6px;font-weight:500;cursor:pointer;">
          <i class="fas fa-plus"></i> Add Stock
        </button>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<?= $this->endSection() ?>

