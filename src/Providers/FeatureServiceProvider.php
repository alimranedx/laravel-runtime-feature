<?php

namespace Imran\LaravelRuntimeFeature\Providers;

use Imran\LaravelRuntimeFeature\Contracts\FeatureContextResolver;
use Imran\LaravelRuntimeFeature\Contracts\FeatureRepositoryInterface;
use Imran\LaravelRuntimeFeature\Extensions\ConditionRegistry;
use Imran\LaravelRuntimeFeature\Repositories\FeatureRepository;
use Imran\LaravelRuntimeFeature\Services\ConditionEvaluator;
use Imran\LaravelRuntimeFeature\Services\FeatureManager;
use Illuminate\Support\ServiceProvider;

class FeatureServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/feature.php',
            'feature'
        );

        $this->app->singleton(ConditionRegistry::class, function ($app) {
            $registry = new ConditionRegistry($app);
            
            foreach (config('feature.conditions', []) as $type => $class) {
                $registry->register($type, $class);
            }

            return $registry;
        });

        $this->app->singleton(FeatureRepositoryInterface::class, FeatureRepository::class);

        $this->app->singleton(FeatureContextResolver::class, function ($app) {
            $class = config('feature.resolver');
            return new $class();
        });

        $this->app->singleton('feature', function ($app) {
            return new FeatureManager(
                $app->make(FeatureRepositoryInterface::class),
                $app->make(ConditionEvaluator::class),
                $app->make(ConditionRegistry::class),
                $app->make(FeatureContextResolver::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/feature.php' => config_path('feature.php'),
            ], 'feature-config');

            $this->publishes([
                __DIR__ . '/../../database/migrations/' => database_path('migrations'),
            ], 'feature-migrations');

            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'feature');

        // Load helpers
        require_once __DIR__ . '/../Support/helpers.php';
    }
}
