<?php
namespace App\Controllers;

class Nurse extends BaseController
{
    public function dashboard()
    {
        helper('url');
        $patientsModel = model('App\\Models\\PatientModel');
        $apptModel = model('App\\Models\\AppointmentModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $labTestModel = model('App\\Models\\LabTestModel');
        $dailyScheduleModel = model('App\\Models\\DailyScheduleModel');

        $today = date('Y-m-d');
        $nurseId = session('user_id') ?: 1; // Fallback for testing

        // KPIs
        $activePatients = $patientsModel->where('is_active', 1)->countAllResults();
        $pendingTasks = $apptModel
            ->where('DATE(appointment_date)', $today)
            ->where('status', 'scheduled')
            ->countAllResults();
        
        $vitalsRecorded = $medicalRecordModel
            ->where('DATE(visit_date)', $today)
            ->where('vital_signs IS NOT NULL')
            ->where('vital_signs !=', '')
            ->countAllResults();

        // Count pending lab samples (tests that require specimen and are ready for collection)
        $pendingLabSamples = $labTestModel
            ->where('requires_specimen', 1)
            ->where('status', 'requested')
            ->countAllResults();

        // Today's appointments with patient details
        $appointments = $apptModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->where('DATE(appointments.appointment_date)', $today)
            ->orderBy('appointments.appointment_time', 'ASC')
            ->findAll(10);

        // Recent vital signs recorded
        $recentVitals = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->where('medical_records.vital_signs IS NOT NULL')
            ->where('medical_records.vital_signs !=', '')
            ->orderBy('medical_records.visit_date', 'DESC')
            ->findAll(5);

        // Pending lab samples to collect (show both approved and pending approval tests that require specimen)
        // Use raw query to ensure we get all necessary fields
        $db = \Config\Database::connect();
        $pendingSamples = $db->query("
            SELECT 
                lt.*,
                p.first_name,
                p.last_name,
                p.patient_id as patient_code
            FROM lab_tests lt
            INNER JOIN patients p ON p.id = lt.patient_id
            WHERE lt.requires_specimen = 1
            AND lt.status = 'requested'
            AND (lt.deleted_at IS NULL OR lt.deleted_at = '')
            ORDER BY 
                CASE WHEN lt.accountant_approved = 1 THEN 0 ELSE 1 END,
                lt.requested_date ASC
            LIMIT 10
        ")->getResultArray();

        // Ward patients (patients with recent medical records)
        $wardPatients = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->where('DATE(medical_records.visit_date) >=', date('Y-m-d', strtotime('-7 days')))
            ->orderBy('medical_records.visit_date', 'DESC')
            ->findAll(10);

        // Weekly schedule for this nurse
        $startOfWeek = date('Y-m-d', strtotime('sunday this week'));
        $endOfWeek = date('Y-m-d', strtotime('saturday this week'));

        log_message('error', 'Fetching schedule for nurse ID: ' . $nurseId . ' from ' . $startOfWeek . ' to ' . $endOfWeek);

        $schedule = $dailyScheduleModel
            ->where('user_id', $nurseId)
            ->where('schedule_date >=', $startOfWeek)
            ->where('schedule_date <=', $endOfWeek)
            ->orderBy('schedule_date', 'ASC')
            ->findAll();

        log_message('error', 'Schedules found: ' . count($schedule));

        // Pending admissions - patients marked for admission but not yet fully admitted
        // Get patients with admission_type='admission' and no admission_date set
        $db = \Config\Database::connect();
        $pendingAdmissionsQuery = $db->query("
            SELECT 
                p.*,
                COALESCE(
                    (SELECT u.username FROM medical_records mr
                     JOIN users u ON u.id = mr.doctor_id
                     WHERE mr.patient_id = p.id 
                     ORDER BY mr.visit_date DESC 
                     LIMIT 1),
                    'N/A'
                ) as doctor_name,
                (SELECT visit_date FROM medical_records 
                 WHERE patient_id = p.id 
                 ORDER BY visit_date DESC 
                 LIMIT 1) as consultation_date,
                r.room_number,
                r.room_type
            FROM patients p
            LEFT JOIN rooms r ON r.id = p.assigned_room_id
            WHERE p.is_active = 1
            AND p.admission_type = 'admission'
            AND (p.admission_date IS NULL OR p.admission_date = '')
            ORDER BY p.created_at DESC
            LIMIT 10
        ");
        $pendingAdmissions = $pendingAdmissionsQuery->getResultArray();

        return view('nurse/dashboard', [
            'activePatients' => $activePatients,
            'pendingTasks' => $pendingTasks,
            'vitalsRecorded' => $vitalsRecorded,
            'pendingLabSamples' => $pendingLabSamples,
            'appointments' => $appointments,
            'recentVitals' => $recentVitals,
            'pendingSamples' => $pendingSamples,
            'wardPatients' => $wardPatients,
            'schedule' => $schedule,
            'pendingAdmissions' => $pendingAdmissions,
        ]);
    }

    /**
     * Parse vitals input from various formats to a structured array
     * 
     * @param string $input The input string from the form
     * @return array Structured vitals data
     */
    protected function parseVitalsInput(string $input): array
    {
        // Try to decode as JSON first
        $decoded = json_decode($input, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        
        // If not valid JSON, try to parse as key:value pairs
        $result = [];
        $lines = preg_split('/\R+/', trim($input));
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Try to match key: value pattern
            if (preg_match('/^\s*([^:]+?)\s*[:=]\s*(.+?)\s*$/', $line, $matches)) {
                $key = trim($matches[1]);
                $value = trim($matches[2]);
                $result[$key] = is_numeric($value) ? (strpos($value, '.') !== false ? (float)$value : (int)$value) : $value;
            } else {
                // If no key:value format, add as a note
                if (!isset($result['notes'])) {
                    $result['notes'] = [];
                }
                $result['notes'][] = $line;
            }
        }
        
        // If we have notes as array, implode them
        if (isset($result['notes']) && is_array($result['notes'])) {
            $result['notes'] = implode("\n", $result['notes']);
        }
        
        // Set a timestamp
        $result['recorded_at'] = date('Y-m-d H:i:s');
        
        return $result;
    }
    
    /**
     * Normalize vitals input to JSON string
     * 
     * @param mixed $input Raw input from form
     * @return string JSON string
     */
    protected function normalizeVitalsToJson($input): string
    {
        // Handle empty input
        if (empty($input)) {
            return json_encode(['notes' => ''], JSON_UNESCAPED_UNICODE);
        }
        
        // If already an array, encode directly
        if (is_array($input)) {
            return json_encode($input, JSON_UNESCAPED_UNICODE);
        }
        
        // Convert to string and trim
        $input = trim((string)$input);
        
        // Try to decode as JSON first
        $decoded = json_decode($input, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }
        
        // Try to parse as key:value pairs
        $result = [];
        $lines = preg_split('/\R+/', $input);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Match key: value pattern
            if (preg_match('/^\s*([A-Za-z0-9 _-]+?)\s*[:=]\s*(.+?)\s*$/', $line, $matches)) {
                $key = trim($matches[1]);
                $value = trim($matches[2]);
                
                // Convert numeric values to proper types
                if (is_numeric($value)) {
                    $value = strpos($value, '.') !== false ? (float)$value : (int)$value;
                }
                
                $result[$key] = $value;
            } else {
                // If line doesn't match key:value, add to notes
                if (!isset($result['notes'])) {
                    $result['notes'] = [];
                }
                $result['notes'][] = $line;
            }
        }
        
        // If we collected notes as array, implode them
        if (isset($result['notes']) && is_array($result['notes'])) {
            $result['notes'] = implode("\n", $result['notes']);
        }
        
        // If no structured data, use the original input as notes
        if (empty($result)) {
            $result = ['notes' => $input];
        }
        
        // Add a timestamp
        $result['recorded_at'] = date('Y-m-d H:i:s');
        
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function newVitals()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');
        
        // Get active patients for dropdown
        $patients = $patientModel
            ->where('is_active', 1)
            ->orderBy('first_name', 'ASC')
            ->findAll();
        
        // Get doctors for dropdown
        $doctors = $userModel
            ->where('role', 'doctor')
            ->where('is_active', 1)
            ->orderBy('first_name', 'ASC')
            ->findAll();
        
        // Get patient_id from query string if coming from patient monitoring
        $preselectedPatientId = $this->request->getGet('patient_id');
        
        return view('nurse/vitals_new', [
            'patients' => $patients,
            'doctors' => $doctors,
            'preselectedPatientId' => $preselectedPatientId
        ]);
    }

    public function storeVitals()
    {
        helper(['url', 'form']);
        
        // Get and validate required fields
        $patientId = (int) ($this->request->getPost('patient_id') ?? 0);
        
        if (!$patientId) {
            return redirect()->back()->with('error', 'Patient is required.')->withInput();
        }
        
        // Get raw input
        $vitalsInput = $this->request->getPost('vital_signs') ?: $this->request->getPost('vitals');
        
        // Normalize to JSON string
        $vitalsJson = $this->normalizeVitalsToJson($vitalsInput);
        
        // Prepare data with proper type handling
        $data = [
            'patient_id'     => $patientId,
            'appointment_id' => (($v = trim((string)$this->request->getPost('appointment_id'))) === '') ? null : (int)$v,
            'doctor_id'      => (($v = trim((string)$this->request->getPost('doctor_id'))) === '') ? null : (int)$v,
            'vital_signs'    => $vitalsJson,
            'record_number'  => 'NV-' . date('YmdHis'),
            'visit_date'     => date('Y-m-d H:i:s'),
            'branch_id'      => 1, // Default branch
        ];
        
        // Save through model with validation
        $model = new \App\Models\MedicalRecordModel();
        
        try {
            if ($model->save($data) === false) {
                return redirect()->back()
                    ->with('errors', $model->errors())
                    ->withInput();
            }
            
            return redirect()->to(site_url('dashboard/nurse'))
                ->with('message', 'Vitals recorded successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error saving vitals: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function newNote()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $appointmentModel = model('App\\Models\\AppointmentModel');
        $userModel = model('App\\Models\\UserModel');
        
        // Get patient_id from query string if provided
        $patientIdFromQuery = (int) ($this->request->getGet('patient_id') ?? 0);
        
        // Get all active patients
        $patients = $patientModel
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->findAll(200);
        
        // Get appointments (for the selected patient if provided)
        $appointments = [];
        if ($patientIdFromQuery > 0) {
            $appointments = $appointmentModel
                ->select('appointments.*, patients.first_name, patients.last_name, users.first_name as doctor_first_name, users.last_name as doctor_last_name')
                ->join('patients', 'patients.id = appointments.patient_id')
                ->join('users', 'users.id = appointments.doctor_id', 'left')
                ->where('appointments.patient_id', $patientIdFromQuery)
                ->orderBy('appointments.appointment_date', 'DESC')
                ->orderBy('appointments.appointment_time', 'DESC')
                ->findAll(50);
        }
        
        // Get all active doctors
        $doctors = $userModel
            ->where('role', 'doctor')
            ->where('is_active', 1)
            ->orderBy('first_name', 'ASC')
            ->orderBy('last_name', 'ASC')
            ->findAll(100);
        
        return view('nurse/note_new', [
            'patients' => $patients,
            'appointments' => $appointments,
            'doctors' => $doctors,
            'patient_id' => $patientIdFromQuery
        ]);
    }

    public function storeNote()
    {
        helper(['url','form']);
        $patientId = (int) $this->request->getPost('patient_id');
        $note      = trim((string) $this->request->getPost('note'));
        if (!$patientId || $note === '') {
            return redirect()->back()->with('error', 'Patient and note are required.')->withInput();
        }
        $appointmentId = $this->request->getPost('appointment_id');
        $doctorId = $this->request->getPost('doctor_id');
        
        $data = [
            'record_number' => 'NN-' . date('YmdHis'),
            'patient_id' => $patientId,
            'appointment_id' => (!empty($appointmentId) && $appointmentId !== '') ? (int) $appointmentId : null,
            'doctor_id' => (!empty($doctorId) && $doctorId !== '') ? (int) $doctorId : null,
            'visit_date' => date('Y-m-d H:i:s'),
            'treatment_plan' => $note,
            'branch_id' => 1,
        ];
        $model = new \App\Models\MedicalRecordModel();
        $model->insert($data);
        return redirect()->to(site_url('dashboard/nurse'))->with('success', 'Note saved.');
    }

    public function getAppointmentsForNote()
    {
        helper('url');
        $appointmentModel = model('App\\Models\\AppointmentModel');
        $patientId = (int) ($this->request->getGet('patient_id') ?? 0);
        
        if (!$patientId) {
            return $this->response->setJSON(['appointments' => []]);
        }
        
        $appointments = $appointmentModel
            ->select('appointments.id, appointments.appointment_date, appointments.appointment_time, users.first_name as doctor_first_name, users.last_name as doctor_last_name')
            ->join('users', 'users.id = appointments.doctor_id', 'left')
            ->where('appointments.patient_id', $patientId)
            ->where('appointments.status !=', 'cancelled')
            ->orderBy('appointments.appointment_date', 'DESC')
            ->orderBy('appointments.appointment_time', 'DESC')
            ->findAll(50);
        
        $formattedAppointments = [];
        foreach ($appointments as $apt) {
            $formattedAppointments[] = [
                'id' => $apt['id'],
                'date' => !empty($apt['appointment_date']) ? date('M j, Y', strtotime($apt['appointment_date'])) : '',
                'time' => !empty($apt['appointment_time']) ? date('g:i A', strtotime($apt['appointment_time'])) : '',
                'doctor' => trim(($apt['doctor_first_name'] ?? '') . ' ' . ($apt['doctor_last_name'] ?? ''))
            ];
        }
        
        return $this->response->setJSON(['appointments' => $formattedAppointments]);
    }

    public function wardPatients()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        
        // Get all active patients with their most recent visit (if any)
        $patients = $patientModel
            ->select('patients.id, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender, patients.phone, MAX(medical_records.visit_date) as visit_date')
            ->join('medical_records', 'medical_records.patient_id = patients.id', 'left')
            ->where('patients.is_active', 1)
            ->groupBy('patients.id, patients.first_name, patients.last_name, patients.patient_id, patients.date_of_birth, patients.gender, patients.phone')
            ->orderBy('visit_date', 'DESC')
            ->orderBy('patients.created_at', 'DESC')
            ->findAll(50);

        return view('nurse/ward_patients', ['patients' => $patients]);
    }

    public function viewPatient($id)
    {
        helper(['url']);
        $id = (int) $id;
        $patient = model('App\Models\PatientModel')->find($id);
        if (!$patient) {
            return redirect()->to(site_url('nurse/ward-patients'))->with('error', 'Patient not found.');
        }
        return view('admin/patient_view', ['patient' => $patient]);
    }

    public function patientMonitoring($patientId)
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('nurse/ward-patients'))->with('error', 'Patient not found.');
        }

        $vitals = $medicalRecordModel
            ->where('patient_id', $patientId)
            ->where('vital_signs IS NOT NULL')
            ->where('vital_signs !=', '')
            ->orderBy('visit_date', 'DESC')
            ->findAll(10);

        $treatmentNotes = $medicalRecordModel
            ->where('patient_id', $patientId)
            ->where('treatment_plan IS NOT NULL')
            ->where('treatment_plan !=', '')
            ->orderBy('visit_date', 'DESC')
            ->findAll(10);

        return view('nurse/patient_monitoring', [
            'patient' => $patient,
            'vitals' => $vitals,
            'treatmentNotes' => $treatmentNotes,
        ]);
    }

