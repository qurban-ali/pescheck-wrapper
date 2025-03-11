<?php

use QurbanAli\PescheckWrapper\Response\ApiResponse;

test('it can be instantiated', function () {
    $response = new ApiResponse(200, ['key' => 'value'], ['Content-Type' => 'application/json']);
    expect($response)->toBeInstanceOf(ApiResponse::class);
});

test('it returns the correct status code', function () {
    $response = new ApiResponse(200, ['key' => 'value'], ['Content-Type' => 'application/json']);

    expect($response->getStatusCode())->toBe(200);
});

test('it returns the correct data', function () {
    $data = ['key' => 'value'];
    $response = new ApiResponse(200, $data, ['Content-Type' => 'application/json']);

    expect($response->getData())->toBe($data);
});

test('it returns the correct headers', function () {
    $headers = ['Content-Type' => 'application/json'];
    $response = new ApiResponse(200, ['key' => 'value'], $headers);

    expect($response->getHeaders())->toBe($headers);
});

test('it correctly identifies successful responses', function () {
    $successCodes = [200, 201, 204, 299];

    foreach ($successCodes as $code) {
        $response = new ApiResponse($code, null, []);
        expect($response->isSuccessful())->toBeTrue();
    }
});

test('it correctly identifies unsuccessful responses', function () {
    $failureCodes = [100, 199, 300, 400, 500];

    foreach ($failureCodes as $code) {
        $response = new ApiResponse($code, null, []);
        expect($response->isSuccessful())->toBeFalse();
    }
});

