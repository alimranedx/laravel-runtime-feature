<?php

namespace Imran\RuntimeFeatureToggle\Repositories;

use Imran\RuntimeFeatureToggle\Contracts\FeatureRepositoryInterface;
use Imran\RuntimeFeatureToggle\Models\Feature;
use Imran\RuntimeFeatureToggle\Services\FeatureCacheService;

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