    public function labSamples()
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        
        // Show approved tests that require specimen and are either 'requested' (need collection) or 'sample_collected' (already collected)
        // Use raw query to handle NULL values properly
        $db = \Config\Database::connect();
        
        $samples = $db->query("
            SELECT 
                lt.*,
                p.first_name,
                p.last_name,
                p.patient_id as patient_code
            FROM lab_tests lt
            INNER JOIN patients p ON p.id = lt.patient_id
            WHERE lt.requires_specimen = 1
            AND lt.status IN ('requested', 'sample_collected')
            AND (lt.deleted_at IS NULL OR lt.deleted_at = '')
            ORDER BY 
                CASE WHEN lt.status = 'requested' THEN 0 ELSE 1 END,
                CASE WHEN lt.accountant_approved = 1 THEN 0 ELSE 1 END,
                lt.requested_date ASC
            LIMIT 50
        ")->getResultArray();

        // Debug: Log if no samples found
        if (empty($samples)) {
            log_message('info', 'Nurse labSamples: No samples found. Query requires: accountant_approved=1, requires_specimen=1, status IN (requested, sample_collected)');
            
            // Also log what tests exist for debugging
            $allTests = $db->query("
                SELECT id, test_name, requires_specimen, accountant_approved, status 
                FROM lab_tests 
                WHERE requires_specimen = 1 
                LIMIT 10
            ")->getResultArray();
            log_message('info', 'Nurse labSamples: Sample tests found: ' . json_encode($allTests));
        }

        return view('nurse/lab_samples', ['samples' => $samples]);
    }

    public function newLabRequest()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $billingModel = model('App\\Models\\BillingModel');
        $db = \Config\Database::connect();
        
        // Get all patients
        $patients = $patientModel
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->findAll();
        
        // Get all active lab tests grouped by category (same as group6)
        $labTests = [];
        try {
            // First try to use lab_test_catalog table (catalog of available tests)
            if ($db->tableExists('lab_test_catalog')) {
                $labTestCatalogModel = model('App\\Models\\LabTestCatalogModel');
                $labTests = $labTestCatalogModel->getActiveTestsGroupedByCategory();
            } 
            // Fallback: use lab_tests table to get distinct test names (if catalog doesn't exist)
            // But only if the test_name field contains single tests (not concatenated)
            elseif ($db->tableExists('lab_tests')) {
                $builder = $db->table('lab_tests');
                
                // Check if requires_specimen column exists
                $columns = $db->getFieldNames('lab_tests');
                $hasRequiresSpecimen = in_array('requires_specimen', $columns);
                
                if ($hasRequiresSpecimen) {
                    $distinctTests = $builder
                        ->select('test_name, test_type, test_category, cost as price, requires_specimen')
                        ->groupBy('test_name')
                        ->having('LENGTH(test_name) < 100') // Filter out concatenated test names (usually longer)
                        ->orderBy('test_name', 'ASC')
                        ->get()
                        ->getResultArray();
                } else {
                    $distinctTests = $builder
                        ->select('test_name, test_type, test_category, cost as price')
                        ->groupBy('test_name')
                        ->having('LENGTH(test_name) < 100') // Filter out concatenated test names
                        ->orderBy('test_name', 'ASC')
                        ->get()
                        ->getResultArray();
                }
                
                // Group by specimen category
                foreach ($distinctTests as $test) {
                    // Determine category from requires_specimen field if available
                    if ($hasRequiresSpecimen && isset($test['requires_specimen'])) {
                        $category = ((int)$test['requires_specimen'] === 1) ? 'with_specimen' : 'without_specimen';
                    } else {
                        // Fallback: determine from test_category
                        $category = 'with_specimen'; // Default
                        $testCategory = strtolower($test['test_category'] ?? '');
                        if (in_array($testCategory, ['imaging', 'radiology'])) {
                            $category = 'without_specimen';
                        }
                    }
                    
                    // Use test_type as the category for grouping (Chemistry, Hematology, Radiology, etc.)
                    $testType = $test['test_type'] ?? 'Other';
                    
                    // Skip if test_name looks like concatenated (contains comma or "ordered")
                    if (stripos($test['test_name'], ',') !== false || stripos($test['test_name'], 'ordered') !== false) {
                        continue;
                    }
                    
                    if (!isset($labTests[$category])) {
                        $labTests[$category] = [];
                    }
                    if (!isset($labTests[$category][$testType])) {
                        $labTests[$category][$testType] = [];
                    }
                    
                    $labTests[$category][$testType][] = [
                        'test_name' => $test['test_name'],
                        'test_type' => $testType,
                        'test_category' => $test['test_category'] ?? 'other',
                        'specimen_category' => $category,
                        'description' => '',
                        'normal_range' => '',
                        'price' => (float)($test['price'] ?? 0.00),
                    ];
                }
                
                // Sort each category's tests alphabetically
                foreach ($labTests as $category => $types) {
                    ksort($labTests[$category]);
                    foreach ($labTests[$category] as $type => $tests) {
                        usort($labTests[$category][$type], function($a, $b) {
                            return strcmp($a['test_name'], $b['test_name']);
                        });
                    }
                }
            }
        } catch (\Exception $e) {
            // Table might not exist or error
            $labTests = [];
        }
        
        // Get payment status for each patient
        $patientPaymentStatus = [];
        foreach ($patients as $patient) {
            $patientId = $patient['id'];
            $billings = $billingModel
                ->where('patient_id', $patientId)
                ->where('payment_status !=', 'cancelled')
                ->orderBy('created_at', 'DESC')
                ->findAll();
            
            $hasPaid = false;
            $unpaidAmount = 0.00;
            $hasPendingBilling = false;
            
            foreach ($billings as $billing) {
                if ($billing['payment_status'] === 'paid') {
                    $hasPaid = true;
                } elseif ($billing['payment_status'] === 'pending' || $billing['payment_status'] === 'partial') {
                    $hasPendingBilling = true;
                    $unpaidAmount += (float) $billing['balance'];
                }
            }
            
            // Can request lab if:
            // 1. Patient has at least one paid billing, OR
            // 2. Patient has no unpaid/pending billings (unpaid amount is 0 and no pending bills)
            $canRequest = $hasPaid || (!$hasPendingBilling && $unpaidAmount == 0);
            
            $patientPaymentStatus[$patientId] = [
                'has_paid' => $hasPaid,
                'unpaid_amount' => $unpaidAmount,
                'can_request_lab' => $canRequest,
            ];
        }
        
        return view('nurse/lab_request_new', [
            'patients' => $patients,
            'labTests' => $labTests,
            'payment_status' => $patientPaymentStatus,
        ]);
    }

