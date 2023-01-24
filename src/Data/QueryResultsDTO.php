<?php

namespace ReedTech\AzureDataExplorer\Data;

use Saloon\Http\Response;

class QueryResultsDTO
{
	public function __construct(
		public array $columns,
		public array $data,
		public float $executionTime,
	) {
	}

	public static function fromSaloon(Response $response): self
	{
		$data = $response->json();

		// Raw Data Extraction
		$rawColumns = $data[2]['Columns'];
		$rawRows = $data[2]['Rows'];
		$executionTime = json_decode($data[3]['Rows'][2][11])->ExecutionTime;

		// Morph to intermediate state
		$columns = collect($rawColumns)->pluck('ColumnName')->map(function ($column) {
			// Column name manipulation
			// $column = str_replace('ID', 'Id', $column);
			// $column = Str::snake($column);

			return trim($column);
		})->all();

		// Loop through each row and map the column names to the values
		$rows = collect($rawRows)->map(function ($row) use ($columns) {
			// Loop through each column and map the column name to the value
			return collect($row)->map(function ($value, $key) use ($columns) {
				// return [$columnName => $value]; // Map the column name to the value
				return [$columns[$key] => trim($value)]; // Map the column name to the value
			})->collapse()->all(); // Collapse the array to a single level
		})->all();

		return new static($columns, $rows, $executionTime);
	}
}
