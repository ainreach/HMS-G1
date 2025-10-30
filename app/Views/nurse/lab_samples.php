<?= $this->extend('templates/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lab Samples Collection</h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="labSamplesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Test ID</th>
                            <th>Patient</th>
                            <th>Test Type</th>
                            <th>Requested Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($samples as $sample): ?>
                        <tr>
                            <td>#<?= $sample['id'] ?></td>
                            <td>
                                <?= $sample['first_name'] . ' ' . $sample['last_name'] ?>
                                <small class="d-block text-muted">ID: <?= $sample['patient_code'] ?></small>
                            </td>
                            <td><?= $sample['test_type'] ?? 'N/A' ?></td>
                            <td><?= date('M d, Y H:i', strtotime($sample['requested_date'])) ?></td>
                            <td>
                                <span class="badge badge-warning"><?= ucfirst(str_replace('_', ' ', $sample['status'])) ?></span>
                            </td>
                            <td>
                                <?php if ($sample['status'] === 'requested'): ?>
                                    <form action="<?= site_url('index.php/nurse/lab-samples/' . $sample['id'] . '/collect') ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this sample as collected?')">
                                            <i class="fas fa-check"></i> Mark Collected
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">Collected</span>
                                <?php endif; ?>
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
