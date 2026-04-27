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
        return Cache::store($this->store)->remember(
            $this->cachePrefix . $key,
            $this->ttl,
            fn () => Feature::with('rules')->where('key', $key)->first()
        );
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
