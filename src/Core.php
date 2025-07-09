<?php

namespace Nodev\Mutaku;

use Exception;
use Nodev\Mutaku\ApiRequestor;
use Nodev\Mutaku\Config;

class Core
{
    /**
     * Ensure configuration is loaded
     * 
     * @return void
     */
    private static function ensureConfigLoaded()
    {
        if (!Config::$authToken || !Config::$accountUsername || !Config::$serverUrl) {
            Config::load(config('mutaku') ?? []);
        }
    }

    /**
     * Validate date format (d-m-Y)
     * 
     * @param string $date
     * @return bool
     */
    private static function validateDate($date)
    {
        $d = \DateTime::createFromFormat('d-m-Y', $date);
        $formatDate =  $d && $d->format('d-m-Y') === $date;

        if (!$formatDate) {
            throw new Exception("Invalid date format. Use 'd-m-Y' format (e.g., 31-12-2024).");
        }

        return $formatDate;
    }

    /**
     * Get mutation data from API
     * 
     * @param string|null $fromDate Format: d-m-Y (default: 30 days ago)
     * @param string|null $toDate Format: d-m-Y (default: today)
     * @param int $page Page number for pagination (default: 1)
     * @return array
     * @throws Exception
     */
    public static function getMutations($fromDate = null, $toDate = null, $page = 1)
    {
        self::ensureConfigLoaded();
        $url = Config::getBaseUrl();
        
        $fromDate = $fromDate ?: date('Y-m-d', strtotime('-30 days'));
        $toDate = $toDate ?: date('d-m-Y');

        self::validateDate($fromDate);
        self::validateDate($toDate);

        $data_hash = [
            'requests' => [
                'qris_history' => [
                    'keterangan' => '',
                    'jumlah' => '',
                    'page' => $page,
                    'dari_tanggal' => $fromDate,
                    'ke_tanggal' => $toDate
                ],
            ],
            'auth_username' => Config::$accountUsername,
            'auth_token' => Config::$authToken,
        ];

        try {
            $response = ApiRequestor::post($url, $data_hash);
            $responseArray = json_decode($response, true);

            if (isset($responseArray['success']) && $responseArray['success'] !== true) {
                throw new Exception($responseArray['message'] ?? 'Unknown error occurred');
            }

            return [
                'error' => false,
                'date' => $fromDate . ' to ' . $toDate,
                'data' => $responseArray,
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get QRIS image from API
     * 
     * @return string
     * @throws Exception
     */
    public static function getImage()
    {
        self::ensureConfigLoaded();
        $url = Config::getBaseUrl();

        $data_hash = [
            'requests' => [
                0 => 'account'
            ],
            'auth_username' => Config::$accountUsername,
            'auth_token' => Config::$authToken,
        ];

        try {
            $response = json_decode(ApiRequestor::post($url, $data_hash), true);
            $imageUrl = $response['account']['results']['qris'] ?? null;

            if (!$imageUrl) {
                return [
                    'error' => true,
                    'message' => 'QRIS image not found',
                ];
            }

            return [
                'error' => false,
                'image_url' => $imageUrl,
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get balance from API
     * 
     * @return string
     * @throws Exception
     */
    public static function getBalance()
    {
        self::ensureConfigLoaded();
        $url = Config::getBaseUrl();
        
        $data_hash = [
            'requests' => [
                0 => 'account'
            ],
            'auth_username' => Config::$accountUsername,
            'auth_token' => Config::$authToken,
        ];

        try {
            $response = json_decode(ApiRequestor::post($url, $data_hash), true);

            if (isset($response['success']) && $response['success'] !== true) {
                throw new Exception($response['message'] ?? 'Unknown error occurred');
            }

            $balance = $response['account']['results']['qris_balance_str'] ?? null;

            if (!$balance) {
                return [
                    'error' => true,
                    'message' => 'Balance not found',
                ];
            }

            return [
                'error' => false,
                'balance' => $balance,
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }
}
