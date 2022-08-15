<?php

namespace Foxws\LaravelMultidomain\Commands;

use Foxws\LaravelMultidomain\Contracts\RepositoryInterface;
use Foxws\LaravelMultidomain\Support\DomainRepository;
use Illuminate\Console\Command;

class CacheCommand extends Command
{
    public $signature = 'multidomain:cache';

    public $description = 'Cache domain repository.';

    public function handle(): void
    {
        /** @var DomainRepository $repository */
        $repository = app(RepositoryInterface::class);
        $repository->getCached();

        $this->info('Domains are cached.');
    }
}
