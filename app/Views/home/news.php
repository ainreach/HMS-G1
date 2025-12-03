<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Physicians • FIFTY 50 MEDTECHIE</title>

  <base href="<?= rtrim(base_url(), '/') ?>/">

  <!-- FULL CSS -->
  <style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f3f8ff;
        color: #1f2c45;
    }

    .physician-container {
        max-width: 950px;
        margin: 40px auto;
        padding: 20px;
        animation: fadeUp 0.8s ease-out;
    }

    .header-section {
        text-align: center;
        padding: 20px 10px;
    }

    .title {
        font-size: 42px;
        font-weight: 700;
        color: #0a4dad;
        animation: slideDown 0.7s ease-out;
    }

    .subtitle {
        font-size: 18px;
        margin-top: 5px;
        color: #3d4d6d;
    }

    /* CARD */
    .empty-card {
        text-align: center;
        background: #fff;
        border-radius: 20px;
        padding: 45px 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        margin-top: 40px;
        animation: fadeIn 1s ease-out;
    }

    .empty-card img {
        width: 180px;
        margin-bottom: 20px;
        opacity: 0.9;
    }

    .empty-card h2 {
        font-size: 26px;
        color: #0a4dad;
    }

    .empty-card p {
        font-size: 16px;
        color: #4a5a75;
    }

    .empty-card:hover {
        transform: translateY(-4px);
        transition: 0.3s ease;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }

    /* BACK BUTTON */
    .btn-container {
        text-align: center;
        margin-top: 35px;
    }

    .back-btn {
        display: inline-block;
        padding: 12px 25px;
        color: #fff;
        background-color: #0a4dad;
        border-radius: 30px;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        transition: 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .back-btn:hover {
        background-color: #083e88;
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    /* ANIMATIONS */
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-18px); }
        to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>

  <main class="physician-container">

      <section class="header-section">
          <h1 class="title">Physicians</h1>
          <p class="subtitle">A complete directory of our medical specialists will be available soon.</p>
      </section>

      <div class="empty-card fade-in">
          <img src="<?= base_url('assets/img/doctor-placeholder.png') ?>" alt="Coming Soon">
          <h2>Directory Coming Soon</h2>
          <p>We are currently updating our list of physicians to serve you better.</p>
      </div>

      <div class="btn-container">
          <a href="<?= base_url('/') ?>" class="back-btn">← Back to Homepage</a>
      </div>

  </main>

</body>
</html>
