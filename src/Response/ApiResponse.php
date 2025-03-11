<?php

namespace QurbanAli\PescheckWrapper\Response;

class ApiResponse
{

    /**
     * ApiResponse constructor.
     *
     * @param int $statusCode
     * @param array|null $data
     * @param array $headers
     */
    public function __construct(protected int $statusCode, protected ?array $data, protected array $headers = [])
    {
    }

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the response data.
     *
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * Get the response headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Check if the response is successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }
}
