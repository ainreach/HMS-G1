<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Edit Patient</h2>
  </div>
  <div class="panel-body">
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-error" style="background:#fef2f2;color:#dc2626;padding:12px;border-radius:8px;margin-bottom:16px;border:1px solid #fecaca;">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= site_url('admin/patients/update/' . $patient['id']) ?>" style="display:grid;gap:12px;">
      <?= csrf_field() ?>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <label for="first_name" style="display:block;margin-bottom:4px;font-weight:600;">First Name <span style="color:#dc2626">*</span></label>
          <input type="text" id="first_name" name="first_name" value="<?= old('first_name', $patient['first_name'] ?? '') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>
        <div>
          <label for="last_name" style="display:block;margin-bottom:4px;font-weight:600;">Last Name <span style="color:#dc2626">*</span></label>
          <input type="text" id="last_name" name="last_name" value="<?= old('last_name', $patient['last_name'] ?? '') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>
      </div>

      <div>
        <label for="middle_name" style="display:block;margin-bottom:4px;font-weight:600;">Middle Name</label>
        <input type="text" id="middle_name" name="middle_name" value="<?= old('middle_name', $patient['middle_name'] ?? '') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <label for="date_of_birth" style="display:block;margin-bottom:4px;font-weight:600;">Date of Birth <span style="color:#dc2626">*</span></label>
          <input type="date" id="date_of_birth" name="date_of_birth" value="<?= old('date_of_birth', $patient['date_of_birth'] ?? '') ?>" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>
        <div>
          <label for="gender" style="display:block;margin-bottom:4px;font-weight:600;">Gender <span style="color:#dc2626">*</span></label>
          <select id="gender" name="gender" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
            <option value="">Select Gender</option>
            <option value="male" <?= old('gender', $patient['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= old('gender', $patient['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
            <option value="other" <?= old('gender', $patient['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <label for="blood_type" style="display:block;margin-bottom:4px;font-weight:600;">Blood Type</label>
          <select id="blood_type" name="blood_type" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
            <?php $bt = old('blood_type', $patient['blood_type'] ?? ''); ?>
            <option value="">Select Blood Type</option>
            <option value="unknown" <?= $bt === 'unknown' ? 'selected' : '' ?>>Unknown / Not sure</option>
            <?php foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $type): ?>
              <option value="<?= $type ?>" <?= $bt === $type ? 'selected' : '' ?>><?= $type ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="phone" style="display:block;margin-bottom:4px;font-weight:600;">Phone Number</label>
          <input type="tel" id="phone" name="phone" value="<?= old('phone', $patient['phone'] ?? '') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>
      </div>

      <div>
        <label for="email" style="display:block;margin-bottom:4px;font-weight:600;">Email Address</label>
        <input type="email" id="email" name="email" value="<?= old('email', $patient['email'] ?? '') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
      </div>

      <div>
        <label for="address" style="display:block;margin-bottom:4px;font-weight:600;">Address</label>
        <textarea id="address" name="address" rows="3" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('address', $patient['address'] ?? '') ?></textarea>
      </div>

      <div>
        <label for="city" style="display:block;margin-bottom:4px;font-weight:600;">City</label>
        <input type="text" id="city" name="city" value="<?= old('city', $patient['city'] ?? '') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
      </div>

      <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Emergency Contact</h3>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <label for="emergency_contact_name" style="display:block;margin-bottom:4px;font-weight:600;">Emergency Contact Name</label>
          <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?= old('emergency_contact_name', $patient['emergency_contact_name'] ?? '') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>
        <div>
          <label for="emergency_contact_phone" style="display:block;margin-bottom:4px;font-weight:600;">Emergency Contact Phone</label>
          <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="<?= old('emergency_contact_phone', $patient['emergency_contact_phone'] ?? '') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>
      </div>

      <div>
        <label for="emergency_contact_relation" style="display:block;margin-bottom:4px;font-weight:600;">Relationship</label>
        <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" value="<?= old('emergency_contact_relation', $patient['emergency_contact_relation'] ?? '') ?>" placeholder="e.g., Spouse, Parent, Sibling" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
      </div>

      <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Insurance Information</h3>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <label for="insurance_provider" style="display:block;margin-bottom:4px;font-weight:600;">Insurance Provider</label>
          <input type="text" id="insurance_provider" name="insurance_provider" value="<?= old('insurance_provider', $patient['insurance_provider'] ?? '') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>
        <div>
          <label for="insurance_number" style="display:block;margin-bottom:4px;font-weight:600;">Insurance Number</label>
          <input type="text" id="insurance_number" name="insurance_number" value="<?= old('insurance_number', $patient['insurance_number'] ?? '') ?>" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;">
        </div>
      </div>

      <h3 style="margin: 1.5rem 0 0.75rem 0; color: #374151; font-size: 1rem;">Medical Information</h3>

      <div>
        <label for="allergies" style="display:block;margin-bottom:4px;font-weight:600;">Allergies</label>
        <textarea id="allergies" name="allergies" rows="3" placeholder="List any known allergies" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('allergies', $patient['allergies'] ?? '') ?></textarea>
      </div>

      <div>
        <label for="medical_history" style="display:block;margin-bottom:4px;font-weight:600;">Medical History</label>
        <textarea id="medical_history" name="medical_history" rows="3" placeholder="Previous medical conditions, surgeries, etc." style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;"><?= old('medical_history', $patient['medical_history'] ?? '') ?></textarea>
      </div>

      <div style="display:flex;gap:8px;margin-top:16px;justify-content:flex-end;">
        <a href="<?= site_url('admin/patients') ?>" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn">
          <i class="fas fa-save"></i> Update Patient
        </button>
      </div>
    </form>
  </div>
</section>
<?= $this->endSection() ?>
