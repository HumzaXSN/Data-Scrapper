<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\ContactsImport;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Storage;

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
    // {
    //     $file = $this->ask('Enter the file name');
    //     $listId = $this->ask('Enter the list id');
    //     $source = $this->ask('Enter the source');
    //     $file = Storage::disk('local')->get($file);
    //     $import = new ContactsImport($source, $listId);
    //     $import->import($file, 'csv', \Maatwebsite\Excel\Excel::CSV);
    //     $this->info('Successfully imported '.$import->success_rows.' rows');
    // }
    {
        $file = storage_path('app\import\contacts.csv');;
        $import = new ContactsImport(0, 6);
        $this->output->title('Starting import');
        $import->withOutput($this->output)->import($file);
        $this->output->success('Import successful');
    }
}
