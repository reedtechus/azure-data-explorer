<?php

namespace ReedTech\AzureDataExplorer\Requests;

use ReedTech\AzureDataExplorer\Connectors\DataExplorerConnector;
use ReedTech\AzureDataExplorer\Interfaces\IngestModelInterface;
use Sammyjo20\Saloon\Constants\Saloon;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;
use Sammyjo20\Saloon\Traits\Plugins\HasJsonBody;

class StreamingIngestRequest extends SaloonRequest
{
    use AcceptsJson;
    use HasJsonBody;

    private string $table;

    private string $mapping;

    private array $payload;

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
        return "/v1/rest/ingest/{$this->database}/{$this->table}";
    }

    public function defaultQuery(): array
    {
        return [
            'streamFormat' => 'JSON',
            // 'mappingName' => $this->mapping,
        ];
    }

    // public function __construct(public string $table, public array $payload)
    public function __construct(protected string $database, protected IngestModelInterface $deModel)
    {
        // $this->database = config('services.data_explorer.enrichment_db');
        $this->table = $deModel->getDETable();
        $this->mapping = $deModel->getDEMapping();
        $this->payload = $deModel->toDEIngest();
    }

    public function defaultData(): array
    {
        return $this->payload;
    }
}