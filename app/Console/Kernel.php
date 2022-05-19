<?php

namespace App\Console;

use App\Models\ScraperCriteria;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\GoogleBusinessScraperCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GoogleBusinessScraperCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $getData = ScraperCriteria::where('status', 'Active')->get();
        if ($getData->count() > 0) {
            $schedule->command('run:google-businesses-scraper',[
                $getData[0]->keyword,
                $getData[0]->location,
                $getData[0]->limit,
                $getData[0]->id
            ])->dailyAt('18:52')->timezone('Asia/Karachi');
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
