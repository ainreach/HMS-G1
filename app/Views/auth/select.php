<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Select Role - FIFTY 50 MEDTECHIE</title>
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

    .role-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 1000px;
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

    .role-card {
      background: var(--card);
      border-radius: var(--radius);
      box-shadow: var(--shadow-lg);
      overflow: hidden;
      border: 1px solid var(--border);
      backdrop-filter: blur(10px);
      position: relative;
    }

    .role-card::before {
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

    .roles-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      margin-top: 24px;
    }

    .role-item {
      background: #ffffff;
      border: 2px solid var(--border);
      border-radius: 16px;
      padding: 24px;
      text-decoration: none;
      color: inherit;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      animation: fadeInUp 0.6s ease-out calc(0.6s + var(--delay, 0s)) both;
    }

    .role-item:nth-child(1) { --delay: 0.1s; }
    .role-item:nth-child(2) { --delay: 0.2s; }
    .role-item:nth-child(3) { --delay: 0.3s; }
    .role-item:nth-child(4) { --delay: 0.4s; }
    .role-item:nth-child(5) { --delay: 0.5s; }
    .role-item:nth-child(6) { --delay: 0.6s; }
    .role-item:nth-child(7) { --delay: 0.7s; }
    .role-item:nth-child(8) { --delay: 0.8s; }

    .role-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), var(--primary-600));
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .role-item:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(14, 165, 233, 0.15);
      border-color: var(--primary);
    }

    .role-item:hover::before {
      transform: scaleX(1);
    }

    .role-icon {
      width: 60px;
      height: 60px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 16px;
      font-size: 24px;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .role-icon::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-600) 100%);
      opacity: 0.9;
    }

    .role-icon i {
      position: relative;
      z-index: 1;
    }

    .role-item:nth-child(1) .role-icon::before { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .role-item:nth-child(2) .role-icon::before { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
    .role-item:nth-child(3) .role-icon::before { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .role-item:nth-child(4) .role-icon::before { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .role-item:nth-child(5) .role-icon::before { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    .role-item:nth-child(6) .role-icon::before { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
    .role-item:nth-child(7) .role-icon::before { background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); }
    .role-item:nth-child(8) .role-icon::before { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }

    .role-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 8px;
      letter-spacing: -0.3px;
    }

    .role-description {
      color: var(--muted);
      font-size: 14px;
      font-weight: 500;
      line-height: 1.5;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 32px;
      padding: 12px 24px;
      background: transparent;
      color: var(--primary);
      text-decoration: none;
      border: 2px solid var(--primary);
      border-radius: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
      animation: fadeInUp 0.6s ease-out 1.2s both;
    }

    .back-link:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(14, 165, 233, 0.2);
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .roles-grid {
        grid-template-columns: 1fr;
        gap: 16px;
      }
      
      .card-header, .card-body {
        padding: 24px 20px;
      }
      
      .logo-section h1 {
        font-size: 20px;
      }
      
      .role-item {
        padding: 20px;
      }
    }

    @media (max-width: 480px) {
      .role-container {
        max-width: 100%;
        margin: 0;
      }
    }
  </style>
</head>
<body>
  <div class="role-container">
    <div class="role-card">
      <div class="card-header">
        <div class="logo-section">
          <img src="<?= base_url('assets/img/logo.png') ?>" alt="FIFTY 50 MEDTECHIE" />
          <h1>FIFTY 50 MEDTECHIE</h1>
        </div>
        <p class="welcome-text">Select your role to continue to login</p>
      </div>
      
      <div class="card-body">
        <div class="roles-grid">
          <a class="role-item" href="<?= site_url('login/admin') ?>">
            <div class="role-icon">
              <i class="fas fa-crown"></i>
            </div>
            <h3 class="role-title">Administrator</h3>
            <p class="role-description">Full system access and management</p>
          </a>
          
          <a class="role-item" href="<?= site_url('login/doctor') ?>">
            <div class="role-icon">
              <i class="fas fa-user-md"></i>
            </div>
            <h3 class="role-title">Doctor</h3>
            <p class="role-description">Patient care and medical orders</p>
          </a>
          
          <a class="role-item" href="<?= site_url('login/nurse') ?>">
            <div class="role-icon">
              <i class="fas fa-user-nurse"></i>
            </div>
            <h3 class="role-title">Nurse</h3>
            <p class="role-description">Patient care and updates</p>
          </a>
          
          <a class="role-item" href="<?= site_url('login/receptionist') ?>">
            <div class="role-icon">
              <i class="fas fa-user-tie"></i>
            </div>
            <h3 class="role-title">Receptionist</h3>
            <p class="role-description">Registration and appointments</p>
          </a>
          
          <a class="role-item" href="<?= site_url('login/lab_staff') ?>">
            <div class="role-icon">
              <i class="fas fa-flask"></i>
            </div>
            <h3 class="role-title">Lab Staff</h3>
            <p class="role-description">Laboratory requests and results</p>
          </a>
          
          <a class="role-item" href="<?= site_url('login/pharmacist') ?>">
            <div class="role-icon">
              <i class="fas fa-pills"></i>
            </div>
            <h3 class="role-title">Pharmacist</h3>
            <p class="role-description">Medicine dispensing and inventory</p>
          </a>
          
          <a class="role-item" href="<?= site_url('login/accountant') ?>">
            <div class="role-icon">
              <i class="fas fa-calculator"></i>
            </div>
            <h3 class="role-title">Accountant</h3>
            <p class="role-description">Billing and financial reports</p>
          </a>
          
          <a class="role-item" href="<?= site_url('login/it_staff') ?>">
            <div class="role-icon">
              <i class="fas fa-laptop-code"></i>
            </div>
            <h3 class="role-title">IT Staff</h3>
            <p class="role-description">System maintenance and support</p>
          </a>
        </div>
        
        <div style="text-align: center;">
          <a href="<?= site_url('login') ?>" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Login
          </a>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Add hover effects and animations
    document.querySelectorAll('.role-item').forEach(item => {
      item.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px) scale(1.02)';
      });
      
      item.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });
  </script>
</body>
</html>
