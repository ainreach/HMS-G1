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
$routes->get('/it/branch-integration', 'Itstaff::branchIntegration', ['filter' => 'role:it_staff']);
$routes->get('/it/system-monitoring', 'Itstaff::systemMonitoring', ['filter' => 'role:it_staff']);
$routes->get('/it/security-scan', 'Itstaff::securityScan', ['filter' => 'role:it_staff']);
$routes->get('/it/database-maintenance', 'Itstaff::databaseMaintenance', ['filter' => 'role:it_staff']);
$routes->post('/it/database-maintenance/optimize', 'Itstaff::optimizeDatabase', ['filter' => 'role:it_staff']);
$routes->get('/it/database-maintenance/optimize', 'Itstaff::optimizeDatabase', ['filter' => 'role:it_staff']);
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
$routes->get('/admin/appointments/edit/(:num)', 'Admin::editAppointment/$1', ['filter' => 'role:admin']);
$routes->post('/admin/appointments/(:num)', 'Admin::updateAppointment/$1', ['filter' => 'role:admin']);
$routes->post('/admin/appointments/delete/(:num)', 'Admin::deleteAppointment/$1', ['filter' => 'role:admin']);
$routes->get('/admin/staff-schedules', 'Admin::staffSchedules', ['filter' => 'role:admin']);
$routes->post('/admin/staff-schedules', 'Admin::storeStaffSchedule', ['filter' => 'role:admin']);
$routes->post('/admin/staff-schedules/delete/(:num)', 'Admin::deleteStaffSchedule/$1', ['filter' => 'role:admin']);
$routes->get('/admin/staff-schedules/year-events', 'Admin::staffScheduleYearEvents', ['filter' => 'role:admin']);

// Medical Records Management Routes
$routes->get('/admin/medical-records', 'Admin::medicalRecords', ['filter' => 'role:admin']);
$routes->get('/admin/medical-records/(:num)', 'Admin::getMedicalRecord/$1', ['filter' => 'role:admin']);
$routes->get('/admin/medical-records/(:num)/edit', 'Admin::editMedicalRecord/$1', ['filter' => 'role:admin']);
$routes->post('/admin/medical-records/(:num)', 'Admin::updateMedicalRecord/$1', ['filter' => 'role:admin']);
$routes->post('/admin/medical-records/(:num)/delete', 'Admin::deleteMedicalRecord/$1', ['filter' => 'role:admin']);
$routes->post('/admin/medical-records/(:num)/restore', 'Admin::restoreMedicalRecord/$1', ['filter' => 'role:admin']);

// Financial Management Routes
$routes->get('/admin/invoices', 'Admin::invoices', ['filter' => 'role:admin,accountant']);
$routes->get('/admin/payments', 'Admin::payments', ['filter' => 'role:admin,accountant']);
$routes->get('/admin/payments/(:num)', 'Admin::viewPayment/$1', ['filter' => 'role:admin,accountant']);
$routes->get('/admin/insurance-claims', 'Admin::insuranceClaims', ['filter' => 'role:admin,accountant']);
// Detailed billing view (reuses Accountant::viewBilling)
$routes->get('/admin/billing/view/(:num)', 'Accountant::viewBilling/$1', ['filter' => 'role:admin,accountant']);
$routes->get('/accountant/billing/view/(:num)', 'Accountant::viewBilling/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/billing/invoice/(:num)', 'Accountant::viewBilling/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/billing/print/(:num)', 'Accountant::viewBilling/$1', ['filter' => 'role:accountant']);

// Lab Test Management Routes
$routes->get('/admin/lab-tests', 'Admin::labTests', ['filter' => 'role:admin']);

