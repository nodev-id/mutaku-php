<?php

namespace Nodev\Mutaku;

use Exception;
use Nodev\Mutaku\ApiRequestor;
use Nodev\Mutaku\Config;

class Core
{
    public static function test(){
        dd(Config::$authToken);
    }

}