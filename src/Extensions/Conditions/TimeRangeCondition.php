<?php

namespace Imran\RuntimeFeatureToggle\Extensions\Conditions;

use Imran\RuntimeFeatureToggle\Contracts\FeatureCondition;
use Illuminate\Support\Carbon;

class TimeRangeCondition implements FeatureCondition
{
    /**
     * Determine if current time is within range.
     *
     * @param mixed $context
     * @param mixed $value
     * @return bool
     */
    public function passes($context, $value): bool
    {
        $now = Carbon::now();

        $start = isset($value['start']) ? Carbon::parse($value['start']) : null;
        $end = isset($value['end']) ? Carbon::parse($value['end']) : null;

        if ($start && $now->lt($start)) {
            return false;
        }

        if ($end && $now->gt($end)) {
            return false;
        }

        return true;
    }
}
