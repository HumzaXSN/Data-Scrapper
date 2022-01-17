<?php

namespace Database\Seeders;

use App\Models\LeadStatus;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lead_status = [
            [
                'status' => 'Hot Lead',
            ],
            [
                'status' => 'Cold Lead',
            ],
            [
                'status' => 'Follow Lead',
            ],
        ];

        foreach($lead_status as $status){
            LeadStatus::create([
                'status' => $status['status'],
            ]);
        }
    }
}
