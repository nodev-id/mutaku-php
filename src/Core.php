<?php

namespace Nodev\Mutaku;

use Exception;
use Nodev\Mutaku\ApiRequestor;
use Nodev\Mutaku\Config;
use Nodev\Mutaku\Helper;

class Core
{


    /**
     * Get mutation data from API
     * 
     * @param string|null $fromDate Format: d-m-Y (default: 30 days ago)
     * @param string|null $toDate Format: d-m-Y (default: today)
     * @param int $page Page number for pagination (default: 1)
     * @param bool $filterOut Whether to display outgoing or incoming transactions (default: false)
     * @return array
     * @throws Exception
     */
    public static function getMutations($fromDate = null, $toDate = null, $page = 1, $filterOut = false)
    {
        $url = Config::getBaseUrl();

        $fromDate = $fromDate ?: date('d-m-Y', strtotime('-30 days'));
        $toDate = $toDate ?: date('d-m-Y');

        Helper::validateDate($fromDate);
        Helper::validateDate($toDate);

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

            if ($filterOut) {
                $responseArray['qris_history']['results'] = Helper::filterOut($responseArray['qris_history']['results']);
            }

            return [
                'error' => false,
                'date' => $fromDate . ' to ' . $toDate,
                'filter_out' => $filterOut,
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
     * @return array
     * @throws Exception
     */
    public static function getImage()
    {
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
     * @return array
     * @throws Exception
     */
    public static function getBalance()
    {
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
