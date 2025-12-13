<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Details | Hospital Management System</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f2f7fb;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      padding: 0 16px;
    }

    .card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      overflow: hidden;
      border-top: 6px solid #7ec8e3;
    }

    .card-header {
      background: #7ec8e3;
      color: #fff;
      padding: 16px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 1.2rem;
      font-weight: 600;
    }

    .card-header a {
      background: #fff;
      color: #1e6f9f;
      padding: 6px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .card-header a:hover {
      background: #e0f0fa;
    }

    .card-body {
      padding: 24px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 0;
    }

    th, td {
      text-align: left;
      padding: 12px 10px;
      border-bottom: 1px solid #c9d9e8;
    }

    th {
      background: #e6f2f9;
      color: #1e6f9f;
      width: 220px;
    }

    tr:last-child td {
      border-bottom: none;
    }

    .section-title {
      font-size: 18px;
      color: #1e6f9f;
      margin: 20px 0 12px;
      border-bottom: 1px solid #c9d9e8;
      padding-bottom: 6px;
    }

    .text-center {
      text-align: center;
      color: #555;
      margin-top: 20px;
    }

    @media (max-width: 600px) {
      th, td {
        display: block;
        width: 100%;
      }
      th {
        background: transparent;
        color: #1e6f9f;
        font-weight: 600;
      }
      td {
        margin-bottom: 12px;
        padding-left: 0;
      }
      table {
        border: none;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="card-header">
      <span>Patient Details</span>
      <a href="<?= site_url('admin/patients') ?>">Back to List</a>
    </div>
    <div class="card-body">
      <?php if (isset($patient) && !empty($patient)): ?>
        <div class="section">
          <div class="section-title">Basic Information</div>
          <table>
            <tbody>
              <tr><th>Patient ID</th><td><?= esc($patient['patient_id'] ?? 'N/A') ?></td></tr>
              <tr><th>Name</th><td><?= esc(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) ?></td></tr>
              <tr><th>Date of Birth</th><td><?= esc($patient['date_of_birth'] ?? 'N/A') ?></td></tr>
              <tr><th>Gender</th><td><?= esc(ucfirst($patient['gender'] ?? 'N/A')) ?></td></tr>
              <tr><th>Phone</th><td><?= esc($patient['phone'] ?? 'N/A') ?></td></tr>
              <tr><th>Email</th><td><?= esc($patient['email'] ?? 'N/A') ?></td></tr>
            </tbody>
          </table>
        </div>

        <div class="section">
          <div class="section-title">Address</div>
          <table>
            <tbody>
              <tr><th>Address</th><td><?= esc($patient['address'] ?? 'N/A') ?></td></tr>
            </tbody>
          </table>
        </div>

        <div class="section">
          <div class="section-title">Emergency Contact</div>
          <table>
            <tbody>
              <tr><th>Name & Relationship</th><td><?= esc($patient['emergency_contact_name'] ?? 'N/A') ?> (<?= esc($patient['emergency_contact_relation'] ?? 'N/A') ?>)</td></tr>
              <tr><th>Phone</th><td><?= esc($patient['emergency_contact_phone'] ?? 'N/A') ?></td></tr>
            </tbody>
          </table>
        </div>

        <div class="section">
          <div class="section-title">Insurance & Medical Info</div>
          <table>
            <tbody>
              <tr><th>Insurance Provider</th><td><?= esc($patient['insurance_provider'] ?? 'N/A') ?></td></tr>
              <tr><th>Insurance Number</th><td><?= esc($patient['insurance_number'] ?? 'N/A') ?></td></tr>
              <tr><th>Allergies</th><td><?= esc($patient['allergies'] ?? 'N/A') ?></td></tr>
              <tr><th>Medical History</th><td><?= esc($patient['medical_history'] ?? 'N/A') ?></td></tr>
              <tr><th>Registered On</th><td><?= esc($patient['created_at'] ?? 'N/A') ?></td></tr>
            </tbody>
          </table>
        </div>

        <!-- CONSOLIDATED BILL - AUTOMATICALLY GENERATED -->
        <?php if (isset($consolidatedBill) && $consolidatedBill): ?>
        <div class="section">
          <div class="section-title" style="display:flex;justify-content:space-between;align-items:center">
            <span>Consolidated Bill (All Charges)</span>
            <span style="font-size:0.875rem;font-weight:normal">
              Bill #: <?= esc($consolidatedBill['invoice_number'] ?? 'N/A') ?>
            </span>
          </div>
          
          <!-- Bill Items -->
          <div style="margin-bottom:16px">
            <table style="width:100%;border-collapse:collapse;background:#fff;border:1px solid #c9d9e8;border-radius:8px;overflow:hidden">
              <thead>
                <tr style="background:#e6f2f9">
                  <th style="padding:10px;text-align:left;border-bottom:2px solid #c9d9e8">Service/Item</th>
                  <th style="padding:10px;text-align:left;border-bottom:2px solid #c9d9e8">Date</th>
                  <th style="padding:10px;text-align:right;border-bottom:2px solid #c9d9e8">Amount</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($billItems)): ?>
                  <?php foreach ($billItems as $item): ?>
                  <tr>
                    <td style="padding:10px;border-bottom:1px solid #e5e7eb">
                      <strong><?= esc(ucfirst($item['item_type'] ?? '')) ?></strong><br>
                      <small style="color:#666"><?= esc($item['item_name'] ?? '') ?></small>
                      <?php if (!empty($item['description'])): ?>
                        <br><small style="color:#999"><?= esc($item['description']) ?></small>
                      <?php endif; ?>
                    </td>
                    <td style="padding:10px;border-bottom:1px solid #e5e7eb;color:#666">
                      <?= esc(date('M d, Y', strtotime($item['created_at'] ?? 'now'))) ?>
                    </td>
                    <td style="padding:10px;border-bottom:1px solid #e5e7eb;text-align:right;font-weight:600">
                      $<?= number_format((float)($item['total_price'] ?? 0), 2) ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="3" style="padding:20px;text-align:center;color:#999">No charges yet</td>
                  </tr>
                <?php endif; ?>
              </tbody>
              <tfoot style="background:#f8fafc">
                <tr>
                  <td colspan="2" style="padding:12px;text-align:right;font-weight:700;border-top:2px solid #c9d9e8">Total Amount:</td>
                  <td style="padding:12px;text-align:right;font-weight:700;font-size:1.1rem;border-top:2px solid #c9d9e8">
                    $<?= number_format((float)($consolidatedBill['total_amount'] ?? 0), 2) ?>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>

          <!-- Payments -->
          <?php if (!empty($billPayments)): ?>
          <div style="margin-bottom:16px">
            <h4 style="margin:0 0 8px;color:#1e6f9f;font-size:0.9rem">Payments Received:</h4>
            <table style="width:100%;border-collapse:collapse;background:#fff;border:1px solid #c9d9e8;border-radius:8px;overflow:hidden">
              <thead>
                <tr style="background:#e6f2f9">
                  <th style="padding:10px;text-align:left;border-bottom:2px solid #c9d9e8">Date</th>
                  <th style="padding:10px;text-align:left;border-bottom:2px solid #c9d9e8">Method</th>
                  <th style="padding:10px;text-align:right;border-bottom:2px solid #c9d9e8">Amount</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($billPayments as $payment): ?>
                <tr>
                  <td style="padding:10px;border-bottom:1px solid #e5e7eb">
                    <?= esc(date('M d, Y', strtotime($payment['paid_at'] ?? $payment['created_at'] ?? 'now'))) ?>
                  </td>
                  <td style="padding:10px;border-bottom:1px solid #e5e7eb">
                    <?= esc(ucfirst($payment['payment_method'] ?? 'Cash')) ?>
                  </td>
                  <td style="padding:10px;border-bottom:1px solid #e5e7eb;text-align:right;color:#16a34a;font-weight:600">
                    $<?= number_format((float)($payment['amount'] ?? 0), 2) ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot style="background:#f0fdf4">
                <tr>
                  <td colspan="2" style="padding:12px;text-align:right;font-weight:700;border-top:2px solid #c9d9e8">Total Paid:</td>
                  <td style="padding:12px;text-align:right;font-weight:700;font-size:1.1rem;color:#16a34a;border-top:2px solid #c9d9e8">
                    $<?= number_format($totalPaid, 2) ?>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <?php endif; ?>

          <!-- Balance Summary -->
          <div style="background:<?= $balance > 0 ? '#fef2f2' : '#f0fdf4' ?>;border:2px solid <?= $balance > 0 ? '#ef4444' : '#16a34a' ?>;border-radius:8px;padding:16px;margin-top:16px">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
              <span style="font-weight:600;font-size:1rem">Total Charges:</span>
              <span style="font-weight:600;font-size:1rem">$<?= number_format((float)($consolidatedBill['total_amount'] ?? 0), 2) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
              <span style="font-weight:600;font-size:1rem">Total Paid:</span>
              <span style="font-weight:600;font-size:1rem;color:#16a34a">$<?= number_format($totalPaid, 2) ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:1.3rem;font-weight:700;border-top:2px solid <?= $balance > 0 ? '#ef4444' : '#16a34a' ?>;padding-top:12px;margin-top:12px">
              <span>Balance Due:</span>
              <span style="color:<?= $balance > 0 ? '#dc2626' : '#16a34a' ?>">
                $<?= number_format($balance, 2) ?>
              </span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div style="display:flex;gap:12px;margin-top:16px">
            <a href="<?= site_url('admin/consolidated-bill/' . $patient['id']) ?>" 
               style="padding:10px 20px;background:#3b82f6;color:white;text-decoration:none;border-radius:6px;display:inline-flex;align-items:center;gap:6px">
              <i class="fa-solid fa-file-invoice"></i> View Full Bill
            </a>
            <?php if ($balance > 0): ?>
            <a href="<?= site_url('admin/payments/new?patient_id=' . $patient['id']) ?>" 
               style="padding:10px 20px;background:#10b981;color:white;text-decoration:none;border-radius:6px;display:inline-flex;align-items:center;gap:6px">
              <i class="fa-solid fa-money-bill"></i> Pay Now
            </a>
            <?php endif; ?>
            <button onclick="window.print()" 
                    style="padding:10px 20px;background:#6b7280;color:white;border:none;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;gap:6px">
              <i class="fa-solid fa-print"></i> Print Bill
            </button>
          </div>
        </div>
        <?php endif; ?>
      <?php else: ?>
        <p class="text-center">Patient data not found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
