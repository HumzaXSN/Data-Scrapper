<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DecisionMakersEmails;

class DecisionMakerEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:decision-makers-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will genrate the emails for the decision makers';

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
        $path = base_path() . '/microservices/services/email-guesser.js >> ' . base_path() . '/microservices/services/data.log 2>> ' . base_path() . '/microservices/services/errors.log';
        exec("node " . $path . " --host=" . "\"{$host}\"" . " " . $port . " " . " --database=" . "\"{$database}\"" . " " . " --username=" . "\"{$username}\"" . " " . " --password=" . "\"{$password}\"");
        DecisionMakersEmails::where('email', null)->forceDelete();
    }
}
