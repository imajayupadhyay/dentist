<?php

namespace Database\Seeders;

use App\Models\HomePage;
use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    /**
     * Seed the current static homepage content into the CMS table.
     */
    public function run(): void
    {
        HomePage::query()->updateOrCreate(
            ['key' => HomePage::KEY],
            HomePage::defaultAttributes(),
        );
    }
}
