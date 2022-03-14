<?php

namespace Database\Seeders;

use App\Models\ListType;
use Illuminate\Database\Seeder;

class ListTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name' => 'Master Block List',
                'slug' => 'master-block-list'
            ],
            [
                'name' => 'Send Lists',
                'slug' => 'send-lists'
            ]
        ];

        foreach ($types as $type) {
            ListType::create([
                'name' => $type['name'],
                'slug' => $type['slug']
            ]);
        }
    }
}
