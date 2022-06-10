<?php

namespace App\Console\Commands;

use App\Models\DecisionMaker;
use Illuminate\Console\Command;

class DecisionMakerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:decision-makers-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will scrap the results obtained from the Google Business Scraper';

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
        $path = base_path() . '/microservices/services/scrap-data.js >> ' . base_path() . '/microservices/services/data.log 2>> ' . base_path() . '/microservices/services/errors.log';
        exec("node " . $path . " --host=" . "\"{$host}\"" . " " . $port . " " . " --database=" . "\"{$database}\"" . " " . " --username=" . "\"{$username}\"" . " " . " --password=" . "\"{$password}\"");
        DecisionMaker::where('name', null)->forceDelete();
    }
}
