<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - FIFTY 50 MEDTECHIE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <header class="topnav">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="FIFTY 50 MEDTECHIE" /> <span>FIFTY 50 MEDTECHIE</span></div>
    <nav class="nav-links">
      <a href="<?= site_url('/') ?>">Home</a>
      <a href="<?= site_url('about') ?>">About Us</a>
      <a href="<?= site_url('login') ?>" class="active">Login</a>
    </nav>
    <div class="nav-right">
      <div class="search-inline"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search..." aria-label="Search" /></div>
    </div>
  </header>

  <main>
    <div class="login-box">
      <h2>Login</h2>
      <form id="loginForm">
        <label>Username:</label>
        <input type="text" id="username" required>
        <label>Password:</label>
        <input type="password" id="password" required>
        <button type="submit">Login</button>
        <button type="button" onclick="createAccount()">Create Account</button>
      </form>
    </div>
  </main>

  <footer>
    <a href="<?= site_url('privacy') ?>">Privacy Policy</a> | 
    <a href="<?= site_url('terms') ?>">Terms of Use</a>
  </footer>

  <script>
    // Provide base URL for static JS
    window.APP_BASE = '<?= rtrim(site_url('/'), '/') ?>/';
  </script>
  <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>


