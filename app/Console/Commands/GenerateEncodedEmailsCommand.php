<?php

namespace App\Console\Commands;

use App\Models\Contact;
use Illuminate\Console\Command;

class GenerateEncodedEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:encodedemails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create a new encoded emails of all the emails in Database';

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
        $contacts = Contact::all();
        foreach ($contacts as $contact) {
            $contact->unsub_link = base64_encode($contact->email);
            $contact->save();
        }
    }
}
