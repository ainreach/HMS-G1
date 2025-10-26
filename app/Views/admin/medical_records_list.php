<?= $this->include('admin/sidebar') ?>

<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">Medical Records</h2>
  </div>
  <div class="panel-body">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <div>
        <h3 style="margin:0;font-size:1rem;color:#6b7280">Total Records: <?= number_format($total) ?></h3>
      </div>
      <div>
        <a href="<?= site_url('doctor/records/new') ?>" class="btn">
          <i class="fas fa-plus"></i> New Medical Record
        </a>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>Record #</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Visit Date</th>
            <th>Chief Complaint</th>
            <th>Diagnosis</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($records)): ?>
            <?php foreach ($records as $record): ?>
              <tr>
                <td><?= esc($record['record_number'] ?? 'N/A') ?></td>
                <td><?= esc($record['patient_first_name'] . ' ' . $record['patient_last_name']) ?></td>
                <td>Dr. <?= esc($record['doctor_first_name'] . ' ' . $record['doctor_last_name']) ?></td>
                <td><?= date('M j, Y', strtotime($record['visit_date'])) ?></td>
                <td><?= esc(substr($record['chief_complaint'] ?? '', 0, 50)) ?><?= strlen($record['chief_complaint'] ?? '') > 50 ? '...' : '' ?></td>
                <td><?= esc(substr($record['diagnosis'] ?? '', 0, 50)) ?><?= strlen($record['diagnosis'] ?? '') > 50 ? '...' : '' ?></td>
                <td>
                  <a href="<?= site_url('doctor/records/' . $record['id']) ?>" class="btn btn-secondary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                    <i class="fas fa-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" style="text-align:center;padding:2rem;color:#6b7280">
                No medical records found
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