// Inventory Management Routes
$routes->get('/admin/inventory', 'Admin::inventory', ['filter' => 'role:admin']);
$routes->get('/admin/add-stock', 'Admin::addStock', ['filter' => 'role:admin']);
$routes->post('/admin/add-stock', 'Admin::storeStock', ['filter' => 'role:admin']);
$routes->get('/admin/inventory/edit/(:num)', 'Admin::editStock/$1', ['filter' => 'role:admin']);
$routes->post('/admin/inventory/(:num)', 'Admin::updateStock/$1', ['filter' => 'role:admin']);
$routes->post('/admin/inventory/delete/(:num)', 'Admin::deleteStock/$1', ['filter' => 'role:admin']);
$routes->get('/admin/medicines', 'Admin::medicines', ['filter' => 'role:admin']);
$routes->post('/admin/medicines/add', 'Admin::addMedicine', ['filter' => 'role:admin']);
$routes->get('/admin/medicines/edit/(:num)', 'Admin::editMedicine/$1', ['filter' => 'role:admin']);
$routes->post('/admin/medicines/save/(:num)', 'Admin::updateMedicine/$1', ['filter' => 'role:admin']);
$routes->get('/admin/medicines/delete/(:num)', 'Admin::deleteMedicine/$1', ['filter' => 'role:admin']);

