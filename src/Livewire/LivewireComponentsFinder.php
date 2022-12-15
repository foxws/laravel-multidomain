<?php

namespace Foxws\MultiDomain\Livewire;

use Illuminate\Filesystem\Filesystem;
use Livewire\LivewireComponentsFinder as baseLivewireComponentsFinder;

class LivewireComponentsFinder extends baseLivewireComponentsFinder
{
    protected string $prefix;

    public function __construct(Filesystem $files, string $manifestPath, string $path, string $prefix)
    {
        parent::__construct($files, $manifestPath, $path);

        $this->prefix = $prefix;
    }

    public function buildWithPrefix(): self
    {
        $this->manifest = $this->getClassNames()
            ->mapWithKeys(fn (string $class) => [$this->getName($class, $this->prefix) => $class])
            ->toArray();

        $this->write($this->manifest);

        return $this;
    }

    protected function getName(string $class): string
    {
        $name = str($class)
            ->replaceFirst(config('livewire.class_namespace'), '')
            ->replaceLast(class_basename($class), str(class_basename($class))->kebab())
            ->replace('\\', '.')
            ->lower()
            ->after('controllers.')
            ->after('resources.components.')
            ->prepend("{$this->prefix}.")
            ->trim('.');

        return $name;
    }
}
