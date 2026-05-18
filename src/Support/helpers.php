<?php

use Imran\RuntimeFeatureToggle\Facades\Feature;

if (!function_exists('feature')) {
    /**
     * Get the feature manager instance or a feature helper.
     *
     * @param string|null $key
     * @return \Imran\RuntimeFeatureToggle\Services\FeatureManager|object
     */
    function feature(?string $key = null)
    {
        if (is_null($key)) {
            return app('feature');
        }

        return new class($key) {
            public function __construct(protected string $key) {}

            public function enabled($context = null): bool
            {
                return Feature::enabled($this->key, $context);
            }

            public function value($default = null)
            {
                return Feature::value($this->key, $default);
            }
        };
    }
}
