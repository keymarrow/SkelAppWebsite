<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'cms:backup-database';

    protected $description = 'Create a compressed database backup and prune old backups.';

    public function handle(): int
    {
        $connection = Config::get('database.default');
        $disk = (string) config('cms.backup_disk', 'local');
        $path = trim((string) config('cms.backup_path', 'backups/database'), '/');
        $timestamp = now()->format('Ymd_His');
        $extension = $connection === 'sqlite' ? 'sqlite.gz' : 'sql.gz';
        $filename = "{$path}/{$connection}_{$timestamp}.{$extension}";

        $dump = match ($connection) {
            'sqlite' => $this->dumpSqlite(),
            'mysql' => $this->dumpMysql(),
            'pgsql' => $this->dumpPostgres(),
            default => throw new \RuntimeException("Database backups are not implemented for [{$connection}]."),
        };

        Storage::disk($disk)->put($filename, gzencode($dump, 9));
        $this->pruneBackups($disk, $path);

        $this->info("Database backup saved to {$disk}:{$filename}");

        return self::SUCCESS;
    }

    private function dumpSqlite(): string
    {
        $database = (string) Config::get('database.connections.sqlite.database');

        if (! is_file($database)) {
            throw new \RuntimeException("SQLite database file not found at [{$database}].");
        }

        return file_get_contents($database) ?: '';
    }

    private function dumpMysql(): string
    {
        $connection = Config::get('database.connections.mysql');

        $command = [
            'mysqldump',
            '--single-transaction',
            '--quick',
            '--lock-tables=false',
            "--host={$connection['host']}",
            "--port={$connection['port']}",
            "--user={$connection['username']}",
        ];

        if (! empty($connection['password'])) {
            $command[] = "--password={$connection['password']}";
        }

        $command[] = $connection['database'];

        return $this->runDumpProcess($command);
    }

    private function dumpPostgres(): string
    {
        $connection = Config::get('database.connections.pgsql');

        $command = [
            'pg_dump',
            '--no-owner',
            '--no-privileges',
            "--host={$connection['host']}",
            "--port={$connection['port']}",
            "--username={$connection['username']}",
            $connection['database'],
        ];

        return $this->runDumpProcess($command, [
            'PGPASSWORD' => (string) ($connection['password'] ?? ''),
        ]);
    }

    private function runDumpProcess(array $command, array $env = []): string
    {
        $process = new Process($command, base_path(), array_filter($env, fn ($value) => $value !== ''));
        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput() ?: $process->getOutput() ?: 'Database dump failed.');
        }

        return $process->getOutput();
    }

    private function pruneBackups(string $disk, string $path): void
    {
        $retentionDays = max(1, (int) config('cms.backup_retention_days', 14));
        $cutoff = now()->subDays($retentionDays)->getTimestamp();

        collect(Storage::disk($disk)->files($path))
            ->filter(fn (string $file) => Storage::disk($disk)->lastModified($file) < $cutoff)
            ->each(fn (string $file) => Storage::disk($disk)->delete($file));
    }
}
