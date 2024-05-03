<?php

namespace Arhamlabs\NotificationHandler\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;

class SmsNotificationHandlerJob implements ShouldQueue
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
        Log::debug("###################### SMS Notification Package Logs ###########################");

        Log::debug("Timestamp: " . strval(date("dS M, Y H:i:s")));
        #Check if SMS is enabled then notify the SMS channel
        if (config('alNotificationConfig.enable_notification') === true) {
            if (config('alNotificationConfig.notification_type.sms') === true) {
                Log::debug("\nSMS is enabled, now sending SMS notifications");
                if (config('alNotificationConfig.sms_service_provider') === 'kaleyra') {
                    $apiKey = config('alNotificationConfig.kaleyra-sms.KALEYRA_API_KEY');
                    $senderId = config('alNotificationConfig.kaleyra-sms.KALEYRA_SENDER_ID');
                    $SID = config('alNotificationConfig.kaleyra-sms.KALEYRA_SID');

                    if (!empty($apiKey) && !empty($senderId) && !empty($SID)) {
                        Log::debug("\nSMS service initialize");
                        try {
                            $client = new GuzzleHttpClient();
                            $jsonData = [
                                'to' => $this->notificationObject['to'],
                                'sender' => $senderId,
                                'type' => 'OTP',
                                'body' => $this->notificationObject['body'],
                                'source' => 'API',
                            ];
                            if (!empty($this->notificationObject['sms_type']))
                                $jsonData['sms_type'] = $this->notificationObject['sms_type'];
                            if (!empty($this->notificationObject['template_id']))
                                $jsonData['template_id'] = $this->notificationObject['template_id'];
                            $response = $client->post('https://api.in.kaleyra.io/v1/' . $SID . '/messages', [
                                'json' => $jsonData,
                                'headers' => [
                                    'api-key' => $apiKey,
                                    'Content-Type' => 'application/json',
                                ],
                                'verify' => true, // Enable SSL verification
                            ]);
                            if (empty($response->error)) {
                                Log::error("SMS request send successfully:");
                            } else {
                                Log::error("Error SMS Send notification:");
                                Log::error($response->error);
                            }
                        } catch (Exception $e) {
                            Log::error("Error SMS Send notification:");
                            Log::error("Error Message: " . $e->getMessage());
                        }
                    } else {
                        Log::debug("SMS Send notification :Please provide kaleyra credentials");
                    }
                } else {
                    $account_sid = config('alNotificationConfig.twilio-sms.TWILIO_SID');
                    $auth_token =  config('alNotificationConfig.twilio-sms.TWILIO_TOKEN');
                    $twilio_number =  config('alNotificationConfig.twilio-sms.TWILIO_FROM');
                    if (!empty($account_sid) && !empty($auth_token) && !empty($twilio_number)) {
                        Log::debug("\nSMS service initialize");
                        try {
                            $client = new Client($account_sid, $auth_token);
                            $response = $client->messages->create($this->notificationObject['to'], [
                                'from' => $twilio_number,
                                'body' => $this->notificationObject['body']
                            ]);
                            if (empty($response->errorMessage)) {
                                Log::error("SMS send successfully:");
                            } else {
                                Log::error("Error SMS Send notification:");
                                Log::error("SMS Status:" . $response->status);
                                Log::error("Error Message:" . $response->errorMessage);
                            }
                        } catch (Exception $e) {
                            Log::error("Error SMS Send notification:");
                            Log::error("Error Message:" . $e->getMessage());
                        }
                    } else {
                        Log::debug("SMS Send notification :Please provide twilio credentials");
                    }
                }
            } else
                Log::debug(config("SMS Send notification is disable in config.For SMS notification allow notification_type['slack'] in config setting"));
        } else
            Log::debug(config("Send notification is disable in config.For send notification allow enable_notification in config setting"));


        Log::debug("##########################################################################");

        Log::debug("\n\n\n");

        return true;
    }
}
