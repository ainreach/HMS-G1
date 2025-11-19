<aside class="simple-sidebar" role="navigation" aria-label="Nurse navigation">
  <nav class="side-nav">
    <a href="<?= route_to('nurse.overview') ?>" class="<?= current_url() == route_to('nurse.overview') ? 'active' : '' ?>" aria-current="page">Overview</a>
    <a href="<?= route_to('nurse.wardPatients') ?>" class="<?= strpos(current_url(), 'ward-patients') !== false ? 'active' : '' ?>" data-feature="ehr">Ward Patients</a>
    <a href="<?= route_to('nurse.labSamples') ?>" class="<?= strpos(current_url(), 'lab-samples') !== false ? 'active' : '' ?>" data-feature="laboratory">Lab Samples</a>
    <a href="<?= route_to('nurse.treatmentUpdates') ?>" class="<?= strpos(current_url(), 'treatment-updates') !== false ? 'active' : '' ?>" data-feature="ehr">Treatment Updates</a>
    <a href="<?= route_to('nurse.newVitals') ?>" class="<?= strpos(current_url(), 'vitals/new') !== false ? 'active' : '' ?>" data-feature="ehr">Record Vitals</a>
  </nav>
</aside>
