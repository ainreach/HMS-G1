<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Patient</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
<header class="dash-topbar"><div class="topbar-inner">
  <a href="<?= site_url('dashboard/receptionist') ?>" class="menu-btn">Back</a>
  <h1 style="margin:0;font-size:1.1rem">Register Patient</h1>
</div></header>
<main class="content" style="max-width:820px;margin:20px auto">
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= site_url('reception/patients') ?>" class="form">
    <?= csrf_field() ?>
    <div class="grid" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <label>First Name
        <input type="text" name="first_name" value="<?= old('first_name') ?>" required>
      </label>
      <label>Last Name
        <input type="text" name="last_name" value="<?= old('last_name') ?>" required>
      </label>
      <label>Date of Birth
        <input type="date" name="date_of_birth" value="<?= old('date_of_birth') ?>">
      </label>
      <label>Gender
        <select name="gender">
          <option value="">Select</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </label>
      <label>Phone
        <input type="text" name="phone" value="<?= old('phone') ?>">
      </label>
      <label>Email
        <input type="email" name="email" value="<?= old('email') ?>">
      </label>
    </div>
    <label>Address
      <textarea name="address" rows="3"><?= old('address') ?></textarea>
    </label>
    <div style="margin-top:12px;display:flex;gap:10px">
      <button type="submit" class="btn btn-primary">Save</button>
      <a class="btn" href="<?= site_url('dashboard/receptionist') ?>">Cancel</a>
    </div>
  </form>
</main>
</body></html>
