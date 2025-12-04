<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="panel">
  <div class="panel-head"><h2 style="margin:0;font-size:1.1rem">Claim Details</h2></div>
  <div class="panel-body" style="position:relative;min-height:240px;">
    <div class="overlay-backdrop" role="dialog" aria-modal="true" style="position:fixed;inset:0;background:rgba(15,23,42,0.65);display:flex;align-items:center;justify-content:center;z-index:50;">
      <div class="floating-card" style="background:#ffffff;border-radius:12px;max-width:520px;width:100%;box-shadow:0 20px 40px rgba(15,23,42,0.25);overflow:hidden;">
        <div class="floating-card-header" style="padding:14px 18px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;background:linear-gradient(to right,#0ea5e9,#0369a1);color:#ffffff;">
          <div>
            <div style="font-size:0.8rem;opacity:.9;">Claim #<?= esc($claim['claim_no'] ?? '') ?></div>
            <div style="font-size:1rem;font-weight:600;">Insurance Claim Details</div>
          </div>
          <a href="<?= site_url('admin/insurance-claims') ?>" aria-label="Close" style="color:#e5e7eb;text-decoration:none;font-size:1.1rem;">
            <i class="fa-solid fa-xmark"></i>
          </a>
        </div>
        <div class="floating-card-body" style="padding:18px;display:grid;grid-template-columns:1fr 1fr;gap:10px 16px;font-size:0.9rem;">
          <div>
            <div class="field-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;margin-bottom:2px;">Invoice #</div>
            <div class="field-value" style="font-weight:500;color:#111827;white-space:nowrap;"><?= esc($claim['invoice_no'] ?? '—') ?></div>
          </div>
          <div>
            <div class="field-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;margin-bottom:2px;">Patient</div>
            <div class="field-value" style="font-weight:500;color:#111827;white-space:nowrap;"><?= esc($claim['patient_name'] ?? '—') ?></div>
          </div>
          <div>
            <div class="field-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;margin-bottom:2px;">Provider</div>
            <div class="field-value" style="font-weight:500;color:#111827;white-space:nowrap;"><?= esc($claim['provider'] ?? '—') ?></div>
          </div>
          <div>
            <div class="field-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;margin-bottom:2px;">Policy #</div>
            <div class="field-value" style="font-weight:500;color:#111827;white-space:nowrap;"><?= esc($claim['policy_no'] ?? '—') ?></div>
          </div>
          <div>
            <div class="field-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;margin-bottom:2px;">Amount Claimed</div>
            <div class="field-value" style="font-weight:500;color:#111827;white-space:nowrap;">$<?= number_format((float)($claim['amount_claimed'] ?? 0), 2) ?></div>
          </div>
          <div>
            <div class="field-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;margin-bottom:2px;">Amount Approved</div>
            <div class="field-value" style="font-weight:500;color:#111827;white-space:nowrap;">$<?= number_format((float)($claim['amount_approved'] ?? 0), 2) ?></div>
          </div>
          <div>
            <div class="field-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;margin-bottom:2px;">Status</div>
            <div class="field-value" style="font-weight:500;color:#111827;white-space:nowrap;"><span class="badge" style="display:inline-flex;align-items:center;white-space:nowrap;"><?= esc(ucfirst($claim['status'] ?? 'submitted')) ?></span></div>
          </div>
          <div>
            <div class="field-label" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:.04em;color:#6b7280;margin-bottom:2px;">Submitted At</div>
            <div class="field-value" style="font-weight:500;color:#111827;white-space:nowrap;">&nbsp;<?= esc($claim['submitted_at'] ?? '—') ?></div>
          </div>
        </div>
        <div class="floating-card-footer" style="padding:12px 18px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;background:#f9fafb;">
          <a href="<?= site_url('admin/insurance-claims') ?>" class="btn" style="background:#e5e7eb;color:#111827;border:none;">Close</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>
