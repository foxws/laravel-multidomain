# A Laravel extension for a multi domain/tenant setting

[![Latest Version on Packagist](https://img.shields.io/packagist/v/foxws/laravel-multidomain.svg?style=flat-square)](https://packagist.org/packages/foxws/laravel-multidomain)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/foxws/laravel-multidomain/run-tests?label=tests)](https://github.com/foxws/laravel-multidomain/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/foxws/laravel-multidomain/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/foxws/laravel-multidomain/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/foxws/laravel-multidomain.svg?style=flat-square)](https://packagist.org/packages/foxws/laravel-multidomain)

## Description

This package allows a single Laravel application to work with multiple domains/tenants.

It is intended to complement a multi-tenancy package such as [spatie/laravel-multitenancy](https://github.com/spatie/laravel-multitenancy) (tested and supported), [archtechx/tenancy](https://github.com/archtechx/tenancy), etc.

It allows caching of configs, routes & views, and is made to be easy to install, as there is no need to modify the core of the Laravel Framework.

> **TIP:** See [foxws/laravel-multidomain-demo](https://github.com/foxws/laravel-multidomain-demo) for a boilerplate.

## Installation

Install the package via composer:

```bash
composer require foxws/laravel-multidomain
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="multidomain-config"
```

### Laravel Multitenancy

When using Spatie's [laravel-multitenancy](https://github.com/spatie/laravel-multitenancy), one may want to use the following task to auto register:

> **NOTE:** Please see [documentation](https://spatie.be/docs/laravel-multitenancy/v2/using-tasks-to-prepare-the-environment/creating-your-own-task) for details.

```php
<?php

namespace App\Core\Support\Multitenancy\Tasks;

use Foxws\MultiDomain\Facades\MultiDomain;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SwitchDomainTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        MultiDomain::initialize($tenant->domain);
    }

    public function forgetCurrent(): void
    {
    }
}
```

## Usage

The package will scan any subfolder (domain) located in `app\Domain` (customisable) containing a `domain.json` file.

e.g. `app\Domain\Example\domain.json`:

```json
{
    "name": "Example",
    "domain": {
        "local": "example.test",
        "staging": "example.dev",
        "production": "example.com"
    }
}
```

> **NOTE:** The `domain` array matches the environment set in `.env`, e.g. `APP_ENV=local` will use `example.test` as it's (routing) base. The `name` is used to register components, routes, views, etc.

The structure of each domain should look like this, using `app\Domain\Example` as it's root path:

| Path                   | Description                              | Cacheable |
| ---------------------- | ---------------------------------------- | --------- |
| Routes\web.php         | The domain web routes.                   | ✅        |
| Routes\api.php         | The domain api routes.                   | ✅        |
| Config\\\*.php         | The domain config files.                 | ✅        |
| Providers              | The domain providers (optional).         |           |
| Resources\Components   | The domain Blade components (optional).  | ✅        |
| Resources\Translations | The domain translation files (optional). |           |
| Resources\Views        | The domain Blade views (optional).       | ✅        |

It will register each config, routes, views, components, using the domain's namespace in lowercase, e.g. `example`.

To interact with the domain(s), one may use the following:

| Helper                          | Description                                             |
| ------------------------------- | ------------------------------------------------------- |
| `config('example.app.name')`    | Would return the name of the application.               |
| `route('example.home')`         | Would return the route to `/`.                          |
| `view('example::home')`         | Would return the `home.blade.php` located in views.     |
| `<x-example::menu-component />` | Would return the `MenuComponent` located in components. |

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/foxws/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [laravel-modules](https://github.com/nWidart/laravel-modules)
- [Spatie](https://github.com/spatie)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
