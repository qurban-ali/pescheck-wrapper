<?php

namespace QurbanAli\PESCheck\Resources;

use QurbanAli\PESCheck\Client;
use QurbanAli\PESCheck\HttpClient\HttpClientInterface;
use QurbanAli\PESCheck\Response\ApiResponse;
use QurbanAli\PESCheck\Response\PaginatedResponse;

abstract class AbstractResource
{
    /**
     * AbstractResource constructor.
     *
     * @param Client $client
     */
    public function __construct(protected Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the HTTP client.
     *
     * @return HttpClientInterface
     */
    protected function getHttpClient(): HttpClientInterface
    {
        return $this->client->getHttpClient();
    }

    /**
     * Create a paginated response.
     *
     * @param ApiResponse $response
     * @return PaginatedResponse
     */
    protected function paginatedResponse(ApiResponse $response): PaginatedResponse
    {
        return new PaginatedResponse(
            $response->getData()['count'] ?? 0,
            $response->getData()['next'] ?? null,
            $response->getData()['previous'] ?? null,
            $response->getData()['results'] ?? []
        );
    }
}
