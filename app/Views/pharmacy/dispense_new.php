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
        <?php if (!empty($patients)): ?>
          <select name="patient_id" required>
            <option value="">-- Select Patient --</option>
            <?php foreach ($patients as $p): $pid=(int)($p['id']??0); $name=trim(($p['last_name']??'').', '.($p['first_name']??'')); ?>
              <option value="<?= $pid ?>" <?= old('patient_id')==$pid?'selected':'' ?>><?= esc($name) ?> (ID: <?= $pid ?>)</option>
            <?php endforeach; ?>
          </select>
        <?php else: ?>
          <input type="number" name="patient_id" required value="<?= old('patient_id') ?>">
        <?php endif; ?>
      </div>
      <div class="form-row">
        <label>Medicine</label>
        <?php if (!empty($medicines)): ?>
          <select name="medicine_id" required>
            <option value="">-- Select Medicine --</option>
            <?php foreach ($medicines as $m): $mid=(int)($m['id']??0); $label=trim(($m['name']??'').' '.($m['strength']??'')); ?>
              <option value="<?= $mid ?>" <?= old('medicine_id')==$mid?'selected':'' ?>><?= esc($label) ?> (ID: <?= $mid ?>)</option>
            <?php endforeach; ?>
          </select>
        <?php else: ?>
          <input type="number" name="medicine_id" required value="<?= old('medicine_id') ?>">
        <?php endif; ?>
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
