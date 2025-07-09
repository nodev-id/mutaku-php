<?php

namespace Nodev\Mutaku;

use Illuminate\Support\ServiceProvider;

class MutakuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // publish config file
        $this->publishes([
            __DIR__ . '/../config/mutaku.php' => config_path('mutaku.php'),
        ], 'mutaku-config');
        
        // set to static class config
        Config::load(config('mutaku') ?? []);
    }
}