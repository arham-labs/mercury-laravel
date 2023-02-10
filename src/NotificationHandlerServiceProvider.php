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
                __DIR__ . '/config/al_notification_config.php' => config_path('al_notification_config.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/resources/views/mails' => resource_path('views/mails'),
            ], 'mails');
        }
    }
}
