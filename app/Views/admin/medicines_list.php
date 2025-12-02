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
            <th>Code</th>
            <th>Name</th>
            <th>Generic Name</th>
            <th>Category</th>
            <th>Dosage Form</th>
            <th>Strength</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($medicines)): ?>
            <?php foreach ($medicines as $medicine): ?>
              <tr>
                <td><?= esc($medicine['medicine_code']) ?></td>
                <td><?= esc($medicine['name']) ?></td>
                <td><?= esc($medicine['generic_name'] ?? 'N/A') ?></td>
                <td><?= esc($medicine['category'] ?? 'N/A') ?></td>
                <td><?= esc($medicine['dosage_form'] ?? 'N/A') ?></td>
                <td><?= esc($medicine['strength'] ?? 'N/A') ?></td>
                <td>₱<?= number_format($medicine['selling_price'] ?? 0, 2) ?></td>
                <td>
                  <?php if ($medicine['is_active'] ?? 0): ?>
                    <span class="badge badge-success">Active</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">Inactive</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="display:flex;gap:6px">
                    <a href="<?= site_url('pharmacy/medicines/edit/' . $medicine['id']) ?>"
                       style="padding:4px 8px;background:#3b82f6;color:white;border-radius:4px;text-decoration:none;font-size:0.75rem">
                      Edit
                    </a>
                    <a href="<?= site_url('pharmacy/medicines/delete/' . $medicine['id']) ?>"
                       onclick="return confirm('Are you sure you want to delete this medicine?')"
                       style="padding:4px 8px;background:#ef4444;color:white;border-radius:4px;text-decoration:none;font-size:0.75rem">
                      Delete
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" style="text-align:center;padding:1.5rem;color:#6b7280">No medicines found</td>
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
<div id="addMedicineModal" class="modal-overlay">
    <div class="modal-card" style="width:95%;max-width:720px;">
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
                                   style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Unit <span style="color:#dc2626">*</span></label>
                            <input type="text" id="unit" name="unit" required
                                   placeholder="tablet, bottle, box..."
                                   style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Cost per Unit (₱)</label>
                            <input type="number" step="0.01" id="cost_per_unit" name="cost_per_unit"
                                   placeholder="0.00"
                                   style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Retail / Selling Price (₱) <span style="color:#dc2626">*</span></label>
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
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Status</label>
                            <select id="is_active" name="is_active"
                                    style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px;">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block;margin-bottom:4px;font-weight:600;">Medicine Code</label>
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
        document.getElementById('addMedicineModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMedicineModal() {
        document.getElementById('addMedicineModal').classList.remove('show');
        document.body.style.overflow = 'auto';
        document.getElementById('addMedicineForm').reset();
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