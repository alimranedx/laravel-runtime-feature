# Laravel Runtime Feature

[![Latest Version on Packagist](https://img.shields.io/packagist/v/al_imran/laravel-runtime-feature.svg?style=flat-square)](https://packagist.org/packages/al_imran/laravel-runtime-feature)
[![Total Downloads](https://img.shields.io/packagist/dt/al_imran/laravel-runtime-feature.svg?style=flat-square)](https://packagist.org/packages/al_imran/laravel-runtime-feature)
[![License](https://img.shields.io/packagist/l/al_imran/laravel-runtime-feature.svg?style=flat-square)](https://packagist.org/packages/al_imran/laravel-runtime-feature)

**Laravel Runtime Feature** is a high-performance, decoupled feature flag engine for Laravel. It allows you to dynamically control application behavior and configuration at runtime without code deployments or configuration changes.

---

## Why Use This Package?

In modern software development, decoupling feature releases from code deployments is critical. This package solves several common problems:
*   **Safe Releases**: Gradually roll out features to specific users or segments.
*   **Kill Switches**: Instantly disable problematic features in production without a rollback.
*   **Dynamic Configuration**: Update logic parameters (like discount rates or API limits) via a dashboard instead of `.env` files.
*   **Zero-Dependency Core**: Unlike many other packages, it doesn't force a specific User model or Auth structure on you.

## Key Features

*   🚀 **High Performance**: Atomic caching ensures evaluation is lightning fast.
*   💎 **Sleek Management UI**: Built-in dashboard to manage flags and JSON payloads.
*   🎭 **Contextual Awareness**: Evaluate flags based on Users, IPs, Request data, or any custom entity.
*   📦 **Dynamic Payloads**: Return complex JSON configurations, not just simple booleans.
*   🛠 **Extensible**: Easily add custom condition logic (e.g., "User belongs to Beta Team").
*   🧪 **Test-Ready**: Robust mocking support for your PHPUnit suites.

---

## Installation

You can install the package via composer:

```bash
composer require al_imran/laravel-runtime-feature
```

## Setup

Run the installation command to publish the configuration, migrations, and assets:

```bash
php artisan feature:install
```

This command will:
1. Publish the `config/feature.php` file.
2. Publish and run the database migrations.
3. Prepare the package for use.

## Feature Management

This package comes with a built-in dashboard to add, edit, and maintain your feature flags without touching the database manually.

### Accessing the Dashboard
By default, the dashboard is available at:
`YOUR_APP_URL/features-manager`

From here, you can:
*   **Add New Features**: Define keys and initial JSON payloads.
*   **Toggle States**: Instantly enable or disable features globally.
*   **Manage Rules**: Add complex conditions (like User ID or Time Ranges) to control feature availability.
*   **Edit Payloads**: Update dynamic configuration values on the fly.

## Customizing & Securing the Dashboard

By default, the management dashboard is accessible at `/features-manager`. While this is convenient for development, you should secure it in production environments.

### Option 1: Using Configuration (Easiest)

The simplest way to protect the dashboard is by updating the `config/feature.php` file. You can add any middleware (like `auth` or custom admin middleware) to the `routes` array:

```php
// config/feature.php
'routes' => [
    'prefix' => 'admin/features', // Change the URL prefix if desired
    'middleware' => ['web', 'auth', 'can:manage-features'], // Add your protection here
],
```

### Option 2: Taking Full Ownership of Routes

If you need even more control (e.g., adding extra routes or changing the route structure), you can publish the routes file to your application:

```bash
php artisan vendor:publish --tag=feature-routes
```

This will create `routes/runtimeFeature.php` in your project. The package will automatically detect and use this file instead of its internal routes. You can then modify the group directly:

```php
// routes/runtimeFeature.php
Route::middleware(['web', 'auth', 'your-custom-admin-middleware'])
    ->prefix('features-manager')
    ->name('features.')
    ->group(function () {
        // Your customized routes...
    });
```

> [!IMPORTANT]
> **Security Reminder**: The developer is responsible for ensuring that the dashboard routes are properly protected. By default, only the `web` middleware is applied, which provides no authentication or authorization.

---

## Basic Usage

### 1. Simple On/Off Checks (`enabled`)
Use this to check if a feature is active.

```php
use Imran\LaravelRuntimeFeature\Facades\Feature;

// Via Facade
if (Feature::enabled('new_checkout_flow')) {
    // Show the new checkout
}

// Via Helper
if (feature('new_checkout_flow')->enabled()) {
    // Show the new checkout
}
```

### 2. Retrieving Dynamic Values (`value`)
Fetch dynamic configurations stored with your feature flag.

```php
// Returns the JSON payload from the DB. 
// If the feature is missing, it returns null.
// If the feature exists but its value is empty, it returns the default (50).
$limit = Feature::value('upload_limit', 50);

// Helper syntax
$config = feature('theme_colors')->value(['primary' => '#000']);
```

| Method | Returns | Description |
| :--- | :--- | :--- |
| `enabled()` | `bool` | Is the feature active? |
| `value()` | `mixed` | Returns the dynamic JSON payload or a default. |

---

## Advanced Usage

### Contextual Evaluation
Pass a context (e.g., a User model) to evaluate rules specifically for that entity.

```php
$user = auth()->user();

if (Feature::enabled('beta_access', $user)) {
    // This user specifically has access based on rules defined in the dashboard
}
```

### Custom Conditions
Register your own logic for rule evaluation.

```php
// In a ServiceProvider
Feature::extend('user_level', function ($context, $value) {
    return $context->level >= $value;
});
```

### Mocking for Tests
Avoid database hits in your tests by faking feature states.

```php
public function test_new_feature_is_displayed()
{
    Feature::fake([
        'new_feature' => true,
        'api_limit' => 1000
    ]);

    $this->get('/dashboard')->assertSee('New Feature');
}
```

---

## Real-World Use Cases

1.  **Feature Rollout**: Enable `new_sidebar` for 10% of users to monitor performance before a full launch.
2.  **A/B Testing**: Store different configuration values (e.g., button colors) in the `value` field to test user engagement.
3.  **Emergency Toggles**: If a 3rd-party service goes down, instantly disable the integration via the dashboard to keep the rest of the app running.

---

## Comparison: Laravel Pennant

While [Laravel Pennant](https://laravel.com/docs/pennant) is an excellent tool for user-centric feature flags, **Laravel Runtime Feature** differs in its focus:

*   **Management UI**: We provide a ready-to-use dashboard for non-technical stakeholders to manage flags.
*   **Dynamic Payloads**: We treat JSON configurations as first-class citizens, making it easier to use flags for remote config.
*   **Decoupled Context**: Our engine doesn't assume an Eloquent User model, making it easier to use in non-standard or multi-tenant applications.

---

## Testing

```bash
composer test
```

## Security

If you discover any security-related issues, please email alimran.edx@gmail.com instead of using the issue tracker.

---

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

---
**Packagist Description:**
A high-performance, decoupled feature flag engine with a management UI and dynamic JSON configuration support for Laravel.

**Keywords:**
laravel, feature-flags, remote-config, feature-toggle, runtime-configuration
