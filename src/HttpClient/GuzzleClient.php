<?php

namespace QurbanAli\PESCheck\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use QurbanAli\PESCheck\Config;
use QurbanAli\PESCheck\Exception\RequestException;
use QurbanAli\PESCheck\Response\ApiResponse;

class GuzzleClient implements HttpClientInterface
{
    /**
     * @var Client $client The Guzzle HTTP client instance used to make HTTP requests.
     */
    private Client $client;

    /**
     * GuzzleClient constructor.
     *
     * @param Config $config
     */
    public function __construct(protected Config $config)
    {
        $this->client = new Client([
            'base_uri' => $config->get('base_uri'),
            'timeout' => $config->get('timeout'),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * @inheritDoc
     * @throws \QurbanAli\PESCheck\Exception\RequestException
     */
    public function get(string $uri, array $options = []): ApiResponse
    {
        return $this->request('GET', $uri, $options);
    }

    /**
     * @inheritDoc
     * @throws \QurbanAli\PESCheck\Exception\RequestException
     */
    public function post(string $uri, array $options = []): ApiResponse
    {
        return $this->request('POST', $uri, $options);
    }

    /**
     * @inheritDoc
     * @throws \QurbanAli\PESCheck\Exception\RequestException
     */
    public function put(string $uri, array $options = []): ApiResponse
    {
        return $this->request('PUT', $uri, $options);
    }

    /**
     * @inheritDoc
     * @throws \QurbanAli\PESCheck\Exception\RequestException
     */
    public function patch(string $uri, array $options = []): ApiResponse
    {
        return $this->request('PATCH', $uri, $options);
    }

    /**
     * @inheritDoc
     * @throws \QurbanAli\PESCheck\Exception\RequestException
     */
    public function delete(string $uri, array $options = []): ApiResponse
    {
        return $this->request('DELETE', $uri, $options);
    }

    /**
     * Make a request to the API.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return ApiResponse
     * @throws RequestException
     */
    private function request(string $method, string $uri, array $options = []): ApiResponse
    {
        // Add authentication token if available
        if ($token = $this->config->get('access_token')) {
            $options['headers']['Authorization'] = 'Bearer ' . $token;
        }

        // Convert JSON body if present
        if (isset($options['json'])) {
            $options['body'] = json_encode($options['json']);
            unset($options['json']);
        }

        try {
            $response = $this->client->request($method, $uri, $options);

            $contents = $response->getBody()->getContents();
            $data = json_decode($contents, true);

            return new ApiResponse(
                $response->getStatusCode(),
                $data,
                $response->getHeaders()
            );
        } catch (GuzzleException $e) {
            throw new RequestException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
