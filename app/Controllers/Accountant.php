<?php
namespace App\Controllers;
use App\Libraries\AuditLogger;

class Accountant extends BaseController
{
    public function dashboard()
    {
        helper('url');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $paymentModel = model('App\\Models\\PaymentModel');

        $today = date('Y-m-d');

        // KPIs
        $invoicesToday = $invoiceModel
            ->where('DATE(issued_at)', $today)
            ->countAllResults();

        $paymentsToday = (float) ($paymentModel
            ->selectSum('amount', 'total')
            ->where('DATE(paid_at)', $today)
            ->first()['total'] ?? 0);

        // Recent mixed transactions (merge last 5 invoices and last 5 payments)
        $recent = [];
        $recentInvoices = $invoiceModel->orderBy('issued_at', 'DESC')->findAll(5);
        foreach ($recentInvoices as $inv) {
            $recent[] = [
                'date' => $inv['issued_at'],
                'type' => 'Invoice',
                'patient' => $inv['patient_name'],
                'amount' => (float)$inv['amount'],
                'status' => ucfirst($inv['status'] ?? 'unpaid'),
            ];
        }
        $recentPayments = $paymentModel->orderBy('paid_at', 'DESC')->findAll(5);
        foreach ($recentPayments as $pay) {
            $recent[] = [
                'date' => $pay['paid_at'],
                'type' => 'Payment',
                'patient' => $pay['patient_name'],
                'amount' => (float)$pay['amount'],
                'status' => 'Posted',
            ];
        }
        // Sort by date desc
        usort($recent, fn($a,$b) => strcmp($b['date'], $a['date']));
        $recent = array_slice($recent, 0, 10);

        return view('Accountant/dashboard', [
            'invoicesToday' => (int)$invoicesToday,
            'paymentsToday' => $paymentsToday,
            'recent' => $recent,
        ]);
    }

    public function invoices()
    {
        helper('url');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $patientModel = model('App\\Models\\PatientModel');
        
        $invoices = $invoiceModel
            ->orderBy('issued_at', 'DESC')
            ->findAll(50);
            
        // Fetch active patients for the dropdown
        $patients = $patientModel->getActivePatients();
        
        // Format patients for the dropdown
        $formattedPatients = array_map(function($patient) {
            return [
                'id' => $patient['id'],
                'name' => $patient['first_name'] . ' ' . $patient['last_name'],
                'mobile' => $patient['phone'] ?? 'N/A'
            ];
        }, $patients);
            
        return view('Accountant/invoices', [
            'invoices' => $invoices,
            'patients' => $formattedPatients
        ]);
    }

    public function payments()
    {
        helper('url');
        $paymentsModel = model('App\\Models\\PaymentModel');
        
        $payments = $paymentsModel
            ->orderBy('paid_at', 'DESC')
            ->findAll(50);
            
        return view('Accountant/payments', [
            'payments' => $payments
        ]);
    }

    public function billing()
    {
        helper('url');
        $paymentsModel = model('App\\Models\\PaymentModel');
        $invoiceModel  = model('App\\Models\\InvoiceModel');

        $payments = $paymentsModel->orderBy('paid_at', 'DESC')->findAll(20);
        $invoices = $invoiceModel->orderBy('issued_at', 'DESC')->findAll(20);

        $today = date('Y-m-d');
        $openInvoices = array_values(array_filter($invoices, fn($i) => strtolower((string)($i['status'] ?? '')) !== 'paid'));
        $paymentsToday = 0.0;
        foreach ($payments as $p) {
            if (strpos((string)$p['paid_at'], $today) === 0) { $paymentsToday += (float)$p['amount']; }
        }
        $arBalance = 0.0;
        foreach ($openInvoices as $i) { $arBalance += (float)$i['amount']; }

        return view('Accountant/billing', [
            'payments' => $payments,
            'invoices' => $invoices,
            'openInvoicesCount' => count($openInvoices),
            'paymentsToday' => $paymentsToday,
            'arBalance' => $arBalance,
        ]);
    }

