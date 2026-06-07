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
     * Stores plain arrays to avoid __PHP_Incomplete_Class on deserialization.
     *
     * @return Collection<int, Feature>
     */
    public function all(): Collection
    {
        $raw = Cache::store($this->store)->rememberForever($this->cacheKey, function () {
            return Feature::with('rules')->get()->map(fn(Feature $f) => array_merge(
                $f->getAttributes(),
                ['rules' => $f->rules->map(fn($r) => $r->getAttributes())->all()]
            ))->all();
        });

        // Guard: stale serialized Eloquent objects come back as non-array — bust & re-fetch.
        if (!is_array($raw)) {
            $this->invalidate();
            $raw = Feature::with('rules')->get()->map(fn(Feature $f) => array_merge(
                $f->getAttributes(),
                ['rules' => $f->rules->map(fn($r) => $r->getAttributes())->all()]
            ))->all();
            Cache::store($this->store)->forever($this->cacheKey, $raw);
        }

        return $this->hydrate($raw);
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
        $raw = Cache::store($this->store)->get($this->cacheKey, []);

        // Guard: stale/corrupt cache value — fall back to empty array.
        if (!is_array($raw)) {
            $raw = [];
        }

        $index = collect($raw)->search(fn($item) => is_array($item) && $item['id'] === $feature->id);

        $snapshot = array_merge(
            $feature->getAttributes(),
            ['rules' => $feature->rules->map(fn($r) => $r->getAttributes())->all()]
        );

        if ($index !== false) {
            $raw[$index] = $snapshot;
        } else {
            $raw[] = $snapshot;
        }

        Cache::store($this->store)->forever($this->cacheKey, array_values($raw));
    }

    /**
     * Remove an individual item from the cached collection.
     *
     * @param int $featureId
     * @return void
     */
    public function removeItem(int $featureId): void
    {
        $cached = Cache::store($this->store)->get($this->cacheKey, []);

        // Guard: stale/corrupt cache value — fall back to empty array.
        if (!is_array($cached)) {
            $cached = [];
        }

        $raw = collect($cached)
            ->reject(fn($item) => is_array($item) && $item['id'] === $featureId)
            ->values()
            ->all();

        Cache::store($this->store)->forever($this->cacheKey, $raw);
    }

    /**
     * Hydrate a plain array back into a Collection of Feature models.
     *
     * @param array $rows
     * @return Collection<int, Feature>
     */
    protected function hydrate(array $rows): Collection
    {
        return collect($rows)->map(function (array $row) {
            $rules = $row['rules'] ?? [];
            unset($row['rules']);

            $feature = (new Feature())->newFromBuilder($row);

            $feature->setRelation(
                'rules',
                $feature->rules()->getRelated()->newCollection(
                    array_map(
                        fn(array $rule) => $feature->rules()->getRelated()->newFromBuilder($rule),
                        $rules
                    )
                )
            );

            return $feature;
        });
    }
}
