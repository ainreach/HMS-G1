<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('role');

        // If no one is logged in, block
        if (!$role) {
            return redirect()->to('/login')->with('error', 'Please login to continue.');
        }

        // If no specific role required, allow any authenticated user
        if (empty($arguments)) {
            return null;
        }

        // Support: role:any means any authenticated
        if (in_array('any', (array)$arguments, true)) {
            return null;
        }

        // Allow if session role matches any of the required roles
        if (in_array($role, (array)$arguments, true)) {
            return null;
        }

        // Otherwise block and bounce to respective dashboard if possible
        // Try to redirect the user to their own dashboard
        switch ($role) {
            case 'admin': return redirect()->to('/dashboard/admin')->with('error', 'Not authorized for that page.');
            case 'doctor': return redirect()->to('/dashboard/doctor')->with('error', 'Not authorized for that page.');
            case 'nurse': return redirect()->to('/dashboard/nurse')->with('error', 'Not authorized for that page.');
            case 'receptionist': return redirect()->to('/dashboard/receptionist')->with('error', 'Not authorized for that page.');
            case 'lab_staff': return redirect()->to('/dashboard/lab')->with('error', 'Not authorized for that page.');
            case 'pharmacist': return redirect()->to('/dashboard/pharmacist')->with('error', 'Not authorized for that page.');
            case 'accountant': return redirect()->to('/dashboard/accountant')->with('error', 'Not authorized for that page.');
            case 'it_staff': return redirect()->to('/dashboard/it')->with('error', 'Not authorized for that page.');
            default: return redirect()->to('/dashboard')->with('error', 'Not authorized for that page.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No-op
    }
}