// Analytics and Reports Routes
$routes->get('/admin/analytics', 'Admin::analytics', ['filter' => 'role:admin']);
$routes->get('/admin/audit-logs', 'Admin::auditLogs', ['filter' => 'role:admin']);
$routes->get('/accountant/billing', 'Accountant::billing', ['filter' => 'role:accountant']);
$routes->get('/accountant/pending-charges', 'Accountant::pendingCharges', ['filter' => 'role:accountant']);
$routes->get('/accountant/pending-charges/approve/(:num)', 'Accountant::approveCharge/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/pending-charges/pay/(:num)', 'Accountant::payCharge/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/pending-charges/cancel/(:num)', 'Accountant::cancelCharge/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/lab-test-approvals', 'Accountant::labTestApprovals', ['filter' => 'role:accountant']);
$routes->get('/accountant/setup-lab-test-columns', 'Accountant::setupLabTestColumns', ['filter' => 'role:accountant']);
$routes->post('/accountant/lab-test-approve/(:num)', 'Accountant::approveLabTest/$1', ['filter' => 'role:accountant']);
$routes->post('/accountant/lab-test-reject/(:num)', 'Accountant::rejectLabTest/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/patients/billing/(:num)', 'Accountant::patientBilling/$1', ['filter' => 'role:admin,accountant']);
// Alias for patient billing (singular path)
$routes->get('/accountant/patient-billing/(:num)', 'Accountant::patientBilling/$1', ['filter' => 'role:admin,accountant']);
// Consolidated billing routes (one bill per patient)
$routes->get('/accountant/consolidated-bills', 'Accountant::consolidatedBills', ['filter' => 'role:accountant']);
$routes->get('/accountant/consolidated-bill/(:num)', 'Accountant::viewConsolidatedBill/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/add-charge/(:num)', 'Accountant::addCharge/$1', ['filter' => 'role:accountant']);
$routes->post('/accountant/add-charge/(:num)', 'Accountant::addCharge/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/remove-charge/(:num)', 'Accountant::removeCharge/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/apply-insurance/(:num)', 'Accountant::applyInsurance/$1', ['filter' => 'role:accountant']);
$routes->post('/accountant/apply-insurance/(:num)', 'Accountant::applyInsurance/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/accept-payment/(:num)', 'Accountant::acceptPayment/$1', ['filter' => 'role:accountant']);
$routes->post('/accountant/accept-payment/(:num)', 'Accountant::acceptPayment/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/print-bill/(:num)', 'Accountant::printBill/$1', ['filter' => 'role:accountant']);
// Admin can also access consolidated bills
$routes->get('/admin/consolidated-bills', 'Accountant::consolidatedBills', ['filter' => 'role:admin']);
$routes->get('/admin/consolidated-bill/(:num)', 'Accountant::viewConsolidatedBill/$1', ['filter' => 'role:admin']);
$routes->get('/accountant/patients/discharge/(:num)', 'Accountant::dischargePatient/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/patients/bills', 'Accountant::allPatientBills', ['filter' => 'role:accountant']);
$routes->get('/accountant/reports', 'Accountant::reports', ['filter' => 'role:accountant']);
$routes->get('/accountant/invoices', 'Accountant::invoices', ['filter' => 'role:admin,accountant']);
$routes->get('/accountant/invoices/new', 'Accountant::newInvoice', ['filter' => 'role:admin,accountant']);
$routes->post('/accountant/invoices', 'Accountant::storeInvoice', ['filter' => 'role:admin,accountant']);
$routes->get('/accountant/invoices/edit/(:num)', 'Accountant::editInvoice/$1', ['filter' => 'role:admin,accountant']);
$routes->post('/accountant/invoices/(:num)', 'Accountant::updateInvoice/$1', ['filter' => 'role:admin,accountant']);
$routes->post('/accountant/invoices/delete/(:num)', 'Accountant::deleteInvoice/$1', ['filter' => 'role:admin,accountant']);
$routes->get('/accountant/payments', 'Accountant::payments', ['filter' => 'role:accountant']);
$routes->get('/accountant/payments/(:num)', 'Accountant::viewPayment/$1', ['filter' => 'role:accountant']);
$routes->get('/accountant/payments/new', 'Accountant::newPayment', ['filter' => 'role:accountant']);
$routes->get('/admin/payments/new', 'Accountant::newPayment', ['filter' => 'role:admin']);
$routes->post('/admin/payments', 'Accountant::storePayment', ['filter' => 'role:admin']);
$routes->get('/admin/payments/(:num)', 'Accountant::viewPayment/$1', ['filter' => 'role:admin']);
$routes->get('/admin/payments/edit/(:num)', 'Accountant::editPayment/$1', ['filter' => 'role:admin']);
$routes->post('/admin/payments/(:num)', 'Accountant::updatePayment/$1', ['filter' => 'role:admin']);
$routes->post('/admin/payments/delete/(:num)', 'Accountant::deletePayment/$1', ['filter' => 'role:admin']);
$routes->post('/accountant/payments', 'Accountant::storePayment', ['filter' => 'role:accountant']);
$routes->post('/accountant/storePayment', 'Accountant::storePayment', ['filter' => 'role:accountant']);
$routes->get('/accountant/payments/edit/(:num)', 'Accountant::editPayment/$1', ['filter' => 'role:admin,accountant']);
$routes->post('/accountant/payments/(:num)', 'Accountant::updatePayment/$1', ['filter' => 'role:admin,accountant']);
$routes->post('/accountant/payments/delete/(:num)', 'Accountant::deletePayment/$1', ['filter' => 'role:admin,accountant']);
$routes->get('/accountant/statements', 'Accountant::statements', ['filter' => 'role:accountant']);
$routes->get('/accountant/statements/export', 'Accountant::exportStatement', ['filter' => 'role:accountant']);
$routes->get('/accountant/invoices/export', 'Accountant::exportInvoicesCsv', ['filter' => 'role:accountant']);
$routes->get('/accountant/finance/export/zip', 'Accountant::exportZip', ['filter' => 'role:accountant']);
// Insurance claims
$routes->get('/accountant/insurance', 'Accountant::insurance', ['filter' => 'role:accountant']);
$routes->get('/accountant/claims/new', 'Accountant::newClaim', ['filter' => 'role:accountant']);
$routes->post('/accountant/claims', 'Accountant::storeClaim', ['filter' => 'role:accountant']);

// View single claim (admin only)
$routes->get('/accountant/claims/(:num)', 'Accountant::viewClaim/$1', ['filter' => 'role:admin']);

