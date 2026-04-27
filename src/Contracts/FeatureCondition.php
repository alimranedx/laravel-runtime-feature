<?php

namespace Imran\LaravelRuntimeFeature\Contracts;

interface FeatureCondition
{
    /**
     * Determine if the condition passes.
     *
     * @param  mixed  $context
     * @param  mixed  $value
     */
    public function passes($context, $value): bool;
}
