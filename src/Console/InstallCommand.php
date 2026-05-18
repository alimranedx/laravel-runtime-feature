<?php
/*
|--------------------------------------------------------------------------
| Installation Command
|--------------------------------------------------------------------------
|
| This command handles the initial setup of the Runtime Feature Toggle package.
| It publishes configuration and migrations, and optionally runs migrations.
|
*/

namespace Imran\RuntimeFeatureToggle\Console;

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
    protected $description = 'Install the Runtime Feature Toggle package';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Installing Runtime Feature Toggle...');

        $this->info('Publishing configuration...');
        $this->call('vendor:publish', [
            '--provider' => 'Imran\RuntimeFeatureToggle\Providers\FeatureServiceProvider',
            '--tag' => 'feature-config',
        ]);

        $this->info('Publishing migrations...');
        $this->call('vendor:publish', [
            '--provider' => 'Imran\RuntimeFeatureToggle\Providers\FeatureServiceProvider',
            '--tag' => 'feature-migrations',
        ]);

        if ($this->confirm('Would you like to run the migrations now?', true)) {
            $this->info('Running migrations...');
            $this->call('migrate');
        }
        if ($this->confirm('Would you like to register runtime feature routes now?', true)) {
            $this->info('Registering runtime feature routes...');
            $this->call('vendor:publish', [
                '--provider' => 'Imran\RuntimeFeatureToggle\Providers\FeatureServiceProvider',
                '--tag' => 'feature-routes',
            ]);
        }

        $this->info('Runtime Feature Toggle installed successfully.');
    }
}
