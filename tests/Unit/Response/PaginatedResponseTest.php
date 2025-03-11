<?php

use QurbanAli\PescheckWrapper\Response\PaginatedResponse;

test('it can be instantiated', function () {
    $response = new PaginatedResponse(10, 'next-url', 'prev-url', [1, 2, 3]);

    expect($response)->toBeInstanceOf(PaginatedResponse::class);
});

test('it returns the correct count', function () {
    $response = new PaginatedResponse(10, 'next-url', 'prev-url', [1, 2, 3]);

    expect($response->getCount())->toBe(10);
});

test('it returns the correct next url', function () {
    $response = new PaginatedResponse(10, 'next-url', 'prev-url', [1, 2, 3]);

    expect($response->getNext())->toBe('next-url');
});

test('it returns the correct previous url', function () {
    $response = new PaginatedResponse(10, 'next-url', 'prev-url', [1, 2, 3]);

    expect($response->getPrevious())->toBe('prev-url');
});

test('it returns the correct results', function () {
    $results = [1, 2, 3];
    $response = new PaginatedResponse(10, 'next-url', 'prev-url', $results);

    expect($response->getResults())->toBe($results);
});

test('it implements iterator aggregate', function () {
    $results = [1, 2, 3];
    $response = new PaginatedResponse(10, 'next-url', 'prev-url', $results);

    $collected = [];
    foreach ($response as $item) {
        $collected[] = $item;
    }

    expect($collected)->toBe($results);
});

test('it implements countable', function () {
    $results = [1, 2, 3];
    $response = new PaginatedResponse(10, 'next-url', 'prev-url', $results);

    expect(count($response))->toBe(count($results));
});