    public function storeLabRequest()
    {
        helper(['url', 'form']);
        $req = $this->request;
        $labTestModel = model('App\\Models\\LabTestModel');
        $billingModel = model('App\\Models\\BillingModel');
        $labTestCatalogModel = model('App\\Models\\LabTestCatalogModel');
        
        $patientId = (int) $req->getPost('patient_id');
        $testName = trim((string) $req->getPost('test_name'));
        $testType = trim((string) $req->getPost('test_type'));
        $priority = $req->getPost('priority') ?: 'routine';
        $instructions = $req->getPost('instructions') ?: null;
        $requestDate = $req->getPost('requested_date');
        
        if (!$patientId || !$testName) {
            return redirect()->back()->with('error', 'Patient and test name are required.')->withInput();
        }
        
        // Determine test details from existing lab_tests or test type
        $requiresSpecimen = 1; // Default
        $category = 'other';
        $cost = 0.00;
        
        // Try to get from existing lab_tests table
        $existingTest = $labTestModel
            ->where('test_name', $testName)
            ->orderBy('created_at', 'DESC')
            ->first();
        
        if ($existingTest) {
            $category = $existingTest['test_category'] ?? 'other';
            $cost = (float)($existingTest['cost'] ?? 0.00);
            $requiresSpecimen = (int)($existingTest['requires_specimen'] ?? 1);
        } else {
            // Try to determine from test type
            if (stripos($testType, 'radiology') !== false || stripos($testType, 'imaging') !== false) {
                $requiresSpecimen = 0;
                $category = 'imaging';
            }
        }
        
        // Check if patient has paid registration fee
        $patientBillings = $billingModel
            ->where('patient_id', $patientId)
            ->where('payment_status !=', 'cancelled')
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        $hasPaidBilling = false;
        $unpaidAmount = 0.00;
        $hasPendingBilling = false;
        
        foreach ($patientBillings as $billing) {
            if ($billing['payment_status'] === 'paid') {
                $hasPaidBilling = true;
            } elseif ($billing['payment_status'] === 'pending' || $billing['payment_status'] === 'partial') {
                $hasPendingBilling = true;
                $unpaidAmount += (float) $billing['balance'];
            }
        }

        // Block only if patient has unpaid/pending billings with amount > 0
        // Allow if patient has paid OR has no unpaid billings (unpaid amount is 0)
        if ($hasPendingBilling && $unpaidAmount > 0) {
            return redirect()->back()->with('error', 'Patient has an unpaid registration fee or billing. Lab test cannot be requested. Unpaid amount: ₱' . number_format($unpaidAmount, 2))->withInput();
        }
        
        // Get patient's doctor from latest medical record (if any) or use default
        $patientModel = model('App\\Models\\PatientModel');
        $patient = $patientModel->find($patientId);
        
        // Try to get doctor from latest medical record
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $latestRecord = $medicalRecordModel
            ->where('patient_id', $patientId)
            ->orderBy('visit_date', 'DESC')
            ->first();
        
        $doctorId = $latestRecord['doctor_id'] ?? session('user_id') ?? 1; // Use nurse as fallback
        
        $requestedDateTime = $requestDate
            ? ($requestDate . ' ' . date('H:i:s'))
            : date('Y-m-d H:i:s');
        
        $data = [
            'test_number' => 'LT-' . date('YmdHis'),
            'patient_id' => $patientId,
            'doctor_id' => (int) $doctorId,
            'test_type' => $testType ?: 'ordered',
            'test_name' => $testName,
            'test_category' => $category,
            'requires_specimen' => $requiresSpecimen,
            'accountant_approved' => 0, // Pending accountant approval
            'requested_date' => $requestedDateTime,
            'status' => 'requested',
            'branch_id' => 1,
            'priority' => $priority,
            'notes' => $instructions,
            'cost' => $cost,
        ];
        
        $labTestModel->insert($data);
        return redirect()->to(site_url('nurse/lab-request'))->with('success', 'Lab test requested successfully. Waiting for accountant approval.');
    }

