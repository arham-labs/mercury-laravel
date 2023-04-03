<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Project Name
    |--------------------------------------------------------------------------
    |
    | Specify the project name for the package
    |
    */

    'project_name' => env("APP_NAME", "Laravel Project"),

    /*
    |--------------------------------------------------------------------------
    | Toggle Notification Service
    |--------------------------------------------------------------------------
    |
    | This is the flag to set if the package should enable developer
    | notification. If set to true, then we may need to enable jobs 
    | on the server in order to receive notifications.
    |
    */

    'enable_notification' => false,

    /*
    |--------------------------------------------------------------------------
    | Enable types of notification required
    |--------------------------------------------------------------------------
    |
    | Note: 'enable_notification' flag needs to be true for this config to work
    |
    | This config is used to toggle types of notification required by the 
    | developer. Set the flag to true to whichever type of notification 
    | you may need to receive notifications.
    |
    | Also, you need to have the credentials present in the env for the
    | notifications to work.
    |
    */

    'notification_type' => [
        'email' => false,
        'slack' => false,
        'firebase' => false,
        'sms' => false
    ],

    /*
    |--------------------------------------------------------------------------
    | List of Emails to receive notifications
    |--------------------------------------------------------------------------
    |
    | If you have enabled email notifications for the user you can add the list
    | emails for the developers who are working on the project
    |
    */

    'notifiable_emails' => [],

    /*
    |--------------------------------------------------------------------------
    | Slack Webhook URL
    |--------------------------------------------------------------------------
    |
    | If you have enabled slack notifications for the user need to add slack
    | webhook url in the env which can be accessed by this config
    |
    | Note: For this we need to add an env variable SLACK_WEBHOOK_URL which
    | has the webhook url for the slack channel
    |
    */

    'slack_webhook_url' => env('SLACK_WEBHOOK_URL', null),


    /*
    |--------------------------------------------------------------------------
    | Firebase Push Notification
    |--------------------------------------------------------------------------
    |
    | If you have enabled firebase notifications for the user then it required firebase json 
    | file to access firebase.You can provide this json via s3 or local storage.
    |
    */
    'firebase' => [
        's3_path' => env('S3_FIREBASE_JSON_PATH', null),
        'local_path' => public_path(env('LOCAL_FIREBASE_JSON_PATH'))
    ],


    /*
    |--------------------------------------------------------------------------
    | SMS Push Notification
    |--------------------------------------------------------------------------
    |
    | If you have enabled sms notifications for the user then it required followings keys to access twilio sms service.
      Update env file with these key.
    |
    */
    'twilio-sms' => [
        'TWILIO_SID' => env('TWILIO_SID', null),
        'TWILIO_TOKEN' => env('TWILIO_TOKEN', null),
        'TWILIO_FROM' => env('TWILIO_FROM', null)
    ],
];
