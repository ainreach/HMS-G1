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
        return view('dashboard/laboratory');
    }

    public function pharmacy()
    {
        helper('url');
        return view('dashboard/pharmacy');
    }

    public function scheduling()
    {
        helper('url');
        return view('dashboard/scheduling');
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
        return view('home/login');
    }

    public function register()
    {
        helper('url');
        return view('home/register');
    }

    public function records()
    {
        helper('url');
        return view('dashboard/records');
    }

    public function reports()
    {
        helper('url');
        return view('dashboard/reports');
    }
}
