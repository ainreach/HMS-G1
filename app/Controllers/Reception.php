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
        
        // KPIs - try without DATE first
        $checkinsToday = $apptModel
            ->where('appointment_date', $today)
            ->where('status', 'checked-in')
            ->countAllResults();
            
        if ($checkinsToday == 0) {
            $checkinsToday = $apptModel
                ->where('DATE(appointment_date)', $today)
                ->where('status', 'checked-in')
                ->countAllResults();
        }
        
        $upcomingAppts = $apptModel
            ->where('appointment_date', $today)
            ->where('status', 'scheduled')
            ->countAllResults();
            
        if ($upcomingAppts == 0) {
            $upcomingAppts = $apptModel
                ->where('DATE(appointment_date)', $today)
                ->where('status', 'scheduled')
                ->countAllResults();
        }
        $totalPatients = $patientModel->where('is_active', 1)->countAllResults();
        $newPatientsToday = $patientModel
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        // Today's appointments with full details - try without DATE first
        $todayAppts = $apptModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.phone, users.username as doctor_name')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->join('users', 'users.id = appointments.doctor_id')
            ->where('appointments.appointment_date', $today)
            ->orderBy('appointments.appointment_time', 'ASC')
            ->findAll(20);
            
        // If empty, try with DATE function
        if (empty($todayAppts)) {
            $todayAppts = $apptModel
                ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.phone, users.username as doctor_name')
                ->join('patients', 'patients.id = appointments.patient_id')
                ->join('users', 'users.id = appointments.doctor_id')
                ->where("DATE(appointments.appointment_date)", $today)
                ->orderBy('appointments.appointment_time', 'ASC')
                ->findAll(20);
        }

        // Tomorrow's appointments
        $tomorrowAppts = $apptModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.username as doctor_name')
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
            ->orderBy('username', 'ASC')
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
        
        $data = [
            'patient_id' => 'P-' . date('YmdHis'),
            'first_name' => trim((string) $this->request->getPost('first_name')),
            'last_name'  => trim((string) $this->request->getPost('last_name')),
            'date_of_birth' => $this->request->getPost('date_of_birth') ?: null,
            'gender'    => $this->request->getPost('gender') ?: null,
            'phone'     => trim((string) $this->request->getPost('phone')) ?: null,
            'email'     => trim((string) $this->request->getPost('email')) ?: null,
            'address'   => trim((string) $this->request->getPost('address')) ?: null,
            'branch_id' => $branchId,
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
        
        // Get ALL rooms from all branches
        $allRooms = $roomModel
            ->select('rooms.*, branches.name as branch_name')
            ->join('branches', 'branches.id = rooms.branch_id')
            ->orderBy('branches.name', 'ASC')
            ->orderBy('rooms.floor', 'ASC')
            ->orderBy('rooms.room_number', 'ASC')
            ->findAll(50, 0);
            
        // Get stats for all rooms
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
        
        return view('Reception/rooms', [
            'rooms' => $allRooms,
            'stats' => $stats
        ]);
    }

    public function newRoom()
    {
        helper('url');
        return view('Reception/room_form');
    }

    public function editRoom($id = null)
    {
        helper('url');
        $roomModel = model('App\\Models\\RoomModel');
        
        $room = $roomModel->find($id);
        if (!$room) {
            return redirect()->to('/reception/rooms')->with('error', 'Room not found.');
        }
        
        return view('Reception/room_form', [
            'room' => $room
        ]);
    }

    public function storeRoom()
    {
        helper(['url', 'form']);
        $roomModel = model('App\\Models\\RoomModel');
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
        
        $id = $this->request->getPost('id');
        
        $data = [
            'branch_id' => $branchId,
            'room_number' => trim($this->request->getPost('room_number')),
            'room_type' => $this->request->getPost('room_type'),
            'floor' => (int) $this->request->getPost('floor'),
            'capacity' => (int) $this->request->getPost('capacity'),
            'current_occupancy' => 0,
            'rate_per_day' => (float) ($this->request->getPost('rate_per_day') ?: 0.00),
            'status' => $this->request->getPost('status') ?? 'available',
            'is_active' => 1,
        ];
        
        // Validation
        if (!$data['room_number'] || !$data['room_type'] || $data['floor'] < 1 || $data['capacity'] < 1) {
            return redirect()->back()->with('error', 'Please fill all required fields correctly.')->withInput();
        }
        
        // Check if room number already exists (including soft-deleted)
        if (!$id) {
            $existingRoom = $roomModel
                ->where('room_number', $data['room_number'])
                ->where('branch_id', $branchId)
                ->first();
            
            if ($existingRoom) {
                return redirect()->back()->with('error', 'Room number "' . esc($data['room_number']) . '" already exists for this branch. Please use a different room number.')->withInput();
            }
        } else {
            // For updates, check if room number conflicts with another room
            $existingRoom = $roomModel
                ->where('room_number', $data['room_number'])
                ->where('branch_id', $branchId)
                ->where('id !=', $id)
                ->first();
            
            if ($existingRoom) {
                return redirect()->back()->with('error', 'Room number "' . esc($data['room_number']) . '" is already used by another room. Please use a different room number.')->withInput();
            }
        }
        
        try {
            $db = \Config\Database::connect();
            
            if ($id) {
                // Update existing room
                $data['id'] = $id;
                $result = $roomModel->save($data);
                if (!$result) {
                    $errors = $roomModel->errors();
                    throw new \Exception('Failed to update room: ' . implode(', ', $errors));
                }
                $roomId = $id;
                $message = 'Room updated successfully.';
            } else {
                // Start transaction for new room
                $db->transStart();
                
                // Create new room using model insert
                try {
                    $result = $roomModel->insert($data);
                } catch (\Exception $insertException) {
                    $db->transRollback();
                    $errorMsg = $insertException->getMessage();
                    if (strpos($errorMsg, 'unique') !== false || strpos($errorMsg, 'Duplicate') !== false) {
                        throw new \Exception('Room number "' . esc($data['room_number']) . '" already exists. Please use a different room number.');
                    }
                    throw new \Exception('Failed to insert room: ' . $errorMsg);
                }
                
                if (!$result) {
                    $errors = $roomModel->errors();
                    $db->transRollback();
                    $errorMsg = implode(', ', $errors);
                    if (strpos($errorMsg, 'unique') !== false || strpos($errorMsg, 'must contain a unique') !== false) {
                        throw new \Exception('Room number "' . esc($data['room_number']) . '" already exists. Please use a different room number.');
                    }
                    throw new \Exception('Failed to insert room: ' . $errorMsg);
                }
                
                $roomId = $roomModel->getInsertID();
                
                // If getInsertID() doesn't work, try database insertID
                if (!$roomId || $roomId == 0) {
                    $roomId = $db->insertID();
                }
                
                // If still no ID, try to get it from the last inserted room
                if (!$roomId || $roomId == 0) {
                    $lastRoom = $roomModel
                        ->where('room_number', $data['room_number'])
                        ->where('branch_id', $branchId)
                        ->orderBy('id', 'DESC')
                        ->first();
                    if ($lastRoom) {
                        $roomId = $lastRoom['id'];
                    }
                }
                
                if (!$roomId || $roomId == 0) {
                    $db->transRollback();
                    throw new \Exception('Failed to get room ID after insertion. Room may not have been created.');
                }
                
                // Create beds if beds_data is provided
                $bedsData = $this->request->getPost('beds_data');
                $bedCount = 0;
                if ($bedsData && !empty(trim($bedsData))) {
                    $beds = json_decode($bedsData, true);
                    if (is_array($beds) && !empty($beds)) {
                        $bedModel = model('App\\Models\\BedModel');
                        foreach ($beds as $bed) {
                            if (!empty($bed['bed_number']) && !empty($bed['bed_type'])) {
                                $bedResult = $bedModel->insert([
                                    'room_id' => $roomId,
                                    'bed_number' => trim($bed['bed_number']),
                                    'bed_type' => $bed['bed_type'],
                                    'status' => 'available',
                                    'is_active' => 1,
                                ]);
                                
                                if (!$bedResult) {
                                    $bedErrors = $bedModel->errors();
                                    $db->transRollback();
                                    throw new \Exception('Failed to create bed: ' . implode(', ', $bedErrors));
                                }
                                $bedCount++;
                            }
                        }
                    }
                }
                
                // Complete transaction
                $db->transComplete();
                
                if ($db->transStatus() === false) {
                    $error = $db->error();
                    $errorMsg = 'Transaction failed. ';
                    if (isset($error['message'])) {
                        $errorMsg .= $error['message'];
                    } elseif (isset($error['code'])) {
                        $errorMsg .= 'Error code: ' . $error['code'];
                    } else {
                        $errorMsg .= 'Unknown database error.';
                    }
                    throw new \Exception($errorMsg);
                }
                
                $message = 'Room added successfully' . ($bedCount > 0 ? ' with ' . $bedCount . ' bed(s)' : '') . '.';
            }
            
            return redirect()->to('/reception/rooms')->with('success', $message);
        } catch (\Exception $e) {
            log_message('error', 'Error saving room: ' . $e->getMessage());
            log_message('error', 'Room data: ' . json_encode($data));
            if (isset($db) && $db->transStatus() !== false) {
                $db->transRollback();
            }
            return redirect()->back()->with('error', 'Error saving room: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteRoom($id = null)
    {
        $roomModel = model('App\\Models\\RoomModel');
        $room = $roomModel->find($id);
        
        if (!$room) {
            return redirect()->to('/reception/rooms')->with('error', 'Room not found.');
        }
        
        // Check if room is occupied
        if ($room['current_occupancy'] > 0) {
            return redirect()->to('/reception/rooms')->with('error', 'Cannot delete room. Room is currently occupied.');
        }
        
        try {
            $roomModel->delete($id);
            return redirect()->to('/reception/rooms')->with('success', 'Room deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->to('/reception/rooms')->with('error', 'Error deleting room: ' . $e->getMessage());
        }
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
        $apptModel    = model('App\\Models\\AppointmentModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel    = model('App\\Models\\UserModel');
        
        $appointments = $apptModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.phone, users.username as doctor_name')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->join('users', 'users.id = appointments.doctor_id')
            ->orderBy('appointments.appointment_date', 'DESC')
            ->orderBy('appointments.appointment_time', 'ASC')
            ->findAll(50);

        $patients = $patientModel->where('is_active', 1)->orderBy('last_name', 'ASC')->findAll(100);
        $doctors  = $userModel->where('role', 'doctor')->where('is_active', 1)->orderBy('first_name', 'ASC')->findAll(50);

        return view('Reception/appointments', [
            'appointments' => $appointments,
            'patients'     => $patients,
            'doctors'      => $doctors,
        ]);
    }

    public function viewAppointment($id)
    {
        helper('url');
        $apptModel = model('App\\Models\\AppointmentModel');
        
        $appointment = $apptModel
            ->select('appointments.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.phone, patients.email, users.username as doctor_name')
            ->join('patients', 'patients.id = appointments.patient_id')
            ->join('users', 'users.id = appointments.doctor_id')
            ->where('appointments.id', $id)
            ->first();

        if (!$appointment) {
            return redirect()->to(site_url('reception/appointments'))->with('error', 'Appointment not found.');
        }

        return view('Reception/appointment_view', ['appointment' => $appointment]);
    }

    public function roomAdmission()
    {
        helper('url');
        $roomModel = model('App\\Models\\RoomModel');
        $patientModel = model('App\\Models\\PatientModel');
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
        
        // Get patients for this branch
        $patients = $patientModel
            ->where('branch_id', $branchId)
            ->where('is_active', 1)
            ->orderBy('last_name', 'ASC')
            ->findAll(100);
        
        // Get all rooms and available rooms for this branch
        $allRooms = $roomModel->getAllRooms($branchId, 100, 0);
        $availableRooms = array_filter($allRooms, function($room) {
            return $room['status'] === 'available' && $room['current_occupancy'] < $room['capacity'];
        });
        
        return view('Reception/room_admission', [
            'patients' => $patients,
            'availableRooms' => $availableRooms,
            'allRooms' => $allRooms
        ]);
    }

    public function admitToRoom()
    {
        helper(['url', 'form']);
        $roomModel = model('App\\Models\\RoomModel');
        $patientModel = model('App\\Models\\PatientModel');
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
        
        $patientId = (int) $this->request->getPost('patient_id');
        $roomId = (int) $this->request->getPost('room_id');
        $admissionDate = $this->request->getPost('admission_date') ?: date('Y-m-d');
        
        if (!$patientId || !$roomId) {
            return redirect()->back()->with('error', 'Patient and room are required.')->withInput();
        }
        
        // Validate patient exists
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found.')->withInput();
        }
        
        // Validate room exists and is available
        $room = $roomModel->find($roomId);
        if (!$room) {
            return redirect()->back()->with('error', 'Room not found.')->withInput();
        }
        
        if ($room['status'] !== 'available' || $room['current_occupancy'] >= $room['capacity']) {
            return redirect()->back()->with('error', 'Room is not available.')->withInput();
        }
        
        // Update room occupancy
        $newOccupancy = $room['current_occupancy'] + 1;
        $roomModel->update($roomId, [
            'current_occupancy' => $newOccupancy,
            'status' => $newOccupancy >= $room['capacity'] ? 'occupied' : 'available'
        ]);
        
        // Update patient with room assignment and admission date
        $patientModel->update($patientId, [
            'assigned_room_id' => $roomId,
            'admission_date' => $admissionDate,
            'admission_type' => 'admission',
            'branch_id' => $branchId
        ]);
        
        return redirect()->to(site_url('reception/rooms'))->with('success', 'Patient admitted to room successfully.');
    }

    public function dischargeFromRoom($patientId)
    {
        helper(['url', 'form']);
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        
        $patientId = (int) $patientId;
        
        // Get patient details
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('reception/rooms'))->with('error', 'Patient not found.');
        }
        
        if (!$patient['assigned_room_id']) {
            return redirect()->to(site_url('reception/rooms'))->with('error', 'Patient is not assigned to any room.');
        }
        
        // Get room details
        $room = $roomModel->find($patient['assigned_room_id']);
        if (!$room) {
            return redirect()->to(site_url('reception/rooms'))->with('error', 'Room not found.');
        }
        
        // Update room occupancy
        $newOccupancy = $room['current_occupancy'] - 1;
        $roomModel->update($room['id'], [
            'current_occupancy' => $newOccupancy,
            'status' => $newOccupancy <= 0 ? 'available' : 'occupied'
        ]);
        
        // Update patient - remove room assignment
        $patientModel->update($patientId, [
            'assigned_room_id' => null,
            'admission_date' => null,
            'admission_type' => null
        ]);
        
        return redirect()->to(site_url('reception/rooms'))->with('success', 'Patient discharged successfully. Room is now available.');
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
        
        $patients = $patientModel
            ->where('branch_id', $branchId)
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

    public function inPatients()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $bedModel = model('App\\Models\\BedModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $userModel = model('App\\Models\\UserModel');
        
        // Get all in-patients (admission_type = 'admission' and has admission_date)
        $db = \Config\Database::connect();
        $inPatients = $db->query("
            SELECT 
                p.*,
                r.room_number,
                r.room_type,
                r.floor,
                r.rate_per_day,
                b.bed_number,
                b.bed_type as bed_type_name,
                u.username as attending_physician_name,
                u.first_name as doctor_first_name,
                u.last_name as doctor_last_name,
                (SELECT COUNT(*) FROM medical_records mr WHERE mr.patient_id = p.id) as total_consultations,
                (SELECT visit_date FROM medical_records WHERE patient_id = p.id ORDER BY visit_date DESC LIMIT 1) as last_consultation
            FROM patients p
            LEFT JOIN rooms r ON r.id = p.assigned_room_id
            LEFT JOIN beds b ON b.id = p.assigned_bed_id
            LEFT JOIN users u ON u.id = p.attending_physician_id
            WHERE p.is_active = 1
            AND p.admission_type = 'admission'
            AND p.admission_date IS NOT NULL
            AND p.admission_date != ''
            ORDER BY p.admission_date DESC
        ")->getResultArray();
        
        return view('Reception/in_patients', [
            'inPatients' => $inPatients
        ]);
    }

    public function viewInPatient($patientId)
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $bedModel = model('App\\Models\\BedModel');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        $userModel = model('App\\Models\\UserModel');
        $labTestModel = model('App\\Models\\LabTestModel');
        $prescriptionModel = model('App\\Models\\PrescriptionModel');
        
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('reception/in-patients'))->with('error', 'Patient not found.');
        }
        
        // Check if patient is an in-patient
        if ($patient['admission_type'] !== 'admission' || empty($patient['admission_date'])) {
            return redirect()->to(site_url('reception/in-patients'))->with('error', 'This patient is not an in-patient.');
        }
        
        // Get room details
        $room = null;
        if ($patient['assigned_room_id']) {
            $room = $roomModel->find($patient['assigned_room_id']);
        }
        
        // Get bed details
        $bed = null;
        if ($patient['assigned_bed_id']) {
            $bed = $bedModel->find($patient['assigned_bed_id']);
        }
        
        // Get attending physician
        $attendingPhysician = null;
        if ($patient['attending_physician_id']) {
            $attendingPhysician = $userModel->find($patient['attending_physician_id']);
        }
        
        // Get medical records
        $medicalRecords = $medicalRecordModel
            ->where('patient_id', $patientId)
            ->orderBy('visit_date', 'DESC')
            ->findAll(10);
        
        // Get lab tests
        $labTests = $labTestModel
            ->where('patient_id', $patientId)
            ->orderBy('created_at', 'DESC')
            ->findAll(10);
        
        // Get prescriptions
        $prescriptions = $prescriptionModel
            ->where('patient_id', $patientId)
            ->orderBy('prescription_date', 'DESC')
            ->findAll(10);
        
        return view('Reception/in_patient_details', [
            'patient' => $patient,
            'room' => $room,
            'bed' => $bed,
            'attendingPhysician' => $attendingPhysician,
            'medicalRecords' => $medicalRecords,
            'labTests' => $labTests,
            'prescriptions' => $prescriptions,
        ]);
    }
}
