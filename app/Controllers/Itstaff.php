<?php
namespace App\Controllers;
use App\Libraries\AuditLogger;

class Itstaff extends BaseController
{
    public function dashboard()
    {
        helper(['url']);
        $data = [
            'cards' => [
                ['title' => 'Maintenance', 'icon' => 'toolbox', 'url' => site_url('it/maintenance')],
                ['title' => 'Security', 'icon' => 'shield-halved', 'url' => site_url('it/security')],
                ['title' => 'Backups', 'icon' => 'database', 'url' => site_url('it/backups')],
                ['title' => 'Reports', 'icon' => 'chart-bar', 'url' => site_url('it/reports')],
                ['title' => 'Audit Logs', 'icon' => 'clipboard-list', 'url' => site_url('it/audit-logs')],
            ],
        ];
        return view('IT_Staff/dashboard', $data);
    }

    public function reports()
    {
        helper('url');
        // Simple stub data; integrate with real system metrics when available
        $data = [
            'uptime' => '—',
            'errors_today' => 0,
            'warnings_today' => 0,
        ];
        return view('IT_Staff/reports', $data);
    }

    public function auditLogs()
    {
        helper('url');
        $file = WRITEPATH . 'logs' . DIRECTORY_SEPARATOR . 'audit.log';
        $lines = [];
        if (is_file($file)) {
            $content = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (is_array($content)) {
                $lines = array_slice($content, -200); // last 200 lines
            }
        }
        return view('IT_Staff/audit_logs', ['logs' => $lines]);
    }

    public function backups()
    {
        helper('url');
        $items = [
            ['name' => 'Users CSV', 'action' => site_url('it/backups/export/users'), 'desc' => 'Export users table as CSV'],
        ];
        return view('IT_Staff/backups', ['backups' => $items]);
    }

    // New: simple maintenance page
    public function maintenance()
    {
        helper(['url']);
        return view('IT_Staff/maintenance');
    }

    // New: clear application cache (safe)
    public function clearCache()
    {
        helper(['url']);
        $cache = \Config\Services::cache();
        if ($cache) { $cache->clean(); }
        AuditLogger::log('cache_cleared', 'IT cleared application cache');
        return redirect()->to(site_url('it/maintenance'))->with('success', 'Application cache cleared.');
    }

    // New: basic security page (stubs)
    public function security()
    {
        helper(['url']);
        $data = [
            'appEnv' => ENVIRONMENT,
            'phpVersion' => PHP_VERSION,
            'debugEnabled' => (bool) (config('App')->debug ?? true),
        ];
        return view('IT_Staff/security', $data);
    }

    // New: basic health page for DB and storage checks
    public function health()
    {
        helper(['url']);
        $dbOk = false; $dbError = null;
        try {
            $db = \Config\Database::connect();
            $db->query('SELECT 1');
            $dbOk = true;
        } catch (\Throwable $e) {
            $dbOk = false; $dbError = $e->getMessage();
        }
        $basePath = defined('FCPATH') ? FCPATH : __DIR__;
        $freeBytes = @disk_free_space($basePath);
        $freeGb = $freeBytes !== false ? ($freeBytes / (1024*1024*1024)) : 0;
        $data = [
            'dbOk' => $dbOk,
            'dbError' => $dbError,
            'storageGbFree' => $freeGb,
            'checkedAt' => date('Y-m-d H:i:s'),
        ];
        return view('IT_Staff/health', $data);
    }

    // New: export users as CSV (simple backup)
    public function exportUsersCsv()
    {
        $userModel = model('App\\Models\\UserModel');
        $rows = $userModel->select('id,employee_id,username,email,role,created_at')->orderBy('id','ASC')->findAll(10000);

        $filename = 'users_export_' . date('Ymd_His') . '.csv';
        $fp = fopen('php://temp', 'w');
        // Header
        fputcsv($fp, ['id','employee_id','username','email','role','created_at']);
        foreach ($rows as $r) {
            fputcsv($fp, [$r['id'], $r['employee_id'] ?? '', $r['username'], $r['email'] ?? '', $r['role'] ?? '', $r['created_at'] ?? '']);
        }
        rewind($fp);
        $csv = stream_get_contents($fp);
        fclose($fp);

        AuditLogger::log('backup_export_csv', 'Exported users CSV (' . count($rows) . ' rows)');
        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    // New: export multiple CSVs as a ZIP (fallback to users CSV if ZipArchive missing)
    public function exportZip()
    {
        helper('url');
        $users = model('App\\Models\\UserModel')
            ->select('id,employee_id,username,email,role,created_at')
            ->orderBy('id','ASC')->findAll(10000);

        if (class_exists('ZipArchive')) {
            $zip = new \ZipArchive();
            $tmp = tempnam(sys_get_temp_dir(), 'hms_zip_');
            $zip->open($tmp, \ZipArchive::OVERWRITE);
            // users.csv
            $fp = fopen('php://temp', 'w');
            fputcsv($fp, ['id','employee_id','username','email','role','created_at']);
            foreach ($users as $r) {
                fputcsv($fp, [$r['id'], $r['employee_id'] ?? '', $r['username'], $r['email'] ?? '', $r['role'] ?? '', $r['created_at'] ?? '']);
            }
            rewind($fp); $usersCsv = stream_get_contents($fp); fclose($fp);
            $zip->addFromString('users.csv', $usersCsv);
            // placeholder.csv
            $zip->addFromString('placeholder.csv', "This is a placeholder for future backups\n");
            $zip->close();

            $filename = 'backup_' . date('Ymd_His') . '.zip';
            $data = file_get_contents($tmp);
            @unlink($tmp);
            AuditLogger::log('backup_export_zip', 'Exported ZIP with users.csv and placeholder.csv');
            return $this->response
                ->setHeader('Content-Type', 'application/zip')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($data);
        }

        // Fallback: return users CSV
        $fp = fopen('php://temp', 'w');
        fputcsv($fp, ['id','employee_id','username','email','role','created_at']);
        foreach ($users as $r) {
            fputcsv($fp, [$r['id'], $r['employee_id'] ?? '', $r['username'], $r['email'] ?? '', $r['role'] ?? '', $r['created_at'] ?? '']);
        }
        rewind($fp); $csv = stream_get_contents($fp); fclose($fp);
        $filename = 'users_export_' . date('Ymd_His') . '.csv';
        AuditLogger::log('backup_export_csv_fallback', 'ZipArchive missing, returned users CSV');
        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }
}
