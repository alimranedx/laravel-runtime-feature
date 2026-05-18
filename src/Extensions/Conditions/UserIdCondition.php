<?php

namespace Imran\RuntimeFeatureToggle\Extensions\Conditions;

use Imran\RuntimeFeatureToggle\Contracts\FeatureCondition;

class UserIdCondition implements FeatureCondition
{
    /**
     * Determine if the user ID matches.
     *
     * @param mixed $context
     * @param mixed $value
     * @return bool
     */
    public function passes($context, $value): bool
    {
        // context could be an object with an id, or a numeric id
        $id = is_object($context) ? ($context->id ?? null) : $context;

        if (is_array($value)) {
            return in_array($id, $value);
        }

        return $id == $value;
    }
}
