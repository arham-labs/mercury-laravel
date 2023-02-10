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
use Exception;

class MailNotificationHandlerJob implements ShouldQueue
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
        Log::debug("###################### Notification Package Email Logs ###########################");

        Log::debug("Timestamp: " . strval(date("dS M, Y H:i:s")));

        Log::debug(config("al_notification_config.project_name"));
        Log::debug(config('al_notification_config.enable_notification'));
        #Check if email is enabled then notify emails in the config
        if (config('al_notification_config.enable_notification') === true) {
            Log::debug("\nEmail is enabled, now sending email notifications");

            Log::debug("\nBody:\n");

            $emailsToNotify = config('al_notification_config.notifiable_emails');

            Log::debug(json_encode($emailsToNotify));
            $this->notificationObject["subject"] = !empty($this->notificationObject["subject"]) ? $this->notificationObject["subject"] : "Api Error in " . config("al_notification_config.project_name") . "!";
            $this->notificationObject["view"] = !empty($this->notificationObject["view"]) ? $this->notificationObject["view"] : "mails.notification_handler_email";
            // dd($this->notificationObject);
            try {
                #Iterate and send emails
                foreach ($emailsToNotify as $email) {
                    #Send mail to the emails
                    $sendMail =  Mail::to($email)->send(new NotificationHandlerMail($this->notificationObject));
                }
            } catch (Exception $e) {
                #If Mail had any errors
                Log::debug("\nMail Errors:");
                Log::debug($e);
            }
        }

        Log::debug("##########################################################################");

        Log::debug("\n\n\n");

        return true;
    }
}
