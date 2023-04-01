<?php

namespace ReedTech\AzureDataExplorer\Requests;

use ReedTech\AzureDataExplorer\Connectors\DataExplorerConnector;
use ReedTech\AzureDataExplorer\Data\QueryResultsDTO;
use Saloon\Contracts\Body\HasBody;
use Saloon\Contracts\Response as ContractsResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class QueryRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasJsonBody;

    public function __construct(protected string $database, public string|array $kustoQuery)
    {
    }

    /**
     * The connector class.
     */
    protected ?string $connector = DataExplorerConnector::class;

    /**
     * The HTTP verb the request will use.
     */
    protected Method $method = Method::POST;

    /**
     * The endpoint of the request.
     */
    public function resolveEndpoint(): string
    {
        return '/v2/rest/query';
    }

    public function defaultHeaders(): array
    {
        // $cluster = config('services.data_explorer.cluster');
        // $region = config('services.data_explorer.region');

        return [
            // 'Content-Type' => 'application/json',
            // 'Host' => "$cluster.$region.kusto.windows.net",
        ];
    }

    protected function defaultBody(): array
    {
        // Allows the user to pass in a single query string or an array of strings (multiple line queries)
        $query = is_array($this->kustoQuery) ? implode("\n", $this->kustoQuery) : $this->kustoQuery;

        return [
            'csl' => $query,
            // 'db' => config('services.data_explorer.db'),
            'db' => $this->database,
        ];
    }

    // protected function castToDto(Response $response): object
    public function createDtoFromResponse(ContractsResponse $response): mixed
    {
        return QueryResultsDTO::fromSaloon($response);
    }
}
