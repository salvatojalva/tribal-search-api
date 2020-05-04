<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tribal\Services\DataHandler;
use Tribal\Interfaces\DataHandlerInterface;

class HttpDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->bind(DataHandlerInterface::class, DataHandler::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
