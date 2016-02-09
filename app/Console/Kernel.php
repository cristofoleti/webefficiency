<?php
namespace Webefficiency\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Webefficiency\Console\Commands\DataImporter;
use Webefficiency\Console\Commands\WebefCleanFiles;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        DataImporter::class,
        WebefCleanFiles::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('webef:import')->hourly();
        $schedule->command('webef:cleanfiles')->daily();
    }
}
