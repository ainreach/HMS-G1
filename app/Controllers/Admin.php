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
            'is_active'   => 1, // Default to active
        ];
        $password = (string)$req->getPost('password');
        
        // Validate required fields
        if (empty($data['employee_id']) || empty($data['username']) || empty($password) || empty($data['role'])) {
            return redirect()->back()->with('error','Employee ID, username, password, and role are required.')->withInput();
        }
        
        // Validate email if provided
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error','Invalid email format.')->withInput();
        }
        
        $users = model('App\\Models\\UserModel');
        
        // Check for duplicate employee_id
        if (!empty($data['employee_id'])) {
            $exists = $users->where('employee_id', $data['employee_id'])->first();
            if ($exists) {
                return redirect()->back()->with('error','Employee ID already exists.')->withInput();
            }
        }
        
        // Check for duplicate username
        $exists = $users->where('username', $data['username'])->first();
        if ($exists) {
            return redirect()->back()->with('error','Username already exists.')->withInput();
        }
        
        // Check for duplicate email if provided
        if (!empty($data['email'])) {
            $exists = $users->where('email', $data['email'])->first();
            if ($exists) {
                return redirect()->back()->with('error','Email already exists.')->withInput();
            }
        }
        
        // Get or create default branch
        $branchModel = model('App\\Models\\BranchModel');
        $existingBranch = $branchModel->first();
        if (!$existingBranch) {
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
            $data['branch_id'] = $branchModel->getInsertID();
        } else {
            $data['branch_id'] = $existingBranch['id'];
        }
        
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        try {
        $users->insert($data);
        AuditLogger::log('user_create', 'username=' . $data['username'] . ' role=' . $data['role']);
            return redirect()->to(site_url('admin/users'))->with('success','User created successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Error creating user: ' . $e->getMessage());
            return redirect()->back()->with('error','Error creating user. Please try again.')->withInput();
        }
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
        
        // Auto-fix doctors without username or password
        $this->autoFixDoctorCredentials($userModel);
        
        // Get total count before applying joins (more accurate)
        $total = $userModel->countAllResults();
        
        // Build query with joins
        $users = $userModel
                    ->select('users.id,users.employee_id,users.username,users.role,users.first_name,users.last_name,users.specialization,users.created_at,departments.name as department_name,departments.code as department_code')
                    ->join('departments', 'departments.id = users.department_id', 'left')
                    ->orderBy('users.created_at','DESC')
                    ->findAll($perPage, $offset);
        
        // Note: username is already in the select statement above
        
        // Calculate pagination
        $totalPages = ceil($total / $perPage);
        $hasPrev = $page > 1;
        $hasNext = $page < $totalPages && ($offset + count($users)) < $total;
        
        $data = [
            'users' => $users,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages,
            'hasPrev' => $hasPrev,
            'hasNext' => $hasNext,
        ];
        return view('admin/users_list', $data);
    }
    
    /**
     * Auto-fix doctors without username or password
     */
    private function autoFixDoctorCredentials($userModel)
    {
        // Get all doctors first
        $allDoctors = $userModel
            ->where('role', 'doctor')
            ->findAll();
        
        if (empty($allDoctors)) {
            return;
        }
        
        // Filter doctors that need fixing
        $doctorsToFix = [];
        foreach ($allDoctors as $doctor) {
            $needsUsername = empty($doctor['username']) || trim($doctor['username']) === '';
            $needsPassword = empty($doctor['password']) || trim($doctor['password']) === '';
            
            if ($needsUsername || $needsPassword) {
                $doctorsToFix[] = $doctor;
            }
        }
        
        if (empty($doctorsToFix)) {
            return;
        }
        
        $defaultPassword = password_hash('password', PASSWORD_DEFAULT);
        $fixed = 0;
        
        foreach ($doctorsToFix as $doctor) {
            $updateData = [];
            
            // Generate username if missing
            if (empty($doctor['username']) || trim($doctor['username']) === '') {
                $firstName = strtolower(trim($doctor['first_name'] ?? ''));
                $lastName = strtolower(trim($doctor['last_name'] ?? ''));
                
                if (empty($firstName) && empty($lastName)) {
                    // Fallback to employee_id if no name
                    $updateData['username'] = 'doc_' . strtolower(str_replace('-', '_', $doctor['employee_id'] ?? 'user' . $doctor['id']));
                } else {
                    // Create username from first_name + last_name
                    $baseUsername = $firstName . '_' . $lastName;
                    $baseUsername = preg_replace('/[^a-z0-9_]/', '', $baseUsername);
                    
                    if (empty($baseUsername)) {
                        $baseUsername = 'doc_' . strtolower(str_replace('-', '_', $doctor['employee_id'] ?? 'user' . $doctor['id']));
                    }
                    
                    // Check if username exists, add number if needed
                    $username = $baseUsername;
                    $counter = 1;
                    $existingUser = $userModel->where('username', $username)->where('id !=', $doctor['id'])->first();
                    while ($existingUser) {
                        $username = $baseUsername . $counter;
                        $counter++;
                        $existingUser = $userModel->where('username', $username)->where('id !=', $doctor['id'])->first();
                    }
                    
                    $updateData['username'] = $username;
                }
            }
            
            // Set default password if missing
            if (empty($doctor['password']) || trim($doctor['password']) === '') {
                $updateData['password'] = $defaultPassword;
            }
            
            // Update if there's something to fix
            if (!empty($updateData)) {
                $updateData['updated_at'] = date('Y-m-d H:i:s');
                try {
                    $userModel->update($doctor['id'], $updateData);
                    $fixed++;
                } catch (\Exception $e) {
                    log_message('error', 'Failed to update doctor credentials: ' . $e->getMessage());
                }
            }
        }
        
        if ($fixed > 0) {
            log_message('info', "Auto-fixed credentials for {$fixed} doctor(s)");
        }
    }
    
    /**
     * Manual fix route - force update all doctor credentials
     */
    public function fixDoctorCredentials()
    {
        helper(['url']);
        $userModel = model('App\\Models\\UserModel');
        
        // Get ALL doctors and force update their credentials
        $allDoctors = $userModel->where('role', 'doctor')->findAll();
        
        if (empty($allDoctors)) {
            return redirect()->to(site_url('admin/users'))->with('error', 'No doctors found.');
        }
        
        $defaultPassword = password_hash('password', PASSWORD_DEFAULT);
        $fixed = 0;
        
        foreach ($allDoctors as $doctor) {
            $updateData = [];
            
            // Always ensure username is set
            if (empty($doctor['username']) || trim($doctor['username']) === '') {
                $firstName = strtolower(trim($doctor['first_name'] ?? ''));
                $lastName = strtolower(trim($doctor['last_name'] ?? ''));
                
                if (empty($firstName) && empty($lastName)) {
                    $updateData['username'] = 'doc_' . strtolower(str_replace('-', '_', $doctor['employee_id'] ?? 'user' . $doctor['id']));
                } else {
                    $baseUsername = $firstName . '_' . $lastName;
                    $baseUsername = preg_replace('/[^a-z0-9_]/', '', $baseUsername);
                    
                    if (empty($baseUsername)) {
                        $baseUsername = 'doc_' . strtolower(str_replace('-', '_', $doctor['employee_id'] ?? 'user' . $doctor['id']));
                    }
                    
                    $username = $baseUsername;
                    $counter = 1;
                    $existingUser = $userModel->where('username', $username)->where('id !=', $doctor['id'])->first();
                    while ($existingUser) {
                        $username = $baseUsername . $counter;
                        $counter++;
                        $existingUser = $userModel->where('username', $username)->where('id !=', $doctor['id'])->first();
                    }
                    
                    $updateData['username'] = $username;
                }
            }
            
            // ALWAYS set password to default (force update)
            $updateData['password'] = $defaultPassword;
            
            // Update
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            try {
                $userModel->update($doctor['id'], $updateData);
                $fixed++;
            } catch (\Exception $e) {
                log_message('error', 'Failed to update doctor credentials: ' . $e->getMessage());
            }
        }
        
        if ($fixed > 0) {
            log_message('info', "Force-updated credentials for {$fixed} doctor(s)");
            return redirect()->to(site_url('admin/users'))->with('success', "Successfully updated credentials for {$fixed} doctor(s). All passwords are now set to 'password'.");
        } else {
            return redirect()->to(site_url('admin/users'))->with('error', 'No doctors were updated.');
        }
    }

    public function editUser($id)
    {
        helper(['url','form']);
        $id = (int) $id;
        
        // Prevent admin from editing their own account
        $currentUserId = session('user_id');
        if ($id === $currentUserId) {
            return redirect()->to(site_url('admin/users'))->with('error','You cannot edit your own account. Please ask another administrator to make changes.');
        }
        
        $user = model('App\\Models\\UserModel')->find($id);
        if (!$user) {
            return redirect()->to(site_url('admin/users'))->with('error','User not found.');
        }
        
        // Get departments for doctor assignment
        $departmentModel = model('App\\Models\\DepartmentModel');
        $departments = $departmentModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        
        return view('admin/user_edit', [
            'user' => $user,
            'departments' => $departments,
        ]);
    }

    public function updateUser($id)
    {
        helper(['url','form']);
        $id = (int) $id;
        
        // Prevent admin from editing their own account
        $currentUserId = session('user_id');
        if ($id === $currentUserId) {
            return redirect()->to(site_url('admin/users'))->with('error','You cannot edit your own account. Please ask another administrator to make changes.');
        }
        
        $req = $this->request;
        
        // Validate required fields
        $username = trim((string)$req->getPost('username'));
        if (empty($username)) {
            return redirect()->back()->with('error','Username is required.')->withInput();
        }
        
        $data = [
            'employee_id' => trim((string)$req->getPost('employee_id')),
            'username'    => $username,
            'email'       => trim((string)$req->getPost('email')),
            'first_name'  => trim((string)$req->getPost('first_name')),
            'last_name'   => trim((string)$req->getPost('last_name')),
            'is_active'   => 1, // Keep user active
            'specialization' => trim((string)$req->getPost('specialization')) ?: null,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        
        // Add department_id only if role is doctor
        $role = trim((string)$req->getPost('role'));
        if ($role === 'doctor') {
            $departmentId = $req->getPost('department_id') ? (int)$req->getPost('department_id') : null;
            $data['department_id'] = $departmentId;
        } else {
            // Clear department if not a doctor
            $data['department_id'] = null;
        }
        
        $users = model('App\\Models\\UserModel');
        $existingUser = $users->find($id);
        if (!$existingUser) {
            return redirect()->to(site_url('admin/users'))->with('error','User not found.');
        }
        
        // Check for duplicate employee_id (if changed)
        if (!empty($data['employee_id']) && $data['employee_id'] !== ($existingUser['employee_id'] ?? '')) {
            $exists = $users->where('employee_id', $data['employee_id'])->where('id !=', $id)->first();
            if ($exists) {
                return redirect()->back()->with('error','Employee ID already exists.')->withInput();
            }
        }
        
        // Check for duplicate username (if changed)
        if (!empty($data['username']) && $data['username'] !== ($existingUser['username'] ?? '')) {
            $exists = $users->where('username', $data['username'])->where('id !=', $id)->first();
            if ($exists) {
                return redirect()->back()->with('error','Username already exists.')->withInput();
            }
        }
        
        // Check for duplicate email (if changed)
        if (!empty($data['email']) && $data['email'] !== ($existingUser['email'] ?? '')) {
            $exists = $users->where('email', $data['email'])->where('id !=', $id)->first();
            if ($exists) {
                return redirect()->back()->with('error','Email already exists.')->withInput();
            }
        }
        
        // Validate email format if provided
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error','Invalid email format.')->withInput();
        }
        
        try {
        $users->update($id, $data);
        AuditLogger::log('user_update', 'user_id=' . $id);
            return redirect()->to(site_url('admin/users'))->with('success','User updated successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Error updating user: ' . $e->getMessage());
            return redirect()->back()->with('error','Error updating user. Please try again.')->withInput();
        }
    }


    // Patient Management
    public function patients()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        
        // Build query
        $builder = $patientModel->select('patients.*, rooms.room_number, rooms.room_type, beds.bed_number')
            ->join('rooms', 'rooms.id = patients.assigned_room_id', 'left')
            ->join('beds', 'beds.id = patients.assigned_bed_id', 'left');
        
        // Apply search filter (prefix match on each field)
        if (!empty($search)) {
            $builder->groupStart()
                ->like('patients.first_name', $search, 'after')
                ->orLike('patients.last_name', $search, 'after')
                ->orLike('patients.patient_id', $search, 'after')
                ->orLike('patients.phone', $search, 'after')
                ->orLike('patients.email', $search, 'after')
                ->groupEnd();
        }
        
        // Apply status filter
        if ($statusFilter === 'admitted') {
            $builder->where('patients.admission_type', 'admission')
                ->where('patients.assigned_room_id IS NOT NULL', null, false)
                ->where('(patients.discharge_date IS NULL OR patients.discharge_date = "")', null, false);
        } elseif ($statusFilter === 'discharged') {
            $builder->where('patients.discharge_date IS NOT NULL', null, false)
                ->where('patients.discharge_date !=', '');
        } elseif ($statusFilter === 'outpatient') {
            $builder->where('(patients.admission_type IS NULL OR patients.admission_type = "checkup" OR patients.admission_type = "")', null, false)
                ->where('(patients.assigned_room_id IS NULL OR patients.assigned_room_id = "")', null, false);
        }
        
        // Apply gender filter
        if ($genderFilter !== 'all') {
            $builder->where('patients.gender', $genderFilter);
        }
        
        // Apply date range filter
        if (!empty($dateFrom)) {
            $builder->where('DATE(patients.created_at) >=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $builder->where('DATE(patients.created_at) <=', $dateTo);
        }
        
        // Get total count for pagination
        $total = $builder->countAllResults(false);
        
        // Apply sorting
        $validSortColumns = ['created_at', 'first_name', 'last_name', 'patient_id', 'admission_date', 'discharge_date'];
        $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'created_at';
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        $builder->orderBy('patients.' . $sortBy, $sortOrder);
        
        // Apply pagination
        $patients = $builder->findAll($perPage, $offset);
        
        // Enhance patient data with status and room info
        foreach ($patients as &$patient) {
            // Determine patient status
            $patient['status'] = 'outpatient';
            $patient['status_label'] = 'Out-Patient';
            $patient['status_color'] = '#6b7280';
            
            if (!empty($patient['discharge_date'])) {
                $patient['status'] = 'discharged';
                $patient['status_label'] = 'Discharged';
                $patient['status_color'] = '#16a34a';
            } elseif (!empty($patient['assigned_room_id']) && $patient['admission_type'] === 'admission') {
                $patient['status'] = 'admitted';
                $patient['status_label'] = 'Admitted';
                $patient['status_color'] = '#3b82f6';
                
                // Check for critical condition (simplified - can be enhanced with medical records)
                // For now, we'll mark as critical if they have recent urgent lab tests or medical records
            }
            
            // Format room info
            if (!empty($patient['room_number'])) {
                $patient['room_display'] = $patient['room_number'];
                if (!empty($patient['bed_number'])) {
                    $patient['room_display'] .= ' - Bed ' . $patient['bed_number'];
                }
            } else {
                $patient['room_display'] = 'N/A';
            }
        }
        
        // Calculate pagination
        $totalPages = ceil($total / $perPage);
        $hasPrev = $page > 1;
        $hasNext = $page < $totalPages;
        
        // Get statistics
        $stats = [
            'total' => $patientModel->countAllResults(),
            'admitted' => $patientModel->where('admission_type', 'admission')
                ->where('assigned_room_id IS NOT NULL', null, false)
                ->where('(discharge_date IS NULL OR discharge_date = "")', null, false)
                ->countAllResults(),
            'discharged' => $patientModel->where('discharge_date IS NOT NULL', null, false)
                ->where('discharge_date !=', '')
                ->countAllResults(),
            'outpatient' => $patientModel->where('(admission_type IS NULL OR admission_type = "checkup" OR admission_type = "")', null, false)
                ->where('(assigned_room_id IS NULL OR assigned_room_id = "")', null, false)
                ->countAllResults(),
        ];
        
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

    public function editAppointment($id)
    {
        helper('url');
        $apptModel = model('App\\Models\\AppointmentModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');
        
        $appointment = $apptModel->find($id);
        if (!$appointment) {
            return redirect()->to(site_url('admin/appointments'))->with('error', 'Appointment not found.');
        }

        $patients = $patientModel->where('is_active', 1)->orderBy('last_name', 'ASC')->findAll(100);
        $doctors = $userModel->where('role', 'doctor')->where('is_active', 1)->orderBy('first_name', 'ASC')->findAll(20);

        return view('admin/appointment_edit', [
            'appointment' => $appointment,
            'patients' => $patients,
            'doctors' => $doctors,
        ]);
    }

    public function updateAppointment($id)
    {
        helper(['url', 'form']);
        $apptModel = model('App\\Models\\AppointmentModel');
        
        $appointment = $apptModel->find($id);
        if (!$appointment) {
            return redirect()->to(site_url('admin/appointments'))->with('error', 'Appointment not found.');
        }

        $data = [
            'patient_id' => (int) $this->request->getPost('patient_id'),
            'doctor_id' => (int) $this->request->getPost('doctor_id'),
            'appointment_date' => $this->request->getPost('appointment_date'),
            'appointment_time' => $this->request->getPost('appointment_time'),
            'duration' => (int) ($this->request->getPost('duration') ?: 30),
            'type' => $this->request->getPost('type') ?: 'consultation',
            'status' => $this->request->getPost('status') ?: 'scheduled',
            'reason' => $this->request->getPost('reason') ?: null,
            'notes' => $this->request->getPost('notes') ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $apptModel->update($id, $data);
        AuditLogger::log('appointment_update', 'appointment_id=' . $id);
        return redirect()->to(site_url('admin/appointments'))->with('success', 'Appointment updated successfully.');
    }

    public function deleteAppointment($id)
    {
        helper('url');
        $apptModel = model('App\\Models\\AppointmentModel');
        
        $appointment = $apptModel->find($id);
        if (!$appointment) {
            return redirect()->to(site_url('admin/appointments'))->with('error', 'Appointment not found.');
        }

        // Soft delete
        $apptModel->delete($id);
        AuditLogger::log('appointment_delete', 'appointment_id=' . $id);
        return redirect()->to(site_url('admin/appointments'))->with('success', 'Appointment deleted successfully.');
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

    public function staffScheduleYearEvents()
    {
        helper(['url']);

        $request = $this->request;
        $year = (int) ($request->getGet('year') ?? date('Y'));
        if ($year < 2000 || $year > 2100) {
            $year = (int) date('Y');
        }

        $userId = (int) ($request->getGet('user_id') ?? 0);

        $scheduleModel = model('App\\Models\\StaffScheduleModel');

        $builder = $scheduleModel
            ->select('staff_schedules.*, users.first_name, users.last_name, users.username, users.role')
            ->join('users', 'users.id = staff_schedules.user_id')
            ->where('staff_schedules.is_active', 1);

        if ($userId > 0) {
            $builder->where('staff_schedules.user_id', $userId);
        }

        $schedules = $builder->findAll(1000);

        $events = [];

        $dayMap = [
            'sunday' => 0,
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
        ];

        foreach ($schedules as $row) {
            $dayOfWeek = strtolower((string) ($row['day_of_week'] ?? ''));
            if (!array_key_exists($dayOfWeek, $dayMap)) {
                continue;
            }

            $dow = $dayMap[$dayOfWeek];

            $startDate = new \DateTime($year . '-01-01');
            $endDate = new \DateTime($year . '-12-31');
            $endDate->setTime(23, 59, 59);

            while ((int) $startDate->format('w') !== $dow) {
                $startDate->modify('+1 day');
            }

            $startTime = (string) ($row['start_time'] ?? '00:00:00');
            $endTime = (string) ($row['end_time'] ?? '00:00:00');

            $fullName = trim((string) ($row['first_name'] ?? '') . ' ' . (string) ($row['last_name'] ?? ''));
            if ($fullName === '') {
                $fullName = (string) ($row['username'] ?? 'Staff');
            }

            $labelParts = [];
            if ($fullName !== '') {
                $labelParts[] = $fullName;
            }
            if (!empty($row['role'])) {
                $labelParts[] = ucfirst((string) $row['role']);
            }

            $title = trim(implode(' - ', $labelParts));
            if ($title === '') {
                $title = 'Shift';
            }

            $current = clone $startDate;
            while ($current <= $endDate) {
                $dateStr = $current->format('Y-m-d');

                $events[] = [
                    'title' => $title,
                    'start' => $dateStr . 'T' . $startTime,
                    'end' => $dateStr . 'T' . $endTime,
                    'extendedProps' => [
                        'user_id' => (int) ($row['user_id'] ?? 0),
                        'role' => (string) ($row['role'] ?? ''),
                        'day_of_week' => $dayOfWeek,
                    ],
                ];

                $current->modify('+1 week');
            }
        }

        return $this->response->setJSON($events);
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
        
        // Include soft-deleted records so admins can see and restore them
        $total = $medicalRecordModel->withDeleted()->countAllResults();
        $records = $medicalRecordModel
                    ->withDeleted()
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

    public function editMedicalRecord($id)
    {
        helper(['url']);
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $patientModel = model('App\\Models\\PatientModel');

        $record = $medicalRecordModel->find($id);
        if (!$record) {
            return redirect()->to(site_url('admin/medical-records'))->with('error', 'Medical record not found.');
        }

        $patients = $patientModel->where('is_active', 1)->orderBy('last_name', 'ASC')->findAll(100);

        return view('admin/medical_record_edit', [
            'record' => $record,
            'patients' => $patients,
        ]);
    }

    public function updateMedicalRecord($id)
    {
        helper(['url', 'form']);
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');

        $record = $medicalRecordModel->find($id);
        if (!$record) {
            return redirect()->to(site_url('admin/medical-records'))->with('error', 'Medical record not found.');
        }

        $normalizeJson = function ($raw) {
            if ($raw === null) {
                return null;
            }
            $raw = trim((string) $raw);
            if ($raw === '') {
                return null;
            }

            json_decode($raw);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $raw;
            }

            return json_encode(['text' => $raw], JSON_UNESCAPED_UNICODE);
        };

        $vitalSignsInput = $this->request->getPost('vital_signs');
        $medicationsInput = $this->request->getPost('medications_prescribed');

        $data = [
            'patient_id' => (int) $this->request->getPost('patient_id'),
            'visit_date' => $this->request->getPost('visit_date') ?: date('Y-m-d H:i:s'),
            'chief_complaint' => $this->request->getPost('chief_complaint') ?: null,
            'history_present_illness' => $this->request->getPost('history_present_illness') ?: null,
            'physical_examination' => $this->request->getPost('physical_examination') ?: null,
            'vital_signs' => $normalizeJson($vitalSignsInput),
            'diagnosis' => $this->request->getPost('diagnosis') ?: null,
            'treatment_plan' => $this->request->getPost('treatment_plan') ?: null,
            'medications_prescribed' => $normalizeJson($medicationsInput),
            'follow_up_instructions' => $this->request->getPost('follow_up_instructions') ?: null,
            'next_visit_date' => $this->request->getPost('next_visit_date') ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $medicalRecordModel->update($id, $data);

        return redirect()->to(site_url('admin/medical-records'))->with('success', 'Medical record updated successfully.');
    }

    public function deleteMedicalRecord($id)
    {
        helper(['url']);
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');

        $record = $medicalRecordModel->find($id);
        if (!$record) {
            return redirect()->to(site_url('admin/medical-records'))->with('error', 'Medical record not found.');
        }

        // Soft delete the record
        $medicalRecordModel->delete($id);

        return redirect()->to(site_url('admin/medical-records'))->with('success', 'Medical record deleted. You can restore it from the list.');
    }

    public function restoreMedicalRecord($id)
    {
        helper(['url']);
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');

        // Need withDeleted() to find soft-deleted rows
        $record = $medicalRecordModel->withDeleted()->find($id);
        if (!$record) {
            return redirect()->to(site_url('admin/medical-records'))->with('error', 'Medical record not found.');
        }

        if (empty($record['deleted_at'])) {
            return redirect()->to(site_url('admin/medical-records'))->with('info', 'Medical record is already active.');
        }

        // Allow updating deleted_at even though it is not in allowedFields
        $medicalRecordModel->protect(false)->update($id, ['deleted_at' => null]);

        return redirect()->to(site_url('admin/medical-records'))->with('success', 'Medical record restored.');
    }

    // Get medical record details as JSON (for modal display)
    public function getMedicalRecord($id)
    {
        helper(['url']);
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        
        $record = $medicalRecordModel
            ->select('medical_records.*, patients.first_name as patient_first_name, patients.last_name as patient_last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender, users.first_name as doctor_first_name, users.last_name as doctor_last_name')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->join('users', 'users.id = medical_records.doctor_id')
            ->where('medical_records.id', $id)
            ->first();
        
        if (!$record) {
            return $this->response->setJSON(['error' => 'Record not found'])->setStatusCode(404);
        }
        
        return $this->response->setJSON($record);
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
            'name'           => 'required|min_length[3]|max_length[255]',
            'unit'           => 'required|max_length[20]',
            'cost_per_unit'  => 'required|numeric',
            'selling_price'  => 'required|numeric|greater_than[0]',
            'stock_quantity' => 'required|integer',
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

        $data = [
            'name'            => trim($this->request->getPost('name')),
            'unit'            => trim($this->request->getPost('unit')),
            'price'           => (float) $this->request->getPost('cost_per_unit'),
            'retail_price'    => (float) $this->request->getPost('selling_price'),
            'stock'           => (int) $this->request->getPost('stock_quantity'),
            'expiration_date' => $this->request->getPost('expiry_date') ?: null,
            'is_active'       => $this->request->getPost('is_active') ? 1 : 0,
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

    public function editMedicine($id)
    {
        helper(['url','form']);
        $medicineModel = model('App\\Models\\MedicineModel');
        $medicine = $medicineModel->find((int) $id);
        if (! $medicine) {
            return redirect()->to(site_url('admin/medicines'))->with('error', 'Medicine not found.');
        }

        // Reuse pharmacist form but allow custom action
        return view('pharmacy/medicine_form', [
            'medicine'   => $medicine,
            'formAction' => site_url('admin/medicines/save/' . $id),
        ]);
    }

    public function updateMedicine($id)
    {
        helper(['form','url']);
        $medicineModel = model('App\\Models\\MedicineModel');

        $rules = [
            'name'           => 'required|min_length[3]|max_length[255]',
            'purchase_price' => 'required|numeric',
            'selling_price'  => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'            => $this->request->getPost('name'),
            'unit'            => $this->request->getPost('unit'),
            'price'           => $this->request->getPost('purchase_price'),
            'retail_price'    => $this->request->getPost('selling_price'),
            'stock'           => $this->request->getPost('stock_quantity'),
            'expiration_date' => $this->request->getPost('expiry_date'),
            'is_active'       => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $medicineModel->update((int) $id, $data);

        return redirect()->to(site_url('admin/medicines'))->with('success', 'Medicine updated successfully.');
    }

    public function deleteMedicine($id)
    {
        helper(['url']);
        $medicineModel = model('App\\Models\\MedicineModel');
        $medicine = $medicineModel->find((int) $id);

        if ($medicine) {
            $medicineModel->delete((int) $id);
        }

        return redirect()->to(site_url('admin/medicines'))->with('success', 'Medicine deleted successfully.');
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
                    ->select('inventory.*, medicines.name as medicine_name')
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
            'branch_id' => 1, // Default branch ID
            'batch_number' => $batchNumber,
            'expiry_date' => $expiryDate,
            'quantity_in_stock' => $quantityInStock,
            'minimum_stock_level' => $minimumStockLevel,
            'maximum_stock_level' => $maximumStockLevel,
            'reorder_level' => $reorderLevel,
            'location' => $location,
            'last_updated_by' => session('user_id') ?: 1,
        ];
        
        $inventoryModel->insert($data);
        
        // Log the action
        AuditLogger::log('stock_add', 'medicine_id=' . $medicineId . ' batch=' . $batchNumber . ' quantity=' . $quantityInStock);
        
        return redirect()->to(site_url('admin/inventory'))->with('success', 'Stock added successfully.');
    }

    public function editStock($id)
    {
        helper(['url', 'form']);
        $inventoryModel = model('App\\Models\\InventoryModel');
        $medicineModel = model('App\\Models\\MedicineModel');
        
        $stock = $inventoryModel
            ->select('inventory.*, medicines.name as medicine_name')
            ->join('medicines', 'medicines.id = inventory.medicine_id')
            ->where('inventory.id', $id)
            ->first();
        
        if (!$stock) {
            return redirect()->to(site_url('admin/inventory'))->with('error', 'Stock entry not found.');
        }

        $medicines = $medicineModel->orderBy('name', 'ASC')->findAll();

        return view('admin/edit_stock', [
            'stock' => $stock,
            'medicines' => $medicines,
        ]);
    }

    public function updateStock($id)
    {
        helper(['url', 'form']);
        $inventoryModel = model('App\\Models\\InventoryModel');
        
        $stock = $inventoryModel->find($id);
        if (!$stock) {
            return redirect()->to(site_url('admin/inventory'))->with('error', 'Stock entry not found.');
        }

        $batchNumber = $this->request->getPost('batch_number');
        $expiryDate = $this->request->getPost('expiry_date');
        $quantityInStock = (int) $this->request->getPost('quantity_in_stock');
        $minimumStockLevel = (int) $this->request->getPost('minimum_stock_level');
        $maximumStockLevel = (int) $this->request->getPost('maximum_stock_level');
        $reorderLevel = (int) $this->request->getPost('reorder_level');
        $location = $this->request->getPost('location');

        // Validate required fields
        if (!$batchNumber || !$expiryDate || $quantityInStock < 0) {
            return redirect()->back()->with('error', 'Please fill in all required fields.')->withInput();
        }

        // Check if batch number already exists for another stock entry
        $existingBatch = $inventoryModel
            ->where('medicine_id', $stock['medicine_id'])
            ->where('batch_number', $batchNumber)
            ->where('id !=', $id)
            ->first();
            
        if ($existingBatch) {
            return redirect()->back()->with('error', 'Batch number already exists for this medicine.')->withInput();
        }

        $data = [
            'batch_number' => $batchNumber,
            'expiry_date' => $expiryDate,
            'quantity_in_stock' => $quantityInStock,
            'minimum_stock_level' => $minimumStockLevel,
            'maximum_stock_level' => $maximumStockLevel,
            'reorder_level' => $reorderLevel,
            'location' => $location,
            'last_updated_by' => session('user_id') ?: 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $inventoryModel->update($id, $data);
        AuditLogger::log('stock_update', 'stock_id=' . $id . ' batch=' . $batchNumber . ' quantity=' . $quantityInStock);
        
        return redirect()->to(site_url('admin/inventory'))->with('success', 'Stock updated successfully.');
    }

    public function deleteStock($id)
    {
        helper('url');
        $inventoryModel = model('App\\Models\\InventoryModel');
        
        $stock = $inventoryModel->find($id);
        if (!$stock) {
            return redirect()->to(site_url('admin/inventory'))->with('error', 'Stock entry not found.');
        }

        // Soft delete
        $inventoryModel->delete($id);
        AuditLogger::log('stock_delete', 'stock_id=' . $id);
        
        return redirect()->to(site_url('admin/inventory'))->with('success', 'Stock entry deleted successfully.');
    }

    // Room Management (Admin access)
    public function rooms()
    {
        helper('url');
        $roomModel = model('App\\Models\\RoomModel');
        $branchModel = model('App\\Models\\BranchModel');
        
        $existingBranch = $branchModel->first();
        if (!$existingBranch) {
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
        }
        
        $allRooms = $roomModel
            ->select('rooms.*, branches.name as branch_name')
            ->join('branches', 'branches.id = rooms.branch_id', 'left')
            ->orderBy('branches.name', 'ASC')
            ->orderBy('rooms.floor', 'ASC')
            ->orderBy('rooms.room_number', 'ASC')
            ->findAll(100);
            
        $totalRooms = count($allRooms);
        $availableRooms = array_filter($allRooms, fn($room) => $room['status'] === 'available');
        $occupiedRooms = array_filter($allRooms, fn($room) => $room['status'] === 'occupied');
        $maintenanceRooms = array_filter($allRooms, fn($room) => $room['status'] === 'maintenance');
        
        $stats = [
            'total' => $totalRooms,
            'available' => count($availableRooms),
            'occupied' => count($occupiedRooms),
            'maintenance' => count($maintenanceRooms),
            'occupancy_rate' => $totalRooms > 0 ? round((count($occupiedRooms) / $totalRooms) * 100, 1) : 0
        ];
        
        return view('admin/rooms', [
            'rooms' => $allRooms,
            'stats' => $stats
        ]);
    }

    public function newRoom()
    {
        helper('url');
        return view('admin/room_form');
    }

    public function editRoom($id)
    {
        helper('url');
        $roomModel = model('App\\Models\\RoomModel');
        
        $room = $roomModel->find($id);
        if (!$room) {
            return redirect()->to('/admin/rooms')->with('error', 'Room not found.');
        }
        
        return view('admin/room_form', ['room' => $room]);
    }

    public function storeRoom()
    {
        helper(['url', 'form']);
        $roomModel = model('App\\Models\\RoomModel');
        $branchModel = model('App\\Models\\BranchModel');
        
        $existingBranch = $branchModel->first();
        if (!$existingBranch) {
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
        
        $id = $this->request->getPost('id');
        
        $data = [
            'branch_id' => $branchId,
            'room_number' => $this->request->getPost('room_number'),
            'room_type' => $this->request->getPost('room_type'),
            'floor' => (int) $this->request->getPost('floor'),
            'capacity' => (int) $this->request->getPost('capacity'),
            'rate_per_day' => (float) $this->request->getPost('rate_per_day'),
            'status' => $this->request->getPost('status') ?? 'available',
        ];
        
        if (!$data['room_number'] || !$data['room_type'] || $data['floor'] < 1 || $data['capacity'] < 1) {
            return redirect()->back()->with('error', 'Please fill all required fields correctly.')->withInput();
        }
        
        try {
            if ($id) {
                $data['id'] = $id;
                $roomModel->save($data);
                $message = 'Room updated successfully.';
            } else {
                $roomModel->save($data);
                $message = 'Room added successfully.';
            }
            
            AuditLogger::log('room_' . ($id ? 'update' : 'create'), 'room_id=' . ($id ?: 'new') . ' room_number=' . $data['room_number']);
            return redirect()->to('/admin/rooms')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving room: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteRoom($id)
    {
        helper('url');
        $roomModel = model('App\\Models\\RoomModel');
        
        $room = $roomModel->find($id);
        if (!$room) {
            return redirect()->to('/admin/rooms')->with('error', 'Room not found.');
        }
        
        if ($room['current_occupancy'] > 0) {
            return redirect()->to('/admin/rooms')->with('error', 'Cannot delete room. Room is currently occupied.');
        }
        
        try {
            $roomModel->delete($id);
            AuditLogger::log('room_delete', 'room_id=' . $id);
            return redirect()->to('/admin/rooms')->with('success', 'Room deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->to('/admin/rooms')->with('error', 'Error deleting room: ' . $e->getMessage());
        }
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

    public function departments()
    {
        helper('url');
        $departmentModel = model('App\Models\DepartmentModel');
        
        // Get all departments with stats (doctor counts, head doctor info)
        $departments = $departmentModel->getDepartmentsWithStats();
        
        return view('admin/departments', [
            'departments' => $departments
        ]);
    }
    
    public function newDepartment()
    {
        helper(['url', 'form']);
        $userModel = model('App\Models\UserModel');
        
        // Get all doctors for head doctor selection
        $doctors = $userModel
            ->where('role', 'doctor')
            ->where('is_active', 1)
            ->orderBy('first_name', 'ASC')
            ->findAll();
        
        return view('admin/department_form', [
            'department' => null,
            'doctors' => $doctors,
            'formAction' => site_url('admin/departments'),
        ]);
    }
    
    public function storeDepartment()
    {
        helper(['url', 'form']);
        $departmentModel = model('App\Models\DepartmentModel');
        
        $data = [
            'name' => trim((string)$this->request->getPost('name')),
            'code' => trim((string)$this->request->getPost('code')),
            'description' => trim((string)$this->request->getPost('description')) ?: null,
            'head_doctor_id' => $this->request->getPost('head_doctor_id') ? (int)$this->request->getPost('head_doctor_id') : null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];
        
        // Validate required fields
        if (empty($data['name']) || empty($data['code'])) {
            return redirect()->back()->with('error', 'Name and code are required.')->withInput();
        }
        
        // Check for duplicate code
        $existing = $departmentModel->where('code', $data['code'])->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Department code already exists.')->withInput();
        }
        
        try {
            $departmentModel->insert($data);
            AuditLogger::log('department_create', 'name=' . $data['name'] . ' code=' . $data['code']);
            return redirect()->to(site_url('admin/departments'))->with('success', 'Department created successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Error creating department: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating department. Please try again.')->withInput();
        }
    }
    
    public function editDepartment($id)
    {
        helper(['url', 'form']);
        $departmentModel = model('App\Models\DepartmentModel');
        $userModel = model('App\Models\UserModel');
        
        $department = $departmentModel->find($id);
        if (!$department) {
            return redirect()->to(site_url('admin/departments'))->with('error', 'Department not found.');
        }
        
        // Get all doctors for head doctor selection
        $doctors = $userModel
            ->where('role', 'doctor')
            ->where('is_active', 1)
            ->orderBy('first_name', 'ASC')
            ->findAll();
        
        // Get doctors in this department
        $departmentDoctors = $departmentModel->getDoctorsInDepartment($id);
        
        return view('admin/department_form', [
            'department' => $department,
            'doctors' => $doctors,
            'departmentDoctors' => $departmentDoctors,
            'formAction' => site_url('admin/departments/' . $id),
        ]);
    }
    
    public function updateDepartment($id)
    {
        helper(['url', 'form']);
        $departmentModel = model('App\Models\DepartmentModel');
        
        $department = $departmentModel->find($id);
        if (!$department) {
            return redirect()->to(site_url('admin/departments'))->with('error', 'Department not found.');
        }
        
        $data = [
            'name' => trim((string)$this->request->getPost('name')),
            'code' => trim((string)$this->request->getPost('code')),
            'description' => trim((string)$this->request->getPost('description')) ?: null,
            'head_doctor_id' => $this->request->getPost('head_doctor_id') ? (int)$this->request->getPost('head_doctor_id') : null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];
        
        // Validate required fields
        if (empty($data['name']) || empty($data['code'])) {
            return redirect()->back()->with('error', 'Name and code are required.')->withInput();
            }
            
        // Check for duplicate code (excluding current department)
        $existing = $departmentModel->where('code', $data['code'])->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Department code already exists.')->withInput();
        }
        
        try {
            $departmentModel->update($id, $data);
            AuditLogger::log('department_update', 'department_id=' . $id . ' name=' . $data['name']);
            return redirect()->to(site_url('admin/departments'))->with('success', 'Department updated successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Error updating department: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating department. Please try again.')->withInput();
        }
    }
    
    public function deleteDepartment($id)
    {
        helper('url');
        $departmentModel = model('App\Models\DepartmentModel');
        $userModel = model('App\Models\UserModel');
        
        $department = $departmentModel->find($id);
        if (!$department) {
            return redirect()->to(site_url('admin/departments'))->with('error', 'Department not found.');
        }
        
        // Check if department has doctors assigned
        $doctorCount = $userModel
            ->where('department_id', $id)
            ->where('role', 'doctor')
            ->countAllResults();
        
        if ($doctorCount > 0) {
            return redirect()->to(site_url('admin/departments'))->with('error', 'Cannot delete department. There are ' . $doctorCount . ' doctor(s) assigned to this department. Please reassign them first.');
        }
        
        try {
            $departmentModel->delete($id);
            AuditLogger::log('department_delete', 'department_id=' . $id);
            return redirect()->to(site_url('admin/departments'))->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            log_message('error', 'Error deleting department: ' . $e->getMessage());
            return redirect()->to(site_url('admin/departments'))->with('error', 'Error deleting department. Please try again.');
        }
    }
}
