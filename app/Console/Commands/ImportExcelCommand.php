<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\ContactsImport;

class ImportExcelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excel:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laravel Excel importer';

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
        $file = storage_path('app\import\contacts.csv');;
        $import = new ContactsImport(0, 6);
        $this->output->title('Starting import');
        $import->withOutput($this->output)->import($file);
        $this->output->success('Import successful');
    }
}
