<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Select Role</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <style>
    :root{--border:#e5e7eb;--muted:#6b7280;--bg:#f9fafb}
    body{background:var(--bg)}
    .container{max-width:960px;margin:2rem auto;padding:0 1rem}
    .panel{background:#fff;border:1px solid var(--border);border-radius:12px;overflow:hidden}
    .panel-head{padding:18px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:flex-start;align-items:center;gap:12px}
    .panel-head h1{font-size:1.25rem;margin:0}
    .panel-body{padding:18px 20px}
    .muted{color:var(--muted)}
    /* removed quick selector */
    .roles{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-top:1rem}
    .role-card{border:1px solid var(--border);border-radius:10px;padding:16px;text-decoration:none;color:inherit;transition:.15s box-shadow, .15s transform;background:#fff}
    .role-card:hover{box-shadow:0 8px 26px rgba(0,0,0,.08);transform:translateY(-2px)}
    .role-card h3{margin:.25rem 0;font-size:1.05rem}
  </style>
</head><body>
<main class="container">
  <section class="panel">
    <div class="panel-head"><h1>Select your role</h1></div>
    <div class="panel-body">
      <p class="muted">Choose your role to continue to login.</p>
      <section class="roles">
        <a class="role-card" href="<?= site_url('login/admin') ?>"><h3>Administrator</h3><p class="muted">Full access</p></a>
        <a class="role-card" href="<?= site_url('login/doctor') ?>"><h3>Doctor</h3><p class="muted">Patients, orders</p></a>
        <a class="role-card" href="<?= site_url('login/nurse') ?>"><h3>Nurse</h3><p class="muted">Care, updates</p></a>
        <a class="role-card" href="<?= site_url('login/receptionist') ?>"><h3>Receptionist</h3><p class="muted">Registration, appointments</p></a>
        <a class="role-card" href="<?= site_url('login/lab_staff') ?>"><h3>Lab Staff</h3><p class="muted">Lab requests</p></a>
        <a class="role-card" href="<?= site_url('login/pharmacist') ?>"><h3>Pharmacist</h3><p class="muted">Dispense, inventory</p></a>
        <a class="role-card" href="<?= site_url('login/accountant') ?>"><h3>Accountant</h3><p class="muted">Billing, reports</p></a>
        <a class="role-card" href="<?= site_url('login/it_staff') ?>"><h3>IT Staff</h3><p class="muted">System maintenance</p></a>
      </section>
    </div>
  </section>
</main>

<!-- quick selector removed -->

</body></html>
