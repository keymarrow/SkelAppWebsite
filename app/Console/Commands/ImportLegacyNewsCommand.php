<?php

namespace App\Console\Commands;

use App\Services\LegacyNewsImporter;
use Illuminate\Console\Command;

class ImportLegacyNewsCommand extends Command
{
    protected $signature = 'news:import-legacy {--overwrite : Replace existing DB posts that share legacy slugs}';

    protected $description = 'Import legacy config/news.php articles into the CMS database.';

    public function handle(LegacyNewsImporter $importer): int
    {
        $count = $importer->import((bool) $this->option('overwrite'));

        $this->info("Imported {$count} legacy news post(s).");

        return self::SUCCESS;
    }
}
