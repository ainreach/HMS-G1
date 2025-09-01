<?php
namespace App\Controllers;

class Labstaff extends BaseController
{
    public function dashboard()
    {
        helper('url');
        $labTests = model('App\\Models\\LabTestModel');
        // Counts
        $pendingCount = $labTests->where('status !=', 'completed')->countAllResults();
        $completedCount = $labTests->where('status', 'completed')->countAllResults();
        // Recent pending list
        $pendingList = $labTests->where('status !=', 'completed')
            ->orderBy('requested_date', 'DESC')
            ->findAll(10);
        return view('Lab_staff/dashboard', [
            'pendingCount' => $pendingCount,
            'completedCount' => $completedCount,
            'pendingList' => $pendingList,
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
}
