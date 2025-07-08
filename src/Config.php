<?php

namespace Nodev\Mutaku;

/**
 * Orderkuota Configuration
 */
class Config
{

    /**
     * Your Orderkuota app authToken
     * 
     * @static
     */
    public static $authToken;

    /**
     * Orderkuota Username
     * 
     * @static
     */
    public static $accountUsername;

    /**
     * Default options for every request
     * 
     * @static
     */
    public static $curlOptions = array();

    /**
     * Orderkuota API URL
     * 
     * @static
     */
    public static $serverUrl;

    const TRANSACTION_BASE_URL = 'https://app.orderkuota.com/api';

    /**
     * Get baseUrl
     * 
     * @return string Orderkuota API URL
     */
    public static function getBaseUrl()
    {
        return Config::$serverUrl . "/v2/get";
    }

    public static function load(array $config)
    {
        self::$authToken = $config['authToken'] ?? null;
        self::$serverUrl = $config['serverUrl'] ?? null;
        self::$accountUsername = $config['accountUsername'] ?? null;
    }
}
