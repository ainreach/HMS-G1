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

    .news-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
        margin-top: 40px;
    }

    .news-article-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: fadeIn 1s ease-out;
    }

    .news-article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .article-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .article-title {
        font-size: 22px;
        font-weight: 600;
        color: #0a4dad;
        margin: 0;
    }

    .article-date {
        font-size: 14px;
        color: #5a6a85;
        margin: 5px 0 0;
    }

    .article-excerpt {
        font-size: 16px;
        color: #4a5a75;
        line-height: 1.6;
    }

    .read-more-btn {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 18px;
        background-color: #eef5ff;
        color: #0a4dad;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .read-more-btn:hover {
        background-color: #0a4dad;
        color: #ffffff;
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

      <div class="news-grid">
          <article class="news-article-card">
              <div class="article-header">
                  <h3 class="article-title">New Health and Wellness Program</h3>
                  <p class="article-date">December 4, 2025</p>
              </div>
              <p class="article-excerpt">We are excited to launch our new comprehensive Health and Wellness program for all members. This program includes personalized fitness plans, nutritional guidance, and mental wellness support. Join us in taking a step towards a healthier lifestyle.</p>
              <a href="#" class="read-more-btn">Read More</a>
          </article>

          <article class="news-article-card">
              <div class="article-header">
                  <h3 class="article-title">Hospital Expansion Update</h3>
                  <p class="article-date">November 28, 2025</p>
              </div>
              <p class="article-excerpt">The construction of our new west wing is on schedule. We expect the new state-of-the-art facility to be operational by Q2 2026, featuring advanced surgical suites and expanded patient rooms.</p>
              <a href="#" class="read-more-btn">Read More</a>
          </article>

          <article class="news-article-card">
              <div class="article-header">
                  <h3 class="article-title">Community Health Fair a Success</h3>
                  <p class="article-date">November 15, 2025</p>
              </div>
              <p class="article-excerpt">Thank you to everyone who participated in our annual Community Health Fair. With over 500 attendees, it was our most successful event yet, offering free health screenings and educational workshops.</p>
              <a href="#" class="read-more-btn">Read More</a>
          </article>
      </div>

      <div class="btn-container">
          <a href="<?= base_url('/') ?>" class="back-btn">← Back to Homepage</a>
      </div>

  </main>

</body>
</html>
