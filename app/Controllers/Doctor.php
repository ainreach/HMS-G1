<?php
namespace App\Controllers;

class Doctor extends BaseController
{
    public function dashboard()
    {
        $patientModel = new \App\Models\PatientModel();
        $appointmentModel = new \App\Models\AppointmentModel();
        $prescriptionModel = new \App\Models\PrescriptionModel();

        $patients = $patientModel->findAll();
        $appointments = $appointmentModel->where('doctor_id', session('user_id'))->findAll();
        $prescriptions = $prescriptionModel->where('doctor_id', session('user_id'))->findAll();

        $data = [
            'patients' => $patients,
            'appointments' => $appointments,
            'prescriptions' => $prescriptions,
        ];

        helper('url');
        $appointmentModel = model('App\\Models\\AppointmentModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $labTestModel = model('App\\Models\\LabTestModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');
        $scheduleModel = model('App\\Models\\StaffScheduleModel');

        $today = date('Y-m-d');
        $doctorId = session('user_id') ?: 1; // Fallback for testing

        // KPIs
        $todayAppointments = $appointmentModel
            ->where('DATE(appointment_date)', $today)
            ->where('doctor_id', $doctorId)
            ->countAllResults();

        $pendingLabResults = $labTestModel
            ->where('doctor_id', $doctorId)
            ->where('status !=', 'completed')
            ->countAllResults();

        // Today's appointments with patient names
        $appointments = $appointmentModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->where('DATE(appointments.appointment_date)', $today)
            ->where('appointments.doctor_id', $doctorId)
            ->orderBy('appointments.appointment_time', 'ASC')
            ->findAll(10);

        // Recent medical records
        $recentRecords = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->where('medical_records.doctor_id', $doctorId)
            ->orderBy('medical_records.visit_date', 'DESC')
            ->findAll(5);

        // Pending lab tests
        $pendingTests = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->where('lab_tests.doctor_id', $doctorId)
            ->where('lab_tests.status !=', 'completed')
            ->orderBy('lab_tests.requested_date', 'DESC')
            ->findAll(5);

        // Weekly schedule for this doctor
        $schedule = $scheduleModel
            ->where('user_id', $doctorId)
            ->orderBy('day_of_week', 'ASC')
            ->orderBy('start_time', 'ASC')
            ->findAll(20);

        return view('doctor/dashboard', [
            'todayAppointments' => $todayAppointments,
            'pendingLabResults' => $pendingLabResults,
            'appointments' => $appointments,
            'recentRecords' => $recentRecords,
            'pendingTests' => $pendingTests,
            'schedule' => $schedule,
        ]);
    }

    public function newRecord()
    {
        helper('url');
        $patientId = $this->request->getGet('patient_id');
        return view('doctor/record_new', ['patient_id' => $patientId]);
    }

    public function storeRecord()
    {
        helper(['url','form']);
        $patientId = (int) $this->request->getPost('patient_id');
        $doctorId  = session('user_id') ?: (int) $this->request->getPost('doctor_id');
        $visitDate = $this->request->getPost('visit_date') ?: date('Y-m-d H:i:s');
        if (!$patientId) {
            return redirect()->back()->with('error', 'Patient is required.')->withInput();
        }
        $data = [
            'record_number' => 'MR-' . date('YmdHis'),
            'patient_id' => $patientId,
            'appointment_id' => $this->request->getPost('appointment_id') ?: null,
            'doctor_id' => (int) $doctorId,
            'visit_date' => $visitDate,
            'chief_complaint' => $this->request->getPost('chief_complaint') ?: null,
            'history_present_illness' => $this->request->getPost('history_present_illness') ?: null,
            'physical_examination' => $this->request->getPost('physical_examination') ?: null,
            'vital_signs' => $this->request->getPost('vital_signs') ?: null,
            'diagnosis' => $this->request->getPost('diagnosis') ?: null,
            'treatment_plan' => $this->request->getPost('treatment_plan') ?: null,
            'medications_prescribed' => $this->request->getPost('medications_prescribed') ?: null,
            'follow_up_instructions' => $this->request->getPost('follow_up_instructions') ?: null,
            'next_visit_date' => $this->request->getPost('next_visit_date') ?: null,
            'branch_id' => 1,
        ];
        $model = new \App\Models\MedicalRecordModel();
        $model->insert($data);
        return redirect()->to(site_url('dashboard/doctor'))->with('success', 'Medical record saved.');
    }

    public function newLabRequest()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $billingModel = model('App\\Models\\BillingModel');
        
        $patients = $patientModel
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->findAll(100);

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
            
            foreach ($billings as $billing) {
                if ($billing['payment_status'] === 'paid') {
                    $hasPaid = true;
                } elseif ($billing['payment_status'] === 'pending' || $billing['payment_status'] === 'partial') {
                    $unpaidAmount += (float) $billing['balance'];
                }
            }
            
            // Patient can request lab test if they have at least one paid billing (registration fee)
            $patientPaymentStatus[$patientId] = [
                'has_paid' => $hasPaid,
                'unpaid_amount' => $unpaidAmount,
                'can_request_lab' => $hasPaid, // Only allow if registration fee is paid
            ];
        }

        $userModel = model('App\\Models\\UserModel');
        $doctors = $userModel
            ->where('role', 'doctor')
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->findAll(100);

