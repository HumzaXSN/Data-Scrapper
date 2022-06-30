<?php

namespace App\Console\Commands;

use App\Models\Lists;
use App\Models\ScraperCriteria;
use Illuminate\Console\Command;

class AddCriteriasToListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:criterias-to-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command wil add those criterias to the list that are not in the list yet';

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
        if (Lists::where('name', 'Default criteria list')->exists()) {
            $this->error('Default criteria list already exists and criterias are added to it');
        } else {
            $list = Lists::Create([
                'name' => 'Default criteria list',
                'description' => 'All the criterias contact which were not associated to any list are stored here.',
                'list_type_id' => 2,
                'user_id' => 1,
            ]);

            ScraperCriteria::where('list_id', null)->update(['list_id' => $list->id]);
            $this->info('Default criteria list created and criterias are added to it');
        }
    }
}
