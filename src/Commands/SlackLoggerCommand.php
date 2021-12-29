<?php

namespace Spatie\SlackLogger\Commands;

use Illuminate\Console\Command;

class SlackLoggerCommand extends Command
{
    public $signature = 'laravel-slack-logger';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