    /**
     * Mark lab test specimen as collected
     * 
     * Route: POST /nurse/lab-samples/collect/{testId}
     * 
     * This method allows nurses to mark specimens as collected even if the doctor_id
     * is invalid/defective. The validation only checks:
     * - Test exists
     * - Status is 'requested' (ready for collection)
     * - requires_specimen = 1 (test needs specimen)
     * - accountant_approved = 1 (approved by accountant)
     * 
     * Note: doctor_id validity is NOT checked - the test itself is valid regardless
     * of whether the doctor who created it is still valid in the system.
     */
    public function collectSample($testId)
    {
        helper(['url', 'form']);
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $test = $labTestModel->find($testId);
        if (!$test) {
            return redirect()->to(site_url('nurse/lab-samples'))->with('error', 'Test not found.');
        }

        // Validate that test can be collected (doesn't depend on doctor_id being valid)
        $currentStatus = trim($test['status'] ?? '');
        $requiresSpecimen = (int)($test['requires_specimen'] ?? 0);
        $accountantApproved = (int)($test['accountant_approved'] ?? 0);
        
        // Only allow collection if: status is 'requested', requires specimen, and accountant approved
        // Note: We don't check doctor_id validity - the test itself is valid regardless of doctor
        if ($currentStatus !== 'requested') {
            return redirect()->to(site_url('nurse/lab-samples'))->with('error', 'This test is not in a state that allows collection. Current status: ' . esc($currentStatus));
        }
        
        if ($requiresSpecimen != 1) {
            return redirect()->to(site_url('nurse/lab-samples'))->with('error', 'This test does not require specimen collection.');
        }
        
        if ($accountantApproved != 1) {
            return redirect()->to(site_url('nurse/lab-samples'))->with('error', 'This test has not been approved by the accountant yet.');
        }

        $labTestModel->update($testId, [
            'status' => 'sample_collected',
            'sample_collected_date' => date('Y-m-d H:i:s'),
            'lab_technician_id' => session('user_id') ?: 0,
        ]);

        // After marking as collected, the test will now appear in Lab Staff's test requests
        // Lab staff will process the test and save results
        return redirect()->to(site_url('nurse/lab-samples'))->with('success', 'Sample collected successfully. The test has been sent to the laboratory for processing.');
    }

