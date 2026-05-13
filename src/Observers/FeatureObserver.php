<?php

namespace Imran\LaravelRuntimeFeature\Observers;

use Imran\LaravelRuntimeFeature\Models\Feature;
use Imran\LaravelRuntimeFeature\Services\FeatureCacheService;

class FeatureObserver
{
    public function __construct(protected FeatureCacheService $cache)
    {
    }

    /**
     * Handle the Feature "saved" event.
     *
     * @param Feature $feature
     * @return void
     */
    public function saved(Feature $feature): void
    {
        // When a feature is saved (created or updated), we refresh its data in the cache.
        // Since we touched the feature from a rule, this will also include updated rules.
        $this->cache->updateItem($feature->load('rules'));
    }

    /**
     * Handle the Feature "deleted" event.
     *
     * @param Feature $feature
     * @return void
     */
    public function deleted(Feature $feature): void
    {
        $this->cache->removeItem($feature->id);
    }
}
