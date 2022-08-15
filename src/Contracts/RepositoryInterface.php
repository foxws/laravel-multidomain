<?php

namespace Foxws\LaravelMultidomain\Contracts;

use Foxws\LaravelMultidomain\Domain;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    public function register(): void;

    public function boot(): void;

    public function allEnabled(): array;

    public function allDisabled(): array;

    public function all(): array;

    public function getCached(): array;

    public function toCollection(): Collection;

    public function find(string $name): ?Domain;

    public function findOrFail(string $name): Domain;
}
