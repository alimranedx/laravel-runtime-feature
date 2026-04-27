<?php

namespace Imran\LaravelRuntimeFeature\Repositories;

use Imran\LaravelRuntimeFeature\Contracts\FeatureRepositoryInterface;
use Imran\LaravelRuntimeFeature\Models\Feature;
use Illuminate\Support\Facades\Cache;

class FeatureRepository implements FeatureRepositoryInterface
{
    protected string $cachePrefix;
    protected int $ttl;
    protected ?string $store;

    public function __construct()
    {
        $this->cachePrefix = config('feature.cache.prefix', 'laravel-runtime-feature:');
        $this->ttl = (int) config('feature.cache.ttl', 3600);
        $this->store = config('feature.cache.store');
    }

    /**
     * Get a feature by its key.
     *
     * @param string $key
     * @return Feature|null
     */
    public function findByKey(string $key): ?Feature
    {
        $cacheKey = $this->cachePrefix . $key;
        $cached = Cache::store($this->store)->get($cacheKey);

        // If we have a cached object, verify it is actually a Feature instance
        // This prevents "__PHP_Incomplete_Class" errors if namespaces change
        if ($cached !== null && !($cached instanceof Feature)) {
            Cache::store($this->store)->forget($cacheKey);
            $cached = null;
        }

        if ($cached !== null) {
            return $cached;
        }

        $feature = Feature::with('rules')->where('key', $key)->first();

        if ($feature) {
            Cache::store($this->store)->put($cacheKey, $feature, $this->ttl);
        }

        return $feature;
    }

    /**
     * Clear the cache for a specific feature or all features.
     *
     * @param string|null $key
     * @return void
     */
    public function clearCache(?string $key = null): void
    {
        if ($key) {
            Cache::store($this->store)->forget($this->cachePrefix . $key);
        } else {
            // Note: This only works if the cache store supports tags or if we have a way to track all keys.
            // For simplicity in this base version, we assume manual clearing or short TTL.
            // A more robust implementation would use cache tags.
            Cache::store($this->store)->flush();
        }
    }
}
