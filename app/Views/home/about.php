<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About Us • FIFTY 50 MEDTECHIE</title>

  <base href="<?= rtrim(base_url(), '/') ?>/">

  <!-- FULL CSS -->
  <style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f3f8ff;
        color: #1f2c45;
    }

    .about-container {
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
    .about-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        margin-top: 40px;
        animation: fadeIn 1s ease-out;
        line-height: 1.8;
        color: #3e4b63;
        font-size: 16px;
    }

    .about-card h2 {
        font-size: 26px;
        font-weight: 600;
        color: #0a4dad;
        margin-bottom: 15px;
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

  <main class="about-container">

      <section class="header-section">
          <h1 class="title">About Us</h1>
          <p class="subtitle">Learn more about FIFTY 50 MEDTECHIE and our commitment to healthcare innovation.</p>
      </section>

      <div class="about-card">
          <h2>Who We Are</h2>
          <p>
              FIFTY 50 MEDTECHIE is a healthcare technology provider dedicated to building efficient, 
              reliable, and user-friendly medical systems. Our mission is to support hospitals, clinics, 
              and medical professionals by delivering digital tools that simplify operations and 
              improve patient care.
          </p>

          <h2>Our Purpose</h2>
          <p>
              We believe healthcare deserves software that is intuitive, modern, and built with 
              precision. Our systems aim to reduce administrative workload, speed up processes, 
              provide transparency, and help your medical staff focus on what matters most—
              delivering quality healthcare.
          </p>

          <h2>Our Commitment</h2>
          <p>
              We continuously innovate to bring advanced yet easy-to-use solutions. Whether for 
              patient records, hospital management, diagnostics, or reporting, we strive to create 
              tools that empower both staff and patients every single day.
          </p>
      </div>

      <div class="btn-container">
          <a href="<?= base_url('/') ?>" class="back-btn">← Back to Homepage</a>
      </div>

  </main>

</body>
</html>
