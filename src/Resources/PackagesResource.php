<?php

namespace QurbanAli\PescheckWrapper\Resources;

use QurbanAli\PescheckWrapper\Response\PaginatedResponse;

class PackagesResource extends AbstractResource
{
    /**
     * Get all packages.
     *
     * @param array $params
     * @return PaginatedResponse
     */
    public function all(array $params = []): PaginatedResponse
    {
        $response = $this->getHttpClient()->get('/api/v1/packages/', [
            'query' => $params,
        ]);

        return $this->paginatedResponse($response);
    }

    /**
     * Get a package by ID.
     *
     * @param string $id
     * @return array
     */
    public function get(string $id): array
    {
        $response = $this->getHttpClient()->get("/api/v1/packages/{$id}/");

        return $response->getData();
    }

    /**
     * Create a new package.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $response = $this->getHttpClient()->post('/api/v1/packages/', [
            'json' => $data,
        ]);

        return $response->getData();
    }
}
