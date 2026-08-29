<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SiteHeaderSeeder::class);
        $this->call(SiteFooterSeeder::class);
        $this->call(HomePageSeeder::class);
        $this->call(AboutPageSeeder::class);
        $this->call(TreatmentSeeder::class);
    }
}
