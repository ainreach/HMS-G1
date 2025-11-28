<?= $this->include('admin/sidebar') ?>

<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Lab Tests</h2>
  </div>
  <div class="panel-body">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <div>
        <h3 style="margin:0;font-size:1rem;color:#6b7280">Total Tests: <?= number_format($total) ?></h3>
      </div>
      <div>
        <a href="<?= site_url('doctor/lab-requests/new') ?>" class="btn">
          <i class="fas fa-plus"></i> New Lab Request
        </a>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Test #</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Test Type</th>
            <th>Test Name</th>
            <th>Status</th>
            <th>Requested Date</th>
            <th>Result Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($labTests)): ?>
            <?php foreach ($labTests as $test): ?>
              <tr>
                <td><?= esc($test['test_number'] ?? 'N/A') ?></td>
                <td><?= esc($test['patient_first_name'] . ' ' . $test['patient_last_name']) ?></td>
                <td>Dr. <?= esc($test['doctor_first_name'] . ' ' . $test['doctor_last_name']) ?></td>
                <td><?= esc($test['test_type'] ?? 'N/A') ?></td>
                <td><?= esc($test['test_name'] ?? 'N/A') ?></td>
                <td>
                  <?php
                    $status = strtolower($test['status'] ?? 'requested');
                    $badgeClass = 'badge-other';
                    if (in_array($status, ['requested', 'in_progress', 'completed', 'cancelled'])) {
                      $badgeClass = 'badge-' . $status;
                    }
                  ?>
                  <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                </td>
                <td><?= date('M j, Y', strtotime($test['requested_date'])) ?></td>
                <td><?= $test['result_date'] ? date('M j, Y', strtotime($test['result_date'])) : 'Pending' ?></td>
                <td>
                  <a href="<?= site_url('lab/tests/' . $test['id']) ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                    <i class="fas fa-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" style="text-align:center;padding:2rem;color:#6b7280">
                No lab tests found
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    
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
  </div>
</section>

</main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>
