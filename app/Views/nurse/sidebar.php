<aside class="simple-sidebar" role="navigation" aria-label="Nurse navigation">
  <nav class="side-nav">
    <a href="<?= site_url('dashboard/nurse') ?>" class="<?= (strpos(current_url(), 'dashboard/nurse') !== false || current_url() == site_url('dashboard/nurse')) ? 'active' : '' ?>" aria-current="page">Overview</a>
    <a href="<?= site_url('nurse/ward-patients') ?>" class="<?= strpos(current_url(), 'ward-patients') !== false ? 'active' : '' ?>" data-feature="ehr">Ward Patients</a>
    <a href="<?= site_url('nurse/lab-samples') ?>" class="<?= strpos(current_url(), 'lab-samples') !== false ? 'active' : '' ?>" data-feature="laboratory">Lab Samples</a>
    <a href="<?= site_url('nurse/lab-request') ?>" class="<?= strpos(current_url(), 'lab-request') !== false ? 'active' : '' ?>" data-feature="laboratory">New Lab Request</a>
    <a href="<?= site_url('nurse/treatment-updates') ?>" class="<?= strpos(current_url(), 'treatment-updates') !== false ? 'active' : '' ?>" data-feature="ehr">Treatment Updates</a>
    <a href="<?= site_url('nurse/vitals/new') ?>" class="<?= strpos(current_url(), 'vitals/new') !== false ? 'active' : '' ?>" data-feature="ehr">Record Vitals</a>
  </nav>
</aside>
