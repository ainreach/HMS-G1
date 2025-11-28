<?= $this->include('admin/sidebar') ?>
  <style>
    .log-entry { font-family: 'Courier New', monospace; font-size: 0.875rem; }
    .log-timestamp { color: #6b7280; font-size: 0.75rem; }
    .log-action { font-weight: 600; color: #3b82f6; }
    .log-details { color: #374151; }
  </style>
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">System Audit Logs (<?= number_format($total) ?> entries)</h2>
      </div>
      <div class="panel-body">
        <?php if (!empty($logs)): ?>
          <table class="table">
            <thead>
              <tr>
                <th>Timestamp</th>
                <th>Action</th>
                <th>Details</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($logs as $log): ?>
                <tr>
                  <td class="log-timestamp"><?= esc($log['timestamp']) ?></td>
                  <td class="log-action"><?= esc($log['action']) ?></td>
                  <td class="log-details"><?= esc($log['details']) ?></td>
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
            <i class="fas fa-history" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3>No audit logs found</h3>
            <p>There are no audit logs in the system yet.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>
