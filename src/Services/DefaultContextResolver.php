<?php

namespace Imran\LaravelRuntimeFeature\Services;

use Imran\LaravelRuntimeFeature\Contracts\FeatureContextResolver;

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
