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

        $pendingLabSamples = $labTestModel
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

        // Pending lab samples to collect
        $pendingSamples = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->where('lab_tests.status', 'requested')
            ->orderBy('lab_tests.requested_date', 'ASC')
            ->findAll(5);

        // Ward patients (patients with recent medical records)
        $wardPatients = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->where('DATE(medical_records.visit_date) >=', date('Y-m-d', strtotime('-7 days')))
            ->orderBy('medical_records.visit_date', 'DESC')
            ->findAll(10);

        return view('nurse/dashboard', [
            'activePatients' => $activePatients,
            'pendingTasks' => $pendingTasks,
            'vitalsRecorded' => $vitalsRecorded,
            'pendingLabSamples' => $pendingLabSamples,
            'appointments' => $appointments,
            'recentVitals' => $recentVitals,
            'pendingSamples' => $pendingSamples,
            'wardPatients' => $wardPatients,
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
        return view('nurse/note_new');
    }

    public function storeNote()
    {
        helper(['url','form']);
        $patientId = (int) $this->request->getPost('patient_id');
        $note      = trim((string) $this->request->getPost('note'));
        if (!$patientId || $note === '') {
            return redirect()->back()->with('error', 'Patient and note are required.')->withInput();
        }
        $data = [
            'record_number' => 'NN-' . date('YmdHis'),
            'patient_id' => $patientId,
            'appointment_id' => $this->request->getPost('appointment_id') ?: null,
            'doctor_id' => (int) ($this->request->getPost('doctor_id') ?: 0),
            'visit_date' => date('Y-m-d H:i:s'),
            'treatment_plan' => $note,
            'branch_id' => 1,
        ];
        $model = new \App\Models\MedicalRecordModel();
        $model->insert($data);
        return redirect()->to(site_url('dashboard/nurse'))->with('success', 'Note saved.');
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
        
        $samples = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->where('lab_tests.status', 'requested')
            ->orderBy('lab_tests.requested_date', 'ASC')
            ->findAll(50);

        return view('nurse/lab_samples', ['samples' => $samples]);
    }

    public function collectSample($testId)
    {
        helper(['url', 'form']);
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $test = $labTestModel->find($testId);
        if (!$test) {
            return redirect()->to(site_url('nurse/lab-samples'))->with('error', 'Test not found.');
        }

        $labTestModel->update($testId, [
            'status' => 'sample_collected',
            'sample_collected_date' => date('Y-m-d H:i:s'),
            'lab_technician_id' => session('user_id') ?: 0,
        ]);

        return redirect()->to(site_url('nurse/lab-samples'))->with('success', 'Sample collected successfully.');
    }

    public function treatmentUpdates()
    {
        helper('url');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');
        
        // Get recent treatment updates
        $updates = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.first_name as doctor_first_name, users.last_name as doctor_last_name')
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
}
