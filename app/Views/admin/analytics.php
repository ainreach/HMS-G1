<?= $this->include('admin/sidebar') ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .chart-container { position: relative; height: 300px; margin: 1rem 0; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card { background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-align: center; }
    .stat-value { font-size: 2rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem; }
    .stat-label { color: #6b7280; font-size: 0.875rem; }
  </style>
    <!-- Monthly Trends Chart -->
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Monthly Trends (Last 12 Months)</h2>
      </div>
      <div class="panel-body">
        <div class="chart-container">
          <canvas id="monthlyTrendsChart"></canvas>
        </div>
      </div>
    </section>

    <!-- Role Distribution -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">User Role Distribution</h2>
        </div>
        <div class="panel-body">
          <div class="chart-container">
            <canvas id="roleDistributionChart"></canvas>
          </div>
        </div>
      </section>

      <section class="panel">
        <div class="panel-head">
          <h2 style="margin:0;font-size:1.1rem">Appointment Status</h2>
        </div>
        <div class="panel-body">
          <div class="chart-container">
            <canvas id="appointmentStatusChart"></canvas>
          </div>
        </div>
      </section>
    </div>

    <!-- Lab Test Status -->
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Lab Test Status Distribution</h2>
      </div>
      <div class="panel-body">
        <div class="chart-container">
          <canvas id="labTestStatusChart"></canvas>
        </div>
      </div>
    </section>

    <!-- Low Stock Alert -->
    <?php if (!empty($lowStockMedicines)): ?>
    <section class="panel">
      <div class="panel-head">
        <h2 style="margin:0;font-size:1.1rem">Low Stock Alert</h2>
      </div>
      <div class="panel-body">
        <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 1rem; margin-bottom: 1rem;">
          <div style="display: flex; align-items: center; color: #dc2626;">
            <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
            <strong>Warning:</strong> The following medicines are running low on stock.
          </div>
        </div>
        <table class="table">
          <thead>
            <tr>
              <th>Medicine Name</th>
              <th>Current Stock</th>
              <th>Minimum Level</th>
              <th>Reorder Level</th>
              <th>Expiry Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($lowStockMedicines as $medicine): ?>
              <tr>
                <td><?= esc($medicine['medicine_name']) ?></td>
                <td style="color: #dc2626; font-weight: 600;"><?= $medicine['quantity_in_stock'] ?></td>
                <td><?= $medicine['minimum_stock_level'] ?></td>
                <td><?= $medicine['reorder_level'] ?></td>
                <td><?= date('M j, Y', strtotime($medicine['expiry_date'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
    <?php endif; ?>
  </main>
</div>

<script>
// Monthly Trends Chart
const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
const monthlyTrendsChart = new Chart(monthlyTrendsCtx, {
  type: 'line',
  data: {
    labels: [<?php foreach (array_keys($monthlyStats) as $month): ?>"<?= date('M Y', strtotime($month . '-01')) ?>",<?php endforeach; ?>],
    datasets: [{
      label: 'Users',
      data: [<?php foreach ($monthlyStats as $stats): ?><?= $stats['users'] ?>,<?php endforeach; ?>],
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59, 130, 246, 0.1)',
      tension: 0.4
    }, {
      label: 'Patients',
      data: [<?php foreach ($monthlyStats as $stats): ?><?= $stats['patients'] ?>,<?php endforeach; ?>],
      borderColor: '#10b981',
      backgroundColor: 'rgba(16, 185, 129, 0.1)',
      tension: 0.4
    }, {
      label: 'Appointments',
      data: [<?php foreach ($monthlyStats as $stats): ?><?= $stats['appointments'] ?>,<?php endforeach; ?>],
      borderColor: '#f59e0b',
      backgroundColor: 'rgba(245, 158, 11, 0.1)',
      tension: 0.4
    }, {
      label: 'Revenue ($)',
      data: [<?php foreach ($monthlyStats as $stats): ?><?= $stats['revenue'] ?>,<?php endforeach; ?>],
      borderColor: '#8b5cf6',
      backgroundColor: 'rgba(139, 92, 246, 0.1)',
      tension: 0.4,
      yAxisID: 'y1'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true
      },
      y1: {
        type: 'linear',
        display: true,
        position: 'right',
        beginAtZero: true
      }
    }
  }
});

// Role Distribution Chart
const roleDistributionCtx = document.getElementById('roleDistributionChart').getContext('2d');
const roleDistributionChart = new Chart(roleDistributionCtx, {
  type: 'doughnut',
  data: {
    labels: [<?php foreach ($roleDistribution as $role): ?>"<?= ucfirst(str_replace('_', ' ', $role['role'])) ?>",<?php endforeach; ?>],
    datasets: [{
      data: [<?php foreach ($roleDistribution as $role): ?><?= $role['count'] ?>,<?php endforeach; ?>],
      backgroundColor: [
        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#84cc16', '#f97316'
      ]
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});

// Appointment Status Chart
const appointmentStatusCtx = document.getElementById('appointmentStatusChart').getContext('2d');
const appointmentStatusChart = new Chart(appointmentStatusCtx, {
  type: 'bar',
  data: {
    labels: [<?php foreach ($appointmentStatus as $status): ?>"<?= ucfirst(str_replace('_', ' ', $status['status'])) ?>",<?php endforeach; ?>],
    datasets: [{
      label: 'Count',
      data: [<?php foreach ($appointmentStatus as $status): ?><?= $status['count'] ?>,<?php endforeach; ?>],
      backgroundColor: [
        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'
      ]
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

// Lab Test Status Chart
const labTestStatusCtx = document.getElementById('labTestStatusChart').getContext('2d');
const labTestStatusChart = new Chart(labTestStatusCtx, {
  type: 'pie',
  data: {
    labels: [<?php foreach ($labTestStatus as $status): ?>"<?= ucfirst(str_replace('_', ' ', $status['status'])) ?>",<?php endforeach; ?>],
    datasets: [{
      data: [<?php foreach ($labTestStatus as $status): ?><?= $status['count'] ?>,<?php endforeach; ?>],
      backgroundColor: [
        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'
      ]
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});
</script>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>
