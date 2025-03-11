<?php

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use QurbanAli\PescheckWrapper\Config;
use QurbanAli\PescheckWrapper\HttpClient\GuzzleClient as PESCheckGuzzleClient;
use QurbanAli\PescheckWrapper\Response\ApiResponse;
use Mockery;
use ReflectionClass;

beforeEach(function () {
    $this->guzzleClient = Mockery::mock(GuzzleClient::class);
    $this->config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    // Create the HTTP client
    $this->httpClient = new PESCheckGuzzleClient($this->config);

    // Replace the Guzzle client with our mock
    $reflection = new ReflectionClass($this->httpClient);
    $property = $reflection->getProperty('client');
    $property->setAccessible(true);
    $property->setValue($this->httpClient, $this->guzzleClient);
});

afterEach(function () {
    Mockery::close();
});

test('it makes a GET request', function () {
    $this->guzzleClient->shouldReceive('request')
        ->once()
        ->with('GET', '/test', Mockery::any())
        ->andReturn(new Response(200, [], json_encode(['key' => 'value'])));

    $response = $this->httpClient->get('/test');

    expect($response)->toBeInstanceOf(ApiResponse::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getData())->toBe(['key' => 'value']);
});

test('it makes a POST request', function () {
    $this->guzzleClient->shouldReceive('request')
        ->once()
        ->with('POST', '/test', Mockery::any())
        ->andReturn(new Response(201, [], json_encode(['key' => 'value'])));

    $response = $this->httpClient->post('/test', ['json' => ['data' => 'value']]);

    expect($response)->toBeInstanceOf(ApiResponse::class);
    expect($response->getStatusCode())->toBe(201);
    expect($response->getData())->toBe(['key' => 'value']);
});

test('it makes a PUT request', function () {
    $this->guzzleClient->shouldReceive('request')
        ->once()
        ->with('PUT', '/test', Mockery::any())
        ->andReturn(new Response(200, [], json_encode(['key' => 'value'])));

    $response = $this->httpClient->put('/test');

    expect($response)->toBeInstanceOf(ApiResponse::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getData())->toBe(['key' => 'value']);
});

test('it makes a PATCH request', function () {
    $this->guzzleClient->shouldReceive('request')
        ->once()
        ->with('PATCH', '/test', Mockery::any())
        ->andReturn(new Response(200, [], json_encode(['key' => 'value'])));

    $response = $this->httpClient->patch('/test');

    expect($response)->toBeInstanceOf(ApiResponse::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getData())->toBe(['key' => 'value']);
});

test('it makes a DELETE request', function () {
    $this->guzzleClient->shouldReceive('request')
        ->once()
        ->with('DELETE', '/test', Mockery::any())
        ->andReturn(new Response(204, [], ''));

    $response = $this->httpClient->delete('/test');

    expect($response)->toBeInstanceOf(ApiResponse::class);
    expect($response->getStatusCode())->toBe(204);
    expect($response->getData())->toBeNull();
});

test('it adds the authorization header when a token is available', function () {
    $this->config->set('access_token', 'test-token');

    $this->guzzleClient->shouldReceive('request')
        ->once()
        ->withArgs(function ($method, $uri, $options) {
            return isset($options['headers']['Authorization']) &&
                $options['headers']['Authorization'] === 'Bearer test-token';
        })
        ->andReturn(new Response(200, [], json_encode(['key' => 'value'])));

    $this->httpClient->get('/test');
});

test('it converts json options to a body', function () {
    $jsonData = ['data' => 'value'];

    $this->guzzleClient->shouldReceive('request')
        ->once()
        ->withArgs(function ($method, $uri, $options) use ($jsonData) {
            return isset($options['body']) &&
                $options['body'] === json_encode($jsonData) &&
                !isset($options['json']);
        })
        ->andReturn(new Response(200, [], json_encode(['key' => 'value'])));

    $this->httpClient->post('/test', ['json' => $jsonData]);
});
