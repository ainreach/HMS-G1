<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Enter Lab Result</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
  <main class="content" style="max-width:720px;margin:20px auto;padding:16px">
    <h1 style="margin:0 0 12px">Enter Lab Result</h1>
    <?php if (session('error')): ?>
      <div class="alert alert-error"><?= esc(session('error')) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('lab/results') ?>">
      <?= csrf_field() ?>
      <div class="form-row">
        <label>Lab Test</label>
        <?php if (!empty($pendingTests)): ?>
          <select name="lab_test_id" required>
            <option value="">-- Select Pending Test --</option>
            <?php foreach ($pendingTests as $t): $tid=(int)($t['id']??0); $label=($t['test_number'] ?? ('TEST-'.$tid)).' • '.($t['test_name'] ?? ''); ?>
              <option value="<?= $tid ?>" <?= old('lab_test_id')==$tid?'selected':'' ?>><?= esc($label) ?></option>
            <?php endforeach; ?>
          </select>
        <?php else: ?>
          <input type="number" name="lab_test_id" required value="<?= old('lab_test_id') ?>">
        <?php endif; ?>
      </div>
      <div class="form-row">
        <label>Results</label>
        <textarea name="results" rows="6" required><?= old('results') ?></textarea>
      </div>
      <div class="form-row">
        <label>Normal Range (optional)</label>
        <input type="text" name="normal_range" value="<?= old('normal_range') ?>">
      </div>
      <div class="form-row">
        <label>Interpretation (optional)</label>
        <textarea name="interpretation" rows="4"><?= old('interpretation') ?></textarea>
      </div>
      <div style="display:flex;gap:8px;margin-top:10px">
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn" href="<?= site_url('dashboard/lab') ?>">Cancel</a>
      </div>
    </form>
  </main>
</body></html>
