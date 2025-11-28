<?= $this->include('admin/sidebar') ?>
    
    <section class="panel">
      <div class="panel-head">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
          <h2 style="margin:0;font-size:1.1rem">Staff Schedules</h2>
          <span style="font-size:0.875rem;color:#6b7280;">Define weekly duty schedules for doctors and nurses.</span>
        </div>
      </div>
      <div class="panel-body">
        <form method="post" action="<?= site_url('admin/staff-schedules') ?>" style="display:grid;gap:12px;margin-bottom:1.5rem;max-width:800px;">
          <?= csrf_field() ?>
          <div style="display:grid;grid-template-columns:1fr;gap:12px;margin-bottom:1rem;">
            <label>Staff Member
              <select name="user_id" id="staff-select" required style="width:100%;" onchange="updateRestDay(this.value)">
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
              <input type="hidden" name="day_of_week" value="all">
            </label>
          </div>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <label>Start Date
              <input type="date" name="start_date" id="start-date" required 
                     value="<?= date('Y-m-d') ?>" 
                     min="<?= date('Y-m-d') ?>" 
                     max="<?= date('Y-m-d', strtotime('+1 year')) ?>" 
                     onchange="updateEndDate(this.value)" />
            </label>
            <label>Schedule Duration
              <div style="display: flex; align-items: center; height: 38px; background-color: #fff; border: 1px solid #d1d5db; border-radius: 4px; padding: 0 12px;">
                <input type="text" value="1" id="duration" style="width: 30px; text-align: center; border: none; background: transparent; padding: 0; font-size: 0.875rem;" disabled />
                <span style="color: #6b7280; font-size: 0.875rem;">year</span>
              </div>
              <input type="hidden" name="end_date" id="end-date" value="<?= date('Y-m-d', strtotime('+1 year')) ?>" />
            </label>
          </div>
          
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
            <label>Start Time
              <input type="time" name="start_time" required value="09:00" />
            </label>
            <label>End Time
              <input type="time" name="end_time" required value="17:00" />
            </label>
            <label>Rest Day
              <select name="rest_day" id="rest-day" style="width:100%;">
                <option value="">-- Select rest day --</option>
                <option value="monday">Monday</option>
                <option value="tuesday">Tuesday</option>
                <option value="wednesday">Wednesday</option>
                <option value="thursday">Thursday</option>
                <option value="friday">Friday</option>
                <option value="saturday">Saturday</option>
                <option value="sunday">Sunday</option>
              </select>
            </label>
          </div>
          
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" name="is_recurring" value="1" checked />
              <span>Recurring schedule</span>
            </label>
            <div>
              <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Save Schedule
              </button>
            </div>
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

    <script>
    // Rest days data from PHP
    const restDays = <?= json_encode($restDays ?? []) ?>;
    
    // Function to update rest day selection when staff member changes
    function updateRestDay(userId) {
        const restDaySelect = document.getElementById('rest-day');
        if (!restDaySelect) return;
        
        // Reset to default
        restDaySelect.value = '';
        
        // If we have a rest day for this user, select it
        if (userId && restDays[userId]) {
            restDaySelect.value = restDays[userId];
        }
    }
    
    // Function to update end date when start date changes
    function updateEndDate(startDateValue) {
        if (!startDateValue) return;
        
        const startDate = new Date(startDateValue);
        const endDate = new Date(startDate);
        
        // Set end date to exactly one year from start date
        endDate.setFullYear(startDate.getFullYear() + 1);
        
        // Format as YYYY-MM-DD
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };
        
        // Update the hidden end date input
        document.getElementById('end-date').value = formatDate(endDate);
    }
    
    // Set up date validation
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start-date');
        
        if (startDateInput) {
            // Format today's date and max date (1 year from now)
            const today = new Date();
            const maxDate = new Date();
            maxDate.setFullYear(today.getFullYear() + 1);
            
            // Format as YYYY-MM-DD
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            // Set max date for start date
            startDateInput.max = formatDate(maxDate);
            
            // Initialize end date when page loads
            updateEndDate(startDateInput.value);
            
            // Update end date when start date changes
            startDateInput.addEventListener('change', function() {
                updateEndDate(this.value);
            });
        }
        
        // Initialize rest day for any pre-selected staff member
        const staffSelect = document.getElementById('staff-select');
        if (staffSelect && staffSelect.value) {
            updateRestDay(staffSelect.value);
        }
    });
    </script>

</div>
