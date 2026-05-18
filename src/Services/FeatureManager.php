<?php

namespace Imran\RuntimeFeatureToggle\Services;

use Imran\RuntimeFeatureToggle\Contracts\FeatureContextResolver;
use Imran\RuntimeFeatureToggle\Contracts\FeatureRepositoryInterface;
use Imran\RuntimeFeatureToggle\Extensions\ConditionRegistry;
use Illuminate\Support\Traits\Macroable;

class FeatureManager
{
    use Macroable;

    protected array $fakes = [];

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
        if (array_key_exists($key, $this->fakes)) {
            return (bool) $this->fakes[$key];
        }

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
    public function value(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->fakes)) {
            return $this->fakes[$key];
        }

        $feature = $this->repository->findByKey($key);
        
        if(!$feature){
            return null;
        }
        if(empty($feature->value)){
            return $default;
        }
        return $feature->value;
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
     * Fake the enabled status or value of features for testing.
     *
     * @param array $fakes
     * @return void
     */
    public function fake(array $fakes): void
    {
        $this->fakes = $fakes;
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
