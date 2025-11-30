<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Physicians - FIFTY 50 MEDTECHIE</title>
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

    .coming {
      text-align: center;
      font-size: 1.2rem;
      color: #334155;
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

    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <header>
    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Hospital Logo">
    <h1>Our Physicians</h1>
    <p>Trusted, Skilled, and Ready to Care for You</p>
  </header>

  <main>
    <a href="<?= site_url('/') ?>" class="back-btn">← Back to Homepage</a>
    <h1>Physicians</h1>
    <p class="coming">Directory coming soon. Stay tuned!</p>
  </main>

</body>
</html>
