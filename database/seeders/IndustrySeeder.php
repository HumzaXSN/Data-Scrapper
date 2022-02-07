<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = [
            [
                'name' => 'Healthcare',
            ],
            [
                'name' => 'Software House',
            ]
        ];

        foreach($industries as $industry){
            Industry::create([
                'name' => $industry['name'],
            ]);
        }
    }
}
