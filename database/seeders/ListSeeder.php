<?php

namespace Database\Seeders;

use App\Models\Lists;
use Illuminate\Database\Seeder;

class ListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lists::create([
            'name' => 'Master Block List',
            'description' => 'Contacts Stored in this list will be blocked and the emails will not be sent to them.',
            'list_type_id' => 1,
            'user_id' => 1,
        ]);
    }
}
