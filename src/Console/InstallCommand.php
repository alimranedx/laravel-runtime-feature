<?php
/*
|--------------------------------------------------------------------------
| Installation Command
|--------------------------------------------------------------------------
|
| This command handles the initial setup of the Laravel Runtime Feature package.
| It publishes configuration and migrations, and optionally runs migrations.
|
*/

namespace Imran\LaravelRuntimeFeature\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Laravel Runtime Feature package';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Installing Laravel Runtime Feature...');

        $this->info('Publishing configuration...');
        $this->call('vendor:publish', [
            '--provider' => 'Imran\LaravelRuntimeFeature\Providers\FeatureServiceProvider',
            '--tag' => 'feature-config',
        ]);

        $this->info('Publishing migrations...');
        $this->call('vendor:publish', [
            '--provider' => 'Imran\LaravelRuntimeFeature\Providers\FeatureServiceProvider',
            '--tag' => 'feature-migrations',
        ]);

        if ($this->confirm('Would you like to run the migrations now?', true)) {
            $this->info('Running migrations...');
            $this->call('migrate');
        }

        $this->info('Laravel Runtime Feature installed successfully.');
    }
}
