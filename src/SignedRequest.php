<?php
namespace nurtext;

/**
 * Signed Request
 *
 * Simple encoding/decoding of data and hash validation using a shared secret
 *
 * @author Cedric Kastner <nurtext@me.com>
 */
class SignedRequest
{
    /**
     * @internal
     */
    static private $data = null;

    /**
     * @internal
     */
    static private $algorithm = 'sha256';

    /**
     * @internal
     */
    static private $delimiter = '-';

    /**
     * @internal
     */
    static private $sharedSecret = '';

    /**
     * @internal Forbid constructing
     */
    private function __construct() { }

    /**
     * @internal Forbid cloning
     */
    private function __clone() { }

    /**
     * Helper function for encoding url-safe base64
     *
     * @param string $string
     * @return string $result
     */
    static private function _base64UrlEncode($string)
    {
        return strtr(base64_encode($string), '+/=', '-_,');
    }

    /**
     * Helper function for decoding url-safe base64
     *
     * @internal
     * @param string $string
     * @return string $result
     */
    static private function _base64UrlDecode($string)
    {
        return base64_decode(strtr($string, '-_,', '+/='));
    }

    /**
     * Set the algorithm for calculating the hash
     *
     * @param string $algorithm Algorithm
     * @see http://www.php.net/manual/en/function.hash-algos.php for a list of all available algorithms
     */
    static public function setAlgorithm($algorithm)
    {
        self::$algorithm = $algorithm;
    }

    /**
     * Set the delimiter to distinguish hash from data
     *
     * @param string $delimiter Delimiter
     */
    static public function setDelimiter($delimiter)
    {
        self::$delimiter = $delimiter;
    }

    /**
     * Set a shared secret used for hashing
     *
     * @param string $sharedSecret Shared secret
     */
    static public function setSharedSecret($sharedSecret)
    {
        self::$sharedSecret = $sharedSecret;
    }

    /**
     * Generate a signed request
     *
     * @param mixed $data Data to encode and hash
     * @return string Signed request
     */
    static public function generate($data)
    {
        return hash_hmac(self::$algorithm, json_encode($data), self::$sharedSecret) .
               self::$delimiter .
               self::_base64UrlEncode(json_encode($data));
    }

    /**
     * Verify if the hash is good and data hasn't been altered
     *
     * @param string $signedRequest
     * @return bool Verification result
     */
    static public function verify($signedRequest)
    {
        try
        {
            // Explode the String into variables
            @list($signature, $payload) = @explode(self::$delimiter, $signedRequest, 2);

            // Validate the given signature against the one we create using the payload
            if ($signature && $payload && $signature === hash_hmac(self::$algorithm, self::_base64UrlDecode($payload), self::$sharedSecret))
            {
                // Store the data
                self::$data = json_decode(self::_base64UrlDecode($payload), true);

                // Data untaltered
                return TRUE;

            }

            // Data altered
            return FALSE;
        }
        catch (Exception $e)
        {
            return FALSE;
        }

    }

    /**
     * Parse signed request and return the decoded data
     *
     * @param string $signedRequest
     * @return mixed Data
     */
    static public function parse($signedRequest)
    {
        if (self::verify($signedRequest) === TRUE) return self::$data;
        return NULL;
    }

}
