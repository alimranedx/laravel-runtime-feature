<?php

namespace Imran\LaravelRuntimeFeature\Contracts;

interface FeatureContextResolver
{
    /**
     * Resolve the current context.
     */
    public function resolve(): mixed;
}
