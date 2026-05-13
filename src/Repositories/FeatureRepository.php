<?php

namespace Imran\LaravelRuntimeFeature\Repositories;

use Imran\LaravelRuntimeFeature\Contracts\FeatureRepositoryInterface;
use Imran\LaravelRuntimeFeature\Models\Feature;
use Imran\LaravelRuntimeFeature\Services\FeatureCacheService;

class FeatureRepository implements FeatureRepositoryInterface
{
    public function __construct(protected FeatureCacheService $cache)
    {
    }

    /**
     * Get a feature by its key.
     *
     * @param string $key
     * @return Feature|null
     */
    public function findByKey(string $key): ?Feature
    {
        return $this->cache->get($key);
    }

    /**
     * Clear the cache for features.
     *
     * @param string|null $key
     * @return void
     */
    public function clearCache(?string $key = null): void
    {
        $this->cache->invalidate();
    }
}
