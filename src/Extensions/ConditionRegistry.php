<?php

namespace Imran\RuntimeFeatureToggle\Extensions;

use Imran\RuntimeFeatureToggle\Contracts\FeatureCondition;
use Illuminate\Contracts\Container\Container;

class ConditionRegistry
{
    protected array $conditions = [];

    public function __construct(protected Container $container) {}

    /**
     * Register a condition.
     *
     * @param string $type
     * @param string $className
     * @return void
     */
    public function register(string $type, string $className): void
    {
        $this->conditions[$type] = $className;
    }

    /**
     * Resolve a condition instance.
     *
     * @param string $type
     * @return FeatureCondition|null
     */
    public function resolve(string $type): ?FeatureCondition
    {
        if (!isset($this->conditions[$type])) {
            return null;
        }

        return $this->container->make($this->conditions[$type]);
    }
}
