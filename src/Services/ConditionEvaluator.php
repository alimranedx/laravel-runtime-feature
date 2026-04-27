<?php

namespace Imran\LaravelRuntimeFeature\Services;

use Imran\LaravelRuntimeFeature\Extensions\ConditionRegistry;
use Illuminate\Support\Collection;

class ConditionEvaluator
{
    public function __construct(
        protected ConditionRegistry $registry
    ) {}

    /**
     * Evaluate a collection of feature rules.
     *
     * @param Collection $rules
     * @param mixed $context
     * @return bool
     */
    public function evaluate(Collection $rules, $context): bool
    {
        foreach ($rules as $rule) {
            $condition = $this->registry->resolve($rule->type);

            if (!$condition || !$condition->passes($context, $rule->value)) {
                return false;
            }
        }

        return true;
    }
}
