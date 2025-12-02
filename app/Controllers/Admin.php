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
        $recentUsers = $userModel->select('id, username, role, created_at')
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
        $today = date('Y-m-d');
        
        // Try multiple approaches to get today's appointments
        $todayAppointments = $appointmentModel->where('appointment_date', $today)->countAllResults();
        
        // If no results, try with DATE function
        if ($todayAppointments == 0) {
            $todayAppointments = $appointmentModel->where("DATE(appointment_date)", $today)->countAllResults();
        }
        
        $upcomingAppointments = $appointmentModel->where('appointment_date >=', date('Y-m-d'))
                                                ->where('status', 'scheduled')
                                                ->countAllResults();
        
        // Get today's appointments details for display - try without DATE first
        $todayAppointmentsList = $appointmentModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.username as doctor_name')
            ->join('patients', 'patients.id = appointments.patient_id', 'left')
            ->join('users', 'users.id = appointments.doctor_id', 'left')
            ->where('appointments.appointment_date', $today)
            ->orderBy('appointments.appointment_time', 'ASC')
            ->findAll(10);
            
        // If empty, try with DATE function
        if (empty($todayAppointmentsList)) {
            $todayAppointmentsList = $appointmentModel
                ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.username as doctor_name')
                ->join('patients', 'patients.id = appointments.patient_id', 'left')
                ->join('users', 'users.id = appointments.doctor_id', 'left')
                ->where("DATE(appointments.appointment_date)", $today)
                ->orderBy('appointments.appointment_time', 'ASC')
                ->findAll(10);
        }
        
        // If still empty, get basic appointments without joins
        if (empty($todayAppointmentsList)) {
            $todayAppointmentsList = $appointmentModel
                ->where('appointment_date', $today)
                ->orderBy('appointment_time', 'ASC')
                ->findAll(10);
        }

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
            'todayAppointmentsList' => $todayAppointmentsList,
            
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
                    ->select('id,username,role,created_at')
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
        helper(['url', 'form']);
        $branchModel = model('App\\Models\\BranchModel');
        
        // Get the first available branch
        $existingBranch = $branchModel->first();
        if (!$existingBranch) {
            // If no branch exists, create one first
            $branchData = [
                'name' => 'Main Branch',
                'code' => 'MAIN',
                'address' => '123 Hospital Street',
                'phone' => '123-456-7890',
                'email' => 'main@hospital.com',
                'is_main' => 1,
                'is_active' => 1,
            ];
            $branchModel->save($branchData);
            $branchId = $branchModel->getInsertID();
        } else {
            $branchId = $existingBranch['id'];
        }
        
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
            'branch_id' => $branchId,
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

    public function viewPatient($id)
    {
        helper(['url']);
        $id = (int) $id;
        $patient = model('App\Models\PatientModel')->find($id);
        if (!$patient) {
            return redirect()->to(site_url('admin/patients'))->with('error', 'Patient not found.');
        }
        return view('admin/patient_view', ['patient' => $patient]);
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
        
        // Debug: Get all appointments first without joins
        $allAppointments = $appointmentModel->findAll(10);
        
        // Then try with joins
        $appointments = $appointmentModel
                        ->select('appointments.*, patients.first_name as patient_first_name, patients.last_name as patient_last_name, users.username as doctor_name')
                        ->join('patients', 'patients.id = appointments.patient_id', 'left')
                        ->join('users', 'users.id = appointments.doctor_id', 'left')
                        ->orderBy('appointments.appointment_date', 'DESC')
                        ->findAll($perPage, $offset);
        
        // If joins return empty, use basic appointment data
        if (empty($appointments) && !empty($allAppointments)) {
            $appointments = $allAppointments;
        }
        
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

    // Staff Scheduling
    public function staffSchedules()
    {
        helper(['url', 'form']);

        $userModel = model('App\\Models\\UserModel');
        $scheduleModel = model('App\\Models\\StaffScheduleModel');

        $doctors = $userModel
            ->where('role', 'doctor')
            ->orderBy('username', 'ASC')
            ->findAll(200);

        $nurses = $userModel
            ->where('role', 'nurse')
            ->orderBy('username', 'ASC')
            ->findAll(200);

        $schedules = $scheduleModel
            ->select('staff_schedules.*, users.username, users.role')
            ->join('users', 'users.id = staff_schedules.user_id')
            ->orderBy('users.role', 'ASC')
            ->orderBy('users.username', 'ASC')
            ->orderBy('staff_schedules.day_of_week', 'ASC')
            ->orderBy('staff_schedules.start_time', 'ASC')
            ->findAll(500);

        return view('admin/staff_schedules', [
            'doctors' => $doctors,
            'nurses' => $nurses,
            'schedules' => $schedules,
        ]);
    }

    public function storeStaffSchedule()
    {
        helper(['url', 'form']);
        $branchModel = model('App\\Models\\BranchModel');

        // Get the first available branch
        $existingBranch = $branchModel->first();
        if (!$existingBranch) {
            // If no branch exists, create one first
            $branchData = [
                'name' => 'Main Branch',
                'code' => 'MAIN',
                'address' => '123 Hospital Street',
                'phone' => '123-456-7890',
                'email' => 'main@hospital.com',
                'is_main' => 1,
                'is_active' => 1,
            ];
            $branchModel->save($branchData);
            $branchId = $branchModel->getInsertID();
        } else {
            $branchId = $existingBranch['id'];
        }

        $userId = (int) $this->request->getPost('user_id');
        $daysOfWeek = $this->request->getPost('days_of_week') ?? [];
        $startTime = (string) $this->request->getPost('start_time');
        $endTime = (string) $this->request->getPost('end_time');

        if (!$userId || empty($daysOfWeek) || $startTime === '' || $endTime === '') {
            return redirect()->back()->with('error', 'Staff, at least one day, start time and end time are required.')->withInput();
        }

        if ($endTime <= $startTime) {
            return redirect()->back()->with('error', 'End time must be after start time.')->withInput();
        }

        $scheduleModel = model('App\\Models\\StaffScheduleModel');
        $addedCount = 0;

        foreach ($daysOfWeek as $dayOfWeek) {
            $data = [
                'user_id'    => $userId,
                'branch_id'  => $branchId,
                'day_of_week'=> strtolower($dayOfWeek),
                'start_time' => $startTime,
                'end_time'   => $endTime,
                'is_active'  => 1,
            ];

            $scheduleModel->insert($data);
            $addedCount++;
        }

        return redirect()->to(site_url('admin/staff-schedules'))->with('success', "Schedule added successfully for {$addedCount} day(s).");
    }

    public function deleteStaffSchedule($id)
    {
        helper(['url', 'form']);
        $id = (int) $id;

        $scheduleModel = model('App\\Models\\StaffScheduleModel');

        if ($id && $scheduleModel->find($id)) {
            $scheduleModel->delete($id);
        }

        return redirect()->to(site_url('admin/staff-schedules'))->with('success', 'Schedule deleted.');
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
                    ->select('medical_records.*, patients.first_name as patient_first_name, patients.last_name as patient_last_name, users.username as doctor_name')
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
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        
        $total = $paymentModel->countAllResults();
        $payments = $paymentModel
                    ->orderBy('paid_at', 'DESC')
                    ->findAll($perPage, $offset);
        
        // Calculate financial summary
        $today = date('Y-m-d');
        $outstandingBalance = 0;
        $overdueAmount = 0;
        $totalCollected = 0;
        
        // Get outstanding and overdue amounts
        $openInvoices = $invoiceModel->where('status !=', 'paid')->findAll();
        foreach ($openInvoices as $invoice) {
            $amount = (float) $invoice['amount'];
            $outstandingBalance += $amount;
            
            // Calculate overdue amount (invoices past due date)
            $dueDate = $invoice['due_date'] ?? $invoice['issued_at'];
            if (strtotime($dueDate) < strtotime($today)) {
                $overdueAmount += $amount;
            }
        }
        
        // Calculate total collected (sum of all payments)
        $totalCollected = (float) ($paymentModel
            ->selectSum('amount', 'total')
            ->first()['total'] ?? 0);
        
        // Calculate today's metrics
        $paymentsToday = (float) ($paymentModel
            ->selectSum('amount', 'total')
            ->where('DATE(paid_at)', $today)
            ->first()['total'] ?? 0);

        $invoicesToday = $invoiceModel
            ->where('DATE(issued_at)', $today)
            ->countAllResults();
        
        // Get recent invoices for the invoice records section (for standalone invoices)
        $invoices = $invoiceModel->orderBy('issued_at', 'DESC')->findAll(20);

        // Map total payments per invoice number for balance calculations
        $paymentsByInvoice = [];
        foreach ($paymentModel->findAll() as $p) {
            if (!empty($p['invoice_no'])) {
                $key = (string) $p['invoice_no'];
                $paymentsByInvoice[$key] = ($paymentsByInvoice[$key] ?? 0) + (float) ($p['amount'] ?? 0);
            }
        }

        // Build billing-style records for patients with active room assignments
        $patientsWithRooms = $patientModel
            ->where('assigned_room_id IS NOT NULL', null, false)
            ->where('assigned_room_id !=', null)
            ->findAll();

        $billingRecords = [];

        foreach ($patientsWithRooms as $patient) {
            $room = null;
            $roomCharges = 0;
            $daysStayed = 0;

            if ($patient['assigned_room_id']) {
                $room = $roomModel->find($patient['assigned_room_id']);

                if ($room && $patient['admission_date']) {
                    $admissionDate = new \DateTime($patient['admission_date']);
                    $currentDate = new \DateTime();
                    $daysStayed = $admissionDate->diff($currentDate)->days + 1;
                    $roomCharges = $daysStayed * (float) ($room['rate_per_day'] ?? 0);
                }
            }

            // Get invoices and payments for this patient by name
            $fullName = trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? ''));
            $patientInvoices = $invoiceModel->where('patient_name', $fullName)->findAll();
            $patientPayments = $paymentModel->where('patient_name', $fullName)->findAll();

            $totalInvoices = 0;
            foreach ($patientInvoices as $inv) {
                $totalInvoices += (float) ($inv['amount'] ?? 0);
            }

            $totalPayments = 0;
            foreach ($patientPayments as $pay) {
                $totalPayments += (float) ($pay['amount'] ?? 0);
            }

            $totalCharges = $roomCharges + $totalInvoices;
            $balanceDue = $totalCharges - $totalPayments;

            $billingRecords[] = [
                'patient' => $patient,
                'room' => $room,
                'roomCharges' => $roomCharges,
                'daysStayed' => $daysStayed,
                'totalInvoices' => $totalInvoices,
                'totalPayments' => $totalPayments,
                'totalCharges' => $totalCharges,
                'balanceDue' => $balanceDue,
            ];
        }
        
        $data = [
            'payments' => $payments,
            'invoices' => $invoices,
            'paymentsByInvoice' => $paymentsByInvoice,
            'billingRecords' => $billingRecords,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($payments)) < $total,
            'outstandingBalance' => $outstandingBalance,
            'overdueAmount' => $overdueAmount,
            'totalCollected' => $totalCollected,
            'paymentsToday' => $paymentsToday,
            'invoicesToday' => $invoicesToday,
            'openInvoicesCount' => count($openInvoices),
        ];
        return view('admin/payments_list', $data);
    }

    public function viewPayment($paymentId)
    {
        helper(['url']);
        $paymentModel = model('App\\Models\\PaymentModel');
        
        $payment = $paymentModel->find($paymentId);
        
        if (!$payment) {
            return redirect()->to(site_url('admin/payments'))->with('error', 'Payment not found.');
        }
        
        return view('admin/payment_view', [
            'payment' => $payment
        ]);
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
                    ->select('lab_tests.*, patients.first_name as patient_first_name, patients.last_name as patient_last_name, users.username as doctor_name')
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

    public function addMedicine()
    {
        helper(['form']);

        // If this is not a POST request, just go back to the medicines list
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(site_url('admin/medicines'));
        }

        $medicineModel = model('App\\Models\\MedicineModel');

        // Validate required fields
        $rules = [
            'medicine_code' => 'required|alpha_numeric|min_length[2]|max_length[20]',
            'name' => 'required|min_length[3]|max_length[255]',
            'unit' => 'required|max_length[20]',
            'selling_price' => 'required|numeric|greater_than[0]'
        ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $errors,
                ]);
            }

            return redirect()->to(site_url('admin/medicines'))
                             ->with('error', 'Validation failed. Please check the form.')
                             ->with('errors', $errors)
                             ->withInput();
        }

        // Check if medicine code already exists
        $existingMedicine = $medicineModel
            ->where('medicine_code', $this->request->getPost('medicine_code'))
            ->first();

        if ($existingMedicine) {
            $message = 'Medicine code already exists';

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $message]);
            }

            return redirect()->to(site_url('admin/medicines'))
                             ->with('error', $message)
                             ->withInput();
        }

        $data = [
            'medicine_code' => strtoupper(trim($this->request->getPost('medicine_code'))),
            'name'          => trim($this->request->getPost('name')),
            'generic_name'  => trim($this->request->getPost('generic_name')) ?: null,
            'category'      => $this->request->getPost('category') ?: null,
            'dosage_form'   => $this->request->getPost('dosage_form') ? strtolower((string) $this->request->getPost('dosage_form')) : null,
            'strength'      => trim($this->request->getPost('strength')) ?: null,
            'unit'          => trim($this->request->getPost('unit')),
            'purchase_price' => $this->request->getPost('cost_per_unit') ? (float) $this->request->getPost('cost_per_unit') : null,
            'selling_price' => (float) $this->request->getPost('selling_price'),
            'manufacturer'  => trim($this->request->getPost('manufacturer')) ?: null,
            'description'   => trim($this->request->getPost('description')) ?: null,
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        try {
            if ($medicineModel->insert($data)) {
                $successMessage = 'Medicine added successfully!';

                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => $successMessage,
                    ]);
                }

                return redirect()->to(site_url('admin/medicines'))
                                 ->with('success', $successMessage);
            }

            $errorMessage = 'Failed to add medicine to database';

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMessage,
                ]);
            }

            return redirect()->to(site_url('admin/medicines'))
                             ->with('error', $errorMessage)
                             ->withInput();
        } catch (\Exception $e) {
            log_message('error', 'Error adding medicine: ' . $e->getMessage());

            $errorMessage = 'Database error: ' . $e->getMessage();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMessage,
                ]);
            }

            return redirect()->to(site_url('admin/medicines'))
                             ->with('error', $errorMessage)
                             ->withInput();
        }
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

    // Add Stock Management
    public function addStock()
    {
        helper(['url', 'form']);
        
        try {
            $medicineModel = model('App\\Models\\MedicineModel');
            
            // Get all medicines for dropdown
            $medicines = $medicineModel->orderBy('name', 'ASC')->findAll();
            
            // If no medicines found, show empty array
            if (empty($medicines)) {
                $medicines = [];
            }
            
            return view('admin/add_stock', ['medicines' => $medicines]);
            
        } catch (\Exception $e) {
            // Log error and show message
            log_message('error', 'Error in addStock: ' . $e->getMessage());
            return view('admin/add_stock', ['medicines' => [], 'error' => 'Unable to load medicines. Please check database connection.']);
        }
    }
    
    public function storeStock()
    {
        helper(['url', 'form']);
        
        $medicineId = $this->request->getPost('medicine_id');
        $batchNumber = $this->request->getPost('batch_number');
        $expiryDate = $this->request->getPost('expiry_date');
        $quantityInStock = (int) $this->request->getPost('quantity_in_stock');
        $minimumStockLevel = (int) $this->request->getPost('minimum_stock_level');
        $maximumStockLevel = (int) $this->request->getPost('maximum_stock_level');
        $reorderLevel = (int) $this->request->getPost('reorder_level');
        $location = $this->request->getPost('location');
        $notes = $this->request->getPost('notes');
        
        // Validate required fields
        if (!$medicineId || !$batchNumber || !$expiryDate || $quantityInStock <= 0) {
            return redirect()->back()->with('error', 'Please fill in all required fields.')->withInput();
        }
        
        // Check if medicine exists
        $medicineModel = model('App\\Models\\MedicineModel');
        $medicine = $medicineModel->find($medicineId);
        
        if (!$medicine) {
            return redirect()->back()->with('error', 'Medicine not found.')->withInput();
        }
        
        // Check if batch already exists for this medicine
        $inventoryModel = model('App\\Models\\InventoryModel');
        $existingBatch = $inventoryModel
            ->where('medicine_id', $medicineId)
            ->where('batch_number', $batchNumber)
            ->first();
            
        if ($existingBatch) {
            return redirect()->back()->with('error', 'Batch number already exists for this medicine.')->withInput();
        }
        
        // Create new inventory record
        $data = [
            'medicine_id' => $medicineId,
            'batch_number' => $batchNumber,
            'expiry_date' => $expiryDate,
            'quantity_in_stock' => $quantityInStock,
            'minimum_stock_level' => $minimumStockLevel,
            'maximum_stock_level' => $maximumStockLevel,
            'reorder_level' => $reorderLevel,
            'location' => $location,
            'notes' => $notes,
            'status' => 'active',
        ];
        
        $inventoryModel->insert($data);
        
        // Log the action
        AuditLogger::log('stock_add', 'medicine_id=' . $medicineId . ' batch=' . $batchNumber . ' quantity=' . $quantityInStock);
        
        return redirect()->to(site_url('admin/inventory'))->with('success', 'Stock added successfully.');
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
