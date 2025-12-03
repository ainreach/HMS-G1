<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>News • FIFTY 50 MEDTECHIE</title>

  <base href="<?= rtrim(base_url(), '/') ?>/">

  <!-- FULL PAGE CSS -->
  <style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f3f8ff;
        color: #1f2c45;
    }

    .news-container {
        max-width: 950px;
        margin: 40px auto;
        padding: 20px;
        animation: fadeUp 0.8s ease-out;
    }

    .header-section {
        text-align: center;
        padding: 20px 10px;
    }

    .news-title {
        font-size: 42px;
        font-weight: 700;
        color: #0a4dad;
        animation: slideDown 0.7s ease-out;
    }

    .news-subtitle {
        font-size: 18px;
        margin-top: 5px;
        color: #3d4d6d;
    }

    .no-news-card {
        text-align: center;
        background: #ffffff;
        border-radius: 20px;
        padding: 40px 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        margin-top: 40px;
        animation: fadeIn 1s ease-out;
    }

    .no-news-card img {
        width: 180px;
        margin-bottom: 20px;
        opacity: 0.9;
    }

    .no-news-card h2 {
        font-size: 26px;
        color: #0a4dad;
    }

    .no-news-card p {
        font-size: 16px;
        color: #4a5a75;
    }

    .no-news-card:hover {
        transform: translateY(-4px);
        transition: 0.3s ease;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }

    .btn-container {
        text-align: center;
        margin-top: 35px;
    }

    .back-btn {
        display: inline-block;
        padding: 12px 25px;
        color: #ffffff;
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

  <main class="news-container">

      <section class="header-section">
          <h1 class="news-title">Latest Announcements</h1>
          <p class="news-subtitle">Stay updated with the most recent updates and important information.</p>
      </section>

      <div class="no-news-card fade-in">
          <img src="<?= base_url('assets/img/empty-news.png') ?>" alt="No News">
          <h2>No Announcements Yet</h2>
          <p>Please check back later for any updates from FIFTY 50 MEDTECHIE.</p>
      </div>

      <div class="btn-container">
          <a href="<?= base_url('/') ?>" class="back-btn">← Back to Homepage</a>
      </div>

  </main>

</body>
</html>
