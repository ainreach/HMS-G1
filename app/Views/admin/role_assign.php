<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Assign Role | Hospital Management System</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f2f7fb;
      margin: 0;
      padding: 0;
      color: #333;
    }

    main.content {
      max-width: 700px;
      margin: 40px auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      padding: 32px 40px;
      border-top: 6px solid #7ec8e3;
    }

    h1 {
      margin-bottom: 20px;
      font-size: 28px;
      color: #1e6f9f;
      text-align: center;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .form-row {
      display: flex;
      flex-direction: column;
    }

    label {
      font-weight: 600;
      color: #2f4f6f;
      margin-bottom: 6px;
    }

    select {
      padding: 10px 14px;
      border: 1px solid #c9d9e8;
      border-radius: 8px;
      font-size: 15px;
      background: #f9fbfd;
      transition: all 0.2s ease;
    }

    select:focus {
      border-color: #7ec8e3;
      outline: none;
      box-shadow: 0 0 0 3px rgba(126, 200, 227, 0.2);
      background: #fff;
    }

    .alert {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 12px;
      font-weight: 500;
    }

    .alert-error {
      background: #ffe5e5;
      color: #b40000;
      border: 1px solid #f5b5b5;
    }

    .alert-success {
      background: #e6f7ef;
      color: #1b7f46;
      border: 1px solid #a7dfb8;
    }

    .btn {
      background: #7ec8e3;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.1s ease;
      text-decoration: none;
      text-align: center;
    }

    .btn:hover {
      background: #6dbcd9;
      transform: translateY(-1px);
    }

    .btn-secondary {
      background: #e0e6ec;
      color: #333;
    }

    .btn-secondary:hover {
      background: #d0d9e2;
    }

    .flex-row {
      display: flex;
      gap: 12px;
    }

    @media (max-width: 600px) {
      main.content {
        padding: 24px;
      }
      .flex-row {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <main class="content">
    <h1>Assign User Role</h1>

    <?php if (session('error')): ?>
      <div class="alert alert-error"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <?php if (session('success')): ?>
      <div class="alert alert-success"><?= esc(session('success')) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('admin/roles/assign') ?>">
      <?= csrf_field() ?>

      <div class="form-row">
        <label for="user_id">Select User</label>
        <select name="user_id" id="user_id" required>
          <option value="">-- Select User --</option>
          <?php foreach (($users ?? []) as $u): ?>
            <option value="<?= (int)$u['id'] ?>"><?= esc($u['username']) ?> (<?= esc($u['role']) ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-row">
        <label for="role">Select Role</label>
        <select name="role" id="role" required>
          <option value="">-- Select Role --</option>
          <?php 
            $roles = ['admin','it_staff','doctor','nurse','receptionist','lab_staff','pharmacist','accountant']; 
            foreach($roles as $r): 
          ?>
            <option value="<?= $r ?>"><?= ucfirst($r) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="flex-row" style="margin-top: 20px;">
        <button type="submit" class="btn">🔑 Assign Role</button>
        <a href="<?= site_url('dashboard/admin') ?>" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </main>

</body>
</html>
