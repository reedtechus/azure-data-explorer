<?php

namespace ReedTech\AzureDataExplorer\Requests;

use ReedTech\AzureDataExplorer\Connectors\AuthConnector;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\SoloRequest;
use Saloon\Traits\Body\HasFormBody;

class FetchToken extends SoloRequest implements HasBody
{
    use HasFormBody;

    protected string $apiBaseUrl = 'https://login.microsoftonline.com';

    /**
     * The connector class.
     */
    protected ?string $connector = AuthConnector::class;

    /**
     * The HTTP verb the request will use.
     */
    protected Method $method = Method::POST;

    public function __construct(
        protected string $tenantId,
        protected string $clientId,
        protected string $clientSecret,
        protected string $region,
        protected string $cluster
    ) {
        // Use this if caching is enabled
        // $this->safeCacheMethods[] = Saloon::POST;
    }

    /**
     * The endpoint of the request.
     */
    public function resolveEndpoint(): string
    {
        return $this->apiBaseUrl."/{$this->tenantId}/oauth2/token";
    }

    protected function defaultBody(): array
    {
        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'resource' => "https://{$this->cluster}.{$this->region}.kusto.windows.net/",
        ];
    }
}
