<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:command-on-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a test command.';

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
        if (Storage::exists('test.txt')) {
            Storage::append('test.txt', 'This is a test.');
        } else {
            Storage::put('test.txt', 'This is a test.');
        }
    }
}
