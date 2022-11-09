<?php

namespace ReedTech\AzureDataExplorer;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ReedTech\AzureDataExplorer\Connectors\AuthConnector;
use ReedTech\AzureDataExplorer\Connectors\DataExplorerConnector;
use ReedTech\AzureDataExplorer\Connectors\StreamingIngestConnector;
use ReedTech\AzureDataExplorer\Data\QueryResultsDTO;
use ReedTech\AzureDataExplorer\Exceptions\AuthException;
use ReedTech\AzureDataExplorer\Exceptions\DTOException;
use ReedTech\AzureDataExplorer\Exceptions\QueryException;
use ReedTech\AzureDataExplorer\Interfaces\IngestModelInterface;
use ReedTech\AzureDataExplorer\Requests\AuthenticationRequest;
use ReedTech\AzureDataExplorer\Requests\QueryRequest;
use ReedTech\AzureDataExplorer\Requests\StreamingIngestRequest;
use ReflectionException;
use Sammyjo20\Saloon\Exceptions\SaloonException;
use Sammyjo20\Saloon\Http\SaloonResponse;

class AzureDataExplorerApi
{
    protected AuthConnector $authConnector;

    protected AuthenticationRequest $authRequest;

    protected ?DataExplorerConnector $queryConnector = null;

    protected ?StreamingIngestConnector $ingestConnector = null;

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
        $this->authConnector = new AuthConnector();

        $this->authRequest = new AuthenticationRequest(
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
    public function fetchToken(bool $force = false): string
    {
        // TODO - Temporary 'in memory' caching of the token
        if (! $force && $this->token !== null) {
            return $this->token;
        }

        // Send the Auth request to Azure
        $response = $this->authConnector->send($this->authRequest);

        // Attempt to parse the access token from the response
        try {
            $this->token = $response->json('access_token');
        } catch (Exception $e) {
            throw new AuthException($e->getMessage(), $response->status());
        }

        $this->queryConnector = new DataExplorerConnector(
            $this->cluster,
            $this->region,
            $this->cluster,
            $this->token
        );

        $this->ingestConnector = new StreamingIngestConnector(
            $this->cluster,
            $this->region,
            $this->cluster,
            $this->token
        );

        // return $response->json()['access_token'];
        return $this->token;
    }

    /**
     * Query Azure Data Explorer
     *
     * @param  string|array  $query
     * @return QueryResultsDTO
     *
     * @throws Exception
     * @throws ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function query(string|array $query): ?QueryResultsDTO
    {
        // Returns true if ready to query, otherwise throws an exception
        $this->validateSetup();

        // Run the Data Explorer query
        $response = $this->queryConnector->send(new QueryRequest($this->database, $query));

        // Handle Successful Response
        try {
            /** @var QueryResultsDTO $results */
            $results = $response->dto();

            return $results;
        } catch (Exception $e) {
            throw new DTOException('Unable to parse response into DTO');
        }
    }

    /**
     * Ingest data into Azure Data Explorer
     *
     * @param  IngestModelInterface  $model
     * @return SaloonResponse
     *
     * @throws Exception
     * @throws ReflectionException
     * @throws GuzzleException
     * @throws SaloonException
     */
    public function ingest(IngestModelInterface $deModel)
    {
        // Returns true if ready to query, otherwise throws an exception
        $this->validateSetup();

        $request = new StreamingIngestRequest($this->database, $deModel);
        $results = $this->ingestConnector->send($request);

        return $results->json();
    }

    private function validateSetup(): ?bool
    {
        if ($this->token === null) {
            if (! $this->fetchToken()) {
                throw new AuthException('Failed to fetch token');
            }
        }

        if ($this->queryConnector === null) {
            throw new AuthException('Critical error! Data Explorer Connector is null!');
        }

        if ($this->database === null) {
            throw new QueryException('Database is not set!');
        }

        return true;
    }
}
