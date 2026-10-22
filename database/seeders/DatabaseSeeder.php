<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seeder: sumber → ahli waris → bagian → data DEMO.
     */
    public function run(): void
    {
        $this->call([
            SourceSeeder::class,
            HeirSeeder::class,
            ShareSeeder::class,
            DemoCalculationSeeder::class,
        ]);
    }
}
