<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Nurse</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head><body>
<main class="auth">
  <h1>Nurse Login</h1>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= site_url('login/nurse') ?>" class="auth-form">
    <label>Username
      <input type="text" name="username" required>
    </label>
    <label>Password
      <input type="password" name="password" required>
    </label>
    <label>Branch
      <input type="text" name="branch" placeholder="Main">
    </label>
    <button type="submit">Login</button>
  </form>
  <p><a href="<?= site_url('login') ?>">Back to role selection</a></p>
</main>
</body></html>
