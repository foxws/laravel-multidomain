<?php

namespace Foxws\MultiDomain\Domains;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

abstract class Domain implements Arrayable
{
    final public function __construct()
    {
    }

    public static function new(): static
    {
        $instance = new static();

        return $instance;
    }

    public function attribute(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->attributes, $key, $default);
    }

    public function attributes(array|callable $attributes): self
    {
        if (is_callable($attributes)) {
            $this->callableAttributes = $attributes;
        }

        if (is_array($attributes)) {
            $this->attributes = array_merge($this->attributes, $attributes);
        }

        return $this;
    }

    public function __get(string $key): mixed
    {
        return $this->attribute($key);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function __isset(string $key): bool
    {
        return isset($this->attributes[$key]);
    }
}
