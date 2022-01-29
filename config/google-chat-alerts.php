<?php

return [
    /*
     * The webhook URLs that we'll use to send a message to GoogleChat.
     */
    'webhook_urls' => [
        'default' => env('GOOGLE_CHAT_ALERT_WEBHOOK'),
    ],

    /*
     * This job will send the message to GoogleChat. You can extend this
     * job to set timeouts, retries, etc...
     */
    'job' => GuyWarner\GoogleChatAlerts\Jobs\SendToGoogleChatChannelJob::class,
];
