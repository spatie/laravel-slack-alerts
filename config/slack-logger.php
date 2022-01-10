<?php

return [
    /*
     * The webhook URL that we'll use to send a message to Slack.
     */
    'webhook_url' => '',

    /*
     * This job will send the message to Slack. You can extend this
     * job to set timeouts, retries, etc...
     */
    'job' => Spatie\SlackLogger\Jobs\SendToSlackChannelJob::class,
];
