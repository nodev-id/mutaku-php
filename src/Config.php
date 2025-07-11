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
     * Default: https://app.orderkuota.com/api
     */
    const TRANSACTION_BASE_URL = 'https://app.orderkuota.com/api';

    /**
     * Get baseUrl
     * 
     * @return string Orderkuota API URL
     */
    public static function getBaseUrl()
    {
        return static::TRANSACTION_BASE_URL . "/v2/get";
    }

    /**
     * Default options for every request
     * 
     * @static
     */
    public static $curlOptions = array();

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

        static::load($_ENV, true);
    }

    /**
     * Load configuration from an array
     * 
     * @param array $config Configuration array
     * @param bool $env If true, load from environment variables (default: false)
     * @return void
     */
    public static function load(array $config, bool $env = false)
    {
        if ($env) {
            static::$authToken = $config['ORDERKUOTA_AUTH_TOKEN'] ?? null;
            static::$accountUsername = $config['ORDERKUOTA_ACCOUNT_USERNAME'] ?? null;
        } else {
            static::$authToken = $config['authToken'] ?? null;
            static::$accountUsername = $config['accountUsername'] ?? null;
        }
    }
}
