<?php

namespace Imran\RuntimeFeatureToggle\Services;

use Imran\RuntimeFeatureToggle\Models\Feature;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class FeatureCacheService
{
    protected string $cacheKey;
    protected ?string $store;

    public function __construct()
    {
        $this->cacheKey = config('feature.cache.prefix', 'runtime-feature-toggle:') . 'all';
        $this->store = config('feature.cache.store');
    }

    /**
     * Get all features from cache or database.
     *
     * @return Collection<int, Feature>
     */
    public function all(): Collection
    {
        return Cache::store($this->store)->rememberForever($this->cacheKey, function () {
            return Feature::with('rules')->get();
        });
    }

    /**
     * Get a specific feature by its key.
     *
     * @param string $key
     * @return Feature|null
     */
    public function get(string $key): ?Feature
    {
        return $this->all()->firstWhere('key', $key);
    }

    /**
     * Invalidate the features cache.
     *
     * @return void
     */
    public function invalidate(): void
    {
        Cache::store($this->store)->forget($this->cacheKey);
    }

    /**
     * Force a refresh of the cache from the database.
     *
     * @return Collection
     */
    public function refresh(): Collection
    {
        $this->invalidate();
        return $this->all();
    }

    /**
     * Update an individual item in the cached collection.
     *
     * @param Feature $feature
     * @return void
     */
    public function updateItem(Feature $feature): void
    {
        $features = $this->all();
        
        $index = $features->search(fn($item) => $item->id === $feature->id);
        
        if ($index !== false) {
            $features->put($index, $feature);
        } else {
            $features->push($feature);
        }

        Cache::store($this->store)->forever($this->cacheKey, $features);
    }

    /**
     * Remove an individual item from the cached collection.
     *
     * @param int $featureId
     * @return void
     */
    public function removeItem(int $featureId): void
    {
        $features = $this->all()->reject(fn($item) => $item->id === $featureId);
        Cache::store($this->store)->forever($this->cacheKey, $features);
    }
}
