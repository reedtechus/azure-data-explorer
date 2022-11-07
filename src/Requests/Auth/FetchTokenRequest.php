<?php

namespace ReedTech\AzureDataExplorer\Requests\Auth;

use ReedTech\AzureDataExplorer\Connectors\AuthConnector;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Traits\Plugins\HasFormParams;

class FetchTokenRequest extends SaloonRequest
{
    use HasFormParams;

    /**
     * The connector class.
     *
     * @var string|null
     */
    protected ?string $connector = AuthConnector::class;

    /**
     * The HTTP verb the request will use.
     *
     * @var string|null
     */
    protected ?string $method = Saloon::POST;

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
     *
     * @return string
     */
    public function defineEndpoint(): string
    {
        // $tenantID = config('azure-app-auth.tenant_id');

        return "/{$this->tenantId}/oauth2/token";
    }

    public function defaultData(): array
    {
        // $clientID = config('azure-app-auth.client_id');
        // $clientSecret = config('azure-app-auth.client_secret');
        // $cluster = config('azure-app-auth.cluster');
        // $region = config('azure-app-auth.region');

        return [
            'grant_type' => 'client_credentials',
            'resource' => "https://{$this->cluster}.{$this->region}.kusto.windows.net/",
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];
    }
}
