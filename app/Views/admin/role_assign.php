<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Assign Role</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
  <main class="content" style="max-width:800px;margin:20px auto;padding:16px">
    <h1 style="margin:0 0 12px">Assign Role</h1>
    <?php if (session('error')): ?><div class="alert alert-error"><?= esc(session('error')) ?></div><?php endif; ?>
    <?php if (session('success')): ?><div class="alert alert-success"><?= esc(session('success')) ?></div><?php endif; ?>
    <form method="post" action="<?= site_url('admin/roles/assign') ?>">
      <?= csrf_field() ?>
      <div class="form-row"><label>User</label>
        <select name="user_id" required>
          <option value="">-- Select User --</option>
          <?php foreach (($users ?? []) as $u): ?>
            <option value="<?= (int)$u['id'] ?>"><?= esc($u['username']) ?> (<?= esc($u['role']) ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-row"><label>Role</label>
        <select name="role" required>
          <option value="">-- Select Role --</option>
          <?php $roles=['admin','it_staff','doctor','nurse','receptionist','lab_staff','pharmacist','accountant']; foreach($roles as $r): ?>
            <option value="<?= $r ?>"><?= $r ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div style="display:flex;gap:8px;margin-top:10px">
        <button type="submit" class="btn">Assign</button>
        <a class="btn" href="<?= site_url('dashboard/admin') ?>">Cancel</a>
      </div>
    </form>
  </main>
</body></html>
