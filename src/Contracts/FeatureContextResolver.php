<?php

namespace Imran\RuntimeFeatureToggle\Contracts;

interface FeatureContextResolver
{
    /**
     * Resolve the current context.
     */
    public function resolve(): mixed;
}
