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
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        
        // KPIs
        $checkinsToday = $apptModel
            ->where('DATE(appointment_date)', $today)
            ->where('status', 'checked-in')
            ->countAllResults();
        $upcomingAppts = $apptModel
            ->where('DATE(appointment_date)', $today)
            ->where('status', 'scheduled')
            ->countAllResults();
        $totalPatients = $patientModel->where('is_active', 1)->countAllResults();
        $newPatientsToday = $patientModel
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        // Today's appointments with full details
        $todayAppts = $apptModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.phone, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->join('users', 'users.id = appointments.doctor_id')
            ->where('DATE(appointments.appointment_date)', $today)
            ->orderBy('appointments.appointment_time', 'ASC')
            ->findAll(20);

        // Tomorrow's appointments
        $tomorrowAppts = $apptModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->join('users', 'users.id = appointments.doctor_id')
            ->where('DATE(appointments.appointment_date)', $tomorrow)
            ->orderBy('appointments.appointment_time', 'ASC')
            ->findAll(10);

        // Recent patient registrations
        $recentPatients = $patientModel
            ->where('is_active', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll(5);

        // Available doctors for booking
        $doctors = $userModel
            ->where('role', 'doctor')
            ->where('is_active', 1)
            ->orderBy('first_name', 'ASC')
            ->findAll(20);

        return view('Reception/dashboard', [
            'checkinsToday' => $checkinsToday,
            'upcomingAppts' => $upcomingAppts,
            'totalPatients' => $totalPatients,
            'newPatientsToday' => $newPatientsToday,
            'appointments' => $todayAppts,
            'tomorrowAppts' => $tomorrowAppts,
            'recentPatients' => $recentPatients,
            'doctors' => $doctors,
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

        // Simplified patient registration - admission fields not implemented in current schema

        if ($data['first_name'] === '' || $data['last_name'] === '') {
            return redirect()->back()->with('error', 'First and last name are required.')->withInput();
        }

        // Admission validation removed - not implemented in current schema

        $patients = new \App\Models\PatientModel();
        $patients->insert($data);
        
        $message = 'Patient registered successfully.';
        return redirect()->to(site_url('dashboard/receptionist'))->with('success', $message);
    }

    public function rooms()
    {
        helper('url');
        $roomModel = model('App\\Models\\RoomModel');
        
        $rooms = $roomModel->getAllRooms(1, 50, 0);
        $stats = $roomModel->getRoomStats(1);
        
        return view('Reception/rooms', [
            'rooms' => $rooms,
            'stats' => $stats
        ]);
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

    public function appointments()
    {
        helper('url');
        $apptModel = model('App\\Models\\AppointmentModel');
        
        $appointments = $apptModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.phone, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->join('users', 'users.id = appointments.doctor_id')
            ->orderBy('appointments.appointment_date', 'DESC')
            ->orderBy('appointments.appointment_time', 'ASC')
            ->findAll(50);

        return view('Reception/appointments', ['appointments' => $appointments]);
    }

    public function viewAppointment($id)
    {
        helper('url');
        $apptModel = model('App\\Models\\AppointmentModel');
        
        $appointment = $apptModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.phone, patients.email, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->join('users', 'users.id = appointments.doctor_id')
            ->where('appointments.id', $id)
            ->first();

        if (!$appointment) {
            return redirect()->to(site_url('reception/appointments'))->with('error', 'Appointment not found.');
        }

        return view('Reception/appointment_view', ['appointment' => $appointment]);
    }

    public function checkIn($id)
    {
        helper('url');
        $apptModel = model('App\\Models\\AppointmentModel');
        
        $appointment = $apptModel->find($id);
        if (!$appointment) {
            return redirect()->to(site_url('reception/appointments'))->with('error', 'Appointment not found.');
        }

        $apptModel->update($id, [
            'status' => 'checked-in',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('reception/appointments'))->with('success', 'Patient checked in successfully.');
    }

    public function cancelAppointment($id)
    {
        helper('url');
        $apptModel = model('App\\Models\\AppointmentModel');
        
        $appointment = $apptModel->find($id);
        if (!$appointment) {
            return redirect()->to(site_url('reception/appointments'))->with('error', 'Appointment not found.');
        }

        $apptModel->update($id, [
            'status' => 'cancelled',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('reception/appointments'))->with('success', 'Appointment cancelled successfully.');
    }

    public function viewPatient($id)
    {
        helper(['url']);
        $id = (int) $id;
        $patient = model('App\Models\PatientModel')->find($id);
        if (!$patient) {
            return redirect()->to(site_url('reception/patients'))->with('error', 'Patient not found.');
        }

        return view('admin/patient_view', ['patient' => $patient]);
    }

    public function patients()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        
        $patients = $patientModel
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->findAll(100);

        return view('Reception/patients', ['patients' => $patients]);
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
                ->orLike('phone', $searchTerm)
            ->groupEnd()
            ->orderBy('last_name', 'ASC')
            ->findAll(20);

        return $this->response->setJSON($patients);
    }

    public function patientLookup()
    {
        helper('url');
        return view('Reception/patient_lookup');
    }

    public function editAppointment($id)
    {
        helper('url');
        $apptModel = model('App\\Models\\AppointmentModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');
        
        $appointment = $apptModel->find($id);
        if (!$appointment) {
            return redirect()->to(site_url('reception/appointments'))->with('error', 'Appointment not found.');
        }

        $patients = $patientModel->where('is_active', 1)->orderBy('last_name', 'ASC')->findAll(100);
        $doctors = $userModel->where('role', 'doctor')->where('is_active', 1)->orderBy('first_name', 'ASC')->findAll(20);

        return view('Reception/appointment_edit', [
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
            return redirect()->to(site_url('reception/appointments'))->with('error', 'Appointment not found.');
        }

        $data = [
            'patient_id' => (int) $this->request->getPost('patient_id'),
            'doctor_id' => (int) $this->request->getPost('doctor_id'),
            'appointment_date' => $this->request->getPost('appointment_date'),
            'appointment_time' => $this->request->getPost('appointment_time'),
            'duration' => (int) ($this->request->getPost('duration') ?: 30),
            'type' => $this->request->getPost('type') ?: 'consultation',
            'reason' => $this->request->getPost('reason') ?: null,
            'notes' => $this->request->getPost('notes') ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $apptModel->update($id, $data);
        return redirect()->to(site_url('reception/appointments'))->with('success', 'Appointment updated successfully.');
    }
}
