<?= $this->include('admin/sidebar') ?>

    <section class="panel">
      <div class="panel-head">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <h2 style="margin:0;font-size:1.1rem">All Patients (<?= number_format($total) ?>)</h2>
          <a href="<?= site_url('admin/patients/new') ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Add New Patient
          </a>
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
                  <td><?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
                  <td><?= esc($patient['phone'] ?? 'N/A') ?></td>
                  <td><?= esc($patient['email'] ?? 'N/A') ?></td>
                  <td><?= date('M j, Y', strtotime($patient['created_at'])) ?></td>
                  <td>
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
            <a href="<?= site_url('admin/patients/new') ?>" class="btn btn-success" style="margin-top: 1rem;">
              <i class="fas fa-plus"></i> Add First Patient
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>
