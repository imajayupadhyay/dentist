<?php

namespace Database\Seeders;

use App\Models\SiteHeader;
use Illuminate\Database\Seeder;

class SiteHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteHeader::query()->updateOrCreate(
            ['key' => SiteHeader::KEY],
            SiteHeader::defaultAttributes(),
        );
    }
}