    public function treatmentUpdates()
    {
        helper('url');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');
        
        // Get recent treatment updates
        $updates = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.username as doctor_name')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->join('users', 'users.id = medical_records.doctor_id', 'left')
            ->where('medical_records.treatment_plan IS NOT NULL')
            ->where('medical_records.treatment_plan !=', '')
            ->orderBy('medical_records.visit_date', 'DESC')
            ->findAll(50);
        
        // Add doctor_name field for display
        foreach ($updates as &$update) {
            $update['doctor_name'] = isset($update['doctor_first_name']) 
                ? $update['doctor_first_name'] . ' ' . $update['doctor_last_name']
                : 'N/A';
        }
        
        // Get active patients for dropdown
        $patients = $patientModel
            ->where('is_active', 1)
            ->orderBy('first_name', 'ASC')
            ->findAll();
        
        // Get doctors for dropdown
        $doctors = $userModel
            ->where('role', 'doctor')
            ->where('is_active', 1)
            ->orderBy('first_name', 'ASC')
            ->findAll();

        return view('nurse/treatment_updates', [
            'updates' => $updates,
            'patients' => $patients,
            'doctors' => $doctors
        ]);
    }

    public function updateTreatment()
    {
        helper(['url', 'form']);
        $patientId = (int) $this->request->getPost('patient_id');
        $treatment = trim((string) $this->request->getPost('treatment_update'));
        
        if (!$patientId || $treatment === '') {
            return redirect()->back()->with('error', 'Patient and treatment update are required.')->withInput();
        }

        $data = [
            'record_number' => 'TU-' . date('YmdHis'),
            'patient_id' => $patientId,
            'doctor_id' => (int) ($this->request->getPost('doctor_id') ?: 0),
            'visit_date' => date('Y-m-d H:i:s'),
            'treatment_plan' => $treatment,
            'branch_id' => 1,
        ];

        $model = new \App\Models\MedicalRecordModel();
        $model->insert($data);
        return redirect()->to(site_url('nurse/treatment-updates'))->with('success', 'Treatment update recorded.');
    }

