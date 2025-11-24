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

// Patient Management Routes
$routes->get('/admin/patients', 'Admin::patients', ['filter' => 'role:admin']);
$routes->get('/admin/patients/new', 'Admin::newPatient', ['filter' => 'role:admin']);
$routes->post('/admin/patients', 'Admin::storePatient', ['filter' => 'role:admin']);
$routes->get('/admin/patients/edit/(:num)', 'Admin::editPatient/$1', ['filter' => 'role:admin']);
$routes->get('/admin/patients/view/(:num)', 'Admin::viewPatient/$1', ['filter' => 'role:admin']);
$routes->post('/admin/patients/(:num)', 'Admin::updatePatient/$1', ['filter' => 'role:admin']);
$routes->post('/admin/patients/delete/(:num)', 'Admin::deletePatient/$1', ['filter' => 'role:admin']);

// Appointment Management Routes
$routes->get('/admin/appointments', 'Admin::appointments', ['filter' => 'role:admin']);
$routes->get('/admin/staff-schedules', 'Admin::staffSchedules', ['filter' => 'role:admin']);
$routes->post('/admin/staff-schedules', 'Admin::storeStaffSchedule', ['filter' => 'role:admin']);
$routes->post('/admin/staff-schedules/delete/(:num)', 'Admin::deleteStaffSchedule/$1', ['filter' => 'role:admin']);

// Medical Records Management Routes
$routes->get('/admin/medical-records', 'Admin::medicalRecords', ['filter' => 'role:admin']);

// Financial Management Routes
$routes->get('/admin/invoices', 'Admin::invoices', ['filter' => 'role:admin']);
$routes->get('/admin/payments', 'Admin::payments', ['filter' => 'role:admin']);
$routes->get('/admin/insurance-claims', 'Admin::insuranceClaims', ['filter' => 'role:admin']);

// Lab Test Management Routes
$routes->get('/admin/lab-tests', 'Admin::labTests', ['filter' => 'role:admin']);

// Inventory Management Routes
$routes->get('/admin/medicines', 'Admin::medicines', ['filter' => 'role:admin']);
$routes->get('/admin/inventory', 'Admin::inventory', ['filter' => 'role:admin']);

// Analytics and Reports Routes
$routes->get('/admin/analytics', 'Admin::analytics', ['filter' => 'role:admin']);
$routes->get('/admin/audit-logs', 'Admin::auditLogs', ['filter' => 'role:admin']);
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
$routes->get('/reception/patients', 'Reception::patients', ['filter' => 'role:receptionist']);
$routes->get('/reception/patients/view/(:num)', 'Reception::viewPatient/$1', ['filter' => 'role:receptionist']);
$routes->get('/reception/patients/search', 'Reception::searchPatients', ['filter' => 'role:receptionist']);
$routes->get('/reception/patient-lookup', 'Reception::patientLookup', ['filter' => 'role:receptionist']);
$routes->get('/reception/appointments', 'Reception::appointments', ['filter' => 'role:receptionist']);
$routes->get('/reception/appointments/(:num)', 'Reception::viewAppointment/$1', ['filter' => 'role:receptionist']);
$routes->get('/reception/appointments/(:num)/edit', 'Reception::editAppointment/$1', ['filter' => 'role:receptionist']);
$routes->post('/reception/appointments/(:num)', 'Reception::updateAppointment/$1', ['filter' => 'role:receptionist']);
$routes->post('/reception/appointments/(:num)/checkin', 'Reception::checkIn/$1', ['filter' => 'role:receptionist']);
$routes->post('/reception/appointments/(:num)/cancel', 'Reception::cancelAppointment/$1', ['filter' => 'role:receptionist']);
$routes->get('/reception/appointments/new', 'Reception::newAppointment', ['filter' => 'role:receptionist']);
$routes->post('/reception/appointments', 'Reception::storeAppointment', ['filter' => 'role:receptionist']);

