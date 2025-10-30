<?= $this->extend('templates/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Treatment Updates</h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Add New Treatment Update Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New Treatment Update</h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('nurse/treatment-updates') ?>" method="post">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="patient_id">Patient</label>
                        <select class="form-control" id="patient_id" name="patient_id" required>
                            <option value="">Select Patient</option>
                            <?php foreach ($patients ?? [] as $patient): ?>
                                <option value="<?= $patient['id'] ?>">
                                    <?= $patient['first_name'] . ' ' . $patient['last_name'] . ' (' . $patient['patient_id'] . ')' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="doctor_id">Doctor</label>
                        <select class="form-control" id="doctor_id" name="doctor_id">
                            <option value="">Select Doctor</option>
                            <?php foreach ($doctors ?? [] as $doctor): ?>
                                <option value="<?= $doctor['id'] ?>">
                                    <?= $doctor['first_name'] . ' ' . $doctor['last_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="treatment_update">Treatment Update</label>
                    <textarea class="form-control" id="treatment_update" name="treatment_update" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Update</button>
            </form>
        </div>
    </div>

    <!-- Treatment Updates List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Treatment Updates</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="treatmentUpdatesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($updates as $update): ?>
                        <tr>
                            <td><?= date('M d, Y H:i', strtotime($update['visit_date'])) ?></td>
                            <td>
                                <?= $update['first_name'] . ' ' . $update['last_name'] ?>
                                <small class="d-block text-muted">ID: <?= $update['patient_code'] ?></small>
                            </td>
                            <td>Dr. <?= $update['doctor_name'] ?? 'N/A' ?></td>
                            <td><?= nl2br(htmlspecialchars($update['treatment_plan'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
