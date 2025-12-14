<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit User | Hospital Management System</title>
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
      max-width: 720px;
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

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
      padding: 10px 14px;
      border: 1px solid #c9d9e8;
      border-radius: 8px;
      font-size: 15px;
      background: #f9fbfd;
      transition: all 0.2s ease;
    }

    input:focus, select:focus {
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
    <h1>Edit User Profile</h1>

    <?php if (session('error')): ?>
      <div class="alert alert-error"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <?php if (session('success')): ?>
      <div class="alert alert-success"><?= esc(session('success')) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('admin/users/' . (int)$user['id']) ?>">
      <?= csrf_field() ?>

      <div class="form-row">
        <label>Employee ID</label>
        <input type="text" name="employee_id" value="<?= esc($user['employee_id'] ?? '') ?>" required>
      </div>

      <div class="form-row">
        <label>Username</label>
        <input type="text" name="username" value="<?= esc($user['username'] ?? '') ?>" required>
        <small style="color: #6b7280; margin-top: 4px;">Username is used for login. Make sure it's unique.</small>
      </div>

      <div class="form-row">
        <label>Email</label>
        <input type="email" name="email" value="<?= esc($user['email'] ?? '') ?>" required>
      </div>

      <div class="flex-row">
        <div class="form-row" style="flex:1">
          <label>First Name</label>
          <input type="text" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>" required>
        </div>
        <div class="form-row" style="flex:1">
          <label>Last Name</label>
          <input type="text" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>" required>
        </div>
      </div>

      <div class="form-row">
        <label>New Password (leave blank to keep current)</label>
        <input type="password" name="password" placeholder="Enter new password">
      </div>

      <div class="form-row" id="specializationRow" style="display: <?= ($user['role'] ?? '') === 'doctor' ? 'flex' : 'none' ?>;">
        <label>Specialization</label>
        <input type="text" name="specialization" value="<?= esc($user['specialization'] ?? '') ?>" placeholder="e.g., Pediatrician, Cardiologist">
      </div>

      <div class="form-row" id="departmentRow" style="display: <?= ($user['role'] ?? '') === 'doctor' ? 'flex' : 'none' ?>;">
        <label>Department <small style="color: #6b7280;">(Required for doctors)</small></label>
        <select name="department_id">
          <option value="">-- Select Department --</option>
          <?php if (!empty($departments)): ?>
            <?php foreach ($departments as $dept): ?>
              <option value="<?= $dept['id'] ?>" <?= ($user['department_id'] ?? null) == $dept['id'] ? 'selected' : '' ?>>
                <?= esc($dept['name']) ?> (<?= esc($dept['code']) ?>)
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
        <small style="color: #6b7280; margin-top: 4px;">
          Assign this doctor to a department. This helps organize doctors by medical specialty.
        </small>
      </div>

      <div class="flex-row" style="margin-top: 20px;">
        <button type="submit" class="btn">💾 Save Changes</button>
        <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
      </div>

      <script>
        // Show/hide department and specialization fields based on role
        document.getElementById('roleSelect').addEventListener('change', function() {
          const isDoctor = this.value === 'doctor';
          document.getElementById('departmentRow').style.display = isDoctor ? 'flex' : 'none';
          document.getElementById('specializationRow').style.display = isDoctor ? 'flex' : 'none';
        });
      </script>
    </form>
  </main>

</body>
</html>
