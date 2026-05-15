<?php

namespace Database\Seeders;

use App\Services\LegacyNewsImporter;
use Illuminate\Database\Seeder;

class LegacyNewsSeeder extends Seeder
{
    public function run(): void
    {
        app(LegacyNewsImporter::class)->import();
    }
}
