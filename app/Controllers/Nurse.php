<?php
namespace App\Controllers;

class Nurse extends BaseController
{
    public function dashboard()
    {
        helper('url');
        $patientsModel = model('App\\Models\\PatientModel');
        $apptModel = model('App\\Models\\AppointmentModel');

        // KPIs
        $activePatients = $patientsModel->where('is_active', 1)->countAllResults();
        $today = date('Y-m-d');
        $pendingTasks = $apptModel
            ->where('DATE(appointment_date)', $today)
            ->where('status', 'scheduled')
            ->countAllResults();

        // Upcoming ward tasks = upcoming appointments
        $upcoming = $apptModel
            ->select('id,patient_id,appointment_date,appointment_time,status,reason')
            ->where('DATE(appointment_date) >=', $today)
            ->orderBy('appointment_date','ASC')
            ->orderBy('appointment_time','ASC')
            ->findAll(10);

        // Map patient names
        $patientIds = array_values(array_unique(array_map(fn($a)=> (int)$a['patient_id'], $upcoming)));
        $patients = [];
        if ($patientIds) {
            $rows = $patientsModel->select('id,first_name,last_name')->whereIn('id', $patientIds)->findAll();
            foreach ($rows as $r) { $patients[(int)$r['id']] = trim($r['first_name'].' '.$r['last_name']); }
        }
        $tasks = array_map(function($a) use ($patients) {
            return [
                'patient' => $patients[(int)$a['patient_id']] ?? ('#'.$a['patient_id']),
                'task' => $a['reason'] ?: 'Check-up',
                'due_date' => $a['appointment_date'],
                'due_time' => $a['appointment_time'],
                'status' => ucfirst($a['status'] ?: 'scheduled'),
            ];
        }, $upcoming);

        return view('nurse/dashboard', [
            'activePatients' => $activePatients,
            'pendingTasks' => $pendingTasks,
            'tasks' => $tasks,
        ]);
    }

    public function newVitals()
    {
        helper('url');
        return view('nurse/vitals_new');
    }

    public function storeVitals()
    {
        helper(['url','form']);
        $patientId = (int) $this->request->getPost('patient_id');
        $vitals    = $this->request->getPost('vitals');
        if (!$patientId || $vitals === null || $vitals === '') {
            return redirect()->back()->with('error', 'Patient and vital signs are required.')->withInput();
        }
        $data = [
            'record_number' => 'NV-' . date('YmdHis'),
            'patient_id' => $patientId,
            'appointment_id' => $this->request->getPost('appointment_id') ?: null,
            'doctor_id' => (int) ($this->request->getPost('doctor_id') ?: 0),
            'visit_date' => date('Y-m-d H:i:s'),
            'vital_signs' => $vitals,
            'branch_id' => 1,
        ];
        $model = new \App\Models\MedicalRecordModel();
        $model->insert($data);
        return redirect()->to(site_url('dashboard/nurse'))->with('success', 'Vitals recorded.');
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
}
