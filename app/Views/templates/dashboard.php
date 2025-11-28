<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Dashboard') ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
  <?= $this->include('nurse/header') ?>
  
  <div class="layout">
    <?= $this->include('nurse/sidebar') ?>
    
    <main class="content">
      <?= $this->renderSection('content') ?>
    </main>
  </div>
  
  <script src="<?= base_url('assets/js/rbac.js') ?>"></script>
  <?= $this->renderSection('scripts') ?>
</body>
</html>
