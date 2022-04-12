<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(LeadStatusSeeder::class);
        $this->call(IndustrySeeder::class);
        $this->call(ListTypeSeeder::class);
        $this->call(ListSeeder::class);
    }
}
