<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <section class="panel">
      <div class="panel-head">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <h2 style="margin:0;font-size:1.1rem">All Patients (<?= number_format($total) ?>)</h2>
          <button type="button" class="btn btn-success" onclick="showPatientModal()">
            <i class="fas fa-plus"></i> Add New Patient
          </button>
        </div>
      </div>
      <div class="panel-body">
        <?php if (!empty($patients)): ?>
          <table class="table">
            <thead>
              <tr>
                <th>Patient ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($patients as $patient): ?>
                <tr>
                  <td><?= esc($patient['patient_id']) ?></td>
                  <td><a href="<?= site_url('admin/patients/view/' . $patient['id']) ?>"><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></a></td>
                  <td><?= esc($patient['phone'] ?? 'N/A') ?></td>
                  <td><?= esc($patient['email'] ?? 'N/A') ?></td>
                  <td><?= date('M j, Y', strtotime($patient['created_at'])) ?></td>
                  <td>
                    <a href="<?= site_url('admin/patients/view/' . $patient['id']) ?>" class="btn btn-info" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                      <i class="fas fa-eye"></i> View
                    </a>
                    <a href="<?= site_url('admin/patients/edit/' . $patient['id']) ?>" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="<?= site_url('admin/patients/delete/' . $patient['id']) ?>" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="return confirm('Are you sure you want to delete this patient?')">
                      <i class="fas fa-trash"></i> Delete
                    </a>
                  </td>
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
            <i class="fas fa-user-injured" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3>No patients found</h3>
            <p>Start by adding your first patient to the system.</p>
            <button type="button" class="btn btn-success" style="margin-top: 1rem;" onclick="showPatientModal()">
              <i class="fas fa-plus"></i> Add First Patient
            </button>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Add Patient Modal -->
    <div id="addPatientModal" style="position:fixed;inset:0;display:none;align-items:center;justify-content:center;background:rgba(15,23,42,0.35);z-index:50;">
      <div style="width:95%;max-width:900px;background:white;border-radius:8px;box-shadow:0 20px 40px rgba(15,23,42,0.35);max-height:90vh;overflow-y:auto;">
        <div style="background:#2563eb;color:white;display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #1d4ed8;">
          <h3 style="margin:0;font-size:1.1rem;">Add New Patient</h3>
          <button type="button" onclick="closePatientModal()" style="background:none;border:none;font-size:20px;color:white;cursor:pointer;">&times;</button>
        </div>
        <div style="padding:16px 20px;">
          <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;">
              <?= esc(session()->getFlashdata('error')) ?>
            </div>
          <?php endif; ?>

          <form id="addPatientForm" method="POST" action="<?= site_url('admin/patients') ?>" style="display:grid;gap:12px;">
            <?= csrf_field() ?>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div>
                <label for="first_name" style="display:block;margin-bottom:4px;font-weight:600;">First Name <span style="color:#dc2626">*</span></label>
                <input type="text" id="first_name" name="first_name" value="<?= old('first_name') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              </div>
              <div>
                <label for="last_name" style="display:block;margin-bottom:4px;font-weight:600;">Last Name <span style="color:#dc2626">*</span></label>
                <input type="text" id="last_name" name="last_name" value="<?= old('last_name') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              </div>
            </div>

            <div>
              <label for="middle_name" style="display:block;margin-bottom:4px;font-weight:600;">Middle Name</label>
              <input type="text" id="middle_name" name="middle_name" value="<?= old('middle_name') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div>
                <label for="date_of_birth" style="display:block;margin-bottom:4px;font-weight:600;">Date of Birth <span style="color:#dc2626">*</span></label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              </div>
              <div>
                <label for="gender" style="display:block;margin-bottom:4px;font-weight:600;">Gender <span style="color:#dc2626">*</span></label>
                <select id="gender" name="gender" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
                  <option value="">Select Gender</option>
                  <option value="male" <?= old('gender') == 'male' ? 'selected' : '' ?>>Male</option>
                  <option value="female" <?= old('gender') == 'female' ? 'selected' : '' ?>>Female</option>
                  <option value="other" <?= old('gender') == 'other' ? 'selected' : '' ?>>Other</option>
                </select>
              </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div>
                <label for="blood_type" style="display:block;margin-bottom:4px;font-weight:600;">Blood Type</label>
                <select id="blood_type" name="blood_type" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
                  <option value="">Select Blood Type</option>
                  <option value="A+" <?= old('blood_type') == 'A+' ? 'selected' : '' ?>>A+</option>
                  <option value="A-" <?= old('blood_type') == 'A-' ? 'selected' : '' ?>>A-</option>
                  <option value="B+" <?= old('blood_type') == 'B+' ? 'selected' : '' ?>>B+</option>
                  <option value="B-" <?= old('blood_type') == 'B-' ? 'selected' : '' ?>>B-</option>
                  <option value="AB+" <?= old('blood_type') == 'AB+' ? 'selected' : '' ?>>AB+</option>
                  <option value="AB-" <?= old('blood_type') == 'AB-' ? 'selected' : '' ?>>AB-</option>
                  <option value="O+" <?= old('blood_type') == 'O+' ? 'selected' : '' ?>>O+</option>
                  <option value="O-" <?= old('blood_type') == 'O-' ? 'selected' : '' ?>>O-</option>
                </select>
              </div>
              <div>
                <label for="phone" style="display:block;margin-bottom:4px;font-weight:600;">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?= old('phone') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              </div>
            </div>

            <div>
              <label for="email" style="display:block;margin-bottom:4px;font-weight:600;">Email Address</label>
              <input type="email" id="email" name="email" value="<?= old('email') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
            </div>

            <div>
              <label for="address" style="display:block;margin-bottom:4px;font-weight:600;">Address</label>
              <textarea id="address" name="address" rows="3" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('address') ?></textarea>
            </div>

            <div>
              <label for="city" style="display:block;margin-bottom:4px;font-weight:600;">City</label>
              <input type="text" id="city" name="city" value="<?= old('city') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
            </div>

            <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Emergency Contact</h3>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div>
                <label for="emergency_contact_name" style="display:block;margin-bottom:4px;font-weight:600;">Emergency Contact Name</label>
                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?= old('emergency_contact_name') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              </div>
              <div>
                <label for="emergency_contact_phone" style="display:block;margin-bottom:4px;font-weight:600;">Emergency Contact Phone</label>
                <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="<?= old('emergency_contact_phone') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              </div>
            </div>

            <div>
              <label for="emergency_contact_relation" style="display:block;margin-bottom:4px;font-weight:600;">Relationship</label>
              <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" value="<?= old('emergency_contact_relation') ?>" placeholder="e.g., Spouse, Parent, Sibling" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
            </div>

            <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Insurance Information</h3>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div>
                <label for="insurance_provider" style="display:block;margin-bottom:4px;font-weight:600;">Insurance Provider</label>
                <input type="text" id="insurance_provider" name="insurance_provider" value="<?= old('insurance_provider') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              </div>
              <div>
                <label for="insurance_number" style="display:block;margin-bottom:4px;font-weight:600;">Insurance Number</label>
                <input type="text" id="insurance_number" name="insurance_number" value="<?= old('insurance_number') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
              </div>
            </div>

            <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Medical Information</h3>

            <div>
              <label for="allergies" style="display:block;margin-bottom:4px;font-weight:600;">Allergies</label>
              <textarea id="allergies" name="allergies" rows="3" placeholder="List any known allergies" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('allergies') ?></textarea>
            </div>

            <div>
              <label for="medical_history" style="display:block;margin-bottom:4px;font-weight:600;">Medical History</label>
              <textarea id="medical_history" name="medical_history" rows="3" placeholder="Previous medical conditions, surgeries, etc." style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('medical_history') ?></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:12px;">
              <button type="button" class="btn btn-secondary" onclick="closePatientModal()">Cancel</button>
              <button type="submit" class="btn">
                <i class="fas fa-save"></i> Create Patient
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
  function showPatientModal() {
    var modal = document.getElementById('addPatientModal');
    if (modal) {
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
  }

  function closePatientModal() {
    var modal = document.getElementById('addPatientModal');
    if (modal) {
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
      var form = document.getElementById('addPatientForm');
      if (form) form.reset();
    }
  }

  // Close modal when clicking outside
  document.addEventListener('click', function(e) {
    var modal = document.getElementById('addPatientModal');
    if (modal && e.target === modal) {
      closePatientModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    var modal = document.getElementById('addPatientModal');
    if (modal && modal.style.display === 'flex' && e.key === 'Escape') {
      closePatientModal();
    }
  });


  // Keyboard navigation for table rows
  document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'A' && e.key === 'Enter') {
      e.target.click();
    }
  });

  // Submit filter form from search box only on Enter key
  (function () {
    var form = document.getElementById('filterForm');
    if (!form) return;

    var searchInput = form.querySelector('input[name="search"]');
    if (!searchInput) return;

    // Allow manual search via Enter key without auto-submitting on each character
    searchInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        form.submit();
      }
    });

    // Optional: when the search is cleared, submit once to reset filters
    var clearTimer;
    searchInput.addEventListener('input', function () {
      if (this.value.trim().length === 0) {
        clearTimeout(clearTimer);
        clearTimer = setTimeout(function () {
          form.submit();
        }, 300);
      }
    });
  })();

</script>
<?= $this->endSection() ?>
