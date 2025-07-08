<?php

namespace Nodev\Mutaku;

use Exception;

/**
 * Send request to Orderkuota API
 * Better don't use this class directly, please use ClientApi instead
 */

class ApiRequestor
{

    /**
     * Send GET request
     *
     * @param string $url
     * @param string $merchantId
     * @param mixed[] $data_hash
     * @return mixed
     * @throws Exception
     */
    public static function get($url, $data_hash)
    {
        return self::remoteCall($url, $data_hash, 'GET');
    }

    /**
     * Send POST request
     *
     * @param string $url
     * @param string $merchantId
     * @param mixed[] $data_hash
     * @return mixed
     * @throws Exception
     */
    public static function post($url, $data_hash)
    {
        return self::remoteCall($url, $data_hash, 'POST');
    }

    /**
     * Send PATCH request
     *
     * @param string $url
     * @param string $merchantId
     * @param mixed[] $data_hash
     * @return mixed
     * @throws Exception
     */
    public static function patch($url, $data_hash)
    {
        return self::remoteCall($url, $data_hash, 'PATCH');
    }

    /**
     * Actually send request to API server
     *
     * @param string $url
     * @param mixed[] $data_hash
     * @param bool $post
     * @return mixed
     * @throws Exception
     */
    public static function remoteCall($url, $data_hash, $method)
    {
        $ch = curl_init();

        $curl_options = array(
            CURLOPT_URL => $url,
            CURLOPT_TIMEOUT => 60,
        );

        // merging with Config::$curlOptions
        if (count(Config::$curlOptions)) {
            if (isset(Config::$curlOptions[CURLOPT_HTTPHEADER])) {
                $mergedHeaders = array_merge(
                    isset($curl_options[CURLOPT_HTTPHEADER]) ? $curl_options[CURLOPT_HTTPHEADER] : [],
                    Config::$curlOptions[CURLOPT_HTTPHEADER]
                );
                $headerOptions = array(CURLOPT_HTTPHEADER => $mergedHeaders);
            } else {
                $mergedHeaders = array();
                $headerOptions = array(CURLOPT_HTTPHEADER => $mergedHeaders);
            }
            $curl_options = array_replace_recursive($curl_options, Config::$curlOptions, $headerOptions);
        }

        if ($method == 'GET') {
            if (!empty($data_hash)) {
                $query = http_build_query($data_hash);
                $curl_options[CURLOPT_URL] = $url . '?' . $query;
            } else {
                $curl_options[CURLOPT_URL] = $url;
            }
        } elseif ($method == 'POST') {
            // Set Content-Type header for form-urlencoded
            $headers = isset($curl_options[CURLOPT_HTTPHEADER]) ? $curl_options[CURLOPT_HTTPHEADER] : [];
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $curl_options[CURLOPT_HTTPHEADER] = $headers;
            // Set POSTFIELDS as urlencoded string
            $curl_options[CURLOPT_POST] = true;
            $curl_options[CURLOPT_POSTFIELDS] = http_build_query($data_hash);
        } elseif ($method == 'PATCH') {
            $curl_options[CURLOPT_CUSTOMREQUEST] = 'PATCH';
            $curl_options[CURLOPT_POSTFIELDS] = http_build_query($data_hash);
            $headers = isset($curl_options[CURLOPT_HTTPHEADER]) ? $curl_options[CURLOPT_HTTPHEADER] : [];
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $curl_options[CURLOPT_HTTPHEADER] = $headers;
        }

        curl_setopt_array($ch, $curl_options);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        if ($result === false) {
            throw new Exception('CURL Error: ' . curl_error($ch), curl_errno($ch));
        } else {
            return $result;
        }
    }
}
