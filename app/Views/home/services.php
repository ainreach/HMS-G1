<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Services • FIFTY 50 MEDTECHIE</title>

  <base href="<?= rtrim(base_url(), '/') ?>/">

  <!-- FULL CSS -->
  <style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f3f8ff;
        color: #1f2c45;
    }

    .services-container {
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
        background: #ffffff;
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

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .service-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: fadeIn 1s ease-out;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .service-title {
        font-size: 20px;
        font-weight: 600;
        color: #0a4dad;
        margin: 0 0 10px;
    }

    .service-description {
        font-size: 15px;
        color: #4a5a75;
        line-height: 1.6;
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

  <main class="services-container">

      <section class="header-section">
          <h1 class="title">Services</h1>
          <p class="subtitle">A complete list of available hospital services will be displayed here.</p>
      </section>

      <div class="services-grid">
          <div class="service-card">
              <h3 class="service-title">Emergency Care</h3>
              <p class="service-description">Our 24/7 emergency department provides immediate care for critical conditions, staffed by expert physicians and nurses.</p>
          </div>

          <div class="service-card">
              <h3 class="service-title">Surgical Services</h3>
              <p class="service-description">Offering a wide range of advanced surgical procedures, from minimally invasive to complex surgeries, in state-of-the-art operating rooms.</p>
          </div>

          <div class="service-card">
              <h3 class="service-title">Cardiology</h3>
              <p class="service-description">Comprehensive heart care, including diagnostics, treatment, and rehabilitation for all cardiovascular conditions.</p>
          </div>
      </div>

      <div class="btn-container">
          <a href="<?= base_url('/') ?>" class="back-btn">← Back to Homepage</a>
      </div>

  </main>

</body>
</html>
