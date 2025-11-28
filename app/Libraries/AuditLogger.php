<?php
namespace App\Libraries;

class AuditLogger
{
    public static function log(string $action, string $details = ''): void
    {
        try {
            $dir = WRITEPATH . 'logs';
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
            $file = $dir . DIRECTORY_SEPARATOR . 'audit.log';
            $user = session('username') ?? session('role') ?? 'guest';
            $line = sprintf("[%s] user=%s action=%s details=%s\n", date('Y-m-d H:i:s'), (string)$user, $action, str_replace(["\r","\n"], ' ', $details));
            @file_put_contents($file, $line, FILE_APPEND);
        } catch (\Throwable $e) {
            // Swallow all errors to avoid breaking the app
        }
    }
}
