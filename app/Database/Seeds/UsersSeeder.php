<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $users = [
            [
                'employee_id' => 'E-0001',
                'username' => 'admin_jane',
                'email' => 'admin_jane@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'first_name' => 'Jane',
                'last_name' => 'Admin',
                'role' => 'admin',
                'branch_id' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'E-0002',
                'username' => 'it_mike',
                'email' => 'it_mike@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'first_name' => 'Mike',
                'last_name' => 'IT',
                'role' => 'it_staff',
                'branch_id' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'E-0003',
                'username' => 'doc_sara',
                'email' => 'doc_sara@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'first_name' => 'Sara',
                'last_name' => 'Doctor',
                'role' => 'doctor',
                'branch_id' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'E-0004',
                'username' => 'nurse_lee',
                'email' => 'nurse_lee@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'first_name' => 'Lee',
                'last_name' => 'Nurse',
                'role' => 'nurse',
                'branch_id' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'E-0005',
                'username' => 'recep_anna',
                'email' => 'recep_anna@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'first_name' => 'Anna',
                'last_name' => 'Reception',
                'role' => 'receptionist',
                'branch_id' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'E-0006',
                'username' => 'lab_ben',
                'email' => 'lab_ben@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'first_name' => 'Ben',
                'last_name' => 'Lab',
                'role' => 'lab_staff',
                'branch_id' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'E-0007',
                'username' => 'pharm_kate',
                'email' => 'pharm_kate@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'first_name' => 'Kate',
                'last_name' => 'Pharm',
                'role' => 'pharmacist',
                'branch_id' => 1,
                'license_number' => 'LIC-67890',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employee_id' => 'E-0008',
                'username' => 'acct_noel',
                'email' => 'acct_noel@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'first_name' => 'Noel',
                'last_name' => 'Acct',
                'role' => 'accountant',
                'branch_id' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert one by one to avoid column count issues
        foreach ($users as $user) {
            $this->db->table('users')->insert($user);
        }
        
        log_message('info', 'UsersSeeder: Successfully inserted ' . count($users) . ' users');
    }
}
