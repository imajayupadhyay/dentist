<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    /**
     * Seed the current static About page content into the CMS table.
     */
    public function run(): void
    {
        AboutPage::query()->updateOrCreate(
            ['key' => AboutPage::KEY],
            AboutPage::defaultAttributes(),
        );
    }
}