// Room management routes
$routes->get('/reception/getAvailableRooms', 'Reception::getAvailableRooms', ['filter' => 'role:receptionist']);
$routes->get('/reception/getRoomDetails', 'Reception::getRoomDetails', ['filter' => 'role:receptionist']);
$routes->get('/reception/rooms', 'Reception::rooms', ['filter' => 'role:receptionist']);
$routes->post('/reception/rooms/(:num)/status', 'Reception::updateRoomStatus/$1', ['filter' => 'role:receptionist']);

// Doctor functional routes
$routes->get('/doctor/patient_records', 'Doctor::patient_records', ['filter' => 'role:doctor']);
$routes->get('/doctor/view_patient_record/(:num)', 'Doctor::view_patient_record/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/create_prescription/(:num)', 'Doctor::create_prescription/$1', ['filter' => 'role:doctor']);
$routes->post('/doctor/store_prescription', 'Doctor::store_prescription', ['filter' => 'role:doctor']);
$routes->get('/doctor/request_test/(:num)', 'Doctor::request_test/$1', ['filter' => 'role:doctor']);
$routes->post('/doctor/store_test_request', 'Doctor::store_test_request', ['filter' => 'role:doctor']);
$routes->get('/doctor/records/new', 'Doctor::newRecord', ['filter' => 'role:doctor']);
$routes->post('/doctor/records', 'Doctor::storeRecord', ['filter' => 'role:doctor']);
$routes->get('/doctor/records', 'Doctor::patientRecords', ['filter' => 'role:doctor']);
$routes->get('/doctor/records/(:num)', 'Doctor::viewRecord/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/lab-requests/new', 'Doctor::newLabRequest', ['filter' => 'role:doctor']);
$routes->post('/doctor/lab-requests', 'Doctor::storeLabRequest', ['filter' => 'role:doctor']);
$routes->get('/doctor/lab-results', 'Doctor::labResults', ['filter' => 'role:doctor']);
$routes->get('/doctor/lab-results/(:num)', 'Doctor::viewLabResult/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/prescriptions', 'Doctor::prescriptions', ['filter' => 'role:doctor']);
$routes->get('/doctor/patients', 'Doctor::patients', ['filter' => 'role:doctor']);
$routes->get('/doctor/patients/search', 'Doctor::searchPatients', ['filter' => 'role:doctor']);
$routes->get('/doctor/patients/new', 'Doctor::newPatient', ['filter' => 'role:doctor,admin']);
$routes->post('/doctor/patients', 'Doctor::storePatient', ['filter' => 'role:doctor,admin']);
$routes->get('/doctor/patients/view/(:num)', 'Doctor::viewPatient/$1', ['filter' => 'role:doctor']);

// Nurse functional routes
// Dashboard
$routes->get('/nurse', 'Nurse::dashboard', ['filter' => 'role:nurse']);
$routes->get('/nurse/dashboard', 'Nurse::dashboard', ['filter' => 'role:nurse']);

// Vitals Management
$routes->get('/nurse/vitals/new', 'Nurse::newVitals', ['filter' => 'role:nurse']);
$routes->post('/nurse/vitals', 'Nurse::storeVitals', ['filter' => 'role:nurse']);
$routes->post('/nurse/vitals/store', 'Nurse::storeVitals', ['filter' => 'role:nurse']);

// Notes Management
$routes->get('/nurse/notes/new', 'Nurse::newNote', ['filter' => 'role:nurse']);
$routes->post('/nurse/notes', 'Nurse::storeNote', ['filter' => 'role:nurse']);
$routes->post('/nurse/notes/store', 'Nurse::storeNote', ['filter' => 'role:nurse']);

// Ward Patients & Patient Monitoring
$routes->get('/nurse/ward-patients', 'Nurse::wardPatients', ['filter' => 'role:nurse']);
$routes->get('/nurse/patients/view/(:num)', 'Nurse::viewPatient/$1', ['filter' => 'role:nurse']);
$routes->get('/nurse/ward-patients/(:num)', 'Nurse::patientMonitoring/$1', ['filter' => 'role:nurse']);
$routes->get('/nurse/patient/(:num)', 'Nurse::patientMonitoring/$1', ['filter' => 'role:nurse']);
$routes->get('/nurse/patient-monitoring/(:num)', 'Nurse::patientMonitoring/$1', ['filter' => 'role:nurse']);

