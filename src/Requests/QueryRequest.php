<?php

namespace ReedTech\AzureDataExplorer\Requests;

use ReedTech\AzureDataExplorer\Connectors\DataExplorerConnector;
use ReedTech\AzureDataExplorer\Data\QueryResultsDTO;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Http\SaloonResponse;
use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;
use Sammyjo20\Saloon\Traits\Plugins\CastsToDto;
use Sammyjo20\Saloon\Traits\Plugins\HasJsonBody;

class QueryRequest extends SaloonRequest
{
    use AcceptsJson;
    use HasJsonBody;
    use CastsToDto;

    public function __construct(protected string $database, public string|array $kustoQuery)
    {
    }

    /**
     * The connector class.
     *
     * @var string|null
     */
    protected ?string $connector = DataExplorerConnector::class;

    /**
     * The HTTP verb the request will use.
     *
     * @var string|null
     */
    protected ?string $method = Saloon::POST;

    /**
     * The endpoint of the request.
     *
     * @return string
     */
    public function defineEndpoint(): string
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

    public function defaultData(): array
    {
        // Allows the user to pass in a single query string or an array of strings (multiple line queries)
        $query = is_array($this->kustoQuery) ? implode("\n", $this->kustoQuery) : $this->kustoQuery;

        return [
            'csl' => $query,
            // 'db' => config('services.data_explorer.db'),
            'db' => $this->database,
        ];
    }

    protected function castToDto(SaloonResponse $response): object
    {
        return QueryResultsDTO::fromSaloon($response);
    }
}