    public function dischargePatient($patientId)
    {
        helper(['url', 'form']);
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        
        $patientId = (int) $patientId;
        
        // Get patient details
        $patient = $patientModel->find($patientId);
        if (!$patient) {
            return redirect()->to(site_url('accountant/billing'))->with('error', 'Patient not found.');
        }
        
        if (!$patient['assigned_room_id']) {
            return redirect()->to(site_url('accountant/billing'))->with('error', 'Patient is not assigned to any room.');
        }
        
        // Get room details
        $room = $roomModel->find($patient['assigned_room_id']);
        if (!$room) {
            return redirect()->to(site_url('accountant/billing'))->with('error', 'Room not found.');
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
        
        return redirect()->to(site_url('accountant/billing'))->with('success', 'Patient discharged successfully. Room is now available.');
    }

    public function patientBilling($patientId = null)
    {
        if (!$patientId) {
            return redirect()->to('/accountant/billing')->with('error', 'Patient ID is required.');
        }
        
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $paymentModel = model('App\\Models\\PaymentModel');
        
        $patient = $patientModel->find($patientId);
        
        if (!$patient) {
            return redirect()->to('/accountant/billing')->with('error', 'Patient not found.');
        }
        
        // Get room details if patient is assigned to a room
        $room = null;
        $roomCharges = 0;
        $daysStayed = 0;
        
        if ($patient['assigned_room_id']) {
            $room = $roomModel->find($patient['assigned_room_id']);
            
            if ($room && $patient['admission_date']) {
                // Calculate days stayed
                $admissionDate = new \DateTime($patient['admission_date']);
                $currentDate = new \DateTime();
                $daysStayed = $admissionDate->diff($currentDate)->days + 1; // +1 to include admission day
                $roomCharges = $daysStayed * $room['rate_per_day'];
            }
        }
        
        // Get existing invoices and payments for this patient
        $invoices = $invoiceModel->where('patient_name', $patient['first_name'] . ' ' . $patient['last_name'])->orderBy('issued_at', 'DESC')->findAll();
        $payments = $paymentModel->where('patient_name', $patient['first_name'] . ' ' . $patient['last_name'])->orderBy('paid_at', 'DESC')->findAll();
        
        // Calculate totals
        $totalInvoices = 0;
        foreach ($invoices as $invoice) {
            $totalInvoices += (float) $invoice['amount'];
        }
        
        $totalPayments = 0;
        foreach ($payments as $payment) {
            $totalPayments += (float) $payment['amount'];
        }
        
        // Calculate total charges (room charges + invoices)
        $totalCharges = $roomCharges + $totalInvoices;
        $balanceDue = $totalCharges - $totalPayments;
        
        return view('Accountant/patient_billing', [
            'patient' => $patient,
            'room' => $room,
            'roomCharges' => $roomCharges,
            'daysStayed' => $daysStayed,
            'invoices' => $invoices,
            'payments' => $payments,
            'totalInvoices' => $totalInvoices,
            'totalPayments' => $totalPayments,
            'totalCharges' => $totalCharges,
            'balanceDue' => $balanceDue
        ]);
    }

    public function allPatientBills()
    {
        helper('url');
        $patientModel = model('App\\Models\\PatientModel');
        $roomModel = model('App\\Models\\RoomModel');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $paymentModel = model('App\\Models\\PaymentModel');
        
        // Get all patients with room assignments
        $patientsWithRooms = $patientModel
            ->where('assigned_room_id IS NOT NULL', null, false)
            ->where('assigned_room_id !=', null)
            ->findAll();
        
        $allBills = [];
        
        foreach ($patientsWithRooms as $patient) {
            // Get room details
            $room = null;
            $roomCharges = 0;
            $daysStayed = 0;
            
            if ($patient['assigned_room_id']) {
                $room = $roomModel->find($patient['assigned_room_id']);
                
                if ($room && $patient['admission_date']) {
                    $admissionDate = new \DateTime($patient['admission_date']);
                    $currentDate = new \DateTime();
                    $daysStayed = $admissionDate->diff($currentDate)->days + 1;
                    $roomCharges = $daysStayed * $room['rate_per_day'];
                }
            }
            
            // Get invoices and payments
            $invoices = $invoiceModel->where('patient_name', $patient['first_name'] . ' ' . $patient['last_name'])->findAll();
            $payments = $paymentModel->where('patient_name', $patient['first_name'] . ' ' . $patient['last_name'])->findAll();
            
            $totalInvoices = 0;
            foreach ($invoices as $invoice) {
                $totalInvoices += (float) $invoice['amount'];
            }
            
            $totalPayments = 0;
            foreach ($payments as $payment) {
                $totalPayments += (float) $payment['amount'];
            }
            
            $totalCharges = $roomCharges + $totalInvoices;
            $balanceDue = $totalCharges - $totalPayments;
            
            $allBills[] = [
                'patient' => $patient,
                'room' => $room,
                'roomCharges' => $roomCharges,
                'daysStayed' => $daysStayed,
                'totalInvoices' => $totalInvoices,
                'totalPayments' => $totalPayments,
                'totalCharges' => $totalCharges,
                'balanceDue' => $balanceDue
            ];
        }
        
        return view('Accountant/all_patient_bills', [
            'allBills' => $allBills
        ]);
    }

    public function reports()
    {
        helper('url');
        $invoiceModel = model('App\\Models\\InvoiceModel');
        $paymentModel = model('App\\Models\\PaymentModel');

        $now = new \DateTimeImmutable('now');
        $d30 = $now->modify('-30 days')->format('Y-m-d 00:00:00');
        $d60 = $now->modify('-60 days')->format('Y-m-d 00:00:00');
        $d30_only = $now->modify('-30 days')->format('Y-m-d');

        // Revenue last 30d
        $revenue30d = (float) ($paymentModel
            ->selectSum('amount', 'total')
            ->where('paid_at >=', $d30)
            ->first()['total'] ?? 0);

        // Load invoices and payments for calculations
        $invoices = $invoiceModel->orderBy('issued_at', 'DESC')->findAll(500);
        $payments = $paymentModel->orderBy('paid_at', 'DESC')->findAll(500);

        // AR > 60d and aging buckets from unpaid invoices
        $arOver60d = 0.0;
        $agingBuckets = [
            '0-30' => 0.0,
            '31-60' => 0.0,
            '61-90' => 0.0,
            '>90' => 0.0,
        ];
        foreach ($invoices as $inv) {
            $isPaid = strtolower((string)($inv['status'] ?? '')) === 'paid';
            if ($isPaid) { continue; }
            $days = (int) floor((strtotime($now->format('Y-m-d')) - strtotime((string)$inv['issued_at'])) / 86400);
            $amt = (float)($inv['amount'] ?? 0);
            if ($days <= 30) { $agingBuckets['0-30'] += $amt; }
            elseif ($days <= 60) { $agingBuckets['31-60'] += $amt; }
            elseif ($days <= 90) { $agingBuckets['61-90'] += $amt; }
            else { $agingBuckets['>90'] += $amt; }
            if ($days > 60) { $arOver60d += $amt; }
        }

        // Avg Days to Pay (match by invoice_no when available)
        $invByNo = [];
        foreach ($invoices as $inv) {
            if (!empty($inv['invoice_no'])) { $invByNo[$inv['invoice_no']] = $inv; }
        }
        $diffs = [];
        foreach ($payments as $p) {
            $no = (string)($p['invoice_no'] ?? '');
            if ($no !== '' && isset($invByNo[$no])) {
                $days = (int) floor((strtotime((string)$p['paid_at']) - strtotime((string)$invByNo[$no]['issued_at'])) / 86400);
                if ($days >= 0) { $diffs[] = $days; }
            }
        }
        $avgDaysToPay = !empty($diffs) ? round(array_sum($diffs) / max(1, count($diffs))) : 0;

        return view('Accountant/reports', [
            'revenue30d' => $revenue30d,
            'arOver60d' => $arOver60d,
            'avgDaysToPay' => $avgDaysToPay,
            'aging' => $agingBuckets,
        ]);
    }

    public function newInvoice()
{
    helper('url');
    $patientModel = model('App\\Models\\PatientModel');
    
    // Fetch active patients
    $patients = $patientModel->getActivePatients();
    
    // Format patients for the dropdown
    $formattedPatients = array_map(function($patient) {
        return [
            'id' => $patient['id'],
            'name' => $patient['first_name'] . ' ' . $patient['last_name'],
            'mobile' => $patient['phone'] ?? 'N/A'
        ];
    }, $patients);

    return view('Accountant/invoices', ['patients' => $formattedPatients]);
}

    public function newPayment()
    {
        helper('url');
        return view('Accountant/payment_new');
    }

    public function storeInvoice()
    {
        helper(['url', 'form']);
        $model = new \App\Models\InvoiceModel();
        $patientModel = model('App\\Models\\PatientModel');

        $patientId = $this->request->getPost('patient_id');
        $patient = $patientModel->find($patientId);
        
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found.')->withInput();
        }

        $data = [
            'invoice_no'   => trim((string)$this->request->getPost('invoice_no')),
            'patient_name' => $patient['first_name'] . ' ' . $patient['last_name'],
            'amount'       => (float)$this->request->getPost('amount'),
            'status'       => $this->request->getPost('status') ?: 'unpaid',
            'issued_at'    => $this->request->getPost('invoice_date') ?: date('Y-m-d'),
        ];

        if ($data['amount'] <= 0) {
            return redirect()->back()->with('error', 'Valid amount is required.')->withInput();
        }

        $model->insert($data);
        AuditLogger::log('invoice_create', 'invoice_no=' . ($data['invoice_no'] ?: 'n/a') . ' amount=' . $data['amount']);
        return redirect()->to(site_url('accountant/invoices'))->with('success', 'Invoice created successfully.');
    }
    public function insurance()
    {
        helper('url');
        $claimsModel = model('App\\Models\\InsuranceClaimModel');
        $claims = $claimsModel->orderBy('submitted_at', 'DESC')->findAll(50);
        return view('Accountant/insurance', ['claims' => $claims]);
    }

