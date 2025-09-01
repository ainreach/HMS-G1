<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Management</title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head><body>
<header class="dash-topbar" role="banner"><div class="topbar-inner">
  <a href="<?= site_url('/') ?>" class="menu-btn" aria-label="Home"><i class="fa-solid fa-house"></i></a>
  <div class="brand"><img src="<?= base_url('assets/img/logo.png') ?>" alt="HMS" />
    <div class="brand-text"><h1 style="font-size:1.25rem;margin:0">Administrator</h1><small>User Management</small></div>
  </div>
  <div class="top-right" aria-label="User session">
    <span class="role"><i class="fa-regular fa-user"></i>
      <?= esc(session('username') ?? session('role') ?? 'User') ?>
    </span>
    <a href="<?= site_url('logout') ?>" class="logout-btn" style="margin-left:12px;text-decoration:none;border:1px solid #e5e7eb;padding:6px 10px;border-radius:6px">Logout</a>
  </div>
</div></header>
<div class="layout"><aside class="simple-sidebar" role="navigation" aria-label="Admin navigation"><nav class="side-nav">
  <a href="<?= site_url('dashboard/admin') ?>">Overview</a>
  <a href="<?= site_url('admin/users') ?>" class="active" aria-current="page">User Management</a>
  <a href="<?= site_url('admin/reports') ?>">Reports</a>
</nav></aside>
  <main class="content">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <h2 style="margin:0">Users</h2>
      <div style="display:flex;gap:8px">
        <a class="btn" href="<?= site_url('admin/users/new') ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Create User</a>
        <a class="btn" href="<?= site_url('admin/roles/assign') ?>" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;text-decoration:none">Assign Role</a>
      </div>
    </div>

    <section class="panel">
      <div class="panel-body" style="overflow:auto">
        <table class="table" style="width:100%;border-collapse:collapse">
          <thead>
            <tr>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Employee ID</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Username</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Role</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Created</th>
              <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($users)): foreach ($users as $u): ?>
              <tr>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($u['employee_id'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($u['username'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><span style="display:inline-block;padding:2px 8px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:.85em;"><?= esc($u['role'] ?? '') ?></span></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6"><?= esc($u['created_at'] ?? '') ?></td>
                <td style="padding:8px;border-bottom:1px solid #f3f4f6">
                  <a class="btn" href="<?= site_url('admin/users/edit/' . $u['id']) ?>" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">Edit</a>
                  <form method="post" action="<?= site_url('admin/users/delete/' . $u['id']) ?>" style="display:inline" onsubmit="return confirm('Delete this user?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn" style="padding:6px 10px;border:1px solid #ef4444;color:#ef4444;border-radius:6px;background:#fff">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="5" style="padding:12px;text-align:center;color:#6b7280">No users found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
      <div style="color:#6b7280">Page <?= (int)($page ?? 1) ?><?php if(isset($total,$perPage)): ?> of <?= (int) ceil(($total)/($perPage)) ?> (<?= (int)$total ?> users)<?php endif; ?></div>
      <div style="display:flex;gap:8px">
        <?php if (!empty($hasPrev) && $hasPrev): ?>
          <a class="btn" href="<?= site_url('admin/users?page=' . max(1, ($page??1)-1)) ?>" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">Prev</a>
        <?php endif; ?>
        <?php if (!empty($hasNext) && $hasNext): ?>
          <a class="btn" href="<?= site_url('admin/users?page=' . (($page??1)+1)) ?>" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:6px;text-decoration:none">Next</a>
        <?php endif; ?>
      </div>
    </div>
  </main>
</div>
</body></html>
