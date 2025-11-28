<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dispense Medicine</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
  <main class="content" style="max-width:720px;margin:20px auto;padding:16px">
    <h1 style="margin:0 0 12px">Dispense Medicine</h1>
    <?php if (session('error')): ?>
      <div class="alert alert-error"><?= esc(session('error')) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('pharmacy/dispense') ?>">
      <?= csrf_field() ?>
      <div class="form-row">
        <label>Patient</label>
        <div class="autocomplete">
          <input type="text" id="patient_search" placeholder="Search by name or ID..." required>
          <input type="hidden" name="patient_id" id="patient_id">
          <div class="autocomplete-items"></div>
        </div>
      </div>
      <div class="form-row">
        <label>Medicine</label>
        <div class="autocomplete">
          <input type="text" id="medicine_search" placeholder="Search by name or code..." required>
          <input type="hidden" name="medicine_id" id="medicine_id">
          <div class="autocomplete-items"></div>
        </div>
      </div>
      <div class="form-row">
        <label>Quantity</label>
        <input type="number" min="1" name="quantity" required value="<?= old('quantity') ?>">
      </div>
      <div class="form-row">
        <label>Notes (optional)</label>
        <textarea name="notes" rows="4"><?= old('notes') ?></textarea>
      </div>
      <div style="display:flex;gap:8px;margin-top:10px">
        <button type="submit" class="btn btn-primary">Dispense</button>
        <a class="btn" href="<?= site_url('dashboard/pharmacist') ?>">Cancel</a>
      </div>
    </form>
  </main>
</body></html>
