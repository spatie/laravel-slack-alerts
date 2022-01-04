<?php

use Illuminate\Support\Facades\Bus;
use Spatie\SlackLogger\Jobs\SendToSlackChannelJob;
use Spatie\SlackLogger\SlackLogger;

it('can dispatch a job', function () {
    Bus::fake();

    SlackLogger::display('test-data');

    Bus::assertDispatched(SendToSlackChannelJob::class);
});