    public function pendingAdmissions()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $branchModel = model('App\\Models\\BranchModel');
        
        // Get the first available branch
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
        
        // Get patients with admission_type='admission' that may need review
        $db = \Config\Database::connect();
        $pendingAdmissionsQuery = $db->query("
            SELECT 
                p.*,
                COALESCE(
                    (SELECT u.username FROM medical_records mr
                     JOIN users u ON u.id = mr.doctor_id
                     WHERE mr.patient_id = p.id 
                     ORDER BY mr.visit_date DESC 
                     LIMIT 1),
                    'N/A'
                ) as doctor_name,
                (SELECT visit_date FROM medical_records 
                 WHERE patient_id = p.id 
                 ORDER BY visit_date DESC 
                 LIMIT 1) as consultation_date,
                r.room_number,
                r.room_type
            FROM patients p
            LEFT JOIN rooms r ON r.id = p.assigned_room_id
            WHERE p.is_active = 1
            AND p.admission_type = 'admission'
            AND (p.admission_date IS NULL OR p.admission_date = '')
            ORDER BY p.created_at DESC
        ");
        $pendingAdmissions = $pendingAdmissionsQuery->getResultArray();
        
        // Format the data for the view
        $formattedAdmissions = [];
        foreach ($pendingAdmissions as $patient) {
            $formattedAdmissions[] = [
                'id' => $patient['id'],
                'patient_id' => $patient['patient_id'] ?? 'N/A',
                'first_name' => $patient['first_name'] ?? '',
                'last_name' => $patient['last_name'] ?? '',
                'phone' => $patient['phone'] ?? 'N/A',
                'doctor_name' => $patient['doctor_name'] ?? 'N/A',
                'consultation_date' => $patient['consultation_date'] ?? $patient['created_at'] ?? date('Y-m-d H:i:s'),
                'room_number' => $patient['room_number'] ?? 'Not assigned',
                'room_type' => $patient['room_type'] ?? null,
                'created_at' => $patient['created_at'] ?? date('Y-m-d H:i:s'),
            ];
        }
        
        // Get available rooms for selection
        $roomModel = model('App\\Models\\RoomModel');
        $branchModel = model('App\\Models\\BranchModel');
        
        $existingBranch = $branchModel->first();
        $branchId = $existingBranch ? $existingBranch['id'] : 1;
        
        $availableRooms = $roomModel->getAvailableRooms(null, $branchId);
        
        return view('nurse/pending_admissions', [
            'pendingAdmissions' => $formattedAdmissions,
            'availableRooms' => $availableRooms,
        ]);
    }

