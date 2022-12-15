<?php

namespace Foxws\MultiDomain\Livewire;

use Livewire\LivewireManager as BaseLivewireManager;

class LivewireManager extends BaseLivewireManager
{
    public function getClass($alias)
    {
        $finder = app(LivewireComponentsFinder::class);

        $class = false;

        $class = $class ?: (
            // Let's first check if the user registered the component using:
            // Livewire::component('name', [Livewire component class]);
            // If not, we'll look in the auto-discovery manifest.
            $this->componentAliases[$alias] ?? $finder->find($alias)
        );

        $class = $class ?: (
            // If none of the above worked, our last-ditch effort will be
            // to re-generate the auto-discovery manifest and look again.
            $finder->buildWithPrefix()->find($alias)
        );

        return ! $class
            ? parent::getClass($alias)
            : $class;
    }
}
