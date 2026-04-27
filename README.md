# Laravel Runtime Feature

A production-ready, decoupled runtime feature engine for Laravel. Dynamically enable/disable features, evaluate them based on custom context, and return dynamic values without re-deploying.

## Features

- ✅ **Decoupled**: No dependencies on Auth, User models, or permission packages.
- ✅ **Dynamic Values**: Supports JSON values for features, not just booleans.
- ✅ **Extensible**: Custom condition rules via a registry system.
- ✅ **Context-Aware**: Evaluate features based on any context (User, Request, IP, etc.).
- ✅ **High Performance**: Cache-first architecture with customizable TTL.
- ✅ **Developer Friendly**: Facades, helpers, and middleware included.

## Installation

You can install the package via composer:

```bash
composer require al_imran/laravel-runtime-feature
```

Then, publish the config and migrations:

```bash
php artisan vendor:publish --tag="feature-config"
php artisan vendor:publish --tag="feature-migrations"
php artisan migrate
```

## Usage

### Basic Check
```php
if (Feature::enabled('new_checkout')) {
    // Show new checkout
}

// Or using the helper
if (feature('new_checkout')->enabled()) {
    // ...
}
```

### Dynamic Values
```php
$discount = Feature::value('holiday_discount', 10);
// Or
$config = feature('ui_theme')->value(['color' => 'blue']);
```

### Contextual Evaluation
You can pass a context (e.g., a User object or ID) to evaluate rules:

```php
if (Feature::enabled('beta_access', $user)) {
    // ...
}
```

## Extensibility

### Custom Conditions
Create a class implementing `FeatureCondition`:

```php
namespace App\Features\Conditions;

use Imran\LaravelRuntimeFeature\Contracts\FeatureCondition;

class PlanCondition implements FeatureCondition
{
    public function passes($context, $value): bool
    {
        return $context->plan === $value;
    }
}
```

Register it in your `AppServiceProvider`:

```php
use Imran\LaravelRuntimeFeature\Facades\Feature;
use App\Features\Conditions\PlanCondition;

public function boot()
{
    Feature::extend('plan', PlanCondition::class);
}
```

### Custom Context Resolver
If you don't want to pass context manually, register a resolver in `config/feature.php`:

```php
'resolver' => \App\Features\MyContextResolver::class,
```

## Middleware
Protect routes with the included middleware:

```php
Route::middleware('feature:beta_access')->group(function () {
    Route::get('/beta-dashboard', ...);
});
```

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
