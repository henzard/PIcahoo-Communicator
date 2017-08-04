<?php

namespace Picahoo\Communicator;

use Illuminate\Support\ServiceProvider;

class CommunicatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('picahoo-communicator', function(){
            return new Communicator();
        });
    }
}
