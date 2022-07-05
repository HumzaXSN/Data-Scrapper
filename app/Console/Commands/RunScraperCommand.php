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
    public function handle(ScraperCriteria $scraperCriteria)
    {
        $criterias = $scraperCriteria->where('status', 'Active')->get();
        if ($criterias->count() > 0) {
            $completedCriterias = $scraperCriteria->where([['status', 'Active'], ['daily_running', 2]])->count();
            if ($completedCriterias == $criterias->count()) {
                $scraperCriteria->where('status', 'Active')->update(['daily_running' => 0]);
                $this->info('Scraper has been reset');
            } else {
                if ($scraperCriteria->where([['status', 'Active'], ['daily_running', 1]])->count() > 0) {
                    $this->info('Google Maps Scraper is running');
                } else {
                    $getData = $scraperCriteria->where([['status', 'Active'], ['daily_running', 0]])->first();
                    $scraperCriteria->where('id', $getData->id)->update(['daily_running' => 1]);
                    if ($getData->count() > 0) {
                        Artisan::call('run:google-businesses-scraper', [
                            'keyword' => $getData->keyword,
                            'city' => $getData->location,
                            'limit' => $getData->limit,
                            'criteriaId' => $getData->id
                        ]);
                    }
                    $scraperCriteria->where('id', $getData->id)->update(['daily_running' => 2]);
                }
            }
        } else {
            $this->info('No active criteria found');
        }
    }
}
