<?php

namespace Foxws\MultiDomain\Domains\Domain;

use Foxws\MultiDomain\Domains\Domain;

class MultiDomain extends Domain
{
    protected array $attributes = [];

    /** @var callable|null */
    protected $callableAttributes;

    public function path(string $path): self
    {
        $this->attributes(['path' => $path]);

        return $this;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}
