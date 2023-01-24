<?php

namespace ReedTech\AzureDataExplorer\Connectors;

use ReedTech\AzureDataExplorer\Exceptions\HTTPException;
use ReedTech\AzureDataExplorer\Requests\QueryRequest;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class DataExplorerConnector extends Connector
{
    use AcceptsJson;
    use HasJsonBody;

    protected string $userAgent = 'AzureDataExplorer-PHPClient/0.1';

    // protected AuthConnector $authConnector;

    public function __construct(
        protected string $cluster,
        protected string $region,
        protected string $database,
        protected string $token
    ) {
        // $this->authenticator = new TokenAuthenticator();
    }

    /**
     * The Base URL of the API.
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        // $cluster = config('services.data_explorer.cluster');
        // $region = config('services.data_explorer.region');

        return 'https://'.$this->generateBaseURL();
    }

    protected array $requests = [
        'query' => QueryRequest::class, // $tmdb->get_popular_movies()
    ];

    /**
     * The headers that will be applied to every request.
     *
     * @return string[]
     */
    public function defaultHeaders(): array
    {
        // $cluster = config('services.data_explorer.cluster');
        // $region = config('services.data_explorer.region');

        return [
            'Content-Type' => 'application/json',
            'Host' => $this->generateBaseURL(),
            // Custom Headers
            'x-ms-app' => $this->userAgent,
        ];
    }

    public function defaultAuth(): Authenticator
    {
        // Fetch a token, it will probably be cached automatically
        // $tokenResponse = (new AuthenticationRequest())->send();
        // $token = $tokenResponse->json('access_token');

        // dd($token);

        return new TokenAuthenticator($this->token);
    }

    // public function boot(SaloonRequest $request): void
    // {
    // 	// If the response failed, throw a custom HTTP exception
    // 	$this->addResponseInterceptor(function (SaloonRequest $request, SaloonResponse $response) {
    // 		if ($response->failed()) {
    // 			// $response->throw();
    // 			throw new HTTPException($response->toGuzzleResponse()->getReasonPhrase(), $response->status(), $response->getGuzzleException());
    // 		}

    // 		return $response;
    // 	});
    // }

    protected function generateBaseURL(): string
    {
        return "{$this->cluster}.{$this->region}.kusto.windows.net";
    }
}