    public function newClaim()
    {
        helper(['url']);
        return view('Accountant/claim_new');
    }

    public function storeClaim()
    {
        helper(['url']);
        $claimsModel = model('App\\Models\\InsuranceClaimModel');

        $data = [
            'claim_no' => $this->request->getPost('claim_no'),
            'invoice_no' => $this->request->getPost('invoice_no'),
            'patient_name' => $this->request->getPost('patient_name'),
            'provider' => $this->request->getPost('provider'),
            'policy_no' => $this->request->getPost('policy_no'),
            'amount_claimed' => (float)$this->request->getPost('amount_claimed'),
            'amount_approved' => (float)($this->request->getPost('amount_approved') ?: 0),
            'status' => $this->request->getPost('status') ?: 'submitted',
            'submitted_at' => $this->request->getPost('submitted_at') ?: date('Y-m-d H:i:s'),
        ];

        $claimsModel->insert($data);
        AuditLogger::log('claim_create', 'claim_no=' . ($data['claim_no'] ?: 'n/a') . ' invoice_no=' . ($data['invoice_no'] ?: 'n/a'));
        return redirect()->to(site_url('accountant/insurance'));
    }

    public function statements()
    {
        helper('url');
        $model = new \App\Models\PaymentModel();
        $payments = $model->orderBy('paid_at', 'DESC')->findAll(100);
        return view('Accountant/statements', ['payments' => $payments]);
    }

