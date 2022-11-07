![logo-print-hd-transparent](https://user-images.githubusercontent.com/77644584/200294033-8c4d0980-56ba-4443-96f0-9dde0753a4df.png)

# PHP SDK for Azure Data Explorer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/reedtechus/azure-data-explorer.svg?style=flat-square)](https://packagist.org/packages/reedtechus/azure-data-explorer)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/reedtechus/azure-data-explorer/run-tests?label=tests)](https://github.com/reedtechus/azure-data-explorer/actions?query=workflow%3Atests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/reedtechus/azure-data-explorer/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/reedtechus/azure-data-explorer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/reedtechus/azure-data-explorer.svg?style=flat-square)](https://packagist.org/packages/reedtechus/azure-data-explorer)

This project is a PHP library that allows you to connect to Azure Data Explorer and execute queries.

It is not endorsed or supported by [Microsoft](https://github.com/microsoft) in any way.

## Goals

The goal of this project is to implement the [Azure Data Explorer REST API](https://learn.microsoft.com/en-us/azure/data-explorer/kusto/api/rest/) in PHP.

**Functional Milestones**

-   [ ] Authentication
-   [ ] Query
-   [ ] Management Commands
-   [ ] Query v2
-   [ ] Streaming Ingestion

## Installation

You can install the package via composer:

```bash
composer require reedtechus/azure-data-explorer
```

## Usage

```php
$skeleton = new ReedTech\AzureDataExplorer();
echo $skeleton->echoPhrase('Hello, ReedTech!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Chris Reed](https://github.com/chrisreedio)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
