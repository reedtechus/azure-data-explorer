<?php

namespace ReedTech\AzureDataExplorer;

use GuzzleHttp\Exception\GuzzleException;
use ReedTech\AzureDataExplorer\Connectors\AuthConnector;
use ReedTech\AzureDataExplorer\Requests\Auth\FetchTokenRequest;
use ReflectionException;
use Sammyjo20\Saloon\Exceptions\SaloonException;
use Sammyjo20\Saloon\Http\SaloonResponse;

class AzureDataExplorerApi
{
    protected AuthConnector $authConnector;

    protected FetchTokenRequest $authRequest;

    public static function make(
        string $tenantId,
        string $clientId,
        string $clientSecret,
        string $region,
        string $cluster
    ): static {
        return new static($tenantId, $clientId, $clientSecret, $region, $cluster);
    }

    public function __construct(
        protected string $tenantId,
        protected string $clientId,
        protected string $clientSecret,
        protected string $region,
        protected string $cluster
    ) {
        $this->authConnector = new AuthConnector();

        $this->authRequest = new FetchTokenRequest(
            $this->tenantId,
            $this->clientId,
            $this->clientSecret,
            $this->region,
            $this->cluster
        );
    }

    /**
     * Set's a new base URL for the API
     *
     * @param  string  $baseUrl
     * @return AzureDataExplorerApi
     */
    // public function setBaseUrl(string $baseUrl): self
    // {
    //     $this->authConnector = new AuthConnector($baseUrl);

    //     return $this;
    // }

    public function authUrl(): string
    {
        return $this->authConnector->defineBaseUrl().$this->authRequest->defineEndpoint();
    }

    /**
     * Acquires a new Auth Token from the Azure Data Explorer API
     *
     * @return SaloonResponse
     *
     * @throws ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function fetchToken(): SaloonResponse
    {
        $response = $this->authConnector->send($this->authRequest);

        // return $response->json()['access_token'];
        return $response;
    }
}
