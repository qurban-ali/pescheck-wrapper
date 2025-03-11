<?php

namespace QurbanAli\PescheckWrapper;

use QurbanAli\PescheckWrapper\Exception\InvalidArgumentException;

class Config
{
    /**
     * @var array
     */
    private array $config;

    /**
     * @var array
     */
    private array $requiredKeys = ['email', 'password'];

    /**
     * Predefined environments
     *
     * @var array
     */
    private array $environments = [
        'production' => 'https://api.pescheck.io/api',
        'staging' => 'https://api-staging.pescheck.io/api',
    ];

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->validateConfig($config);

        // Handle environment setting if provided
        if (isset($config['environment']) && isset($this->environments[$config['environment']])) {
            $config['base_uri'] = $this->environments[$config['environment']];
            unset($config['environment']);
        }

        $this->config = array_merge([
            'base_uri' => $this->environments['staging'],
            'timeout' => 30,
            'access_token' => null,
            'refresh_token' => null,
        ], $config);
    }

    /**
     * Get a config value.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key): mixed
    {
        return $this->config[$key] ?? null;
    }

    /**
     * Set a config value.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value): self
    {
        $this->config[$key] = $value;

        return $this;
    }

    /**
     * Set the environment.
     *
     * @param string $environment
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setEnvironment(string $environment): self
    {
        if (!isset($this->environments[$environment])) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid environment "%s". Available environments: %s',
                    $environment,
                    implode(', ', array_keys($this->environments))
                )
            );
        }

        $this->config['base_uri'] = $this->environments[$environment];

        return $this;
    }

    /**
     * Get available environments.
     *
     * @return array
     */
    public function getAvailableEnvironments(): array
    {
        return array_keys($this->environments);
    }

    /**
     * Add a custom environment.
     *
     * @param string $name
     * @param string $baseUri
     * @return $this
     */
    public function addEnvironment(string $name, string $baseUri): self
    {
        $this->environments[$name] = $baseUri;

        return $this;
    }

    /**
     * Validate the config.
     *
     * @param array $config
     * @throws InvalidArgumentException
     */
    private function validateConfig(array $config): void
    {
        foreach ($this->requiredKeys as $key) {
            if (!isset($config[$key])) {
                throw new InvalidArgumentException(sprintf('The "%s" config key is required.', $key));
            }
        }
    }
}
