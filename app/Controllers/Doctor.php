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
        return view('doctor/record_new');
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
        $patients = $patientModel
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->findAll(100);

        $userModel = model('App\\Models\\UserModel');
        $doctors = $userModel
            ->where('role', 'doctor')
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->findAll(100);

        return view('doctor/lab_request_new', [
            'patients' => $patients,
            'doctors'  => $doctors,
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

        $data = [
            'test_number' => 'LT-' . date('YmdHis'),
            'patient_id' => $patientId,
            'doctor_id' => (int) $doctorId,
            'test_type' => $req->getPost('test_type') ?: 'ordered',
            'test_name' => $testName,
            'test_category' => $category,
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
        $data = [
            'patient_id' => 'P-' . date('YmdHis'),
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
            'branch_id' => 1,
            'admission_type' => $req->getPost('admission_type') ?: 'checkup',
            'assigned_room_id' => $req->getPost('assigned_room_id') ?: null,
            'admission_date' => $req->getPost('admission_type') === 'admission' ? date('Y-m-d H:i:s') : null,
            'is_active' => 1,
        ];

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

    // Allow doctors to manage their own weekly schedule
    public function schedule()
    {
        helper('url');
        $doctorId = session('user_id') ?: 0;
        $scheduleModel = model('App\\Models\\StaffScheduleModel');

        $schedule = $scheduleModel
            ->where('user_id', $doctorId)
            ->where('is_active', 1)
            ->orderBy('day_of_week', 'ASC')
            ->findAll();

        return view('doctor/schedule', [
            'schedule' => $schedule,
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
        $patients = $patientModel
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->findAll(100);

        return view('doctor/patients', ['patients' => $patients]);
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
}
