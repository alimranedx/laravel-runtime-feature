<?php

namespace Imran\RuntimeFeatureToggle\Contracts;

interface FeatureRepositoryInterface
{
    /**
     * Get a feature by its key.
     *
     * @return mixed
     */
    public function findByKey(string $key);

    /**
     * Clear the cache for a specific feature or all features.
     */
    public function clearCache(?string $key = null): void;
}
