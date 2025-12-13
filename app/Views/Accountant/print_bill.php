<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice - <?= esc($billing['invoice_number'] ?? '') ?></title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    @media print {
      body { margin: 0; padding: 20px; }
      .no-print { display: none !important; }
      .print-header { margin-bottom: 30px; }
    }
    .invoice-container { max-width: 800px; margin: 0 auto; padding: 20px; background: white; }
    .invoice-header { border-bottom: 2px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 30px; }
    .invoice-footer { border-top: 2px solid #e5e7eb; padding-top: 20px; margin-top: 30px; text-align: center; color: #6b7280; font-size: 0.875rem; }
  </style>
</head>
<body>
<div class="invoice-container">
  <div class="no-print" style="margin-bottom: 20px; text-align: right;">
    <button onclick="window.print()" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer;">
      <i class="fa-solid fa-print"></i> Print
    </button>
    <a href="<?= site_url('accountant/consolidated-bill/' . $patient['id']) ?>" style="padding: 8px 16px; background: #6b7280; color: white; text-decoration: none; border-radius: 6px; margin-left: 8px;">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
  </div>

  <div class="invoice-header">
    <div style="display: flex; justify-content: space-between; align-items: start;">
      <div>
        <h1 style="margin: 0; font-size: 1.5rem; color: #0ea5e9;">Hospital Management System</h1>
        <p style="margin: 4px 0 0 0; color: #6b7280;">Consolidated Patient Invoice</p>
      </div>
      <div style="text-align: right;">
        <h2 style="margin: 0; font-size: 1.25rem; color: #1f2937;">INVOICE</h2>
        <p style="margin: 4px 0 0 0; color: #6b7280; font-size: 0.875rem;"><?= esc($billing['invoice_number'] ?? '') ?></p>
      </div>
    </div>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
    <div>
      <h3 style="margin: 0 0 12px 0; font-size: 1rem; color: #1f2937;">Bill To:</h3>
      <p style="margin: 0 0 4px 0; font-weight: 600;"><?= esc(trim($patient['first_name'] . ' ' . $patient['last_name'])) ?></p>
      <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 0.875rem;">Patient ID: <?= esc($patient['patient_id'] ?? 'N/A') ?></p>
      <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 0.875rem;">Phone: <?= esc($patient['phone'] ?? 'N/A') ?></p>
      <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">DOB: <?= esc($patient['date_of_birth'] ?? 'N/A') ?></p>
    </div>
    <div style="text-align: right;">
      <h3 style="margin: 0 0 12px 0; font-size: 1rem; color: #1f2937;">Invoice Details:</h3>
      <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 0.875rem;">Bill Date: <?= esc($billing['bill_date'] ?? date('Y-m-d')) ?></p>
      <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 0.875rem;">Due Date: <?= esc($billing['due_date'] ?? 'N/A') ?></p>
      <p style="margin: 0;">
        <span style="padding: 4px 12px; background: <?= strtolower($billing['payment_status'] ?? 'pending') === 'paid' ? '#10b981' : '#f59e0b' ?>; color: white; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
          <?= esc(ucfirst($billing['payment_status'] ?? 'Pending')) ?>
        </span>
      </p>
    </div>
  </div>

  <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
    <thead>
      <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
        <th style="text-align: left; padding: 12px; font-weight: 600; color: #1f2937;">Service/Item</th>
        <th style="text-align: left; padding: 12px; font-weight: 600; color: #1f2937;">Type</th>
        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Quantity</th>
        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Unit Price</th>
        <th style="text-align: right; padding: 12px; font-weight: 600; color: #1f2937;">Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($items)): ?>
        <?php foreach ($items as $item): ?>
          <tr style="border-bottom: 1px solid #f3f4f6;">
            <td style="padding: 12px;">
              <strong><?= esc($item['item_name'] ?? '') ?></strong>
              <?php if (!empty($item['description'])): ?>
                <br><small style="color: #6b7280; font-size: 0.875rem;"><?= esc($item['description']) ?></small>
              <?php endif; ?>
            </td>
            <td style="padding: 12px; color: #6b7280; font-size: 0.875rem;"><?= esc(ucfirst($item['item_type'] ?? '')) ?></td>
            <td style="padding: 12px; text-align: right;"><?= number_format((float)($item['quantity'] ?? 0), 2) ?></td>
            <td style="padding: 12px; text-align: right;">$<?= number_format((float)($item['unit_price'] ?? 0), 2) ?></td>
            <td style="padding: 12px; text-align: right; font-weight: 600;">$<?= number_format((float)($item['total_price'] ?? 0), 2) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" style="padding: 40px; text-align: center; color: #6b7280;">No items found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
    <tfoot style="background: #f9fafb; border-top: 2px solid #e5e7eb;">
      <tr>
        <td colspan="4" style="padding: 12px; text-align: right; font-weight: 600;">Subtotal:</td>
        <td style="padding: 12px; text-align: right; font-weight: 600;">$<?= number_format((float)($billing['subtotal'] ?? 0), 2) ?></td>
      </tr>
      <?php if ((float)($billing['discount_amount'] ?? 0) > 0): ?>
      <tr>
        <td colspan="4" style="padding: 12px; text-align: right;">Discount:</td>
        <td style="padding: 12px; text-align: right; color: #10b981;">-$<?= number_format((float)($billing['discount_amount'] ?? 0), 2) ?></td>
      </tr>
      <?php endif; ?>
      <tr style="border-top: 2px solid #e5e7eb;">
        <td colspan="4" style="padding: 12px; text-align: right; font-weight: 700; font-size: 1.1rem;">Total Amount:</td>
        <td style="padding: 12px; text-align: right; font-weight: 700; font-size: 1.1rem;">$<?= number_format((float)($billing['total_amount'] ?? 0), 2) ?></td>
      </tr>
    </tfoot>
  </table>

  <?php if (!empty($payments)): ?>
  <div style="margin-bottom: 30px;">
    <h3 style="margin: 0 0 16px 0; font-size: 1rem; color: #1f2937;">Payment History:</h3>
    <table style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
          <th style="text-align: left; padding: 8px; font-weight: 600; color: #1f2937; font-size: 0.875rem;">Date</th>
          <th style="text-align: right; padding: 8px; font-weight: 600; color: #1f2937; font-size: 0.875rem;">Amount</th>
          <th style="text-align: left; padding: 8px; font-weight: 600; color: #1f2937; font-size: 0.875rem;">Method</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($payments as $p): ?>
          <tr style="border-bottom: 1px solid #f3f4f6;">
            <td style="padding: 8px; font-size: 0.875rem;"><?= esc(date('M d, Y', strtotime($p['paid_at'] ?? $p['created_at'] ?? 'now'))) ?></td>
            <td style="padding: 8px; text-align: right; font-weight: 600; color: #10b981; font-size: 0.875rem;">$<?= number_format((float)($p['amount'] ?? 0), 2) ?></td>
            <td style="padding: 8px; font-size: 0.875rem; color: #6b7280;"><?= esc(ucfirst($p['payment_method'] ?? 'N/A')) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr style="border-top: 2px solid #e5e7eb;">
          <td style="padding: 8px; text-align: right; font-weight: 700; font-size: 0.875rem;" colspan="2">Total Paid:</td>
          <td style="padding: 8px; font-weight: 700; font-size: 0.875rem; color: #10b981;">$<?= number_format((float)($totalPaid ?? 0), 2) ?></td>
        </tr>
      </tfoot>
    </table>
  </div>
  <?php endif; ?>

  <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <div>
        <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 0.875rem;">Total Amount:</p>
        <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">$<?= number_format((float)($billing['total_amount'] ?? 0), 2) ?></p>
      </div>
      <div style="text-align: right;">
        <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 0.875rem;">Amount Paid:</p>
        <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #10b981;">$<?= number_format((float)($totalPaid ?? 0), 2) ?></p>
      </div>
      <div style="text-align: right;">
        <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 0.875rem;">Balance:</p>
        <?php $balance = (float)($billing['balance'] ?? 0); ?>
        <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: <?= $balance > 0 ? '#ef4444' : '#10b981' ?>;">
          $<?= number_format($balance, 2) ?>
        </p>
      </div>
    </div>
  </div>

  <div class="invoice-footer">
    <p style="margin: 0;">This is a computer-generated invoice. No signature required.</p>
    <p style="margin: 4px 0 0 0;">Generated on <?= date('F d, Y \a\t h:i A') ?></p>
  </div>
</div>
</body>
</html>

