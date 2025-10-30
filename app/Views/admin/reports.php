<?= $this->include('admin/sidebar') ?>

<section class="panel">
  <div class="panel-head">
    <h2 style="margin:0;font-size:1.1rem">User Reports</h2>
  </div>
  <div class="panel-body">
    <?php $c = $counts ?? []; ?>
    <div class="kpi-grid">
      <article class="kpi-card kpi-primary"><div class="kpi-head"><span>Total</span></div><div class="kpi-value"><?= (int)($c['total'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Admins</span></div><div class="kpi-value"><?= (int)($c['admins'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Doctors</span></div><div class="kpi-value"><?= (int)($c['doctors'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Nurses</span></div><div class="kpi-value"><?= (int)($c['nurses'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Receptionists</span></div><div class="kpi-value"><?= (int)($c['receptionists'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Pharmacists</span></div><div class="kpi-value"><?= (int)($c['pharmacists'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Lab Staff</span></div><div class="kpi-value"><?= (int)($c['lab_staff'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>Accountants</span></div><div class="kpi-value"><?= (int)($c['accountants'] ?? 0) ?></div></article>
      <article class="kpi-card kpi-info"><div class="kpi-head"><span>IT Staff</span></div><div class="kpi-value"><?= (int)($c['it_staff'] ?? 0) ?></div></article>
    </div>
  </div>
</section>

</main>
</div>

<script src="<?= base_url('assets/js/rbac.js') ?>"></script>
</body>
</html>
