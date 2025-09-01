<?php
namespace App\Controllers;

class Reception extends BaseController
{
    public function dashboard()
    {
        helper('url');
        $apptModel = model('App\\Models\\AppointmentModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');

        $today = date('Y-m-d');
        // KPIs
        $checkinsToday = $apptModel
            ->where('DATE(appointment_date)', $today)
            ->where('status', 'checked-in')
            ->countAllResults();
        $upcomingAppts = $apptModel
            ->where('DATE(appointment_date)', $today)
            ->where('status', 'scheduled')
            ->countAllResults();

        // Today's appointments list
        $todayAppts = $apptModel
            ->select('id,patient_id,doctor_id,appointment_time,status')
            ->where('DATE(appointment_date)', $today)
            ->orderBy('appointment_time', 'ASC')
            ->findAll(20);

        // Map names
        $pids = array_values(array_unique(array_map(fn($a)=>(int)$a['patient_id'], $todayAppts)));
        $dids = array_values(array_unique(array_map(fn($a)=>(int)$a['doctor_id'], $todayAppts)));
        $patientNames = [];
        if ($pids) {
            $rows = $patientModel->select('id,first_name,last_name')->whereIn('id', $pids)->findAll();
            foreach ($rows as $r) { $patientNames[(int)$r['id']] = trim($r['first_name'].' '.$r['last_name']); }
        }
        $doctorNames = [];
        if ($dids) {
            $rows = $userModel->select('id,first_name,last_name')->whereIn('id', $dids)->findAll();
            foreach ($rows as $r) { $doctorNames[(int)$r['id']] = 'Dr. '.trim($r['last_name'] ?: ($r['first_name'] ?? '')); }
        }
        $appointments = array_map(function($a) use ($patientNames, $doctorNames) {
            return [
                'patient' => $patientNames[(int)$a['patient_id']] ?? ('#'.$a['patient_id']),
                'doctor' => $doctorNames[(int)$a['doctor_id']] ?? ('#'.$a['doctor_id']),
                'time' => $a['appointment_time'],
                'status' => ucfirst($a['status'] ?? 'scheduled'),
            ];
        }, $todayAppts);

        return view('Reception/dashboard', [
            'checkinsToday' => $checkinsToday,
            'upcomingAppts' => $upcomingAppts,
            'appointments' => $appointments,
        ]);
    }

    public function newPatient()
    {
        helper('url');
        return view('Reception/patient_new');
    }

    public function storePatient()
    {
        helper(['url', 'form']);
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
        return redirect()->to(site_url('dashboard/receptionist'))->with('success', 'Patient registered.');
    }

    public function newAppointment()
    {
        helper('url');
        return view('Reception/appointment_new');
    }

    public function storeAppointment()
    {
        helper(['url', 'form']);
        $appointments = new \App\Models\AppointmentModel();

        $patientId = (int) $this->request->getPost('patient_id');
        $doctorId  = (int) $this->request->getPost('doctor_id');
        $date      = (string) $this->request->getPost('appointment_date');
        $time      = (string) $this->request->getPost('appointment_time');

        if (!$patientId || !$doctorId || $date === '' || $time === '') {
            return redirect()->back()->with('error', 'Patient, doctor, date and time are required.')->withInput();
        }

        $data = [
            'appointment_number' => 'A-' . date('YmdHis'),
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'branch_id' => 1,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'duration' => (int) ($this->request->getPost('duration') ?: 30),
            'type' => $this->request->getPost('type') ?: 'consultation',
            'status' => 'scheduled',
            'reason' => $this->request->getPost('reason') ?: null,
            'notes' => $this->request->getPost('notes') ?: null,
            'created_by' => session('user_id') ?: 0,
        ];

        $appointments->insert($data);
        return redirect()->to(site_url('dashboard/receptionist'))->with('success', 'Appointment booked.');
    }
}
