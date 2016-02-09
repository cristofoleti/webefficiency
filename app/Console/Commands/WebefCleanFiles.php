<?php

namespace Webefficiency\Console\Commands;

use Illuminate\Console\Command;

class WebefCleanFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webef:cleanfiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean xls exported files';

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
     * @return mixed
     */
    public function handle()
    {
        \File::cleanDirectory(public_path('exports/excel'));

        $this->info('Export files deleted');
    }
}
