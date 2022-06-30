<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Lists;
use Illuminate\Console\Command;

class AddContactsToListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:contacts-to-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command wil add those contacts to the list that are not in the list yet';

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
        if (Lists::where('name', 'Sales Force Old Data')->exists()) {
            $this->error('Sales Force Old Data list already exists and contacts are added to it');
        } else {
            $this->info('PLease wait while the contacts are being added');
            Lists::Create([
                'name' => 'Sales Force Old Data',
                'description' => 'All the contacts which were not associated to any list are stored here.',
                'list_type_id' => 2,
                'user_id' => 1,
            ]);

            Contact::where('list_id', null)->update(['list_id' => Lists::latest()->first()->id]);
            $this->info('Sales Force Old Data list created and contacts are added to it');
        }
    }
}
