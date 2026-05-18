<?php

namespace Imran\RuntimeFeatureToggle\Console;

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
    protected $description = 'Cleanup and uninstall the Runtime Feature Toggle package';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting uninstall process for Runtime Feature Toggle...');

        if (!$this->confirm('This will delete all feature flags, rules, and configuration. Are you sure?', false)) {
            $this->warn('Uninstall cancelled.');
            return 1;
        }

        // 1. Rollback migrations (Drop tables)
        $this->dropTables();

        // 2. Remove published config
        $this->removeConfig();

        // 3. Remove published views
        $this->removeViews();

        // 4. Remove published migrations
        $this->removeMigrations();

        // 5. Remove published routes
        $this->removeRoutes();

        $this->info('Cleanup completed successfully.');
        $this->comment('You can now safely run: composer remove al_imran/runtime-feature-toggle');

        return 0;
    }

    protected function dropTables(): void
    {
        $this->info('Dropping database tables...');
        Schema::dropIfExists('rtf_feature_rules');
        Schema::dropIfExists('rtf_features');
        
        \DB::table('migrations')
            ->where('migration', 'like', '%_create_features_table')
            ->orWhere('migration', 'like', '%_create_feature_rules_table')
            ->delete();
        $this->info('Tables dropped.');
    }

    protected function removeConfig(): void
    {
        $configPath = config_path('feature.php');

        if (File::exists($configPath)) {
            $this->info('Removing configuration file...');
            File::delete($configPath);
            $this->info('Config file removed.');
        }
    }

    protected function removeViews(): void
    {
        $viewsPath = resource_path('views/vendor/feature');

        if (File::isDirectory($viewsPath)) {
            $this->info('Removing published view files...');
            File::deleteDirectory($viewsPath);
            $this->info('View files removed.');
        }
    }

    protected function removeMigrations(): void
    {
        $this->info('Removing published migration files...');
        $migrationFiles = File::files(database_path('migrations'));
        
        foreach ($migrationFiles as $file) {
            if (str_contains($file->getFilename(), 'create_features_table') || 
                str_contains($file->getFilename(), 'create_feature_rules_table')) {
                File::delete($file->getPathname());
            }
        }
        $this->info('Migration files removed.');
    }

    protected function removeRoutes(): void
    {
        $routePath = base_path('routes/runtimeFeature.php');

        if (File::exists($routePath)) {
            $this->info('Removing published route file...');
            File::delete($routePath);
            $this->info('Route file removed.');
        }
    }
}
