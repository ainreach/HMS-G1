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

        <hr style="margin:2rem 0;">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
          <h3 style="margin:0;font-size:1rem;">Year Schedule Calendar</h3>
          <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
            <label style="margin:0;">
              <span style="font-size:0.875rem;margin-right:0.25rem;">Year</span>
              <select id="staffScheduleYear" style="padding:0.25rem 0.5rem;">
                <?php $currentYear = (int) date('Y'); ?>
                <?php for ($y = $currentYear - 1; $y <= $currentYear + 2; $y++): ?>
                  <option value="<?= $y ?>" <?= $y === $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
              </select>
            </label>
            <label style="margin:0;">
              <span style="font-size:0.875rem;margin-right:0.25rem;">Staff</span>
              <select id="staffScheduleUserFilter" style="padding:0.25rem 0.5rem;min-width:160px;">
                <option value="0">All staff</option>
                <?php if (!empty($schedules)): ?>
                  <?php
                    $uniqueStaff = [];
                    foreach ($schedules as $row) {
                      $id = (int) ($row['user_id'] ?? 0);
                      if (!$id || isset($uniqueStaff[$id])) {
                        continue;
                      }
                      $name = trim((string) ($row['first_name'] ?? '') . ' ' . (string) ($row['last_name'] ?? ''));
                      if ($name === '') {
                        $name = (string) ($row['username'] ?? 'Staff');
                      }
                      $roleLabel = '';
                      if (!empty($row['role'])) {
                        $roleLabel = ' (' . ucfirst((string) $row['role']) . ')';
                      }
                      $uniqueStaff[$id] = $name . $roleLabel;
                    }
                    asort($uniqueStaff);
                  ?>
                  <?php foreach ($uniqueStaff as $id => $name): ?>
                    <option value="<?= (int) $id ?>"><?= esc($name) ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </label>
          </div>
        </div>
        <div id="yearScheduleCalendar" style="border:1px solid #e5e7eb;border-radius:0.5rem;padding:0.5rem;min-height:400px;"></div>
      </div>
    </section>
  </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<script>
  (function() {
    var calendarEl = document.getElementById('yearScheduleCalendar');
    if (!calendarEl || typeof FullCalendar === 'undefined') {
      return;
    }

    var yearSelect = document.getElementById('staffScheduleYear');
    var userSelect = document.getElementById('staffScheduleUserFilter');

    function getSelectedYear() {
      var y = parseInt(yearSelect ? yearSelect.value : '', 10);
      if (!y || isNaN(y)) {
        y = new Date().getFullYear();
      }
      return y;
    }

    var initialYear = getSelectedYear();
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'multiMonthYear',
      multiMonthMaxColumns: 3,
      height: 'auto',
      firstDay: 0,
      headerToolbar: false,
      views: {
        multiMonthYear: {
          type: 'multiMonth',
          duration: { years: 1 }
        }
      },
      initialDate: new Date(initialYear + '-01-01T00:00:00'),
      validRange: function(nowDate) {
        var y = getSelectedYear();
        return {
          start: y + '-01-01',
          end: (y + 1) + '-01-01'
        };
      },
      eventSources: [
        {
          events: function(fetchInfo, successCallback, failureCallback) {
            var params = new URLSearchParams();
            var y = getSelectedYear();
            params.set('year', y.toString());
            if (userSelect && userSelect.value && userSelect.value !== '0') {
              params.set('user_id', userSelect.value);
            }

            var url = '<?= site_url('admin/staff-schedules/year-events') ?>' + '?' + params.toString();

            fetch(url, {
              headers: {
                'Accept': 'application/json'
              }
            })
              .then(function(response) {
                if (!response.ok) {
                  throw new Error('Network response was not ok');
                }
                return response.json();
              })
              .then(function(data) {
                successCallback(data || []);
              })
              .catch(function(error) {
                console.error(error);
                if (failureCallback) {
                  failureCallback(error);
                }
              });
          }
        }
      ],
      eventTimeFormat: {
        hour: 'numeric',
        minute: '2-digit',
        meridiem: 'short'
      },
      eventDisplay: 'block'
    });

    calendar.render();

    function refreshCalendar() {
      var y = getSelectedYear();
      calendar.gotoDate(new Date(y + '-01-01T00:00:00'));
      calendar.refetchEvents();
    }

    if (yearSelect) {
      yearSelect.addEventListener('change', function() {
        refreshCalendar();
      });
    }

    if (userSelect) {
      userSelect.addEventListener('change', function() {
        refreshCalendar();
      });
    }
  })();
</script>
<?= $this->endSection() ?>