// Receptionist functional routes
$routes->get('/reception/patients/new', 'Reception::newPatient', ['filter' => 'role:receptionist,admin']);
$routes->post('/reception/patients', 'Reception::storePatient', ['filter' => 'role:receptionist,admin']);
$routes->get('/reception/patients', 'Reception::patients', ['filter' => 'role:receptionist']);
$routes->get('/reception/patients/view/(:num)', 'Reception::viewPatient/$1', ['filter' => 'role:receptionist']);
$routes->get('/reception/patients/search', 'Reception::searchPatients', ['filter' => 'role:receptionist']);
$routes->get('/reception/patients/all', 'Reception::allPatients', ['filter' => 'role:receptionist']);
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
$routes->get('/reception/get-beds-by-room/(:num)', 'Reception::getBedsByRoom/$1', ['filter' => 'role:receptionist']);
$routes->get('/reception/rooms', 'Reception::rooms', ['filter' => 'role:receptionist']);
$routes->get('/reception/rooms/admit', 'Reception::roomAdmission', ['filter' => 'role:receptionist']);
$routes->post('/reception/rooms/admit', 'Reception::admitToRoom', ['filter' => 'role:receptionist']);
$routes->get('/reception/rooms/discharge/(:num)', 'Reception::dischargeFromRoom/$1', ['filter' => 'role:receptionist']);
$routes->get('/reception/rooms/new', 'Reception::newRoom', ['filter' => 'role:receptionist']);
$routes->get('/reception/rooms/edit/(:num)', 'Reception::editRoom/$1', ['filter' => 'role:receptionist']);
$routes->post('/reception/rooms/save', 'Reception::storeRoom', ['filter' => 'role:receptionist']);
$routes->get('/reception/rooms/delete/(:num)', 'Reception::deleteRoom/$1', ['filter' => 'role:receptionist']);
$routes->post('/reception/rooms/(:num)/status', 'Reception::updateRoomStatus/$1', ['filter' => 'role:receptionist']);
$routes->post('/reception/beds/add', 'Reception::addBed', ['filter' => 'role:receptionist']);
$routes->get('/reception/beds/delete/(:num)', 'Reception::deleteBed/$1', ['filter' => 'role:receptionist']);
$routes->get('/reception/in-patients', 'Reception::inPatients', ['filter' => 'role:receptionist']);
$routes->get('/reception/in-patients/view/(:num)', 'Reception::viewInPatient/$1', ['filter' => 'role:receptionist']);
$routes->get('/reception/in-patients/edit/(:num)', 'Reception::editInPatient/$1', ['filter' => 'role:receptionist']);
$routes->post('/reception/in-patients/update/(:num)', 'Reception::updateInPatient/$1', ['filter' => 'role:receptionist']);

