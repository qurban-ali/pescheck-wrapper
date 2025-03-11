<?php

namespace QurbanAli\PescheckWrapper\HttpClient;

use QurbanAli\PescheckWrapper\Response\ApiResponse;

interface HttpClientInterface
{
    /**
     * Send a GET request.
     *
     * @param string $uri
     * @param array $options
     * @return ApiResponse
     */
    public function get(string $uri, array $options = []): ApiResponse;

    /**
     * Send a POST request.
     *
     * @param string $uri
     * @param array $options
     * @return ApiResponse
     */
    public function post(string $uri, array $options = []): ApiResponse;

    /**
     * Send a PUT request.
     *
     * @param string $uri
     * @param array $options
     * @return ApiResponse
     */
    public function put(string $uri, array $options = []): ApiResponse;

    /**
     * Send a PATCH request.
     *
     * @param string $uri
     * @param array $options
     * @return ApiResponse
     */
    public function patch(string $uri, array $options = []): ApiResponse;

    /**
     * Send a DELETE request.
     *
     * @param string $uri
     * @param array $options
     * @return ApiResponse
     */
    public function delete(string $uri, array $options = []): ApiResponse;
}
