<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Source;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //php artisan db:seed --class=SourceSeeder
    public function run()
    {
        // List of sources to be seeded
        $sources = ['ny_times', 'news_api', 'guardian'];

        // Seed each source
        foreach ($sources as $sourceName) {
            // Check if the source already exists in the database
            $existingSource = Source::where('source', $sourceName)->first();

            // If the source doesn't exist, create a new record
            if (!$existingSource) {
                Source::create(['source' => $sourceName]);
                $this->command->info("Source '{$sourceName}' seeded successfully.");
            } else {
                $this->command->info("Source '{$sourceName}' already exists in the database. Skipped seeding.");
            }
        }
    }
}