// Admin Room Management Routes
$routes->get('/admin/rooms', 'Admin::rooms', ['filter' => 'role:admin']);
$routes->get('/admin/rooms/new', 'Admin::newRoom', ['filter' => 'role:admin']);
$routes->get('/admin/rooms/edit/(:num)', 'Admin::editRoom/$1', ['filter' => 'role:admin']);
$routes->post('/admin/rooms/save', 'Admin::storeRoom', ['filter' => 'role:admin']);
$routes->get('/admin/rooms/delete/(:num)', 'Admin::deleteRoom/$1', ['filter' => 'role:admin']);

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
$routes->get('/doctor/records/(:num)/edit', 'Doctor::editRecord/$1', ['filter' => 'role:doctor']);
$routes->post('/doctor/records/(:num)', 'Doctor::updateRecord/$1', ['filter' => 'role:doctor']);
$routes->post('/doctor/records/(:num)/delete', 'Doctor::deleteRecord/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/appointments/patient/(:num)', 'Doctor::appointmentsByPatient/$1', ['filter' => 'role:doctor,admin']);
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
$routes->get('/doctor/patients/consultation/(:num)', 'Doctor::startConsultation/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/consultations/start/(:num)', 'Doctor::startConsultation/$1', ['filter' => 'role:doctor']);
$routes->post('/doctor/consultations/save-consultation', 'Doctor::saveConsultation', ['filter' => 'role:doctor']);
$routes->get('/doctor/consultations/done/(:num)', 'Doctor::consultationDone/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/patients/edit/(:num)', 'Doctor::editPatient/$1', ['filter' => 'role:doctor']);
$routes->post('/doctor/patients/update/(:num)', 'Doctor::updatePatient/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/patients/delete/(:num)', 'Doctor::deletePatient/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/patients/view/(:num)', 'Doctor::viewPatient/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/admit-patients', 'Doctor::admitPatients', ['filter' => 'role:doctor']);
$routes->get('/doctor/admit-patient/(:num)', 'Doctor::admitPatientForm/$1', ['filter' => 'role:doctor']);
$routes->post('/doctor/admit-patient/(:num)', 'Doctor::processAdmission/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/get-beds-by-room/(:num)', 'Doctor::getBedsByRoom/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/upcoming-consultations', 'Doctor::upcomingConsultations', ['filter' => 'role:doctor']);
$routes->get('/doctor/lab-requests-nurses', 'Doctor::labRequestsFromNurses', ['filter' => 'role:doctor']);
$routes->post('/doctor/lab-requests-nurses/(:num)/confirm', 'Doctor::confirmLabRequest/$1', ['filter' => 'role:doctor']);
$routes->get('/doctor/orders', 'Doctor::orders', ['filter' => 'role:doctor']);
$routes->get('/doctor/discharge-patients', 'Doctor::dischargePatients', ['filter' => 'role:doctor']);
$routes->post('/doctor/discharge-patient/(:num)', 'Doctor::processDischarge/$1', ['filter' => 'role:doctor']);
// Doctor schedule management
$routes->get('/doctor/schedule', 'Doctor::schedule', ['filter' => 'role:doctor']);
$routes->post('/doctor/schedule', 'Doctor::storeSchedule', ['filter' => 'role:doctor']);

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
$routes->post('/nurse/lab-samples/collect/(:num)', 'Nurse::collectSample/$1', ['filter' => 'role:nurse']);
$routes->get('/nurse/lab-request', 'Nurse::newLabRequest', ['filter' => 'role:nurse']);
$routes->post('/nurse/lab-request', 'Nurse::storeLabRequest', ['filter' => 'role:nurse']);

// Treatment Updates
$routes->get('/nurse/treatment-updates', 'Nurse::treatmentUpdates', ['filter' => 'role:nurse']);
$routes->post('/nurse/treatment-updates', 'Nurse::updateTreatment', ['filter' => 'role:nurse']);
$routes->post('/nurse/treatment-update', 'Nurse::updateTreatment', ['filter' => 'role:nurse']);

// Pending Admissions
$routes->get('/nurse/pending-admissions', 'Nurse::pendingAdmissions', ['filter' => 'role:nurse']);
$routes->get('/nurse/admit-patient/(:num)', 'Nurse::admitPatient/$1', ['filter' => 'role:nurse']);
$routes->post('/nurse/admit-patient/(:num)', 'Nurse::processAdmission/$1', ['filter' => 'role:nurse']);
$routes->get('/nurse/get-beds-by-room/(:num)', 'Nurse::getBedsByRoom/$1', ['filter' => 'role:nurse']);
$routes->get('/nurse/patients/consultation/(:num)', 'Nurse::viewPatientConsultation/$1', ['filter' => 'role:nurse']);

// Pharmacy functional routes
$routes->get('/pharmacy/dispense/new', 'Pharmacy::newDispense', ['filter' => 'role:pharmacist']);
$routes->post('/pharmacy/dispense', 'Pharmacy::storeDispense', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/dispense/from-prescription/(:num)', 'Pharmacy::dispenseFromPrescription/$1', ['filter' => 'role:pharmacist']);
$routes->post('/pharmacy/dispense/from-prescription/(:num)', 'Pharmacy::processDispenseFromPrescription/$1', ['filter' => 'role:pharmacist']);
$routes->post('/pharmacy/prescriptions/(:num)/update-status', 'Pharmacy::updatePrescriptionStatus/$1', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/inventory', 'Pharmacy::inventory', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/prescriptions', 'Pharmacy::prescriptions', ['filter' => 'role:pharmacist']);
$routes->get('/pharmacy/prescription/view/(:num)', 'Pharmacy::viewPrescription/$1', ['filter' => 'role:pharmacist']);
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