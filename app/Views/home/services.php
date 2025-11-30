<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Services - FIFTY 50 MEDTECHIE</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4faff;
      color: #003366;
      overflow-x: hidden;
    }

    header {
      background: linear-gradient(90deg, #0a6cff, #2a9bff);
      padding: 30px 20px;
      text-align: center;
      color: white;
      animation: fadeInDown 1s ease;
    }

    header img {
      height: 70px;
      margin-bottom: 10px;
      animation: fadeInDown 1.2s ease;
    }

    main {
      max-width: 1000px;
      margin: 30px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      animation: fadeInUp 1s ease;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
    }

    .back-btn {
      display: inline-block;
      margin-bottom: 15px;
      padding: 10px 16px;
      background: #1d4ed8;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }

    .back-btn:hover {
      transform: translateX(-4px);
      background: #0d3ec9;
    }

    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .card {
      background: #e8f1ff;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      transition: 0.3s;
      animation: floatUp 1.2s ease;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 20px rgba(0, 102, 255, 0.2);
    }

    .card i {
      font-size: 32px;
      color: #1d4ed8;
      margin-bottom: 10px;
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
    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Hospital Logo">
    <h1>Our Services</h1>
    <p>Compassionate Care and Modern Medical Solutions</p>
  </header>

  <main>
    <a href="<?= site_url('/') ?>" class="back-btn">← Back to Homepage</a>
    <h1>Services</h1>

    <div class="services-grid">
      <div class="card">
        <i class="fa-solid fa-stethoscope"></i>
        <h3>General Checkup</h3>
        <p>Complete health assessments by licensed professionals.</p>
      </div>

      <div class="card">
        <i class="fa-solid fa-truck-medical"></i>
        <h3>Emergency Care</h3>
        <p>24/7 emergency response with trained medical staff.</p>
      </div>

      <div class="card">
        <i class="fa-solid fa-vials"></i>
        <h3>Laboratory Tests</h3>
        <p>Accurate diagnostics with modern lab equipment.</p>
      </div>

      <div class="card">
        <i class="fa-solid fa-x-ray"></i>
        <h3>Radiology</h3>
        <p>High-quality imaging including X-ray and ultrasound.</p>
      </div>
    </div>

  </main>

</body>
</html>