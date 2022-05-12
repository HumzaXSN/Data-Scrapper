<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\ScraperJob;
use Illuminate\Console\Command;

class GoogleBusinessScraperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:google-businesses-scraper {keyword} {city} {limit}';

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
        $lastJob = ScraperJob::latest()->first();
        $keyword = $this->argument('keyword');
        $city = $this->argument('city');
        $limit = $this->argument('limit');
        $searchQueries = array("https://www.google.com/maps/?q=" .$keyword. " in " .$city);

        if ($lastJob !== null) {

            $lastJobTime = Carbon::parse($lastJob->end_at);
            $currentTime = Carbon::parse(now());
            $duration = $lastJobTime->diffInMinutes($currentTime);

            if((int)$duration >= 20)
            {
                if($lastJob !== null) {
                    if(in_array($lastJob->url ,$searchQueries))
                    {
                        $i = array_search($lastJob->url, $searchQueries);
                        if($i != count($searchQueries)-1)
                        {
                            $url = $searchQueries[$i+=1];
                        }else {
                            $url = $searchQueries[0];
                        }
                    }else{
                        $url = $searchQueries[0];
                    }
                }else{
                    $url = $searchQueries[0];
                }

                $ip = request()->server('SERVER_ADDR');
                $job = ScraperJob::create([
                    'ip' => $ip,
                    'url' => $url,
                    'platform' => 'Google Business',
                    'location' => $city,
                    'keyword' => $keyword,
                ]);

                $jobId = $job->id;
                exec("node microservices/services/index.js --url="."\"{$url}\""." ".$limit. " " .$jobId);
                $job->status = 1;
                $job->end_at = now();
                $job->save();
            }else{
                return;
            }

        }else{

            $url = $searchQueries[0];

            $ip = request()->server('SERVER_ADDR');
            $job = ScraperJob::create([
                'ip' => $ip,
                'url' => $url,
                'platform' => 'Google Business',
                'location' => $city,
                'keyword' => $keyword,
            ]);

            $jobId = $job->id;
            exec("node microservices/services/index.js --url="."\"{$url}\""." ".$limit. " " .$jobId);
            $job->status = 1;
            $job->end_at = now();
            $job->save();
        }
    }
}
