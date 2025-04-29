<?php

use Spatie\SlackAlerts\Facades\SlackAlert;

beforeEach(function () {
    SlackAlert::fake();
});

it('can get the sent messages', function () {
    SlackAlert::message('hey');

    $sentMessages = SlackAlert::sentMessages();

    expect($sentMessages)->toHaveCount(1);
    expect($sentMessages[0])->toHaveKeys([
        'webhookUrl',
        'channel',
        'username',
        'icon_emoji',
        'icon_url',
        'text'
    ]);
    expect($sentMessages[0]['text'])->toBe('hey');
});

it('can get the sent blocks', function () {
    SlackAlert::blocks(['my block']);

    $sentMessages = SlackAlert::sentMessages();

    expect($sentMessages)->toHaveCount(1);
    expect($sentMessages[0])->toHaveKeys([
        'webhookUrl',
        'channel',
        'username',
        'icon_emoji',
        'icon_url',
        'blocks'
    ]);
    expect($sentMessages[0]['blocks'])->toBe(['my block']);
});

it('can determine that no messages were sent', function () {
    SlackAlert::expectNoMessagesSent();
});

it('will fail when messages were sent when none were expected', function () {
    SlackAlert::message('hey');

    SlackAlert::expectNoMessagesSent();
})->fails();


it('can determine that messages were sent', function () {
    SlackAlert::message('hey');

    SlackAlert::expectMessagesSent();
});

it('will fail when no messages were sent when some were expected', function () {
    SlackAlert::expectMessagesSent();
})->fails();

it('can determine that a message was sent containing a string', function () {
    SlackAlert::message('hey there');

    SlackAlert::expectMessageSentContaining('there');
});

it('will fail when a message was not sent containing a string', function () {
    SlackAlert::message('hey there');

    SlackAlert::expectMessageSentContaining('something else');
})->fails();

it('will fail when expecting a certain message and none were sent', function () {
    SlackAlert::expectMessageSentContaining('something else');
})->fails();

//

it('can determine that a block was sent containing a string', function () {
    SlackAlert::blocks(['key' => 'hey there']);

    SlackAlert::expectMessageSentContaining('there');
});

it('will fail when a block was not sent containing a string', function () {
    SlackAlert::blocks(['key' => 'hey there']);

    SlackAlert::expectMessageSentContaining('something else');
})->fails();

it('will not use the keys of the block when searching the substring', function() {
    SlackAlert::blocks(['key' => 'hey there']);

    SlackAlert::expectMessageSentContaining('key');
})->fails();
