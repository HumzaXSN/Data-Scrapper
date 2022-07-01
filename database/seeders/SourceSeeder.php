<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sources = [
            [
                'name' => 'N/A',
            ],
            [
                'name' => 'USA Directories',
            ],
            [
                'name' => 'Google Listing',
            ],
            [
                'name' => 'LinkedIn',
            ],
            [
                'name' => 'Indeed',
            ],
            [
                'name' => 'Glassdoor',
            ],
        ];

        foreach ($sources as $source) {
            Source::create([
                'name' => $source['name'],
            ]);
        }
    }
}
