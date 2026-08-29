<?php

namespace Database\Seeders;

use App\Models\SiteFooter;
use Illuminate\Database\Seeder;

class SiteFooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteFooter::query()->updateOrCreate(
            ['key' => SiteFooter::KEY],
            SiteFooter::defaultAttributes(),
        );
    }
}
