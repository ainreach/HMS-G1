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
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender, users.username as doctor_name')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->join('users', 'users.id = lab_tests.doctor_id')
            ->where('lab_tests.status', 'requested')
            ->orderBy('lab_tests.priority', 'DESC')
            ->orderBy('lab_tests.requested_date', 'ASC')
            ->findAll(10);

        // Recently completed tests
        $recentCompleted = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.username as doctor_name')
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
        $labTestModel = model('App\\Models\\LabTestModel');
        $testId = (int) $this->request->getGet('test_id');
        
        // Get pending tests that are ready for results (sample_collected or in_progress)
        $pending = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->where('lab_tests.accountant_approved', 1)
            ->whereIn('lab_tests.status', ['sample_collected', 'in_progress', 'requested'])
            ->orderBy('lab_tests.priority', 'DESC')
            ->orderBy('lab_tests.requested_date', 'ASC')
            ->findAll(100);
        
        // Get selected test details if test_id is provided
        $selectedTest = null;
        if ($testId > 0) {
            $selectedTest = $labTestModel
                ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender')
                ->join('patients', 'patients.id = lab_tests.patient_id')
                ->where('lab_tests.id', $testId)
                ->where('lab_tests.accountant_approved', 1)
                ->whereIn('lab_tests.status', ['sample_collected', 'in_progress', 'requested'])
                ->first();
        }
        
        return view('Lab_staff/result_new', [
            'pendingTests' => $pending,
            'selectedTest' => $selectedTest,
            'testId' => $testId
        ]);
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
        
        // Convert results to JSON format if column is JSON type
        // Try to decode first to see if it's already JSON
        $resultsJson = $results;
        $decoded = json_decode($results, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Not valid JSON, wrap it in a JSON object
            $resultsJson = json_encode(['text' => $results], JSON_UNESCAPED_UNICODE);
        } else {
            // Already valid JSON, ensure it's properly encoded
            $resultsJson = json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }
        
        $model->update($testId, [
            'results' => $resultsJson,
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
        
        // Only show approved tests
        // For tests with specimen: status should be 'sample_collected'
        // For tests without specimen: status should be 'requested' (direct to lab)
        $requests = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender, users.username as doctor_name, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = lab_tests.patient_id')
            ->join('users', 'users.id = lab_tests.doctor_id', 'left')
            ->where('lab_tests.accountant_approved', 1)
            ->groupStart()
                ->where('lab_tests.requires_specimen', 1)
                ->where('lab_tests.status', 'sample_collected')
                ->orGroupStart()
                    ->where('lab_tests.requires_specimen', 0)
                    ->where('lab_tests.status', 'requested')
                ->groupEnd()
            ->groupEnd()
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
        $db = \Config\Database::connect();
        
        // Get samples that are collected and ready for processing
        $samples = $db->query("
            SELECT 
                lt.*,
                p.first_name,
                p.last_name,
                p.patient_id as patient_code
            FROM lab_tests lt
            INNER JOIN patients p ON p.id = lt.patient_id
            WHERE lt.status = 'sample_collected'
            AND lt.accountant_approved = 1
            AND (lt.deleted_at IS NULL OR lt.deleted_at = '')
            ORDER BY 
                CASE WHEN lt.priority = 'urgent' THEN 0 
                     WHEN lt.priority = 'stat' THEN 1 
                     ELSE 2 END,
                lt.sample_collected_date ASC
            LIMIT 50
        ")->getResultArray();

        return view('Lab_staff/sample_queue', ['samples' => $samples]);
    }

    public function completedTests()
    {
        helper('url');
        $db = \Config\Database::connect();
        
        // Get completed tests with patient and doctor info
        $tests = $db->query("
            SELECT 
                lt.*,
                p.first_name,
                p.last_name,
                p.patient_id as patient_code,
                COALESCE(u.username, CONCAT(u.first_name, ' ', u.last_name), 'N/A') as doctor_name
            FROM lab_tests lt
            INNER JOIN patients p ON p.id = lt.patient_id
            LEFT JOIN users u ON u.id = lt.doctor_id
            WHERE lt.status = 'completed'
            AND (lt.deleted_at IS NULL OR lt.deleted_at = '')
            ORDER BY lt.result_date DESC, lt.updated_at DESC
            LIMIT 50
        ")->getResultArray();

        return view('Lab_staff/completed_tests', ['tests' => $tests]);
    }

    public function viewTest($id)
    {
        helper('url');
        $labTestModel = model('App\\Models\\LabTestModel');
        
        $test = $labTestModel
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender, users.username as doctor_name')
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
            ->select('lab_tests.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, patients.date_of_birth, patients.gender, users.username as doctor_name')
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
        $db = \Config\Database::connect();
        
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');
        
        // Get statistics using raw queries to handle soft deletes properly
        $totalTests = $db->query("
            SELECT COUNT(*) as count 
            FROM lab_tests 
            WHERE (deleted_at IS NULL OR deleted_at = '')
        ")->getRowArray()['count'] ?? 0;
        
        $completedToday = $db->query("
            SELECT COUNT(*) as count 
            FROM lab_tests 
            WHERE status = 'completed' 
            AND DATE(result_date) = ?
            AND (deleted_at IS NULL OR deleted_at = '')
        ", [$today])->getRowArray()['count'] ?? 0;
        
        $pendingTests = $db->query("
            SELECT COUNT(*) as count 
            FROM lab_tests 
            WHERE status != 'completed' 
            AND status != 'cancelled'
            AND (deleted_at IS NULL OR deleted_at = '')
        ")->getRowArray()['count'] ?? 0;
        
        $urgentTests = $db->query("
            SELECT COUNT(*) as count 
            FROM lab_tests 
            WHERE status != 'completed' 
            AND status != 'cancelled'
            AND priority = 'urgent'
            AND (deleted_at IS NULL OR deleted_at = '')
        ")->getRowArray()['count'] ?? 0;
        
        $stats = [
            'totalTests' => (int)$totalTests,
            'completedToday' => (int)$completedToday,
            'pendingTests' => (int)$pendingTests,
            'urgentTests' => (int)$urgentTests,
        ];

        // Test categories breakdown
        $categories = $db->query("
            SELECT 
                test_category, 
                COUNT(*) as count,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN status != 'completed' AND status != 'cancelled' THEN 1 ELSE 0 END) as pending_count
            FROM lab_tests 
            WHERE (deleted_at IS NULL OR deleted_at = '')
            GROUP BY test_category
            ORDER BY count DESC
        ")->getResultArray();

        // Status breakdown
        $statusBreakdown = $db->query("
            SELECT 
                status,
                COUNT(*) as count
            FROM lab_tests 
            WHERE (deleted_at IS NULL OR deleted_at = '')
            GROUP BY status
            ORDER BY count DESC
        ")->getResultArray();

        return view('Lab_staff/statistics', [
            'stats' => $stats,
            'categories' => $categories,
            'statusBreakdown' => $statusBreakdown,
        ]);
    }
}
