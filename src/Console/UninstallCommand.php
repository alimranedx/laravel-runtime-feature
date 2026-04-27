<?php

namespace Imran\LaravelRuntimeFeature\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class UninstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:uninstall {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup and uninstall the Laravel Runtime Feature package';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting uninstall process for Laravel Runtime Feature...');

        if (!$this->confirm('This will delete all feature flags, rules, and configuration. Are you sure?', false)) {
            $this->warn('Uninstall cancelled.');
            return 1;
        }

        // 1. Rollback migrations (Drop tables)
        $this->dropTables();

        // 2. Remove published config
        $this->removeConfig();

        // 3. Remove published migrations
        $this->removeMigrations();

        $this->info('Cleanup completed successfully.');
        $this->comment('You can now safely run: composer remove al_imran/laravel-runtime-feature');

        return 0;
    }

    protected function dropTables(): void
    {
        $this->task('Dropping database tables', function () {
            Schema::dropIfExists('feature_rules');
            Schema::dropIfExists('features');
            
            // Clean up migration records from the migrations table
            \DB::table('migrations')
                ->where('migration', 'like', '%_create_features_table')
                ->orWhere('migration', 'like', '%_create_feature_rules_table')
                ->delete();
        });
    }

    protected function removeConfig(): void
    {
        $configPath = config_path('feature.php');

        if (File::exists($configPath)) {
            $this->task('Removing configuration file', function () use ($configPath) {
                File::delete($configPath);
            });
        }
    }

    protected function removeMigrations(): void
    {
        $this->task('Removing published migration files', function () {
            $migrationFiles = File::files(database_path('migrations'));
            
            foreach ($migrationFiles as $file) {
                if (str_contains($file->getFilename(), 'create_features_table') || 
                    str_contains($file->getFilename(), 'create_feature_rules_table')) {
                    File::delete($file->getPathname());
                }
            }
        });
    }
}
