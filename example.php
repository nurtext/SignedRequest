<?php
// Require class file
require_once('libs/SecureHash.class.php');

// Get the instance
$SecureHash = SecureHash::getInstance();

// Set the shared secret, algorithm and delimiter
$SecureHash::setSharedSecret('328beab968f0faaec4c6bbd912aba013c929fd01');
$SecureHash::SetAlgorithm('sha1');
$SecureHash::setDelimiter('-');

// Hashe the data and return as string
$str = $SecureHash::generateHashedData(array('Hello' => 'World'));

// Debug output
var_dump($str);

// Just verify, don't return the parsed data
var_dump($SecureHash::verifyHashedData($str));

// Like verifyHashedData() but returns the parsed data
var_dump($SecureHash::parseHashedData($str));