<?php

use QurbanAli\PESCheck\Client;
use QurbanAli\PESCheck\Config;
use QurbanAli\PESCheck\HttpClient\HttpClientInterface;
use QurbanAli\PESCheck\Resources\AuthResource;
use QurbanAli\PESCheck\Resources\ChecksResource;
use QurbanAli\PESCheck\Resources\DivisionsResource;
use QurbanAli\PESCheck\Resources\PackagesResource;
use QurbanAli\PESCheck\Resources\ScreeningsResource;
use Mockery;

beforeEach(function () {
    $this->httpClient = Mockery::mock(HttpClientInterface::class);
    $this->client = new Client([
        'email' => 'test@example.com',
        'password' => 'password',
    ], $this->httpClient);
});

afterEach(function () {
    Mockery::close();
});

test('it can be instantiated', function () {
    expect($this->client)->toBeInstanceOf(Client::class);
});

test('it creates a config instance', function () {
    expect($this->client->getConfig())->toBeInstanceOf(Config::class);
});

test('it returns the http client', function () {
    expect($this->client->getHttpClient())->toBe($this->httpClient);
});

test('it returns an auth resource', function () {
    expect($this->client->auth())->toBeInstanceOf(AuthResource::class);
});

test('it returns a packages resource', function () {
    expect($this->client->packages())->toBeInstanceOf(PackagesResource::class);
});

test('it returns a screenings resource', function () {
    expect($this->client->screenings())->toBeInstanceOf(ScreeningsResource::class);
});

test('it returns a divisions resource', function () {
    expect($this->client->divisions())->toBeInstanceOf(DivisionsResource::class);
});

test('it returns a checks resource', function () {
    expect($this->client->checks())->toBeInstanceOf(ChecksResource::class);
});

test('it reuses resource instances', function () {
    $auth1 = $this->client->auth();
    $auth2 = $this->client->auth();

    expect($auth1)->toBe($auth2);
});