        return view('doctor/lab_request_new', [
            'patients' => $patients,
            'doctors'  => $doctors,
            'payment_status' => $patientPaymentStatus,
        ]);
    }

    public function storeLabRequest()
    {
        helper(['url','form']);
        $req = $this->request;

        $patientId = (int) $req->getPost('patient_id');
        $formDoctorId = (int) $req->getPost('doctor_id');
        $doctorId  = $formDoctorId ?: (int) (session('user_id') ?: 0);

        $selectedTests = (array) $req->getPost('tests');
        $testNameInput = trim((string) $req->getPost('test_name'));
        $testName = $testNameInput;
        if (!empty($selectedTests)) {
            $testName = implode(', ', $selectedTests);
        }

        $category = (string) $req->getPost('test_category');
        if ($category === '') {
            $category = !empty($selectedTests) ? 'panel' : 'general';
        }

        $requestDate = $req->getPost('request_date');
        $requestedDateTime = $requestDate
            ? ($requestDate . ' ' . date('H:i:s'))
            : date('Y-m-d H:i:s');

        if (!$patientId || $testName === '') {
            return redirect()->back()->with('error', 'Patient and at least one test type are required.')->withInput();
        }

        // Check if patient has paid registration fee before allowing lab test
        $billingModel = new \App\Models\BillingModel();
        $patientBillings = $billingModel
            ->where('patient_id', $patientId)
            ->where('payment_status !=', 'cancelled')
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        // Check if there's at least one paid billing (registration fee should be paid)
        $hasPaidBilling = false;
        $unpaidAmount = 0.00;
        
        foreach ($patientBillings as $billing) {
            if ($billing['payment_status'] === 'paid') {
                $hasPaidBilling = true;
                break;
            } elseif ($billing['payment_status'] === 'pending' || $billing['payment_status'] === 'partial') {
                $unpaidAmount += (float) $billing['balance'];
            }
        }
        
        // If no billing exists or registration fee is not paid, prevent lab test
        if (empty($patientBillings) || !$hasPaidBilling) {
            $errorMessage = 'Cannot create lab test. Patient must pay registration fee first.';
            if ($unpaidAmount > 0) {
                $errorMessage .= ' Unpaid amount: ₱' . number_format($unpaidAmount, 2);
            }
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }

        // Determine if test requires specimen (default: yes for blood/urine, no for imaging)
        $requiresSpecimen = 1; // Default: requires specimen
        if ($req->getPost('requires_specimen') !== null) {
            $requiresSpecimen = (int) $req->getPost('requires_specimen');
        } elseif (in_array(strtolower($category), ['imaging', 'pathology'])) {
            $requiresSpecimen = 0; // Imaging and pathology usually don't need specimen collection
        }
        
        $data = [
            'test_number' => 'LT-' . date('YmdHis'),
            'patient_id' => $patientId,
            'doctor_id' => (int) $doctorId,
            'test_type' => $req->getPost('test_type') ?: 'ordered',
            'test_name' => $testName,
            'test_category' => $category,
            'requires_specimen' => $requiresSpecimen,
            'accountant_approved' => 0, // Pending accountant approval
            'requested_date' => $requestedDateTime,
            'status' => 'requested',
            'branch_id' => 1,
            'priority' => $req->getPost('priority') ?: 'routine',
            'notes' => $req->getPost('notes') ?: null,
        ];
        $model = new \App\Models\LabTestModel();
        $model->insert($data);
        return redirect()->to(site_url('dashboard/doctor'))->with('success', 'Lab test requested.');
    }

    // Allow doctors to register new patients directly
    public function newPatient()
    {
        helper('url');
        return view('doctor/patient_new');
    }

    public function storePatient()
    {
        helper(['url','form']);
        $req = $this->request;
        // Validate gender first - it's required and must be one of the allowed values
        $gender = trim((string) $req->getPost('gender'));
        if (empty($gender) || !in_array($gender, ['male', 'female', 'other'])) {
            return redirect()->back()->with('error', 'Gender is required and must be selected.')->withInput();
        }
        
        $data = [
            'patient_id' => 'P-' . date('YmdHis'),
            'first_name' => trim((string) $req->getPost('first_name')),
            'last_name'  => trim((string) $req->getPost('last_name')),
            'middle_name' => trim((string) $req->getPost('middle_name')) ?: null,
            'date_of_birth' => $req->getPost('date_of_birth') ?: null,
            'gender'    => $gender,
            'blood_type' => $req->getPost('blood_type') ?: null,
            'phone'     => trim((string) $req->getPost('phone')) ?: null,
            'email'     => trim((string) $req->getPost('email')) ?: null,
            'address'   => trim((string) $req->getPost('address')) ?: null,
            'city'      => trim((string) $req->getPost('city')) ?: null,
            'emergency_contact_name' => trim((string) $req->getPost('emergency_contact_name')) ?: null,
            'emergency_contact_phone' => trim((string) $req->getPost('emergency_contact_phone')) ?: null,
            'emergency_contact_relation' => trim((string) $req->getPost('emergency_contact_relation')) ?: null,
            'insurance_provider' => trim((string) $req->getPost('insurance_provider')) ?: null,
            'insurance_number' => trim((string) $req->getPost('insurance_number')) ?: null,
            'allergies' => trim((string) $req->getPost('allergies')) ?: null,
            'medical_history' => trim((string) $req->getPost('medical_history')) ?: null,
            'branch_id' => 1,
            'admission_type' => $req->getPost('admission_type') ?: 'checkup',
            'assigned_room_id' => $req->getPost('assigned_room_id') ?: null,
            'admission_date' => $req->getPost('admission_type') === 'admission' ? date('Y-m-d H:i:s') : null,
            'is_active' => 1,
        ];

        // Validate required fields
        if ($data['first_name'] === '' || $data['last_name'] === '' || empty($data['date_of_birth'])) {
            return redirect()->back()->with('error', 'First name, last name and date of birth are required.')->withInput();
        }

        $patients = new \App\Models\PatientModel();
        $patients->insert($data);
        return redirect()->to(site_url('dashboard/doctor'))->with('success', 'Patient registered.');
    }

    public function patientRecords()
    {
        helper('url');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $doctorId = session('user_id') ?: 1;

        $records = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->where('medical_records.doctor_id', $doctorId)
            ->orderBy('medical_records.visit_date', 'DESC')
            ->findAll(50);

        return view('doctor/patient_records', ['records' => $records]);
    }

    public function viewRecord($id)
    {
        helper('url');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $record = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->where('medical_records.id', $id)
            ->first();

        if (!$record) {
            return redirect()->to(site_url('doctor/records'))->with('error', 'Record not found.');
        }

        return view('doctor/record_view', ['record' => $record]);
    }

    public function editRecord($id)
    {
        helper('url');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $patientModel = model('App\\Models\\PatientModel');
        
        $record = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->where('medical_records.id', $id)
            ->first();

        if (!$record) {
            return redirect()->to(site_url('doctor/records'))->with('error', 'Record not found.');
        }

        $patients = $patientModel->where('is_active', 1)->orderBy('last_name', 'ASC')->findAll(100);

        return view('doctor/record_edit', [
            'record' => $record,
            'patients' => $patients,
        ]);
    }

    public function updateRecord($id)
    {
        helper(['url', 'form']);
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        
        $record = $medicalRecordModel->find($id);
        if (!$record) {
            return redirect()->to(site_url('doctor/records'))->with('error', 'Record not found.');
        }

        $data = [
            'patient_id' => (int) $this->request->getPost('patient_id'),
            'visit_date' => $this->request->getPost('visit_date') ?: date('Y-m-d H:i:s'),
            'chief_complaint' => $this->request->getPost('chief_complaint') ?: null,
            'history_present_illness' => $this->request->getPost('history_present_illness') ?: null,
            'physical_examination' => $this->request->getPost('physical_examination') ?: null,
            'vital_signs' => $this->request->getPost('vital_signs') ?: null,
            'diagnosis' => $this->request->getPost('diagnosis') ?: null,
            'treatment_plan' => $this->request->getPost('treatment_plan') ?: null,
            'medications_prescribed' => $this->request->getPost('medications_prescribed') ?: null,
            'follow_up_instructions' => $this->request->getPost('follow_up_instructions') ?: null,
            'next_visit_date' => $this->request->getPost('next_visit_date') ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $medicalRecordModel->update($id, $data);
        return redirect()->to(site_url('doctor/records/' . $id))->with('success', 'Medical record updated successfully.');
    }

    public function deleteRecord($id)
    {
        helper('url');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        
        $record = $medicalRecordModel->find($id);
        if (!$record) {
            return redirect()->to(site_url('doctor/records'))->with('error', 'Record not found.');
        }

        // Soft delete
        $medicalRecordModel->delete($id);
        return redirect()->to(site_url('doctor/records'))->with('success', 'Medical record deleted successfully.');
    }

    public function prescriptions()
    {
        helper('url');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $doctorId = session('user_id') ?: 1;

        $prescriptions = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->where('medical_records.doctor_id', $doctorId)
            ->where('medical_records.medications_prescribed IS NOT NULL')
            ->where('medical_records.medications_prescribed !=', '')
            ->orderBy('medical_records.visit_date', 'DESC')
            ->findAll(50);

        return view('doctor/prescriptions', ['prescriptions' => $prescriptions]);
    }

    // Show complete schedule of consultations
    public function schedule()
    {
        helper('url');
        $doctorId = session('user_id') ?: 1;
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $appointmentModel = model('App\\Models\\AppointmentModel');
        
        $filter = $this->request->getGet('filter') ?? 'all';
        $today = date('Y-m-d');
        
        // Get all medical records (consultations) for this doctor
        $consultations = $medicalRecordModel
            ->select('medical_records.*, 
                     patients.first_name, 
                     patients.last_name, 
                     patients.patient_id as patient_code,
                     appointments.appointment_time,
                     appointments.status as appointment_status,
                     appointments.reason,
                     appointments.notes as appointment_notes')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->join('appointments', 'appointments.id = medical_records.appointment_id', 'left')
            ->where('medical_records.doctor_id', $doctorId)
            ->orderBy('medical_records.visit_date', 'DESC')
            ->orderBy('appointments.appointment_time', 'DESC')
            ->findAll(100);
        
        // Format consultations for display
        $formattedConsultations = [];
        foreach ($consultations as $consultation) {
            $visitDate = $consultation['visit_date'] ?? $consultation['created_at'] ?? date('Y-m-d H:i:s');
            $isUpcoming = strtotime($visitDate) >= strtotime($today);
            
            // Skip if filter is "upcoming" and this is not upcoming
            if ($filter === 'upcoming' && !$isUpcoming) {
                continue;
            }
            
            // Check if patient needs admission
            $needsAdmission = false;
            if (!empty($consultation['treatment_plan']) || !empty($consultation['follow_up_instructions'])) {
                // Check if patient is already admitted
                $patientModel = model('App\\Models\\PatientModel');
                $patient = $patientModel->find($consultation['patient_id']);
                if ($patient && (empty($patient['admission_date']) || $patient['admission_date'] === '')) {
                    $needsAdmission = true;
                }
            }
            
            // Get queue number from appointment notes or create from ID
            $queueNumber = 'Queue #' . ($consultation['id'] % 10 + 1);
            if (!empty($consultation['appointment_notes'])) {
                $queueNumber = $consultation['appointment_notes'] . ' | ' . $queueNumber;
            } elseif (!empty($consultation['chief_complaint'])) {
                $queueNumber = $consultation['chief_complaint'] . ' | ' . $queueNumber;
            }
            
            $formattedConsultations[] = [
                'id' => $consultation['id'],
                'appointment_id' => $consultation['appointment_id'],
                'patient_id' => $consultation['patient_id'],
                'date' => $visitDate,
                'time' => $consultation['appointment_time'] ?? date('H:i', strtotime($visitDate)),
                'patient_name' => trim(($consultation['first_name'] ?? '') . ' ' . ($consultation['last_name'] ?? '')),
                'patient_code' => $consultation['patient_code'] ?? 'N/A',
                'type' => 'COMPLETED',
                'status' => $consultation['appointment_status'] ?? 'approved',
                'notes' => $queueNumber,
                'needs_admission' => $needsAdmission,
            ];
        }
        
        return view('doctor/schedule', [
            'consultations' => $formattedConsultations,
            'currentFilter' => $filter,
        ]);
    }

    public function storeSchedule()
    {
        helper(['url','form']);
        $doctorId = session('user_id') ?: 0;
        if (!$doctorId) {
            return redirect()->to(site_url('dashboard/doctor'))->with('error', 'Unable to determine doctor.');
        }

        $daysOfWeek = (array) $this->request->getPost('days_of_week');
        $startTime = (string) $this->request->getPost('start_time');
        $endTime = (string) $this->request->getPost('end_time');

        if (empty($daysOfWeek) || $startTime === '' || $endTime === '') {
            return redirect()->back()->with('error', 'Please select at least one day and specify start and end time.')->withInput();
        }

        if ($endTime <= $startTime) {
            return redirect()->back()->with('error', 'End time must be after start time.')->withInput();
        }

        $branchId = 1;

        $scheduleModel = model('App\\Models\\StaffScheduleModel');
        // Clear existing active schedule for this doctor
        $scheduleModel->where('user_id', $doctorId)->delete();

        foreach ($daysOfWeek as $day) {
            $scheduleModel->insert([
                'user_id' => $doctorId,
                'branch_id' => $branchId,
                'day_of_week' => strtolower($day),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_active' => 1,
            ]);
        }

        return redirect()->to(site_url('dashboard/doctor'))->with('success', 'Schedule updated successfully.');
    }

    public function labResults()
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        $doctorId = session('user_id') ?: 1;

        $results = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->where('lab_tests.doctor_id', $doctorId)
            ->where('lab_tests.status', 'completed')
            ->orderBy('lab_tests.result_date', 'DESC')
            ->findAll(50);

        return view('doctor/lab_results', ['results' => $results]);
    }

    public function viewLabResult($id)
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        $result = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->where('lab_tests.id', $id)
            ->first();

        if (!$result) {
            return redirect()->to(site_url('doctor/lab-results'))->with('error', 'Lab result not found.');
        }

        return view('doctor/lab_result_view', ['result' => $result]);
    }

    public function patients()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $doctorId = session('user_id') ?: 1;
        $today = date('Y-m-d');
        
        $patients = $patientModel
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->findAll(100);

        // Check consultation status for each patient
        $patientsWithStatus = [];
        foreach ($patients as $patient) {
            // Check if patient has a consultation today
            $hasConsultationToday = $medicalRecordModel
                ->where('patient_id', $patient['id'])
                ->where('doctor_id', $doctorId)
                ->where('DATE(visit_date)', $today)
                ->countAllResults() > 0;
            
            $patient['consultation_done'] = $hasConsultationToday;
            $patientsWithStatus[] = $patient;
        }

        return view('doctor/patients', ['patients' => $patientsWithStatus]);
    }

    public function viewPatient($id)
    {
        helper(['url']);
        $id = (int) $id;
        $patient = model('App\Models\PatientModel')->find($id);
        if (!$patient) {
            return redirect()->to(site_url('doctor/patients'))->with('error', 'Patient not found.');
        }
        return view('admin/patient_view', ['patient' => $patient]);
    }

    public function searchPatients()
    {
        helper('url');
        $searchTerm = $this->request->getGet('q');
        $patientModel = model('App\\Models\\PatientModel');
        
        $patients = $patientModel
            ->where('is_active', 1)
            ->groupStart()
                ->like('first_name', $searchTerm)
                ->orLike('last_name', $searchTerm)
                ->orLike('patient_id', $searchTerm)
            ->groupEnd()
            ->orderBy('last_name', 'ASC')
            ->findAll(20);

        return $this->response->setJSON($patients);
    }

    public function startConsultation($patientId)
    {
        helper('url');
        $patientId = (int) $patientId;
        $patientModel = model('App\\Models\\PatientModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $db = \Config\Database::connect();
        $doctorId = session('user_id') ?: 1;
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('doctor/patients'))->with('error', 'Patient not found.');
        }
        
        // Calculate age
        $age = null;
        if (!empty($patient['date_of_birth'])) {
            try {
                $birth = new \DateTime($patient['date_of_birth']);
                $today = new \DateTime();
                $age = (int)$today->diff($birth)->y;
            } catch (\Exception $e) {
                $age = null;
            }
        }
        
        // Get existing consultation for this patient today (if any)
        $today = date('Y-m-d');
        $existingConsultation = $medicalRecordModel
            ->where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->where('DATE(visit_date)', $today)
            ->orderBy('created_at', 'DESC')
            ->first();
        
        // Get all available medicines from pharmacy for medication prescription
        $medicines = [];
        if ($db->tableExists('pharmacy')) {
            $medicines = $db->table('pharmacy')
                ->where('quantity >', 0)
                ->orderBy('item_name', 'ASC')
                ->get()
                ->getResultArray();
        } elseif ($db->tableExists('medicines')) {
            $medicines = $db->table('medicines')
                ->where('stock >', 0)
                ->where('is_active', 1)
                ->orderBy('name', 'ASC')
                ->get()
                ->getResultArray();
        }
        
        // Get all active nurses (for medication orders)
        $nurses = [];
        if ($db->tableExists('users')) {
            $userModel = model('App\\Models\\UserModel');
            $nurses = $userModel
                ->where('role', 'nurse')
                ->where('is_active', 1)
                ->orderBy('username', 'ASC')
                ->findAll();
        }
        
        // Get active lab tests grouped by category (for lab test requests)
        $labTests = [];
        try {
            if ($db->tableExists('lab_test_catalog')) {
                $labTestCatalogModel = model('App\\Models\\LabTestCatalogModel');
                $labTests = $labTestCatalogModel->getActiveTestsGroupedByCategory();
            } elseif ($db->tableExists('lab_tests')) {
                $labTestModel = model('App\\Models\\LabTestModel');
                $distinctTests = $db->table('lab_tests')
                    ->select('test_name, test_type, test_category, cost as price, requires_specimen')
                    ->groupBy('test_name')
                    ->orderBy('test_name', 'ASC')
                    ->get()
                    ->getResultArray();
                
                foreach ($distinctTests as $test) {
                    if (strpos($test['test_name'], ',') !== false || strpos(strtolower($test['test_name']), '(ordered)') !== false) {
                        continue;
                    }
                    
                    $category = ((int)$test['requires_specimen'] === 1) ? 'with_specimen' : 'without_specimen';
                    $testType = $test['test_type'] ?? 'Other';
                    
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
            }
        } catch (\Exception $e) {
            $labTests = [];
        }
        
        // Format patient data
        $isAdmitted = ($patient['admission_type'] ?? '') === 'admission';
        $patientData = [
            'id' => $patient['id'],
            'patient_id' => $patient['patient_id'] ?? ('P-' . $patient['id']),
            'firstname' => $patient['first_name'] ?? '',
            'lastname' => $patient['last_name'] ?? '',
            'full_name' => trim(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')),
            'birthdate' => $patient['date_of_birth'] ?? null,
            'age' => $age,
            'gender' => strtolower($patient['gender'] ?? ''),
            'contact' => $patient['phone'] ?? null,
            'address' => $patient['address'] ?? null,
            'visit_type' => $isAdmitted ? 'In-Patient' : 'Out-Patient',
            'is_admitted' => $isAdmitted,
            'admission_type' => $patient['admission_type'] ?? 'checkup',
        ];
        
        return view('doctor/consultation_start', [
            'patient' => $patientData,
            'existing_consultation' => $existingConsultation,
            'medicines' => $medicines,
            'nurses' => $nurses,
            'labTests' => $labTests,
        ]);
    }

    public function editPatient($id)
    {
        helper('url');
        $id = (int) $id;
        $patientModel = model('App\\Models\\PatientModel');
        
        $patient = $patientModel->find($id);
        if (!$patient) {
            return redirect()->to(site_url('doctor/patients'))->with('error', 'Patient not found.');
        }
        
        return view('doctor/patient_edit', ['patient' => $patient]);
    }

    public function updatePatient($id)
    {
        helper(['url', 'form']);
        $id = (int) $id;
        $req = $this->request;
        $patientModel = model('App\\Models\\PatientModel');
        
        $patient = $patientModel->find($id);
        if (!$patient) {
            return redirect()->to(site_url('doctor/patients'))->with('error', 'Patient not found.');
        }
        
        $data = [
            'first_name' => trim((string) $req->getPost('first_name')),
            'last_name'  => trim((string) $req->getPost('last_name')),
            'middle_name' => trim((string) $req->getPost('middle_name')) ?: null,
            'date_of_birth' => $req->getPost('date_of_birth') ?: null,
            'gender'    => $req->getPost('gender') ?: null,
            'blood_type' => $req->getPost('blood_type') ?: null,
            'phone'     => trim((string) $req->getPost('phone')) ?: null,
            'email'     => trim((string) $req->getPost('email')) ?: null,
            'address'   => trim((string) $req->getPost('address')) ?: null,
            'city'      => trim((string) $req->getPost('city')) ?: null,
            'emergency_contact_name' => trim((string) $req->getPost('emergency_contact_name')) ?: null,
            'emergency_contact_phone' => trim((string) $req->getPost('emergency_contact_phone')) ?: null,
            'emergency_contact_relation' => trim((string) $req->getPost('emergency_contact_relation')) ?: null,
            'insurance_provider' => trim((string) $req->getPost('insurance_provider')) ?: null,
            'insurance_number' => trim((string) $req->getPost('insurance_number')) ?: null,
            'allergies' => trim((string) $req->getPost('allergies')) ?: null,
            'medical_history' => trim((string) $req->getPost('medical_history')) ?: null,
            'admission_type' => $req->getPost('admission_type') ?: 'checkup',
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $patientModel->update($id, $data);
        return redirect()->to(site_url('doctor/patients'))->with('success', 'Patient updated successfully.');
    }

    public function deletePatient($id)
    {
        helper('url');
        $id = (int) $id;
        $patientModel = model('App\\Models\\PatientModel');
        
        $patient = $patientModel->find($id);
        if (!$patient) {
            return redirect()->to(site_url('doctor/patients'))->with('error', 'Patient not found.');
        }
        
        // Soft delete
        $patientModel->delete($id);
        return redirect()->to(site_url('doctor/patients'))->with('success', 'Patient deleted successfully.');
    }

    /**
     * Save consultation form data
     * 
     * Flow:
     * 1. Doctor fills consultation form (consultation_start.php)
     * 2. Form submits to: POST /doctor/consultations/save-consultation
     * 3. Creates medical record (consultation)
     * 4. If lab test requested with specimen:
     *    - Creates lab_test with requires_specimen=1, status='requested', accountant_approved=0
     *    - Goes to accountant for approval
     *    - After approval: accountant_approved=1, appears in nurse lab samples
     *    - Nurse can mark as collected (doesn't depend on doctor_id validity)
     * 5. If medicine prescribed:
     *    - Creates prescription record
     *    - If hospital_pharmacy: assigns nurse_id, status='pending_pharmacy'
     *    - If outside: patient gets prescription to buy elsewhere
     */
    public function saveConsultation()
    {
        helper(['url', 'form']);
        $req = $this->request;
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $labTestModel = model('App\\Models\\LabTestModel');
        $billingModel = model('App\\Models\\BillingModel');
        $billingItemModel = model('App\\Models\\BillingItemModel');
        $db = \Config\Database::connect();
        $doctorId = session('user_id') ?: 1;
        
        // Validate doctor_id exists (but allow fallback if invalid)
        // This ensures consultation can proceed even if doctor_id is defective
        $userModel = model('App\\Models\\UserModel');
        if ($doctorId && $doctorId > 0) {
            $doctor = $userModel->find($doctorId);
            if (!$doctor || ($doctor['role'] ?? '') !== 'doctor') {
                // If doctor_id is invalid, use fallback but log warning
                log_message('warning', "Invalid doctor_id {$doctorId} in consultation. Using fallback.");
                $doctorId = 1; // Fallback to default doctor
            }
        } else {
            $doctorId = 1; // Fallback to default doctor
        }
        
        $patientId = (int) $req->getPost('patient_id');
        if (!$patientId) {
            return redirect()->back()->with('error', 'Patient ID is required.')->withInput();
        }
        
        // Create medical record (consultation)
        $visitDate = $req->getPost('consultation_date') . ' ' . ($req->getPost('consultation_time') ?: date('H:i:s'));
        
        // Handle medication prescription data first
        $prescribeMedication = $req->getPost('prescribe_medication');
        $medicationData = null;
        if ($prescribeMedication === 'yes') {
            $medicineName = $req->getPost('medicine_name');
            $dosage = $req->getPost('dosage');
            $frequency = $req->getPost('frequency');
            $duration = $req->getPost('duration');
            $purchaseLocation = $req->getPost('purchase_location');
            $nurseId = $req->getPost('nurse_id') ?: null;
            
            if ($medicineName && $dosage && $frequency && $duration && $purchaseLocation) {
                $medicationData = [
                    'medicine_name' => $medicineName,
                    'dosage' => $dosage,
                    'frequency' => $frequency,
                    'duration' => $duration,
                    'purchase_location' => $purchaseLocation,
                ];
                if ($purchaseLocation === 'hospital_pharmacy' && $nurseId) {
                    $medicationData['nurse_id'] = (int) $nurseId;
                    $medicationData['status'] = 'pending_pharmacy';
                }
            }
        }
        
        $recordData = [
            'record_number' => 'MR-' . date('YmdHis'),
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'visit_date' => $visitDate,
            'chief_complaint' => $req->getPost('observations') ?: null,
            'history_present_illness' => $req->getPost('observations') ?: null,
            'physical_examination' => $req->getPost('observations') ?: null,
            'diagnosis' => $req->getPost('diagnosis') ?: null,
            'treatment_plan' => $req->getPost('notes') ?: null,
            'follow_up_instructions' => $req->getPost('notes') ?: null,
            'medications_prescribed' => $medicationData ? json_encode($medicationData) : null,
            'branch_id' => 1,
        ];
        
        // Try to insert medical record with error handling
        try {
            $inserted = $medicalRecordModel->insert($recordData);
            if ($inserted === false) {
                $errors = $medicalRecordModel->errors();
                log_message('error', 'Failed to insert medical record: ' . json_encode($errors));
                return redirect()->back()->withInput()->with('error', 'Failed to save consultation: ' . implode(', ', $errors));
            }
            $recordId = $medicalRecordModel->getInsertID();
            log_message('info', 'Medical record saved successfully: ' . $recordId);
        } catch (\Exception $e) {
            log_message('error', 'Exception saving medical record: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error saving consultation: ' . $e->getMessage());
        }
        
        // Handle lab test request if provided
        $requestLabTest = $req->getPost('request_lab_test');
        if ($requestLabTest === 'yes') {
            $labTestName = $req->getPost('lab_test_name');
            $labNurseId = $req->getPost('lab_nurse_id') ?: null;
            $labTestRemarks = $req->getPost('lab_test_remarks') ?: null;
            
            if (!empty($labTestName)) {
                // Determine test details
                $requiresSpecimen = true;
                $testPrice = 300.00;
                $testType = 'General';
                $category = 'other';
                
                // Check test type from lab_test_catalog or lab_tests
                if ($db->tableExists('lab_test_catalog')) {
                    $labTestCatalogModel = model('App\\Models\\LabTestCatalogModel');
                    $testInfo = $db->table('lab_test_catalog')
                        ->where('test_name', $labTestName)
                        ->where('is_active', 1)
                        ->get()
                        ->getRowArray();
                    
                    if ($testInfo) {
                        $testType = $testInfo['test_type'] ?? 'General';
                        $testPrice = (float)($testInfo['price'] ?? 300.00);
                        $specimenCategory = $testInfo['specimen_category'] ?? 'with_specimen';
                        $requiresSpecimen = ($specimenCategory === 'with_specimen');
                        $category = $testInfo['test_category'] ?? 'other';
                    }
                } elseif ($db->tableExists('lab_tests')) {
                    $testInfo = $db->table('lab_tests')
                        ->where('test_name', $labTestName)
                        ->orderBy('created_at', 'DESC')
                        ->get()
                        ->getRowArray();
                    
                    if ($testInfo) {
                        $testType = $testInfo['test_type'] ?? 'General';
                        $testPrice = (float)($testInfo['cost'] ?? 300.00);
                        $requiresSpecimen = ((int)($testInfo['requires_specimen'] ?? 1) === 1);
                        $category = $testInfo['test_category'] ?? 'other';
                    }
                }
                
                // Create lab test request
                // Status starts as 'requested' but accountant_approved = 0, so it won't proceed until approved
                $labTestData = [
                    'test_number' => 'LT-' . date('YmdHis'),
                    'patient_id' => $patientId,
                    'doctor_id' => $doctorId,
                    'test_type' => $testType,
                    'test_name' => $labTestName,
                    'test_category' => $category,
                    'requested_date' => $visitDate,
                    'status' => 'requested', // Will be approved by accountant, then proceed to nurse (if with specimen) or lab (if without specimen)
                    'branch_id' => 1,
                    'priority' => 'routine',
                    'notes' => $labTestRemarks,
                    'requires_specimen' => $requiresSpecimen ? 1 : 0,
                    'accountant_approved' => 0, // Explicitly set to 0 - pending accountant approval
                    'cost' => $testPrice,
                ];
                
                // Only add nurse_id if specimen is required and nurse is assigned
                if ($requiresSpecimen && $labNurseId) {
                    $labTestData['nurse_id'] = (int) $labNurseId;
                } else {
                    // Make sure nurse_id is not set if not needed
                    unset($labTestData['nurse_id']);
                }
                
                // Try to insert lab test with error handling
                try {
                    $inserted = $labTestModel->insert($labTestData);
                    if ($inserted === false) {
                        $errors = $labTestModel->errors();
                        log_message('error', 'Failed to insert lab test: ' . json_encode($errors));
                        log_message('error', 'Lab test data: ' . json_encode($labTestData));
                        return redirect()->back()->withInput()->with('error', 'Failed to save lab test request: ' . implode(', ', $errors));
                    }
                    $labTestId = $labTestModel->getInsertID();
                    log_message('info', 'Lab test saved successfully. ID: ' . $labTestId . ', Test: ' . $labTestName);
                } catch (\Exception $e) {
                    log_message('error', 'Exception saving lab test: ' . $e->getMessage());
                    log_message('error', 'Lab test data: ' . json_encode($labTestData));
                    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                    return redirect()->back()->withInput()->with('error', 'Error saving lab test: ' . $e->getMessage());
                }
            }
        }
        
        // Add consultation fee to patient's consolidated bill
        $consultationFee = 500.00;
        
        // Get or create active bill for this patient (consolidated billing)
        $activeBill = $billingModel->getOrCreateActiveBill($patientId, 1, $doctorId);
        $billingId = $activeBill['id'];
        
        // Check if consultation was already added today for this patient (avoid duplicate charges)
        $today = date('Y-m-d');
        $existingConsultation = $billingItemModel
            ->where('billing_id', $billingId)
            ->where('item_type', 'consultation')
            ->where('item_name', 'Doctor Consultation')
            ->where('description LIKE', '%Consultation on ' . $today . '%')
            ->first();
        
        if (!$existingConsultation) {
            // Add consultation item to the bill
            $billingItemData = [
                'billing_id' => $billingId,
                'item_type' => 'consultation',
                'item_name' => 'Doctor Consultation',
                'description' => 'Consultation on ' . $today . ' - Doctor ID: ' . $doctorId,
                'quantity' => 1,
                'unit_price' => $consultationFee,
                'total_price' => $consultationFee,
            ];
            $billingItemModel->insert($billingItemData);
            
            // Recalculate bill totals
            $billingModel->recalculateBill($billingId);
        }
        
        // Handle medication prescription if provided
        if ($prescribeMedication === 'yes' && $medicationData) {
            // Only require nurse_id if hospital pharmacy
            if ($medicationData['purchase_location'] === 'hospital_pharmacy' && empty($medicationData['nurse_id'])) {
                return redirect()->back()->withInput()->with('error', 'Please assign a nurse for hospital pharmacy medication orders.');
            }
            
                // Create prescription record
                $prescriptionModel = model('App\\Models\\PrescriptionModel');
                
                // Calculate end_date from duration
                $startDate = date('Y-m-d');
                $endDate = $startDate; // Default to start date
                
                // Try to parse duration (e.g., "7 days", "2 weeks", "1 month")
                $duration = strtolower(trim($medicationData['duration']));
                if (preg_match('/(\d+)\s*(day|days|week|weeks|month|months)/', $duration, $matches)) {
                    $number = (int)$matches[1];
                    $unit = $matches[2];
                    
                    if (strpos($unit, 'day') !== false) {
                        $endDate = date('Y-m-d', strtotime("+{$number} days"));
                    } elseif (strpos($unit, 'week') !== false) {
                        $endDate = date('Y-m-d', strtotime("+{$number} weeks"));
                    } elseif (strpos($unit, 'month') !== false) {
                        $endDate = date('Y-m-d', strtotime("+{$number} months"));
                    }
                } else {
                    // If can't parse, default to 7 days
                    $endDate = date('Y-m-d', strtotime('+7 days'));
                }
                
                $prescriptionData = [
                    'patient_id' => $patientId,
                    'doctor_id' => $doctorId,
                    'medication' => $medicationData['medicine_name'],
                    'dosage' => $medicationData['dosage'],
                    'frequency' => $medicationData['frequency'],
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'pending', // Set status to pending so it shows in pharmacy queue
                    'instructions' => 'Duration: ' . $medicationData['duration'] . '. Purchase location: ' . $medicationData['purchase_location'],
                ];
                
                // Try to insert prescription with error handling
                try {
                    $inserted = $prescriptionModel->insert($prescriptionData);
                    if ($inserted === false) {
                        $errors = $prescriptionModel->errors();
                        log_message('error', 'Failed to insert prescription: ' . json_encode($errors));
                        log_message('error', 'Prescription data: ' . json_encode($prescriptionData));
                        return redirect()->back()->withInput()->with('error', 'Failed to save prescription: ' . implode(', ', $errors));
                    }
                    log_message('info', 'Prescription saved successfully: ' . $prescriptionModel->getInsertID());
                } catch (\Exception $e) {
                    log_message('error', 'Exception saving prescription: ' . $e->getMessage());
                    log_message('error', 'Prescription data: ' . json_encode($prescriptionData));
                    return redirect()->back()->withInput()->with('error', 'Error saving prescription: ' . $e->getMessage());
                }
        }
        
        // Handle admission if marked
        $forAdmission = $req->getPost('for_admission') ? 1 : 0;
        if ($forAdmission) {
            $patientModel = model('App\\Models\\PatientModel');
            $patientModel->update($patientId, [
                'admission_type' => 'admission',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        // Redirect to consultation done page
        $redirectData = [
            'consultation_done' => true,
            'patient_id' => $patientId,
            'record_id' => $recordId,
        ];
        
        if ($prescribeMedication === 'yes') {
            $purchaseLocation = $req->getPost('purchase_location');
            $redirectData['medication_prescribed'] = true;
            $redirectData['purchase_location'] = $purchaseLocation;
        }
        
        if ($requestLabTest === 'yes') {
            $redirectData['lab_test_requested'] = true;
        }
        
        return redirect()->to(site_url('doctor/consultations/done/' . $patientId))->with('consultation_data', $redirectData);
    }

    public function consultationDone($patientId)
    {
        helper('url');
        $patientId = (int) $patientId;
        $patientModel = model('App\\Models\\PatientModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('doctor/patients'))->with('error', 'Patient not found.');
        }
        
        // Get the latest consultation record
        $latestRecord = $medicalRecordModel
            ->where('patient_id', $patientId)
            ->where('doctor_id', session('user_id') ?: 1)
            ->orderBy('created_at', 'DESC')
            ->first();
        
        $consultationData = session()->getFlashdata('consultation_data') ?: [];
        
        return view('doctor/consultation_done', [
            'patient' => $patient,
            'consultation' => $latestRecord,
            'consultation_data' => $consultationData,
        ]);
    }

    public function admitPatients()
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
        
        // Get patients pending admission - those marked for admission but not yet fully admitted
        // Exclude patients who already have admission_date and assigned_room_id
        $pendingAdmissions = $patientModel
            ->select('patients.*, rooms.room_number, rooms.room_type, beds.bed_number')
            ->join('rooms', 'rooms.id = patients.assigned_room_id', 'left')
            ->join('beds', 'beds.id = patients.assigned_bed_id', 'left')
            ->where('patients.is_active', 1)
            ->where('patients.admission_type', 'admission')
            ->groupStart()
                ->where('patients.admission_date', null)
                ->orWhere('patients.admission_date', '')
                ->orWhere('patients.assigned_room_id', null)
            ->groupEnd()
            ->orderBy('patients.created_at', 'DESC')
            ->findAll(50);
        
        // Format the data for the view
        $formattedAdmissions = [];
        foreach ($pendingAdmissions as $patient) {
            $formattedAdmissions[] = [
                'id' => $patient['id'],
                'patient_id' => $patient['patient_id'] ?? 'N/A',
                'first_name' => $patient['first_name'] ?? '',
                'last_name' => $patient['last_name'] ?? '',
                'phone' => $patient['phone'] ?? 'N/A',
                'room_number' => $patient['room_number'] ?? 'Not assigned',
                'bed_number' => $patient['bed_number'] ?? null,
                'room_type' => $patient['room_type'] ?? null,
                'created_at' => $patient['created_at'] ?? date('Y-m-d H:i:s'),
            ];
        }
        
        return view('doctor/admit_patients', [
            'pendingAdmissions' => $formattedAdmissions,
        ]);
    }

    public function admitPatientForm($patientId)
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $branchModel = model('App\\Models\\BranchModel');
        $userModel = model('App\\Models\\UserModel');
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('doctor/admit-patients'))->with('error', 'Patient not found.');
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
        
        return view('doctor/admit_patient_form', [
            'patient' => $patient,
            'availableRooms' => $availableRooms,
            'doctors' => $doctors,
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
            return redirect()->to(site_url('doctor/admit-patients'))->with('error', 'Patient not found.');
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
        
        return redirect()->to(site_url('doctor/admit-patients'))->with('success', $successMsg);
    }

    public function getBedsByRoom($roomId)
    {
        $bedModel = model('App\\Models\\BedModel');
        $beds = $bedModel->getAvailableBeds($roomId);
        
        return $this->response->setJSON([
            'beds' => $beds
        ]);
    }

    public function upcomingConsultations()
    {
        helper('url');
        $appointmentModel = model('App\\Models\\AppointmentModel');
        $doctorId = session('user_id') ?: 1;
        $today = date('Y-m-d');
        
        // Get upcoming appointments (future dates and today's not completed)
        $upcoming = $appointmentModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.phone')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->where('appointments.doctor_id', $doctorId)
            ->where('appointments.status !=', 'cancelled')
            ->where('appointments.status !=', 'completed')
            ->groupStart()
                ->where('DATE(appointments.appointment_date) >', $today)
                ->orGroupStart()
                    ->where('DATE(appointments.appointment_date)', $today)
                    ->whereIn('appointments.status', ['scheduled', 'confirmed', 'checked_in', 'in_progress'])
                ->groupEnd()
            ->groupEnd()
            ->orderBy('appointments.appointment_date', 'ASC')
            ->orderBy('appointments.appointment_time', 'ASC')
            ->findAll(50);
        
        return view('doctor/upcoming_consultations', [
            'consultations' => $upcoming,
        ]);
    }

    public function labRequestsFromNurses()
    {
        helper('url');
        $db = \Config\Database::connect();
        $doctorId = session('user_id') ?: 1;
        
        // Get lab requests from nurses (where nurse_id is set and status is requested)
        $pendingRequests = $db->query("
            SELECT 
                lt.*,
                p.first_name,
                p.last_name,
                p.patient_id as patient_code,
                u.first_name as nurse_first,
                u.last_name as nurse_last,
                u.username as nurse_username
            FROM lab_tests lt
            INNER JOIN patients p ON p.id = lt.patient_id
            LEFT JOIN users u ON u.id = lt.nurse_id
            WHERE lt.nurse_id IS NOT NULL
            AND lt.status = 'requested'
            AND (lt.deleted_at IS NULL OR lt.deleted_at = '')
            ORDER BY lt.requested_date ASC
            LIMIT 50
        ")->getResultArray();
        
        // Get confirmed requests (status is sample_collected, in_progress, or completed)
        $confirmedRequests = $db->query("
            SELECT 
                lt.*,
                p.first_name,
                p.last_name,
                p.patient_id as patient_code,
                u.first_name as nurse_first,
                u.last_name as nurse_last,
                u.username as nurse_username
            FROM lab_tests lt
            INNER JOIN patients p ON p.id = lt.patient_id
            LEFT JOIN users u ON u.id = lt.nurse_id
            WHERE lt.nurse_id IS NOT NULL
            AND lt.status IN ('sample_collected', 'in_progress', 'completed')
            AND (lt.deleted_at IS NULL OR lt.deleted_at = '')
            ORDER BY lt.requested_date DESC
            LIMIT 50
        ")->getResultArray();
        
        return view('doctor/lab_requests_nurses', [
            'pendingRequests' => $pendingRequests,
            'confirmedRequests' => $confirmedRequests,
        ]);
    }

    public function confirmLabRequest($testId)
    {
        helper(['url', 'form']);
        $labTestModel = model('App\\Models\\LabTestModel');
        $doctorId = session('user_id') ?: 1;
        
        $test = $labTestModel->find($testId);
        if (!$test) {
            return redirect()->to(site_url('doctor/lab-requests-nurses'))
                ->with('error', 'Lab test not found.');
        }
        
        // Update test to approved status
        $labTestModel->update($testId, [
            'accountant_approved' => 1,
            'doctor_id' => $doctorId,
            'status' => 'requested',
        ]);
        
        return redirect()->to(site_url('doctor/lab-requests-nurses'))
            ->with('success', 'Lab request confirmed successfully.');
    }

    public function orders()
    {
        helper('url');
        $prescriptionModel = model('App\\Models\\PrescriptionModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $doctorId = session('user_id') ?: 1;
        
        $status = $this->request->getGet('status') ?? 'all';
        
        // Get prescriptions as orders
        $prescriptions = $prescriptionModel
            ->select('prescriptions.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = prescriptions.patient_id')
            ->where('prescriptions.doctor_id', $doctorId)
            ->orderBy('prescriptions.start_date', 'DESC')
            ->findAll(100);
        
        // Get medical records with medications as orders
        $medicalRecords = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->where('medical_records.doctor_id', $doctorId)
            ->where('medical_records.medications_prescribed IS NOT NULL')
            ->where('medical_records.medications_prescribed !=', '')
            ->orderBy('medical_records.visit_date', 'DESC')
            ->findAll(100);
        
        // Combine and format orders
        $allOrders = [];
        foreach ($prescriptions as $rx) {
            $allOrders[] = [
                'id' => 'RX-' . $rx['id'],
                'type' => 'prescription',
                'patient_name' => trim(($rx['first_name'] ?? '') . ' ' . ($rx['last_name'] ?? '')),
                'patient_code' => $rx['patient_code'] ?? 'N/A',
                'medication' => $rx['medication'] ?? 'N/A',
                'date' => $rx['start_date'] ?? $rx['created_at'] ?? date('Y-m-d'),
                'status' => $rx['status'] ?? 'pending',
            ];
        }
        
        foreach ($medicalRecords as $record) {
            $medications = json_decode($record['medications_prescribed'], true);
            if ($medications) {
                $medName = $medications['medicine_name'] ?? $medications['drug'] ?? $medications['name'] ?? 'N/A';
                $allOrders[] = [
                    'id' => 'MR-' . $record['id'],
                    'type' => 'medical_record',
                    'patient_name' => trim(($record['first_name'] ?? '') . ' ' . ($record['last_name'] ?? '')),
                    'patient_code' => $record['patient_code'] ?? 'N/A',
                    'medication' => $medName,
                    'date' => $record['visit_date'] ?? $record['created_at'] ?? date('Y-m-d'),
                    'status' => 'completed',
                ];
            }
        }
        
        // Filter by status
        if ($status !== 'all') {
            $filteredOrders = [];
            foreach ($allOrders as $order) {
                if ($status === 'in_progress' && ($order['status'] === 'prepared' || $order['status'] === 'approved')) {
                    $filteredOrders[] = $order;
                } elseif ($order['status'] === $status) {
                    $filteredOrders[] = $order;
                }
            }
            $allOrders = $filteredOrders;
        }
        
        // Sort by date
        usort($allOrders, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        // Count by status
        $counts = [
            'all' => count($allOrders),
            'pending' => count(array_filter($allOrders, fn($o) => $o['status'] === 'pending')),
            'in_progress' => count(array_filter($allOrders, fn($o) => $o['status'] === 'prepared' || $o['status'] === 'approved')),
            'completed' => count(array_filter($allOrders, fn($o) => $o['status'] === 'completed' || $o['status'] === 'dispensed')),
            'cancelled' => count(array_filter($allOrders, fn($o) => $o['status'] === 'cancelled')),
        ];
        
        return view('doctor/orders', [
            'orders' => $allOrders,
            'currentStatus' => $status,
            'counts' => $counts,
        ]);
    }

    public function dischargePatients()
    {
        helper('url');
        $db = \Config\Database::connect();
        $doctorId = session('user_id') ?: 1;
        
        // Get patients admitted by this doctor
        // First try to get patients where attending_physician_id matches
        $admittedPatients = $db->query("
            SELECT 
                p.*,
                r.room_number,
                r.room_type,
                b.bed_number,
                b.bed_type,
                p.assigned_bed_id
            FROM patients p
            LEFT JOIN rooms r ON r.id = p.assigned_room_id
            LEFT JOIN beds b ON b.id = p.assigned_bed_id AND b.is_active = 1
            WHERE p.attending_physician_id = ?
            AND p.admission_date IS NOT NULL
            AND p.admission_date != ''
            AND p.assigned_room_id IS NOT NULL
            AND (p.discharge_date IS NULL OR p.discharge_date = '')
            AND (p.deleted_at IS NULL OR p.deleted_at = '')
            AND p.is_active = 1
            ORDER BY p.admission_date DESC
            LIMIT 50
        ", [$doctorId])->getResultArray();
        
        // If no patients found with attending_physician_id, show all admitted patients
        // (This helps identify if the issue is with attending_physician_id assignment)
        if (empty($admittedPatients)) {
            $admittedPatients = $db->query("
                SELECT 
                    p.*,
                    r.room_number,
                    r.room_type,
                    b.bed_number,
                    b.bed_type,
                    p.assigned_bed_id
                FROM patients p
                LEFT JOIN rooms r ON r.id = p.assigned_room_id
                LEFT JOIN beds b ON b.id = p.assigned_bed_id AND b.is_active = 1
                WHERE p.admission_date IS NOT NULL
                AND p.admission_date != ''
                AND p.assigned_room_id IS NOT NULL
                AND (p.discharge_date IS NULL OR p.discharge_date = '')
                AND (p.deleted_at IS NULL OR p.deleted_at = '')
                AND p.is_active = 1
                ORDER BY p.admission_date DESC
                LIMIT 50
            ")->getResultArray();
        }
        
        return view('doctor/discharge_patients', [
            'patients' => $admittedPatients,
        ]);
    }

    public function processDischarge($patientId)
    {
        helper(['url', 'form']);
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $bedModel = model('App\\Models\\BedModel');
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('doctor/discharge-patients'))
                ->with('error', 'Patient not found.');
        }
        
        $dischargeDate = $this->request->getPost('discharge_date') ?: date('Y-m-d');
        $dischargeNotes = $this->request->getPost('discharge_notes');
        
        // Update patient discharge info
        $updateData = [
            'discharge_date' => $dischargeDate,
            'discharge_notes' => $dischargeNotes,
            'admission_type' => null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        // Free up room and bed if assigned
        if (!empty($patient['assigned_room_id'])) {
            $room = $roomModel->find($patient['assigned_room_id']);
            if ($room) {
                $newOccupancy = max(0, ($room['current_occupancy'] ?? 0) - 1);
                $roomModel->update($patient['assigned_room_id'], [
                    'current_occupancy' => $newOccupancy,
                    'status' => $newOccupancy <= 0 ? 'available' : 'occupied'
                ]);
            }
        }
        
        if (!empty($patient['assigned_bed_id'])) {
            $bedModel->update($patient['assigned_bed_id'], [
                'status' => 'available'
            ]);
        }
        
        // Clear room and bed assignments
        $updateData['assigned_room_id'] = null;
        $updateData['assigned_bed_id'] = null;
        
        $patientModel->update($patientId, $updateData);
        
        return redirect()->to(site_url('doctor/discharge-patients'))
            ->with('success', 'Patient discharged successfully.');
    }
}
