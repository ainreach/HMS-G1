<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - FIFTY 50 MEDTECHIE</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <style>
    :root{--border:#e5e7eb;--muted:#6b7280;--bg:#f9fafb}
    body{background:var(--bg)}
    .container{max-width:420px;margin:3rem auto;padding:0 1rem}
    .card{background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden}
    .card h1{font-size:1.25rem;margin:0}
    .card-head{padding:18px 20px;border-bottom:1px solid var(--border)}
    .card-body{padding:18px 20px}
    .muted{color:var(--muted)}
    form{display:grid;gap:12px}
    label{display:grid;gap:6px;font-size:.95rem}
    input[type=text],input[type=password]{padding:10px 12px;border:1px solid var(--border);border-radius:8px}
    button{padding:10px 14px;border:1px solid #111827;background:#111827;color:#fff;border-radius:8px;cursor:pointer}
    button:hover{opacity:.92}
    .alert{padding:10px 12px;border-radius:8px}
    .alert-danger{background:#fee2e2;border:1px solid #fecaca;color:#991b1b}
  </style>
</head><body>
<main class="container">
  <section class="card">
    <div class="card-head"><h1>Sign in</h1></div>
    <div class="card-body">
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <form method="post" action="<?= site_url('login') ?>">
        <label>Username
          <input type="text" name="username" autocomplete="username" required>
        </label>
        <label>Password
          <input type="password" name="password" autocomplete="current-password" required>
        </label>
        <button type="submit">Login</button>
      </form>
      <p class="muted" style="margin-top:10px">We'll detect your role automatically based on your account.</p>
    </div>
  </section>
</main>
</body></html>
