<?php

namespace Imran\RuntimeFeatureToggle\Services;

use Imran\RuntimeFeatureToggle\Contracts\FeatureContextResolver;

class DefaultContextResolver implements FeatureContextResolver
{
    /**
     * Resolve the current context.
     * Default implementation returns null.
     *
     * @return mixed
     */
    public function resolve(): mixed
    {
        return null;
    }
}
