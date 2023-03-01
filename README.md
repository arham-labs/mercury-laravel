# Laravel Notification Package

This package will used for notifications.This package provides SLACL,EMAIL and FIREBASE Notification.

## Installation

In order to install the package use the command specified below

```bash
composer require arhamlabs/notification_handler

```
## Usage

### Mail
For mail notification in config file alNotificationConfig.php enable following flags

```bash
'enable_notification' => true,

'notification_type' => [
        'email' => true
    ],
````

Once confing is enable then simply dispact array object to the MailNotificationHandlerJob. 

Example-
```bash

use Arhamlabs\NotificationHandler\Jobs\MailNotificationHandlerJob;

 $body = array(
            /*errors you want to show*/
        );

        #Dispatch to job with the notification object
        $notificationObject = array();
        $notificationObject["body"] = json_encode($body);

        dispatch(new MailNotificationHandlerJob($notificationObject));

```


### Slack
For slack notification in config file alNotificationConfig.php enable following flags

```bash
'enable_notification' => true,

'notification_type' => [
        'slack' => true
    ],
````

Once confing is enable then simply dispact array object to the SlackNotificationHandlerJob. 

Example-
```bash

use Arhamlabs\NotificationHandler\Jobs\SlackNotificationHandlerJob;

 $body = array(
            /*errors you want to show*/
        );

        #Dispatch to job with the notification object
        $notificationObject = array();
        $notificationObject["body"] = json_encode($body);

        dispatch(new SlackNotificationHandlerJob($notificationObject));

```



### Firebase
For slack notification in config file alNotificationConfig.php enable following flags

```bash
'enable_notification' => true,

'notification_type' => [
        'firebase' => true
    ],
````

for firebase notification it requires firebase json file.Simply add path into .env file.There are two ways to define firebase json path.If you are using s3 bucket then use S3_FIREBASE_JSON_PATH otherwise you can provide json file into public folder with LOCAL_FIREBASE_JSON_PATH.


```bash

S3_FIREBASE_JSON_PATH='s3 bucket path'


=========OR============

LOCAL_FIREBASE_JSON_PATH='path from public directory'

Example-

LOCAL_FIREBASE_JSON_PATH='firebase/request.json'

````
For send push notification on user device you require firebase token.

Example -

``` bash


use Arhamlabs\NotificationHandler\Jobs\FirebaseNotificationHandlerJob;

 $tokens = [
            'token 1',
            'token 2',
            'token 3'
           ];
            $requestJson = [
                "title" => 'Test',
                "subtitle" => 'Testing',
                "data" => [
                    "redirection" => "/homeScreen",
                    "arguments" => json_encode(array(
                        'userUuid' => 'userUuid'
                    ))
                ]
            ];
            dispatch(new FirebaseNotificationHandlerJob($tokens, $requestJson));

```
