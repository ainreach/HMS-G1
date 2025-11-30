<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FIFTY 50 MEDTECHIE - Hospital Management System</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
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
    header h1 {
      font-size: 2.5rem;
      margin: 0;
    }
    header p {
      margin-top: 10px;
      font-size: 1.2rem;
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
    .features {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
  <header style="text-align:center; padding:40px 20px; background:linear-gradient(90deg,#0a6cff,#2a9bff); color:white; animation:fadeInDown 1s ease;">
    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Hospital Logo" style="height:80px; margin-bottom:10px; animation:fadeInDown 1.2s ease;">
    <h1>FIFTY 50 MEDTECHIE</h1>
    <p>"Trust Us... If You're Feeling Lucky."</p>
  </header>

  <main class="content" style="max-width:900px;margin:20px auto;padding:16px">
    <a href="<?= site_url('/') ?>" style="display:inline-block;margin-bottom:15px;padding:10px 16px;background:#1d4ed8;color:white;border-radius:8px;text-decoration:none;font-weight:600;transition:0.3s;">← Back to Homepage</a>
    <h2>Welcome to Our Hospital Management System</h2>
    <p>
      Experience a smoother, faster, and more reliable way to manage hospital operations. 
      From scheduling to patient records, our system ensures efficiency with a clean, user-friendly interface.
    </p>

    <div class="features">
      <div class="card">
        <h3>Patient Records</h3>
        <p>View, update, and organize patient details effortlessly.</p>
      </div>
      <div class="card">
        <h3>Appointments</h3>
        <p>Manage doctor schedules and upcoming patient appointments.</p>
      </div>
      <div class="card">
        <h3>Medical Inventory</h3>
        <p>Track supplies and equipment with real-time updates.</p>
      </div>
      <div class="card">
        <h3>Hospital News</h3>
        <p>Stay updated with announcements and important updates.</p>
      </div>
    </div>
  </main>
</body>
</html>
