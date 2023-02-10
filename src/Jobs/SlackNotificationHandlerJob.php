<?php

namespace Arhamlabs\NotificationHandler\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

#Import Mail
use Illuminate\Support\Facades\Mail;
use Arhamlabs\NotificationHandler\Mail\NotificationHandlerMail;

class SlackNotificationHandlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationObject;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notificationObject)
    {
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
        #Check if slack is enabled then notify the slack channel
        if (config('al_notification_config.enable_notification') === true) {

            Log::debug("\nSlack is enabled, now sending slack notifications");

            $response = Http::withHeaders([
                'Content-type' => 'application/json',
            ])->post(config('al_notification_config.slack_webhook_url'), [
                'text' => $this->notificationObject["body"]
            ]);

            #If Http request failed to send message to slack log the message
            if (!$response->ok()) {
                Log::debug("Error encountered while sending slack message:\n");
                Log::debug($response->body());
            } else {
                Log::debug("Slack message sent successfully");
            }
        }

        Log::debug("##########################################################################");

        Log::debug("\n\n\n");

        return true;
    }
}
