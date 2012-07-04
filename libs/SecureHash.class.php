<?php
/**
 * SecureHash
 * 
 * Simple hash generation and validation using a shared secret
 *
 * @author	Cedric Kastner <cedric@nur-text.de>
 * @version	1.0.0
 */
class SecureHash
{
	// Stores the Singleton instance
	static private $instance	= NULL;
	
	// Holds the decoded data
	static private $data		= NULL;
	
	// Stores the shared secret
	static private $secret		= '';
	
	// Algorithm used for hashing the data
	static private $algorithm	= '';
	
	// Delimiter to separate hash from data
	static private $delimiter	= '';
	
	// Static class, forbid constructing/cloning
	private function __construct(){}
	private function __clone(){}
	
	// Get the current instance or create one if it doesn't exist
	static public function getInstance()
	{
		if (self::$instance === NULL)
		{
			self::$instance = new self;

		}
		
		return self::$instance;

	}
	
	// Sets the shared secret used for this session
	static public function setSharedSecret($str)
	{
		self::$secret = $str;
	
	}
	
	// Sets the used algorithm for hashing
	static public function setAlgorithm($str)
	{
		self::$algorithm = $str;
		
	}
	
	// Sets the delimiter
	static public function setDelimiter($str)
	{
		self::$delimiter = $str;
		
	}
		
	// Function to verify if the hash is good and data hasn't been altered
	static public function verifyHashedData($str)
	{
		try
		{
			// Explode the String into variables
			@list($signature, $payload) = @explode('-', $str, 2);
			
			// Validate the given signature against the one we create using the payload
			if ($signature && $payload && $signature === hash_hmac(self::$algorithm, self::base64UrlDecode($payload), self::$secret))
			{
				// Store the data
				self::$data = json_decode(self::base64UrlDecode($payload), TRUE);
				
				// Data untaltered
				return TRUE;
				
			}
			
			// Data altered
			return FALSE;
			
		}
		catch (Exception $e)
		{
			// Unknown error occured
			return FALSE;
			
		}
		
	}
	
	// Function to parse the verified hash and return the decoded data
	static public function parseHashedData($str)
	{
		if (self::verifyHashedData($str) === TRUE)
		{
			return self::$data;
			
		}
		
		return NULL;
		
	}
	
	// Function to generate and return the url-safe hash and data
	static public function generateHashedData($data)
	{
		return	hash_hmac(self::$algorithm, json_encode($data), self::$secret) . self::$delimiter . 
				self::base64UrlEncode(json_encode($data));
		
	}
	
	// Helper function for encoding url-safe BASE64
	static private function base64UrlEncode($str)
	{
		return strtr(base64_encode($str), '+/=', '-_,');
		
	}
	
	// Helper function for decoding url-safe BASE64
	static private function base64UrlDecode($str)
	{
		return base64_decode(strtr($str, '-_,', '+/='));
		
	}
	
}
