<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <section class="panel">
      <div class="panel-head">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
          <h2 style="margin:0;font-size:1.1rem">Staff Schedules</h2>
          <span style="font-size:0.875rem;color:#6b7280;">Define weekly duty schedules for doctors and nurses.</span>
        </div>
      </div>
      <div class="panel-body">
        <form method="post" action="<?= site_url('admin/staff-schedules') ?>" style="display:grid;gap:12px;margin-bottom:1.5rem;max-width:640px;">
          <?= csrf_field() ?>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <label>Staff Member
              <select name="user_id" required style="width:100%;">
                <option value="">-- Select doctor or nurse --</option>
                <?php if (!empty($doctors)): ?>
                  <optgroup label="Doctors">
                    <?php foreach ($doctors as $d): ?>
                      <option value="<?= esc($d['id']) ?>">
                        Dr. <?= esc(($d['last_name'] ?? '') . ', ' . ($d['first_name'] ?? '')) ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endif; ?>
                <?php if (!empty($nurses)): ?>
                  <optgroup label="Nurses">
                    <?php foreach ($nurses as $n): ?>
                      <option value="<?= esc($n['id']) ?>">
                        Nurse <?= esc(($n['last_name'] ?? '') . ', ' . ($n['first_name'] ?? '')) ?>
                      </option>
                    <?php endforeach; ?>
                  </optgroup>
                <?php endif; ?>
              </select>
            </label>
            <label>Day(s) of Week
              <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(100px,1fr));gap:8px;margin-top:4px;">
                <label style="display:flex;align-items:center;gap:4px;font-weight:normal;">
                  <input type="checkbox" name="days_of_week[]" value="monday" style="margin:0;">
                  Monday
                </label>
                <label style="display:flex;align-items:center;gap:4px;font-weight:normal;">
                  <input type="checkbox" name="days_of_week[]" value="tuesday" style="margin:0;">
                  Tuesday
                </label>
                <label style="display:flex;align-items:center;gap:4px;font-weight:normal;">
                  <input type="checkbox" name="days_of_week[]" value="wednesday" style="margin:0;">
                  Wednesday
                </label>
                <label style="display:flex;align-items:center;gap:4px;font-weight:normal;">
                  <input type="checkbox" name="days_of_week[]" value="thursday" style="margin:0;">
                  Thursday
                </label>
                <label style="display:flex;align-items:center;gap:4px;font-weight:normal;">
                  <input type="checkbox" name="days_of_week[]" value="friday" style="margin:0;">
                  Friday
                </label>
                <label style="display:flex;align-items:center;gap:4px;font-weight:normal;">
                  <input type="checkbox" name="days_of_week[]" value="saturday" style="margin:0;">
                  Saturday
                </label>
                <label style="display:flex;align-items:center;gap:4px;font-weight:normal;">
                  <input type="checkbox" name="days_of_week[]" value="sunday" style="margin:0;">
                  Sunday
                </label>
              </div>
            </label>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <label>Start Time
              <input type="time" name="start_time" required />
            </label>
            <label>End Time
              <input type="time" name="end_time" required />
            </label>
          </div>
          <div>
            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> Add Schedule
            </button>
          </div>
        </form>

        <?php
          $dayNames = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
          ];
        ?>

        <?php if (!empty($schedules)): ?>
          <table class="table">
            <thead>
              <tr>
                <th>Staff</th>
                <th>Role</th>
                <th>Day</th>
                <th>Start</th>
                <th>End</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($schedules as $row): ?>
                <tr>
                  <td><?= esc(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '')) ?></td>
                  <td><?= esc(ucfirst($row['role'] ?? '')) ?></td>
                  <td><?= esc($dayNames[strtolower($row['day_of_week'] ?? '')] ?? ucfirst($row['day_of_week'] ?? '')) ?></td>
                  <td><?= esc(date('g:i A', strtotime($row['start_time'] ?? '00:00:00'))) ?></td>
                  <td><?= esc(date('g:i A', strtotime($row['end_time'] ?? '00:00:00'))) ?></td>
                  <td>
                    <form action="<?= site_url('admin/staff-schedules/delete/' . ($row['id'] ?? 0)) ?>" method="post" style="display:inline" onsubmit="return confirm('Delete this schedule?');">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-danger" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                        <i class="fas fa-trash"></i> Delete
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div style="text-align:center;padding:2rem;color:#6b7280;">
            <i class="fas fa-user-clock" style="font-size:3rem;margin-bottom:1rem;opacity:0.5;"></i>
            <h3>No schedules defined</h3>
            <p>Use the form above to add duty schedules for doctors and nurses.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<?= $this->endSection() ?>
