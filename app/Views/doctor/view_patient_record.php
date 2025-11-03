<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient Record</title>
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
                    <h2 style="margin:0;font-size:1.1rem">Patient: <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?></h2>
                </div>
                <div class="panel-body">
                    <h3>Medical Records</h3>
                    <table class="table" style="width:100%;border-collapse:collapse">
                        <thead>
                            <tr>
                                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Date</th>
                                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Diagnosis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($records)) : ?>
                                <?php foreach ($records as $record) : ?>
                                    <tr>
                                        <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($record['visit_date']) ?></td>
                                        <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($record['diagnosis']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="2" style="padding:8px;border-bottom:1px solid #f3f4f6;text-align:center;">No medical records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
