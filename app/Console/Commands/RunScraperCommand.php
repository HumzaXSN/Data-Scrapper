<?php

namespace App\Console\Commands;

use App\Models\ScraperCriteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunScraperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:spider-scraper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will run the google businesses scraper';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $getData = ScraperCriteria::where('status', 'Active')->get();
        if ($getData->count() > 0) {
            Artisan::call('run:google-businesses-scraper', [
                'keyword' => $getData[0]->keyword,
                'city' => $getData[0]->location,
                'limit' => $getData[0]->limit,
                'criteriaId' => $getData[0]->id
            ]);
        }
    }
}
