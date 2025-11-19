<?php
namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use App\Models\UserModel;

class Auth extends BaseController
{
    // Role selection landing
    public function select()
    {
        helper('url');
        return view('auth/login');
    }

    // Show per-role login form
    public function login(string $role = 'admin')
    {
        helper('url');
        $viewMap = [
            'admin' => 'auth/admin',
            'doctor' => 'auth/doctor',
            'nurse' => 'auth/nurse',
            'receptionist' => 'auth/receptionist',
            'lab_staff' => 'auth/lab_staff',
            'pharmacist' => 'auth/pharmacist',
            'accountant' => 'auth/accountant',
            'it_staff' => 'auth/it_staff',
        ];
        $view = $viewMap[$role] ?? 'auth/login';
        return view($view);
    }

    // Process per-role login (placeholder logic)
    public function doLogin(string $role): RedirectResponse
    {
        $session = session();
        $session->set('role', $role);
        // Redirect to matching dashboard controller
        switch ($role) {
            case 'admin': return redirect()->to('/dashboard/admin');
            case 'doctor': return redirect()->to('/dashboard/doctor');
            case 'nurse': return redirect()->to('/dashboard/nurse');
            case 'receptionist': return redirect()->to('/dashboard/receptionist');
            case 'lab_staff': return redirect()->to('/dashboard/lab');
            case 'pharmacist': return redirect()->to('/dashboard/pharmacist');
            case 'accountant': return redirect()->to('/dashboard/accountant');
            case 'it_staff': return redirect()->to('/dashboard/it');
            default: return redirect()->to('/dashboard');
        }
    }

    // Process single login form against DB users table
    public function doLoginSingle(): RedirectResponse
    {
        $request = $this->request;
        $username = trim((string) $request->getPost('username'));
        $password = (string) $request->getPost('password');

        if ($username === '' || $password === '') {
            return redirect()->to('/login')->with('error', 'Please enter username and password.');
        }

        $users = new UserModel();
        $user = $users->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->to('/login')->with('error', 'Invalid credentials.');
        }

        // Save session
        $session = session();
        $session->set('role', $user['role']);
        $session->set('username', $user['username']);
        if (isset($user['id'])) {
            $session->set('user_id', (int) $user['id']);
        }

        switch ($user['role']) {
            case 'admin': return redirect()->to('/dashboard/admin');
            case 'doctor': return redirect()->to('/dashboard/doctor');
            case 'nurse': return redirect()->to('/dashboard/nurse');
            case 'receptionist': return redirect()->to('/dashboard/receptionist');
            case 'lab_staff': return redirect()->to('/dashboard/lab');
            case 'pharmacist': return redirect()->to('/dashboard/pharmacist');
            case 'accountant': return redirect()->to('/dashboard/accountant');
            case 'it_staff': return redirect()->to('/dashboard/it');
            default: return redirect()->to('/dashboard');
        }
    }

    private function inferRoleFromUsername(string $username): string
    {
        $u = strtolower($username);
        if (str_contains($u, 'admin')) return 'admin';
        if (str_contains($u, 'it')) return 'it_staff';
        if (str_contains($u, 'doc')) return 'doctor';
        if (str_contains($u, 'nurse')) return 'nurse';
        if (str_contains($u, 'recep') || str_contains($u, 'recept') || str_contains($u, 'reception')) return 'receptionist';
        if (str_contains($u, 'lab')) return 'lab_staff';
        if (str_contains($u, 'pharm')) return 'pharmacist';
        if (str_contains($u, 'account')) return 'accountant';
        return 'admin';
    }

    public function logout(): RedirectResponse
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
