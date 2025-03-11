<?php

namespace QurbanAli\PescheckWrapper;

use QurbanAli\PescheckWrapper\HttpClient\GuzzleClient;
use QurbanAli\PescheckWrapper\HttpClient\HttpClientInterface;
use QurbanAli\PescheckWrapper\Resources\AuthResource;
use QurbanAli\PescheckWrapper\Resources\ChecksResource;
use QurbanAli\PescheckWrapper\Resources\DivisionsResource;
use QurbanAli\PescheckWrapper\Resources\PackagesResource;
use QurbanAli\PescheckWrapper\Resources\ScreeningsResource;

class Client
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @var array
     */
    private array $resources = [];

    /**
     * Client constructor.
     *
     * @param array $config
     * @param HttpClientInterface|null $httpClient
     */
    public function __construct(array $config, HttpClientInterface $httpClient = null)
    {
        $this->config = new Config($config);
        $this->httpClient = $httpClient ?: new GuzzleClient($this->config);
    }

    /**
     * Get the authentication resource.
     *
     * @return AuthResource
     */
    public function auth(): AuthResource
    {
        return $this->getResource('auth', AuthResource::class);
    }

    /**
     * Get the packages resource.
     *
     * @return PackagesResource
     */
    public function packages(): PackagesResource
    {
        return $this->getResource('packages', PackagesResource::class);
    }

    /**
     * Get the screenings resource.
     *
     * @return ScreeningsResource
     */
    public function screenings(): ScreeningsResource
    {
        return $this->getResource('screenings', ScreeningsResource::class);
    }

    /**
     * Get the divisions resource.
     *
     * @return DivisionsResource
     */
    public function divisions(): DivisionsResource
    {
        return $this->getResource('divisions', DivisionsResource::class);
    }

    /**
     * Get the checks resource.
     *
     * @return ChecksResource
     */
    public function checks(): ChecksResource
    {
        return $this->getResource('checks', ChecksResource::class);
    }

    /**
     * Get the HTTP client.
     *
     * @return HttpClientInterface
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * Get the config.
     *
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Get a resource instance.
     *
     * @param string $name
     * @param string $class
     * @return mixed
     */
    private function getResource(string $name, string $class): mixed
    {
        if (!isset($this->resources[$name])) {
            $this->resources[$name] = new $class($this);
        }

        return $this->resources[$name];
    }
}
