# Laravel Model Settings

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pkalusek/laravel-model-settings.svg?style=flat-square)](https://packagist.org/packages/pkalusek/laravel-model-settings)
[![Total Downloads](https://img.shields.io/packagist/dt/pkalusek/laravel-model-settings.svg?style=flat-square)](https://packagist.org/packages/pkalusek/laravel-model-settings)

The simplest way to store model-specific settings/preferences in Laravel. This package allows you to easily attach customizable settings to any Eloquent model.

## Installation

You can install the package via composer:

```bash
composer require pkalusek/laravel-model-settings
```

The package will automatically register itself via Laravel's package discovery.

Publish and run the migrations:

```bash
php artisan vendor:publish --tag="laravel-model-settings-migrations"
php artisan migrate
```

## Usage

Add the `HasSettings` trait to any model you want to have settings:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Pkalusek\LaravelModelSettings\Traits\HasSettings;

class User extends Model
{
    use HasSettings;
    
    // ... your model code
}
```

Now you can easily get, set, and manage settings for your model:

```php
$user = User::find(1);

// Set a setting
$user->settings->set('theme', 'dark');
$user->settings->set('notifications.email', true);
$user->settings->set('preferences.language', 'en');

// Get a setting
$theme = $user->settings->get('theme'); // 'dark'
$emailNotifications = $user->settings->get('notifications.email'); // true

// Get with default value
$language = $user->settings->get('preferences.language', 'en');

// Remove a setting
$user->settings->forget('theme');
$user->settings->forget('notifications.email');
```

## Features

- **Simple API**: Easy-to-use methods for getting, setting, and removing settings
- **Nested Settings**: Support for dot notation for nested settings (e.g., `notifications.email`)
- **Default Values**: Get settings with fallback default values
- **Automatic Cleanup**: Empty nested objects are automatically removed
- **JSON Storage**: Settings are stored as JSON in the database for flexibility
- **Polymorphic Relations**: Each model instance has its own settings record

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Phillip Kalusek](https://github.com/pkalusek)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
