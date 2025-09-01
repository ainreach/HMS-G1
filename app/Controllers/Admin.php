<?php
namespace App\Controllers;
use App\Libraries\AuditLogger;

class Admin extends BaseController
{
    public function dashboard()
    {
        helper(['url', 'date']);
        $userModel = model('App\\Models\\UserModel');

        // KPIs (avoid non-existent columns like 'status')
        $totalUsers = $userModel->countAllResults();
        $newUsersThisMonth = $userModel->where('created_at >=', date('Y-m-01'))->countAllResults();
        $recentUsers = $userModel->select('id, employee_id, username, role, created_at')
                                 ->orderBy('created_at', 'DESC')
                                 ->findAll(5);

        // Windows-safe disk space check (use FCPATH if available)
        $basePath = defined('FCPATH') ? FCPATH : __DIR__;
        $freeBytes = @disk_free_space($basePath);
        $freeGb = $freeBytes !== false ? ($freeBytes / (1024 * 1024 * 1024)) : 0;

        $data = [
            'totalUsers' => $totalUsers,
            // Temporary: without a 'status' column, treat all as active
            'activeUsers' => $totalUsers,
            'newUsersThisMonth' => $newUsersThisMonth,
            'recentUsers' => $recentUsers,
            'systemStatus' => [
                'database' => 'online',
                'storage' => $freeGb,
                'lastBackup' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'activeSessions' => 1,
            ],
        ];

        return view('admin/dashboard', $data);
    }

    public function newUser()
    {
        helper(['url','form']);
        return view('admin/user_new');
    }

    public function storeUser()
    {
        helper(['url','form']);
        $req = $this->request;
        $data = [
            'employee_id' => trim((string)$req->getPost('employee_id')),
            'username'    => trim((string)$req->getPost('username')),
            'email'       => trim((string)$req->getPost('email')),
            'first_name'  => trim((string)$req->getPost('first_name')),
            'last_name'   => trim((string)$req->getPost('last_name')),
            'role'        => trim((string)$req->getPost('role')),
        ];
        $password = (string)$req->getPost('password');
        if ($data['username']==='' || $password==='' || $data['role']==='') {
            return redirect()->back()->with('error','Username, password, and role are required.')->withInput();
        }
        $users = model('App\\Models\\UserModel');
        // Basic uniqueness check on username
        $exists = $users->where('username', $data['username'])->first();
        if ($exists) {
            return redirect()->back()->with('error','Username already exists.')->withInput();
        }
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $users->insert($data);
        AuditLogger::log('user_create', 'username=' . $data['username'] . ' role=' . $data['role']);
        return redirect()->to(site_url('dashboard/admin'))->with('success','User created successfully.');
    }

    public function assignRole()
    {
        helper(['url','form']);
        $users = model('App\\Models\\UserModel')->orderBy('username','ASC')->findAll(1000);
        return view('admin/role_assign', ['users'=>$users]);
    }

    public function storeRoleAssignment()
    {
        helper(['url','form']);
        $userId = (int) $this->request->getPost('user_id');
        $role   = trim((string)$this->request->getPost('role'));
        if (!$userId || $role==='') {
            return redirect()->back()->with('error','User and role are required.')->withInput();
        }
        $users = model('App\\Models\\UserModel');
        if (!$users->find($userId)) {
            return redirect()->back()->with('error','Selected user not found.')->withInput();
        }
        $users->update($userId, ['role'=>$role, 'updated_at'=>date('Y-m-d H:i:s')]);
        AuditLogger::log('role_assign', 'user_id=' . $userId . ' role=' . $role);
        return redirect()->to(site_url('dashboard/admin'))->with('success','Role updated.');
    }

    public function reports()
    {
        helper('url');
        $users = model('App\\Models\\UserModel');
        $counts = [
            'total' => $users->countAllResults(),
            'admins' => $users->where('role','admin')->countAllResults(),
            'doctors' => $users->where('role','doctor')->countAllResults(),
            'nurses' => $users->where('role','nurse')->countAllResults(),
            'receptionists' => $users->where('role','receptionist')->countAllResults(),
            'pharmacists' => $users->where('role','pharmacist')->countAllResults(),
            'lab_staff' => $users->where('role','lab_staff')->countAllResults(),
            'accountants' => $users->where('role','accountant')->countAllResults(),
            'it_staff' => $users->where('role','it_staff')->countAllResults(),
        ];
        return view('admin/reports', ['counts'=>$counts]);
    }

    public function usersList()
    {
        helper(['url']);
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $userModel = model('App\\Models\\UserModel');
        $total = $userModel->countAllResults();
        $users = $userModel
                    ->select('id,employee_id,username,role,created_at')
                    ->orderBy('created_at','DESC')
                    ->findAll($perPage, $offset);
        $data = [
            'users' => $users,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'hasPrev' => $page > 1,
            'hasNext' => ($offset + count($users)) < $total,
        ];
        return view('admin/users_list', $data);
    }

    public function editUser($id)
    {
        helper(['url','form']);
        $id = (int) $id;
        $user = model('App\\Models\\UserModel')->find($id);
        if (!$user) {
            return redirect()->to(site_url('admin/users'))->with('error','User not found.');
        }
        return view('admin/user_edit', ['user' => $user]);
    }

    public function updateUser($id)
    {
        helper(['url','form']);
        $id = (int) $id;
        $req = $this->request;
        $data = [
            'employee_id' => trim((string)$req->getPost('employee_id')),
            'email'       => trim((string)$req->getPost('email')),
            'first_name'  => trim((string)$req->getPost('first_name')),
            'last_name'   => trim((string)$req->getPost('last_name')),
            'role'        => trim((string)$req->getPost('role')),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        $password = (string)$req->getPost('password');
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $users = model('App\\Models\\UserModel');
        if (!$users->find($id)) {
            return redirect()->to(site_url('admin/users'))->with('error','User not found.');
        }
        $users->update($id, $data);
        AuditLogger::log('user_update', 'user_id=' . $id);
        return redirect()->to(site_url('admin/users'))->with('success','User updated.');
    }

    public function deleteUser($id)
    {
        helper(['url']);
        $id = (int) $id;
        $users = model('App\\Models\\UserModel');
        if ($users->find($id)) {
            $users->delete($id);
            AuditLogger::log('user_delete', 'user_id=' . $id);
        }
        return redirect()->to(site_url('admin/users'))->with('success','User deleted.');
    }
}
