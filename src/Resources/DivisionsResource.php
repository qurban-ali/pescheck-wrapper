<?php

namespace QurbanAli\PescheckWrapper\Resources;

use QurbanAli\PescheckWrapper\Response\PaginatedResponse;

class DivisionsResource extends AbstractResource
{
    /**
     * Get all divisions.
     *
     * @param array $params
     * @return PaginatedResponse
     */
    public function all(array $params = []): PaginatedResponse
    {
        $response = $this->getHttpClient()->get('/api/v1/organisations/divisions/', [
            'query' => $params,
        ]);

        return $this->paginatedResponse($response);
    }

    /**
     * Get a division by ID.
     *
     * @param string $id
     * @return array
     */
    public function get(string $id): array
    {
        $response = $this->getHttpClient()->get("/api/v1/organisations/divisions/{$id}/");

        return $response->getData();
    }

    /**
     * Create a new division.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $response = $this->getHttpClient()->post('/api/v1/organisations/divisions/', [
            'json' => $data,
        ]);

        return $response->getData();
    }

    /**
     * Update a division.
     *
     * @param string $id
     * @param array $data
     * @return array
     */
    public function update(string $id, array $data): array
    {
        $response = $this->getHttpClient()->put("/api/v1/organisations/divisions/{$id}/", [
            'json' => $data,
        ]);

        return $response->getData();
    }

    /**
     * Partially update a division.
     *
     * @param string $id
     * @param array $data
     * @return array
     */
    public function patch(string $id, array $data): array
    {
        $response = $this->getHttpClient()->patch("/api/v1/organisations/divisions/{$id}/", [
            'json' => $data,
        ]);

        return $response->getData();
    }
}
