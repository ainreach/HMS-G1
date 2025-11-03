<?php
namespace App\Controllers;
use App\Libraries\AuditLogger;

class Admin extends BaseController
{
    public function dashboard()
    {
        helper(['url', 'date']);
        
        // Load all models
        $userModel = model('App\\Models\\UserModel');
        $patientModel = model('App\\Models\\PatientModel');
        $appointmentModel = model('App\\Models\\AppointmentModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $paymentModel = model('App\\Models\\PaymentModel');
        $labTestModel = model('App\\Models\\LabTestModel');
        $medicineModel = model('App\\Models\\MedicineModel');
        $inventoryModel = model('App\\Models\\InventoryModel');
        $insuranceClaimModel = model('App\\Models\\InsuranceClaimModel');

        // User Statistics
        $totalUsers = $userModel->countAllResults();
        $newUsersThisMonth = $userModel->where('created_at >=', date('Y-m-01'))->countAllResults();
        $recentUsers = $userModel->select('id, employee_id, username, role, created_at')
                                 ->orderBy('created_at', 'DESC')
                                 ->findAll(5);

        // Patient Statistics
        $totalPatients = $patientModel->countAllResults();
        $newPatientsThisMonth = $patientModel->where('created_at >=', date('Y-m-01'))->countAllResults();
        $recentPatients = $patientModel->select('id, patient_id, first_name, last_name, created_at')
                                      ->orderBy('created_at', 'DESC')
                                      ->findAll(5);

        // Appointment Statistics
        $totalAppointments = $appointmentModel->countAllResults();
        $todayAppointments = $appointmentModel->where('appointment_date', date('Y-m-d'))->countAllResults();
        $upcomingAppointments = $appointmentModel->where('appointment_date >=', date('Y-m-d'))
                                                ->where('status', 'scheduled')
                                                ->countAllResults();

        // Financial Statistics
        $totalInvoices = $invoiceModel->countAllResults();
        $totalRevenue = $paymentModel->selectSum('amount')->first()['amount'] ?? 0;
        $pendingPayments = $invoiceModel->where('status', 'unpaid')->countAllResults();

        // Lab Test Statistics
        $totalLabTests = $labTestModel->countAllResults();
        $pendingLabTests = $labTestModel->where('status', 'requested')->countAllResults();
        $completedLabTests = $labTestModel->where('status', 'completed')->countAllResults();

        // Inventory Statistics
        $totalMedicines = $medicineModel->countAllResults();
        $lowStockItems = $inventoryModel->where('quantity_in_stock <=', 'minimum_stock_level')->countAllResults();

        // Insurance Claims Statistics
        $totalClaims = $insuranceClaimModel->countAllResults();
        $pendingClaims = $insuranceClaimModel->where('status', 'submitted')->countAllResults();
        $approvedClaims = $insuranceClaimModel->where('status', 'approved')->countAllResults();

        // System Status
        $basePath = defined('FCPATH') ? FCPATH : __DIR__;
        $freeBytes = @disk_free_space($basePath);
        $freeGb = $freeBytes !== false ? ($freeBytes / (1024 * 1024 * 1024)) : 0;

        $data = [
            // User Statistics
            'totalUsers' => $totalUsers,
            'activeUsers' => $totalUsers,
            'newUsersThisMonth' => $newUsersThisMonth,
            'recentUsers' => $recentUsers,
            
            // Patient Statistics
            'totalPatients' => $totalPatients,
            'newPatientsThisMonth' => $newPatientsThisMonth,
            'recentPatients' => $recentPatients,
            
            // Appointment Statistics
            'totalAppointments' => $totalAppointments,
            'todayAppointments' => $todayAppointments,
            'upcomingAppointments' => $upcomingAppointments,
            
            // Financial Statistics
            'totalInvoices' => $totalInvoices,
            'totalRevenue' => $totalRevenue,
            'pendingPayments' => $pendingPayments,
            
            // Lab Test Statistics
            'totalLabTests' => $totalLabTests,
            'pendingLabTests' => $pendingLabTests,
            'completedLabTests' => $completedLabTests,
            
            // Inventory Statistics
            'totalMedicines' => $totalMedicines,
            'lowStockItems' => $lowStockItems,
            
            // Insurance Claims Statistics
            'totalClaims' => $totalClaims,
            'pendingClaims' => $pendingClaims,
            'approvedClaims' => $approvedClaims,
            
            // System Status
            'systemStatus' => [
                'database' => 'online',
                'storage' => $freeGb,
                'lastBackup' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'activeSessions' => 1,
            ],
        ];

        return view('admin/dashboard', $data);
    }

    public function newUser()
    {
        helper(['url','form']);
        return view('admin/user_new');
    }

    public function storeUser()
    {
        helper(['url','form']);
        $req = $this->request;
        $data = [
            'employee_id' => trim((string)$req->getPost('employee_id')),
            'username'    => trim((string)$req->getPost('username')),
            'email'       => trim((string)$req->getPost('email')),
            'first_name'  => trim((string)$req->getPost('first_name')),
            'last_name'   => trim((string)$req->getPost('last_name')),
            'role'        => trim((string)$req->getPost('role')),
        ];
        $password = (string)$req->getPost('password');
        if ($data['username']==='' || $password==='' || $data['role']==='') {
            return redirect()->back()->with('error','Username, password, and role are required.')->withInput();
        }
        $users = model('App\\Models\\UserModel');
        // Basic uniqueness check on username
        $exists = $users->where('username', $data['username'])->first();
        if ($exists) {
            return redirect()->back()->with('error','Username already exists.')->withInput();
        }
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $users->insert($data);
        AuditLogger::log('user_create', 'username=' . $data['username'] . ' role=' . $data['role']);
        return redirect()->to(site_url('dashboard/admin'))->with('success','User created successfully.');
    }

    public function assignRole()
    {
        helper(['url','form']);
        $users = model('App\\Models\\UserModel')->orderBy('username','ASC')->findAll(1000);
        return view('admin/role_assign', ['users'=>$users]);
    }

    public function storeRoleAssignment()
    {
        helper(['url','form']);
        $userId = (int) $this->request->getPost('user_id');
        $role   = trim((string)$this->request->getPost('role'));
        if (!$userId || $role==='') {
            return redirect()->back()->with('error','User and role are required.')->withInput();
        }
        $users = model('App\\Models\\UserModel');
        if (!$users->find($userId)) {
            return redirect()->back()->with('error','Selected user not found.')->withInput();
        }
        $users->update($userId, ['role'=>$role, 'updated_at'=>date('Y-m-d H:i:s')]);
        AuditLogger::log('role_assign', 'user_id=' . $userId . ' role=' . $role);
        return redirect()->to(site_url('dashboard/admin'))->with('success','Role updated.');
    }

    public function reports()
    {
        helper('url');
        $users = model('App\\Models\\UserModel');
        $counts = [
            'total' => $users->countAllResults(),
            'admins' => $users->where('role','admin')->countAllResults(),
            'doctors' => $users->where('role','doctor')->countAllResults(),
            'nurses' => $users->where('role','nurse')->countAllResults(),
            'receptionists' => $users->where('role','receptionist')->countAllResults(),
            'pharmacists' => $users->where('role','pharmacist')->countAllResults(),
            'lab_staff' => $users->where('role','lab_staff')->countAllResults(),
            'accountants' => $users->where('role','accountant')->countAllResults(),
            'it_staff' => $users->where('role','it_staff')->countAllResults(),
        ];
        return view('admin/reports', ['counts'=>$counts]);
    }

    public function usersList()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $userModel = model('App\\Models\\UserModel');
        $total = $userModel->countAllResults();
        $users = $userModel
                    ->select('id,employee_id,username,role,created_at')
                    ->orderBy('created_at','DESC')
                    ->findAll($perPage, $offset);
        $data = [
            'users' => $users,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($users)) < $total,
        ];
        return view('admin/users_list', $data);
    }

    public function editUser($id)
    {
        helper(['url','form']);
        $id = (int) $id;
        $user = model('App\\Models\\UserModel')->find($id);
        if (!$user) {
            return redirect()->to(site_url('admin/users'))->with('error','User not found.');
        }
        return view('admin/user_edit', ['user' => $user]);
    }

    public function updateUser($id)
    {
        helper(['url','form']);
        $id = (int) $id;
        $req = $this->request;
        $data = [
            'employee_id' => trim((string)$req->getPost('employee_id')),
            'email'       => trim((string)$req->getPost('email')),
            'first_name'  => trim((string)$req->getPost('first_name')),
            'last_name'   => trim((string)$req->getPost('last_name')),
            'role'        => trim((string)$req->getPost('role')),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        $password = (string)$req->getPost('password');
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $users = model('App\\Models\\UserModel');
        if (!$users->find($id)) {
            return redirect()->to(site_url('admin/users'))->with('error','User not found.');
        }
        $users->update($id, $data);
        AuditLogger::log('user_update', 'user_id=' . $id);
        return redirect()->to(site_url('admin/users'))->with('success','User updated.');
    }

    public function deleteUser($id)
    {
        helper(['url']);
        $id = (int) $id;
        $users = model('App\\Models\\UserModel');
        if ($users->find($id)) {
            $users->delete($id);
            AuditLogger::log('user_delete', 'user_id=' . $id);
        }
        return redirect()->to(site_url('admin/users'))->with('success','User deleted.');
    }

    // Patient Management
    public function patients()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $patientModel = model('App\\Models\\PatientModel');
        $total = $patientModel->countAllResults();
        $patients = $patientModel
                    ->select('id, patient_id, first_name, last_name, phone, email, created_at')
                    ->orderBy('created_at', 'DESC')
                    ->findAll($perPage, $offset);
        $data = [
            'patients' => $patients,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($patients)) < $total,
        ];
        return view('admin/patients_list', $data);
    }

    public function newPatient()
    {
        helper(['url','form']);
        return view('admin/patient_new');
    }

    public function storePatient()
    {
        helper(['url','form']);
        $req = $this->request;
        $data = [
            'patient_id' => 'P-' . date('YmdHis'),
            'first_name' => trim((string)$req->getPost('first_name')),
            'last_name' => trim((string)$req->getPost('last_name')),
            'middle_name' => trim((string)$req->getPost('middle_name')),
            'date_of_birth' => $req->getPost('date_of_birth'),
            'gender' => $req->getPost('gender'),
            'blood_type' => $req->getPost('blood_type'),
            'phone' => trim((string)$req->getPost('phone')),
            'email' => trim((string)$req->getPost('email')),
            'address' => trim((string)$req->getPost('address')),
            'city' => trim((string)$req->getPost('city')),
            'emergency_contact_name' => trim((string)$req->getPost('emergency_contact_name')),
            'emergency_contact_phone' => trim((string)$req->getPost('emergency_contact_phone')),
            'emergency_contact_relation' => trim((string)$req->getPost('emergency_contact_relation')),
            'insurance_provider' => trim((string)$req->getPost('insurance_provider')),
            'insurance_number' => trim((string)$req->getPost('insurance_number')),
            'allergies' => trim((string)$req->getPost('allergies')),
            'medical_history' => trim((string)$req->getPost('medical_history')),
            'branch_id' => 1, // Default branch
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        if ($data['first_name'] === '' || $data['last_name'] === '' || $data['date_of_birth'] === '') {
            return redirect()->back()->with('error','First name, last name, and date of birth are required.')->withInput();
        }
        
        $patients = model('App\\Models\\PatientModel');
        $patients->insert($data);
        AuditLogger::log('patient_create', 'patient_id=' . $data['patient_id']);
        return redirect()->to(site_url('admin/patients'))->with('success','Patient created successfully.');
    }

    public function editPatient($id)
    {
        helper(['url','form']);
        $id = (int) $id;
        $patient = model('App\\Models\\PatientModel')->find($id);
        if (!$patient) {
            return redirect()->to(site_url('admin/patients'))->with('error','Patient not found.');
        }
        return view('admin/patient_edit', ['patient' => $patient]);
    }

    public function updatePatient($id)
    {
        helper(['url','form']);
        $id = (int) $id;
        $req = $this->request;
        $data = [
            'first_name' => trim((string)$req->getPost('first_name')),
            'last_name' => trim((string)$req->getPost('last_name')),
            'middle_name' => trim((string)$req->getPost('middle_name')),
            'date_of_birth' => $req->getPost('date_of_birth'),
            'gender' => $req->getPost('gender'),
            'blood_type' => $req->getPost('blood_type'),
            'phone' => trim((string)$req->getPost('phone')),
            'email' => trim((string)$req->getPost('email')),
            'address' => trim((string)$req->getPost('address')),
            'city' => trim((string)$req->getPost('city')),
            'emergency_contact_name' => trim((string)$req->getPost('emergency_contact_name')),
            'emergency_contact_phone' => trim((string)$req->getPost('emergency_contact_phone')),
            'emergency_contact_relation' => trim((string)$req->getPost('emergency_contact_relation')),
            'insurance_provider' => trim((string)$req->getPost('insurance_provider')),
            'insurance_number' => trim((string)$req->getPost('insurance_number')),
            'allergies' => trim((string)$req->getPost('allergies')),
            'medical_history' => trim((string)$req->getPost('medical_history')),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $patients = model('App\\Models\\PatientModel');
        if (!$patients->find($id)) {
            return redirect()->to(site_url('admin/patients'))->with('error','Patient not found.');
        }
        $patients->update($id, $data);
        AuditLogger::log('patient_update', 'patient_id=' . $id);
        return redirect()->to(site_url('admin/patients'))->with('success','Patient updated.');
    }

    public function deletePatient($id)
    {
        helper(['url']);
        $id = (int) $id;
        $patients = model('App\\Models\\PatientModel');
        if ($patients->find($id)) {
            $patients->delete($id);
            AuditLogger::log('patient_delete', 'patient_id=' . $id);
        }
        return redirect()->to(site_url('admin/patients'))->with('success','Patient deleted.');
    }

    // Appointment Management
    public function appointments()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $appointmentModel = model('App\\Models\\AppointmentModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');
        
        $total = $appointmentModel->countAllResults();
        $appointments = $appointmentModel
                        ->select('appointments.*, patients.first_name as patient_first_name, patients.last_name as patient_last_name, users.first_name as doctor_first_name, users.last_name as doctor_last_name')
                        ->join('patients', 'patients.id = appointments.patient_id')
                        ->join('users', 'users.id = appointments.doctor_id')
                        ->orderBy('appointments.appointment_date', 'DESC')
                        ->findAll($perPage, $offset);
        
        $data = [
            'appointments' => $appointments,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($appointments)) < $total,
        ];
        return view('admin/appointments_list', $data);
    }

    // Medical Records Management
    public function medicalRecords()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');
        
        $total = $medicalRecordModel->countAllResults();
        $records = $medicalRecordModel
                    ->select('medical_records.*, patients.first_name as patient_first_name, patients.last_name as patient_last_name, users.first_name as doctor_first_name, users.last_name as doctor_last_name')
                    ->join('patients', 'patients.id = medical_records.patient_id')
                    ->join('users', 'users.id = medical_records.doctor_id')
                    ->orderBy('medical_records.visit_date', 'DESC')
                    ->findAll($perPage, $offset);
        
        $data = [
            'records' => $records,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($records)) < $total,
        ];
        return view('admin/medical_records_list', $data);
    }

    // Financial Management
    public function invoices()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $invoiceModel = model('App\\Models\\InvoiceModel');
        
        $total = $invoiceModel->countAllResults();
        $invoices = $invoiceModel
                    ->orderBy('issued_at', 'DESC')
                    ->findAll($perPage, $offset);
        
        $data = [
            'invoices' => $invoices,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($invoices)) < $total,
        ];
        return view('admin/invoices_list', $data);
    }

    public function payments()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $paymentModel = model('App\\Models\\PaymentModel');
        
        $total = $paymentModel->countAllResults();
        $payments = $paymentModel
                    ->orderBy('paid_at', 'DESC')
                    ->findAll($perPage, $offset);
        
        $data = [
            'payments' => $payments,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($payments)) < $total,
        ];
        return view('admin/payments_list', $data);
    }

    public function insuranceClaims()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $insuranceClaimModel = model('App\\Models\\InsuranceClaimModel');
        
        $total = $insuranceClaimModel->countAllResults();
        $claims = $insuranceClaimModel
                    ->orderBy('submitted_at', 'DESC')
                    ->findAll($perPage, $offset);
        
        $data = [
            'claims' => $claims,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($claims)) < $total,
        ];
        return view('admin/insurance_claims_list', $data);
    }

    // Lab Test Management
    public function labTests()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $labTestModel = model('App\\Models\\LabTestModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');
        
        $total = $labTestModel->countAllResults();
        $labTests = $labTestModel
                    ->select('lab_tests.*, patients.first_name as patient_first_name, patients.last_name as patient_last_name, users.first_name as doctor_first_name, users.last_name as doctor_last_name')
                    ->join('patients', 'patients.id = lab_tests.patient_id')
                    ->join('users', 'users.id = lab_tests.doctor_id')
                    ->orderBy('lab_tests.requested_date', 'DESC')
                    ->findAll($perPage, $offset);
        
        $data = [
            'labTests' => $labTests,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($labTests)) < $total,
        ];
        return view('admin/lab_tests_list', $data);
    }

    // Inventory Management
    public function medicines()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $medicineModel = model('App\\Models\\MedicineModel');
        
        $total = $medicineModel->countAllResults();
        $medicines = $medicineModel
                    ->orderBy('name', 'ASC')
                    ->findAll($perPage, $offset);
        
        $data = [
            'medicines' => $medicines,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($medicines)) < $total,
        ];
        return view('admin/medicines_list', $data);
    }

    public function inventory()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $inventoryModel = model('App\\Models\\InventoryModel');
        $medicineModel = model('App\\Models\\MedicineModel');
        
        $total = $inventoryModel->countAllResults();
        $inventory = $inventoryModel
                    ->select('inventory.*, medicines.name as medicine_name, medicines.medicine_code')
                    ->join('medicines', 'medicines.id = inventory.medicine_id')
                    ->orderBy('inventory.updated_at', 'DESC')
                    ->findAll($perPage, $offset);
        
        $data = [
            'inventory' => $inventory,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($inventory)) < $total,
        ];
        return view('admin/inventory_list', $data);
    }

    // System Analytics
    public function analytics()
    {
        helper(['url', 'date']);
        
        // Load all models
        $userModel = model('App\\Models\\UserModel');
        $patientModel = model('App\\Models\\PatientModel');
        $appointmentModel = model('App\\Models\\AppointmentModel');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $paymentModel = model('App\\Models\\PaymentModel');
        $labTestModel = model('App\\Models\\LabTestModel');
        $medicineModel = model('App\\Models\\MedicineModel');
        $inventoryModel = model('App\\Models\\InventoryModel');
        $insuranceClaimModel = model('App\\Models\\InsuranceClaimModel');

        // Monthly statistics for the last 12 months
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthlyStats[$month] = [
                'users' => $userModel->where('DATE_FORMAT(created_at, "%Y-%m")', $month)->countAllResults(),
                'patients' => $patientModel->where('DATE_FORMAT(created_at, "%Y-%m")', $month)->countAllResults(),
                'appointments' => $appointmentModel->where('DATE_FORMAT(appointment_date, "%Y-%m")', $month)->countAllResults(),
                'revenue' => $paymentModel->where('DATE_FORMAT(paid_at, "%Y-%m")', $month)->selectSum('amount')->first()['amount'] ?? 0,
            ];
        }

        // Role distribution
        $roleDistribution = $userModel->select('role, COUNT(*) as count')
                                    ->groupBy('role')
                                    ->findAll();

        // Appointment status distribution
        $appointmentStatus = $appointmentModel->select('status, COUNT(*) as count')
                                            ->groupBy('status')
                                            ->findAll();

        // Lab test status distribution
        $labTestStatus = $labTestModel->select('status, COUNT(*) as count')
                                    ->groupBy('status')
                                    ->findAll();

        // Low stock medicines
        $lowStockMedicines = $inventoryModel->select('inventory.*, medicines.name as medicine_name')
                                          ->join('medicines', 'medicines.id = inventory.medicine_id')
                                          ->where('inventory.quantity_in_stock <= inventory.minimum_stock_level')
                                          ->findAll();

        $data = [
            'monthlyStats' => $monthlyStats,
            'roleDistribution' => $roleDistribution,
            'appointmentStatus' => $appointmentStatus,
            'labTestStatus' => $labTestStatus,
            'lowStockMedicines' => $lowStockMedicines,
        ];

        return view('admin/analytics', $data);
    }

    // Audit Logs
    public function auditLogs()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 50;
        $offset = ($page - 1) * $perPage;
        
        // Read audit log file
        $logFile = WRITEPATH . 'logs/audit.log';
        $logs = [];
        $total = 0;
        
        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $total = count($lines);
            $lines = array_reverse($lines); // Most recent first
            $lines = array_slice($lines, $offset, $perPage);
            
            foreach ($lines as $line) {
                $parts = explode(' | ', $line);
                if (count($parts) >= 3) {
                    $logs[] = [
                        'timestamp' => $parts[0] ?? '',
                        'action' => $parts[1] ?? '',
                        'details' => $parts[2] ?? '',
                    ];
                }
            }
        }
        
        $data = [
            'logs' => $logs,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($logs)) < $total,
        ];
        
        return view('admin/audit_logs', $data);
    }
}
