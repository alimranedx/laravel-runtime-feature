<?php

use Imran\LaravelRuntimeFeature\Extensions\Conditions\TimeRangeCondition;
use Imran\LaravelRuntimeFeature\Extensions\Conditions\UserIdCondition;
use Imran\LaravelRuntimeFeature\Services\DefaultContextResolver;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache store that will be used to store
    | feature configurations. You may use any of the cache stores defined
    | in your application's cache configuration file.
    |
    */
    'cache' => [
        'store' => env('FEATURE_CACHE_STORE', 'file'),
        'prefix' => 'laravel-runtime-feature:',
        'ttl' => 3600, // 1 hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Context Resolver
    |--------------------------------------------------------------------------
    |
    | This class is responsible for resolving the current context (e.g. User,
    | Request) when no explicit context is provided to the Feature::enabled() method.
    | Must implement \Imran\LaravelRuntimeFeature\Contracts\FeatureContextResolver.
    |
    */
    'resolver' => DefaultContextResolver::class,

    /*
    |--------------------------------------------------------------------------
    | Built-in Conditions
    |--------------------------------------------------------------------------
    |
    | Here you may register built-in conditions that are available out of the box.
    |
    */
    'conditions' => [
        'user_id' => UserIdCondition::class,
        'time_range' => TimeRangeCondition::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | These options control the registration of the feature management routes.
    | You can specify the prefix and middleware that should be applied to
    | the dashboard routes.
    |
    */
    'routes' => [
        'prefix' => env('FEATURE_ROUTE_PREFIX', 'features-manager'),
        'middleware' => ['web'],
    ],
];
