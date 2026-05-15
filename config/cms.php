<?php

return [
    'public_host' => env('CMS_PUBLIC_HOST'),
    'admin_host' => env('CMS_ADMIN_HOST'),
    'admin_prefix' => env('CMS_ADMIN_PREFIX', 'admin'),
    'backup_disk' => env('CMS_BACKUP_DISK', env('FILESYSTEM_DISK', 'local')),
    'backup_path' => env('CMS_BACKUP_PATH', 'backups/database'),
    'backup_retention_days' => (int) env('CMS_BACKUP_RETENTION_DAYS', 14),
];
