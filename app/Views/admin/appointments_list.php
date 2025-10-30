<?= $this->include('admin/sidebar') ?>
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
              </tr>
            </thead>
            <tbody>
              <?php foreach ($appointments as $appointment): ?>
                <tr>
                  <td><?= esc($appointment['appointment_number']) ?></td>
                  <td><?= esc($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']) ?></td>
                  <td>Dr. <?= esc($appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']) ?></td>
                  <td>
                    <?= date('M j, Y', strtotime($appointment['appointment_date'])) ?><br>
                    <small style="color: #6b7280;"><?= date('g:i A', strtotime($appointment['appointment_time'])) ?></small>
                  </td>
                  <td><?= ucfirst(str_replace('_', ' ', $appointment['type'])) ?></td>
                  <td>
                    <span class="badge badge-<?= $appointment['status'] ?>">
                      <?= ucfirst(str_replace('_', ' ', $appointment['status'])) ?>
                    </span>
                  </td>
                  <td><?= $appointment['duration'] ?> min</td>
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
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>
