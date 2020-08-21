<?php

namespace Larangular\WebServiceClient;

use Illuminate\Support\ServiceProvider;
use Larangular\WebServiceClient\Request\WSClientRequest;
use Larangular\WebServiceLogger\WebServiceLoggerServiceProvider;
use Larangular\WebServiceManager\WebServiceManagerServiceProvider;

class WebServiceClientServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        if ($this->app->environment('local')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->register(WebServiceManagerServiceProvider::class);
        $this->app->register(WebServiceLoggerServiceProvider::class);
        $this->app->singleton(WSClientRequest::class, static function () {
            return new WSClientRequest();
        });
    }


    public function provides() {
        return [
            WSClientRequest::class,
        ];
    }
}

