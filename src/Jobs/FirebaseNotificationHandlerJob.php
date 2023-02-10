<?php

namespace Arhamlabs\NotificationHandler\Jobs;

use Arhamlabs\NotificationHandler\Traits\FirebasePushNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class FirebaseNotificationHandlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FirebasePushNotificationTrait;

    protected $notificationObject;
    protected $tokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tokens, $notificationObject)
    {
        $this->tokens = $tokens;
        $this->notificationObject = $notificationObject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug("\n\n\n");
        Log::debug("###################### Notification Package Logs ###########################");

        Log::debug("Timestamp: " . strval(date("dS M, Y H:i:s")));

        #Check if firebase is enabled then notify the firebase channel
        if (config('al_notification_config.notification_type.firebase') === true) {
            Log::debug("Firebase is enabled, now sending firebase push notifications");
            $this->sendFirebasePushNotification($this->tokens, $this->notificationObject);
        } else
            Log::debug("Firebase is disabled");
        Log::debug("##########################################################################");

        Log::debug("\n\n\n");

        return true;
    }
}
