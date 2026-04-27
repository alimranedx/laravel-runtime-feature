<?php

namespace Imran\LaravelRuntimeFeature\Services;

use Imran\LaravelRuntimeFeature\Contracts\FeatureContextResolver;
use Imran\LaravelRuntimeFeature\Contracts\FeatureRepositoryInterface;
use Imran\LaravelRuntimeFeature\Extensions\ConditionRegistry;
use Illuminate\Support\Traits\Macroable;

class FeatureManager
{
    use Macroable;

    public function __construct(
        protected FeatureRepositoryInterface $repository,
        protected ConditionEvaluator $evaluator,
        protected ConditionRegistry $registry,
        protected FeatureContextResolver $resolver
    ) {}

    /**
     * Determine if a feature is enabled.
     *
     * @param string $key
     * @param mixed|null $context
     * @return bool
     */
    public function enabled(string $key, $context = null): bool
    {
        $feature = $this->repository->findByKey($key);

        if (!$feature) {
            return false;
        }

        if (!$feature->enabled) {
            return false;
        }

        if ($feature->rules->isEmpty()) {
            return true;
        }

        $context = $context ?? $this->resolver->resolve();

        return $this->evaluator->evaluate($feature->rules, $context);
    }

    /**
     * Get the value of a feature.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function value(string $key, $default = null)
    {
        $feature = $this->repository->findByKey($key);

        return $feature ? ($feature->value ?? $default) : $default;
    }

    /**
     * Register a custom condition.
     *
     * @param string $type
     * @param string $callback
     * @return void
     */
    public function extend(string $type, string $callback): void
    {
        $this->registry->register($type, $callback);
    }

    /**
     * Clear the feature cache.
     *
     * @param string|null $key
     * @return void
     */
    public function clearCache(?string $key = null): void
    {
        $this->repository->clearCache($key);
    }
}
