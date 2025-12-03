<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="panel">
  <div class="panel-head" style="display:flex;justify-content:space-between;align-items:center">
    <h2 style="margin:0;font-size:1.1rem">Medicines</h2>
    <button type="button" class="btn" id="addMedicineBtn" onclick="showMedicineModal()">
      <i class="fas fa-plus"></i> Add New Medicine
    </button>
  </div>
  <div class="panel-body">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-error">
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <div style="margin-bottom:12px">
      <form action="<?= current_url() ?>" method="get" style="max-width:320px">
        <input
          type="text"
          name="search"
          placeholder="Search medicines..."
          value="<?= esc($search ?? '') ?>"
          style="width:100%;padding:8px 10px;border:1px solid #e5e7eb;border-radius:8px"
        >
      </form>
    </div>

    <div style="overflow:auto">
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Unit</th>
            <th>Stock</th>
            <th>Cost / Unit</th>
            <th>Retail Price</th>
            <th>Expiration</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($medicines)): ?>
            <?php foreach ($medicines as $medicine): ?>
              <tr>
                <td><?= esc($medicine['name'] ?? '') ?></td>
                <td><?= esc($medicine['unit'] ?? '') ?></td>
                <td><?= esc($medicine['stock'] ?? 0) ?></td>
                <td>₱<?= number_format($medicine['price'] ?? 0, 2) ?></td>
                <td>₱<?= number_format($medicine['retail_price'] ?? 0, 2) ?></td>
                <td><?= esc($medicine['expiration_date'] ?? '') ?></td>
                <td>
                  <?php if ($medicine['is_active'] ?? 0): ?>
                    <span class="badge badge-success">Active</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">Inactive</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" style="text-align:center;padding:1.5rem;color:#6b7280">No medicines found</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($total > $perPage): ?>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
        <div style="color:#6b7280;font-size:0.875rem">
          Showing <?= (($page - 1) * $perPage) + 1 ?>
          to <?= min($page * $perPage, $total) ?> of <?= $total ?> entries
        </div>
        <div style="display:flex;gap:6px">
          <?php if ($page > 1): ?>
            <a class="btn" href="?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Prev</a>
          <?php endif; ?>
          <?php if ($page < ceil($total / $perPage)): ?>
            <a class="btn" href="?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Next</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Add Medicine Modal -->
<div id="addMedicineModal" class="modal-overlay" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(15,23,42,0.35);z-index:50;">
    <div class="modal-card" style="width:95%;max-width:720px;background:white;border-radius:8px;box-shadow:0 20px 40px rgba(15,23,42,0.35);">
        <div class="modal-header" style="background:#2563eb;color:white;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #1d4ed8;">
            <h3 style="margin:0;font-size:1.1rem;">Add New Medicine</h3>
            <button type="button" class="modal-close" onclick="closeMedicineModal()" style="background:none;border:none;font-size:20px;color:white;cursor:pointer;">&times;</button>
        </div>
        <div class="modal-body" style="padding:16px 20px;">
                <form id="addMedicineForm" action="<?= site_url('admin/medicines/add') ?>" method="post">
                    <?= csrf_field() ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Medicine Name <span style="color:#dc2626">*</span></label>
                            <input type="text" id="name" name="name" required
                                   placeholder="e.g., Paracetamol 500mg"
                                   style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Unit <span style="color:#dc2626">*</span></label>
                            <select id="unit" name="unit" required
                                    style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                                <option value="">-- Select unit --</option>
                                <option value="tablet">Tablet</option>
                                <option value="capsule">Capsule</option>
                                <option value="bottle">Bottle</option>
                                <option value="box">Box</option>
                                <option value="vial">Vial</option>
                                <option value="sachet">Sachet</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Cost per unit (₱) <span style="color:#dc2626">*</span></label>
                            <input type="number" step="0.01" id="cost_per_unit" name="cost_per_unit" required
                                   placeholder="0.00"
                                   style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Retail / Selling Price per Unit (₱) <span style="color:#dc2626">*</span></label>
                            <input type="number" step="0.01" id="selling_price" name="selling_price" required
                                   placeholder="0.00"
                                   style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Initial Stock <span style="color:#dc2626">*</span></label>
                            <input type="number" id="stock_quantity" name="stock_quantity" required
                                   min="0" value="0"
                                   style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Expiration Date</label>
                            <input type="date" id="expiry_date" name="expiry_date"
                                   style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                            <small style="display:block;margin-top:4px;color:#6b7280;font-size:0.75rem;">Optional: Leave blank if no expiration date</small>
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Status</label>
                            <select id="is_active" name="is_active"
                                    style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div style="display:none;">
                            <label>Medicine Code</label>
                            <input type="text" id="medicine_code" name="medicine_code"
                                   placeholder="Auto-generated if left blank"
                                   style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                        </div>
                    </div>
                    <div style="margin-top:12px;">
                        <label style="display:block;margin-bottom:4px;font-weight:600;">Notes</label>
                        <textarea id="description" name="description" rows="2"
                                  style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;resize:vertical;"></textarea>
                    </div>
                </form>
        </div>
        <div class="modal-footer" style="padding:12px 20px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;">
            <button type="button" class="btn" onclick="closeMedicineModal()" style="background:#6b7280;color:white;">Cancel</button>
            <button type="submit" form="addMedicineForm" class="btn btn-primary">Add Medicine</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 600);
        });
    }, 5000);

    // Simple modal functionality
    function showMedicineModal() {
        var modal = document.getElementById('addMedicineModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeMedicineModal() {
        var modal = document.getElementById('addMedicineModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('addMedicineForm').reset();
        }
    }

    // Basic modal wiring & helpers
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-generate medicine code before normal submit
        const form = document.getElementById('addMedicineForm');
        if (form) {
            form.addEventListener('submit', function () {
                const codeInput = document.getElementById('medicine_code');
                if (codeInput && !codeInput.value.trim()) {
                    codeInput.value = 'MED' + Date.now(); // alpha_numeric only
                }
            });
        }

        // Close modal when clicking outside
        document.getElementById('addMedicineModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMedicineModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMedicineModal();
            }
        });
    });
</script>
<?= $this->endSection() ?>