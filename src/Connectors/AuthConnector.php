<?php

namespace ReedTech\AzureDataExplorer\Connectors;

use Sammyjo20\Saloon\Http\SaloonConnector;
use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;

/**
 * These methods are 'magic' methods
 *
 * @method AuthenticationRequest fetchToken
 */
class AuthConnector extends SaloonConnector
{
	use AcceptsJson;

	protected array $requests = ['fetchToken' => AuthenticationRequest::class];

	public function __construct(
		protected string $apiBaseUrl = 'https://login.microsoftonline.com'
	) {
	}

	/**
	 * The Base URL of the API.
	 *
	 * @return string
	 */
	public function defineBaseUrl(): string
	{
		return $this->apiBaseUrl;
	}

	/**
	 * The headers that will be applied to every request.
	 *
	 * @return string[]
	 */
	public function defaultHeaders(): array
	{
		return [];
	}

	/**
	 * The config options that will be applied to every request.
	 *
	 * @return string[]
	 */
	public function defaultConfig(): array
	{
		return [];
	}
}
