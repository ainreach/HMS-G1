<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Pages::home');
$routes->get('/dashboard', 'Pages::dashboard');
$routes->get('/dashboard/admin', 'Admin::dashboard', ['filter' => 'role:admin']);
$routes->get('/dashboard/doctor', 'Doctor::dashboard', ['filter' => 'role:doctor']);
$routes->get('/dashboard/nurse', 'Nurse::dashboard', ['filter' => 'role:nurse']);
$routes->get('/dashboard/receptionist', 'Reception::dashboard', ['filter' => 'role:receptionist']);
$routes->get('/dashboard/lab', 'Labstaff::dashboard', ['filter' => 'role:lab_staff']);
$routes->get('/dashboard/pharmacist', 'Pharmacy::dashboard', ['filter' => 'role:pharmacist']);
$routes->get('/dashboard/accountant', 'Accountant::dashboard', ['filter' => 'role:accountant']);
$routes->get('/dashboard/it', 'Itstaff::dashboard', ['filter' => 'role:it_staff']);
$routes->get('/it/reports', 'Itstaff::reports', ['filter' => 'role:it_staff']);
$routes->get('/it/audit-logs', 'Itstaff::auditLogs', ['filter' => 'role:it_staff']);
$routes->get('/it/backups', 'Itstaff::backups', ['filter' => 'role:it_staff']);
// IT maintenance & security
$routes->get('/it/maintenance', 'Itstaff::maintenance', ['filter' => 'role:it_staff']);
$routes->get('/it/maintenance/clear-cache', 'Itstaff::clearCache', ['filter' => 'role:it_staff']);
$routes->get('/it/security', 'Itstaff::security', ['filter' => 'role:it_staff']);
$routes->get('/it/health', 'Itstaff::health', ['filter' => 'role:it_staff,admin']);
$routes->get('/it/backups/export/users', 'Itstaff::exportUsersCsv', ['filter' => 'role:it_staff']);
$routes->get('/it/backups/export/zip', 'Itstaff::exportZip', ['filter' => 'role:it_staff']);
$routes->get('/admin/users/new', 'Admin::newUser', ['filter' => 'role:admin']);
$routes->post('/admin/users', 'Admin::storeUser', ['filter' => 'role:admin']);
$routes->get('/admin/roles/assign', 'Admin::assignRole', ['filter' => 'role:admin']);
$routes->post('/admin/roles/assign', 'Admin::storeRoleAssignment', ['filter' => 'role:admin']);
$routes->get('/admin/reports', 'Admin::reports', ['filter' => 'role:admin']);
$routes->get('/admin/users', 'Admin::usersList', ['filter' => 'role:admin']);
$routes->get('/admin/users/edit/(:num)', 'Admin::editUser/$1', ['filter' => 'role:admin']);
$routes->post('/admin/users/(:num)', 'Admin::updateUser/$1', ['filter' => 'role:admin']);
$routes->post('/admin/users/delete/(:num)', 'Admin::deleteUser/$1', ['filter' => 'role:admin']);
$routes->get('/accountant/billing', 'Accountant::billing', ['filter' => 'role:accountant']);
$routes->get('/accountant/reports', 'Accountant::reports', ['filter' => 'role:accountant']);
$routes->get('/accountant/invoices/new', 'Accountant::newInvoice', ['filter' => 'role:accountant']);
$routes->post('/accountant/invoices', 'Accountant::storeInvoice', ['filter' => 'role:accountant']);
$routes->get('/accountant/payments/new', 'Accountant::newPayment', ['filter' => 'role:accountant']);
$routes->get('/accountant/statements', 'Accountant::statements', ['filter' => 'role:accountant']);
$routes->post('/accountant/payments', 'Accountant::storePayment', ['filter' => 'role:accountant']);
$routes->get('/accountant/statements/export', 'Accountant::exportStatement', ['filter' => 'role:accountant']);
$routes->get('/accountant/invoices/export', 'Accountant::exportInvoicesCsv', ['filter' => 'role:accountant']);
$routes->get('/accountant/finance/export/zip', 'Accountant::exportZip', ['filter' => 'role:accountant']);
// Insurance claims
$routes->get('/accountant/insurance', 'Accountant::insurance', ['filter' => 'role:accountant']);
$routes->get('/accountant/claims/new', 'Accountant::newClaim', ['filter' => 'role:accountant']);
$routes->post('/accountant/claims', 'Accountant::storeClaim', ['filter' => 'role:accountant']);

