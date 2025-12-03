<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Admin Dashboard') ?></title>
  <base href="<?= rtrim(base_url(), '/') ?>/">
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; }
    
    .dash-topbar { background: white; border-bottom: 1px solid #e5e7eb; padding: 0 1rem; height: 60px; display: flex; align-items: center; justify-content: space-between; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; }
    .topbar-inner { display: flex; align-items: center; justify-content: space-between; width: 100%; }
    .brand { display: flex; align-items: center; gap: 0.75rem; }
    .brand img { height: 32px; width: 32px; }
    .brand-text h1 { font-size: 1.25rem; margin: 0; color: #111827; }
    .brand-text small { color: #6b7280; font-size: 0.75rem; }
    .top-right { display: flex; align-items: center; gap: 1rem; }
    .role { color: #374151; font-size: 0.875rem; }
    .logout-btn { color: #ef4444; text-decoration: none; font-size: 0.875rem; }
    .logout-btn:hover { color: #dc2626; }
    
    .layout { display: flex; margin-top: 60px; min-height: calc(100vh - 60px); }
    .simple-sidebar { width: 250px; background: #f8f9fa; border-right: 1px solid #e9ecef; overflow-y: auto; position: fixed; left: 0; top: 60px; bottom: 0; }
    .content { flex: 1; margin-left: 250px; padding: 1rem; background: #ffffff; }
    
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .kpi-card { background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .kpi-primary { border-left: 4px solid #3b82f6; }
    .kpi-success { border-left: 4px solid #10b981; }
    .kpi-warning { border-left: 4px solid #f59e0b; }
    .kpi-info { border-left: 4px solid #60a5fa; }
    .kpi-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; color: #6b7280; font-size: 0.875rem; }
    .kpi-value { font-size: 1.5rem; font-weight: 600; color: #111827; }
    .kpi-subtitle { font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem; }
    .panel { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; overflow: hidden; }
    .panel-head { padding: 1rem 1.25rem; border-bottom: 1px solid #e5e7eb; background-color: #f9fafb; }
    .panel-body { padding: 1.25rem; }
    .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; text-transform: capitalize; }
    .badge-admin { background-color: #dbeafe; color: #1e40af; }
    .badge-doctor { background-color: #d1fae5; color: #065f46; }
    .badge-nurse { background-color: #fef3c7; color: #92400e; }
    .badge-accountant { background-color: #ede9fe; color: #5b21b6; }
    .badge-it_staff { background-color: #f3e8ff; color: #6b21a8; }
    .badge-other { background-color: #e5e7eb; color: #374151; }
    .nav-section { margin-bottom: 1.5rem; }
    .nav-section-title { font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.5rem 0; padding: 0 1rem; }
    .side-nav { padding: 0 1rem; }
    .side-nav a { display: flex; align-items: center; padding: 0.75rem 1rem; color: #495057; text-decoration: none; transition: all 0.2s; font-size: 0.9rem; background-color: #ffffff; border: 1px solid #e9ecef; border-radius: 0.5rem; margin-bottom: 0.5rem; }
    .side-nav a:hover { background-color: #f8f9fa; color: #212529; border-color: #dee2e6; }
    .side-nav a.active { background-color: #007bff; color: white; font-weight: 500; border-color: #007bff; }
    .side-nav a i { margin-right: 0.75rem; width: 1.25rem; text-align: center; font-size: 0.85rem; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { text-align: left; padding: 8px 12px; border-bottom: 1px solid #e5e7eb; }
    .table th { background-color: #f9fafb; font-weight: 600; color: #374151; }
    .table tbody tr:hover { background-color: #f9fafb; }
    .btn { display: inline-block; padding: 0.5rem 1rem; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s; border: none; cursor: pointer; }
    .btn:hover { background-color: #2563eb; }
    .btn-secondary { background-color: #6b7280; }
    .btn-secondary:hover { background-color: #4b5563; }
    .btn-success { background-color: #10b981; }
    .btn-success:hover { background-color: #059669; }
    .btn-warning { background-color: #f59e0b; }
    .btn-warning:hover { background-color: #d97706; }
    .btn-danger { background-color: #ef4444; }
    .btn-danger:hover { background-color: #dc2626; }
    .alert { padding: 1rem; border-radius: 0.375rem; margin-bottom: 1rem; }
    .alert-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .pagination { display: flex; justify-content: center; align-items: center; gap: 0.5rem; margin-top: 1rem; }
    .pagination a { padding: 0.5rem 0.75rem; background-color: #f3f4f6; color: #374151; text-decoration: none; border-radius: 0.375rem; }
    .pagination a:hover { background-color: #e5e7eb; }
    .pagination .current { padding: 0.5rem 0.75rem; background-color: #3b82f6; color: white; border-radius: 0.375rem; }
    .container-fluid { padding: 0; }
    .card { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1rem; }
    .card-header { padding: 1rem 1.25rem; border-bottom: 1px solid #e5e7eb; background-color: #f9fafb; display: flex; justify-content: between; align-items: center; }
    .card-body { padding: 1.25rem; }
    .card-title { margin: 0; font-size: 1.125rem; font-weight: 600; color: #111827; }
    
    /* Modal Styles */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; animation: fadeIn 0.3s ease; }
    .modal-overlay.show { display: flex; justify-content: center; align-items: center; }
    .modal-card { background: white; border-radius: 12px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); animation: slideUp 0.3s ease; }
    .modal-header { padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
    .modal-title { margin: 0; font-size: 1.25rem; font-weight: 600; color: #111827; }
    .modal-close { background: none; border: none; font-size: 1.25rem; color: #6b7280; cursor: pointer; padding: 0.25rem; border-radius: 0.375rem; }
    .modal-close:hover { background: #f3f4f6; color: #111827; }
    .modal-body { padding: 1.5rem; }
    .modal-footer { padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 0.75rem; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group { display: flex; flex-direction: column; }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-group label { margin-bottom: 0.5rem; font-weight: 500; color: #374151; font-size: 0.875rem; }
    .form-control { padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; transition: border-color 0.15s; }
    .form-control:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    .checkbox-label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.875rem; color: #374151; }
    .checkbox-label input[type="checkbox"] { width: 1rem; height: 1rem; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
  </style>
</head>
<body>
<?= $this->include('admin/sidebar') ?>

<?= $this->renderSection('scripts') ?>
</body>
</html>
