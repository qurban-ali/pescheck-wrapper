<?php

use QurbanAli\PESCheck\Config;
use QurbanAli\PESCheck\Exception\InvalidArgumentException;

test('it can be instantiated with valid config', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    expect($config)->toBeInstanceOf(Config::class);
});

test('it throws an exception when required keys are missing', function () {
    expect(fn() => new Config([]))->toThrow(InvalidArgumentException::class);
    expect(fn() => new Config(['email' => 'test@example.com']))->toThrow(InvalidArgumentException::class);
    expect(fn() => new Config(['password' => 'password']))->toThrow(InvalidArgumentException::class);
});

test('it sets default values', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    expect($config->get('base_uri'))->toBe('https://api-staging.pescheck.io/api');
    expect($config->get('timeout'))->toBe(30);
    expect($config->get('access_token'))->toBeNull();
    expect($config->get('refresh_token'))->toBeNull();
});

test('it can get a config value', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    expect($config->get('email'))->toBe('test@example.com');
    expect($config->get('password'))->toBe('password');
});

test('it returns null for non-existent config keys', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    expect($config->get('non_existent'))->toBeNull();
});

test('it can set a config value', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $config->set('new_key', 'new_value');

    expect($config->get('new_key'))->toBe('new_value');
});

test('it can update an existing config value', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $config->set('email', 'new@example.com');

    expect($config->get('email'))->toBe('new@example.com');
});

test('it can be initialized with a specific environment', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
        'environment' => 'production',
    ]);

    expect($config->get('base_uri'))->toBe('https://api.pescheck.io/api');
});

test('it can switch environments', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    // Default is staging
    expect($config->get('base_uri'))->toBe('https://api-staging.pescheck.io/api');

    // Switch to production
    $config->setEnvironment('production');
    expect($config->get('base_uri'))->toBe('https://api.pescheck.io/api');

    // Switch to testing
    $config->setEnvironment('testing');
    expect($config->get('base_uri'))->toBe('https://api-testing.pescheck.io/api');
});

test('it throws an exception when setting an invalid environment', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    expect(fn() => $config->setEnvironment('invalid'))->toThrow(InvalidArgumentException::class);
});

test('it can add a custom environment', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $config->addEnvironment('local', 'http://localhost:8000/api');
    $config->setEnvironment('local');

    expect($config->get('base_uri'))->toBe('http://localhost:8000/api');
});

test('it can get available environments', function () {
    $config = new Config([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $environments = $config->getAvailableEnvironments();

    expect($environments)->toContain('production');
    expect($environments)->toContain('staging');
    expect($environments)->toContain('testing');
    expect($environments)->toContain('development');
});
