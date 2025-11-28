<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About Us • FIFTY 50 MEDTECHIE</title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <header class="topnav">
    <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="FIFTY 50 MEDTECHIE" /> <span>FIFTY 50 MEDTECHIE</span></div>
    <nav class="nav-links">
      <a href="<?= site_url('/') ?>">Home</a>
      <a href="<?= site_url('about') ?>" class="active">About Us</a>
      <a href="<?= site_url('login') ?>">Login</a>
    </nav>
    <div class="nav-right">
      <div class="search-inline"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search..." aria-label="Search" /></div>
    </div>
  </header>

  <main>
    <div class="welcome-box" style="max-width:800px;">
      <h1>About FIFTY 50 MEDTECHIE</h1>
      <p>We build reliable and simple healthcare tools to help clinics and patients every day.</p>
    </div>
  </main>

  <footer>
    <a href="<?= site_url('privacy') ?>">Privacy Policy</a> |
    <a href="<?= site_url('terms') ?>">Terms of Use</a>
  </footer>
</body>
</html>