    public function admitPatient($patientId)
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $branchModel = model('App\\Models\\BranchModel');
        $userModel = model('App\\Models\\UserModel');
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('nurse/pending-admissions'))->with('error', 'Patient not found.');
        }
        
        // Get available rooms
        $existingBranch = $branchModel->first();
        $branchId = $existingBranch ? $existingBranch['id'] : 1;
        
        $availableRooms = $roomModel->getAvailableRooms(null, $branchId);
        
        // Get doctors for attending physician selection
        $doctors = $userModel->where('role', 'doctor')
                            ->where('is_active', 1)
                            ->orderBy('first_name', 'ASC')
                            ->findAll();
        
        return view('nurse/admit_patient', [
            'patient' => $patient,
            'availableRooms' => $availableRooms,
            'doctors' => $doctors,
        ]);
    }

    public function getBedsByRoom($roomId)
    {
        $bedModel = model('App\\Models\\BedModel');
        $beds = $bedModel->getAvailableBeds($roomId);
        
        return $this->response->setJSON([
            'beds' => $beds
        ]);
    }

    public function processAdmission($patientId)
    {
        helper(['url', 'form']);
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $bedModel = model('App\\Models\\BedModel');
        $branchModel = model('App\\Models\\BranchModel');
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('nurse/pending-admissions'))->with('error', 'Patient not found.');
        }
        
        $roomId = (int) $this->request->getPost('room_id');
        $bedId = (int) $this->request->getPost('bed_id') ?: null;
        $admissionDate = $this->request->getPost('admission_date') ?: date('Y-m-d');
        $admissionReason = $this->request->getPost('admission_reason');
        $attendingPhysicianId = (int) $this->request->getPost('attending_physician_id');
        $admissionNotes = $this->request->getPost('admission_notes');
        
        if (!$roomId) {
            return redirect()->back()->with('error', 'Please select a room for admission.')->withInput();
        }
        
        if (!$admissionReason) {
            return redirect()->back()->with('error', 'Admission reason is required.')->withInput();
        }
        
        if (!$attendingPhysicianId) {
            return redirect()->back()->with('error', 'Attending physician is required.')->withInput();
        }
        
        // Get branch
        $existingBranch = $branchModel->first();
        $branchId = $existingBranch ? $existingBranch['id'] : 1;
        
        // Validate room exists and is available
        $room = $roomModel->find($roomId);
        if (!$room) {
            return redirect()->back()->with('error', 'Room not found.')->withInput();
        }
        
        if ($room['status'] !== 'available' || $room['current_occupancy'] >= $room['capacity']) {
            return redirect()->back()->with('error', 'Room is not available. Please select another room.')->withInput();
        }
        
        // Validate bed if provided
        if ($bedId) {
            $bed = $bedModel->find($bedId);
            if (!$bed || $bed['room_id'] != $roomId) {
                return redirect()->back()->with('error', 'Selected bed is not valid for this room.')->withInput();
            }
            if ($bed['status'] !== 'available') {
                return redirect()->back()->with('error', 'Selected bed is not available.')->withInput();
            }
            
            // Update bed status
            $bedModel->update($bedId, [
                'status' => 'occupied'
            ]);
        }
        
        // Update room occupancy
        $newOccupancy = $room['current_occupancy'] + 1;
        $roomModel->update($roomId, [
            'current_occupancy' => $newOccupancy,
            'status' => $newOccupancy >= $room['capacity'] ? 'occupied' : 'available'
        ]);
        
        // Update patient with room assignment and admission details
        $updateData = [
            'assigned_room_id' => $roomId,
            'assigned_bed_id' => $bedId,
            'admission_date' => $admissionDate,
            'admission_type' => 'admission',
            'admission_reason' => $admissionReason,
            'attending_physician_id' => $attendingPhysicianId,
            'admission_notes' => $admissionNotes,
            'branch_id' => $branchId,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $patientModel->update($patientId, $updateData);
        
        $successMsg = 'Patient admitted to room ' . $room['room_number'];
        if ($bedId && $bed) {
            $successMsg .= ', bed ' . $bed['bed_number'];
        }
        $successMsg .= ' successfully.';
        
        return redirect()->to(site_url('nurse/pending-admissions'))->with('success', $successMsg);
    }

    public function viewPatientConsultation($patientId)
    {
        helper('url');
        // Redirect to patient view page for nurses
        return redirect()->to(site_url('nurse/patients/view/' . $patientId));
    }
}
