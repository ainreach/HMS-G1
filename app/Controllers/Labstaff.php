<?php
namespace App\Controllers;

class Labstaff extends BaseController
{
    public function dashboard()
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        $patientModel = model('App\\Models\\PatientModel');
        $userModel = model('App\\Models\\UserModel');

        $today = date('Y-m-d');
        $labTechId = session('user_id') ?: 1; // Fallback for testing

        // KPIs
        $pendingCount = $labTestModel->where('status', 'requested')->countAllResults();
        $completedCount = $labTestModel->where('status', 'completed')->countAllResults();
        $sampleCollectedCount = $labTestModel->where('status', 'sample_collected')->countAllResults();
        $urgentTests = $labTestModel->where('status !=', 'completed')->where('priority', 'urgent')->countAllResults();

        // Recent pending tests with patient details
        $pendingList = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->join('users', 'users.id = lab_tests.doctor_id')
            ->where('lab_tests.status', 'requested')
            ->orderBy('lab_tests.priority', 'DESC')
            ->orderBy('lab_tests.requested_date', 'ASC')
            ->findAll(10);

        // Recently completed tests
        $recentCompleted = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->join('users', 'users.id = lab_tests.doctor_id')
            ->where('lab_tests.status', 'completed')
            ->orderBy('lab_tests.result_date', 'DESC')
            ->findAll(5);

        // Sample collection queue
        $sampleQueue = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->where('lab_tests.status', 'sample_collected')
            ->orderBy('lab_tests.sample_collected_date', 'ASC')
            ->findAll(10);

        // Test categories summary
        $testCategories = $labTestModel
            ->select('test_category, COUNT(*) as count')
            ->where('status !=', 'completed')
            ->groupBy('test_category')
            ->findAll();

        return view('Lab_staff/dashboard', [
            'pendingCount' => $pendingCount,
            'completedCount' => $completedCount,
            'sampleCollectedCount' => $sampleCollectedCount,
            'urgentTests' => $urgentTests,
            'pendingList' => $pendingList,
            'recentCompleted' => $recentCompleted,
            'sampleQueue' => $sampleQueue,
            'testCategories' => $testCategories,
        ]);
    }

    public function newResult()
    {
        helper('url');
        $pending = model('App\\Models\\LabTestModel')
            ->where('status !=', 'completed')
            ->orderBy('requested_date', 'DESC')
            ->findAll(1000);
        return view('Lab_staff/result_new', ['pendingTests' => $pending]);
    }

    public function storeResult()
    {
        helper(['url','form']);
        $testId = (int) $this->request->getPost('lab_test_id');
        $results = trim((string) $this->request->getPost('results'));
        $interpretation = trim((string) $this->request->getPost('interpretation'));
        $normal = trim((string) $this->request->getPost('normal_range'));
        if (!$testId || $results === '') {
            return redirect()->back()->with('error', 'Lab test and results are required.')->withInput();
        }
        $model = new \App\Models\LabTestModel();
        $row = $model->find($testId);
        if (!$row) {
            return redirect()->back()->with('error', 'Lab test not found.')->withInput();
        }
        $model->update($testId, [
            'results' => $results,
            'interpretation' => $interpretation ?: null,
            'normal_range' => $normal ?: null,
            'status' => 'completed',
            'result_date' => date('Y-m-d H:i:s'),
            'lab_technician_id' => (int) (session('user_id') ?: 0),
        ]);
        return redirect()->to(site_url('dashboard/lab'))
            ->with('success', 'Result saved successfully.');
    }

    public function testRequests()
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $requests = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->join('users', 'users.id = lab_tests.doctor_id')
            ->where('lab_tests.status', 'requested')
            ->orderBy('lab_tests.priority', 'DESC')
            ->orderBy('lab_tests.requested_date', 'ASC')
            ->findAll(50);

        return view('Lab_staff/test_requests', ['requests' => $requests]);
    }

    public function collectSample($testId)
    {
        helper(['url', 'form']);
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $test = $labTestModel->find($testId);
        if (!$test) {
            return redirect()->to(site_url('lab/test-requests'))->with('error', 'Test not found.');
        }

        $labTestModel->update($testId, [
            'status' => 'sample_collected',
            'sample_collected_date' => date('Y-m-d H:i:s'),
            'lab_technician_id' => session('user_id') ?: 0,
        ]);

        return redirect()->to(site_url('lab/test-requests'))->with('success', 'Sample collected successfully.');
    }

    public function sampleQueue()
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $samples = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->where('lab_tests.status', 'sample_collected')
            ->orderBy('lab_tests.sample_collected_date', 'ASC')
            ->findAll(50);

        return view('Lab_staff/sample_queue', ['samples' => $samples]);
    }

    public function completedTests()
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $tests = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->join('users', 'users.id = lab_tests.doctor_id')
            ->where('lab_tests.status', 'completed')
            ->orderBy('lab_tests.result_date', 'DESC')
            ->findAll(50);

        return view('Lab_staff/completed_tests', ['tests' => $tests]);
    }

    public function viewTest($id)
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $test = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->join('users', 'users.id = lab_tests.doctor_id')
            ->where('lab_tests.id', $id)
            ->first();

        if (!$test) {
            return redirect()->to(site_url('lab/test-requests'))->with('error', 'Test not found.');
        }

        return view('Lab_staff/test_view', ['test' => $test]);
    }

    public function printReport($id)
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $test = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->join('users', 'users.id = lab_tests.doctor_id')
            ->where('lab_tests.id', $id)
            ->first();

        if (!$test) {
            return redirect()->to(site_url('lab/completed-tests'))->with('error', 'Test not found.');
        }

        return view('Lab_staff/test_report', ['test' => $test]);
    }

    public function testStatistics()
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');
        
        $stats = [
            'todayTests' => $labTestModel->where('DATE(requested_date)', $today)->countAllResults(),
            'monthTests' => $labTestModel->where('DATE_FORMAT(requested_date, "%Y-%m")', $thisMonth)->countAllResults(),
            'completedToday' => $labTestModel->where('DATE(result_date)', $today)->countAllResults(),
            'pendingUrgent' => $labTestModel->where('status !=', 'completed')->where('priority', 'urgent')->countAllResults(),
        ];

        // Test categories breakdown
        $categories = $labTestModel
            ->select('test_category, COUNT(*) as count')
            ->where('status !=', 'completed')
            ->groupBy('test_category')
            ->findAll();

        return view('Lab_staff/statistics', [
            'stats' => $stats,
            'categories' => $categories,
        ]);
    }
}
