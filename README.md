# SecureHash

Simple hash generation and validation using a shared secret.

## Usage example

### 1. Initialization

	<?php
	// Require class file
	require_once('libs/SecureHash.class.php');
	
	// Get the instance
	$SecureHash = SecureHash::getInstance();
	
	// Set the shared secret, algorithm and delimiter
	$SecureHash::setSharedSecret('328beab968f0faaec4c6bbd912aba013c929fd01');
	$SecureHash::SetAlgorithm('sha1');
	$SecureHash::setDelimiter('-');
	
	
### 2. Generating hashed data

	// Hashe the data and return as string
	$str = $SecureHash::generateHashedData(array('Hello' => 'World'));
	
	// Debug output
	var_dump($str);


### 3. Verifying hashed data

	// Just verify, don't return the parsed data
	var_dump($SecureHash::verifyHashedData($str));
	
### 3. Parsing hashed data directly

	// Like verifyHashedData() but returns the parsed data
	var_dump($SecureHash::parseHashedData($str));
	
	
## Requirements

- PHP 5.1.2

## Additional information

- See [www.php.net/manual/en/function.hash-algos.php](http://www.php.net/manual/en/function.hash-algos.php) for a list of available algorithms