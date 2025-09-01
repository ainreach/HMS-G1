<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - FIFTY 50 MEDTECHIE</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary: #1d4ed8;
      --primary-light: #3b82f6;
      --primary-dark: #1e40af;
      --secondary: #2563eb;
      --text: #0f172a;
      --text-light: #475569;
      --muted: #64748b;
      --border: #e5e7eb;
      --bg: #f8fafc;
      --card-bg: #ffffff;
      --success: #22c55e;
      --danger: #ef4444;
      --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    body {
      font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text);
      position: relative;
      overflow-x: hidden;
    }

    /* Animated background elements */
    .bg-animation {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 1;
    }

    .floating-shapes {
      position: absolute;
      width: 100px;
      height: 100px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    .shape-1 { top: 20%; left: 10%; animation-delay: 0s; }
    .shape-2 { top: 60%; left: 80%; animation-delay: 2s; transform: scale(0.8); }
    .shape-3 { top: 80%; left: 20%; animation-delay: 4s; transform: scale(1.2); }
    .shape-4 { top: 10%; right: 20%; animation-delay: 1s; transform: scale(0.6); }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }

    .login-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 420px;
      padding: 0 20px;
      animation: slideInUp 0.8s ease-out;
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-card {
      background: var(--card-bg);
      border-radius: 20px;
      box-shadow: var(--shadow-lg);
      overflow: hidden;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: cardGlow 0.6s ease-out 0.3s both;
    }

    @keyframes cardGlow {
      from {
        box-shadow: var(--shadow);
      }
      to {
        box-shadow: var(--shadow-lg), 0 0 0 1px rgba(29, 78, 216, 0.1);
      }
    }

    .login-header {
      background: linear-gradient(135deg, var(--primary), var(--primary-light));
      padding: 40px 30px 30px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .login-header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .brand-logo {
      position: relative;
      z-index: 2;
    }

    .brand-logo i {
      font-size: 48px;
      color: white;
      margin-bottom: 15px;
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    .brand-title {
      color: white;
      font-size: 24px;
      font-weight: 800;
      margin-bottom: 5px;
      letter-spacing: 0.5px;
    }

    .brand-subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: 14px;
      font-style: italic;
    }

    .login-body {
      padding: 40px 30px;
    }

    .welcome-text {
      text-align: center;
      margin-bottom: 30px;
    }

    .welcome-text h2 {
      color: var(--text);
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .welcome-text p {
      color: var(--text-light);
      font-size: 16px;
    }

    .form-group {
      margin-bottom: 25px;
      position: relative;
    }

    .form-group label {
      display: block;
      color: var(--text);
      font-weight: 600;
      margin-bottom: 8px;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .input-wrapper {
      position: relative;
    }

    .form-control {
      width: 100%;
      padding: 16px 20px 16px 50px;
      border: 2px solid var(--border);
      border-radius: 12px;
      font-size: 16px;
      background: var(--card-bg);
      color: var(--text);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      outline: none;
    }

    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.1);
      transform: translateY(-2px);
    }

    .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      font-size: 18px;
      transition: color 0.3s ease;
    }

    .form-control:focus + .input-icon {
      color: var(--primary);
    }

    .login-btn {
      width: 100%;
      padding: 18px;
      background: linear-gradient(135deg, var(--primary), var(--primary-light));
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .login-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .login-btn:hover::before {
      left: 100%;
    }

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(29, 78, 216, 0.3);
    }

    .login-btn:active {
      transform: translateY(0);
    }

    .alert {
      padding: 16px 20px;
      border-radius: 12px;
      margin-bottom: 25px;
      font-size: 14px;
      font-weight: 500;
      animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-danger {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.2);
      color: var(--danger);
    }

    .help-text {
      text-align: center;
      color: var(--muted);
      font-size: 14px;
      margin-top: 20px;
      padding: 20px;
      background: rgba(100, 116, 139, 0.05);
      border-radius: 12px;
      border-left: 4px solid var(--primary);
    }

    .loading {
      display: none;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .spinner {
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .home-button-wrapper {
      text-align: center;
      margin-top: 25px;
      padding-top: 20px;
      border-top: 1px solid var(--border);
    }

    .home-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      background: transparent;
      color: var(--muted);
      text-decoration: none;
      border: 2px solid var(--border);
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .home-btn:hover {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(29, 78, 216, 0.2);
    }

    .home-btn i {
      font-size: 16px;
      transition: transform 0.3s ease;
    }

    .home-btn:hover i {
      transform: scale(1.1);
    }

    /* Responsive design */
    @media (max-width: 480px) {
      .login-container {
        padding: 0 15px;
      }
      
      .login-header {
        padding: 30px 20px 25px;
      }
      
      .login-body {
        padding: 30px 20px;
      }
      
      .brand-title {
        font-size: 20px;
      }
      
      .welcome-text h2 {
        font-size: 24px;
      }

      .home-btn {
        padding: 10px 20px;
        font-size: 13px;
      }
    }
  </style>
</head>
<body>
  <div class="bg-animation">
    <div class="floating-shapes shape-1"></div>
    <div class="floating-shapes shape-2"></div>
    <div class="floating-shapes shape-3"></div>
    <div class="floating-shapes shape-4"></div>
  </div>

  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="brand-logo">
          <i class="fas fa-heartbeat"></i>
          <div class="brand-title">FIFTY 50 MEDTECHIE</div>
          <div class="brand-subtitle">"Trust Us... If You're Feeling Lucky."</div>
        </div>
      </div>

      <div class="login-body">
        <div class="welcome-text">
          <h2>Welcome Back</h2>
          <p>Please sign in to your account</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <?= esc(session()->getFlashdata('error')) ?>
          </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('login') ?>" id="loginForm">
          <div class="form-group">
            <label for="username">Username</label>
            <div class="input-wrapper">
              <input type="text" id="username" name="username" class="form-control" autocomplete="username" required>
              <i class="fas fa-user input-icon"></i>
            </div>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
              <input type="password" id="password" name="password" class="form-control" autocomplete="current-password" required>
              <i class="fas fa-lock input-icon"></i>
            </div>
          </div>

          <button type="submit" class="login-btn">
            <span class="btn-text">Sign In</span>
            <div class="loading">
              <div class="spinner"></div>
            </div>
          </button>
        </form>

        <div class="help-text">
          <i class="fas fa-info-circle"></i>
          We'll automatically detect your role and redirect you to the appropriate dashboard.
        </div>

        <div class="home-button-wrapper">
          <a href="<?= site_url('/') ?>" class="home-btn">
            <i class="fas fa-home"></i>
            Back to Home
          </a>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Add loading animation on form submit
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const btn = this.querySelector('.login-btn');
      const btnText = btn.querySelector('.btn-text');
      const loading = btn.querySelector('.loading');
      
      btnText.style.opacity = '0';
      loading.style.display = 'block';
      btn.disabled = true;
    });

    // Add input focus animations
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
      });
    });

    // Add typing animation for better UX
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('input', function() {
        if (this.value.length > 0) {
          this.style.borderColor = 'var(--success)';
          this.nextElementSibling.style.color = 'var(--success)';
        } else {
          this.style.borderColor = 'var(--border)';
          this.nextElementSibling.style.color = 'var(--muted)';
        }
      });
    });
  </script>
</body>
</html>
