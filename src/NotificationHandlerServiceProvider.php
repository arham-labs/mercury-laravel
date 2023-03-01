<?php

namespace Arhamlabs\NotificationHandler;

use Illuminate\Support\ServiceProvider;

class NotificationHandlerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/alNotificationConfig.php' => config_path('alNotificationConfig.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/resources/views/mails' => resource_path('views/mails'),
            ], 'mails');
        }
    }
}
