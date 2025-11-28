<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - FIFTY 50 MEDTECHIE</title>
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
      <a href="<?= site_url('login') ?>">Login</a>
    </nav>
    <div class="nav-right">
      <div class="search-inline"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search..." aria-label="Search" /></div>
    </div>
  </header>

  <main>
    <div class="login-box">
      <h2>Create Account</h2>
      <form id="registerForm">
        <label>Full Name:</label>
        <input type="text" id="fullname" required>
        <label>Username:</label>
        <input type="text" id="regUsername" required>
        <label>Password:</label>
        <input type="password" id="regPassword" required>
        <label>Role:</label>
        <select id="role" required>
          <option value="Patient">Patient</option>
          <option value="Doctor">Doctor</option>
          <option value="Admin">Admin</option>
        </select>
        <button type="submit">Register</button>
      </form>
      <p>Already have an account? <a href="<?= site_url('login') ?>">Login here</a></p>
    </div>
  </main>

  <footer>
    <a href="<?= site_url('privacy') ?>">Privacy Policy</a> | 
    <a href="<?= site_url('terms') ?>">Terms of Use</a>
  </footer>

  <script>
    // Debug: Verify script is loading
    console.log('Registration page loaded');
  </script>
  <script>
    // Provide base URL for static JS
    window.APP_BASE = '<?= rtrim(site_url('/'), '/') ?>/';
    // Provide site_url root; includes index.php automatically when required
    window.SITE_URL = '<?= rtrim(site_url(), '/') ?>';
  </script>
  <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>


