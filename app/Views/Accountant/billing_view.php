<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Invoice Details</h4>
            <small class="text-muted">Invoice Number: <?= esc($billing['invoice_number'] ?? '') ?></small>
        </div>
        <div>
            <a href="<?= site_url('accountant/billing') ?>" class="btn btn-sm btn-outline-secondary">&larr; Back to Invoices</a>
            <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">Print Invoice</button>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <strong>Patient Information</strong>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Name:</strong> <?= esc($patient['first_name'] ?? '') . ' ' . esc($patient['last_name'] ?? '') ?></p>
                    <p class="mb-1"><strong>Patient Code:</strong> <?= esc($patient['patient_id'] ?? '') ?></p>
                    <p class="mb-1"><strong>Date of Birth:</strong> <?= esc($patient['date_of_birth'] ?? '') ?></p>
                    <p class="mb-1"><strong>Gender:</strong> <?= esc(ucfirst($patient['gender'] ?? '')) ?></p>
                    <p class="mb-1"><strong>Contact:</strong> <?= esc($patient['phone'] ?? '') ?></p>
                    <p class="mb-0"><strong>Insurance:</strong> <?= esc($patient['insurance_provider'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <strong>Invoice Summary</strong>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Invoice Number</div>
                        <div class="col-6 text-end"><?= esc($billing['invoice_number'] ?? '') ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Bill Date</div>
                        <div class="col-6 text-end"><?= esc($billing['bill_date'] ?? '') ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Due Date</div>
                        <div class="col-6 text-end"><?= esc($billing['due_date'] ?? '') ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6 text-muted">Status</div>
                        <div class="col-6 text-end">
                            <?php $status = strtolower((string)($billing['payment_status'] ?? 'pending')); ?>
                            <?php
                                $badgeClass = 'secondary';
                                if ($status === 'paid') $badgeClass = 'success';
                                elseif ($status === 'partial') $badgeClass = 'warning';
                                elseif ($status === 'overdue') $badgeClass = 'danger';
                            ?>
                            <span class="badge bg-<?= $badgeClass ?>"><?= esc(ucfirst($status)) ?></span>
                        </div>
                    </div>

                    <hr class="mt-0">

                    <div class="row mb-1">
                        <div class="col-6 text-muted">Total Amount</div>
                        <div class="col-6 text-end">₱<?= number_format((float)($billing['total_amount'] ?? 0), 2) ?></div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-6 text-muted">Insurance Coverage</div>
                        <div class="col-6 text-end text-success">-₱<?= number_format((float)($insuranceCoverage ?? 0), 2) ?></div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-6 text-muted">Amount Paid</div>
                        <div class="col-6 text-end text-primary">₱<?= number_format((float)($totalPaid ?? 0), 2) ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6 text-muted"><strong>Balance</strong></div>
                        <?php $balance = (float)($billing['balance'] ?? 0); ?>
                        <div class="col-6 text-end fw-bold <?= $balance > 0 ? 'text-danger' : 'text-success' ?>">
                            ₱<?= number_format($balance, 2) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <strong>Services &amp; Products</strong>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 15%">Type</th>
                        <th>Description</th>
                        <th style="width: 15%">Date</th>
                        <th style="width: 15%" class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= esc(ucfirst($item['item_type'] ?? '')) ?></td>
                                <td><?= esc($item['item_name'] ?? '') ?><?= $item['description'] ? ' - ' . esc($item['description']) : '' ?></td>
                                <td><?= esc($item['created_at'] ?? '') ?></td>
                                <td class="text-end">₱<?= number_format((float)($item['total_price'] ?? 0), 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No items found for this invoice.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end">Subtotal:</td>
                        <td class="text-end">₱<?= number_format((float)($billing['subtotal'] ?? 0), 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end">Insurance Coverage:</td>
                        <td class="text-end text-success">-₱<?= number_format((float)($insuranceCoverage ?? 0), 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                        <td class="text-end fw-bold">₱<?= number_format((float)($billing['total_amount'] ?? 0), 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <strong>Payment History</strong>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 25%">Payment Date</th>
                        <th style="width: 20%">Amount</th>
                        <th style="width: 20%">Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($payments)): ?>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <td><?= esc($p['paid_at'] ?? '') ?></td>
                                <td>₱<?= number_format((float)($p['amount'] ?? 0), 2) ?></td>
                                <td><?= esc($billing['payment_method'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">No payments recorded yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-end fw-bold">Total Paid:</td>
                        <td class="fw-bold">₱<?= number_format((float)($totalPaid ?? 0), 2) ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
