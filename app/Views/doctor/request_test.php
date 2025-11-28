<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Lab Test</title>
    <base href="<?= rtrim(base_url(), '/') ?>/">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <header class="dash-topbar" role="banner">
        <div class="topbar-inner">
            <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
            <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
                <div class="brand-text">
                    <h1 style="font-size:1.25rem;margin:0">Doctor</h1><small>Patient records</small>
                </div>
            </div>
            <div class="top-right" aria-label="User session">
                <span class="role"><i class="fa-regular fa-user"></i>
                    <?= esc(session('username') ?? session('role') ?? 'User') ?>
                </span>
                <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
            </div>
        </div>
    </header>
    <div class="layout">
        <aside class="simple-sidebar" role="navigation" aria-label="Doctor navigation">
            <nav class="side-nav">
                <a href="<?= site_url('dashboard/doctor') ?>">Overview</a>
                <a href="<?= site_url('doctor/patient_records') ?>" class="active" aria-current="page">Patient Records</a>
            </nav>
        </aside>
        <main class="content">
            <section class="panel">
                <div class="panel-head">
                    <h2 style="margin:0;font-size:1.1rem">Request Lab Test</h2>
                </div>
                <div class="panel-body">
                    <form action="<?= site_url('doctor/store_test_request') ?>" method="post">
                        <input type="hidden" name="patient_id" value="<?= esc($patient_id) ?>">
                        <label for="test_name">Test Name</label>
                        <input type="text" id="test_name" name="test_name" required>
                        <label for="test_date">Test Date</label>
                        <input type="date" id="test_date" name="test_date" required>
                        <button type="submit">Request Test</button>
                    </form>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
