# Signed Request

Simple encoding/decoding of data and hash validation using a shared secret.

## Usage

### 1. Initialization

```php
// Composer autoloader
require_once 'vendor/autoload.php';

use nurtext\SignedRequest;

// Set a shared secret
SignedRequest::setSharedSecret('my shared secret');
```

### 2. Generating a signed request

```php
// Encode and hash the data
$signedRequest = SignedRequest::generate(array('hello' => 'world'));

// Debug output
var_dump($signedRequest);
```

### 3. Verifying a signed request

```php
// Just verify, don't return the parsed data
var_dump(SignedRequest::verify($signedRequest));
```

### 3. Verifying and parsing a signed request in a single call

```php
// Verify and return data if verification succeeds
var_dump(SignedRequest::parse($signedRequest));
```

## Requirements

- PHP >= 5.1.2
  - ext-json

## Additional information

- See [www.php.net/manual/en/function.hash-algos.php](http://www.php.net/manual/en/function.hash-algos.php) for a list of available algorithms
