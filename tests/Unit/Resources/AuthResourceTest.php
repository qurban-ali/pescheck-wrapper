<?php

use QurbanAli\PescheckWrapper\Client;
use QurbanAli\PescheckWrapper\Config;
use QurbanAli\PescheckWrapper\Exception\AuthenticationException;
use QurbanAli\PescheckWrapper\HttpClient\HttpClientInterface;
use QurbanAli\PescheckWrapper\Resources\AuthResource;
use QurbanAli\PescheckWrapper\Response\ApiResponse;
use Mockery;

beforeEach(function () {
    $this->httpClient = Mockery::mock(HttpClientInterface::class);
    $this->config = Mockery::mock(Config::class);
    $this->client = Mockery::mock(Client::class);

    $this->client->shouldReceive('getHttpClient')
        ->andReturn($this->httpClient);
    $this->client->shouldReceive('getConfig')
        ->andReturn($this->config);

    $this->authResource = new AuthResource($this->client);
});

afterEach(function () {
    Mockery::close();
});

test('it can login successfully', function () {
    $this->config->shouldReceive('get')
        ->with('email')
        ->andReturn('test@example.com');
    $this->config->shouldReceive('get')
        ->with('password')
        ->andReturn('password');
    $this->config->shouldReceive('set')
        ->with('access_token', 'access-token')
        ->andReturnSelf();
    $this->config->shouldReceive('set')
        ->with('refresh_token', 'refresh-token')
        ->andReturnSelf();

    $response = Mockery::mock(ApiResponse::class);
    $response->shouldReceive('getStatusCode')
        ->andReturn(201);
    $response->shouldReceive('getData')
        ->andReturn([
            'access' => 'access-token',
            'refresh' => 'refresh-token',
        ]);

    $this->httpClient->shouldReceive('post')
        ->with('/jwt/', [
            'json' => [
                'email' => 'test@example.com',
                'password' => 'password',
            ],
        ])
        ->andReturn($response);

    $result = $this->authResource->login();

    expect($result)->toBe([
        'access' => 'access-token',
        'refresh' => 'refresh-token',
    ]);
});

test('it throws an exception when login fails', function () {
    $this->config->shouldReceive('get')
        ->with('email')
        ->andReturn('test@example.com');
    $this->config->shouldReceive('get')
        ->with('password')
        ->andReturn('password');

    $response = Mockery::mock(ApiResponse::class);
    $response->shouldReceive('getStatusCode')
        ->andReturn(401);

    $this->httpClient->shouldReceive('post')
        ->with('/jwt/', [
            'json' => [
                'email' => 'test@example.com',
                'password' => 'password',
            ],
        ])
        ->andReturn($response);

    expect(fn() => $this->authResource->login())->toThrow(AuthenticationException::class);
});

test('it can refresh the token successfully', function () {
    $this->config->shouldReceive('get')
        ->with('refresh_token')
        ->andReturn('old-refresh-token');
    $this->config->shouldReceive('set')
        ->with('access_token', 'new-access-token')
        ->andReturnSelf();

    $response = Mockery::mock(ApiResponse::class);
    $response->shouldReceive('getStatusCode')
        ->andReturn(201);
    $response->shouldReceive('getData')
        ->andReturn([
            'access' => 'new-access-token',
        ]);

    $this->httpClient->shouldReceive('post')
        ->with('/jwt/refresh/', [
            'json' => [
                'refresh' => 'old-refresh-token',
            ],
        ])
        ->andReturn($response);

    $result = $this->authResource->refresh();

    expect($result)->toBe([
        'access' => 'new-access-token',
    ]);
});

test('it throws an exception when no refresh token is available', function () {
    $this->config->shouldReceive('get')
        ->with('refresh_token')
        ->andReturnNull();

    expect(fn() => $this->authResource->refresh())->toThrow(AuthenticationException::class);
});

test('it throws an exception when token refresh fails', function () {
    $this->config->shouldReceive('get')
        ->with('refresh_token')
        ->andReturn('old-refresh-token');

    $response = Mockery::mock(ApiResponse::class);
    $response->shouldReceive('getStatusCode')
        ->andReturn(401);

    $this->httpClient->shouldReceive('post')
        ->with('/jwt/refresh/', [
            'json' => [
                'refresh' => 'old-refresh-token',
            ],
        ])
        ->andReturn($response);

    expect(fn() => $this->authResource->refresh())->toThrow(AuthenticationException::class);
});
