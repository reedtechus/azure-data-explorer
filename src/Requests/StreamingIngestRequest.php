<?php

namespace ReedTech\AzureDataExplorer\Requests;

use ReedTech\AzureDataExplorer\Connectors\StreamingIngestConnector;
use ReedTech\AzureDataExplorer\Interfaces\IngestModelInterface;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

// use Sammyjo20\Saloon\Constants\Saloon;
// use Sammyjo20\Saloon\Http\SaloonRequest;
// use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;
// use Sammyjo20\Saloon\Traits\Plugins\HasJsonBody;

class StreamingIngestRequest extends Request
{
	use AcceptsJson;
	use HasJsonBody;

	private string $database;

	private string $table;

	private string $mapping;

	private array $payload;

	/**
	 * The connector class.
	 *
	 * @var string|null
	 */
	protected ?string $connector = StreamingIngestConnector::class;

	/**
	 * The HTTP verb the request will use.
	 *
	 * @var Method
	 */
	protected Method $method = Method::POST;

	/**
	 * The endpoint of the request.
	 *
	 * @return string
	 */
	public function resolveEndpoint(): string
	{
		return "/v1/rest/ingest/{$this->database}/{$this->table}";
	}

	public function defaultQuery(): array
	{
		return [
			'streamFormat' => 'JSON',
			'mappingName' => $this->mapping,
		];
	}

	public function __construct(protected IngestModelInterface $deModel)
	{
		$this->database = $deModel->getIngestDatabase(); // Override the default database
		$this->table = $deModel->getIngestTable();
		$this->mapping = $deModel->getIngestMapping();
		$this->payload = $deModel->toIngest();
	}

	protected function defaultBody(): array
	{
		return $this->payload;
	}
}
