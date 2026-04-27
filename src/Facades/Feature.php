<?php

namespace Imran\LaravelRuntimeFeature\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool enabled(string $key, $context = null)
 * @method static mixed value(string $key, $default = null)
 * @method static void extend(string $type, string $callback)
 * @method static void clearCache(?string $key = null)
 * @method static void fake(array $fakes)
 *
 * @see \Imran\LaravelRuntimeFeature\Services\FeatureManager
 */
class Feature extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'feature';
    }
}