// Lab Samples
$routes->get('/nurse/lab-samples', 'Nurse::labSamples', ['filter' => 'role:nurse']);
$routes->get('/nurse/lab-samples/collect/(:num)', 'Nurse::collectSample/$1', ['filter' => 'role:nurse']);
$routes->post('/nurse/lab-samples/(:num)/collect', 'Nurse::collectSample/$1', ['filter' => 'role:nurse']);

// Treatment Updates
$routes->get('/nurse/treatment-updates', 'Nurse::treatmentUpdates', ['filter' => 'role:nurse']);
$routes->post('/nurse/treatment-updates', 'Nurse::updateTreatment', ['filter' => 'role:nurse']);
$routes->post('/nurse/treatment-update', 'Nurse::updateTreatment', ['filter' => 'role:nurse']);

// Pharmacy functional routes
$routes->get('/pharmacy/dispense/new', 'Pharmacy::newDispense', ['filter' => 'role:pharmacist']);
$routes->post('/pharmacy/dispense', 'Pharmacy::storeDispense', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/inventory', 'Pharmacy::inventory', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/prescriptions', 'Pharmacy::prescriptions', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/dispensing-history', 'Pharmacy::dispensingHistory', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/medicines/search', 'Pharmacy::medicineSearch', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/patient-search', 'Pharmacy::patientSearch', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/low-stock-alerts', 'Pharmacy::lowStockAlerts', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/add-stock', 'Pharmacy::addStock', ['filter' => 'role:pharmacist']);
$routes->post('/pharmacy/add-stock', 'Pharmacy::storeStock', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/prescription-fulfillment', 'Pharmacy::prescriptionFulfillment', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/medicine-expiry', 'Pharmacy::medicineExpiry', ['filter' => 'role:pharmacist']);
$routes->group('pharmacy', ['filter' => 'auth'], function($routes) {
$routes->get('medicines', 'Pharmacy::medicines');
$routes->get('medicines/new', 'Pharmacy::medicineForm');
$routes->get('medicines/edit/(:num)', 'Pharmacy::medicineForm/$1');
$routes->post('medicines/save/(:num)', 'Pharmacy::saveMedicine/$1');
$routes->post('medicines/save', 'Pharmacy::saveMedicine');
$routes->get('medicines/delete/(:num)', 'Pharmacy::deleteMedicine/$1');
});

// Lab Staff functional routes
$routes->get('/lab/results/new', 'Labstaff::newResult', ['filter' => 'role:lab_staff']);
$routes->post('/lab/results', 'Labstaff::storeResult', ['filter' => 'role:lab_staff']);
$routes->get('/lab/test-requests', 'Labstaff::testRequests', ['filter' => 'role:lab_staff']);
$routes->post('/lab/test-requests/(:num)/collect', 'Labstaff::collectSample/$1', ['filter' => 'role:lab_staff']);
$routes->get('/lab/sample-queue', 'Labstaff::sampleQueue', ['filter' => 'role:lab_staff']);
$routes->get('/lab/completed-tests', 'Labstaff::completedTests', ['filter' => 'role:lab_staff']);
$routes->get('/lab/tests/(:num)', 'Labstaff::viewTest/$1', ['filter' => 'role:lab_staff']);
$routes->get('/lab/tests/(:num)/print', 'Labstaff::printReport/$1', ['filter' => 'role:lab_staff']);
$routes->get('/lab/statistics', 'Labstaff::testStatistics', ['filter' => 'role:lab_staff']);
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
$routes->get('/auth/login', 'Auth::login');

// Dashboard pages
$routes->get('/records', 'Pages::records');
$routes->get('/reports', 'Pages::reports');