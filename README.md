# Laravel Runtime Feature

A high-performance, decoupled feature flag engine for Laravel. Dynamically control application behavior without code deployments.

## Why this package?

- **Zero-Dependency Core**: Decoupled from Auth, User models, or external permissions.
- **Dynamic Payloads**: Return complex JSON configurations, not just booleans.
- **Contextual Awareness**: Evaluate flags based on Users, Requests, IPs, or any custom entity.
- **Atomic Caching**: Cache-first evaluation with automatic invalidation on updates.
- **Management UI**: Built-in sleek dashboard for real-time feature control.

---

## Quick Start

### 1. Installation
```bash
composer require al_imran/laravel-runtime-feature
```

### 2. Setup
```bash
php artisan vendor:publish --tag="feature-config"
php artisan vendor:publish --tag="feature-migrations"
php artisan migrate
```

---

### 1. Simple On/Off Checks (`enabled`)
Use this when you just need to know if a feature is active.

```php
use Imran\LaravelRuntimeFeature\Facades\Feature;

// Returns true if 'new_ui' is enabled in the dashboard
if (Feature::enabled('new_ui')) {
    // Show the new UI
}
```

### 2. Configuration Values (`value`)
Use this to retrieve dynamic data (numbers, strings, or arrays) stored inside a feature.

```php
// If 'upload_limit' exists, it returns its value. 
// If it doesn't exist, it returns the default (50).
$limit = feature('upload_limit')->value(50); 
```

### Quick Reference
| Method | Returns | Use Case |
| :--- | :--- | :--- |
| **`enabled()`** | `true` / `false` | Is the light switch **ON**? |
| **`value()`** | `mixed` data | How **BRIGHT** is the light? |

### Practical Examples

Here is how you would typically interact with a feature named `test_access`:

```php
// 1. Check if the feature is enabled for the current user
if (feature('test_access')->enabled()) {
    // Current user has access to the test feature
}

// 2. Get the specific value/configuration stored in the feature
// If the feature stores {"role": "admin", "beta_group": 1}
$config = feature('test_access')->value();

echo $config['role']; // Output: admin

// Using the Facade instead of the helper
use Imran\LaravelRuntimeFeature\Facades\Feature;

if (Feature::enabled('test_access')) {
    $value = Feature::value('test_access');
}
```

### Contextual Evaluation
Pass any object or value to evaluate dynamic rules (e.g., target specific users or plans).

```php
// Explicit context
if (Feature::enabled('beta_access', $user)) {
    // ...
}

// Dynamic payload based on context
$discount = feature('holiday_sale')->value(['percent' => 5], $user);
```

### Middleware Protection
```php
Route::middleware('feature:premium_search')->group(function () {
    Route::get('/search/advanced', [SearchController::class, 'advanced']);
});
```

---

## Extending the Engine

### Custom Conditions
Create a class implementing `FeatureCondition` to define your own logic.

```php
namespace App\Features\Conditions;

use Imran\LaravelRuntimeFeature\Contracts\FeatureCondition;

class SubscriptionPlanCondition implements FeatureCondition
{
    /**
     * @param mixed $context Usually the User model
     * @param mixed $value The value defined in the rule (e.g., 'pro')
     */
    public function passes($context, $value): bool
    {
        return $context && $context->plan === $value;
    }
}
```

Register it in your `AppServiceProvider`:
```php
Feature::extend('plan', SubscriptionPlanCondition::class);
```

---

## Testing & Quality Assurance

### Suggested Test Cases for Developers

When implementing feature flags, ensure your test suite covers these scenarios:

1.  **Default Fallback**: Verify the application behaves correctly when a feature flag is missing from the database.
2.  **Toggle Consistency**: Assert that functionality disappears immediately when `enabled` is set to `false`.
3.  **JSON Schema Integrity**: If using JSON values, test that your code handles missing keys or malformed data gracefully.
4.  **Context Leakage**: Ensure `User A` (with feature) and `User B` (without) see different results in the same request cycle.
5.  **Cache Invalidation**: Verify that updating a feature via the UI/Database reflects in the application without manual cache clears.

### Mocking in Tests
```php
public function test_premium_feature_is_accessible()
{
    Feature::fake(['premium_access' => true]);

    $response = $this->get('/premium-zone');
    $response->assertStatus(200);
}
```

---

## Management Dashboard

Access the built-in manager at `/features-manager`. 

**Features include:**
- Real-time toggle of flags.
- JSON payload editor with validation.
- Cache invalidation tracking.

> [!IMPORTANT]
> Secure this route in production by defining custom middleware in `config/feature.php`.

---

## License
The MIT License (MIT).
