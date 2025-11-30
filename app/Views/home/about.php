<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About Us • FIFTY 50 MEDTECHIE</title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f4faff;
      color: #003366;
      overflow-x: hidden;
    }
    header {
      background: linear-gradient(90deg, #0a6cff, #2a9bff);
      color: white;
      padding: 40px 20px;
      text-align: center;
      animation: fadeInDown 1s ease;
    }
    header img {
      height: 80px;
      margin-bottom: 10px;
      animation: fadeInDown 1.2s ease;
    }
    header h1 {
      margin: 0;
      font-size: 2.4rem;
    }
    header p {
      font-size: 1.1rem;
      margin-top: 8px;
    }
    main {
      max-width: 1000px;
      margin: 30px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      animation: fadeInUp 1s ease;
      position: relative;
    }
    .section {
      margin-top: 20px;
      animation: floatUp 1.2s ease;
    }
    h2 {
      color: #0a6cff;
    }
    footer {
      text-align: center;
      padding: 20px;
      color: #003366;
      margin-top: 20px;
      font-size: 0.9rem;
    }
    footer a {
      color: #0a6cff;
      text-decoration: none;
    }
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes floatUp {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <header>
    <img src="<?= base_url('assets/img/logo.png') ?>" alt="FIFTY 50 MEDTECHIE">
    <h1>About FIFTY 50 MEDTECHIE</h1>
    <p>"Trust Us... If You're Feeling Lucky."</p>
  </header>

  <main>
    <a href="<?= site_url('/') ?>" class="back-btn" style="position: absolute; top: -50px; left: 0; display: inline-block; margin-bottom: 15px; padding: 10px 16px; background: #1d4ed8; color: white; border-radius: 8px; text-decoration: none; font-weight: 600; transition: 0.3s;" onmouseover="this.style.transform='translateX(-4px)'; this.style.background='#0d3ec9';" onmouseout="this.style.transform=''; this.style.background='#1d4ed8';">← Back to Homepage</a>
    <h2>Who We Are</h2>
    <p>
      FIFTY 50 MEDTECHIE is dedicated to building modern, reliable, and user-friendly healthcare systems
      designed to help hospitals, clinics, staff, and patients operate with ease and confidence.
    </p>

    <div class="section">
      <h2>Our Commitment</h2>
      <p>
        We aim to provide fast, efficient, and technology-driven solutions that enhance patient care,
        streamline hospital operations, and support medical professionals in their daily tasks.
      </p>
    </div>

    <div class="section">
      <h2>Why Choose Us?</h2>
      <p>
        With a strong focus on simplicity and performance, our system is crafted to minimize delays,
        reduce workload, and make healthcare processes smoother for everyone involved.
      </p>
    </div>
  </main>

  <footer>
    <a href="<?= site_url('privacy') ?>">Privacy Policy</a> &nbsp;|&nbsp;
    <a href="<?= site_url('terms') ?>">Terms of Use</a>
  </footer>
</body>
</html>