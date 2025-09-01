<?php
namespace App\Controllers;

class Pages extends BaseController
{
    public function home()
    {
        helper('url');
        return view('home/index');
    }

    public function dashboard()
    {
        helper('url');
        return view('dashboard/dashboard');
    }

    public function laboratory()
    {
        helper('url');
        // Point to existing lab staff dashboard view
        return view('Lab_staff/dashboard');
    }

    public function pharmacy()
    {
        helper('url');
        // Point to existing pharmacy dashboard view
        return view('pharmacy/dashboard');
    }

    public function scheduling()
    {
        helper('url');
        $appointments = model('App\\Models\\AppointmentModel');
        $list = $appointments
            ->select('id,appointment_number,patient_id,doctor_id,appointment_date,appointment_time,status,created_at')
            ->orderBy('appointment_date','DESC')
            ->orderBy('appointment_time','DESC')
            ->findAll(50);
        return view('dashboard/scheduling', ['appointments' => $list]);
    }

    public function billing()
    {
        helper('url');
        return view('dashboard/billing');
    }

    public function about()
    {
        helper('url');
        return view('home/about');
    }

    public function login()
    {
        helper('url');
        return view('auth/login');
    }

    public function register()
    {
        helper('url');
        return view('home/register');
    }

    // Simple static pages
    public function privacy(){ helper('url'); return view('home/privacy'); }
    public function terms(){ helper('url'); return view('home/terms'); }
    public function news(){ helper('url'); return view('home/news'); }
    public function physicians(){ helper('url'); return view('home/physicians'); }
    public function services(){ helper('url'); return view('home/services'); }
    public function notifications(){ helper('url'); return view('home/notifications'); }

    public function records()
    {
        helper('url');
        $patients = model('App\\Models\\PatientModel');
        $list = $patients->select('id,patient_id,first_name,last_name,date_of_birth,phone,is_active,created_at')
                         ->orderBy('created_at','DESC')
                         ->findAll(50);
        return view('dashboard/records', ['patients' => $list]);
    }

    public function reports()
    {
        helper('url');
        // Collect simple KPIs for general reports page
        $patients = model('App\\Models\\PatientModel');
        $appointments = model('App\\Models\\AppointmentModel');
        $lab = model('App\\Models\\LabTestModel');
        $users = model('App\\Models\\UserModel');

        $today = date('Y-m-d');
        $kpi = [
            'total_patients' => $patients->countAllResults(),
            'appointments_today' => $appointments->where('DATE(appointment_date)', $today)->countAllResults(),
            'lab_pending' => $lab->where('status', 'pending')->countAllResults(),
            'lab_completed' => $lab->where('status', 'completed')->countAllResults(),
            'total_users' => $users->countAllResults(),
        ];
        return view('dashboard/reports', ['kpi' => $kpi]);
    }

    // Role-specific dashboards (clean folder structure under app/Views)
    public function dashboardAdmin(){ helper('url'); return view('admin/dashboard'); }
    public function dashboardDoctor(){ helper('url'); return view('doctor/dashboard'); }
    public function dashboardNurse(){ helper('url'); return view('nurse/dashboard'); }
    public function dashboardReceptionist(){ helper('url'); return view('Reception/dashboard'); }
    public function dashboardLabStaff(){ helper('url'); return view('Lab_staff/dashboard'); }
    public function dashboardPharmacist(){ helper('url'); return view('pharmacy/dashboard'); }
    public function dashboardAccountant(){ helper('url'); return view('Accountant/dashboard'); }
    public function dashboardITStaff(){ helper('url'); return view('IT_Staff/dashboard'); }
}


