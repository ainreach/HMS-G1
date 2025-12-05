<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">All Appointments (<?= number_format($total) ?>)</h2>
      </div>
      <div class="panel-body">
        <?php if (!empty($appointments)): ?>
          <table class="table">
            <thead>
              <tr>
                <th>Appointment #</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date & Time</th>
                <th>Type</th>
                <th>Status</th>
                <th>Duration</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($appointments as $appointment): ?>
                <tr>
                  <td><?= esc($appointment['appointment_number'] ?? 'N/A') ?></td>
                  <td>
                    <?php 
                    if (isset($appointment['patient_first_name']) && isset($appointment['patient_last_name'])) {
                        echo esc($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']);
                    } else {
                        echo 'Patient ID: ' . esc($appointment['patient_id'] ?? 'Unknown');
                    }
                    ?>
                  </td>
                  <?php
                    $patientLabel = isset($appointment['patient_first_name'], $appointment['patient_last_name'])
                      ? trim($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name'])
                      : 'Patient ID: ' . (string)($appointment['patient_id'] ?? 'Unknown');

                    $doctorLabel = isset($appointment['doctor_name'])
                      ? 'Dr. ' . $appointment['doctor_name']
                      : 'Doctor ID: ' . (string)($appointment['doctor_id'] ?? 'Unknown');

                    $dateLabel = date('M j, Y', strtotime($appointment['appointment_date']));
                    $timeLabel = date('g:i A', strtotime($appointment['appointment_time']));
                    $typeLabel = ucfirst(str_replace('_', ' ', (string)($appointment['type'] ?? 'consultation')));
                  ?>
                  <td><?= esc($patientLabel) ?></td>
                  <td><?= esc($doctorLabel) ?></td>
                  <td>
                    <?= esc($dateLabel) ?><br>
                    <small style="color:#6b7280;"><?= esc($timeLabel) ?></small>
                  </td>
                  <td><?= esc($typeLabel) ?></td>
                  <?php
                    $rawStatus = strtolower(trim($appointment['status'] ?? 'scheduled'));
                    if ($rawStatus === '') { $rawStatus = 'scheduled'; }
                    $statusLabel = $rawStatus === 'checked-in' ? 'Checked-in'
                                 : ($rawStatus === 'cancelled' ? 'Cancelled'
                                 : 'Scheduled');
                    $statusClass = $rawStatus === 'checked-in' ? 'success'
                                 : ($rawStatus === 'cancelled' ? 'danger'
                                 : 'info');
                  ?>
                  <td style="padding:8px 10px;white-space:nowrap;">
                    <span class="badge badge-<?= $statusClass ?>" style="font-size:0.75rem;">
                      <?= esc($statusLabel) ?>
                    </span>
                  </td>
                  <td style="white-space:nowrap;"><?= (int)($appointment['duration'] ?? 0) ?> min</td>
                  <td>
                    <div style="display:flex;gap:4px;">
                      <a href="<?= site_url('admin/appointments/edit/' . $appointment['id']) ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                      <form method="post" action="<?= site_url('admin/appointments/delete/' . $appointment['id']) ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger" style="padding:0.25rem 0.5rem;font-size:0.75rem;background:#dc2626;color:white;border:none;cursor:pointer;">
                          <i class="fas fa-trash"></i> Delete
                        </button>
                      </form>
                    </div>
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
            <i class="fas fa-calendar-check" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3>No appointments found</h3>
            <p>There are no appointments in the system yet.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
<?= $this->endSection() ?>
