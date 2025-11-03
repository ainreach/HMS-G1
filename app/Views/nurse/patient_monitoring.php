<?= $this->extend('templates/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Patient Monitoring</h1>
        <a href="<?= route_to('nurse.wardPatients') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Ward Patients
        </a>
    </div>

    <!-- Patient Info Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Patient Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Patient ID:</strong> <?= esc($patient['patient_id'] ?? 'N/A') ?></p>
                    <p><strong>Name:</strong> <?= esc(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) ?></p>
                    <p><strong>Gender:</strong> <?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date of Birth:</strong> <?= !empty($patient['date_of_birth']) ? date('M d, Y', strtotime($patient['date_of_birth'])) : 'N/A' ?></p>
                    <p><strong>Phone:</strong> <?= esc($patient['phone'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vital Signs -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Vital Signs History</h6>
                    <a href="<?= route_to('nurse.newVitals') ?>?patient_id=<?= $patient['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Record Vitals
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($vitals)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>BP</th>
                                        <th>Pulse</th>
                                        <th>Temp (°C)</th>
                                        <th>Resp. Rate</th>
                                        <th>O2 Sat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vitals as $vital): 
                                        $vitalSigns = json_decode($vital['vital_signs'] ?? '{}', true);
                                    ?>
                                        <tr>
                                            <td><?= date('M d, Y H:i', strtotime($vital['visit_date'])) ?></td>
                                            <td><?= esc($vitalSigns['blood_pressure'] ?? 'N/A') ?></td>
                                            <td><?= esc($vitalSigns['pulse'] ?? 'N/A') ?></td>
                                            <td><?= esc($vitalSigns['temperature'] ?? 'N/A') ?></td>
                                            <td><?= esc($vitalSigns['respiratory_rate'] ?? 'N/A') ?></td>
                                            <td><?= esc($vitalSigns['oxygen_saturation'] ?? 'N/A') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">No vital signs recorded yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= route_to('nurse.newVitals') ?>?patient_id=<?= $patient['id'] ?>" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-heartbeat"></i> Record Vitals
                        </a>
                        <a href="<?= route_to('nurse.newNote') ?>?patient_id=<?= $patient['id'] ?>" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-notes-medical"></i> Add Clinical Note
                        </a>
                    </div>
                </div>
            </div>

            <!-- Treatment Notes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Notes</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($treatmentNotes)): ?>
                        <div class="list-group">
                            <?php foreach ($treatmentNotes as $note): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <small class="text-muted"><?= date('M d, Y', strtotime($note['visit_date'])) ?></small>
                                    </div>
                                    <p class="mb-1"><?= nl2br(esc($note['treatment_plan'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No treatment notes available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
