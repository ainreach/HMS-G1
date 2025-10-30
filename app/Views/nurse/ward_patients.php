<?= $this->extend('templates/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ward Patients</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="wardPatientsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>Phone</th>
                            <th>Last Visit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?= $patient['patient_code'] ?></td>
                            <td><?= $patient['first_name'] . ' ' . $patient['last_name'] ?></td>
                            <td><?= ucfirst($patient['gender'] ?? '') ?></td>
                            <td>
                                <?php 
                                    $dob = new \DateTime($patient['date_of_birth']);
                                    $now = new \DateTime();
                                    $age = $now->diff($dob)->y;
                                    echo $age . ' years';
                                ?>
                            </td>
                            <td><?= $patient['phone'] ?? 'N/A' ?></td>
                            <td><?= date('M d, Y', strtotime($patient['visit_date'])) ?></td>
                            <td>
                                <a href="<?= route_to('nurse.patientMonitoring', $patient['id']) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-heartbeat"></i> Monitor
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
