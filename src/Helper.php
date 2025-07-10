<?php

namespace Nodev\Mutaku;

use Exception;

class Helper
{
    /**
     * Validate date format (d-m-Y)
     * 
     * @param string $date
     * @return bool
     */
    public static function validateDate($date)
    {
        $d = \DateTime::createFromFormat('d-m-Y', $date);
        $formatDate =  $d && $d->format('d-m-Y') === $date;

        if (!$formatDate) {
            throw new Exception("Invalid date format. Use 'd-m-Y' format (e.g., 31-12-2024).");
        }

        return $formatDate;
    }

    public static function filterOut($array)
    {
        return array_filter($array, function ($item) {
            return $item['status'] === 'IN';
        });
    }
}
