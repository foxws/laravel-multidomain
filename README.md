# A Laravel extension for using a Laravel application on a multi domain setting

[![Latest Version on Packagist](https://img.shields.io/packagist/v/foxws/laravel-multidomain.svg?style=flat-square)](https://packagist.org/packages/foxws/laravel-multidomain)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/foxws/laravel-multidomain/run-tests?label=tests)](https://github.com/foxws/laravel-multidomain/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/foxws/laravel-multidomain/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/foxws/laravel-multidomain/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/foxws/laravel-multidomain.svg?style=flat-square)](https://packagist.org/packages/foxws/laravel-multidomain)

## Installation

You can install the package via composer:

```bash
composer require foxws/laravel-multidomain
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="multidomain-config"
```

## Usage

Create a `domain.json` file in each domain directory.

e.g. `App\Domain\Example\domain.json`:

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

When using Spatie's [laravel-multitenancy](https://github.com/spatie/laravel-multitenancy), one may want to use the following task to auto register service providers for each domain:

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
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
