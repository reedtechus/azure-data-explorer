<?php

namespace ReedTech\AzureDataExplorer\Connectors;

use ReedTech\AzureDataExplorer\Exceptions\IngestException;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Http\SaloonResponse;

class StreamingIngestConnector extends DataExplorerConnector
{
    // We need to prepend 'ingest-' to the cluster URL
    // protected function generateBaseURL(): string
    // {
    //     return 'ingest-'.parent::generateBaseURL();
    // }

    public function boot(SaloonRequest $request): void
    {
        // If the response failed, throw a custom HTTP exception
        $this->addResponseInterceptor(function (SaloonRequest $request, SaloonResponse $response) {
            if ($response->failed()) {
                // Default error message
                $errorMessage = 'The ingest request failed with the following error: '.$response->toGuzzleResponse()->getReasonPhrase();
                // We can build a better error message in the case of a bad request
                if ($response->status() == 400) {
                    // $response->throw();
                    $errorMessage = $response->toGuzzleResponse()->getReasonPhrase().' - '.$response->json('error.@message');
                }
                // Throw the exception
                throw new IngestException($errorMessage, $response->status(), $response->getGuzzleException());
            }

            return $response;
        });
    }
}
