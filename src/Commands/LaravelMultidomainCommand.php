<?php

namespace Foxws\LaravelMultidomain\Commands;

use Illuminate\Console\Command;

class LaravelMultidomainCommand extends Command
{
    public $signature = 'laravel-multidomain';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
