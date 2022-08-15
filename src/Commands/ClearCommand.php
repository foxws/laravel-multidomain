<?php

namespace Foxws\LaravelMultidomain\Commands;

use Foxws\LaravelMultidomain\Contracts\RepositoryInterface;
use Foxws\LaravelMultidomain\Support\DomainRepository;
use Illuminate\Console\Command;

class ClearCommand extends Command
{
    public $signature = 'multidomain:clear';

    public $description = 'Clear the domain repository cache.';

    public function handle(): void
    {
        /** @var DomainRepository $repository */
        $repository = app(RepositoryInterface::class);
        $repository->removeCached();

        $this->info('Domains cached cleared.');
    }
}
