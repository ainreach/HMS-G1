<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - FIFTY 50 MEDTECHIE</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    :root {
      --primary: #0ea5e9;
      --primary-600: #0284c7;
      --primary-700: #0369a1;
      --bg: #f5f7fb;
      --card: #ffffff;
      --text: #0f172a;
      --muted: #64748b;
      --border: #e2e8f0;
      --shadow: 0 20px 40px rgba(2,6,23,0.08);
      --shadow-lg: 0 25px 50px rgba(2,6,23,0.12);
      --radius: 16px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background: linear-gradient(135deg, #f5f7fb 0%, #e0f2fe 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
    }

    /* Animated background elements */
    body::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at 20% 80%, rgba(14, 165, 233, 0.1) 0%, transparent 50%),
                  radial-gradient(circle at 80% 20%, rgba(2, 132, 199, 0.1) 0%, transparent 50%);
      animation: float 20s ease-in-out infinite;
      z-index: 0;
    }

    @keyframes float {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      33% { transform: translate(30px, -30px) rotate(120deg); }
      66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .login-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 420px;
      animation: slideUp 0.8s ease-out;
    }

    @keyframes slideUp {
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
      background: var(--card);
      border-radius: var(--radius);
      box-shadow: var(--shadow-lg);
      overflow: hidden;
      border: 1px solid var(--border);
      backdrop-filter: blur(10px);
      position: relative;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--primary-600));
    }

    .card-header {
      padding: 32px 32px 24px;
      text-align: center;
      background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    }

    .logo-section {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin-bottom: 20px;
      animation: fadeInDown 0.6s ease-out 0.2s both;
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo-section img {
      height: 40px;
      width: auto;
      filter: drop-shadow(0 4px 8px rgba(14, 165, 233, 0.2));
    }

    .logo-section h1 {
      font-size: 24px;
      font-weight: 800;
      color: var(--text);
      letter-spacing: -0.5px;
    }

    .welcome-text {
      color: var(--muted);
      font-size: 16px;
      font-weight: 500;
      animation: fadeInUp 0.6s ease-out 0.4s both;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card-body {
      padding: 32px;
    }

    .form-group {
      margin-bottom: 24px;
      animation: fadeInUp 0.6s ease-out calc(0.6s + var(--delay, 0s)) both;
    }

    .form-group:nth-child(1) { --delay: 0.1s; }
    .form-group:nth-child(2) { --delay: 0.2s; }

    .form-label {
      display: block;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 8px;
      font-size: 14px;
      letter-spacing: 0.2px;
    }

    .form-input {
      width: 100%;
      padding: 16px 20px;
      border: 2px solid var(--border);
      border-radius: 12px;
      font-size: 16px;
      font-weight: 500;
      background: #fafbfc;
      transition: all 0.3s ease;
      outline: none;
      position: relative;
    }

    .form-input:focus {
      border-color: var(--primary);
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
      transform: translateY(-2px);
    }

    .form-input::placeholder {
      color: var(--muted);
      font-weight: 400;
    }

    .login-btn {
      width: 100%;
      padding: 16px 24px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-600) 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      margin-top: 8px;
      animation: fadeInUp 0.6s ease-out 0.8s both;
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

    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 24px rgba(14, 165, 233, 0.3);
    }

    .login-btn:hover::before {
      left: 100%;
    }

    .login-btn:active {
      transform: translateY(0);
    }

    .alert {
      padding: 16px 20px;
      border-radius: 12px;
      margin-bottom: 24px;
      animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }

    .alert-danger {
      background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
      border: 2px solid #fecaca;
      color: #991b1b;
      font-weight: 500;
    }

    .info-text {
      text-align: center;
      color: var(--muted);
      font-size: 14px;
      font-weight: 500;
      margin-top: 24px;
      padding: 16px;
      background: #f8fafc;
      border-radius: 12px;
      border: 1px solid var(--border);
      animation: fadeInUp 0.6s ease-out 1s both;
    }

    .role-select-link {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 24px;
      background: transparent;
      color: var(--primary);
      text-decoration: none;
      border: 2px solid var(--primary);
      border-radius: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
      text-align: center;
      width: 100%;
      animation: fadeInUp 0.6s ease-out 1.2s both;
    }

    .role-select-link:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(14, 165, 233, 0.2);
    }

    /* Responsive design */
    @media (max-width: 480px) {
      .login-container {
        max-width: 100%;
        margin: 0;
      }
      
      .card-header, .card-body {
        padding: 24px 20px;
      }
      
      .logo-section h1 {
        font-size: 20px;
      }
    }

    /* Loading animation */
    .loading {
      display: none;
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s ease-in-out infinite;
      margin-right: 8px;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .login-btn.loading .loading {
      display: inline-block;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="card-header">
        <div class="logo-section">
          <img src="<?= base_url('assets/img/logo.png') ?>" alt="FIFTY 50 MEDTECHIE" />
          <h1>FIFTY 50 MEDTECHIE</h1>
        </div>
        <p class="welcome-text">Welcome back! Please sign in to your account</p>
      </div>
      
      <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <?= esc(session()->getFlashdata('error')) ?>
          </div>
        <?php endif; ?>
        
        <form method="post" action="<?= site_url('login') ?>" id="loginForm">
          <div class="form-group">
            <label class="form-label" for="username">
              <i class="fas fa-user"></i> Username
            </label>
            <input 
              type="text" 
              id="username" 
              name="username" 
              class="form-input" 
              placeholder="Enter your username"
              autocomplete="username" 
              required
            >
          </div>
          
          <div class="form-group">
            <label class="form-label" for="password">
              <i class="fas fa-lock"></i> Password
            </label>
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="form-input" 
              placeholder="Enter your password"
              autocomplete="current-password" 
              required
            >
          </div>
          
          <button type="submit" class="login-btn" id="loginBtn">
            <span class="loading"></span>
            <i class="fas fa-sign-in-alt"></i>
            Sign In
          </button>
        </form>
        
        <div class="info-text">
          <i class="fas fa-info-circle"></i>
          We'll detect your role automatically based on your account.
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function() {
      const btn = document.getElementById('loginBtn');
      btn.classList.add('loading');
      btn.innerHTML = '<span class="loading"></span><i class="fas fa-spinner fa-spin"></i> Signing In...';
      btn.disabled = true;
    });

    // Add focus animations
    document.querySelectorAll('.form-input').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
      });
    });
  </script>
</body>
</html>