    public function storePayment()
    {
        helper(['url', 'form']);

        $patient = trim((string) $this->request->getPost('patient_name'));
        $invoice = trim((string) $this->request->getPost('invoice_no'));
        $amount  = (float) $this->request->getPost('amount');
        $paidAt  = (string) $this->request->getPost('paid_at');

        if ($patient === '' || $amount <= 0) {
            return redirect()->back()->with('error', 'Patient and valid amount are required.')->withInput();
        }

        $model = new \App\Models\PaymentModel();
        $model->insert([
            'patient_name' => $patient,
            'invoice_no'   => $invoice !== '' ? $invoice : null,
            'amount'       => $amount,
            'paid_at'      => $paidAt !== '' ? $paidAt : date('Y-m-d H:i:s'),
        ]);

        AuditLogger::log('payment_record', 'patient=' . $patient . ' amount=' . $amount . ' invoice_no=' . ($invoice ?: 'n/a'));
        return redirect()->to(site_url('accountant/billing'))
            ->with('success', 'Payment recorded successfully.');
    }

    public function exportStatement()
    {
        // Simple CSV export of all payments
        $model = new \App\Models\PaymentModel();
        $payments = $model->orderBy('paid_at', 'DESC')->findAll();

        $filename = 'statement_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $fp = fopen('php://temp', 'w');
        fputcsv($fp, ['Date', 'Patient', 'Invoice', 'Amount']);
        foreach ($payments as $p) {
            fputcsv($fp, [
                $p['paid_at'],
                $p['patient_name'],
                $p['invoice_no'] ?? '',
                number_format((float)$p['amount'], 2),
            ]);
        }
        rewind($fp);
        $csv = stream_get_contents($fp);
        fclose($fp);

        AuditLogger::log('statement_export_csv', 'payments=' . count($payments));
        return service('response')->setStatusCode(200)->setHeader('Content-Type', $headers['Content-Type'])
            ->setHeader('Content-Disposition', $headers['Content-Disposition'])->setBody($csv);
    }

