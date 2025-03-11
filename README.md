# PESCheck API PHP Wrapper

A PHP wrapper for the PESCheck API that simplifies integration with PESCheck services for background checks, identity verification, and candidate screening.

## Requirements

- PHP 8.2 or higher
- Composer

## Installation

```bash
composer require qurban-ali/pescheck-wrapper
```

## Usage

### Initialize the Client

```php
<?php

use QurbanAli\PescheckWrapper\Client;

// Basic initialization with default environment (staging)
$client = new Client([
    'email' => 'your-email',
    'password' => 'your-password',
]);

// Initialize with a specific environment
$client = new Client([
    'email' => 'your-email',
    'password' => 'your-password',
    'environment' => 'production', // Options: 'production', 'staging', 'testing', 'development'
]);

// Or specify a custom base URI
$client = new Client([
    'email' => 'your-email',
    'password' => 'your-password',
    'base_uri' => 'https://your-custom-api-endpoint.com/api'
]);
```

### Switching Environments

You can switch environments at runtime:

```php
// Switch to production
$client->getConfig()->setEnvironment('production');

// Switch to testing
$client->getConfig()->setEnvironment('testing');

// Get available environments
$environments = $client->getConfig()->getAvailableEnvironments();
// Returns: ['production', 'staging', 'testing', 'development']

// Add a custom environment
$client->getConfig()->addEnvironment('local', 'http://localhost:8000/api');
$client->getConfig()->setEnvironment('local');
```

### Authentication

```php
// Login to get access token (automatically stored in the client)
$authResponse = $client->auth()->login();

// If you need to refresh the token
$refreshResponse = $client->auth()->refresh();
```

### Working with Packages

```php
// Get all available packages
$packages = $client->packages()->all();

// Get a specific package
$package = $client->packages()->get('package-id');
```

### Managing Screenings

```php
// Create a new screening
$screening = $client->screenings()->create([
    'email' => 'john.doe@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'package_id' => 'package-uuid'
]);

// Get all screenings (with optional filters)
$allScreenings = $client->screenings()->all([
    'status' => 'completed',
    'page' => 1
]);

// Get a specific screening
$screening = $client->screenings()->get('screening-id');

// Get documents for a screening
$documents = $client->screenings()->getDocuments('screening-id');
```

### Divisions

```php
// Get all divisions
$divisions = $client->divisions()->all();
```

### Available Checks

```php
// Get all available checks
$checks = $client->checks()->all();
```

## Error Handling

The wrapper uses exceptions to handle errors:

```php
<?php

use QurbanAli\PescheckWrapper\Exception\AuthenticationException;
use QurbanAli\PescheckWrapper\Exception\RequestException;
use QurbanAli\PescheckWrapper\Exception\InvalidArgumentException;

try {
    $client->auth()->login();
    $screenings = $client->screenings()->all();
} catch (AuthenticationException $e) {
    // Handle authentication errors
    echo "Authentication failed: " . $e->getMessage();
} catch (RequestException $e) {
    // Handle API request errors
    echo "API request failed: " . $e->getMessage();
} catch (InvalidArgumentException $e) {
    // Handle invalid arguments
    echo "Invalid argument: " . $e->getMessage();
} catch (\Exception $e) {
    // Handle other exceptions
    echo "Error: " . $e->getMessage();
}
```

## Response Handling

All API responses are returned as arrays or objects with helpful methods:

```php
// Paginated responses implement IteratorAggregate and Countable
$screenings = $client->screenings()->all();

// Get total count
$total = $screenings->getCount();

// Iterate through results
foreach ($screenings as $screening) {
    echo $screening['id'] . "\n";
}

// Get all results as array
$results = $screenings->getResults();

// Check if there are more pages
$nextPageUrl = $screenings->getNext();
$previousPageUrl = $screenings->getPrevious();
```

## Testing

This package uses [Pest PHP](https://pestphp.com/) for testing. To run the tests:

```bash
composer test
```

Or directly:

```bash
./vendor/bin/pest
```

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
