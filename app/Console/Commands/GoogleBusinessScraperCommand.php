<?php

namespace App\Console\Commands;

use App\Models\ScraperJob;
use Illuminate\Console\Command;

class GoogleBusinessScraperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:google-businesses-scraper {keyword} {city} {limit} {criteriaId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Google Businesses Scraper';

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
        $host = config('app.host');
        $port = config('app.port');
        $database = config('app.database');
        $username = config('app.username');
        $password = config('app.password');
        $keyword = $this->argument('keyword');
        $city = $this->argument('city');
        $limit = $this->argument('limit');
        $criteriaId = $this->argument('criteriaId');
        $searchQueries = array("https://www.google.com/maps/?q=" .$keyword. " in " .$city);
        $url = $searchQueries[0];
        $ip = request()->server('SERVER_ADDR');
        $job = ScraperJob::create([
            'ip' => $ip,
            'url' => $url,
            'platform' => 'Google Business',
            'scraper_criteria_id' => $criteriaId,
        ]);
        $jobId = $job->id;
        $path = base_path() . '/microservices/services/index.js >> ' . base_path() . '/microservices/services/data.log 2>> ' . base_path() . '/microservices/services/errors.log';
        exec("node " . $path." --url=" . "\"{$url}\"" . " " . $limit . " " . $jobId . " " . $criteriaId . " " . " --host=". "\"{$host}\"" . " " . $port . " " ." --database=" . "\"{$database}\"" . " " ." --username=" . "\"{$username}\"" . " " ." --password=" . "\"{$password}\"");
        $job->end_at = now();
        $job->save();
    }
}