    // Export all invoices as CSV
    public function exportInvoicesCsv()
    {
        $model = new \App\Models\InvoiceModel();
        $invoices = $model->orderBy('issued_at', 'DESC')->findAll();

        $filename = 'invoices_' . date('Ymd_His') . '.csv';
        $fp = fopen('php://temp', 'w');
        fputcsv($fp, ['Issued At', 'Invoice No', 'Patient', 'Amount', 'Status']);
        foreach ($invoices as $i) {
            fputcsv($fp, [
                $i['issued_at'],
                $i['invoice_no'] ?? '',
                $i['patient_name'] ?? '',
                number_format((float)($i['amount'] ?? 0), 2),
                $i['status'] ?? '',
            ]);
        }
        rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
        AuditLogger::log('invoices_export_csv', 'invoices=' . count($invoices));
        return service('response')
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    // Export ZIP: invoices.csv, payments.csv, aging.csv
    public function exportZip()
    {
        $invoiceModel = new \App\Models\InvoiceModel();
        $paymentModel = new \App\Models\PaymentModel();

        $invoices = $invoiceModel->orderBy('issued_at', 'DESC')->findAll(10000);
        $payments = $paymentModel->orderBy('paid_at', 'DESC')->findAll(10000);

        // Compute aging similar to reports()
        $now = new \DateTimeImmutable('now');
        $aging = ['0-30'=>0.0,'31-60'=>0.0,'61-90'=>0.0,'>90'=>0.0];
        foreach ($invoices as $inv) {
            $isPaid = strtolower((string)($inv['status'] ?? '')) === 'paid';
            if ($isPaid) continue;
            $days = (int) floor((strtotime($now->format('Y-m-d')) - strtotime((string)($inv['issued_at'] ?? ''))) / 86400);
            $amt = (float)($inv['amount'] ?? 0);
            if ($days <= 30) { $aging['0-30'] += $amt; }
            elseif ($days <= 60) { $aging['31-60'] += $amt; }
            elseif ($days <= 90) { $aging['61-90'] += $amt; }
            else { $aging['>90'] += $amt; }
        }

        if (class_exists('ZipArchive')) {
            $zip = new \ZipArchive();
            $tmp = tempnam(sys_get_temp_dir(), 'acct_zip_');
            $zip->open($tmp, \ZipArchive::OVERWRITE);

            // invoices.csv
            $fp = fopen('php://temp', 'w');
            fputcsv($fp, ['Issued At','Invoice No','Patient','Amount','Status']);
            foreach ($invoices as $i) {
                fputcsv($fp, [$i['issued_at'], $i['invoice_no'] ?? '', $i['patient_name'] ?? '', (float)($i['amount'] ?? 0), $i['status'] ?? '']);
            }
            rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
            $zip->addFromString('invoices.csv', $csv);

            // payments.csv
            $fp = fopen('php://temp', 'w');
            fputcsv($fp, ['Paid At','Patient','Invoice','Amount']);
            foreach ($payments as $p) {
                fputcsv($fp, [$p['paid_at'], $p['patient_name'] ?? '', $p['invoice_no'] ?? '', (float)($p['amount'] ?? 0)]);
            }
            rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
            $zip->addFromString('payments.csv', $csv);

            // aging.csv
            $fp = fopen('php://temp', 'w');
            fputcsv($fp, ['Bucket','Amount']);
            foreach ($aging as $bucket=>$amt) { fputcsv($fp, [$bucket, $amt]); }
            rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
            $zip->addFromString('aging.csv', $csv);

            $zip->close();
            $filename = 'finance_backup_' . date('Ymd_His') . '.zip';
            $data = file_get_contents($tmp); @unlink($tmp);
            AuditLogger::log('finance_export_zip', 'invoices=' . count($invoices) . ' payments=' . count($payments));
            return service('response')
                ->setHeader('Content-Type', 'application/zip')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($data);
        }

        // Fallback: return payments CSV
        $fp = fopen('php://temp', 'w');
        fputcsv($fp, ['Date', 'Patient', 'Invoice', 'Amount']);
        foreach ($payments as $p) {
            fputcsv($fp, [$p['paid_at'], $p['patient_name'] ?? '', $p['invoice_no'] ?? '', (float)($p['amount'] ?? 0)]);
        }
        rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
        $filename = 'statement_' . date('Ymd_His') . '.csv';
        AuditLogger::log('finance_export_csv_fallback', 'payments=' . count($payments));
        return service('response')
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }
}
