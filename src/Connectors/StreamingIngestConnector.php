<?php

namespace ReedTech\AzureDataExplorer\Connectors;

use ReedTech\AzureDataExplorer\Exceptions\IngestException;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Http\SaloonResponse;

class StreamingIngestConnector extends DataExplorerConnector
{
    // We need to prepend 'ingest-' to the cluster URL
    protected function generateBaseURL(): string
    {
        return 'ingest-'.parent::generateBaseURL();
    }

    public function boot(SaloonRequest $request): void
    {
        // If the response failed, throw a custom HTTP exception
        $this->addResponseInterceptor(function (SaloonRequest $request, SaloonResponse $response) {
            if ($response->failed()) {
                // $response->throw();
                throw new IngestException($response->toGuzzleResponse()->getReasonPhrase(), $response->status(), $response->getGuzzleException());
            }

            return $response;
        });
    }
}
