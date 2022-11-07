<?php

namespace ReedTech\AzureDataExplorer;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ReedTech\AzureDataExplorer\Connectors\AuthConnector;
use ReedTech\AzureDataExplorer\Connectors\DataExplorerConnector;
use ReedTech\AzureDataExplorer\Requests\Auth\FetchTokenRequest;
use ReedTech\AzureDataExplorer\Requests\Query\QueryRequest;
use ReflectionException;
use Sammyjo20\Saloon\Exceptions\SaloonException;
use Sammyjo20\Saloon\Http\SaloonResponse;

class AzureDataExplorerApi
{
    protected AuthConnector $authConnector;

    protected FetchTokenRequest $authRequest;

    protected ?DataExplorerConnector $deConnector = null;

    protected ?string $database = null;

    /**
     * Holds the currently fetched auth token
     *
     * @var string
     */
    private ?string $token = null;

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
        // TODO - Allow this to be more dynamic
        $this->database = config('azure-data-explorer.database');

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
     * @param  bool  $force Force a new token to be fetched
     * @return SaloonResponse
     *
     * @throws ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function fetchToken(bool $force = false): static
    {
        // TODO - Temporary 'in memory' caching of the token
        if (! $force && $this->token !== null) {
            return $this->token;
        }

        $response = $this->authConnector->send($this->authRequest);
        // TODO - Add Error Handling for failed requests

        if ($response->successful()) {
            $this->token = $response->json('access_token');
            $this->deConnector = new DataExplorerConnector($this->cluster, $this->region, $this->cluster, $this->token);
        }

        // return $response->json()['access_token'];
        return $this;
    }

    /**
     * Query Azure Data Explorer
     *
     * @param  string|array  $query
     * @return SaloonResponse
     *
     * @throws Exception
     * @throws ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function query(string|array $query): SaloonResponse
    {
        if ($this->deConnector === null) {
            throw new Exception('You must fetch a token before you can query the API');
        }

        $response = $this->deConnector->send(new QueryRequest($this->database, $query));
        // $request = ::query($query);
        // $response = $request->send();
        if ($response->failed()) {
            // $this->error('Failed to query Azure Data Explorer');
            // $this->error('Response: '.print_r($response->json(), true));

            // return Command::FAILURE;
            return $response;
        }

        // Handle Successful Response
        // /** @var QueryResultsDTO $results */
        // $results = $response->dto();

        // dump('Columns: '.implode(', ', $results->columns));
        // dump('Number of Results: '.count($results->data));
        // dump('Execution Time: '.$results->executionTime);

        return $response;
    }
}
