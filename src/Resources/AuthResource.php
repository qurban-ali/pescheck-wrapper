<?php

namespace QurbanAli\PescheckWrapper\Resources;

use QurbanAli\PescheckWrapper\Exception\AuthenticationException;

class AuthResource extends AbstractResource
{
    /**
     * Login to the API.
     *
     * @return array
     * @throws AuthenticationException
     */
    public function login(): array
    {
        $response = $this->getHttpClient()->post('/jwt/', [
            'json' => [
                'email' => $this->client->getConfig()->get('email'),
                'password' => $this->client->getConfig()->get('password'),
            ],
        ]);

        if ($response->getStatusCode() !== 201) {
            throw new AuthenticationException('Failed to authenticate with the API.');
        }

        $data = $response->getData();

        // Store the tokens in the config
        $this->client->getConfig()->set('access_token', $data['access'] ?? null);
        $this->client->getConfig()->set('refresh_token', $data['refresh'] ?? null);

        return $data;
    }

    /**
     * Refresh the access token.
     *
     * @return array
     * @throws AuthenticationException
     */
    public function refresh(): array
    {
        $refreshToken = $this->client->getConfig()->get('refresh_token');

        if (!$refreshToken) {
            throw new AuthenticationException('No refresh token available.');
        }

        $response = $this->getHttpClient()->post('/jwt/refresh/', [
            'json' => [
                'refresh' => $refreshToken,
            ],
        ]);

        if ($response->getStatusCode() !== 201) {
            throw new AuthenticationException('Failed to refresh the access token.');
        }

        $data = $response->getData();

        // Update the access token in the config
        $this->client->getConfig()->set('access_token', $data['access'] ?? null);

        return $data;
    }
}
