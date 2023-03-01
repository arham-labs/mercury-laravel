<?php

namespace Arhamlabs\NotificationHandler\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

trait FirebasePushNotificationTrait
{
    public  function sendFirebasePushNotification($tokens, $requestJson)
    {
        //check firebase json path 
        if (!empty(config('alNotificationConfig.firebase.s3_path')) || !empty(config('alNotificationConfig.firebase.local_path'))) {

            Log::debug(config('alNotificationConfig.firebase'));
            //function will access json file either local path or S3 bucket
            if (!empty(config('alNotificationConfig.firebase.s3_path')))
                $factory = (new Factory)->withServiceAccount(Storage::disk('s3')->get(config('alNotificationConfig.firebase.s3_path')));
            else {
                $factory = (new Factory)->withServiceAccount(config('alNotificationConfig.firebase.local_path'));
            }

            $messaging  = $factory->createMessaging();
            foreach ($tokens as $token) {
                //this function will validate firebase token
                $result = $messaging->validateRegistrationTokens($token);
                if ($result['valid'] != []) {
                    Log::debug("Validate token successfully.");
                    //initialize notification object
                    $userMessages = CloudMessage::withTarget('token', $token)
                        ->withNotification(Notification::create(
                            $requestJson['title'],
                            $requestJson['subtitle']
                        ))
                        ->withData($requestJson['data']);

                    //send push notification
                    $responseData = $messaging->send($userMessages);
                    Log::debug("Firebase Notification Status:-");
                    Log::debug($responseData);
                } else
                    Log::debug("Invalid token");
            }
        } else
            Log::error("Firebase Json path missing");
    }
}
