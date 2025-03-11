<?php

namespace QurbanAli\PESCheck\Resources;

use QurbanAli\PESCheck\Response\PaginatedResponse;

class ScreeningsResource extends AbstractResource
{
    /**
     * Get all screenings.
     *
     * @param array $params
     * @return PaginatedResponse
     */
    public function all(array $params = []): PaginatedResponse
    {
        $response = $this->getHttpClient()->get('/v1/screenings/', [
            'query' => $params,
        ]);

        return $this->paginatedResponse($response);
    }

    /**
     * Get a screening by ID.
     *
     * @param string $id
     * @return array
     */
    public function get(string $id): array
    {
        $response = $this->getHttpClient()->get("/v1/screenings/{$id}/");

        return $response->getData();
    }

    /**
     * Create a new screening.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $response = $this->getHttpClient()->post('/v1/screenings/', [
            'json' => $data,
        ]);

        return $response->getData();
    }

    /**
     * Get documents for a screening.
     *
     * @param string $id
     * @return array
     */
    public function getDocuments(string $id): array
    {
        $response = $this->getHttpClient()->get("/v1/screenings/{$id}/document/");

        return $response->getData();
    }
}
