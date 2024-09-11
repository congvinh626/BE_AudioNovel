<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\NovelImport;

class NovelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:novel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import an Excel file into the database';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $file = $this->argument('file');
        
        // if (!file_exists($file)) {
        //     $this->error('File not found!');
        //     return;
        // }
        Excel::import(new NovelImport(), storage_path('listAudio3.xlsx'));

        $this->info('Import completed successfully!');
    }
}
