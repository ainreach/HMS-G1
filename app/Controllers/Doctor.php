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
        return view('doctor/dashboard', $data);
    }

    public function patient_records()
    {
        $patientModel = new \App\Models\PatientModel();
        $data['patients'] = $patientModel->findAll();
        return view('doctor/patient_records', $data);
    }

    public function view_patient_record($patient_id)
    {
        $patientModel = new \App\Models\PatientModel();
        $medicalRecordModel = new \App\Models\MedicalRecordModel();

        $data['patient'] = $patientModel->find($patient_id);
        $data['records'] = $medicalRecordModel->where('patient_id', $patient_id)->findAll();

        return view('doctor/view_patient_record', $data);
    }

    public function create_prescription($patient_id)
    {
        $data['patient_id'] = $patient_id;
        return view('doctor/create_prescription', $data);
    }

    public function store_prescription()
    {
        $prescriptionModel = new \App\Models\PrescriptionModel();

        $data = [
            'patient_id' => $this->request->getPost('patient_id'),
            'doctor_id' => session('user_id'),
            'medication' => $this->request->getPost('medication'),
            'dosage' => $this->request->getPost('dosage'),
            'frequency' => $this->request->getPost('frequency'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
        ];

        $prescriptionModel->save($data);

        return redirect()->to('/doctor/patient_records');
    }

    public function request_test($patient_id)
    {
        $data['patient_id'] = $patient_id;
        return view('doctor/request_test', $data);
    }

    public function store_test_request()
    {
        $labTestModel = new \App\Models\LabTestModel();

        $data = [
            'patient_id' => $this->request->getPost('patient_id'),
            'doctor_id' => session('user_id'),
            'test_name' => $this->request->getPost('test_name'),
            'test_date' => $this->request->getPost('test_date'),
        ];

        $labTestModel->save($data);

        return redirect()->to('/doctor/patient_records');
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
        return view('doctor/lab_request_new');
    }

    public function storeLabRequest()
    {
        helper(['url','form']);
        $patientId = (int) $this->request->getPost('patient_id');
        $doctorId  = session('user_id') ?: (int) $this->request->getPost('doctor_id');
        $testName  = trim((string) $this->request->getPost('test_name'));
        $category  = (string) $this->request->getPost('test_category');
        if (!$patientId || $testName === '' || $category === '') {
            return redirect()->back()->with('error', 'Patient, test name and category are required.')->withInput();
        }
        $data = [
            'test_number' => 'LT-' . date('YmdHis'),
            'patient_id' => $patientId,
            'doctor_id' => (int) $doctorId,
            'test_type' => $this->request->getPost('test_type') ?: 'ordered',
            'test_name' => $testName,
            'test_category' => $category,
            'requested_date' => date('Y-m-d H:i:s'),
            'status' => 'requested',
            'branch_id' => 1,
            'priority' => $this->request->getPost('priority') ?: 'routine',
            'notes' => $this->request->getPost('notes') ?: null,
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
        $data = [
            'patient_id' => 'P-' . date('YmdHis'),
            'first_name' => trim((string) $this->request->getPost('first_name')),
            'last_name'  => trim((string) $this->request->getPost('last_name')),
            'date_of_birth' => $this->request->getPost('date_of_birth') ?: null,
            'gender'    => $this->request->getPost('gender') ?: null,
            'phone'     => trim((string) $this->request->getPost('phone')) ?: null,
            'email'     => trim((string) $this->request->getPost('email')) ?: null,
            'address'   => trim((string) $this->request->getPost('address')) ?: null,
            'branch_id' => 1,
            'is_active' => 1,
        ];

        if ($data['first_name'] === '' || $data['last_name'] === '') {
            return redirect()->back()->with('error', 'First and last name are required.')->withInput();
        }

        $patients = new \App\Models\PatientModel();
        $patients->insert($data);
        return redirect()->to(site_url('dashboard/doctor'))->with('success', 'Patient registered.');
    }
}
