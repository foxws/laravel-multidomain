# A Laravel extension for a multi domain/tenant setting

[![Latest Version on Packagist](https://img.shields.io/packagist/v/foxws/laravel-multidomain.svg?style=flat-square)](https://packagist.org/packages/foxws/laravel-multidomain)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/foxws/laravel-multidomain/run-tests?label=tests)](https://github.com/foxws/laravel-multidomain/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/foxws/laravel-multidomain/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/foxws/laravel-multidomain/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/foxws/laravel-multidomain.svg?style=flat-square)](https://packagist.org/packages/foxws/laravel-multidomain)

## Description

This package allows a single Laravel application to work with multiple domains/tenants.

It is intended to complement a multi-tenancy package such as [spatie/laravel-multitenancy](https://github.com/spatie/laravel-multitenancy), [archtechx/tenancy](https://github.com/archtechx/tenancy), etc.

It allows caching of configs, routes & views, and is made to be easy to install, as there is no need to modify the core of the Laravel Framework.

## Installation

Install the package via composer:

```bash
composer require foxws/laravel-multidomain
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="multidomain-config"
```

## Usage

The package will scan any subfolder (domain) located in `app\Domain` containing a `domain.json` file.

e.g. `app\Domain\Example\domain.json`:

```json
{
    "name": "Example",
    "enabled": true,
    "domain": {
        "local": "example.test",
        "staging": "example.dev",
        "production": "example.com"
    }
}
```

> **NOTE:** The `domain` array matches the environment set in `.env`, e.g. `APP_ENV=local` will use `example.test` as it's base.

The structure of each domain should look like this, using `app\Domain\Example` as it's root path:

| Path | Description | Cachable |
| --- | --- | --- |
| Routes\web.php | The domain web routes. | ✅ |
| Routes\api.php | The domain api routes. | ✅ |
| Config\\*.php | The domain config files. | ✅ |
| Providers | The domain providers (optional). | |
| Resources\Translations | The domain translation files. | |
| Resources\Views | The domain Blade views. | ✅ |
| Resources\Components | The domain Blade components. | ✅ |

It will register each config, routes, views, components, using the domain's namespace in lowercase, e.g. `example`.

> **NOTE:** Service Providers will not be registred by default, see [Switch Task](#switch-task) to register at runtime.

To interact with the domain(s), one may use the following:

| Helper | Description |
| --- | --- |
| `config('example.app.name')` | Would return the name of the application. |
| `route('example.home'` | Would return the route to `/`. |
| `view('example::home')` | Would return the `home.blade.php` located in views. |
| `domain('example')` | Would return a domain instance. |
| `<x-example::menu-component />` | Would return the `MenuComponent` located in components. |

## Switch Task

When using Spatie's [laravel-multitenancy](https://github.com/spatie/laravel-multitenancy), one may want to use the following task to auto register service providers for each domain:

> **NOTE:** Please see [documentation](https://spatie.be/docs/laravel-multitenancy/v2/using-tasks-to-prepare-the-environment/creating-your-own-task) for details.

```php
namespace App\Support\Multitenancy\Tasks;

use Foxws\LaravelMultidomain\Contracts\RepositoryInterface;
use Foxws\LaravelMultidomain\Support\DomainRepository;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SwitchDomainTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        /** @var DomainRepository $repository */
        $repository = app(RepositoryInterface::class);

        if ($domain = $repository->find($tenant->domain)) {
            $domain->registerProviders();
        }
    }

    public function forgetCurrent(): void
    {
    }
}
```

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

- [foxws](https://github.com/foxws)
- [laravel-modules](https://github.com/nWidart/laravel-modules)
- [Spatie](https://github.com/spatie)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
