![logo-print-hd-transparent](https://user-images.githubusercontent.com/77644584/200294033-8c4d0980-56ba-4443-96f0-9dde0753a4df.png)

# PHP SDK for Azure Data Explorer

<!-- [![GitHub Tests Action Status](https://github.com/reedtechus/azure-data-explorer/workflows/Tests/badge.svg)](https://github.com/reedtechus/azure-data-explorer/actions?query=workflow%3Arun-tests+branch%3Amain) -->

[![Latest Version on Packagist](https://img.shields.io/packagist/v/reedtechus/azure-data-explorer)](https://packagist.org/packages/reedtechus/azure-data-explorer)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/reedtechus/azure-data-explorer/Tests?label=tests)](https://github.com/reedtechus/azure-data-explorer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/reedtechus/azure-data-explorer/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/reedtechus/azure-data-explorer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/reedtechus/azure-data-explorer)](https://packagist.org/packages/reedtechus/azure-data-explorer)

This project is a PHP library that allows you to connect to Azure Data Explorer and execute queries.

> :warning: **Experimental:** This package is still in development and is not ready for production use.
>
> Breaking changes can still occur **without** a major version change until **1.0.0**.

## Goals

The goal of this project is to implement the [Azure Data Explorer REST API](https://learn.microsoft.com/en-us/azure/data-explorer/kusto/api/rest/) in PHP.

**Feature Roadmap**

-   [x] Authentication
-   [x] Query
-   [x] Streaming Ingestion
-   [ ] Management Commands
-   [ ] Query v2

## Installation

You can install the package via composer:

```bash
composer require reedtechus/azure-data-explorer
```

## Usage

```php
use ReedTech\AzureDataExplorer\AzureDataExplorerApi;

$dataExplorer = new AzureDataExplorerApi(
	'AZURE_TENANT_ID',
	'AZURE_CLIENT_ID',
	'AZURE_CLIENT_SECRET',
	'AZURE_DATA_EXPLORER_REGION',
	'AZURE_DATA_EXPLORER_CLUSTER',
);
$results = $dataExplorer->query($query);
```

This returns a `QueryResultsDTO` object (or throws an exception).

**Using the results**

```php

dump('Columns: '.implode(', ', $results->columns));
dump('Number of Results: '.count($results->data));
dump('Execution Time: '.$results->executionTime);

dump('First Row: '.print_r($results->data[0], true));
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

<!-- ## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details. -->

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Chris Reed](https://github.com/chrisreedio)
<!-- -   [All Contributors](../../contributors) -->

This package is not endorsed nor supported by [Microsoft](https://github.com/microsoft) in any way.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
