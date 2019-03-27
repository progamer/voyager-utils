<?php

namespace Codept\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class BackupBreadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bread:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate seeders for bread related tables';

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
        Artisan::call('iseed', [
            'tables' => implode(",", config('core.backup.tables')),
            '--classnameprefix' => config('core.backup.classnameprefix'),
            '--force' => config('core.backup.force')
        ]);
    }
}
