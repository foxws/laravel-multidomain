<?php

namespace Foxws\MultiDomain\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearCommand extends Command
{
    public $signature = 'multidomain:clear';

    public $description = 'Clear the multidomain cache.';

    public function handle(): void
    {
        $this->clearCache();

        $this->info('Domain cache cleared.');
    }

    protected function clearCache(): bool
    {
        return Cache::forget('multidomain');
    }
}
