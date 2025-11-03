<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit User</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
  <main class="content" style="max-width:800px;margin:20px auto;padding:16px">
    <h1 style="margin:0 0 12px">Edit User</h1>
    <?php if (session('error')): ?><div class="alert alert-error"><?= esc(session('error')) ?></div><?php endif; ?>
    <?php if (session('success')): ?><div class="alert alert-success"><?= esc(session('success')) ?></div><?php endif; ?>
    <form method="post" action="<?= site_url('admin/users/' . (int)$user['id']) ?>">
      <?= csrf_field() ?>
      <div class="form-row"><label>Employee ID</label><input type="text" name="employee_id" value="<?= esc($user['employee_id'] ?? '') ?>"></div>
      <div class="form-row"><label>Username</label><input type="text" value="<?= esc($user['username'] ?? '') ?>" disabled></div>
      <div class="form-row"><label>Email</label><input type="email" name="email" value="<?= esc($user['email'] ?? '') ?>"></div>
      <div class="form-row" style="display:flex;gap:8px">
        <div style="flex:1"><label>First Name</label><input type="text" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>"></div>
        <div style="flex:1"><label>Last Name</label><input type="text" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>"></div>
      </div>
      <div class="form-row"><label>New Password (leave blank to keep)</label><input type="password" name="password"></div>
      <div class="form-row"><label>Role</label>
        <select name="role" required>
          <?php $roles=['admin','it_staff','doctor','nurse','receptionist','lab_staff','pharmacist','accountant']; foreach($roles as $r): ?>
            <option value="<?= $r ?>" <?= ($user['role']??'')===$r?'selected':'' ?>><?= $r ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div style="display:flex;gap:8px;margin-top:10px">
        <button type="submit" class="btn">Save</button>
        <a class="btn" href="<?= site_url('admin/users') ?>">Cancel</a>
      </div>
    </form>
  </main>
</body></html>
