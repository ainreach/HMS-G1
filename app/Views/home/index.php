<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FIFTY 50 MEDTECHIE</title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body { margin:0; font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; color:#0f172a; }
    .topbar { position:sticky; top:0; background:#fff; border-bottom:1px solid #e5e7eb; }
    .topbar .inner { max-width:1100px; margin:0 auto; display:flex; align-items:center; justify-content:space-between; padding:10px 16px; }
    .brand { display:flex; align-items:center; gap:10px; }
    .brand img { height:42px; }
    .brand h2 { margin:0; font-size:18px; letter-spacing:0.5px; }
    .brand small { color:#64748b; }
    .main-nav { display:flex; gap:22px; align-items:center; }
    .main-nav a { text-decoration:none; color:#0f172a; font-weight:600; }
    .main-nav a.active { color:#2563eb; }
    .right-actions { display:flex; align-items:center; gap:16px; }
    .right-actions a { text-decoration:none; color:#0f172a; font-weight:600; }

    .hero { max-width:1000px; margin:40px auto 0; padding:0 16px; text-align:center; }
    .hero h1 { margin:0; color:#1d4ed8; font-size:44px; line-height:1.1; }
    .hero h1 .sub { display:block; color:#0f172a; font-size:28px; font-weight:700; margin-bottom:6px; }
    .hero p { color:#475569; max-width:900px; margin:14px auto; }

    .cards { max-width:1000px; margin:28px auto 60px; padding:0 16px; display:grid; gap:18px; grid-template-columns: repeat(3, 1fr); }
    .card { background:#1d4ed8; color:white; border-radius:14px; padding:22px; box-shadow:0 6px 16px rgba(2,6,23,0.12); text-align:center; }
    .card i { font-size:28px; }
    .card .big { display:block; margin-top:8px; font-size:22px; font-weight:800; letter-spacing:0.5px; }
    .card small { display:block; opacity:0.9; margin-top:6px; }
    @media (max-width: 800px){ .cards{ grid-template-columns: 1fr; } }
    .footer { border-top:1px solid #e5e7eb; text-align:center; padding:16px; color:#64748b; }
    .footer a { color:#2563eb; text-decoration:none; }
  </style>
  </head>
  <body>
    <header class="topbar">
      <div class="inner">
        <div class="brand">
          <img src="<?= base_url('assets/img/logo.png') ?>" alt="FIFTY 50 MEDTECHIE">
          <div>
            <h2>FIFTY 50 MEDTECHIE</h2>
            <small>"Trust Us... If You’re Feeling Lucky."</small>
          </div>
        </div>

        <nav class="main-nav" aria-label="Primary">
          <a class="active" href="<?= site_url('/') ?>">Home</a>
          <a href="#">News</a>
          <a href="#">Physicians</a>
          <a href="#">Service</a>
        </nav>

        <div class="right-actions">
          <a href="#" title="Notifications"><i class="fa-regular fa-bell"></i></a>
          <a href="<?= site_url('about') ?>">ABOUT US</a>
          <a href="<?= site_url('login') ?>">LOGIN</a>
        </div>
      </div>
    </header>

    <section class="hero">
      <h1>
        <span class="sub">Welcome to</span>
        FIFTY 50 MEDTECHIE
      </h1>
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed at mi augue. Maecenas a nisl nec nulla aliquam tristique.
        Integer aliquet, magna in dictum mattis, urna arcu tempus enim, eu placerat dui erat nec lorem. Curabitur efficitur, quam non aliquet cursus, ligula lacus fermentum velit, sed dapibus sem orci ac justo.
      </p>
      <p>
        Praesent vitae magna vitae mauris vulputate viverra. Quisque posuere, neque non placerat dictum, ligula tellus pellentesque elit, sit amet varius lorem odio et nunc. Phasellus id urna id justo efficitur volutpat. Etiam non leo at quam
        tristique pellentesque. Donec tincidunt, nunc et blandit rhoncus, nibh felis semper.
      </p>
    </section>

    <section class="cards" aria-label="Quick contacts">
      <article class="card">
        <i class="fa-solid fa-phone"></i>
        <span class="big">(0912) 345 6789</span>
        <small>You can call us here</small>
      </article>
      <article class="card">
        <i class="fa-regular fa-envelope"></i>
        <span class="big">medtechie@gscmed.com</span>
        <small>Send us Email</small>
      </article>
      <article class="card">
        <i class="fa-solid fa-clock"></i>
        <span class="big">24/7 Open</span>
        <small>We are open for you</small>
      </article>
    </section>

    <footer class="footer">
      <a href="#">Privacy Policy</a> · <a href="#">Terms of Use</a>
    </footer>
  </body>
</html>
