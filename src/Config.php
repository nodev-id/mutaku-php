<?php

namespace Nodev\Mutaku;

use Dotenv\Dotenv;

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
     * Orderkuota API URL
     * 
     * @static
     */
    public static $serverUrl;

    /**
     * Get baseUrl
     * 
     * @return string Orderkuota API URL
     */
    public static function getBaseUrl()
    {
        return self::$serverUrl . "/v2/get";
    }

    /**
     * Load environment variables from .env file
     * 
     * @param string|null $path Path to the directory containing the .env file (default: current directory)
     * @return void
     */
    public static function initialize($path = null)
    {
        $path = $path ?: dirname(__DIR__, 1);
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();

        static::load($_ENV);
    }

    public static function load(array $config)
    {
        static::$authToken = $config['authToken'] ?? null;
        static::$serverUrl = $config['serverUrl'] ?? null;
        static::$accountUsername = $config['accountUsername'] ?? null;
    }
}