// Receptionist functional routes
$routes->get('/reception/patients/new', 'Reception::newPatient', ['filter' => 'role:receptionist,admin']);
$routes->post('/reception/patients', 'Reception::storePatient', ['filter' => 'role:receptionist,admin']);
$routes->get('/reception/appointments/new', 'Reception::newAppointment', ['filter' => 'role:receptionist']);
$routes->post('/reception/appointments', 'Reception::storeAppointment', ['filter' => 'role:receptionist']);

// Doctor functional routes
$routes->get('/doctor/records/new', 'Doctor::newRecord', ['filter' => 'role:doctor']);
$routes->post('/doctor/records', 'Doctor::storeRecord', ['filter' => 'role:doctor']);
$routes->get('/doctor/lab-requests/new', 'Doctor::newLabRequest', ['filter' => 'role:doctor']);
$routes->post('/doctor/lab-requests', 'Doctor::storeLabRequest', ['filter' => 'role:doctor']);
$routes->get('/doctor/patients/new', 'Doctor::newPatient', ['filter' => 'role:doctor,admin']);
$routes->post('/doctor/patients', 'Doctor::storePatient', ['filter' => 'role:doctor,admin']);

// Nurse functional routes
$routes->get('/nurse/vitals/new', 'Nurse::newVitals', ['filter' => 'role:nurse']);
$routes->post('/nurse/vitals', 'Nurse::storeVitals', ['filter' => 'role:nurse']);
$routes->get('/nurse/notes/new', 'Nurse::newNote', ['filter' => 'role:nurse']);
$routes->post('/nurse/notes', 'Nurse::storeNote', ['filter' => 'role:nurse']);

// Pharmacy functional routes
$routes->get('/pharmacy/dispense/new', 'Pharmacy::newDispense', ['filter' => 'role:pharmacist']);
$routes->post('/pharmacy/dispense', 'Pharmacy::storeDispense', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/inventory', 'Pharmacy::inventory', ['filter' => 'role:pharmacist']);

// Lab Staff functional routes
$routes->get('/lab/results/new', 'Labstaff::newResult', ['filter' => 'role:lab_staff']);
$routes->post('/lab/results', 'Labstaff::storeResult', ['filter' => 'role:lab_staff']);
$routes->get('/laboratory', 'Pages::laboratory');
$routes->get('/pharmacy', 'Pages::pharmacy');
$routes->get('/scheduling', 'Pages::scheduling');
$routes->get('/billing', 'Pages::billing');

// Auth and misc pages
$routes->get('/about', 'Pages::about');
$routes->get('/register', 'Pages::register');
$routes->get('/privacy', 'Pages::privacy');
$routes->get('/terms', 'Pages::terms');
$routes->get('/news', 'Pages::news');
$routes->get('/physicians', 'Pages::physicians');
$routes->get('/services', 'Pages::services');
$routes->get('/notifications', 'Pages::notifications');
$routes->get('/login', 'Auth::select');
$routes->post('/login', 'Auth::doLoginSingle');
$routes->get('/login/(:segment)', 'Auth::login/$1');
$routes->post('/login/(:segment)', 'Auth::doLogin/$1');
$routes->get('/logout', 'Auth::logout');

// Dashboard pages
$routes->get('/records', 'Pages::records');
$routes->get('/reports', 'Pages::reports');